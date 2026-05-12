<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    // Tampilkan halaman kasir
    public function index()
{
    $products = Product::with('categoryRelation')->latest()->get();
    return view('admin.kasir.index', compact('products'));
}

    // Proses pesanan dari kasir
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima'     => 'required|string|max:100',
            'no_telepon'        => 'required|string|max:20',
            'metode_pembayaran' => 'required|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        // Hitung subtotal dari item yang dipesan
        $subtotal = 0;
        foreach ($request->items as $item) {
            $product   = Product::findOrFail($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }

        // Buat order baru — masuk ke tabel orders yang sama
        $order = Order::create([
            'user_id'           => null,         // tidak ada akun, jadi null
            'nama_penerima'     => $request->nama_penerima,
            'no_telepon'        => $request->no_telepon,
            'alamat'            => 'Pembelian Langsung di Outlet',
            'metode_pembayaran' => $request->metode_pembayaran,
            'metode_pengiriman' => 'pickup',      // selalu ambil sendiri
            'catatan'           => $request->catatan ?? null,
            'subtotal'          => $subtotal,
            'ongkir'            => 0,             // gratis karena ambil sendiri
            'total'             => $subtotal,
            'status'            => 'selesai',     // langsung selesai
        ]);

        // Simpan setiap item ke tabel order_items
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ]);
        }

        return response()->json([
            'success'  => true,
            'order_id' => $order->id,
            'total'    => $order->total,
        ]);
    }
}