<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    const MAX_QTY = 10;

    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);
        $maxQty = self::MAX_QTY;
        return view('cart', compact('carts', 'subtotal', 'maxQty'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:' . self::MAX_QTY,
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

        if ($cart) {
            $newTotal = $cart->quantity + $request->quantity;
            if ($newTotal > self::MAX_QTY) {
                $sisa = self::MAX_QTY - $cart->quantity;
                $msg = $sisa > 0
                    ? "Maksimal pemesanan per produk adalah " . self::MAX_QTY . " item. Anda masih bisa menambah {$sisa} item lagi."
                    : "Anda sudah mencapai batas maksimal " . self::MAX_QTY . " item untuk produk ini.";
                return back()->withErrors(['quantity' => $msg])->withInput();
            }
            $cart->increment('quantity', $request->quantity);
        } else {
            Cart::create(['user_id' => Auth::id(), 'product_id' => $request->product_id, 'quantity' => $request->quantity]);
        }

        if ($request->action === 'buy_now') {
            return redirect()->route('checkout');
        }

        return redirect()->route('catalogue')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::with('product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $quantity = max(1, (int) $request->quantity);

        if ($quantity > self::MAX_QTY) {
            if ($request->ajax()) {
                return response()->json(
                    ['error' => 'Maksimal pesanan per produk adalah ' . self::MAX_QTY . ' item.'],
                    422
                );
            }
            return redirect()->back()->withErrors(['quantity' => 'Maksimal pesanan per produk adalah ' . self::MAX_QTY . ' item.']);
        }

        $cart->update(['quantity' => $quantity]);

        if ($request->ajax()) {
            $carts = Cart::with('product')->where('user_id', Auth::id())->get();
            $subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);
            return response()->json([
                'quantity'   => $quantity,
                'item_total' => $cart->product->price * $quantity,
                'subtotal'   => $subtotal,
                'cart_count' => $carts->count(),
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}