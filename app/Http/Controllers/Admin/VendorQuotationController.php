<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $select = ['vendor_quotation.id','vendor_name', 'project_name', 'item_description', 'vendor_quotation.total', 'users.name'];

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

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'          => 'required',
            'quotation_ref'      => 'required',
            'total'              =>'required',
            'category_id'        => 'required|array',
            'category_id.*'      => 'required',
            'item_description'   => 'required|array',
            'item_description.*' => 'required',
            'quantity'           => 'required|array',
            'quantity.*'         => 'required',
            'unit'               => 'required|array',
            'unit.*'             => 'required',
            'price'              => 'required|array',
            'price.*'            => 'required',
            'amount'             => 'required|array',
            'amount.*'           => 'required',
            'quotation_pdf'      => 'required|file',
            'project_name'       => 'required'
        ],[
            'vendor_id.required'     => 'The vendor field is required.',
        ]);

        $categories   = $request->category_id;
        $quantities   = $request->quantity;
        $descriptions = $request->item_description;
        $units        = $request->unit;
        $prices       = $request->price;
        $amounts      = $request->amount;

        $id = Auth::user()->id;
        $data = $request->all();
        $data['user_id']  = $id;
        $data['vendor_quotation'] = Uuid::uuid4()->getHex();
        $vendor_quotation = new VendorQuotation($data);
        $vendor_quotation['quotation_pdf']     =  $this->uploadPDF($request->file('quotation_pdf'));
        $vendor_quotation->save();

        $save = [];

        foreach($categories as $index => $category) {
            $vendor_quotation_item = [
                'vendor_quotation_id' => $vendor_quotation->id,
                'category_id'         => $category,
                'item_description'    => $descriptions[$index],
                'quantity'            => $quantities[$index],
                'unit'                => $units[$index],
                'price'               => $prices[$index],
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
