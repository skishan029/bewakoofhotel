<?php

namespace App\Http\Controllers\Admin\ProductMaster;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = \App\Models\Product::withTrashed()->orderByRaw('-deleted_at asc')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group">';
                    if (Auth::guard('admin')->user()->user_type == '1') {
                        if ($row->trashed()) {
                            $btn .= \App\Helper\Helper::commonDisableEditButton();
                            $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '1', '2');
                        } else {
                            $url = route('admin.productmaster.product.edit', ['id' => $row->id]);
                            $btn .= \App\Helper\Helper::commonEditButton($url);
                            $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '2', '1');
                        }
                    }
                    if (!$row->trashed()) {
                        $btn .= '
                            <div class="ml-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="customSwitch_' . $row->id . '" ' . ($row->is_active ? 'checked' : '') . ' onchange="changeStatus(' . $row->id . ')">
                                <label class="custom-control-label" for="customSwitch_' . $row->id . '"></label>
                            </div>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('product_name', function ($row) {
                    return $row->product_name;
                })
                ->editColumn('full_price', function ($row) {
                    return number_format($row->full_price, '2', '.', '');
                })
                ->editColumn('half_price', function ($row) {
                    return (empty($row->half_price)) ? '--' :  number_format($row->half_price, '2', '.', '');
                })
                ->addColumn('is_active', function ($row) {
                    return $row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                })
                ->rawColumns(['action', 'product_code', 'product_name', 'full_price', 'half_price', 'is_active', 'category_name'])
                ->make(true);
        }
        $data['title'] = 'All Products';
        $data['table'] = collect([
            'product_code'      => 'P.Code',
            'product_name'      => 'Name',
            'full_price'        => 'Full Price',
            'half_price'        => 'Half Price',
            'is_active'         => 'Status',
            'action'            => 'Action',
        ]);
        $data['dataTableURL'] = route('admin.productmaster.product.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.product');
        return view('admin.productmaster.product.index', $data);
    }
    public function create(Request $request)
    {

        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "product_name"      => "required|string",
                "product_name_english" => "required|string",
                "product_name_online" => "required|string",
                "featured_photo"    => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "product_desc"      => "nullable|string",
                "full_price"        => "nullable|numeric",
                "half_price"        => "nullable|numeric",
                "sku_code"          => "nullable|string",
                "full_lbl_show"     => "required|in:1,2",
                "ordering"          => "required|integer",
                "half_photo"        => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "shipping_charge"   => "nullable|numeric",
                "category_id"       => "nullable|array",
                "category_id.*"     => "nullable|exists:product_categories,id",
                "is_active"         => "required|in:0,1",
                "is_online_label_show" => "required|in:0,1",
                "is_split"          => "required|in:0,1",
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            try {
                DB::transaction(function () use ($request) {
                    $product = new \App\Models\Product();
                    $product->product_code              = \App\Helper\Clib::generateCode(new \App\Models\Product(), 'P');
                    $product->product_name              = strip_tags($request->product_name);
                    $product->product_name_english      = strip_tags($request->product_name_english);
                    $product->product_name_online       = strip_tags($request->product_name_online);
                    $product->product_desc              = (empty($request->product_desc)) ? NULL : strip_tags($request->product_desc);
                    $product->full_price                = (empty($request->full_price)) ? NULL : floatval($request->full_price);
                    $product->shipping_charge           = (empty($request->shipping_charge)) ? NULL : floatval($request->shipping_charge);
                    $product->half_price                = (empty($request->half_price)) ? NULL : floatval($request->half_price);
                    $product->sku_code                  = (empty($request->sku_code)) ? NULL : strip_tags($request->sku_code);
                    $product->full_lbl_show             = (empty($request->full_lbl_show)) ? NULL : strip_tags($request->full_lbl_show);
                    $product->ordering                  = (empty($request->ordering)) ? NULL : intval($request->ordering);
                    $product->is_active                 = $request->is_active;
                    $product->is_online_label_show      = $request->is_online_label_show;
                    $product->is_split                  = $request->is_split;
                    $product->save();

                    if (!empty($request->category_id)) {
                        foreach ($request->category_id as $category_id) {
                            CategoryProduct::updateOrCreate([
                                'product_id' => $product->id,
                                'category_id' => $category_id,
                            ], [
                                'product_id' => $product->id,
                                'category_id' => $category_id,
                            ]);
                        }
                    }

                    if (isset($_FILES['featured_photo'])) {
                        if ($_FILES['featured_photo']['error'] == 0) {
                            $product->featured_photo = strip_tags($request->file('featured_photo')->store('products'));
                            $product->save();
                        }
                    }
                    if (isset($_FILES['half_photo'])) {
                        if ($_FILES['half_photo']['error'] == 0) {
                            $product->half_photo = strip_tags($request->file('half_photo')->store('products'));
                            $product->save();
                        }
                    }
                });
                return response()->json(['type' => 'success', 'message' => 'Successfully create product']);
            } catch (\Exception $th) {
                return response()->json(['type' => 'error', 'message' => "Something went wrong", 'error' => $th->getMessage()]);
            }
        }
        $data['title'] = 'Create Product';
        $data['categories'] = \App\Models\ProductCategory::select(['id', 'cat_title'])->whereNull('parent_id')->get();
        $data['submitURL'] = route('admin.productmaster.product.create');

        return view('admin.productmaster.product.product', $data);
    }
    public function edit(Request $request, $id)
    {
        $product = \App\Models\Product::with(['categories'])->find($id);

        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "product_name"      => "required|string",
                "product_name_english"      => "required|string",
                "product_name_online" => "required|string",
                "featured_photo"    => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "product_desc"      => "nullable|string",
                "full_price"        => "nullable|numeric",
                "half_price"        => "nullable|numeric",
                "sku_code"          => "nullable|string",
                "full_lbl_show"     => "required|in:1,2",
                "ordering"          => "required|integer",
                "half_photo"        => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "shipping_charge"   => "nullable|numeric",
                "category_id"       => "nullable|array",
                "category_id.*"     => "nullable|exists:product_categories,id",
                "is_active"         => "required|in:0,1",
                "is_online_label_show" => "required|in:0,1",
                "is_split"          => "required|in:0,1",
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            try {
                DB::transaction(function () use ($request, $product) {
                    $product->product_name              = strip_tags($request->product_name);
                    $product->product_name_english      = strip_tags($request->product_name_english);
                    $product->product_name_online       = strip_tags($request->product_name_online);
                    $product->product_desc              = (empty($request->product_desc)) ? NULL : strip_tags($request->product_desc);
                    $product->full_price                = (empty($request->full_price)) ? NULL : floatval($request->full_price);
                    $product->shipping_charge           = (empty($request->shipping_charge)) ? NULL : floatval($request->shipping_charge);
                    $product->half_price                = (empty($request->half_price)) ? NULL : floatval($request->half_price);
                    $product->sku_code                  = (empty($request->sku_code)) ? NULL : strip_tags($request->sku_code);
                    $product->full_lbl_show             = (empty($request->full_lbl_show)) ? NULL : strip_tags($request->full_lbl_show);
                    $product->ordering                  = (empty($request->ordering)) ? NULL : intval($request->ordering);
                    $product->is_active                 = $request->is_active;
                    $product->is_online_label_show      = $request->is_online_label_show;
                    $product->is_split                  = $request->is_split;
                    $product->save();

                    CategoryProduct::where('product_id', $product->id)->whereNotIn('category_id', $request->category_id)->delete();
                    if (!empty($request->category_id)) {
                        foreach ($request->category_id as $category_id) {
                            CategoryProduct::updateOrCreate([
                                'product_id' => $product->id,
                                'category_id' => $category_id,
                            ], [
                                'product_id' => $product->id,
                                'category_id' => $category_id,
                            ]);
                        }
                    }

                    if (isset($_FILES['featured_photo'])) {
                        if ($_FILES['featured_photo']['error'] == 0) {
                            $oldfeatured_photo = $product->featured_photo;
                            $product->featured_photo = strip_tags($request->file('featured_photo')->store('products'));
                            $product->save();

                            \App\Helper\Helper::customFileDelete($oldfeatured_photo);
                        }
                    }
                    if (isset($_FILES['half_photo'])) {
                        if ($_FILES['half_photo']['error'] == 0) {
                            $oldhalf_photo = $product->half_photo;
                            $product->half_photo = strip_tags($request->file('half_photo')->store('products'));
                            $product->save();

                            \App\Helper\Helper::customFileDelete($oldhalf_photo);
                        }
                    }
                });

                $featured_photo = (empty($product->featured_photo)) ? NULL : Storage::url($product->featured_photo);
                $half_photo = (empty($product->half_photo)) ? NULL : Storage::url($product->half_photo);
                $output = [
                    'type' => 'success',
                    'message' => 'Successfully update product',
                    'featured_photo' => $featured_photo,
                    'half_photo' => $half_photo
                ];
                return response()->json($output);
            } catch (\Exception $th) {
                return response()->json(['type' => 'error', 'message' => 'Something went wrong']);
            }
        }
        if (blank($product)) {
            return redirect()->route('admin.productmaster.product.index');
        }
        $data['title'] = 'Edit Product';
        $data['submitURL'] = route('admin.productmaster.product.edit', ['id' => $product->id]);
        $data['product'] = $product;
        $data['categories'] = \App\Models\ProductCategory::select(['id', 'cat_title'])->whereNull('parent_id')->get();

        return view('admin.productmaster.product.product', $data);
    }
    public function changeStatus(Request $request, $id)
    {
        try {
            $product = \App\Models\Product::find($id);
            if (blank($product)) {
                return response()->json(['type' => 'error', 'message' => 'Product not found']);
            }
            $product->is_active = !$product->is_active;
            $product->save();
            return response()->json(['type' => 'success', 'message' => 'Product status ' . ($product->is_active ? 'activated' : 'deactivated') . ' successfully']);
        } catch (\Throwable $th) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong']);
        }
    }
}
