<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $storeOpen = Setting::get('store_open', '1');
        return view('admin.settings.index', compact('storeOpen'));
    }

    public function update(Request $request)
    {
        $storeOpen = $request->has('store_open') ? '1' : '0';
        Setting::set('store_open', $storeOpen);

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}
