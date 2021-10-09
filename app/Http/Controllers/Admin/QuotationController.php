<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Quotations',
            'user'    => Auth::user(),
        ];
        return view('admin.quotation.view',$data);
    }
    public function customer()
    {
        $data = [
            'title'   => 'Customer Quotation',
            'user'    => Auth::user(),
        ];
        return view('admin.quotation.customer',$data);
    }

    public function add()
    {
        $customers = Customer::orderBy('id','DESC')->paginate($this->count);
        $brands    = Brand::orderBy('id','DESC')->paginate($this->count);


        $data = [
            'title'    => 'Submit Quotation',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'customers' => $customers,
        ];
        return view('admin.quotation.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Quotation',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
        ];
        return view('admin.quotation.edit', $data);
    }
}
