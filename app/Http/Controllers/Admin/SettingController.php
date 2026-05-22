<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringOrder;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function destroyAllOrders(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:HAPUS SEMUA PESANAN',
        ], [
            'confirmation.in' => 'Frasa konfirmasi tidak cocok. Ketik persis: HAPUS SEMUA PESANAN',
        ]);

        $count         = Order::count();
        $cateringCount = CateringOrder::count();

        DB::transaction(function () {
            DB::table('orders')->delete();
            DB::table('catering_orders')->delete();
        });

        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE order_items AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE testimonials AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE catering_orders AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE catering_order_cost_items AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE catering_order_histories AUTO_INCREMENT = 1');

        return back()->with('success', "Berhasil menghapus {$count} pesanan produk dan {$cateringCount} pesanan catering beserta data terkait.");
    }
}
