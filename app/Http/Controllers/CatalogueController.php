<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $kategori   = $request->get('kategori', 'semua');
        $sort       = $request->get('sort', 'terbaru');
        $search     = $request->get('search', '');
        $categories = Category::withCount('products')->get();

        $query = Product::with('categoryRelation');

        // Filter kategori
        if ($kategori !== 'semua') {
            $query->whereHas('categoryRelation', fn($q) => $q->where('slug', $kategori));
        }

        // Search
        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        // Sort
        if ($sort === 'harga_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'harga_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('catalogue', compact('kategori', 'products', 'categories', 'sort', 'search'));
    }
}