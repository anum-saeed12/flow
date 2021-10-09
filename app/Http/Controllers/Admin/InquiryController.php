<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
        ];
        return view('admin.inquiry.view',$data);
    }

    public function open()
    {
        $data = [
            'title'   => 'Open Inquires',
            'user'    => Auth::user(),
        ];
        return view('admin.inquiry.open',$data);
    }

    public function add()
    {
        $customers = Customer::orderBy('id','DESC')->paginate($this->count);
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $brands    = Brand::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Submit Inquiry',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'categories' => $categories,
            'customers' => $customers,
        ];
        return view('admin.inquiry.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Inquiry',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.inquiry.edit', $data);
    }
}
