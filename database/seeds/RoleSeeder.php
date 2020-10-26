<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            'Training Model',
            'Trainee'
        ];
        foreach($list as $l) {
            DB::table('roles')->insert([
                'name' => $l,
                'guard_name' => 'api'
            ]);
        }
    }
}
