<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = request()->query('keyword', null);
        $is_verified = request()->query('is_verified', null);
        $is_active = request()->query('is_active', null);

        $data['customers'] = Customer::with(['region','sub_region'])
        ->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%")->orWhere('username', 'like', "%{$keyword}%");
        })
        ->when($is_verified, function ($query) use ($is_verified) {
            $query->where('is_verified', ($is_verified == 'verified' ? true : false));
        })
        ->when($is_active, function ($query) use ($is_active) {
            $query->where('is_active', ($is_active == 'active' ? true : false));
        })
        ->latest()->paginate(10);
        
        $data['title'] = 'All Customer';
        return view('admin.customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
