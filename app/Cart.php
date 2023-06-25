<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Product;
use Session;
use Auth;
class Cart extends Model
{
    use HasFactory;

    public static function userCarItems(){
        if(Auth::check()){
            $userCartItems = Cart::with('product')->where('user_id',Auth::user()->id)->get()->toArray();
        }else{
            $userCartItems = Cart:: with('product')->where('session_id',Session::get('session_id'))->get()->toArray();
        }
        return $userCartItems;
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

    public static function getProductAttrPrice($product_id){
        $attrPrice = ProductsAttribute::select('price')->where(['product_id'=>$product_id])->first()->toArray();
        return $attrPrice['price'];
    }
}
