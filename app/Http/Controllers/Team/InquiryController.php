<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryDocument;
use App\Models\InquiryOrder;
use App\Models\Item;
use App\Models\UserCategory;
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
            'inquiries.currency',
            'inquiries.total',
            'inquiries.date',
            'inquiries.timeline',
            'users.name',
            DB::raw("GROUP_CONCAT(categories.category_name) as category_names"),
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'"),
            DB::raw("COUNT(inquiry_order.id) as item_count"),
            DB::raw("SUM(inquiry_order.amount) as amount")
        ];
        $inquiries = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            # The line below is a where clause which will only fetch the records for the specified category_id
            # In our case, we get the category_id from the userCategory table which is linked to the user_id
            ->whereIn('categories.id', UserCategory::select('category_id as id')->where('user_id', Auth::id())->get())
            # where IN (value1, value2, value3)
            # where IN ($array_of_objects)
            #->where('user_id', Auth::id()->get())
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);

        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
            'inquiries' => $inquiries
        ];
        return view('team.inquiry.view', $data);
    }

    public function open()
    {
        $select = [
            'customers.customer_name',
            'inquiries.id',
            'inquiries.project_name',
            'inquiries.currency',
            'inquiries.total',
            'inquiries.date',
            'inquiries.timeline',
            'users.name',
            DB::raw("GROUP_CONCAT(categories.category_name) as category_names"),
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'"),
            DB::raw("COUNT(inquiry_order.id) as item_count"),
            DB::raw("SUM(inquiry_order.amount) as amount")
        ];
        $inquiries = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            ->whereNull('quotations.id')
            # The line below is a where clause which will only fetch the records for the specified category_id
            # In our case, we get the category_id from the userCategory table which is linked to the user_id
            ->whereIn('categories.id', UserCategory::select('category_id as id')->where('user_id', Auth::id()))
            #->where('user_id', Auth::id()->get())
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->paginate($this->count);
        $data = [
            'title'   => 'Open Inquires',
            'user'    => Auth::user(),
            'inquiries' => $inquiries,
        ];
        return view('team.inquiry.open',$data);
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
        return view('team.inquiry.add', $data);
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
            route('inquiry.list.team')
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
        return view('team.inquiry.edit', $data);
    }

    public function view($id)
    {
        $select = [
            'inquiries.id',
            'inquiries.inquiry',
            'inquiries.project_name',
            'inquiries.date',
            'inquiries.timeline',
            'inquiries.currency',
            'inquiries.discount',
            'inquiries.remarks',
            'inquiries.created_at',
            'customers.customer_name',
            'customers.address',
            'customers.attention_person',
            DB::raw("SUM(inquiry_order.amount) as total"),
        ];
        $inquiry = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->where('inquiries.id', $id)
            # The line below is a where clause which will only fetch the records for the specified category_id
            # In our case, we get the category_id from the userCategory table which is linked to the user_id
            ->whereIn('inquiry_order.category_id', UserCategory::select('category_id')->where('user_id', Auth::id())->get())
            ->groupBy('inquiries.id','inquiry_order.inquiry_id')
            ->first();

        if (!$inquiry) return redirect()->back()->with("error","Inquiry not found!");

        $select_items = [
            "inquiry_order.quantity",
            "inquiry_order.unit",
            "inquiry_order.rate",
            "inquiry_order.amount",
            "items.item_name",
            "items.item_description",
            "brands.brand_name",
        ];
        $inquiry->items = InquiryOrder::select($select_items)
            ->leftJoin('brands', 'brands.id', '=', 'inquiry_order.brand_id')
            ->leftJoin('items', 'items.id', '=', 'inquiry_order.item_id')
            ->where('inquiry_id', $id)
            # The line below is a where clause which will only fetch the records for the specified category_id
            # In our case, we get the category_id from the userCategory table which is linked to the user_id
            ->whereIn('inquiry_order.category_id', UserCategory::select('category_id')->where('user_id', Auth::id())->get())
            ->get();

        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
            'inquiry'=> $inquiry
        ];
        return view('team.inquiry.item',$data);
    }
}
