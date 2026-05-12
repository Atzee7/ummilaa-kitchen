<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Order;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:500',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', auth()->id())
                      ->where('status', 'selesai')
                      ->firstOrFail();

        // Cegah user submit testimoni 2x untuk order yang sama
        if ($order->testimonial) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
        }

        Testimonial::create([
    'user_id'  => auth()->id(),
    'order_id' => $order->id,
    'nama'     => auth()->user()->name,
    'rating'   => $request->rating,
    'komentar' => $request->komentar,
    'status'   => 'approved', // langsung approved
]);

        return back()->with('success', 'Ulasan berhasil dikirim! Menunggu persetujuan admin.');
    }
}