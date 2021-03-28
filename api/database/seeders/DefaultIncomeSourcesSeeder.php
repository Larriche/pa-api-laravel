<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DefaultIncomeSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('income_sources')
            ->insert([
                'name' => 'Transfers',
                'color' => '#20F3E',
                'is_system' => true
            ]);
    }
}
