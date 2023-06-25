<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductsAttribute;
use App\Product;
use App\Section;
use App\Category;
use Session;
use Image;

class ProductsController extends Controller
{
    public function products(){
        Session::put('page', 'products');
        $products = Product::with(['category'=>function($query){
            $query->select('id', 'category_name')->from('categories');
        },'section'=>function($query){
            $query->select('id', 'name')->from('sections');
        }])->get();

        // dd($products);
        // $products = Product::with(['category','section'])->get();

        // $products = json_decode(json_encode($products));
        // echo "<pre>"; print_r($products); die;

        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request){
        if($request->ajax()){
         $data =$request->all();
            //echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active"){
             $status = 0;
            }else{
             $status = 1;
            }

            Product ::where('id',$data['product_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'product_id'=>$data['product_id']]);
            }
         }

         public function deleteProduct($id){

            // to delete the product
            Product::where('id', $id)->delete();

            $message = 'Product is deleted successfully!';
            session::flash('success_message',$message);
            return redirect()->back();
        }

        public function addEditProduct(Request $request, $id=null){
            if($id==""){
                $title = "Add Product";
                $product = new Product;
                $productData = array();
                $message = "Product has been added successfully!";
            }else{
                $title = "Edit Product";
                $productData = Product::find($id);
                $productData = json_decode(json_encode($productData), true);
                // echo "<pre>"; print_r($productData); die;
                $product = Product ::find($id);
                $message = "Product has been updated successfully!";
            }

            if($request->isMethod('post')){
                $data = $request->all();
                /*echo "<pre>"; print_r($data); die;*/

                 //validations for product
                 $rules = [
                    //To make the name only be in alphabet and no numeric formula regex:/^[\pL\s\-]+$/u
                    'category_id'=> 'required',
                    'product_name'=> 'required|regex:/^[\pL\s\-]+$/u',
                    'product_code'=> 'required|regex:/^[\w-]*$/',
                    'product_price'=>'required|numeric',
                ];
                $customMessages = [
                    'category_id.required'=> 'Category is required!',
                    'product_name.required'=>'The product name is required!',
                    'product_name.regex'=>'Please provide a valid product name!',
                    'product_code.required'=>'The product code is required!',
                    'product_code.regex'=>'Requires a valid product code!',
                    'product_price.required'=>'The product price is required!',
                    'product_price.regex'=>'Requires a valid product price!',
                ];
                $this->validate($request, $rules,$customMessages);

                if(empty($data['is_featured'])){
                    $is_featured = "No";
                }else{
                    $is_featured = "Yes";

                }

                if(empty($data['product_price'])){
                    $data['product_price']= "";
                }

                if(empty($data['product_discount'])){
                    $data['product_discount']= 0;
                }

                if(empty($data['product_weight'])){
                    $data['product_weight']= 0;
                }

                if(empty($data['description'])){
                    $data['description']= "";
                }

                if(empty($data['meta_title'])){
                    $data['meta_title']= "";
                }

                if(empty($data['meta_keywords'])){
                    $data['meta_keywords']= "";
                }
                if(empty($data['meta_description'])){
                    $data['meta_description']= "";
                }

                //upload of product images

                if($request->hasFile('main_image')){
                 $image_tmp = $request->file('main_image');
                 if ($image_tmp->isValid()){
                    //get ori image name
                    $image_name = $image_tmp->getClientOriginalName();
                    //get image extension
                    // $extension = $image_tmp->getClientOriginalExtension();
                    //get new image name
                    $imageName = $image_name. '-'. rand(111,9999). '-'.'.png';
                    //path for 3 sizes images
                    $large_image_path = 'images/product_images/large/'.$imageName;
                    $medium_image_path = 'images/product_images/medium/'.$imageName;
                    $small_image_path = 'images/product_images/small/'.$imageName;
                    //upload the large images
                    Image::make($image_tmp)->save($large_image_path);
                    //upload small and medium  images
                    Image::make($image_tmp)->resize(520,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(260,300)->save($small_image_path);
                    //save Main img in the product table
                    $product->main_image = $imageName;

                    }
                }

                //save the product details in the product table
                $categoryDetails = Category::find($data['category_id']);
                $product->section_id = $categoryDetails['section_id'];
                $product->category_id =$data['category_id'];
                $product->product_name =$data['product_name'];
                $product->product_price =$data['product_price'];
                $product->product_code =$data['product_code'];
                // $product->main_image=$data['main_image'];
                $product->product_discount =$data['product_discount'];
                $product->product_weight =$data['product_weight'];
                $product->description =$data['description'];
                $product->meta_title =$data['meta_title'];
                $product->meta_keywords =$data['meta_keywords'];
                $product->meta_description =$data['meta_description'];
                $product->is_featured =$is_featured;
                $product->status =1;
                $product->save();
                Session::flash('success_message', $message);
                return redirect('admin/products');

            }

            //Sections for category and sub category
            $categories = Section:: with('categories')->get();
            $categories = json_decode(json_encode($categories), true);
            /*echo "<pre>"; print_r($categories); die;*/


            return view('admin.products.add_edit_product')->with(compact('title', 'categories', 'productData'));
        }

        public function deleteProductImage($id){

            //Obtain product image
            $productImage = Product::select ('main_image')->where('id',$id)->first();

            //get product image path
            $small_Image_Path = 'images/product_images/small/';
            $medium_Image_Path = 'images/product_images/medium/';
            $large_Image_Path = 'images/product_images/large/';

            //delete the small product image from the product_images folder when exist
            if(file_exists($small_Image_Path.$productImage->main_image)){
                unlink($small_Image_Path.$productImage->main_image);
            }
            //delete the medium product image from the product_images folder when exist
            if(file_exists($medium_Image_Path.$productImage->main_image)){
                unlink($medium_Image_Path.$productImage->main_image);
            }
           // delete the large product image from the product_images folder when exist
            if(file_exists($large_Image_Path.$productImage->main_image)){
                unlink($large_Image_Path.$productImage->main_image);
            }
            //delete product image from the product table
            Product::where('id',$id)->update(['main_image'=>'']);

            $message = 'Product image is deleted successfully!';
            session::flash('success_message', $message);
            return redirect()->back();
        }
        //attributes
        public function addAttributes( Request $request,$id){
            if($request->isMethod('post')){
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
              foreach($data['sku'] as $key => $value){
                if(!empty($value)) {


                    //to check is sku already exist

                    $attrCountSKU = ProductsAttribute::where(['sku'=>$value])->count();
                    if($attrCountSKU>0){
                        $message = 'SKU is already in! Please enter a new SKU!';
                        session::flash('error_message',$message);
                        return redirect()->back();
                    }

                    $attribute =  new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
              }

              $success_message = 'The product attribute is added successfully!';
              session::flash('success_message',$success_message);
              return redirect()->back();
            }

            $productData = Product::with('attributes')->find($id);
            $productData = json_decode(json_encode($productData), true);
            $title = "Product Attributes";
            // echo "<pre>"; print_r($productData); die;
            return view('admin.products.add_attributes')->with(compact('productData', 'title'));
        }

        public function editAttributes(Request $request, $id){
            if ($request->isMethod('post')){
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
                foreach ($data['attrId'] as $key => $attr) {
                    if(!empty($attr)){
                        ProductsAttribute::where(['id'=>$data['attrId'][$key]])->update(['price'=>
                        $data['price'][$key], 'stock'=>$data['stock'][$key]]);
                    }

                }
                $message = 'Product attribute is updated successfully!';
            session::flash('success_message', $message);
            return redirect()->back();

            }
        }

        public function updateAttributeStatus(Request $request){
            if($request->ajax()){
             $data =$request->all();
                //echo "<pre>"; print_r($data); die;
                if ($data['status']=="Active"){
                 $status =0;
                }else{
                 $status = 1;
                }

                ProductsAttribute ::where('id',$data['attribute_id'])->update(['status'=>$status]);
                return response()->json(['status'=>$status,'attribute_id'=>$data['attribute_id']]);
                }
             }


         public function deleteAttribute($id){

            // to delete the attribute
            ProductsAttribute::where('id', $id)->delete();

            $message = 'Product attribute is deleted successfully!';
            session::flash('success_message',$message);
            return redirect()->back();
        }

}
