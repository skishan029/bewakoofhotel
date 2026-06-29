<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    function getSubRegions(Request $request)
    {
        try {
            $region_id = $request->query('region_id', null);
            if (empty($region_id)) {
                return response()->json(['message'=> 'Region id is required'], 400);
            }
            $subRegions = \App\Models\Region::whereParentId($request->region_id)->get(['id','name']);
            return response()->json(['data'=>$subRegions, 'message'=> 'Sub regions fetched successfully'], 200);
        } catch (\Exception $th) {
            return response()->json(['message'=> 'Something went wrong'], 400);
        }
    }
}
