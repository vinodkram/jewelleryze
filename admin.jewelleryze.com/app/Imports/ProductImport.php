<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
class ProductImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $rowData = [
            'name' => $row[0],
            'short_name' => $row[1],
            'slug' => $row[2],
            'thumb_image' => $row[3],
            'vendor_id' => $row[4] ? $row[4] : 0,
            'category_id' => (int)$row[5],
            'sub_category_id' => $row[6] ? (int)$row[6] : 0,
            'child_category_id' => $row[7] ? (int)$row[7] : 0,
            'brand_id' => $row[8] ? (int)$row[8] : 0,
            'qty' => (int)$row[9],
            'weight' => (float)$row[10],
            'sold_qty' => 0, //vr
            'short_description' => $row[11],
            'long_description' => $row[12],
            'video_link' => $row[13],
            'sku' => $row[14],
            'seo_title' => $row[15],
            'seo_description' => $row[16],
            'price' => (float)$row[17],
            'offer_price' => $row[18] ? (float)$row[18]: null,
            'tags' => null, //
            'is_undefine' => 1, //
            'is_featured' => 0, //
            'new_product' => 0, //
            'is_top' => 0, //
            'is_best' => 0, //
            'is_specification' => 0, //
            'show_homepage' => 0, //
            'status' => 1,
            'approve_by_admin' => 1
        ];

        return new Product($rowData);
    }
}