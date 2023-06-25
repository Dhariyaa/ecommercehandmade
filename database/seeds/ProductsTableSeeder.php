<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productRecords = [
        ['id'=>1, 'category_id'=>4,'section_id'=>1,'product_name'=>'Kashmiri Embroided Clutch', 'product_code'=>'KEC001',
        'product_price'=>'500','product_discount'=>15, 'product_weight'=>300, 'main_image'=>'null', 'description'=>'Product Test'
        ,'meta_title'=>'', 'meta_description'=>'', 'meta_keywords'=>'', 'is_featured'=>'No', 'status'=>1],

        ['id'=>2, 'category_id'=>4,'section_id'=>1,'product_name'=>'Kashmiri Embroided Handbag', 'product_code'=>'KEH001',
        'product_price'=>'300','product_discount'=>'20', 'product_weight'=>'150', 'main_image'=>'null', 'description'=>'Product Test',
        'meta_title'=>'', 'meta_description'=>'', 'meta_keywords'=>'', 'is_featured'=>'Yes', 'status'=>1]
    ];
    Product::insert($productRecords);
    }
}
