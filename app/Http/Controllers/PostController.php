<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function googleLineChart()
    {
        $visitor = DB::table('stock')
            ->where('fk_Stock_card','=',1)
            ->select(
                DB::raw("posting_date as year"),
//                DB::raw("SUM(quantity) as total_click"),
                DB::raw("SUM(quantity) as total_viewer"),
//                DB::raw('YEAR(posting_date) year'),DB::raw('MONTH(posting_date) month')
            )
            ->groupBy(DB::raw('YEAR(posting_date)'), DB::raw('MONTH(posting_date)'))
//            ->groupBy(DB::raw("posting_date"))
//            ->groupBy(DB::raw("MONTH(posting_date)"))
            ->get();

        $result[] = ['Year','Viewer'];
        foreach ($visitor as $key => $value) {
            $result[++$key] = [$value->year,(int)$value->total_viewer];
//            dd($result[++$key] = [$value->year,(int)$value->total_viewer]);
        }

        return view('googleLineChart')
            ->with('visitor',json_encode($result));
    }
}
