
<?php

use Illuminate\Database\Seeder;
use App\ProductsAttribute;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAttributes = [
            ['id'=>1, 'product_id'=>1, 'price'=>600, 'stock'=>20, 'SKU'=>'KEH001', 'status'=>1],
            // ['id'=>2, 'product_id'=>1, 'price'=>400, 'stock'=>40, 'SKU'=>'KEH002', 'status'=>1],
            // ['id'=>3, 'product_id'=>1, 'price'=>900, 'stock'=>70, 'SKU'=>'KEH003', 'status'=>1],
        ];
        ProductsAttribute::insert($productAttributes);
    }
}
