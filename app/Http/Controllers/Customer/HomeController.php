<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active()->inStock();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->withCount('products')->get();
        $featured_products = Product::active()->inStock()->take(6)->get();

        return view('customer.home', compact('products', 'categories', 'featured_products'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return view('customer.product-detail', compact('product', 'related_products'));
    }

    public function products(Request $request)
{
    $query = Product::with('category')->active()->inStock();

    // Filter by category
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Search
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Sorting
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
    } else {
        $query->latest();
    }

    $products = $query->paginate(12)->withQueryString();
    $categories = Category::where('is_active', true)->withCount(['products' => function($q) {
        $q->active()->inStock();
    }])->get();

    return view('customer.products', compact('products', 'categories'));
}
}