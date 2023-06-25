<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function section(){
        return $this->belongsTo('App\Section', 'section_id');
    }

    public function attributes(){
        return $this->hasMany('App\ProductsAttribute');
    }


    public static function getDiscountedPrice($product_id){
        $proDetails = Product ::select('product_price', 'product_discount','category_id')->where('id',$product_id)->first()->toArray();

        $catDetails = Category::select('category_discount')->where('id', $proDetails['category_id'])->first()->toArray();

        if($proDetails['product_discount']>0){
            //if the discount is added from admin panel
            $discounted_price = $proDetails ['product_price']-($proDetails ['product_price']*$proDetails['product_discount']/100);

            //sale price = cost price-discount price
            //450 =  500-(500*10/100 = 50)
        }else if ($catDetails['category_discount']>0){
            //if product discount is not added and category discount added from the admin panel
            $discounted_price =$proDetails['product_price'] - ($proDetails['product_price']*$catDetails['category_discount']/100);

        }else{
            $discounted_price = 0;

        }
        return $discounted_price;
    }

    public  static function getDiscountedAttrPrice($product_id){
        $proAttrPrice = ProductsAttribute::where(['product_id'=>$product_id])->first()->toArray();
        $proDetails = Product ::select('product_discount','category_id')->where('id',$product_id)->first()->toArray();
        $catDetails = Category::select('category_discount')->where('id', $proDetails['category_id'])->first()->toArray();

        if($proDetails['product_discount']>0){
            //if the discount is added from admin panel
            $discounted_price = $proAttrPrice ['price']-($proAttrPrice ['price']*$proDetails['product_discount']/100);
            $discount = $proAttrPrice ['price'] - $discounted_price;
            //sale price = cost price-discount price
            //450 =  500-(500*10/100 = 50)
        }else if ($catDetails['category_discount']>0){
            //if product discount is not added and category discount added from the admin panel
            $discounted_price =$proAttrPrice['price'] - ($proAttrPrice['price']*$catDetails['category_discount']/100);
            $discount = $proAttrPrice ['price'] - $discounted_price;

        }else{
            $discounted_price = 0;
            $discount= 0;

        }
        return array ('discounted_price'=>$discounted_price, 'discount'=>$discount);
    }


}
