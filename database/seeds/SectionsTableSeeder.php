<?php

use Illuminate\Database\Seeder;
use App\Section;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectionsRecords = [
            ['id'=>1,'name'=>'Embroidery','status'=>1],
            ['id'=>2,'name'=>'Paintings','status'=>1],
            ['id'=>3,'name'=>'Block Printing','status'=>1],


        ];
        Section::insert($sectionsRecords);


    }
}
