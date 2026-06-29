<?php

namespace App\Http\Controllers\Customer;

use App\Models\OrderIntent;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\ProductOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    public function index()
    {

        $user = Auth::guard('customer')->user();
        $orders = ProductOrder::with(['order_items', 'order_items.product'])->where('customer_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        $title = 'My Orders';
        return view('customer.order.index', compact('orders', 'title'));
    }

    public function checkout(Request $request)
    {
        if ($request->isMethod('post')) {

            $resturentStatus = \App\Helper\Helper::getRestaurantStatus();
            if ($resturentStatus == 'Closed') {
                return redirect()->back()->with('error', 'Resturent is Closed Now..');
            }

            $cart = Cart::instance(Auth::guard('customer')->user()->id)->content();
            if ($cart->isEmpty()) {
                return redirect()->route('customer.product.index')->with('error', 'Your cart is empty.');
            }

            $request->validate([
                'address'           => 'required|string',
                'landmark'          => 'required|string',
                'alt_mobile'        => 'nullable',
                'payment_option'    => 'required|in:cash,online',
                'region_id'         => 'required|exists:regions,id',
                'sub_region_id'     => 'required|exists:regions,id',
            ]);

            try {

                if ($request->payment_option == 'online') {
                    $orderIntent = DB::transaction(function () use ($request) {
                        $loggedInUser = Auth::guard('customer')->user();

                        $subTotal = Cart::instance($loggedInUser->id)->subtotalFloat();

                        $shippingCharge = 0;
                        $orderItem = [];
                        foreach (Cart::instance($loggedInUser->id)->content() as $item) {
                            $shippingCharge += $item->options['shipping_cost'] ?? 0;
                            $orderItem[] = [
                                'id' => $item->id,
                                'name' => $item->name,
                                'qty' => $item->qty,
                                'price' => $item->price,
                                'total' => $item->price * $item->qty,
                                'options' => $item->options->toArray(),
                            ];
                        }
                        $orderIntent = OrderIntent::create([
                            'customer_id'       => $loggedInUser->id,
                            'sub_total'         => $subTotal,
                            'discount'          => 0,
                            'delivery_charge'   => 0,
                            'grand_total'       => $subTotal,
                            'order_date'        => date('Y-m-d'),
                            'address'           => $request->address,
                            'landmark'          => $request->landmark,
                            'customer_name'     => $loggedInUser->name,
                            'customer_phone'    => $loggedInUser->username,
                            'email'             => $loggedInUser->email,
                            'order_note'        => $request->order_note,
                            'order_item'        => $orderItem,
                            'region_id'         => $request->region_id ?? null,
                            'sub_region_id'     => $request->sub_region_id ?? null,
                        ]);

                        Cart::instance($loggedInUser->id)->destroy();

                        return $orderIntent;
                    });

                    return redirect()->route('payment.processing', ['id' => $orderIntent->id]);
                } else {

                    $order = DB::transaction(function () use ($request) {
                        $loggedInUser = Auth::guard('customer')->user();

                        $subTotal = Cart::instance($loggedInUser->id)->subtotalFloat();

                        $order = new ProductOrder();
                        $order->order_no         = \App\Helper\Clib::getOrderNo();
                        $order->order_key        = md5($order->order_no . time());
                        $order->order_date       = date('Y-m-d');
                        $order->sub_total        = $subTotal;
                        $order->discount         = 0;
                        $order->delivery_charge  = 0;
                        $order->grand_total      = $subTotal;
                        $order->customer_id      = $loggedInUser->id;
                        $order->name             = $loggedInUser->name;
                        $order->whatsapp_number  = null;
                        $order->alt_number       = null;
                        $order->address          = $request->address;
                        $order->landmark         = $request->landmark;
                        $order->payment_option   = 'cash';
                        $order->status           = '1';
                        $order->order_status     = 'pending';
                        $order->is_paid          = false;
                        $order->order_note       = $request->order_note;
                        $order->region_id        = $request->region_id ?? null;
                        $order->sub_region_id    = $request->sub_region_id ?? null;
                        $order->save();

                        $orderItems = Cart::instance($loggedInUser->id)->content();
                        $subTotal = 0;
                        foreach ($orderItems as $item) {
                            $qty                = intval($item->qty);
                            $shipping_cost      = floatval($item->options['shipping_cost']);
                            $actual_price       = floatval($item->options['actual_price']);

                            $total_shipping_cost = $shipping_cost * $qty;
                            $price               = $actual_price * $qty;
                            $total               = $price + $total_shipping_cost;

                            $subTotal += $total;
                            $product = \App\Models\Product::find($item->id);

                            $orderItem = new ProductOrderItem();
                            $orderItem->product_order_id        = $order->id;
                            $orderItem->product_id              = $item->id;
                            $orderItem->plate_type              = $item->options['plate_type'];
                            $orderItem->quantity                = intval($item->qty);
                            $orderItem->price                   = $actual_price;
                            $orderItem->delivery_charge         = $shipping_cost;
                            $orderItem->total_delivery_charge   = $total_shipping_cost;
                            $orderItem->total                   = $total;
                            $orderItem->product_name            = $item->name;
                            $orderItem->full_lbl_show           = $product->full_lbl_show;
                            $orderItem->product_details         = $product->toJson();
                            $orderItem->status                  = '1';
                            $orderItem->save();
                        }

                        $order->sub_total       = $subTotal;
                        $order->delivery_charge = 0;
                        $order->grand_total     = $order->sub_total - $order->discount;
                        $order->save();

                        Cart::instance($loggedInUser->id)->destroy();

                        return $order;
                    });
					
					\App\Services\TwilioService::makeCall();
					
                    return redirect()->route('customer.order.success', $order->order_key);
                }
            } catch (\Exception $e) {
                Log::error("Customer::OrderController::create() - " . $e->getMessage());
                return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
            }
        }

        $cart = Cart::instance(Auth::guard('customer')->user()->id)->content();

        if ($cart->isEmpty()) {
            return redirect()->route('customer.product.index');
        }
        $user =  Customer::find(Auth::guard('customer')->user()->id);
        
        $subTotal = Cart::instance(Auth::guard('customer')->user()->id)->subtotalFloat();
        $parentRegions = \App\Models\Region::whereNull('parent_id')->has('subregion')->get(['id', 'name']);
        $subRegions = \App\Models\Region::where('parent_id', $user->region_id)->whereNotNull('parent_id')->get(['id', 'name']);
        $title = 'Checkout';
        return view('customer.checkout.index', compact('cart', 'subTotal','user','parentRegions','subRegions', 'title'));
    }

    public function success($key)
    {
        $order = \App\Models\ProductOrder::where('order_key', $key)
            ->where('customer_id', Auth::guard('customer')->user()->id)
            ->firstOrFail();
        $title = 'Order Placed';
        return view('customer.order.success', compact('order', 'title'));
    }
}
