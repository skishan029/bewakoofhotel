<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::instance(Auth::guard('customer')->user()->id)->content();
        $subTotal = Cart::instance(Auth::guard('customer')->user()->id)->subtotal();
        $title = 'Card';
        return view('customer.cart.index', compact('cart', 'subTotal', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'plate_type' => 'required|in:1,2', // 1=Full, 2=Half
            'qty'        => 'required|integer|min:1'
        ]);

        try {
            $product = \App\Models\Product::onlyActive()->findOrFail($request->product_id);

            $price = $request->plate_type == '1' ? $product->full_price : $product->half_price;
            $plateLabel = $request->plate_type == '1' ? 'Full' : 'Half';

            Cart::instance(Auth::guard('customer')->user()->id)->add([
                'id'    => $product->id,
                'name'  => $product->product_name_online ?? $product->product_name_english,
                'qty'   => intval($request->qty),
                'price' => (float) $price + floatval($product->shipping_charge),
                'weight' => 0,
                'options' => [
                    'actual_price'  => (float) $price,
                    'plate_type'    => $request->plate_type,
                    'plate_label'   => $plateLabel,
                    'photo'         => $product->featured_photo,
                    'shipping_cost' => (float) $product->shipping_charge,
                    'is_online_label_show' => $product->is_online_label_show,
                ],
            ])->associate($product);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        } catch (\Exception $th) {
            Log::error("CartController::store() - " . $th->getMessage());
            return redirect()->back()->with('error', 'Product not added to cart!');
        }
    }

    public function update(Request $request, $rowId)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        try {
            $instance = Auth::guard('customer')->user()->id;
            Cart::instance($instance)->update($rowId, $request->qty);

            if ($request->ajax()) {
                $item = Cart::instance($instance)->get($rowId);
                return response()->json([
                    'success'   => true,
                    'message'   => 'Cart updated successfully!',
                    'qty'       => $item->qty,
                    'rowTotal'  => $item->price * $item->qty,
                    'subTotal'  => Cart::instance($instance)->subtotal(),
                    'cartCount' => Cart::instance($instance)->count(),
                ]);
            }

            return redirect()->back()->with('success', 'Cart updated successfully!');
        } catch (\Exception $th) {
            Log::error("CartController::update() - " . $th->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cart update failed!'], 500);
            }

            return redirect()->back()->with('error', 'Cart update failed!');
        }
    }

    public function destroy($id)
    {

        try {
            Cart::instance(Auth::guard('customer')->user()->id)->remove($id);
            return redirect()->back()->with('success', 'Product removed from cart successfully!');
        } catch (\Exception $th) {
            Log::error("CartController::destroy() - " . $th->getMessage());
            return redirect()->back()->with('error', 'Product not removed from cart!');
        }
    }
}
