<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorQuotationController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Vendor Quotation',
            'user'    => Auth::user(),
        ];
        return view('admin.vendorquotation.view',$data);
    }

    public function add()
    {
        $vendors = Vendor::orderBy('id','DESC')->paginate($this->count);
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $data = [
            'title'       => 'Add Vendor Quotation',
            'base_url'    => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'        => Auth::user(),
            'vendors'     => $vendors,
            'categories'  => $categories
        ];
        return view('admin.vendorquotation.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Vendor Quotation',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.vendorquotation.edit', $data);
    }
}
