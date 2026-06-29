<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    function index(Request $request)
    {
        $keyword = request()->query('keyword', null);
        $from_date = request()->query('from_date', null);
        $to_date = request()->query('to_date', null);

        $query = \App\Models\ProductOrder::onlyNonCustomer()
            ->where('status', '2')
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('order_no', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->when($from_date, function ($q) use ($from_date) {
                $q->whereDate('created_at', '>=', date('Y-m-d', strtotime($from_date)));
            })
            ->when($to_date, function ($q) use ($to_date) {
                $q->whereDate('created_at', '<=', date('Y-m-d', strtotime($to_date)));
            })
            ->latest();

        if (Auth::guard('admin')->user()->user_type == '1') {
            $data['orders'] = $query->latest()->paginate(20);
        } else {
            $data['orders'] = $query->limit(20)->latest()->get();
        }



        $data['title'] = 'All Invoice';
        return view('admin.order.index', $data);
    }
    function pendingIndex(Request $request)
    {
        $keyword = request()->query('keyword', null);
        $from_date = request()->query('from_date', null);
        $to_date = request()->query('to_date', null);

        $query = \App\Models\ProductOrder::onlyNonCustomer()
            ->where('status', '1')
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('order_no', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->when($from_date, function ($q) use ($from_date) {
                $q->whereDate('created_at', '>=', date('Y-m-d', strtotime($from_date)));
            })
            ->when($to_date, function ($q) use ($to_date) {
                $q->whereDate('created_at', '<=', date('Y-m-d', strtotime($to_date)));
            })
            ->latest();

        if (Auth::guard('admin')->user()->user_type == '1') {
            $data['orders'] = $query->latest()->paginate(20);
        } else {
            $data['orders'] = $query->limit(20)->latest()->get();
        }

        $data['title'] = 'All Pending Invoice';
        return view('admin.order.index', $data);
    }
    public function placeOrderForm(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "id"    => "required|integer",

            ]);

            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $productOrder = \App\Models\ProductOrder::onlyNonCustomer()->find($request->id);
                if (blank($productOrder)) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid  application id']);
                }

                $data['productOrder'] = $productOrder;
                $html = view('admin.order.payfrm', $data)->render();


                $output = ['type' => 'success', 'message' => 'Successfully fetch data', 'html' => $html, 'status' => $request->status];
            }
            return response()->json($output);
        }
    }

    public function placeOrderSubmit(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "placeorder_id"     => "required|integer",
                "name"              => "nullable|string",
                "whatsapp_number"   => "nullable|string",
                "payment_option"    => "required|string",
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $productOrder = \App\Models\ProductOrder::onlyNonCustomer()->find($request->placeorder_id);

                if (blank($productOrder)) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid application id']);
                }

                $productOrder->name             = (empty($request->name)) ? NULL : strip_tags(trim($request->name));
                $productOrder->whatsapp_number  = (empty($request->whatsapp_number)) ? NULL : strip_tags(trim($request->whatsapp_number));
                $productOrder->payment_option   = (empty($request->payment_option)) ? NULL : strip_tags(trim($request->payment_option));
                $productOrder->status           = '2';
                $productOrder->is_paid          = true;
                $productOrder->save();

                $productOrderItem = \App\Models\ProductOrderItem::where('product_order_id', $productOrder->id)->get();
                if (!blank($productOrderItem)) {
                    foreach ($productOrderItem as $item) {
                        $item->status           = '2';
                        $item->save();
                    }
                }


                $output = ['type' => 'success', 'message' => "{$productOrder->order_no} is paid successfully"];
            }
            return response()->json($output);
        }
    }

    function create(Request $request)
    {

        if ($request->ajax()) {
            // dd($request->all());

            $grand_total = $_POST['grand_total'];

            $validator =  validator($request->all(), [
                "name"              => "nullable|string",
                "whatsapp_number"   => "nullable|string",
                "alt_number"        => "nullable|string",
                "address"           => "nullable|string",
                "payment_option"    => "required|string",
                "transaction_no"    => "nullable|string",
                "discount"          => "nullable|numeric",
                "given_amount"      => "required|numeric|min:{$grand_total}",

                "product_ids.*" => "required|integer",
                "plate_types.*" => "required|in:1,2",
                "quantitys.*"   => "required|integer",
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            if (!empty($request->pending)) {
                $status = 1;
                $is_paid = false;
            } else {
                $status = 2;
                $is_paid = true;
            }

            $productOrder = new \App\Models\ProductOrder();
            $productOrder->order_no         = \App\Helper\Clib::getOrderNo();
            $productOrder->order_key        = md5($productOrder->order_no . time());
            $productOrder->order_date       = date('Y-m-d');
            $productOrder->name             = (empty($request->name)) ? NULL : strip_tags(trim($request->name));
            $productOrder->whatsapp_number  = (empty($request->whatsapp_number)) ? NULL : strip_tags(trim($request->whatsapp_number));
            $productOrder->alt_number       = (empty($request->alt_number)) ? NULL : strip_tags(trim($request->alt_number));
            $productOrder->address          = (empty($request->name)) ? NULL : strip_tags(trim($request->address));
            $productOrder->payment_option   = (empty($request->payment_option)) ? NULL : strip_tags(trim($request->payment_option));
            $productOrder->transaction_no   = (empty($request->transaction_no)) ? NULL : strip_tags(trim($request->transaction_no));
            $productOrder->status           = (empty($status)) ? NULL : strip_tags(trim($status));
            $productOrder->is_paid          = $is_paid;
            $productOrder->save();



            $subtotal = 0;
            if (!empty($request->product_ids)) {

                for ($i = 0; $i < count($request->product_ids); $i++) {
                    if (!empty($request->quantitys[$i])) {
                        if (intval($request->quantitys[$i]) > 0) {

                            $productOrderItem = \App\Models\ProductOrderItem::where([
                                ['product_order_id', '=', $productOrder->id],
                                ['product_id', '=', $request->product_ids[$i]],
                                ['plate_type', '=', $request->plate_types[$i]],
                            ])->first();
                            if (blank($productOrderItem)) {
                                $product = \App\Models\Product::find($request->product_ids[$i]);
                                if (!blank($product)) {
                                    $productOrderItem = new \App\Models\ProductOrderItem();
                                    $productOrderItem->product_order_id = $productOrder->id;
                                    $productOrderItem->product_id       = $product->id;
                                    $productOrderItem->plate_type       = $request->plate_types[$i];
                                    $productOrderItem->quantity         = intval($request->quantitys[$i]);
                                    $productOrderItem->product_name     = $product->product_name;
                                    $productOrderItem->full_lbl_show    = $product->full_lbl_show;
                                    $productOrderItem->status           = (empty($status)) ? NULL : strip_tags(trim($status));
                                    if ($productOrderItem->plate_type == '1') {
                                        $productOrderItem->price = floatval($product->full_price);
                                    } elseif ($productOrderItem->plate_type == '2') {
                                        $productOrderItem->price = floatval($product->half_price);
                                    }
                                    $productOrderItem->total = $productOrderItem->price * $productOrderItem->quantity;
                                    $subtotal = floatval($subtotal) + floatval($productOrderItem->total);
                                    $productOrderItem->product_details = $product->toJson();
                                    $productOrderItem->save();
                                }
                            } else {
                                $productOrderItem->quantity = $productOrderItem->quantity + intval($request->quantitys[$i]);
                                $productOrderItem->total    = $productOrderItem->price * $productOrderItem->quantity;
                                $subtotal = floatval($subtotal) + floatval($productOrderItem->total);
                                $productOrderItem->save();
                            }
                        }
                    }
                }
            }
            $productOrder->sub_total = floatval($subtotal);
            $productOrder->grand_total = $productOrder->sub_total;
            if (!empty($request->discount)) {
                $productOrder->discount = floatval($request->discount);
                $productOrder->grand_total = floatval($productOrder->grand_total) - floatval($request->discount);
            }
            $productOrder->given_amount = floatval($request->given_amount);
            if (floatval($productOrder->given_amount) >= floatval($productOrder->grand_total)) {
                $productOrder->return_amount = floatval($productOrder->given_amount) - floatval($productOrder->grand_total);
            } else {
                $productOrder->return_amount = 0;
            }
            $productOrder->save();

            return response()->json(['type' => 'success', 'message' => "Successfully submit your invoice & invoice no {$productOrder->order_no}", 'url' => route('admin.order.summary', ['order_key' => $productOrder->order_key])]);
        }

        $data['title'] = 'Create Invoice';
        $data['submitURL'] = route('admin.order.create');
        $data['allproducts'] = \App\Models\Product::orderBy('ordering', 'asc')->get();
        return view('admin.order.create', $data);
    }
    function edit(Request $request, $id)
    {
        $productOrder = \App\Models\ProductOrder::with(['order_items'])->find($id);
        if (blank($productOrder)) {
            return redirect()->route('admin.order.index');
        }

        abort_if(blank(Auth::guard('admin')->user()->user_type != '1'), 401);

        if ($request->ajax()) {

            $grand_total = $_POST['grand_total'];

            $validator =  validator($request->all(), [
                "name"              => "nullable|string",
                "whatsapp_number"   => "nullable|string",
                "alt_number"        => "nullable|string",
                "address"           => "nullable|string",
                "payment_option"    => "required|string",
                "transaction_no"    => "nullable|string",
                "discount"          => "nullable|numeric",
                "given_amount"      => "required|numeric|min:{$grand_total}",

                "product_ids.*" => "required|integer",
                "plate_types.*" => "required|in:1,2",
                "quantitys.*"   => "required|integer",
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            $productOrder->name             = (empty($request->name)) ? NULL : strip_tags(trim($request->name));
            $productOrder->whatsapp_number  = (empty($request->whatsapp_number)) ? NULL : strip_tags(trim($request->whatsapp_number));
            $productOrder->alt_number       = (empty($request->alt_number)) ? NULL : strip_tags(trim($request->alt_number));
            $productOrder->address          = (empty($request->name)) ? NULL : strip_tags(trim($request->address));
            $productOrder->payment_option   = (empty($request->payment_option)) ? NULL : strip_tags(trim($request->payment_option));
            $productOrder->transaction_no   = (empty($request->transaction_no)) ? NULL : strip_tags(trim($request->transaction_no));
            $productOrder->save();

            \App\Models\ProductOrderItem::where('product_order_id', $id)->delete();
            $subtotal = 0;
            if (!empty($request->product_ids)) {

                for ($i = 0; $i < count($request->product_ids); $i++) {
                    if (!empty($request->quantitys[$i])) {
                        if (intval($request->quantitys[$i]) > 0) {

                            $productOrderItem = \App\Models\ProductOrderItem::where([
                                ['product_order_id', '=', $productOrder->id],
                                ['product_id', '=', $request->product_ids[$i]],
                                ['plate_type', '=', $request->plate_types[$i]],
                            ])->first();
                            if (blank($productOrderItem)) {
                                $product = \App\Models\Product::find($request->product_ids[$i]);
                                if (!blank($product)) {
                                    $productOrderItem = new \App\Models\ProductOrderItem();
                                    $productOrderItem->product_order_id = $productOrder->id;
                                    $productOrderItem->product_id       = $product->id;
                                    $productOrderItem->plate_type       = $request->plate_types[$i];
                                    $productOrderItem->quantity         = intval($request->quantitys[$i]);
                                    $productOrderItem->product_name     = $product->product_name;
                                    $productOrderItem->full_lbl_show    = $product->full_lbl_show;
                                    if ($productOrderItem->plate_type == '1') {
                                        $productOrderItem->price = floatval($product->full_price);
                                    } elseif ($productOrderItem->plate_type == '2') {
                                        $productOrderItem->price = floatval($product->half_price);
                                    }
                                    $productOrderItem->total = $productOrderItem->price * $productOrderItem->quantity;
                                    $subtotal = floatval($subtotal) + floatval($productOrderItem->total);
                                    $productOrderItem->product_details = $product->toJson();
                                    $productOrderItem->save();
                                }
                            } else {
                                $productOrderItem->quantity = $productOrderItem->quantity + intval($request->quantitys[$i]);
                                $productOrderItem->total    = $productOrderItem->price * $productOrderItem->quantity;
                                $subtotal = floatval($subtotal) + floatval($productOrderItem->total);
                                $productOrderItem->save();
                            }
                        }
                    }
                }
            }
            $productOrder->sub_total = floatval($subtotal);
            $productOrder->grand_total = $productOrder->sub_total;
            if (!empty($request->discount)) {
                $productOrder->discount = floatval($request->discount);
                $productOrder->grand_total = floatval($productOrder->grand_total) - floatval($request->discount);
            }
            $productOrder->given_amount = floatval($request->given_amount);
            if (floatval($productOrder->given_amount) >= floatval($productOrder->grand_total)) {
                $productOrder->return_amount = floatval($productOrder->given_amount) - floatval($productOrder->grand_total);
            } else {
                $productOrder->return_amount = 0;
            }
            $productOrder->save();
            if ($productOrder->status == 1) {
                $url = route('admin.order.pendingindex');
            } else {
                $url = route('admin.order.index');
            }

            return response()->json(['type' => 'success', 'message' => "Successfully update your invoice & invoice no {$productOrder->order_no}", 'url' => $url]);
        }

        $data['title'] = 'Edit Invoice';
        $data['submitURL'] = route('admin.order.edit', ['id' => $id]);
        $data['allproducts'] = \App\Models\Product::orderBy('ordering', 'asc')->get();
        $data['productOrder'] = $productOrder;
        return view('admin.order.edit', $data);
    }
    function details(Request $request, $order_key)
    {
        $productOrder = \App\Models\ProductOrder::onlyNonCustomer()->with(['order_items'])->where([
            ['order_key', '=', $order_key]
        ])->first();
        if (blank($productOrder)) {
            return redirect()->route('admin.order.index');
        }
        $data['title'] = 'Order Details';
        $data['productOrder'] = $productOrder;
        return view('admin.order.details', $data);
    }
    function print(Request $request, $order_key)
    {
        $productOrder = \App\Models\ProductOrder::with(['order_items'])->where([
            ['order_key', '=', $order_key]
        ])->first();

        abort_if(blank($productOrder), 404);
        $data['productOrder'] = $productOrder;
        return view('admin.order.print', $data);
    }
    function report(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));

                $start_time = date('H:i:s', strtotime($request->start_date));
                $end_time = date('H:i:s', strtotime($request->end_date));

                $data = \App\Models\ProductOrder::where('status', '2')->whereDate('created_at', '>=', $start_date)->whereTime('created_at', '>=', $start_time)->whereDate('created_at', '<=', $end_date)->whereTime('created_at', '<=', $end_time)->latest()->get();
                //$data = \App\Models\ProductOrder::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();
            } else {
                $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

                $data = \App\Models\ProductOrder::where('status', '2')->whereDate('created_at', '>=', $startOfMonth)->whereDate('created_at', '<=', $endOfMonth)->latest()->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group">';

                    //$url = route('admin.productmaster.product.edit', ['id'=> $row->id]);
                    //$btn .= \App\Helper\Helper::commonEditButton($url);
                    //$btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '2', '1');

                    $btn .= \App\Helper\Helper::adminDataTableLink([
                        'url' => '#',
                        'label' => '<i class="fas fa-eye"></i>',
                    ]);

                    $btn .= \App\Helper\Helper::adminDataTableButton([
                        'title' => 'Print',
                        'label' => '<i class="fas fa-print"></i>',
                        'value' => route('admin.order.print', ['order_key' => $row->order_key]),
                        'onclick' => 'printInvoice(this.value)'
                    ]);

                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('created_date', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->editColumn('created_time', function ($row) {
                    return date('h:i:s a', strtotime($row->created_at));
                })
                ->editColumn('order_no', function ($row) {
                    return $row->order_no;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('grand_total', function ($row) {
                    return number_format($row->grand_total, '2', '.', '');
                })
                ->addColumn('onlinepayment', function ($row) {
                    if ($row->payment_option != 'cash') {
                        return number_format($row->grand_total, '2', '.', '');
                    } else {
                        return '';
                    }
                })
                ->editColumn('payment_option', function ($row) {
                    return \App\Helper\Helper::paymentOption($row->payment_option);
                })
                ->rawColumns(['action', 'created_date', 'created_time', 'order_no', 'name', 'grand_total', 'onlinepayment', 'payment_option'])
                ->make(true);
        }
        $data['table'] = collect([
            'created_date'    => 'C. Date',
            'created_time'    => 'C. Time',
            'order_no'      => 'Inv No',
            'name'          => 'Cus Name',
            'payment_option' => 'Payment Option',
            // 'onlinepayment' =>'Online',
            'grand_total'   => 'Grand Total',
            //'action'        =>'Action',
        ]);
        $data['title'] = 'Report';
        $data['dataTableURL'] = route('admin.report.index');
        return view('admin.order.report', $data);
    }
    function productReport(Request $request)
    {

        if ($request->ajax()) {
            //use($start_date, $end_date)

            $start_date = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
            $data = \App\Models\Product::latest()->get();
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group">';

                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('product_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('start_date', function ($row) use ($start_date) {
                    return date('d-m-Y', strtotime($start_date));
                })
                ->addColumn('close_date', function ($row) use ($end_date) {
                    return date('d-m-Y', strtotime($end_date));
                })
                ->addColumn('quantity_full', function ($row) use ($start_date, $end_date) {
                    return \App\Models\ProductOrderItem::where([
                        ['product_id', '=', $row->id],
                        ['plate_type', '=', '1'],
                        ['status', '=', '2']
                    ])->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('quantity');
                })
                ->addColumn('quantity_half', function ($row) use ($start_date, $end_date) {
                    return \App\Models\ProductOrderItem::where([
                        ['product_id', '=', $row->id],
                        ['plate_type', '=', '2'],
                        ['status', '=', '2']
                    ])->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('quantity');
                })
                ->editColumn('total', function ($row) use ($start_date, $end_date) {
                    $total = \App\Models\ProductOrderItem::where([
                        ['product_id', '=', $row->id],
                        ['status', '=', '2']
                    ])->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('total');
                    return number_format($total, '2', '.', '');
                })
                ->rawColumns(['action', 'product_name', 'start_date', 'close_date', 'quantity_full', 'quantity_half', 'total'])
                ->make(true);
        }
        $data['table'] = collect([
            'product_name'  => 'Product Name',
            'start_date'    => 'Start Date',
            'close_date'    => 'Close Date',
            'quantity_full' => 'Full Plate',
            'quantity_half' => 'Half Plate',
            'total'         => 'Total',
            //'action'        =>'Action',
        ]);
        $data['title'] = 'Product Report';
        $data['dataTableURL'] = route('admin.report.product');
        return view('admin.order.reportproduct', $data);
    }
    function orderSummary(Request $request, $order_key)
    {
        $productOrder = \App\Models\ProductOrder::where([
            ['order_key', '=', $order_key]
        ])->first();

        abort_if(blank($productOrder), 404);

        $data['title'] = 'Order Summary';
        $data['productOrder'] = $productOrder;
        return view('admin.order.summary', $data);
    }
}
