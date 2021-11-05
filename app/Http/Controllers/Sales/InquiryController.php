<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryDocument;
use App\Models\InquiryOrder;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class InquiryController extends Controller
{
    public function index()
    {
        $select = [
            'inquiries.id','customer_name', 'project_name', 'item_description', 'amount', 'users.name', 'date', 'timeline'
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->where('inquiries.user_id','=',Auth::user()->id)
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);

        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
            'inquires'=> $inquires
        ];
        return view('sale.inquiry.view',$data);
    }

    public function open()
    {
        $select = [
            'inquiries.id','customer_name', 'project_name', 'item_description', 'amount', 'users.name', 'date', 'timeline'
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->where('inquiries.user_id','=',Auth::user()->id)
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);
        $data = [
            'title'   => 'Open Inquires',
            'user'    => Auth::user(),
            'inquires'=> $inquires
        ];
        return view('sale.inquiry.open',$data);
    }

    public function add()
    {
        $customers  = Customer::orderBy('id','DESC')->paginate($this->count);
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $brands     = Brand::orderBy('id','DESC')->paginate($this->count);
        $items      = Item::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'      => 'Submit Inquiry',
            'base_url'   => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'       => Auth::user(),
            'brands'     => $brands,
            'categories' => $categories,
            'customers'  => $customers,
            'items'      => $items
        ];
        return view('sale.inquiry.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'timeline'       => 'required',
            'total'       => 'required',
            'remarks'        => 'sometimes',
            'rate'           => 'required|array',
            'rate.*'         => 'required',
            'category_id'    => 'required|array',
            'category_id.*'  => 'required',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'amount'           => 'required|array',
            'amount.*'         => 'required',
            'inquiry_file'   => 'required|array',
            'inquiry_file.*' => 'required|',
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.'
        ]);

        $files      = $request->inquiry_file;
        $items      = $request->item_id;
        $categories = $request->category_id;
        $brands     = $request->brand_id;
        $quantities = $request->quantity;
        $units      = $request->unit;
        $rates      = $request->rate;
        $amounts    = $request->amount;

        $data = $request->all();
        $id=Auth::user()->id;
        $data['user_id']  = $id;
        $data['date']     = Carbon::parse($request->date)->format('Y-m-d');
        $data['timeline'] = Carbon::parse($request->timeline)->format('Y-m-d');
        $data['inquiry']  = Uuid::uuid4()->getHex();
        $inquiry = new Inquiry($data);
        $inquiry->save();

        $save = [];
        $save_document = [];

        foreach($files as $file) {
            $file_item = [
                'inquiry_id'   => $inquiry->id,
                'file_path'    => $this->uploadPDF($file)
            ];
            $save_document[] = (new InquiryDocument($file_item))->save();
        }

        foreach($categories as $index => $category) {
            $inquiry_item = [
                'inquiry_id'   => $inquiry->id,
                'category_id'  => $category,
                'item_id'      => $items[$index],
                'brand_id'     => $brands[$index],
                'quantity'     => $quantities[$index],
                'unit'         => $units[$index],
                'rate'         => $rates[$index],
                'amount'       => $amounts[$index],
            ];
            $save[] = (new InquiryOrder($inquiry_item))->save();
        }
        return redirect(
            route('inquiry.list.sale')
        )->with('success', 'Inquiry was added successfully!');
    }

    private function uploadPDF($file)
    {
        $filename  = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs('public/inquiry',$filename);
        $public_path  = Storage::url("inquiry/$filename");
        return $filename;
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Inquiry',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('sale.inquiry.edit', $data);
    }

    public function view($id)
    {
        $select = [
            'inquiries.id','customer_name', 'project_name', 'item_description', 'amount', 'users.name', 'date', 'timeline'
        ];
        $inquires = Inquiry::select('*')
            ->where('inquiries.id',$id)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->where('inquiries.user_id','=',Auth::user()->id)
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->get();
        $data = [
            'title'      => 'Inquiry',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'inquiry'  => $inquires
        ];
        return view('sale.inquiry.item',$data);
    }
}
