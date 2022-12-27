<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Place as PlaceResource;
use App\Place;
use Session;
use Illuminate\Http\Request;
use App\Imports\PlaceImport;
use Maatwebsite\Excel\Facades\Excel;

class PlaceController extends Controller
{

    public function index(Request $request)
    {
        $places = Place::all();
        return view('place',['place'=>$places]);

        $geoJSONdata = $places->map(function ($place) {
            return [
                'type' => 'Feature',
                'properties' => new PlaceResource($place),
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $place->longitude,
                        $place->latitude,

                    ],
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geoJSONdata,
        ]);
    }

    public function ImportExcel(Request $request) 
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
    
        // menangkap file excel
        $file = $request->file('file');
    
        // membuat nama file unik
        $nama_file = rand().$file->getClientOriginalName();
    
        // upload ke folder file_place di dalam folder public
        $file->move('file_place',$nama_file);
    
        // import data
        Excel::import(new PlaceImport, public_path('/file_place/'.$nama_file));
    
        // notifikasi dengan session
        Session::flash('sukses','Data Place Berhasil Diimport!');
    
            // alihkan halaman kembali
        return redirect('/place');
    }
}
