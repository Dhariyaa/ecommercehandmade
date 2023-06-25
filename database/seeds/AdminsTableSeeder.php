<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
        $adminRecords = [
            ['id'=>1,'name'=>'john','type'=>'admin','mobile'=>'01111111111','email'=>'john@admin.com',
            'password'=>'$2y$10$RctWuDV66Rp4mnX8.EJJtOJ5PwTDBRDQsMt71ThKXYsosm78e6sbe','image'=>'','status'=>1
        ],

        ['id'=>2,'name'=>'wick','type'=>'subadmin1','mobile'=>'01222222222','email'=>'wick@admin.com',
        'password'=>'$2y$10$RctWuDV66Rp4mnX8.EJJtOJ5PwTDBRDQsMt71ThKXYsosm78e6sbe','image'=>'','status'=>1
    ],

    ['id'=>3,'name'=>'raj','type'=>'subadmin2','mobile'=>'01333333333','email'=>'raj@admin.com',
    'password'=>'$2y$10$RctWuDV66Rp4mnX8.EJJtOJ5PwTDBRDQsMt71ThKXYsosm78e6sbe','image'=>'','status'=>1
],

        ];

        DB::table('admins')->insert($adminRecords);

        /*foreach ($adminRecords as $key => $records){
            \App\Admin::create($records);
        }*/

    }
}
