<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = $this->getOrCreateCart();

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

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cartItem->update($validated);

        return back()->with('success', 'Keranjang berhasil diupdate');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        
        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    private function getOrCreateCart()
    {
        return Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);
    }
    public function toggleSelect(CartItem $cartItem)
{
    if ($cartItem->cart->user_id !== auth()->id()) {
        abort(403);
    }

    $cartItem->update([
        'is_selected' => !$cartItem->is_selected
    ]);

    return back()->with('success', 'Produk ' . ($cartItem->is_selected ? 'dipilih' : 'tidak dipilih'));
}

public function selectAll(Request $request)
{
    $cart = $this->getOrCreateCart();
    
    $selectAll = $request->input('select_all', true);
    
    $cart->items()->update([
        'is_selected' => $selectAll
    ]);

    return back()->with('success', $selectAll ? 'Semua produk dipilih' : 'Semua produk dibatalkan');
}
}
