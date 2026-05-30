<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Order;
use App\Models\CateringOrder;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id'          => 'required_without:catering_order_id|nullable|exists:orders,id',
            'catering_order_id' => 'required_without:order_id|nullable|exists:catering_orders,id',
            'rating'            => 'required|integer|min:1|max:5',
            'komentar'          => 'required|string|max:500',
        ]);

        if ($request->filled('catering_order_id')) {
            $order = CateringOrder::where('id', $request->catering_order_id)
                          ->where('user_id', auth()->id())
                          ->where('status', 'selesai')
                          ->firstOrFail();

            if ($order->testimonial) {
                return back()->with('error', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
            }

            Testimonial::create([
                'user_id'           => auth()->id(),
                'catering_order_id' => $order->id,
                'nama'              => auth()->user()->name,
                'rating'            => $request->rating,
                'komentar'          => $request->komentar,
                'status'            => 'approved',
            ]);
        } else {
            $order = Order::where('id', $request->order_id)
                          ->where('user_id', auth()->id())
                          ->where('status', 'selesai')
                          ->firstOrFail();

            if ($order->testimonial) {
                return back()->with('error', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
            }

            Testimonial::create([
                'user_id'  => auth()->id(),
                'order_id' => $order->id,
                'nama'     => auth()->user()->name,
                'rating'   => $request->rating,
                'komentar' => $request->komentar,
                'status'   => 'approved',
            ]);
        }

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
