<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Food/Beverage',
            'Travel/Commute',
            'Shopping',
            'Housing/Utilities',
            'Entertainment',
            'Health/Medical',
            'Education',
            'Savings/Investments',
            'Insurance',
            'Gifts/Donations',
            'Miscellaneous',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
