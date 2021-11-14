<?php

namespace App\Imports;

use App\Models\ImportedItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Ramsey\Uuid\Uuid;

class ItemsImport implements ToModel
{
    public $batch_id;

    public function __construct()
    {
        $this->batch_id = Uuid::uuid4()->getHex();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportedItem([
            'item_name'         => $row[0],
            'brand_name'        => $row[1],
            'category_name'     => $row[2],
            'item_description'  => $row[3],
            'price'             => $row[4],
            'weight'            => $row[5],
            'unit'              => $row[6],
            'width'             => $row[7],
            'dimension'         => $row[8],
            'height'            => $row[9],
            'batch_id'          => $this->batch_id,
        ]);
    }
}
