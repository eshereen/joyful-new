<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Joyful|Categories';
        $categories = Category::with('products')->get();
        return view('categories', compact('categories', 'title'));
    }
}
