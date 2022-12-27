<?php

namespace App\Imports;

use App\Place;
use Maatwebsite\Excel\Concerns\ToModel;

class PlaceImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Place([
            'place_name' => $row[8],
            'address' => $row[7], 
            'description' => $row[10], 
            'longitude' => $row[2], 
            'latitude' => $row[1], 
        ]);
    }
}
