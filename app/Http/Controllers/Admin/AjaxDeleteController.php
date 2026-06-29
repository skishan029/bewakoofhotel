<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxDeleteController extends Controller
{
    private function changeStatus($model, $module)
    {
        //echo $module; exit;
        $request = Request();
        if ($request->isMethod('post')) {

            $validator = validator($request->all(), [
                'id'    => 'required|integer',
                'status' => "required|in:1,2",
            ]);
            if ($validator->fails()) {
                $output = array('type' => 'error', 'message' => $validator->errors()->all());
            } else {
                if ($request->status == '2') {
                    if ($model::withTrashed()->find($request->id)->delete()) {
                        $output = array('type' => 'success', 'message' => 'Successfully deleted ' . $module);
                    } else {
                        $output = array('type' => 'error', 'message' => 'Whoops! try again...');
                    }
                } elseif ($request->status == '1') {
                    if ($model::withTrashed()->find($request->id)->restore()) {
                        $output = array('type' => 'success', 'message' => 'Successfully restore ' . $module);
                    } else {
                        $output = array('type' => 'error', 'message' => 'Whoops! try again...');
                    }
                }
            }
            return response()->json($output);
        }
    }

    function userStatus()
    {
        return self::changeStatus(new \App\Models\User(), 'user');
    }
    function productStatus()
    {
        return self::changeStatus(new \App\Models\Product(), 'product');
    }
    function frequentlyAskedQuestionStatus()
    {
        return self::changeStatus(new \App\Models\FrequentlyAskedQuestion(), 'FAQ');
    }
    function testimonialStatus()
    {
        return self::changeStatus(new \App\Models\Testimonial(), 'testimonial');
    }
    function productCategoryStatus()
    {
        return self::changeStatus(new \App\Models\ProductCategory(), 'product category');
    }
    function regionStatus()
    {
        return self::changeStatus(new \App\Models\Region(), 'region');
    }
}
