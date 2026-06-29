<?php

namespace App\Http\Controllers\Customer;

use App\Models\OrderIntent;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\ProductOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        return view('customer.payment.success');
    }

    public function failed(Request $request)
    {
        return view('customer.payment.failed');
    }

    public function processing(Request $request)
    {
        return view('customer.payment.processing');
    }

    public function verify(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $orderIntent = OrderIntent::where('id', $request->query('id'))->firstOrFail();

                $order = new ProductOrder();
                $order->order_no         = \App\Helper\Clib::getOrderNo();
                $order->order_key        = md5($order->order_no . time());
                $order->order_date       = $orderIntent->order_date;
                $order->sub_total        = $orderIntent->sub_total;
                $order->discount         = $orderIntent->discount;
                $order->delivery_charge  = $orderIntent->delivery_charge;
                $order->grand_total      = $orderIntent->grand_total;
                $order->customer_id      = $orderIntent->customer_id;
                $order->name             = $orderIntent->customer_name;
                $order->whatsapp_number  = null;
                $order->alt_number       = null;
                $order->address          = $orderIntent->address;
                $order->landmark         = $orderIntent->landmark;
                $order->payment_option   = 'online';
                $order->status           = '1';
                $order->order_status     = 'pending';
                $order->is_paid          = true;
                $order->order_note       = $orderIntent->order_note;
                $order->region_id        = $orderIntent->region_id ?? null;
                $order->sub_region_id    = $orderIntent->sub_region_id ?? null;
                $order->save();

                foreach ($orderIntent->order_item as $item) {

                    $qty                = intval($item['qty']);
                    $shipping_cost      = floatval($item['options']['shipping_cost']);
                    $actual_price       = floatval($item['options']['actual_price']);

                    $total_shipping_cost = $shipping_cost * $qty;
                    $price               = $actual_price * $qty;
                    $total               = $price + $total_shipping_cost;


                    $product = \App\Models\Product::find($item['id']);

                    $orderItem = new ProductOrderItem();
                    $orderItem->product_order_id        = $order->id;
                    $orderItem->product_id              = $item['id'];
                    $orderItem->plate_type              = $item['options']['plate_type'];
                    $orderItem->quantity                = $qty;
                    $orderItem->price                   = $actual_price;
                    $orderItem->delivery_charge         = $shipping_cost;
                    $orderItem->total_delivery_charge   = $total_shipping_cost;
                    $orderItem->total                   = $total;
                    $orderItem->product_name            = $product->product_name_online ?? $product->product_name_english;
                    $orderItem->full_lbl_show           = $product->full_lbl_show;
                    $orderItem->product_details         = $product->toJson();
                    $orderItem->status                  = '1';
                    $orderItem->save();
                }

                $orderIntent->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'product_order_id' => $order->id,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified successfully'
            ]);
        } catch (\Exception $th) {
            Log::error("Payment verification failed: " . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed'
            ]);
        }
    }
}
