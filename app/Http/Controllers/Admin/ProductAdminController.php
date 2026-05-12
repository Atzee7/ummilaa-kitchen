<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductAdminController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search', '');
        $categoryId = $request->get('category_id', '');

        $query = Product::with('categoryRelation')->latest();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products   = $query->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    public function create()
    {
        $categories = Category::all();
        $badgeCount = Product::whereNotNull('badge')->where('badge', '!=', '')->count();
        return view('admin.products.create', compact('categories', 'badgeCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:ready,habis',
            'badge'       => 'nullable|in:new,terlaris,unggulan',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Cek batas badge
        if ($request->badge && $request->badge !== '') {
            $badgeCount = Product::whereNotNull('badge')->where('badge', '!=', '')->count();
            if ($badgeCount >= 4) {
                return back()
                    ->withErrors(['badge' => 'Maksimal hanya 4 produk yang boleh memiliki badge. Hapus badge produk lain terlebih dahulu.'])
                    ->withInput();
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'category'    => Category::find($request->category_id)->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'status'      => $request->status,
            'badge'       => $request->badge,
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        // Hitung badge SELAIN produk yang sedang diedit
        $badgeCount = Product::whereNotNull('badge')
            ->where('badge', '!=', '')
            ->where('id', '!=', $product->id)
            ->count();
        return view('admin.products.edit', compact('product', 'categories', 'badgeCount'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:ready,habis',
            'badge'       => 'nullable|in:new,terlaris,unggulan',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Cek batas badge (kecualikan produk ini sendiri)
        if ($request->badge && $request->badge !== '') {
            $badgeCount = Product::whereNotNull('badge')
                ->where('badge', '!=', '')
                ->where('id', '!=', $product->id)
                ->count();
            if ($badgeCount >= 4) {
                return back()
                    ->withErrors(['badge' => 'Maksimal hanya 4 produk yang boleh memiliki badge. Hapus badge produk lain terlebih dahulu.'])
                    ->withInput();
            }
        }

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'category'    => Category::find($request->category_id)->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'status'      => $request->status,
            'badge'       => $request->badge,
            'image'       => $product->image,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}