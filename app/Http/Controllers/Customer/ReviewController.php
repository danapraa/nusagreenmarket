<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Tambahkan validasi image
        ]);

        // Cek apakah user sudah pernah beli produk ini di order tersebut
        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', auth()->id())
            ->whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->first();

        if (!$order) {
            return back()->with('error', 'Anda belum membeli produk ini');
        }

        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('order_id', $order->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini');
        }

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('reviews', 'public');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'image' => $validated['image'] ?? null,
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        // Hapus gambar jika ada
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();
        return back()->with('success', 'Review berhasil dihapus');
    }
}
