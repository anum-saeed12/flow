<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Items',
            'user'    => Auth::user(),
        ];
        return view('admin.item.view',$data);
    }

    public function add()
    {
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $brands    = Brand::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Add Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'categories' => $categories,
        ];
        return view('admin.item.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.item.edit', $data);
    }
}
