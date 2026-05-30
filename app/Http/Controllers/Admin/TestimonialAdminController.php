<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialAdminController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user', 'order', 'cateringOrder')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimoni dihapus.');
    }
}