<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DefaultExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_categories')
            ->insert([
                'name' => 'Transfers',
                'color' => '#20F3E',
                'is_system' => true
            ]);
    }
}
