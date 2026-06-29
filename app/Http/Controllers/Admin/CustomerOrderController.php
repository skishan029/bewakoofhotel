<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = 'Customer Order';
        $status = request()->query('status', 'pending');
        $keyword = request()->query('keyword', null);
        $from_date = request()->query('from_date', null);
        $to_date = request()->query('to_date', null);

        $query = ProductOrder::query()
            ->onlyCustomer()
            ->where('order_status', $status)
            ->when($keyword, function ($q, $keyword) {
                return $q->where('order_no', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->orWhereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('username', 'LIKE', "%$keyword%")
                    ->orWhere('phone_number', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%");
                });
            })
            ->when($from_date, function ($q, $from_date) {
                return $q->whereDate('created_at', '>=', date('Y-m-d', strtotime($from_date)));
            })
            ->when($to_date, function ($q, $to_date) {
                return $q->whereDate('created_at', '<=', date('Y-m-d', strtotime($to_date)));
            })
            ->latest();

        if (Auth::guard('admin')->user()->user_type == '1') {
            $data['orders'] = $query->paginate(20);
        } else {
            $data['orders'] = $query->limit(20)->paginate(10);
        }

        $data['title'] = 'All ' . str_replace('_', ' ', ucfirst($status)) . ' Invoice';
        return view('admin.customerorder.index', $data);
    }

    public function details($order_key)
    {
        $data['page_title'] = 'Customer Order Details';
        $data['order'] = ProductOrder::onlyCustomer()->where('order_key', $order_key)->with(['customer', 'order_items', 'order_items.product', 'region', 'sub_region'])->first();
        return view('admin.customerorder.details', $data);
    }

    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id'  => 'required|integer',
                'status'    => 'required|in:accepted,preparing,out_for_delivery,delivered,cancelled',
            ]);

            DB::transaction(function () use ($validated) {
                $order = ProductOrder::onlyCustomer()->findOrFail($validated['order_id']);
                $order->order_status = $validated['status'];
                if ($validated['status'] == 'delivered') {
                    $order->status          = '2';
                    $order->given_amount    = $order->grand_total;
                    $order->return_amount   = 0;
                    \App\Models\ProductOrderItem::where('product_order_id', $order->id)->update([
                        'status' => '2',
                    ]);

                    if ($order->payment_option == 'cash') {
                        $order->is_paid         = 1;
                    }
                }
                $order->save();
            });
            return response()->json(['status' => 'success', 'message' => 'Order status updated successfully']);
        } catch (ValidationException $th) {
            return response()->json(['status' => 'error', 'message' => [$th->validator->errors()->first()]]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Order not found']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
        }
    }
}
