<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use Yajra\DataTables\DataTables;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Region::with('region:id,name')->withCount('subregion')->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="btn-group">';
                    if ($data->trashed()) {
                        $btn .= \App\Helper\Helper::commonDisableEditButton();
                        $btn .= \App\Helper\Helper::commonDeleteRestoreButton($data->id, '1', '2');
                    } else {
                        $url = route('admin.region.edit', ['region' => $data->id]);
                        $btn .= \App\Helper\Helper::commonEditButton($url);
                        $btn .= \App\Helper\Helper::commonDeleteRestoreButton($data->id, '2', '1');
                        /*
                        if (empty($data->parent_id)) {
                            if ($data->subregion_count == 0) {
                                $btn .= \App\Helper\Helper::commonDeleteRestoreButton($data->id, '2', '1');
                            }
                        } else {
                            $btn .= \App\Helper\Helper::commonDeleteRestoreButton($data->id, '2', '1');
                        }
                        */
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return date('d-M-Y', strtotime($data->created_at));
                })
                ->addColumn('region_name', function ($data) {
                    return $data->name;
                })
                ->editColumn('price', function ($data) {
                    return empty($data->price) ? NULL : number_format($data->price, '2', '.', ' ');
                })
                ->addColumn('parent_region', function ($data) {
                    return $data->region->name;
                })
                ->rawColumns(['action', 'created_at', 'region_name', 'price', 'parent_region'])
                ->make(true);
        }
        return view('admin.region.region');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Region';
        $data['submitURL'] = route('admin.region.store');
        $data['dataTableURL'] = route('admin.region.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.region');
        $data['parent_regions'] = Region::select(['id', 'name'])->whereNull('parent_id')->get();
        $data['table'] = collect([
            'created_at'    => 'C. Date',
            'region_name'   => 'Region Name',
            'parent_region' => 'Parent Region',
            'action'        => 'Action',
        ]);
        return view('admin.region.region', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator =  validator($request->all(), [
            "name"      => "required|string",
            "parent_id" => "nullable|integer",
            "price"     => "nullable|numeric",
        ]);
        if ($validator->fails()) {
            $output = ['type' => 'error', 'message' => $validator->errors()->all()];
        } else {
            $region = new Region();
            $region->name = strip_tags($request->name);
            $region->parent_id = (empty($request->parent_id)) ? NULL : strip_tags($request->parent_id);
            $region->price = (empty($request->price)) ? NULL : strip_tags($request->price);
            $region->save();
            $output = [
                'type' => 'success',
                'message' => 'Successfully create region',
                'parent_regions' => Region::select(['id', 'name'])->whereNull('parent_id')->get(),
            ];
        }
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $region = Region::findOrFail($id);
        $data['title'] = 'Edit Region';
        $data['submitURL'] = route('admin.region.update', ['region' => $region->id]);
        $data['dataTableURL'] = route('admin.region.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.region');
        $data['parent_regions'] = Region::select(['id', 'name'])->whereNull('parent_id')->get();
        $data['region'] = $region;
        $data['table'] = collect([
            'created_at'    => 'C. Date',
            'region_name'   => 'Region Name',
            'parent_region' => 'Parent Region',
            'action'        => 'Action',
        ]);
        return view('admin.region.region', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "name"      => "required|string",
                "parent_id" => "nullable|integer",
                "price"     => "nullable|numeric",
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $region = Region::findOrFail($id);
                if (blank($region)) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid id']);
                }
                $region->name = strip_tags($request->name);
                $region->parent_id = (empty($request->parent_id)) ? NULL : strip_tags($request->parent_id);
                $region->price = (empty($request->price)) ? NULL : strip_tags($request->price);
                $region->save();
                $output = [
                    'type' => 'success',
                    'message' => 'Successfully update region',
                    'parent_regions' => Region::select(['id', 'name'])->whereNull('parent_id')->get(),
                ];
            }
            return response()->json($output);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
