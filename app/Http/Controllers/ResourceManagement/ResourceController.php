<?php

namespace App\Http\Controllers\ResourceManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RecipeManagement\RecipeController;
use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\HouseholdResource\Transit_Road;
use App\Models\ResourceManagement\Purchase_Offer;
use App\Models\ResourceManagement\Stock_Place;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\ResourceManagement\Batch;
use App\Models\ResourceManagement\Entry_Type;
use App\Models\ResourceManagement\Stock;
use App\Models\ResourceManagement\Stock_Type;
use App\Models\ResourceManagement\Time;
use App\Models\ResourceManagement\Time_options;
use App\Models\ResourceManagement\Warehouse_place;
use App\Models\UserManagement\Home_Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Array_;
use function Symfony\Component\String\b;

class placeNode {
    public $place;
    public $quantity = 0;
    public $totalQuantityIncludingChildren = 0;
    public $childrenNodes = array();
}
class PlacePath{
    public $place;
    public $placename;
    public $path;
}
class MessageOut{
    public $stockname;
    public $missingPlace;
}

class ResourceController extends Controller
{
    public function index($Id)
    {
        $types = Supplier_Type::all();
        $title = 'Resources list';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $stockTypes = Stock_Type::where('fk_household_id', '=', $Id)->get();

//        SELECT *, SUM(quantity) AS "Total Salary" FROM `stock` GROUP BY fk_Stock_card
        //RABOTAJET
        $data = Stock::where('stock.fk_Home', '=', $Id)
            ->leftJoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
//dd($data);
        $count=count($data);
        $items = collect($data)->paginate(9);
        $search=null;
        $filter_id=-1;
        $Type=null;
        $button=2;
        return view('ResourceManagement/resourcelist', compact('filter_id','items', 'types', 'title', 'allmembers', 'house', 'stockTypes',
        'search','Type','button','count'));
    }
    public function indexActiveOnly($Id)
    {
        $types = Supplier_Type::all();
        $title = 'Resources list';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $stockTypes = Stock_Type::where('fk_household_id', '=', $Id)->get();
//        SELECT *, SUM(quantity) AS "Total Salary" FROM `stock` GROUP BY fk_Stock_card
        //RABOTAJET
        $data1 = Stock::where('stock.fk_Home', '=', $Id)
            ->leftJoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $data = $data1->filter(function ($value, $key) {
            return $value->total_quantity > 0;
        });
//        dd($data);
        $count=count($data);
        $items = collect($data)->paginate(9);
        $search=null;
        $filter_id=-1;
        $Type=null;
        $button='';
        $messages=array();
        return view('ResourceManagement/resourcelist', compact('filter_id','items', 'types', 'title', 'allmembers', 'house', 'stockTypes',
            'search','Type','button','count','messages'));
    }

    public function filter($Id, $filter_id, $button)
    {
        $types = Supplier_Type::all();
        $title = 'Resources list';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $stockTypes = Stock_Type::where('fk_household_id', '=', $Id)->get();

        $data = Stock::where('stock.fk_Home', '=', $Id)
            ->join('stock_card', function ($join) use ($filter_id) {
                $join->on('id_Stock_card', '=', 'stock.fk_Stock_card')
                    ->where('stock_card.fk_Stock_type', '=', $filter_id);
            })
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
        $Type=  Stock_Type::where('fk_household_id', '=', $Id)
            ->where('id_Stock_type',$filter_id)
            ->first();

        $items = collect($data)->paginate(9);
        $count=count($items);

        $search = null;


        return view('ResourceManagement/resourcelist', compact('filter_id','items', 'types', 'title', 'allmembers', 'house', 'stockTypes',
        'search','Type','count','button'));
    }


    public function OpenResourceView($Id, $id_resource)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();

