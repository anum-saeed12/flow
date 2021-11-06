<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class QuotationController extends Controller
{
    public function customer()
    {
        $select = [
            'quotations.id',
            'customer_name',
            'project_name',
            'item_description',
            'total',
            'users.name'
        ];
        $quotations = Quotation::select($select)
            ->leftJoin('quotation_item', 'quotation_item.quotation_id', '=', 'quotations.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('users','users.id','=','quotations.user_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->groupBy('quotations.id')
            ->paginate($this->count);

        $data = [
            'title'     => 'Quotations',
            'user'      => Auth::user(),
            'quotations' => $quotations
        ];
        return view('team.quotation.customer',$data);
    }

    public function add()
    {
        $customers = Customer::orderBy('id','DESC')->paginate($this->count);
        $brands    = Brand::orderBy('id','DESC')->paginate($this->count);
        $items     = Item::select([
            DB::raw("DISTINCT item_name"),
        ])->orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Submit Quotation',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'customers' => $customers,
            'items'     => $items
        ];
        return view('team.quotation.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency'       =>'required',
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'discount'       => 'sometimes',
            'terms_condition'=> 'sometimes',
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
            'amount.*'       => 'required',
            'total'          => 'required'
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

        $data = $request->all();
        $id=Auth::user()->id;
        $data['user_id']  = $id;
        $data['date'] = Carbon::parse($request->date)->format('Y-m-d');
        $data['quotation'] = Uuid::uuid4()->getHex();
        $quotation = new Quotation($data);
        $quotation->save();

        $save = [];

        foreach($items as $index => $item) {
            $item_detail = Item::where('item_name',$item)->where('brand_id', $brands[$index])->first();
            $quotation_item = [
                'quotation_id' => $quotation->id,
                'item_id'  => $item_detail->id,
                'brand_id' => $brands[$index],
                'quantity' => $quantities[$index],
                'unit'     => $units[$index],
                'rate'     => $rates[$index],
                'amount'   => $amounts[$index]
            ];
            $save[] = (new QuotationItem($quotation_item))->save();
        }

        return redirect(
            route('customerquotation.list.team')
        )->with('success', 'Quotation was added successfully!');
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Quotation',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
        ];
        return view('team.quotation.edit', $data);
    }

    public function view ($id)
    {
        $quotation = Quotation::select('*')
            ->where('quotations.id',$id)
            ->leftJoin('quotation_item', 'quotation_item.quotation_id', '=', 'quotations.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->get();

        #return $quotation[0]->date;

        $data = [
            'title'      => 'Quotations',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotation'  => $quotation
        ];
        return view('team.quotation.item', $data);
    }

}