<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

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
        $items     = Item::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Submit Quotation',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'customers' => $customers,
            'items'     => $items
        ];
        return view('admin.quotation.add', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'discount'       => 'sometimes|required',
            'terms_condition'=> 'sometimes|required',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'rate'           => 'required|array',
            'rate.*'         => 'required',
            'amount'         => 'required|array',
            'amount.*'       => 'required'
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.',
            'terms_condition.required' => 'The terms and condition field is required.'
        ]);

        $items = $request->item_id;
        $brands = $request->brand_id;
        $quantities = $request->quantity;
        $units = $request->unit;
        $rates = $request->rate;
        $amounts = $request->amount;

        $quotation_id = 99;

        $save = [];

        foreach($items as $index => $item) {
            $quotation_item = [
                'item_id' => $item,
                'brand_id' => $brands[$index],
                'quantity' => $quantities[$index],
                'unit' => $units[$index],
                'rate' => $rates[$index],
                'amount' => $amounts[$index]
            ];
            $save[] = (new QuotationItem($quotation_item))->save();
            # Mae araha hu December me
            # Clean your pussy
            # And I dont care tum periods pe ho ya na ho!
            # I Want to finger you and I want to make you cum
            # Phir to tumhe bht ziada maza ayega ! I'll insert my phone and put it on vibrate
        }

        dd($save);

        #$item    =  $request->all();
        #$item['picture']     =  $this->uploadPicture($request->file('picture'));
        #$user = new Item($item);

        #$user->save() ;
        /*return redirect(
            route('item.list.admin')
        )->with('success', 'Item was added successfully!');*/
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
