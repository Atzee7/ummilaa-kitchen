<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\TestimonialController; // TAMBAHAN
use App\Http\Controllers\CateringController;

// Profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile-user', [UserProfileController::class, 'edit'])->name('profile.user');
    Route::post('/profile-user', [UserProfileController::class, 'update'])->name('profile.user.update');
});

// Orders & riwayat
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/mark-paid', [OrderController::class, 'markPaid'])->name('orders.markPaid');
    Route::post('/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store'); // TAMBAHAN
});

// Product detail (tanpa login)
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Catering — wajib login (urutan: checkout & riwayat sebelum param {id})
Route::middleware('auth')->group(function () {
    Route::get('/catering', [CateringController::class, 'index'])->name('catering.index');
    Route::get('/catering/checkout', [CateringController::class, 'checkout'])->name('catering.checkout');
    Route::post('/catering/checkout', [CateringController::class, 'store'])->name('catering.store');
    Route::get('/catering/riwayat', [CateringController::class, 'history'])->name('catering.history');
    Route::get('/catering/riwayat/{id}', [CateringController::class, 'show'])->name('catering.show');
    Route::get('/catering/riwayat/{id}/bayar', [CateringController::class, 'payment'])->name('catering.payment');
});

// Cart — login tapi tidak perlu profile lengkap
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::get('/order/success/{id}', function($id) {
        $order = \App\Models\Order::with('items.product')->findOrFail($id);
        return view('order-success', compact('order'));
    })->name('order.success');
    Route::get('/order/payment/{id}', function($id) {
        \App\Models\Order::cancelExpiredUnpaidOrders();
        $order = \App\Models\Order::with('items.product')->findOrFail($id);

        // Self-heal: regenerate Snap token kalau order online masih belum_bayar tapi token NULL
        if ($order->status === 'belum_bayar'
            && in_array($order->metode_pembayaran, \App\Models\Order::ONLINE_PAYMENT_METHODS, true)
            && empty($order->snap_token)
            && !$order->isPaymentExpired()
        ) {
            try {
                app(\App\Services\MidtransService::class)->getSnapToken($order);
                $order->refresh();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Retry Snap token gagal', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return view('order-payment', compact('order'));
    })->name('order.payment');
});

// Checkout — wajib login + profile lengkap
Route::middleware(['auth', 'profile.complete'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Midtrans webhook (di luar middleware auth, CSRF di-exclude di bootstrap/app.php)
// Dua path didukung agar dashboard Midtrans bisa pakai konvensi mana saja.
Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');
Route::post('/midtrans/callback',     [MidtransWebhookController::class, 'handle'])->name('midtrans.callback');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('products', App\Http\Controllers\Admin\ProductAdminController::class)
            ->except(['show']);

        Route::get('/orders', [App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
        Route::get('/orders/{id}/modal', [App\Http\Controllers\Admin\OrderAdminController::class, 'modalContent'])->name('orders.modalContent');
        Route::patch('/orders/{id}/status', [App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{id}/send-whatsapp', [App\Http\Controllers\Admin\OrderAdminController::class, 'sendWhatsapp'])->name('orders.sendWhatsapp');

        // Catering — Paket & Pesanan
        Route::resource('catering-packages', App\Http\Controllers\Admin\CateringPackageController::class)
            ->except(['show']);
        Route::get('/catering-orders', [App\Http\Controllers\Admin\CateringOrderAdminController::class, 'index'])->name('catering-orders.index');
        Route::get('/catering-orders/{id}', [App\Http\Controllers\Admin\CateringOrderAdminController::class, 'show'])->name('catering-orders.show');
        Route::post('/catering-orders/{id}/open-payment', [App\Http\Controllers\Admin\CateringOrderAdminController::class, 'openPayment'])->name('catering-orders.openPayment');
        Route::post('/catering-orders/{id}/send-whatsapp', [App\Http\Controllers\Admin\CateringOrderAdminController::class, 'sendWhatsapp'])->name('catering-orders.sendWhatsapp');
        Route::patch('/catering-orders/{id}/status', [App\Http\Controllers\Admin\CateringOrderAdminController::class, 'updateStatus'])->name('catering-orders.updateStatus');

        Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [UserAdminController::class, 'show'])->name('users.show');
        Route::delete('/users/{id}', [UserAdminController::class, 'destroy'])->name('users.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
        // Kasir / Pembelian Langsung
        Route::get('/kasir', [App\Http\Controllers\Admin\KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [App\Http\Controllers\Admin\KasirController::class, 'store'])->name('kasir.store');

        // Testimoni Admin — TAMBAHAN
        Route::get('/testimonials', [App\Http\Controllers\Admin\TestimonialAdminController::class, 'index'])->name('testimonials.index');
        Route::delete('/testimonials/{id}', [App\Http\Controllers\Admin\TestimonialAdminController::class, 'destroy'])->name('testimonials.destroy');

        // Pengaturan Toko
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/orders', [App\Http\Controllers\Admin\SettingController::class, 'destroyAllOrders'])->name('settings.orders.destroyAll');

        // API polling pesanan aktif
        Route::get('/api/pesanan-aktif', [App\Http\Controllers\Admin\DashboardController::class, 'pesananAktifApi'])->name('api.pesanan-aktif');
    });
});

require __DIR__.'/auth.php';