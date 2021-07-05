<?php

namespace App\Http\Controllers\ResourceManagement;

use App\Http\Controllers\Controller;
use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\HouseholdResource\Stock_Supplier;
use App\Models\HouseholdResource\Supplier;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\ResourceManagement\Purchase_Offer;
use App\Models\ResourceManagement\Stock;
use App\Models\ResourceManagement\Stock_Type;
use App\Models\ResourceManagement\Warehouse_place;
use App\Models\UserManagement\Home_Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PlacePath{
    public $place;
    public $placename;
    public $path;
}

class PurchaseOfferController extends Controller
{
    public function index($Id,$user){

//        $items=Purchase_Offer::where('fk_household_id','=', $Id)->get();
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();

        $suppliersAll=Stock_Supplier::all();

if($user!='all') {
    $items = Purchase_Offer::where('fk_household_id', '=', $Id)->where('buyer', '=', $user)
        ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'id_Stock_card')
        ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
        ->groupBy('fk_stock_card_id')
        ->selectRaw('SUM(amount) as total_quantity')
        ->get();
    $title='Personal purchase offer';
}
else{
    $items = Purchase_Offer::where('fk_household_id', '=', $Id)
        ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'id_Stock_card')
        ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
        ->groupBy('fk_stock_card_id')
        ->selectRaw('SUM(amount) as total_quantity')
        ->get();
    $title='Household purchase offer';
}

        $allGroupedStocks = Stock::where('fk_Home','=',$Id)
            ->select('stock.*')
            ->groupBy('fk_Stock_card')
            ->groupBy('fk_Warehouse_place')
           ->selectRaw('SUM(quantity) as total_quantity_in_place')
            ->get();

        $allitems=Purchase_Offer::where('fk_household_id','=', $Id)
            ->leftJoin('stock_place', function ($join) {
                $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                    ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
            })
            ->groupBy('purchase_offer.fk_stock_card_id')
            ->groupBy('purchase_offer.fk_Warehouse_place')
            ->select('stock_place.*', 'purchase_offer.*')
            ->selectRaw('SUM(amount) as total_quantity_in_place')
            ->get();
//dd($allitems);
        $allitemsDetailed=Purchase_Offer::where('fk_household_id','=', $Id)
            ->leftJoin('stock_place', function ($join) {
                $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                    ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
            })
            ->select('stock_place.*', 'purchase_offer.*')
            ->get();

        $suppliers=Stock_Supplier::query()
            ->groupBy('fk_supplier')
            ->get();

        $supplierforFilter=Supplier::where('fk_household_id','=',$Id)->get();

        $stockTypes=Stock_Type::where('fk_household_id',$Id)->get();

        $explaces = DB::table('warehouse_place')
            ->where('fk_Home', '=', $Id)
            ->get();
        $visos=array();
        foreach ($explaces as $ex){
            $parent=new PlacePath();
            if($ex->fk_Warehouse_place==null){
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
            }
            else{
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
                $this->NUDAVAI($parent, $ex->fk_Warehouse_place);
            }
            array_push($visos, $parent);
        }
        $volume  = array_column($visos, 'place');
        $edition = array_column($visos, 'path');
// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
        array_multisort( $edition, SORT_STRING, $visos);

        $supplier=' ';
        $type=' ';

        return view('ResourceManagement/purchaseOfferView', compact('title','items','types',
            'allmembers', 'house','allitems','suppliers', 'allGroupedStocks','stockTypes','allitemsDetailed',
            'supplierforFilter','visos', 'supplier','type','user','suppliersAll'));
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

    public function filteredPurchaseOffer($Id, $user, $supplier,$type){

        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $stockTypes=Stock_Type::where('fk_household_id',$Id)->get();
//        $title='Household purchase offer /' . $filter_id;

        if($user=='all') {
            if ($type == ' ' && $supplier != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'id_Stock_card')
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->join('stocks_supplier', function ($join) use ($supplier) {
                        $join->on('stocks_supplier.fk_stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stocks_supplier.fk_supplier', '=', $supplier);
                    })
//            ->leftJoin('stocks_supplier','stocks_supplier.fk_stock_card','=','purchase_offer.fk_stock_card_id')
//           ->where('stocks_supplier.fk_supplier','=',$filter_id)
//            ->select('stocks_supplier.*')
                    ->get();
//                dd($items);
            } elseif ($type != ' ' && $supplier != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
//                ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'stock_card.id_Stock_card')
                    ->join('stock_card', function ($join) use ($type) {
                        $join->on('stock_card.id_Stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stock_card.fk_Stock_type', '=', $type);
                    })
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->join('stocks_supplier', function ($join) use ($supplier) {
                        $join->on('stocks_supplier.fk_stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stocks_supplier.fk_supplier', '=', $supplier);
                    })
                    ->get();
            } elseif ($supplier == ' ' && $type != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->join('stock_card', function ($join) use ($type) {
                        $join->on('stock_card.id_Stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stock_card.fk_Stock_type', '=', $type);
                    })
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->get();
            }
        }
        else{
            if ($type == ' ' && $supplier != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->where('buyer', '=', $user)
                    ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'id_Stock_card')
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->join('stocks_supplier', function ($join) use ($supplier) {
                        $join->on('stocks_supplier.fk_stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stocks_supplier.fk_supplier', '=', $supplier);
                    })
                    ->get();

            } elseif ($type != ' ' && $supplier != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->where('buyer', '=', $user)
//                ->join('stock_card', 'purchase_offer.fk_stock_card_id', '=', 'stock_card.id_Stock_card')
                    ->join('stock_card', function ($join) use ($type) {
                        $join->on('stock_card.id_Stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stock_card.fk_Stock_type', '=', $type);
                    })
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->join('stocks_supplier', function ($join) use ($supplier) {
                        $join->on('stocks_supplier.fk_stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stocks_supplier.fk_supplier', '=', $supplier);
                    })
                    ->get();
            } elseif ($supplier == ' ' && $type != ' ') {
                $items = Purchase_Offer::where('fk_household_id', '=', $Id)
                    ->where('buyer', '=', $user)
                    ->join('stock_card', function ($join) use ($type) {
                        $join->on('stock_card.id_Stock_card', '=', 'purchase_offer.fk_stock_card_id')
                            ->where('stock_card.fk_Stock_type', '=', $type);
                    })
                    ->select('fk_stock_card_id', 'purchase_offer.*', 'stock_card.*')
                    ->groupBy('fk_stock_card_id')
                    ->selectRaw('SUM(amount) as total_quantity')
                    ->get();
            }

        }

        $allGroupedStocks = Stock::where('fk_Home','=',$Id)
            ->select('stock.*')
            ->groupBy('fk_Stock_card')
            ->groupBy('fk_Warehouse_place')
            ->selectRaw('SUM(quantity) as total_quantity_in_place')
            ->get();

        $allitems=Purchase_Offer::where('fk_household_id','=', $Id)
            ->leftJoin('stock_place', function ($join) {
                $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                    ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
            })
            ->groupBy('purchase_offer.fk_stock_card_id')
            ->groupBy('purchase_offer.fk_Warehouse_place')
            ->select('stock_place.*', 'purchase_offer.*')
            ->selectRaw('SUM(amount) as total_quantity_in_place')
            ->get();

        $allitemsDetailed=Purchase_Offer::where('fk_household_id','=', $Id)
            ->leftJoin('stock_place', function ($join) {
                $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                    ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
            })
            ->select('stock_place.*', 'purchase_offer.*')
            ->get();

        $suppliers=Stock_Supplier::query()
            ->groupBy('fk_supplier')
            ->get();
