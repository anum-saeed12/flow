<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use App\Models\ImportedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class ImportController extends Controller
{
    public function importItem(Request $request)
    {
        $request->validate([
            'itemsFile' => 'file'
        ]);
        $file = $this->uploadFile($request->file('itemsFile'));
        $itemImport = new ItemsImport();
        # Start Excel Upload
        $imported = Excel::import($itemImport, $file);

        $batch_id = $itemImport->batch_id;

        return redirect(route('import.report', $batch_id));
    }

    public function uploadFile($file)
    {
        $filename  = date('Ymdhis')."Imported".ucfirst($file->getClientOriginalName());
        $directory = "item_imports";
        $private_path = $file->storeAs("public/{$directory}",$filename);
        $filepath = "{$directory}/{$filename}";
        return Storage::disk('local')->path($private_path);
        #return "/item_imports/{$filename}";
    }

    public function viewImport($batch_id)
    {
        $import_data = ImportedItem::paginate($this->count);
        $data = [
            'title'     => 'View Import Data ',
            'user'      => Auth::user(),
            'import_data'=> $import_data
        ];
        return view('admin.item.importView',$data);
    }
}
