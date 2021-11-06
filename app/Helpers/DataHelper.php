<?php

use App\Models\Brand;
use App\Models\Item;

if (!function_exists('fetchBrandsForItem'))
{
    function fetchBrandsForItem($item_name)
    {
        $brand = (new Brand())->getTable();
        $item = (new Item())->getTable();

        $select = [
            "{$brand}.brand_name",
            "{$brand}.id",
        ];

        $brands = Item::select($select)
            ->join($brand, "{$brand}.id", "=", "{$item}.brand_id")
            ->where('item_name', "$item_name")
            ->get();

        return $brands;
    }
}
