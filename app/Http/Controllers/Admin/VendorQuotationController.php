<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Vendor;
use App\Models\VendorQuotation;
use App\Models\VendorQuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class VendorQuotationController extends Controller
{
    public function index()
    {
        $select = ['vendor_quotation.quotation_pdf','vendor_quotation.id','vendor_name', 'project_name', 'vendor_quotation.total', 'users.name'];

        $vendors_quotation = VendorQuotation::select($select)
            ->leftJoin('vendors', 'vendor_quotation.vendor_id', '=', 'vendors.id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('vendor_quotation_item', 'vendor_quotation_item.vendor_quotation_id', '=', 'vendor_quotation.id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->paginate($this->count);
        $data = [
            'title'            => 'Vendor Quotation',
            'vendor_quotation' => $vendors_quotation,
            'user'             => Auth::user()
        ];
        return view('admin.vendorquotation.view',$data);
    }

    public function add()
    {
        $vendors = Vendor::orderBy('id','DESC')->get();
        $categories = Category::orderBy('id','DESC')->get();
        $items = Item::orderBy('id','DESC')->get();
        $brands = Brand::orderBy('id','DESC')->get();
        $data = [
            'title'       => 'Add Vendor Quotation',
            'base_url'    => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'        => Auth::user(),
            'vendors'     => $vendors,
            'categories'  => $categories,
            'brands'      => $brands,
            'items'       => $items
        ];
        return view('admin.vendorquotation.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'          => 'required',
            'quotation_ref'      => 'required',
            'quotation_pdf'      => 'required|file',
            'project_name'       => 'required',
            'total'              => 'required',
            'category_id'        => 'required|array',
            'category_id.*'      => 'required',
            'item_id'            => 'required|array',
            'item_id.*'          => 'required',
            'brand_id'           => 'required|array',
            'brand_id.*'         => 'required',
            'quantity'           => 'required|array',
            'quantity.*'         => 'required',
            'unit'               => 'required|array',
            'unit.*'             => 'required',
            'rate'               => 'required|array',
            'rate.*'             => 'required',
            'amount'             => 'required|array',
            'amount.*'           => 'required'
        ],[
            'vendor_id.required'     => 'The vendor field is required.',
        ]);

        $categories   = $request->category_id;
        $quantities   = $request->quantity;
        $units        = $request->unit;
        $rates        = $request->rate;
        $amounts      = $request->amount;
        $brands       = $request->brand_id;
        $items        = $request->item_id;

        $id = Auth::user()->id;
        $data = $request->all();
        $data['user_id']  = $id;
        $data['vendor_quotation'] = Uuid::uuid4()->getHex();
        $vendor_quotation = new VendorQuotation($data);
        $vendor_quotation['quotation_pdf']     =  $this->uploadPDF($request->file('quotation_pdf'));
        $vendor_quotation->save();

        $save = [];

        foreach($categories as $index => $category) {
            $item_detail = Item::select('*')
                ->where('item_name',$items[$index])
                ->where('brand_id', $brands[$index])
                ->first();
            $vendor_quotation_item = [
                'vendor_quotation_id' => $vendor_quotation->id,
                'category_id'         => $category,
                'brand_id'            => $brands[$index],
                'item_id'             => $item_detail->id,
                'quantity'            => $quantities[$index],
                'unit'                => $units[$index],
                'rate'                => $rates[$index],
                'amount'              => $amounts[$index],
            ];
            $save[] = (new VendorQuotationItem($vendor_quotation_item))->save();

        }
        return redirect(
            route('vendorquotation.list.admin')
        )->with('success', 'Vendor Quotation was added successfully!');
    }
    private function uploadPDF($file)
    {
        $filename  = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs('public/file',$filename);
        $public_path  = Storage::url("file/$filename");
        return $filename;
    }

    public function delete($id)
    {
        $items = VendorQuotationItem::where('vendor_quotation_id',$id)->delete();
        $quotation = VendorQuotation::find($id)->delete();
        return redirect(
            route('customerquotation.list.admin')
        )->with('success', 'Customer Quotation deleted successfully!');
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

    public function view($id)
    {
        $select = [
            '*',
            'vendor_quotation.total'
        ];
        $vendors_quotation = VendorQuotation::select($select)
            ->where('vendor_quotation.id',$id)
            ->leftJoin('vendor_quotation_item', 'vendor_quotation_item.vendor_quotation_id', '=', 'vendor_quotation.id')
            ->leftJoin('vendors', 'vendor_quotation.vendor_id', '=', 'vendors.id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->get();
        $data = [
            'title'            => 'Vendor Quotation',
            'user'             => Auth::user(),
            'quotation'        => $vendors_quotation
        ];
        return view('admin.vendorquotation.item',$data);
    }
}
