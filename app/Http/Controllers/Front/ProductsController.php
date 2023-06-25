<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use Session;
use App\Cart;
use Auth;

class ProductsController extends Controller
{
    public function listing(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $url = $data['url'];
            $categoryCount = Category::where(['url'=>$url, 'status'=>1])->count();
            if($categoryCount>0){
                $categoryDetails = Category::catDetails($url);

                $categoryProducts = Product::whereIn('category_id', $categoryDetails['catIds'])->
                where('status', 1);
                // echo "<pre>"; print_r($categoryDetails); die;
                // echo "<pre>"; print_r($categoryProducts); die;

                //if sort option selected by user
                if(isset($data['sort']) && !empty($data['sort'])){
                    if($data['sort']=="product_latest"){
                        $categoryProducts->orderBy('id', 'Desc');
                    }else if($data['sort']=="product_name_a_z"){
                        $categoryProducts->orderBy('product_name', 'Asc');
                    }else if($data['sort']=="product_name_z_a"){
                        $categoryProducts->orderBy('product_name', 'Desc');
                    }else if($data['sort']=="price_lowest"){
                        $categoryProducts->orderBy('product_price', 'Asc');
                    }else if($data['sort']=="price_highest"){
                        $categoryProducts->orderBy('product_price', 'Desc');
                    }
                }else{
                    $categoryProducts->orderBy('id', 'Desc');
                }
                    $categoryProducts=$categoryProducts->simplePaginate(30);



                return view('front.products.ajax_products_listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
            }else{
                abort(404);
            }
        }else{
           $url = Route::getFacadeRoot()->current()->uri();
            $categoryCount = Category::where(['url'=>$url, 'status'=>1])->count();
        if($categoryCount>0){
            $categoryDetails = Category::catDetails($url);

            $categoryProducts = Product::whereIn('category_id', $categoryDetails['catIds'])->
            where('status', 1);
            // echo "<pre>"; print_r($categoryDetails); die;
            // echo "<pre>"; print_r($categoryProducts); die;
                $categoryProducts=$categoryProducts->simplePaginate(30);
            return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
        }else{
            abort(404);
            }
        }
    }

    public function detail($id){
        $productDetails = Product::with('category','attributes')->find($id)->toArray();

         $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');
         $relatedProducts = Product::where ('category_id', $productDetails['category']['id'])->where('id','!=',$id)->limit(3)->
         inRandomOrder()->get()->toArray();
        //   dd($relatedProducts); die;
        return view('front.products.detail', compact('productDetails', 'total_stock','relatedProducts'));
    }

    public function addtocart(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            // Check if stock is available
            $getProductStock = ProductsAttribute::where('product_id', $data['product_id'])->first();

            if ($getProductStock) {
                $getProductStock = $getProductStock->toArray();

                if ($getProductStock['stock'] <$data['quantity']) {
                    $message = "The wanted quantity is unavailable!";
                    session::flash('error_message', $message);
                    return redirect()->back();
                }
            } else {
                // Handle the case where the record is not found
                $message = "Product not found!";
                session::flash('error_message', $message);
                return redirect()->back();
            }
           // Generate session id if not available
            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

           // Check if the product is already added in the user's cart
            if (Auth::check()) {
                // User is logged in
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'user_id' => Auth::user()->id])->count();
            } else {
                // User is logged out
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'session_id' => Session::get('session_id')])->count();
            }

            if ($countProducts > 0) {
                $message = "The desired product already exists in the cart!";
                session::flash('error_message', $message);
                return redirect()->back();
            }

            if (Auth::check()){
                $user_id= Auth::user()->id;
            }else{
                $user_id=0;
            }

            // Save the product in the cart
            $cart = new Cart;
            $cart->session_id = $session_id;
            $cart->user_id = $user_id;
            $cart->product_id = $data['product_id'];
            $cart->quantity = $data['quantity'];
            $cart->save();

            $message = "The chosen product has been added to the cart!";
            session::flash('success_message', $message);
            return redirect('cart');
        }
    }

    public function cart(){
        $userCartItems =  Cart::userCarItems();
        //$cartProduct = Cart::with('product')->get()->toArray();
        //echo "<pre>"; print_r($cartProduct); die;
        return view ('front.products.cart')->with(compact('userCartItems'));
    }
    public function updateCartItemQty(Request $request){
        if ($request->ajax()){
            $data=$request->all();

            //get cart details
            $cartDetails= Cart::find($data['cartid']);

            //get available product stock

            $availableStock = ProductsAttribute::select('stock')->where(['product_id'=>$cartDetails['product_id']])->first()->toArray();

            // echo "Wanted Stock: ".$data['qty'];
            // echo "<br>";
            // echo "Available Stock: ".$availableStock['stock']; die;

            //check if the stock is available
            if($data['qty']>$availableStock['stock']){
                $userCartItems=Cart::userCarItems();
                return response()->json([
                    'status'=>false,
                    'message'=>'The wanted product stock is unavailable!',
                    'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
                ]);
            }

            // echo "<pre>"; print_r($data); die;
         Cart::where ('id',$data['cartid'])->update(['quantity'=>$data['qty']]);
         $userCartItems=Cart::userCarItems();
         return response()->json([
            'status'=>true,
            'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
        ]);
        }
    }

    public function deleteCartItem(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            Cart::where('id',$data['cartid'])->delete();
            $userCartItems = Cart::userCarItems();
            return response()->json([
                'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
            ]);

        }
    }
}
