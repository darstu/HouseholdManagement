<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResourceManagement\Warehouse_place;
use Illuminate\Http\Request;

class Select2SearchController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function selectSearch(Request $request)
    {
        $movies = [];

        if($request->has('q')){
            $search = $request->q;
            $movies =Warehouse_place::query()
                ->where('Warehouse_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($movies);
    }
}
