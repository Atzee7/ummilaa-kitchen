<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Koordinat outlet Ummilaa Kitchen
    const OUTLET_LAT = -7.963536;
    const OUTLET_LNG = 112.669380;
    const MAX_DELIVERY_KM = 5; // maksimal jarak untuk delivery

    /**
     * Hitung jarak antara dua koordinat menggunakan rumus Haversine
     * Haversine = rumus matematika untuk menghitung jarak dua titik di bola bumi
     */
    private function hitungJarak($lat1, $lng1, $lat2, $lng2)
    {
        $r = 6371; // radius bumi dalam km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $r * $c; // hasil dalam km
    }

    private function hitungOngkir(float $jarakKm): int
    {
        $km = (int) ceil($jarakKm);
        if ($km <= 2) return 5000;
        if ($km <= 4) return 8000;
        if ($km === 5) return 10000;
        return 0;
    }

    public function index()
    {
        if (Setting::get('store_open', '1') !== '1') {
            return redirect()->route('home')->with('error', 'Maaf, toko sedang tutup. Anda tidak dapat melakukan checkout saat ini.');
        }

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) return redirect()->route('cart')->with('error', 'Keranjang kosong!');

        $subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);

        // Cek jarak user ke outlet
        $user = Auth::user();
        $jarakKm = null;
        $bisaDelivery = false;

        if ($user->lat && $user->lng) {
            $jarakKm = $this->hitungJarak($user->lat, $user->lng, self::OUTLET_LAT, self::OUTLET_LNG);
            $bisaDelivery = $jarakKm <= self::MAX_DELIVERY_KM;
        }

        // Ongkir default (akan diupdate via JS sesuai pilihan)
        $ongkir = $bisaDelivery ? $this->hitungOngkir($jarakKm) : 0;
        $total = $subtotal + $ongkir;

        return view('checkout', compact('carts', 'subtotal', 'ongkir', 'total', 'jarakKm', 'bisaDelivery'));
    }

    public function store(Request $request)
{
    if (Setting::get('store_open', '1') !== '1') {
        return redirect()->route('home')->with('error', 'Maaf, toko sedang tutup. Anda tidak dapat melakukan checkout saat ini.');
    }

    $request->validate([
        'metode_pembayaran'  => 'required|string',
        'metode_pengiriman'  => 'required|in:delivery,pickup',
        'catatan'            => 'nullable|string|max:500',
    ]);

    $carts = Cart::with('product')->where('user_id', Auth::id())->get();
    if ($carts->isEmpty()) return redirect()->route('cart');

    $user = Auth::user();

    // Ambil data dari profil user
    $nama_penerima = $user->name;
    $no_telepon    = $user->no_telepon;
    $alamat        = $user->alamat;

    $subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);
    $ongkir   = 0;

    if ($request->metode_pengiriman === 'delivery') {
        if ($user->lat && $user->lng) {
            $jarakKm = $this->hitungJarak($user->lat, $user->lng, self::OUTLET_LAT, self::OUTLET_LNG);
            if ($jarakKm > self::MAX_DELIVERY_KM) {
                return back()->withErrors(['metode_pengiriman' => 'Maaf, jarak Anda terlalu jauh untuk delivery (maks. 5 km).']);
            }
        }
        $ongkir = $this->hitungOngkir($jarakKm);
    }

    $total = $subtotal + $ongkir;

    $order = Order::create([
        'user_id'            => Auth::id(),
        'nama_penerima'      => $nama_penerima,
        'alamat'             => $alamat,
        'detail_alamat'      => $user->detail_alamat,
        'no_telepon'         => $no_telepon,
        'metode_pembayaran'  => $request->metode_pembayaran,
        'metode_pengiriman'  => $request->metode_pengiriman,
        'catatan'            => $request->catatan,
        'subtotal'           => $subtotal,
        'ongkir'             => $ongkir,
        'total'              => $total,
        'status'             => in_array($request->metode_pembayaran, Order::ONLINE_PAYMENT_METHODS, true)
            ? 'belum_bayar'
            : 'pending',
    ]);

    foreach ($carts as $cart) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $cart->product_id,
            'quantity'   => $cart->quantity,
            'price'      => $cart->product->price,
        ]);
    }

    Cart::where('user_id', Auth::id())->delete();

    if (in_array($order->metode_pembayaran, Order::ONLINE_PAYMENT_METHODS, true)) {
        try {
            app(MidtransService::class)->getSnapToken($order->fresh('items.product'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuat Snap token Midtrans', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            return redirect()->route('order.payment', $order->id)
                ->with('error', 'Gagal menghubungi gateway pembayaran. Silakan coba lagi atau hubungi admin.');
        }
    }

    return redirect()->route('order.payment', $order->id);
    }
}