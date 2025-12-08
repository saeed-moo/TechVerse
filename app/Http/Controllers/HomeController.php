<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
 public function index()
    {
        $categories = Category::all();
        $featuredProducts = Product::active()
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