        $items = Stock::where('fk_Stock_card', '=', $id_resource)->
        where('stock.fk_Home', '=', $Id)->
        join('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->leftjoin('warehouse_place', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
//            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $places = Warehouse_place::query()
            ->join('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock.fk_Stock_card', '=', $id_resource)->where('stock.fk_Home', '=', $Id)
            ->select('warehouse_place.*', 'stock.*')
            ->groupBy('id_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('warehouse_place.fk_Warehouse_place as warehouse')
            ->with('Warehouse_Place')
            ->get();

        $topPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();

        $topPlaceNodes = array();
        foreach ($topPlaces as $topPlace) {
            $placeNode = new placeNode();
            array_push($topPlaceNodes, $placeNode);

            $placeNode->place = $topPlace;

            $placeNode->totalQuantityIncludingChildren = $this->checkPlace($placeNode, $id_resource, $Id);
        }

        $topPlacesWhichHaveItem = array();
        foreach ($topPlaceNodes as $placeNode) {
            if ($placeNode->totalQuantityIncludingChildren > 0) {
                array_push($topPlacesWhichHaveItem, $placeNode);
            }
        }
//        dd($topPlacesWhichHaveItem);

//        foreach ($topPlacesWhichHaveItem as $topplaces)
//        dd($topplaces);

        $title = Stock_Card::where('id_Stock_card', '=', $id_resource)->first();
//            dd($title);

        $list = Stock::where('fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            ->leftJoin('transit_road','fk_Stock','=','stock_id')
            ->select('stock.*','transit_road.*')
            ->get();

        $minMaxList=Stock_Place::where('fk_Home','=',$Id)
            ->where('fk_Stock_card','=',$id_resource)
            ->get();

        if(count($minMaxList) > 0) {
        foreach ($minMaxList as $mm) {
            $minMaxListValues [] = $mm->fk_Warehouse_place;
        }

            $filteredPlacesForMinMax = Warehouse_place::where('fk_Home', '=', $Id)
                ->whereNotIn('id_Warehouse_place', $minMaxListValues)
                ->get();
        }
        else $filteredPlacesForMinMax = Warehouse_place::where('fk_Home', '=', $Id)->get();

        $qcount = 0;

//TESTING AS WELL
        $notfilteredexplaces = Warehouse_place::query()
            ->join('stock_place', 'stock_place.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock_place.fk_Stock_card', '=', $id_resource)
            ->where('stock_place.fk_Home', '=', $Id)
            ->groupBy('id_Warehouse_place')
            ->select('warehouse_place.fk_Warehouse_place as warehouse','warehouse_place.*')
            ->get();
//        dd($notfilteredexplaces);

        $visos=array();
        foreach ($notfilteredexplaces as $ex){
            $parent=new PlacePath();
            if($ex->warehouse==null){
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
//                dd($parent);
            }
            else{
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
                $this->NUDAVAI($parent, $ex->warehouse);
            }

            array_push($visos, $parent);
        }
        $volume  = array_column($visos, 'place');
        $edition = array_column($visos, 'path');
        array_multisort( $edition, SORT_STRING, $visos);

        //TESTING

        $categories = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
//            ->join('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
//            ->where('stock.fk_Stock_card', '=', $id_resource)
            ->whereNull('warehouse_place.fk_Warehouse_place')
//            ->leftJoin('stock', 'stock.fk_Warehouse_place', '=', 'warehouse_place.id_Warehouse_place')
//            ->where('stock.fk_Warehouse_place','=', 'warehouse_place.id_Warehouse_place')
            ->with('Warehouse_Place2')
            ->get();


        return view('ResourceManagement/resourceview', compact('types', 'list', 'items', 'places', 'title',
            'qcount', 'allmembers', 'house', 'minMaxList', 'filteredPlacesForMinMax','categories','topPlacesWhichHaveItem','visos','topPlaces'));
    }

    public function checkPlace($placeNode, $id_resource, $Id) {
        $placeQuantityInformation = Warehouse_place::query()
            ->join('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock.fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            ->select('warehouse_place.*', 'stock.*')
            ->groupBy('id_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('COUNT(stock_id) as counted')
            ->selectRaw('warehouse_place.fk_Warehouse_place as warehouse')
            ->where('id_Warehouse_place', '=', $placeNode->place->id_Warehouse_place)
            ->first();

        if ($placeQuantityInformation != null) {
            $placeNode->quantity = $placeQuantityInformation->total_quantity;
        }
        elseif ($placeQuantityInformation == null) {
//            $placeNode->quantity = -1;
            $placeNode->quantity=-1;
        }

        $placeChildrenPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('warehouse_place.fk_Warehouse_place', '=', $placeNode->place->id_Warehouse_place)
            ->get();

        foreach ($placeChildrenPlaces as $placeChildrenPlace) {
            $childPlaceNode = new placeNode();
            $childPlaceNode->place = $placeChildrenPlace;

            $totalQuantityIncludingChildren = $this->checkPlace($childPlaceNode, $id_resource, $Id);
            if ($totalQuantityIncludingChildren == -1) {
                continue;
            }

            $placeNode->totalQuantityIncludingChildren += $totalQuantityIncludingChildren;
            array_push($placeNode->childrenNodes, $childPlaceNode);
        }

//        foreach ($placeNode->childrenNodes as $childNode) {
//            $placeNode->totalQuantityIncludingChildren += $this->checkPlace($childNode, $id_resource, $Id);
//        }
//dd($placeNode );
        $placeNode->totalQuantityIncludingChildren += $placeNode->quantity;

        return $placeNode->totalQuantityIncludingChildren;
    }



    public function OpenResourceViewAll($Id, $id_resource)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();

        $items = Stock::where('fk_Stock_card', '=', $id_resource)->
        where('stock.fk_Home', '=', $Id)->
        join('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->leftjoin('warehouse_place', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
//            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $places = Warehouse_place::query()
            ->join('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock.fk_Stock_card', '=', $id_resource)->where('stock.fk_Home', '=', $Id)
            ->select('warehouse_place.*', 'stock.*')
            ->groupBy('id_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('warehouse_place.fk_Warehouse_place as warehouse')
            ->with('Warehouse_Place')
            ->get();

        $topPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();

        $topPlaceNodes = array();
        foreach ($topPlaces as $topPlace) {
            $placeNode = new placeNode();
            array_push($topPlaceNodes, $placeNode);

            $placeNode->place = $topPlace;

            $placeNode->totalQuantityIncludingChildren = $this->checkPlace($placeNode, $id_resource, $Id);
        }

        $topPlacesWhichHaveItem = array();
        foreach ($topPlaceNodes as $placeNode) {
            if ($placeNode->totalQuantityIncludingChildren >= 0) {
                array_push($topPlacesWhichHaveItem, $placeNode);
            }
        }
//        dd($topPlacesWhichHaveItem);

//        foreach ($topPlacesWhichHaveItem as $topplaces)
//        dd($topplaces);

        $title = Stock_Card::where('id_Stock_card', '=', $id_resource)->first();
//            dd($title);

        $list = Stock::where('fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            ->leftJoin('transit_road','fk_Stock','=','stock_id')
            ->select('stock.*','transit_road.*')
            ->get();

        $minMaxList=Stock_Place::where('fk_Home','=',$Id)
            ->where('fk_Stock_card','=',$id_resource)
            ->get();

        if(count($minMaxList) > 0) {
            foreach ($minMaxList as $mm) {
                $minMaxListValues [] = $mm->fk_Warehouse_place;
            }

            $filteredPlacesForMinMax = Warehouse_place::where('fk_Home', '=', $Id)
                ->whereNotIn('id_Warehouse_place', $minMaxListValues)
                ->get();
        }
        else $filteredPlacesForMinMax = Warehouse_place::where('fk_Home', '=', $Id)->get();

        $qcount = 0;

//TESTING AS WELL
        $notfilteredexplaces = Warehouse_place::query()
            ->join('stock_place', 'stock_place.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock_place.fk_Stock_card', '=', $id_resource)
            ->where('stock_place.fk_Home', '=', $Id)
            ->groupBy('id_Warehouse_place')
            ->select('warehouse_place.fk_Warehouse_place as warehouse','warehouse_place.*')
            ->get();
//        dd($notfilteredexplaces);

        $visos=array();
        foreach ($notfilteredexplaces as $ex){
            $parent=new PlacePath();
            if($ex->warehouse==null){
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
//                dd($parent);
            }
            else{
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
                $this->NUDAVAI($parent, $ex->warehouse);
            }

            array_push($visos, $parent);
        }
        $volume  = array_column($visos, 'place');
        $edition = array_column($visos, 'path');
        array_multisort( $edition, SORT_STRING, $visos);

        //TESTING

        $categories = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
//            ->join('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
//            ->where('stock.fk_Stock_card', '=', $id_resource)
            ->whereNull('warehouse_place.fk_Warehouse_place')
//            ->leftJoin('stock', 'stock.fk_Warehouse_place', '=', 'warehouse_place.id_Warehouse_place')
//            ->where('stock.fk_Warehouse_place','=', 'warehouse_place.id_Warehouse_place')
            ->with('Warehouse_Place2')
            ->get();


        return view('ResourceManagement/resourceview', compact('types', 'list', 'items', 'places', 'title',
            'qcount', 'allmembers', 'house', 'minMaxList', 'filteredPlacesForMinMax','categories','topPlacesWhichHaveItem','visos','topPlaces'));
    }




    public static function addResource($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $cards = Stock_Card::where('fk_Home', '=', $Id)->where('removed',0)->get();
        $places = Warehouse_place::where('fk_Home', '=', $Id)->get();
        $batches = Batch::where('fk_Home', '=', $Id)->get();
//        ->where('batch.fk_Stock_card', '=','stock_card.id_Stock_card')->
        $en = Entry_Type::all();
        $stockTypes=Stock_Type::where('fk_household_id',$Id)->get();
        $topPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();

        return view('ResourceManagement/addresource',
            compact('allmembers', 'house', 'types', 'cards', 'places', 'batches', 'en','stockTypes','topPlaces'));
    }

    public function addResource2(Request $request, $Id)
    {

        $validator = Validator::make(
            [
                'fk_Stock_card' => $request->input('category'),
                'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                'fk_Batch' => $request->input('batch1'),
                'quantity' => $request->input('quantity'),
//                    'expiration_date' => $request->input('expiration_date'),
                'Action_type' => $request->input('fk_Entry_type'),
            ],
            [
                'fk_Stock_card' => 'required',
                'fk_Warehouse_place' => 'required',
                'quantity' => 'required',
//                    'expiration_date' => 'required',
                'Action_type' => 'required'
            ]
        );


        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ex = $request->input('expiration_date');
        if ($ex != null) {

            $newstock = new Stock;
            $newstock->fk_Stock_card = $request->input('category');
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->fk_Batch = $request->input('batch1');
            $newstock->quantity = $request->input('quantity');
            $newstock->expiration_date = $request->input('expiration_date');
            $newstock->fk_Entry_type = $request->input('fk_Entry_type');
            $newstock->posting_date = Carbon::now();
            $newstock->fk_Home = $Id;

            $newstock->save();
            if(session()->has('recipeName')){
                return RecipeController::useStock();
            }
            return Redirect::route('resourcesList', ['Id' => $Id])->with('success', 'Resource added');
        } else {
            $newstock = new Stock;
            $newstock->fk_Stock_card = $request->input('category');
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->fk_Batch = $request->input('batch1');
            $newstock->quantity = $request->input('quantity');
            $newstock->expiration_date = '9999-09-09';
            $newstock->fk_Entry_type = $request->input('fk_Entry_type');
            $newstock->posting_date = Carbon::now();
            $newstock->fk_Home = $Id;

            $newstock->save();
            if(session()->has('recipeName')){
                return RecipeController::useStock();
            }
            return Redirect::route('resourcesList', ['Id' => $Id])->with('success', 'Resource added');
        }


//                return view('ResourceManagement/cardlist', compact('types', 'stock_types', 'title', 'allmembers', 'house'))->with('success', 'Stock Card created');
    }

    public static function addResourceFromResourceView($Id, $id_resource)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $card = Stock_Card::where('fk_Home', '=', $Id)
            ->where('removed',0)
            ->where('id_Stock_card',$id_resource)
            ->first();
//        $places = Warehouse_place::where('fk_Home', '=', $Id)->get();
        $topPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();
        $en = Entry_Type::all();
//        $data = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
//            ->where('removed',0)
//            ->where('warehouse_place.fk_Warehouse_place',23)
//            ->get();
//        dd($data);


        return view('ResourceManagement/addResourceFromResource',
            compact('allmembers', 'house', 'types', 'card', 'topPlaces', 'en'));
    }

    public function getChildPlaces($Id, $topPlace)
    {
        $data = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->where('warehouse_place.fk_Warehouse_place',$topPlace)
            ->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }
    public function getChildPlaces2($Id, $topPlace,$id_resource)
    {
        $minmaxPlaces=Stock_Place::where('fk_Home',$Id)
            ->where('fk_Stock_card',$id_resource)
            ->get();

        if(count($minmaxPlaces) > 0) {
            foreach ($minmaxPlaces as $mm) {
                $minMaxListValues [] = $mm->fk_Warehouse_place;
            }

            $data = Warehouse_place::where('fk_Home', '=', $Id)
                ->where('removed',0)
                ->where('warehouse_place.fk_Warehouse_place',$topPlace)
                ->whereNotIn('id_Warehouse_place', $minMaxListValues)
                ->get();
        }
        else {
            $data = Warehouse_place::where('fk_Home', '=', $Id)
                ->where('removed',0)
                ->where('warehouse_place.fk_Warehouse_place',$topPlace)
                ->get();
        }


        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function getAllPlaces($Id )
    {
        $data = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }


    public function checkBatch($Id, $resource_id, $b)
    {
        $data = Stock::where('fk_Stock_card', '=', $resource_id)
            ->where('fk_Home', '=', $Id)
            ->where('fk_Batch', '=', $b)
//            ->leftJoin('stock_card', 'id_Stock_card','=','stock.fk_Stock_card')
            ->select('stock.*')
//            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
//        dd($data);

        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function checkBatch2($Id, $resource_id, $id_place, $b)
    {
        $data = Stock::where('fk_Stock_card', '=', $resource_id)
            ->where('fk_Home', '=', $Id)
            ->where('fk_Warehouse_place','=',$id_place)
            ->where('fk_Batch', '=', $b)
//            ->leftJoin('stock_card', 'id_Stock_card','=','stock.fk_Stock_card')
            ->select('stock.*')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
//        dd($data);

        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function addResourceFromResource(Request $request, $Id,$resource_id)
    {

        $validator = Validator::make(
            [
//                'fk_Stock_card' => $request->input('category'),
                'Warehouse_place' => $request->input('fk_Warehouse_place'),
                'Batch' => $request->input('batch1'),
                'quantity' => $request->input('quantity'),
//                    'expiration_date' => $request->input('expiration_date'),
                'Action_type' => $request->input('fk_Entry_type'),
            ],
            [
//                'fk_Stock_card' => 'required',
                'Warehouse_place' => 'required',
                'Batch' => 'required',
                'quantity' => 'required',
//                    'expiration_date' => 'required',
                'Action_type' => 'required'
            ]
        );


        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ex = $request->input('expiration_date');
        if ($ex != null) {

            $newstock = new Stock;
            $newstock->fk_Stock_card = $resource_id;
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->fk_Batch = $request->input('batch1');
            $newstock->quantity = $request->input('quantity');
            $newstock->expiration_date = $request->input('expiration_date');
            $newstock->fk_Entry_type = $request->input('fk_Entry_type');
            $newstock->posting_date = Carbon::now();
            $newstock->fk_Home = $Id;

            $newstock->save();
            if (session()->has('recipeName')) {
                return RecipeController::useStock();
            }
            return Redirect::route('resourceView', ['Id' => $Id, 'id_resource'=>$resource_id])->with('success', 'Resource added');
        } else {
            $newstock = new Stock;
            $newstock->fk_Stock_card = $resource_id;
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->fk_Batch = $request->input('batch1');
            $newstock->quantity = $request->input('quantity');
            $newstock->expiration_date = '9999-09-09';
            $newstock->fk_Entry_type = $request->input('fk_Entry_type');
            $newstock->posting_date = Carbon::now();
            $newstock->fk_Home = $Id;
            $newstock->save();
            if (session()->has('recipeName')) {
                return RecipeController::useStock();
            }
            return Redirect::route('resourceView', ['Id' => $Id, 'id_resource'=>$resource_id])->with('success', 'Resource added');
        }
    }

    public function addResourcePP(Request $request, $Id, $resource_id, $place)
    {
        $expiration=$request->expiration1;
        if($expiration==null){
            $expiration='9999-09-09';
        }
        \DB::table('stock')->insert([
            'fk_Stock_card' => $resource_id,
            'fk_Warehouse_place' => $place,
            'fk_Batch' => $request->batch1,
            'quantity' => $request->quantity1,
            'expiration_date' => $expiration,
            'fk_Entry_type' => 1,
            'fk_Home' => $Id,
            'posting_date'=>Carbon::now()
        ]);

        $itemperplace = Stock::where('stock.fk_Home', '=', $Id)
            ->where('fk_Stock_card',$resource_id)
            ->where('fk_Warehouse_place',$place)
            ->select('stock.*')
            ->groupBy('fk_Stock_card')
            ->groupBy('fk_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity_per_place')
            ->first();

        $itemsinOffer= Purchase_Offer::where('fk_household_id', '=', $Id)
            ->where('fk_stock_card_id',$resource_id)
            ->where('fk_Warehouse_place',$place)
            ->groupBy('purchase_offer.fk_stock_card_id')
            ->groupBy('purchase_offer.fk_Warehouse_place')
            ->selectRaw('SUM(amount) as total_quantity')
            ->first();

        $currentuserid = Auth::user()->id;
        $amounttoAdd = $request->quantity1;

        if($itemsinOffer->total_quantity>$amounttoAdd) {

            $purchaseOffer = new Purchase_Offer();
            $purchaseOffer->fk_stock_card_id = $resource_id;
            $purchaseOffer->fk_Warehouse_place = $place;
            $purchaseOffer->amount = -$amounttoAdd;
            $purchaseOffer->date = Carbon::now();
            $purchaseOffer->want_to_buy = 0;
            $purchaseOffer->buyer = $currentuserid;
            $purchaseOffer->byQuantity = 4;
            $purchaseOffer->existing_amount = $itemperplace->total_quantity_per_place;
            $purchaseOffer->who_added = $currentuserid;
            $purchaseOffer->fk_household_id = $Id;
            $purchaseOffer->save();

            $itemAfterUpdate= Purchase_Offer::where('fk_household_id', '=', $Id)
                ->where('fk_stock_card_id',$resource_id)
                ->where('fk_Warehouse_place',$place)
                ->groupBy('purchase_offer.fk_stock_card_id')
                ->groupBy('purchase_offer.fk_Warehouse_place')
                ->selectRaw('SUM(amount) as total_quantity')
                ->get();

            \Log::info($itemAfterUpdate);
            return response()->json(['message' => 'Purchase registered successfully',
                'data'=>$itemAfterUpdate]);
//            \Log::info($newstock);
//            return response()->json(['data' => $newstock]);
        }

       else {
            $oldstock = Purchase_Offer::where('fk_household_id','=',$Id)
                ->where('fk_stock_card_id', '=', $resource_id)
                ->where('fk_Warehouse_place', '=', $place)
                ->get();
            foreach ($oldstock as $old) {
                $old->forceDelete();
            }

//           $itemAfterUpdate= new Purchase_Offer();
//           $itemAfterUpdate->fk_stock_card_id = $resource_id;
//           $itemAfterUpdate->fk_Warehouse_place = $place;
//           $itemAfterUpdate->amount = 0;
//           $itemAfterUpdate->date = Carbon::now();
//           $itemAfterUpdate->want_to_buy = 0;
//           $itemAfterUpdate->byQuantity = 4;
//           $itemAfterUpdate->existing_amount = $itemperplace->total_quantity_per_place;
//           $itemAfterUpdate->who_added = $currentuserid;
//           $itemAfterUpdate->fk_household_id = $Id;


//           \Log::info($itemAfterUpdate);
           return response()->json(['message' => 'Purchase registered successfully, full amount was purchased',
               'zero'=>0]);
        }


//        $newstock->quantity = $request->input('quantity1');

//        $validator = Validator::make(
//            [
////                'fk_Stock_card' => $request->input('category'),
//                'Warehouse_place' => $request->input('fk_Warehouse_place'),
//                'Batch' => $request->input('batch1'),
//                'quantity' => $request->input('quantity'),
////                    'expiration_date' => $request->input('expiration_date'),
//                'Action_type' => $request->input('fk_Entry_type'),
//            ],
//            [
////                'fk_Stock_card' => 'required',
//                'Warehouse_place' => 'required',
//                'Batch' => 'required',
//                'quantity' => 'required',
////                    'expiration_date' => 'required',
//                'Action_type' => 'required'
//            ]
//        );


//        if ($validator->fails()) {
//
//            return Redirect::back()->withErrors($validator)->withInput();
//        }

//        return Stock::create([
//            'fk_Stock_card' => $resource_id,
//            'fk_Warehouse_place' => $place,
//            'fk_Batch' => 'klkl',
//            'quantity' => 1,
//            'expiration_date' => '2022-10-10',
//            'fk_Entry_type' => 1,
//            'fk_Home' => $Id,
//            'posting_date'=>Carbon::now()
//        ]);;

//        $ex = $request->input('expiration1');
//        if ($ex != null) {

//            $newstock = new Stock;
//            $newstock->fk_Stock_card = $resource_id;
//            $newstock->fk_Warehouse_place = $place;
//            $newstock->fk_Batch = 'lll';
//            $newstock->quantity = 1;
//            $newstock->expiration_date = '2022-10-10';
//            $newstock->fk_Entry_type = 1;
//            $newstock->posting_date = Carbon::now();
//            $newstock->fk_Home = $Id;
////dd($newstock);
//            $newstock->save();
//        }
//            \Log::info($newstock);
//            return response()->json(['data' => $newstock]);
//            if (session()->has('recipeName')) {
//                return RecipeController::useStock();
//            }
//            return Redirect::back()->with('success', 'Resource added');
//        } else {
//            $newstock = new Stock;
//            $newstock->fk_Stock_card = $resource_id;
//            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
//            $newstock->fk_Batch = $request->input('batch1');
//            $newstock->quantity = $request->input('quantity');
//            $newstock->expiration_date = '9999-09-09';
//            $newstock->fk_Entry_type = $request->input('fk_Entry_type');
//            $newstock->posting_date = Carbon::now();
//            $newstock->fk_Home = $Id;
//            $newstock->save();
//            if (session()->has('recipeName')) {
//                return RecipeController::useStock();
//            }
//            return Redirect::route('resourcesList', ['Id' => $Id])->with('success', 'Resource added');
//        }
    }

    public static function saveStock($stockCardID,$warehousePlace,$batch,$quantity,$expiration_date,$fk_Entry_type,$houseID){
        return Stock::create([
            'fk_Stock_card' => $stockCardID,
            'fk_Warehouse_place' => $warehousePlace,
            'fk_Batch' => $batch,
            'quantity' => $quantity,
            'expiration_date' => $expiration_date,
            'fk_Entry_type' => $fk_Entry_type,
            'fk_Home' => $houseID,
            'posting_date'=>Carbon::now()
        ]);;
    }
//PARTIJOS
    public function addBatch($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $cards = Stock_Card::where('fk_Home', '=', $Id)->where('removed',0)->get();

        return view('ResourceManagement/addBatch',
            compact('allmembers', 'house', 'types', 'cards'));
    }

    public function addBatch2(Request $request, $Id)
    {
        $validator = Validator::make(
            [
                'fk_Stock_card' => $request->input('fk_Stock_card'),
                'number' => $request->input('number'),
                'comment' => $request->input('comment')
            ],
            [
                'fk_Stock_card' => 'required',
                'number' => 'required | min:3'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {

            $newbatch = new Batch();
            $newbatch->fk_Stock_card = $request->input('fk_Stock_card');
            $newbatch->number = $request->input('number');
            $newbatch->comment = $request->input('comment');
            $newbatch->fk_Home = $Id;

            $newbatch->save();

            return Redirect::route('addResource', ['Id' => $Id])->with('success', 'Batch added');
        }
        //                return view('ResourceManagement/cardlist', compact('types', 'stock_types', 'title', 'allmembers', 'house'))->with('success', 'Stock Card created');
    }

    public function allbatchesList($Id, $stock_id)
    {
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $card=Stock_Card::where('id_Stock_card',$stock_id)->first();

        $items=Stock::where('fk_Home', '=', $Id)
           ->where('fk_Stock_card','=',$stock_id)
            ->groupBy('fk_Batch')
            ->select('fk_Batch', 'expiration_date')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
        $button=2;
        return view('ResourceManagement/batches', compact('items', 'types', 'allmembers', 'house','button', 'card'));
    }
    public function batchesList($Id, $stock_id)
    {
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $card=Stock_Card::where('id_Stock_card',$stock_id)->first();
        $items1=Stock::where('fk_Home', '=', $Id)
            ->where('fk_Stock_card','=',$stock_id)
            ->groupBy('fk_Batch')
            ->select('fk_Batch', 'expiration_date')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();
        $items=$items1->where('total_quantity','>',0);
        $button='';
        return view('ResourceManagement/batches', compact('items', 'types', 'allmembers', 'house','card','button'));
    }

//    public function editBatch($Id, $batch_id)
//    {
//        $allmembers = Home_Member::all();
//        $types = Supplier_Type::all();
//        $house = Home::where('id_Home', '=', $Id)->first();
//
//        $cards = Stock_Card::where('fk_Home', '=', $Id)->get();
//        $batch = Batch::where('id_Stock_batch', '=', $batch_id)->first();
//
//        return view('ResourceManagement/editBatchView',
//            compact('allmembers', 'house', 'types', 'cards', 'batch'));
//    }


    public function saveEditBatch($Id, $card, Request $request,$currentBatch)
    {
       $batch=$request->input('batch');
       $expiration=$request->input('expiration');


        $validator = Validator::make(
            [
                'batch' => $request->input('batch'),
                'expiration' => $request->input('expiration')
            ],
            [
                'batch' => 'required'
            ]
        );
        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {

            $items = Stock::where('fk_Stock_card', '=', $card)
                ->where('fk_Batch','=',$currentBatch)
                ->get();

//            dd($batch,$items);

            foreach ($items as $item) {
                if ($expiration !=null) {
                    $item->update(
                        [
                            'fk_Batch' => $batch,
                            'expiration_date' => $expiration,
                        ]);
                } else {
                    $item->update(
                        [
                            'fk_Batch' => $batch,
                            'expiration_date' => '9999-09-09',
                        ]);
                }
            }

            return Redirect::back()->with('success', 'Batch updated');

        }
    }

    public function deleteBatch($Id, $batch_id)
    {
        $apmok = Batch::where('id_Stock_batch', '=', $batch_id)->delete();
        return Redirect::back()->with('success', 'Batch blocked');
    }


    public function getCategory($Id, $id)
    {
//        $data = Batch::where('fk_Stock_card', '=', $id)->where('batch.fk_Home', '=', $Id)->get();
//        \Log::info($data);
        $data = Stock_Card::where('fk_Home', '=', $Id)
            ->where('id_Stock_card',$id)
            ->get();
        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function openMinMax($Id)
    {
        $types = Supplier_Type::all();
        $title = 'List of set min max quantities';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $data = Stock_Place::where('fk_Home', '=', $Id)->get();
        $items = collect($data)->paginate(9);

        return view('ResourceManagement/openMinMax', compact('items', 'types', 'title', 'allmembers', 'house'));
    }

    public function setMinMax($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $items = Stock_Place::where('fk_Home', '=', $Id)->get();
        $cards = Stock_Card::where('fk_Home', '=', $Id)->get();
        $places = Warehouse_place::where('fk_Home', '=', $Id)->get();

        return view('ResourceManagement/setMinMax',
            compact('items', 'types', 'allmembers', 'house', 'cards', 'places'));
    }


    public function setMinMax2FromResource($Id, $stock_card_id, Request $request)
    {
        $min = 0;
        $max = 0;
        $items = Stock_Place::where('fk_Home', '=', $Id)->get();
        $card = Stock_Card::where('fk_Home', '=', $Id)
            ->where('id_Stock_card', '=', $stock_card_id)
            ->first();

        $validator = Validator::make(
            [
                'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                'min' => $request->input('min'),
                'max' => $request->input('max'),
            ],
            [
                'fk_Warehouse_place' => 'required',
                'min' => 'required',
                'max' => 'required'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $min = $request->input('min');
            $max = $request->input('max');
            if ($min > $max) {
                return Redirect::back()->withErrors(['Min value can not be less than max value'])->withInput();
            }

            $c = $card->id_Stock_card;
            $w = $request->input('fk_Warehouse_place');
            $k = 0;
            $l = 0;

            foreach ($items as $a) {
                if ($a->fk_Warehouse_place == $w && $a->fk_Stock_card == $c)
                    return Redirect::back()->withErrors(['Values for this card and place exist already, please edit'])->withInput();
            }

            $newstock = new Stock_Place();
            $newstock->fk_Stock_card = $card->id_Stock_card;
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->min_amount = $request->input('min');
            $newstock->max_amount = $request->input('max');
            $newstock->fk_Home = $Id;

            $newstock->save();

            return Redirect::back()->with('success', 'Min max have been set');

        }
    }

    public function setMinMax2(Request $request, $Id)
    {
        $min = 0;
        $max = 0;
        $items = Stock_Place::where('fk_Home', '=', $Id)->get();
        $validator = Validator::make(
            [
                'Stock_card' => $request->input('fk_Stock_card'),
                'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                'min' => $request->input('min'),
                'max' => $request->input('max'),
            ],
            [
                'Stock_card' => 'required',
                'fk_Warehouse_place' => 'required',
                'min' => 'required',
                'max' => 'required'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $min = $request->input('min');
            $max = $request->input('max');
            if ($min > $max) {
                return Redirect::back()->withErrors(['Min value can not be less than max value'])->withInput();
            }


            $c = $request->input('fk_Stock_card');
            $w = $request->input('fk_Warehouse_place');
            $k = 0;
            $l = 0;

            foreach ($items as $a) {
                if ($a->fk_Warehouse_place == $w && $a->fk_Stock_card == $c)
                    return Redirect::back()->withErrors(['Values for this card and place exist already, please edit'])->withInput();
            }

            $newstock = new Stock_Place();
            $newstock->fk_Stock_card = $request->input('fk_Stock_card');
            $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
            $newstock->min_amount = $request->input('min');
            $newstock->max_amount = $request->input('max');
            $newstock->fk_Home = $Id;

            $newstock->save();

            return Redirect::route('openMinMax', ['Id' => $Id])->with('success', 'Min max have been set');

        }
    }

    public function editMinMax($Id, $set_id, $set_id2)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $item = Stock_Place::where('fk_Home', '=', $Id)->where('fk_Stock_card', '=', $set_id)->where('fk_Warehouse_place', '=', $set_id2)->first();
        $cards1 = Stock_Card::where('fk_Home', '=', $Id)->get();
        $cards = $cards1->except($item->fk_Stock_card);
        $places = Warehouse_place::where('fk_Home', '=', $Id)->get();
        $places = $places->whereNotIn('id_Warehouse_place', $set_id2);

//                $places=$places1->except($item->fk_Warehouse_place);

        return view('ResourceManagement/editMinMaxView',
            compact('item', 'types', 'allmembers', 'house', 'cards', 'places'));
    }

    public function saveMinMax($Id, $set_id, $set_id2, Request $request)
    {
        $items = Stock_Place::where('fk_Home', '=', $Id)->get();
        $item = Stock_Place::where('fk_Home', '=', $Id)->where('fk_Stock_card', '=', $set_id)->where('fk_Warehouse_place', '=', $set_id2);
        $validator = Validator::make(
            [
//                'fk_Stock_card' => $request->input('fk_Stock_card'),
//                'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                'min_amount' => $request->input('min_amount'),
                'max_amount' => $request->input('max_amount'),
            ],
            [
//                'fk_Stock_card' => 'required',
//                'fk_Warehouse_place' => 'required',
                'min_amount' => 'required',
                'max_amount' => 'required'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $min = $request->input('min_amount');
            $max = $request->input('max_amount');
            if ($min > $max) {
                return Redirect::back()->withErrors(['Min value can not be less than max value'])->withInput();
            }

            $item->update(
                [
//                    'fk_Stock_card' => $request->input('fk_Stock_card'),
//                    'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                    'min_amount' => $request->input('min_amount'),
                    'max_amount' => $request->input('max_amount')
                ]);

            return Redirect::back()->with('success', 'Updated');
        }
    }

    public function moveResource($Id, $id_resource)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $resource = Stock_Card::where('id_Stock_card', '=', $id_resource)->first();
        $places = Warehouse_place::where('fk_Home', '=', $Id)->get();
//                $explaces=Warehouse_place::where( 'fk_Home','=',$Id)->get();

        $items = Stock::where('fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
//                    ->join('stock_card', 'stock.fk_Stock_card','=', 'id_Stock_card')
//                    ->select('fk_Stock_card','stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();


        $notfilteredexplaces = DB::table('warehouse_place')
            ->leftjoin('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock.fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            ->select('warehouse_place.fk_Warehouse_place as warehouse','warehouse_place.*', 'stock.*')
            ->groupBy('id_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $explaces = $notfilteredexplaces->filter(function ($value, $key) {
            return $value->total_quantity > 0;
        });
//        dd($explaces,$filtered);

//        $parent=array();
$visos=array();
    foreach ($explaces as $ex){
        $parent=new PlacePath();
    if($ex->warehouse==null){
        $parent->place=$ex->id_Warehouse_place;
        $parent->path=$ex->Warehouse_name;
        $parent->placename=$ex->Warehouse_name;
//        dd($ex);
    }
    else{
        $parent->place=$ex->id_Warehouse_place;
        $parent->path=$ex->Warehouse_name;
        $parent->placename=$ex->Warehouse_name;
        $this->NUDAVAI($parent, $ex->warehouse);

    }
        array_push($visos, $parent);

}

        $volume  = array_column($visos, 'place');
        $edition = array_column($visos, 'path');

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
        array_multisort( $edition, SORT_STRING, $visos);
//    dd($visos);


        $data = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();


        return view('ResourceManagement/moveResourceView', compact('resource', 'types', 'allmembers',
            'house', 'visos', 'places', 'items'));
    }



    public function getCategoryMove($Id, $id, $id_place)
    {
        $data = Stock::where('stock.fk_Home', '=', $Id)->where('stock.fk_Stock_card', '=', $id)
            ->where('stock.fk_Warehouse_place', '=', $id_place)
            ->where('stock.quantity', '>', 0)
            ->leftJoin('batch', 'batch.id_Stock_batch', '=', 'stock.fk_Batch')->get();
        \Log::info($data);
        return response()->json(['data' => $data]);
    }


    public function moveResource2($Id, $id_resource, Request $request)
    {

        $batch = $request->input('sub_category');
        $place = $request->input('category');
        $inputAmount = $request->input('quantity');
        $expiration_date = $request->input('sub_category2');
        $fk_Warehouse_place = $request->input('fk_Warehouse_place');
//       dd($expiration_date);
        if($place==$fk_Warehouse_place) {
            $nameOfplace=Warehouse_place::where('id_Warehouse_place',$place)->first();

            return Redirect::back()->withErrors('Destination place ('.$nameOfplace->Warehouse_name.') can not be same as moving from place ('.$nameOfplace->Warehouse_name.')')->withInput();
        }
        $validator = Validator::make(
            [
                'batch' => $request->input('sub_category'),
                'place' => $request->input('category'),
                'inputAmount' => $request->input('quantity'),
//                'expiration_date' => $request->input('sub_category2'),
                'warehouse_place' => $request->input('fk_Warehouse_place'),
                'reason' => $request->input('reason'),
            ],
            [
                'batch' => 'required',
                'place' => 'required',
                'inputAmount' => 'required',
//                'expiration_date' => 'required',
                'warehouse_place' => 'required',
                'reason' => 'required'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }
        $total = 0;

        $stock = Stock::where('fk_Home', '=', $Id)
            ->where('fk_Stock_card', '=', $id_resource)
            ->where('fk_Batch', '=', $batch)
            ->where('fk_Warehouse_place', '=', $place)
            ->get();

        foreach ($stock as $st) {
            $total = $total + $st->quantity;
        }
//        dd($total);

        if ($inputAmount > $total) {
            return Redirect::back()->withErrors(['Not enough quantity. Max quantity is:' . $total])->withInput();
        }

//                    foreach ($stock as $st) {
//                       if($st->fk_Warehouse_place==$place){
//                           return Redirect::back()->withErrors(['1Resource is already at: ' . $st->Warehouse_Place->Warehouse_name])->withInput();
//                       }
//                    }
        $data = Carbon::now();
        $newstock = new Stock();
        $newstock->fk_Stock_card = $id_resource;
        $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
        $newstock->fk_Batch = $batch;
        $newstock->quantity = $request->input('quantity');
        $newstock->fk_Entry_type = 6;
        if($expiration_date!=null) {
            $newstock->expiration_date = $expiration_date;
        }
        else{
            $newstock->expiration_date = '9999-09-09';
        }
        $newstock->posting_date = $data;
        $newstock->fk_Home = $Id;
        $newstock->save();


        $transit = new Transit_Road();
        $transit->reason = $request->input('reason');
        $transit->comment = $request->input('comment');
        $transit->fk_Stock = $newstock->stock_id;
        $transit->save();


        $newstock2 = new Stock();
        $newstock2->fk_Stock_card = $id_resource;
        $newstock2->fk_Warehouse_place = $request->input('category');
        $newstock2->fk_Batch = $batch;
        $newstock2->quantity = 0 - $request->input('quantity');
        $newstock2->fk_Entry_type = 6;
        if($expiration_date!=null) {
            $newstock2->expiration_date = $expiration_date;
        }
        else{
            $newstock2->expiration_date = '9999-09-09';
        }
        $newstock2->posting_date = Carbon::now();
        $newstock2->fk_Home = $Id;
        $newstock2->save();

        return Redirect::route('resourceView', ['Id' => $Id, 'id_resource' => $id_resource])->with('success', 'Moved');


    }


    public function deleteResource($Id, $id_resource)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();

        $resource = Stock_Card::where('id_Stock_card', '=', $id_resource)->first();

        $items = Stock::where('fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            //                    ->join('stock_card', 'stock.fk_Stock_card','=', 'id_Stock_card')
            //                    ->select('fk_Stock_card','stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();
//
//        $explaces = DB::table('warehouse_place')
//            ->leftjoin('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
//            ->where('stock.fk_Stock_card', '=', $id_resource)
//            ->where('stock.fk_Home', '=', $Id)
//            ->select('warehouse_place.*', 'stock.*')
//            ->groupBy('id_Warehouse_place')
//            ->selectRaw('SUM(quantity) as total_quantity')
//            ->get();

        $notfilteredexplaces = DB::table('warehouse_place')
            ->leftjoin('stock', 'stock.fk_Warehouse_place', '=', 'id_Warehouse_place')
            ->where('stock.fk_Stock_card', '=', $id_resource)
            ->where('stock.fk_Home', '=', $Id)
            ->select('warehouse_place.*', 'stock.*','warehouse_place.fk_Warehouse_place as warehouse')
            ->groupBy('id_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $explaces = $notfilteredexplaces->filter(function ($value, $key) {
            return $value->total_quantity > 0;
        });
//        dd($explaces,$filtered);

//        $parent=array();
        $visos=array();
        foreach ($explaces as $ex) {
            $parent = new PlacePath();
            if ($ex->warehouse == null) {
                $parent->place = $ex->id_Warehouse_place;
                $parent->path = $ex->Warehouse_name;
                $parent->placename = $ex->Warehouse_name;
//        dd($ex);
            } else {
                $parent->place = $ex->id_Warehouse_place;
                $parent->path = $ex->Warehouse_name;
                $parent->placename = $ex->Warehouse_name;
                $this->NUDAVAI($parent, $ex->warehouse);
            }

            array_push($visos, $parent);
        }

//        dd($visos);

        return view('ResourceManagement/deleteResource', compact('resource', 'types', 'allmembers',
            'house', 'explaces', 'items','visos'));
    }

    public function getCategoryDelete1($Id, $id, $id_place)
    {
        $data1 = Stock::where('stock.fk_Home', '=', $Id)->where('stock.fk_Stock_card', '=', $id)
            ->where('stock.fk_Warehouse_place', '=', $id_place)
            ->leftJoin('stock_card', 'id_Stock_card','=','stock.fk_Stock_card')
//            ->leftJoin('batch', 'batch.id_Stock_batch', '=', 'stock.fk_Batch')
            ->groupBy('stock.fk_Batch')
            ->select('stock.*','stock_card.*')
            ->selectRaw('SUM(quantity) as total_quantity')
//            ->where('stock.quantity', '>', 0)
            ->get();


        $data2 = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
//            ->
//        where('id_Warehouse_place', '!=', $id_place)
//            $data2=  $data3->except($id_place);
        ->where('removed',0)
        ->whereNull('warehouse_place.fk_Warehouse_place')
        ->get();

        \Log::info($data1);
        \Log::info($data2);
        return response()->json(['data' => $data1, 'data2' => $data2]);
    }

    public function getCategoryDelete($Id, $id, $id_place, $id_batch)
    {
        $data = Stock::where('stock.fk_Home', '=', $Id)->where('stock.fk_Stock_card', '=', $id)
            ->where('stock.fk_Warehouse_place', '=', $id_place)
            ->where('stock.fk_Batch', '=', $id_batch)
            ->select('stock.*')
            ->groupBy('expiration_date')
            ->selectRaw('SUM(quantity) as total_quantity')
//                ->where('stock.quantity', '>', 0)
            ->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function getCategoryDelete2($Id, $id, $id_place, $id_batch, $id_exp)
    {
        $data = Stock::where('stock.fk_Home', '=', $Id)->where('stock.fk_Stock_card', '=', $id)
            ->where('stock.fk_Warehouse_place', '=', $id_place)
            ->where('stock.fk_Batch', '=', $id_batch)
            ->where('stock.expiration_date', '=', $id_exp)
            ->select('stock.*')
            ->groupBy('expiration_date')
            ->selectRaw('SUM(quantity) as total_quantity')
//                ->where('stock.quantity', '>', 0)
            ->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }


    public function getCategoryMove6($Id, $id, $id_place)
    {
        $data = Warehouse_place::where('stock.fk_Home', '=', $Id)->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function confirmDeleteResource($Id, $id_resource, Request $request)
    {

        $batch = $request->input('sub_category');
        $place = $request->input('category');
        $inputAmount = $request->input('quantity');
        $expiration_date = $request->input('sub_category2');

        $validator = Validator::make(
            [
                'batch' => $request->input('sub_category'),
                'place' => $request->input('category'),
                'inputAmount' => $request->input('quantity'),
//                'expiration_date' => $request->input('sub_category2'),
                'Action_type' => $request->input('fk_Entry_type'),
            ],
            [
                'batch' => 'required',
                'place' => 'required',
                'inputAmount' => 'required',
//                'expiration_date' => 'required',
                'Action_type' => 'required'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }
        $total = 0;

        $stock = Stock::where('fk_Home', '=', $Id)
            ->where('fk_Stock_card', '=', $id_resource)
            ->where('fk_Batch', '=', $batch)
            ->where('fk_Warehouse_place', '=', $place)
            ->get();

        foreach ($stock as $st) {
            $total = $total + $st->quantity;
        }

        if ($inputAmount > $total) {
            return Redirect::back()->withErrors(['Not enough quantity. Max quantity is:' . $total])->withInput();
        }

        $newstock2 = new Stock();
        $newstock2->fk_Stock_card = $id_resource;
        $newstock2->fk_Warehouse_place = $request->input('category');
        $newstock2->fk_Batch = $batch;
        $newstock2->quantity = 0 - $inputAmount;
        $newstock2->fk_Entry_type = $request->input('fk_Entry_type');
        if($expiration_date!=null) {
            $newstock2->expiration_date = $expiration_date;
        }
        else{
            $newstock2->expiration_date = '9999-09-09';
        }
//        $newstock2->expiration_date = $expiration_date;
        $newstock2->posting_date = Carbon::now();
        $newstock2->fk_Home = $Id;
        $newstock2->save();

        return Redirect::route('resourceView', ['Id' => $Id, 'id_resource' => $id_resource])->with('success', 'Removed');
    }


    public function calculatePurchaseOffer($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $allitems = Purchase_Offer::all();
        $howManyadded=0;

        Purchase_Offer::where('fk_household_id', '=', $Id)
            ->where('byQuantity', '=', 1)
            ->where('byQuantity', '=', 4)
            ->delete();

        $items = Stock::where('stock.fk_Home', '=', $Id)
            ->join('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();


        foreach ($items as $item) {
            $itemplacelist = Stock_Place::where('fk_Home', '=', $Id)
                ->where('fk_Stock_card', '=', $item->fk_Stock_card)
                ->get();

            $summedminvalue = 0;
            foreach ($itemplacelist as $itemplace) {
                $summedminvalue += $itemplace['min_amount'];
            }
//////TODO Sitas reikalingas isvesti pranesimui jog jei atsargu yra kitur pakankamai, perkelk o ne siulyk pirkti
//            if ($summedminvalue!=0 && $summedminvalue< $item->total_quantity) {
//                $message=new MessageOut();
//                $message->stockname=$item->Stock_Card->Name;
//                $message->missingPlace=$item->total_quantity;
//                array_push($messages,$message);
////                dd($messages);
////                $message1='alala';
//                continue;
//            }

            foreach ($itemplacelist as $itemplace) {
                $itemQuantitiesForPlace = Stock::where('stock.fk_Home', '=', $Id)
                    ->where('stock.fk_Warehouse_place', '=', $itemplace->fk_Warehouse_place)
                    ->where('stock.fk_Stock_card', '=', $item->fk_Stock_card)
                    ->select('stock.*')
                    ->get();

                $itemTotalQuantityForPlace = 0;
                foreach ($itemQuantitiesForPlace as $itemQuantityForPlace) {
                    $itemTotalQuantityForPlace += $itemQuantityForPlace['quantity'];
                }

                if ($itemTotalQuantityForPlace >= $itemplace->min_amount) {
                    continue;
                }

                $itemQuantityToBuyForPlace = $itemplace->max_amount - $itemTotalQuantityForPlace;
                $purchaseOffer = new Purchase_Offer();
                $purchaseOffer->fk_stock_card_id = $item->fk_Stock_card;
                $purchaseOffer->fk_Warehouse_place = $itemplace->fk_Warehouse_place;
                $purchaseOffer->amount = $itemQuantityToBuyForPlace;
                $purchaseOffer->existing_amount = $itemTotalQuantityForPlace;
                $purchaseOffer->date = Carbon::now();
                $purchaseOffer->want_to_buy = 0;
                $purchaseOffer->byQuantity = 1;
                $purchaseOffer->fk_household_id = $Id;
                $purchaseOffer->save();
                $howManyadded+=1;
            }
        }
//
//        Session::flash('account', 'messages');
      if($howManyadded<1)
          return back()->with('success', 'Check finished, no items were missing.');
          else
        return back()->with('success', 'Check finished, missing items added to purchase offer.');
//                return view('ResourceManagement/purchaseOfferView', compact(' items','types','allmembers', 'house', 'allitems'));

    }


    public function makeWarehousePlace($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        return view('ResourceManagement/makeWarehousePlace', compact('types', 'allmembers', 'house'));

    }

    public function setCheckTimes($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $time = Time::where('fk_Home', '=', $Id)->first();


        if ($time == null) {
            $time = new Time();
            $time->id_Time = '';
            $time->Quantity_check_time1 = '';
            $time->Quantity_check_time2 ='';
            $time->Quantity_check_time3 = '';
            $time->Quantity_check_time4 = '';
            $time->Quantity_check_time5 = '';
            $time->Quantity_check_time6 = '';
            $time->Quantity_check_time7 = '';
            $time->Expiration_check_time1 = '';
            $time->Expiration_check_time2 = '';
            $time->Expiration_check_time3 = '';
            $time->Expiration_check_time4 = '';
            $time->Expiration_check_time5 = '';
            $time->Expiration_check_time6 = '';
            $time->Expiration_check_time7 = '';
            $time->fk_Home = $Id;

        }

        return view('ResourceManagement/setCheckTimes', compact('types', 'allmembers',
             'house', 'time'));

    }

    public function saveCheckTimes($Id, Request $request)
    {
        $time = Time::where('fk_Home', '=', $Id)->first();
        if ($time == null) {
            $time = new Time();
            $time->Quantity_check_time1 = $request->input('Quantity_check_time1');
            $time->Quantity_check_time2 = $request->input('Quantity_check_time2');
            $time->Quantity_check_time3 = $request->input('Quantity_check_time3');
            $time->Quantity_check_time4 = $request->input('Quantity_check_time4');
            $time->Quantity_check_time5 = $request->input('Quantity_check_time5');
            $time->Quantity_check_time6 = $request->input('Quantity_check_time6');
            $time->Quantity_check_time7 = $request->input('Quantity_check_time7');
            $time->Expiration_check_time1 = $request->input('Expiration_check_time1');
            $time->Expiration_check_time2 = $request->input('Expiration_check_time2');
            $time->Expiration_check_time3 = $request->input('Expiration_check_time3');
            $time->Expiration_check_time4 = $request->input('Expiration_check_time4');
            $time->Expiration_check_time5 = $request->input('Expiration_check_time5');
            $time->Expiration_check_time6 = $request->input('Expiration_check_time6');
            $time->Expiration_check_time7 = $request->input('Expiration_check_time7');
            $time->fk_Home = $Id;
            $time->save();

            return back()->with('success', 'Saved');
        } else {
            $time->update(
                [
                    'Quantity_check_time1' => $request->input('Quantity_check_time1'),
                    'Quantity_check_time2' => $request->input('Quantity_check_time2'),
                    'Quantity_check_time3' => $request->input('Quantity_check_time3'),
                    'Quantity_check_time4' => $request->input('Quantity_check_time4'),
                    'Quantity_check_time5' => $request->input('Quantity_check_time5'),
                    'Quantity_check_time6' => $request->input('Quantity_check_time6'),
                    'Quantity_check_time7' => $request->input('Quantity_check_time7'),
                    'Expiration_check_time1' => $request->input('Expiration_check_time1'),
                    'Expiration_check_time2' => $request->input('Expiration_check_time2'),
                    'Expiration_check_time3' => $request->input('Expiration_check_time3'),
                    'Expiration_check_time4' => $request->input('Expiration_check_time4'),
                    'Expiration_check_time5' => $request->input('Expiration_check_time5'),
                    'Expiration_check_time6' => $request->input('Expiration_check_time6'),
                    'Expiration_check_time7' => $request->input('Expiration_check_time7')
                ]);
            return back()->with('success', 'Updated');
        }

    }


    public function calculatePurchaseOfferExpiration($Id)
    {
        $howManyAdded=0;
        Purchase_Offer::where('fk_household_id', '=', $Id)
            ->where('byQuantity', '=', 0)
            ->where('byQuantity', '=', 4)
            ->delete();

        $items = Stock::where('stock.fk_Home', '=', $Id)
            ->leftjoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->groupBy('fk_Warehouse_place')
//            ->selectRaw('SUM(quantity) as total_quantity_per_place')
            ->groupBy('fk_Batch')
            ->groupBy('expiration_date')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->get();

        $itemsperplace = Stock::where('stock.fk_Home', '=', $Id)
            ->leftjoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
            ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
            ->groupBy('fk_Stock_card')
            ->groupBy('fk_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity_per_place')
            ->get();



//        $purchaseOfferItems = Purchase_Offer::where('fk_household_id', '=', $Id)
//            ->where( 'byQuantity','=', 0)
//            ->get();
//        $count = $purchaseOfferItems->count();

        $todaydate = Carbon::now()->format('Y-m-d');
        $itemTotalQuantityForPlace = 0;
        foreach ($items as $item) {
//            dd($item);
            $itemDate = $item->expiration_date;
            $itemTotalQuantityForPlace = $itemTotalQuantityForPlace + $item->total_quantity;

            if ($itemDate < $todaydate && $item->total_quantity>0) {

                $offerItem = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->where( 'byQuantity','=', 0)
                    ->where('fk_stock_card_id', '=', $item->fk_Stock_card)
                    ->where('fk_Warehouse_place', '=', $item->fk_Warehouse_place)
                    ->first();

                if($offerItem!=null){
                            $offerItem->update(
                                [
                                    'amount' => $offerItem->amount + $item->total_quantity,
                                ]
                            );
                        }
                        else {
                            foreach ($itemsperplace as $offerItem) {
                                if ($offerItem->fk_Stock_card == $item->fk_Stock_card && $offerItem->fk_Warehouse_place == $item->fk_Warehouse_place) {
                            $purchaseOffer = new Purchase_Offer();
                            $purchaseOffer->fk_stock_card_id = $item->fk_Stock_card;
                            $purchaseOffer->fk_Warehouse_place = $item->fk_Warehouse_place;
                            $purchaseOffer->amount = $item->total_quantity;
                            $purchaseOffer->date = Carbon::now();
                            $purchaseOffer->want_to_buy = 0;
                            $purchaseOffer->byQuantity = 0;
                            $purchaseOffer->existing_amount = $offerItem->total_quantity_per_place;
                            $purchaseOffer->fk_household_id = $Id;
                            $purchaseOffer->save();
                            $howManyAdded+=1;
//                        dd($purchaseOffer);
                        }
                    }
                }
                }

            }
        if($howManyAdded<1)
            return back()->with('success', 'Expiration check finished, no items are expired.');
        else
            return back()->with('success', 'Expiration finished, missing items added to purchase offer.');

        }

    public static function generatePurchaseOfferFromRecipe($id_House,$id_Warehouse,$id_Stock_card,$amount,$existingAmount){
        Purchase_Offer::create([
            'fk_stock_card_id'=>$id_Stock_card,
            'fk_Warehouse_place'=>$id_Warehouse,
            'fk_household_id'=>$id_House,
            'amount'=>$amount,
            'date'=>Carbon::now(),
            'want_to_buy'=>0,
            'byQuantity'=>2,
            'existing_amount'=>$existingAmount,
            'who_added'=>Auth::id(),
        ]);
    }

    public function search($Id, Request $request, $filter_id,$button){
        $types = Supplier_Type::all();
        $title = 'Resources list';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $stockTypes = Stock_Type::where('fk_household_id', '=', $Id)->get();

        // Get the search value from the request
        $search = $request->input('search');
        if($filter_id >0) {
            $data = Stock::where('stock.fk_Home', '=', $Id)
                ->leftJoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
                ->where('stock_card.fk_Stock_type', '=', $filter_id)
                ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
                ->groupBy('fk_Stock_card')
//            ->where('stock_card.Name', 'LIKE', "%{$search}%")
//            ->orWhere('stock_card.Description', 'LIKE', "%{$search}%")
                ->where(function ($q) use ($search) {
                    $q->where('stock_card.Name', 'LIKE', "%{$search}%")
                        ->orWhere('stock_card.Description', 'LIKE', "%{$search}%");
                })
                ->selectRaw('SUM(quantity) as total_quantity')
                ->get();

            $Type=  Stock_Type::where('fk_household_id', '=', $Id)
                ->where('id_Stock_type',$filter_id)
                ->first();
        }
        else {
            $data = Stock::where('stock.fk_Home', '=', $Id)
                ->leftJoin('stock_card', 'stock.fk_Stock_card', '=', 'id_Stock_card')
                ->select('fk_Stock_card', 'stock.*', 'stock_card.*')
                ->groupBy('fk_Stock_card')
                ->where('stock_card.Name', 'LIKE', "%{$search}%")
                ->orWhere('stock_card.Description', 'LIKE', "%{$search}%")
                ->selectRaw('SUM(quantity) as total_quantity')
                ->get();
            $Type=  null;
        }
        $count=count($data);
        $items = collect($data)->paginate(9);


        return view('ResourceManagement/resourcelist', compact('filter_id','items', 'types', 'title', 'allmembers', 'house',
            'stockTypes','search','Type','button','count'));
    }

    public function getResourceCards($Id, $type)
    {
        $data = Stock_Card::where('fk_Stock_type', '=', $type)
            ->where('fk_Home', '=', $Id)
            ->where('removed', '=', 0)
            ->get();

        \Log::info($data);
        return response()->json(['data' => $data]);
    }

    public function NUDAVAI($parent, $ex){

        $pparent=Warehouse_place::where('id_Warehouse_place',$ex)->first();
        $parent->path=$pparent->Warehouse_name .'->'. strval($parent->path);
        if( $pparent->fk_Warehouse_place==null){
//            $parent->path.=$ex->Warehouse_name;
        }
        else{
//        $parent->path.=$ex->warehouse;
            $this->NUDAVAI($parent, $pparent->fk_Warehouse_place);
        }
        return $parent;

    }

}
