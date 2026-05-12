<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $orders = Order::where('user_id', $id)->latest()->get();
        $totalBelanja = $orders->sum('total');

        return view('admin.users.show', compact('user', 'orders', 'totalBelanja'));
    }
}