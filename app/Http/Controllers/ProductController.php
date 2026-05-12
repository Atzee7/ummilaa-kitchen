<?php
namespace App\Http\Controllers;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        return view('product-detail', compact('product', 'related'));
    }
}