//        dd($suppliers);
        $suppliersAll=Stock_Supplier::all();

        $supplier=Supplier::where('supplier_id','=',$supplier)
            ->first();
        if($supplier==null ){
            $supplier=' ';
        }
        $type=Stock_Type::where('id_Stock_type','=',$type)
            ->first();
        if($type==null ){
            $type=' ';
        }

        $title='Filtered purchase offer';

        $explaces = DB::table('warehouse_place')
            ->where('fk_Home', '=', $Id)
            ->get();
        $visos=array();
        foreach ($explaces as $ex){
            $parent=new PlacePath();
            if($ex->fk_Warehouse_place==null){
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
            }
            else{
                $parent->place=$ex->id_Warehouse_place;
                $parent->path=$ex->Warehouse_name;
                $parent->placename=$ex->Warehouse_name;
                $this->NUDAVAI($parent, $ex->fk_Warehouse_place);
            }
            array_push($visos, $parent);
        }
        $volume  = array_column($visos, 'place');
        $edition = array_column($visos, 'path');
        array_multisort( $edition, SORT_STRING, $visos);


        return view('ResourceManagement/purchaseOfferView', compact('title','items','types',
            'allmembers', 'house','allitems','stockTypes','suppliers','allGroupedStocks',
            'allitemsDetailed','visos','supplier','type','user','suppliersAll'));
    }


    public function insertToPurchaseOffer($Id, $user){
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $cards=Stock_Card::where('fk_Home', '=', $Id)->get();
        $places=Warehouse_place::where('fk_Home', '=', $Id)->get();
        $stockTypes=Stock_Type::where('fk_household_id',$Id)->get();
        $topPlaces = Warehouse_place::where('warehouse_place.fk_Home', '=', $Id)
            ->where('removed',0)
            ->whereNull('warehouse_place.fk_Warehouse_place')
            ->get();

        return view('ResourceManagement/insertToPurchaseOffer', compact('cards','places','types','allmembers',
            'house','user','stockTypes','topPlaces'));
    }

    public function saveInsertToPurchaseOffer($Id, Request $request,$user){

        $validator = Validator::make(
            [
                'fk_Stock_card' => $request->input('category'),
                'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                'quantity' => $request->input('quantity')
            ],
            [
                'fk_Stock_card' => 'required',
                'fk_Warehouse_place' => 'required',
                'quantity' => 'required | min:0.01'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }
        else {
            $itemperplace = Stock::where('stock.fk_Home', '=', $Id)
                ->where('fk_Stock_card',$request->input('category'))
                ->where('fk_Warehouse_place',$request->input('fk_Warehouse_place'))
                ->select('stock.*')
                ->groupBy('fk_Stock_card')
                ->groupBy('fk_Warehouse_place')
                ->selectRaw('SUM(quantity) as total_quantity_per_place')
                ->first();
//            dd($itemperplace);

            $currentuserid = Auth::user()->id;
            if($user=='all') {
//            $cat=$request->input('category');
//            $pla=$request->input('fk_Warehouse_place');
//
//            $newstock = Purchase_Offer::where('fk_household_id','=',$Id)->where('fk_stock_card_id', '=', $cat)
//                    ->where('fk_Warehouse_place', '=', $pla)->first();
//            dd($newstock);
//            if($newstock == null) {
                $newstock = new Purchase_Offer();
                $newstock->fk_stock_card_id = $request->input('category');
                $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
                $newstock->amount = $request->input('quantity');
                $newstock->date = Carbon::now();
                $newstock->want_to_buy = 0;
                $newstock->byQuantity = 3;
                if($itemperplace!=null) {
                    $newstock->existing_amount = $itemperplace->total_quantity_per_place;
                }
                else{
                    $newstock->existing_amount = 0;
                }
                $newstock->who_added = $currentuserid;
                $newstock->fk_household_id = $Id;
                $newstock->save();

                return Redirect::route('purchaseOffer', ['Id' => $Id, 'user' => $user])->with('success', 'Item added');
            }
            else{
                $newstock = new Purchase_Offer();
                $newstock->fk_stock_card_id = $request->input('category');
                $newstock->fk_Warehouse_place = $request->input('fk_Warehouse_place');
                $newstock->amount = $request->input('quantity');
                $newstock->date = Carbon::now();
                $newstock->want_to_buy = 1;
                $newstock->buyer = $user;
                $newstock->byQuantity = 3;
                $newstock->fk_household_id = $Id;
                $newstock->save();

                return Redirect::route('purchaseOffer', ['Id' => $Id, 'user' => $user])->with('success', 'Item added');
            }
//            }
//            else{
//                $newstock->update(
//                    [
//                        $newstock->amount =  $newstock->amount + $request->input('quantity')
//                    ]);
//                return Redirect::route('purchaseOffer', ['Id' => $Id])->with('success', 'Item added to personal purchaseoffer');
//            }
        }
        }

        public function itemedit($Id, $id, $card, $place,Request $request){

//            $inserted=$request->input('number');
//            if($inserted>0) {
//                $newstock->update(
//                    [
//                        $newstock->amount = $request->input('number')
//                    ]);
//                return back()->with('success', 'Quantity updated');
//            }
//            else
//                {
//                    $newstock->forceDelete();
//                    return back()->with('success', 'Removed');
//
//                }

            $inserted=$request->input('number');
            $checkIfStockAddedToPersonal = Purchase_Offer::where('fk_household_id','=',$Id)
                ->where('fk_stock_card_id', '=', $card)
                ->where('fk_Warehouse_place', '=', $place)
                ->first();
            $currentuserid = Auth::user()->id;
            if($inserted>0) {
                $newstock = new Purchase_Offer();
                $newstock->fk_stock_card_id = $card;
                $newstock->fk_Warehouse_place =$place;
                $newstock->amount = $request->input('number') - $id;
                $newstock->date = Carbon::now();
                if($checkIfStockAddedToPersonal->want_to_buy>0) {
                    $newstock->want_to_buy = 1;
                    $newstock->buyer = $checkIfStockAddedToPersonal->buyer;
                }
                else   $newstock->want_to_buy = 0;
                $newstock->byQuantity = 3;
                $newstock->who_added = $currentuserid;
                $newstock->fk_household_id = $Id;
                $newstock->save();
                return back()->with('success', 'Quantity updated');
            }

            else
                {
                    $oldstock = Purchase_Offer::where('fk_household_id','=',$Id)
                        ->where('fk_stock_card_id', '=', $card)
                        ->where('fk_Warehouse_place', '=', $place)
                        ->get();
                    foreach ($oldstock as $old) {
                    $old->forceDelete();
                    }
                    return back()->with('success', 'Removed');

                }

        }

        public function insertToOwn($Id, $item, $user){
            $toAdd = Purchase_Offer::where('fk_household_id','=',$Id)->where('fk_stock_card_id', '=', $item)->get();
            foreach ($toAdd as $add){
                $add->update(
                   [ $add->want_to_buy=1,
                     $add->buyer=$user
                   ]);
            }
            return back()->with('success', 'Updated');
        }

    public function removeFromOwn($Id, $item, $user){
        $toAdd = Purchase_Offer::where('fk_household_id','=',$Id)->where('fk_stock_card_id', '=', $item)->get();
        foreach ($toAdd as $add){
            $add->update(
                [ $add->want_to_buy=0,
                    $add->buyer=null
                ]);
        }
        return back()->with('success', 'Updated');
    }




        public function userPurchaseOffer($Id, $user){
            $types = Supplier_Type::all();
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $Id)->first();
            $title='Personal purchase offer';


            $items= Purchase_Offer::where('fk_household_id','=',$Id)
                ->join('stock_card', 'purchase_offer.fk_stock_card_id','=', 'id_Stock_card')
                ->select('fk_stock_card_id','purchase_offer.*', 'stock_card.*')
                ->groupBy('fk_stock_card_id')
                ->selectRaw('SUM(amount) as total_quantity')
                ->get();

            $allitems=Purchase_Offer::where('fk_household_id','=', $Id)
                ->leftJoin('stock_place', function ($join) {
                    $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                        ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
                })
                ->groupBy('purchase_offer.fk_stock_card_id')
                ->groupBy('purchase_offer.fk_Warehouse_place')
                ->select('stock_place.*', 'purchase_offer.*')
                ->selectRaw('SUM(amount) as total_quantity_in_place')
                ->get();


            $suppliers=Stock_Supplier::query()
                ->groupBy('fk_supplier')
                ->get();

            $supplierforFilter=Supplier::where('fk_household_id','=',$Id)->get();


            $allGroupedStocks = Stock::where('fk_Home','=',$Id)
                ->select('stock.*')
                ->groupBy('fk_Stock_card')
                ->groupBy('fk_Warehouse_place')
                ->selectRaw('SUM(quantity) as total_quantity_in_place')
                ->get();

            $allitemsDetailed=Purchase_Offer::where('fk_household_id','=', $Id)
                ->leftJoin('stock_place', function ($join) {
                    $join->on('purchase_offer.fk_stock_card_id','=', 'stock_place.fk_Stock_card')
                        ->on('purchase_offer.fk_Warehouse_place','=','stock_place.fk_Warehouse_place');
                })
                ->select('stock_place.*', 'purchase_offer.*')
                ->get();

            return view('ResourceManagement/purchaseOfferView', compact('title','items','types','allmembers',
                'house','allitems','suppliers','supplierforFilter','allitemsDetailed','allGroupedStocks'));

        }

        public function clearPurchaseOffer($Id){
            $oldstock = Purchase_Offer::where('fk_household_id','=',$Id)
                ->get();
            foreach ($oldstock as $old) {
                $old->forceDelete();
            }
            return back()->with('success', 'Purchase offer has been cleared');
        }



}
