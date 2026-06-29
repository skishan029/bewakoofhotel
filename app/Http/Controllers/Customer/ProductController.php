<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $category   = $request->category ?? null;
        $search     = $request->search ?? null;
        $products = \App\Models\Product::orderBy('ordering', 'asc')
        ->when($category, function ($query) use ($category) {
            return $query->whereHas('categories', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        })
        ->when($search, function ($query) use ($search) {
            return $query->where('product_name_online', 'like', '%'.$search.'%')
            ->orWhere('product_name_english', 'like', '%'.$search.'%')
            ->orWhere('product_name', 'like', '%'.$search.'%');
        })
        ->onlyActive()->paginate(20)->withQueryString();


        /*
        $perPage    = 12;
        $page       = $request->get('page', 1);
        $category   = $request->category ?? null;

        $query = Product::orderBy('ordering', 'asc')
            ->when($category, function ($query) use ($category) {
                return $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('category_id', $category);
                });
            })
            ->onlyActive()
            ->get();

        $transformed  = $query->flatMap(function ($product) {

            $items = collect();

            if (!empty($product->half_price)) {
                $clone = clone $product;
                $clone->plate_type = 2;
                $clone->display_price = floatval($product->half_price) + floatval($product->shipping_charge);
                $clone->plate_label = 'Half';
                $items->push($clone);
            }

            if (!empty($product->full_price)) {
                $clone = clone $product;
                $clone->plate_type = 1;
                $clone->display_price = floatval($product->full_price) + floatval($product->shipping_charge);
                $clone->plate_label = 'Full';
                $items->push($clone);
            }

            return $items;
        });

        // Manual pagination
        $products = new LengthAwarePaginator(
            $transformed->forPage($page, $perPage),
            $transformed->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        */
        $title = 'Our Menu';
        return view('customer.product.index', compact('products', 'title'));
    }
}
