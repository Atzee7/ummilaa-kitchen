<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('order', 'cateringOrder')
                        ->where('status', 'approved')
                        ->latest()
                        ->get();
        return view('about', compact('testimonials'));
    }
}