<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService 
{
    public function getCategories()
    {
        $categories = Category::all();
        return $categories;
    }
}