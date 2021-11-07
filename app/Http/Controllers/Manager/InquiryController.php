<?php

namespace App\Http\Controllers\Manager;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class InquiryController extends Controller
{
    public function index()
    {
        $select = [
            'customers.customer_name',
            'inquiries.id',
            'inquiries.project_name',
            'inquiries.total',
            'inquiries.date',
            'inquiries.timeline',
            'users.name',
            'items.item_description',
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'")
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('items', 'items.id' ,'=', 'inquiry_order.item_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);

        $data = [
            'title'   => 'View Inquiries',
            'user'    => Auth::user(),
            'inquires'=> $inquires
        ];
        return view('manager.inquiry.view',$data);
    }

    public function open()
    {

    $select = [
            'customers.customer_name',
            'inquiries.id',
            'inquiries.project_name',
            'inquiries.total',
            'inquiries.date',
            'inquiries.timeline',
            'users.name',
            'items.item_description',
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'")
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('items', 'items.id' ,'=', 'inquiry_order.item_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            ->whereNull('quotations.id')
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);

        $data = [
            'title'   => 'View Inquiries',
            'user'    => Auth::user(),
            'inquires'=> $inquires
        ];
        return view('manager.inquiry.open',$data);
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
        return view('manager.inquiry.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'timeline'       => 'required',
            'total'          => 'required',
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
            'amount'         => 'required|array',
            'amount.*'       => 'required',
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
            route('inquiry.list.manager')
        )->with('success', 'Inquiry was added successfully!');
    }

    public function update(Request $request,$id)
    {
        $inquiry = Inquiry::find($id);

        if(!$inquiry)
        {
            return redirect(
                route('inquiry.list.manager')
            )->with('error', 'Inquiry doesn\'t exists!');
        }

        $request->validate([
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'currency'       => 'required',
            'timeline'       => 'required',
            'total'          => 'required',
            'remarks'        => 'sometimes',
            'rate'           => 'required|array',
            'rate.*'         => 'required',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'amount'         => 'required|array',
            'amount.*'       => 'required',
            'inquiry_file'   => 'required|array',
            'inquiry_file.*' => 'required|',
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.'
        ]);

        $inquiry->customer_id = $request->customer_id;
        $inquiry->project_name = $request->project_name;
        $inquiry->date = $request->date;
        $inquiry->currency = $request->currency;
        $inquiry->discount = $request->discount;
        $inquiry->remarks = $request->remarks;
        $inquiry->total = $request->total;
        $inquiry->save();

        $inquiry_item = InquiryOrder::where('inquiry_id',$inquiry->id)->delete();

        $items      = $request->item_id;
        $categories = $request->category_id;
        $brands     = $request->brand_id;
        $quantities = $request->quantity;
        $units      = $request->unit;
        $rates      = $request->rate;
        $amounts    = $request->amount;

        $data = $request->all();
        $inquiry = new Inquiry($data);
        $inquiry->save();

        $save = [];

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
            route('inquiry.list.manager')
        )->with('success', 'Inquiry was updated successfully!');
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
        $customers = Customer::orderBy('id','DESC')->get();
        $brands    = Brand::orderBy('id','DESC')->get();
        $items     = Item::select([
            DB::raw("DISTINCT item_name"),
        ])->orderBy('id','DESC')->get();

        $inquiry = Inquiry::select('*')
            ->join('customers','customers.id','=','inquiries.customer_id')
            ->join('inquiry_order','inquiry_order.inquiry_id','=','inquiries.id')
            ->where('inquiries.id', $id)
            ->first();

        # If inquiry was not found
        if (!$inquiry) return redirect()->back()->with('error', 'Inquiry not found');

        $select = [
            "inquiry_order.*",
            "items.item_name"
        ];

        $inquiry->items = InquiryOrder::select()
            ->join('items', 'items.id', '=', 'inquiry_order.item_id')
            ->where('inquiry_id', $id)
            ->get();

        $data = [
            'title'     => 'Edit Inquiry',
            'base_url'  => env('APP_URL', 'http://omnibiz.local'),
            'user'      => Auth::user(),
            'inquiry'   => $inquiry,
            'brands'    => $brands,
            'customers' => $customers,
            'items'     => $items
        ];

        return view('manager.inquiry.edit', $data);
    }

    public function view($id)
    {
        $inquires = Inquiry::select('*')
            ->where('inquiries.id',$id)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->get();

        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
            'inquiry'=> $inquires
        ];
        return view('manager.inquiry.item',$data);
    }

    public function delete($id)
    {
        $document = InquiryDocument::where('inquiry_id',$id)->delete();
        $order    = InquiryOrder::where('inquiry_id',$id)->delete();
        $inquiry = Inquiry::find($id)->delete();
        return redirect(
            route('inquiry.list.manager')
        )->with('success', 'Inquiry deleted successfully!');
    }
}
