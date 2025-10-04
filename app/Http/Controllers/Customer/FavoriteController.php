<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('product.category')
            ->latest()
            ->get();

        return view('customer.favorites', compact('favorites'));
    }

    public function add(Product $product)
    {
        // Cek apakah sudah ada di favorites
        $exists = Favorite::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Produk sudah ada di favorit');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Produk ditambahkan ke favorit');
    }

    public function remove(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();
        return back()->with('success', 'Produk dihapus dari favorit');
    }

    public function moveToCart(Request $request, Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $favorite->product;

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        // Get or create cart
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        // Cek apakah sudah ada di cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok tidak mencukupi');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        // Hapus dari favorites
        $favorite->delete();

        return redirect()->route('customer.cart')->with('success', 'Produk dipindahkan ke keranjang');
    }
}