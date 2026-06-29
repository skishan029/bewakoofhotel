<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data['categories'] = \App\Models\ProductCategory::orderBy('cat_title', 'asc')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('cat_title', 'like', '%' . $search . '%')
                ->orWhere('cat_desc', 'like', '%' . $search . '%');
            });
        })
        ->get();
        
        $data['title'] = 'Explore Menu';
        return view('customer.category.index', $data);
    }
}
