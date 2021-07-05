<?php

namespace App\Http\Controllers\ResourceManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RecipeManagement\RecipeController;
use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\HouseholdResource\Stock_Supplier;
use App\Models\HouseholdResource\Supplier;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\ResourceManagement\Stock;
use App\Models\ResourceManagement\Stock_Type;
use App\Models\ResourceManagement\Warehouse_place;
use App\Models\UserManagement\Home_Member;
use App\Models\UserManagement\Locked_Warehouses;
use App\Models\UserManagement\User_Permission;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class ResourceListController extends Controller
{
    public function index($id_house)
    {
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $allmembers = Home_Member::all();
        $stock_types = Stock_Card::all();
        $title = 'Stock card';
        $typ = 'null';
        $search = null;
        $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
        $as= count($allstocks);
        $stypes = Stock_Type::where('fk_household_id', '=', $id_house)->get();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('ResourceManagement/cardlist', compact('types', 'allstocks', 'typ', 'search', 'stypes', 'permission_create', 'as', 'title', 'stock_types', 'allmembers', 'house'));
    }

    public function stockTypeList($id_house)
    {
        $types = Supplier_Type::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $allmembers = Home_Member::all();
        $stock_types = Stock_Type::where('fk_household_id', '=', $id_house)->get();
        $title = 'Resource types';
        return view('ResourceManagement/cardlist', compact('types', 'stock_types', 'title', 'allmembers', 'house'));
    }

    public function createStockType($Id)
    {
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $title = 'Stock type';
        return view('ResourceManagement/addCard', compact('allmembers', 'house', 'types', 'title'));
    }

    public static function createStockCard($Id)
    {
        $types = Supplier_Type::all();
        $stock_types = Stock_Type::where('fk_household_id', '=', $Id)->get();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $title = 'Stock card';
        return view('ResourceManagement/addCard', compact('allmembers', 'types', 'house', 'title', 'stock_types'));
    }

    public function addCard(Request $request, $Id, $title)
    {
        if ($title == 'Stock card') {
            $validator = Validator::make(
                [
                    'Name' => $request->input('Name'),
                    'fk_Stock_type' => $request->input('fk_Stock_type'),
                    'measurement_unit' => $request->input('measurement_unit')
                ],
                [
                    'Name' => 'required|min:3|max:30',
                    'fk_Stock_type' => 'required',
                    'measurement_unit' => 'required'
                ]
            );

            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $filename = null;
                $filenam = null;

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $filenam = time();
                    $location = public_path('images/' . $filename);
                    Image::make($image)->resize(200, 200)->save($location);

                    $new= Stock_Card::Create([
                        'Name' => $request->input('Name'),
                        'Description' => $request->input('Description'),
                        'fk_Home' => $Id,
                        'fk_Stock_type' => $request->input('fk_Stock_type'),
                        'measurement_unit' => $request->input('measurement_unit'),
                        'image' => $filenam
                    ]);
                } else {
                    $new=Stock_Card::Create([
                        'Name' => $request->input('Name'),
                        'Description' => $request->input('Description'),
                        'fk_Home' => $Id,
                        'fk_Stock_type' => $request->input('fk_Stock_type'),
                        'measurement_unit' => $request->input('measurement_unit'),
                        'image' => 'missing'
                    ]);
                }

                $house = Home::where('id_Home', '=', $Id)->first();
                $allmembers = Home_Member::all();
                $types = Stock_Card::all();
                $title = 'Stock card';
                $stock_types = Stock_Card::where('fk_Home', '=', $Id)->get();
                $allstocks = Stock_Card::where('fk_Home', '=', $Id)->where('removed', '=', 0)->get()->paginate(8);
                $as= count($allstocks);
                $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $Id)->first();
                if(session()->has('recipeName')){
                    return RecipeController::createStock($new->id_Stock_card);
                }
                return redirect()->route('stockCards', ['id_house' => $Id])->with('success', 'Resource card created');
            }
        } elseif($title == 'Stock type') {
            $validator = Validator::make(
                [
                    'Type_name' => $request->input('Type_name')
                ],
                [
                    'Type_name' => 'required|min:3|max:50'
                ]
            );

            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                Stock_Type::Create([
                    'Type_name' => $request->input('Type_name'),
                    'Type_description' => $request->input('Type_description'),
                    'fk_household_id' => $Id
                ]);

                return redirect()->route('CreateStockCard', ['Id' => $Id])->with('success', 'Resource type created');
            }
        } else {
            $validator = Validator::make(
                [
                    'Warehouse_name' => $request->input('Warehouse_name')
                ],
                [
                    'Warehouse_name' => 'required|min:3|max:50'
                ]
            );

            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput();
            } elseif($title == 'Warehouse place') {
                Warehouse_place::Create([
                    'Warehouse_name' => $request->input('Warehouse_name'),
                    'Address' => $request->input('Address'),
                    'Description' => $request->input('Description'),
                    'fk_Warehouse_place' => $request->input('fk_Warehouse_place'),
                    'fk_Home' => $Id
                ]);

                $house = Home::where('id_Home', '=', $Id)->first();
                $allmembers = Home_Member::all();
                $types = Stock_Card::all();
                $title = 'Warehouse place';
                $wares=Warehouse_place::all();
                $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('removed', '=', 0)->get()->paginate(8);
                $as= count($allstocks);
                $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
                $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $Id)->first();
                return redirect()->route('warehousePlaces', ['id_house' => $Id])->with('success', 'Storage created');
            }
        }
    }

    public function Card($id_house, $Id, $title)
    {
        if($title == 'Stock card') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $stock = Stock_Card::where('id_Stock_card', '=', $Id)->first();
            $types = Supplier_Type::all();
            $supplier = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 0)->get();
            $supplierbelong = Stock_Supplier::where('fk_stock_card', '=', $Id)->get();
            $sc = count($supplierbelong);
            $stoc_t = Stock_Type::where('fk_household_id', '=', $id_house)->get();
            $permission_edit = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 8)->where('fk_household_id', '=', $id_house)->first();
            $permission_delete = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 9)->where('fk_household_id', '=', $id_house)->first();
            return view('ResourceManagement/card', compact('stock', 'permission_edit', 'permission_delete', 'sc', 'stoc_t', 'supplierbelong', 'supplier', 'house', 'types', 'allmembers', 'title'));
        } elseif($title == 'Warehouse place') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $ware = Warehouse_place::where('id_Warehouse_place', '=', $Id)->first();
            $types = Supplier_Type::all();
            $storage = Warehouse_place::where('fk_Warehouse_place', '=', $Id)->where('removed', '=', 0)->get();
            $countstorageplaces = count($storage);
            $allwares = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
            $warehouses = Warehouse_place::where('id_Warehouse_place', '=', $ware->fk_Warehouse_place)->first();
            $permission_edit = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 8)->where('fk_household_id', '=', $id_house)->first();
            $permission_delete = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 9)->where('fk_household_id', '=', $id_house)->first();
            return view('ResourceManagement/card', compact('ware', 'allwares', 'countstorageplaces', 'storage', 'warehouses', 'permission_edit', 'permission_delete', 'house', 'types', 'allmembers', 'title'));
        }
    }

    public function confirmEditCard(Request $request, $Id, $title)
    {
        if($title == 'Stock card') {
            $stock = Stock_Card::where('id_Stock_card', '=', $Id)->first();
            $filename = null;
            $filenam = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $filenam = time();
                $location = public_path('images/' . $filename);
                Image::make($image)->resize(200, 200)->save($location);

                $data = Stock_Card::where('id_Stock_card', '=', $Id)->update([
                    'Name' => $request->input('Name'),
                    'Description' => $request->input('Description'),
                    'fk_Stock_type' => $request->input('fk_Stock_type'),
                    'measurement_unit' => $request->input('measurement_unit'),
                    'image' => $filenam
                ]);
            } else {
                $data = Stock_Card::where('id_Stock_card', '=', $Id)->update([
                    'Name' => $request->input('Name'),
                    'Description' => $request->input('Description'),
                    'fk_Stock_type' => $request->input('fk_Stock_type'),
                    'measurement_unit' => $request->input('measurement_unit'),
                ]);
            }
            return redirect()->route('stockCards', ['id_house' => $stock->fk_Home])->with('success', 'Resource card information was edited');
        } elseif($title == 'Warehouse place') {
            $t = $request->input('fk_Warehouse_place');
            if($t != 0) {
                $ware = Warehouse_place::where('id_warehouse_place', '=', $Id)->first();
                $data = Warehouse_place::where('id_Warehouse_place', '=', $Id)->update(
                    [
                        'Warehouse_name' => $request->input('Warehouse_name'),
                        'Address' => $request->input('Address'),
                        'Description' => $request->input('Description'),
                        'fk_Warehouse_place' => $request->input('fk_Warehouse_place')
                    ]);
            }
            else {
                $ware = Warehouse_place::where('id_warehouse_place', '=', $Id)->first();
                $data = Warehouse_place::where('id_Warehouse_place', '=', $Id)->update(
                    [
                        'Warehouse_name' => $request->input('Warehouse_name'),
                        'Address' => $request->input('Address'),
                        'Description' => $request->input('Description')
                    ]);
            }
            return redirect()->route('warehousePlaces', ['id_house' => $ware->fk_Home])->with('success', 'Storage information was edited');
        }
    }

    public function manageCardEdit($id_house, $Id, $title)
    {
        if($title == 'Stock card') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $stock = Stock_Card::where('id_Stock_card', '=', $Id)->first();
            $types = Supplier_Type::all();
            $stock_types = Stock_Type::where('fk_household_id', '=', $id_house)->get();
            $now_type = Stock_Type::where('id_Stock_type', '=', $stock->fk_Stock_type)->first();
            $supplier = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 0)->get();
            $supp_types = Supplier_Type::where('fk_household_id', '=', $id_house)->get();
            $supplierbelong = Stock_Supplier::where('fk_stock_card', '=', $Id)->get();
            $sc = count($supplierbelong);
            return view('ResourceManagement/cardEdit', compact('stock', 'sc', 'supplierbelong', 'types', 'house', 'supplier', 'supp_types', 'title', 'now_type', 'allmembers', 'stock_types'));
        } elseif($title == 'Warehouse place') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $ware = Warehouse_place::where('id_Warehouse_place', '=', $Id)->first();
            $types = Supplier_Type::all();
            $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
            $ware_types = Warehouse_place::where('fk_Home', '=', $id_house)->get();
            if($ware->fk_Warehouse_place == null) {
                $now_place = null;
            } else {
                $now_place = Warehouse_place::where('id_Warehouse_place', '=', $ware->fk_Warehouse_place)->first();
            }
            return view('ResourceManagement/cardEdit', compact('ware', 'lockedwarehouse', 'now_place', 'types', 'ware_types', 'house', 'title', 'allmembers'));
        }
    }

    public function manageCard($id_house, $Id, $title)
    {
        if($title == 'Stock card') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $stock = Stock_Card::where('id_Stock_card', '=', $Id)->first();
            $types = Supplier_Type::all();
            return view('ResourceManagement/card', compact('stock', 'types', 'house', 'title', 'allmembers'));
        } elseif($title == 'Warehouse place') {
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $id_house)->first();
            $ware = Warehouse_place::where('id_Warehouse_place', '=', $Id)->first();
            $types = Supplier_Type::all();
            return view('ResourceManagement/card', compact('ware', 'types', 'house', 'title', 'allmembers'));
        }
    }

    public function removeStockCard($Id)
    {
        //$title = "Stock card";
        $home = Stock_Card::where('id_Stock_card', '=', $Id)->first();
        $homeid = $home->fk_Home;
        $stock = Stock::where('fk_Stock_card', '=', $Id)->get();
        $sto = count($stock);
        $dt = Carbon::now();
        $dt->toArray('Y-m-d');
        if($sto == 0) {
            $data = Stock_Card::where('id_Stock_card', '=', $Id)->update(
                [
                    'removed' => 1,
                    'removed_date' => $dt
                ]
            );
            Stock_Supplier::where('fk_stock_card', '=', $Id)->delete();
            return redirect()->route('stockCards', ['id_house' => $homeid])->with('success', 'Resource card deactivated');
        } else {
            return redirect()->route('stockCards', ['id_house' => $homeid])->with('error', 'Resource card belongs to a resource');
        }
    }

    public function removeWarCard($Id)
    {
        //$title = "Warehouse place";
        $home = Warehouse_place::where('id_Warehouse_place', '=', $Id)->first();
        $homeid = $home->fk_Home;
        $warehouses = Warehouse_place::where('fk_Warehouse_place', '=', $Id)->where('removed', '=', 0)->get();
        $stoks = Stock::where('fk_Warehouse_place', '=', $Id)->get();
        $sto = count($stoks);
        $ware = count($warehouses);
        $dt = Carbon::now();
        $dt->toArray('Y-m-d');
        $s = $sto + $ware;
        if($s == 0) {
            $data = Warehouse_place::where('id_Warehouse_place', '=', $Id)->update(
                [
                    'removed' => 1,
                    'removed_date' => $dt
                ]
            );
            return redirect()->route('warehousePlaces', ['id_house' => $homeid])->with('success', 'Storage place deactivated');
        } else {
            if($sto != 0)
            {
                return redirect()->route('warehousePlaces', ['id_house' => $homeid])->with('error', 'Storage place belongs to stock');
            } else {
                return redirect()->route('warehousePlaces', ['id_house' => $homeid])->with('error', 'Storage place belongs to other storage');
            }
        }
    }

    public function addSupplierForStock(Request $request, $fk_stock_card)
    {
        $fk_supplier = $request->input('fk_supplier');
        if($fk_supplier != 0) {
            Stock_Supplier::Create([
                'fk_stock_card' => $fk_stock_card,
                'fk_supplier' => $request->input('fk_supplier')
            ]);
            return Redirect::back()->with('success', 'Supplier added for resource');
        } else {
            return Redirect::back()->with('error', 'No supplier selected');
        }
    }

    public function removeSupplierFromStock($fk_stock_card, $fk_suppliers)
    {
        Stock_Supplier::where('fk_stock_card', '=', $fk_stock_card)->where('fk_supplier', '=', $fk_suppliers)->delete();
        return Redirect::back()->with('success', 'Supplier removed from resource card');
    }

    public function manageType($Id, $title)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $types = Supplier_Type::all();
        $permission_edit = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 8)->where('fk_household_id', '=', $Id)->first();
        $permission_delete = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 9)->where('fk_household_id', '=', $Id)->first();
        if($title == 'Stock type')
        {
            $sto_type = Stock_Type::where('fk_household_id', '=', $Id)->get();
            $sc = count($sto_type);
            return view('ResourceManagement/manageType', compact('sto_type', 'permission_delete', 'permission_edit', 'sc', 'types', 'house', 'title', 'allmembers'));

        } elseif($title == 'Supplier type') {
            $sup_type = Supplier_Type::where('fk_household_id', '=', $Id)->get();
            $sc = count($sup_type);
            return view('ResourceManagement/manageType', compact('sup_type', 'sc', 'permission_delete', 'permission_edit', 'types', 'house', 'title', 'allmembers'));
        }
    }

    public function removeSupplierType($type_id)
    {
        $s = 0;
        $stocks = Supplier::where('fk_type_id', '=', $type_id)->where('removed', '=', 0)->get();
        $s = count($stocks);
        $stockas = Supplier::where('fk_type_id', '=', $type_id)->where('removed', '=', 1)->get();
        $st = count($stockas);
        if($st == 0) {
            if ($s == 0) {
                $data = Supplier_Type::where('type_id', '=', $type_id)->delete();
                return Redirect::back()->with('success', 'Supplier type deleted');
            } else {
                return Redirect::back()->with('error', 'This type belongs to supplier. So can not be deleted.');
            }
        } else {
            return Redirect::back()->with('error', 'This type belongs to deactivated supplier. So can not be deleted.');
        }
    }

    public function removeStockType($id_Stock_type)
    {
        $s = 0;
        $stocks = Stock_Card::where('fk_Stock_type', '=', $id_Stock_type)->where('removed', '=', 0)->get();
        $s = count($stocks);
        $stockas = Stock_Card::where('fk_Stock_type', '=', $id_Stock_type)->where('removed', '=', 1)->get();
        $st = count($stockas);
        if($st == 0) {
            if($s == 0) {
                $data = Stock_Type::where('id_Stock_type', '=', $id_Stock_type)->delete();
                return Redirect::back()->with('success', 'Resource type deleted');
            } else{
                return Redirect::back()->with('error', 'This type belongs to a resource. So can not be deleted.');
            }
        }
        else {
            return Redirect::back()->with('error', 'This type belongs to deactivated resource. So can not be deleted.');
        }
    }

    public function confirmEditStockType(Request $request, $Id)
    {
        $validator = Validator::make(
            [
                'Type_name' => $request->input('Type_name')
            ],
            [
                'Type_name' => 'required|min:3|max:30'
            ]
        );

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $data = Stock_Type::where('id_Stock_type', '=', $Id)->update(
                [
                    'Type_name' => $request->input('Type_name'),
                    'Type_description' => $request->input('Type_description')
                ]
            );
            $s = Stock_Type::where('id_Stock_type', '=', $Id)->first();
            $allmembers = Home_Member::all();
            $house = Home::where('id_Home', '=', $s->fk_household_id)->first();
            $types = Supplier_Type::all();
            $sup_type = Supplier_Type::where('fk_household_id', '=', $s->fk_household_id)->get();
            $sc = count($sup_type);
            $title = 'Stock type';
            $sto_type = Stock_Type::where('fk_household_id', '=', $Id)->get();
            $stype = Stock_Type::where('id_Stock_type', '=', $Id)->first();
            $sc = count($sto_type);
            //return redirect()->route('addCard', ['Id' => $s->fk_household_id, 'title' => $title])->with('success', 'Stock type information was edited');
            return redirect()->route('manageType', ['Id' => $s->fk_household_id, 'title' => $title])->with('success', 'Resource type information was edited');
        }
    }

    public function manageStockEditType($id_house, $Id)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home','=', $id_house)->first();
        $types = Supplier_Type::all();
        $sup_type = Supplier_Type::where('fk_household_id', '=', $id_house)->get();
        $sc = count($sup_type);
        $title = 'Stock type';
        $stype = Stock_Type::where('id_Stock_type', '=', $Id)->first();
        return view('ResourceManagement/editType', compact('sup_type', 'stype', 'sc', 'types', 'house', 'title', 'allmembers'));
    }

//sort stock
    public function SortStock(Request $request, $id_house)
    {
        $title='Stock card';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $types = Supplier_Type::all();
        $typ = 'null';
        $stock_types = Stock_Card::all();
        $onetypesupplier = Stock_Card::all();
        $search = null;
        $stypes = Stock_Type::where('fk_household_id', '=', $id_house)->get();
        $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get()->paginate(8);
        $as= count($allstocks);
        $typesc = count($onetypesupplier);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        switch ($_POST['orderBy']) {
            case 'newest':
                $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->orderBy('id_Stock_card', 'desc')->get();
                break;
            case 'asc':
                $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->orderBy('Name')->get();
                break;
            case '':
                $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
                break;
        }
        return view('ResourceManagement/cardlist', compact('house', 'title', 'stypes', 'allstocks', 'as', 'stock_types', 'search', 'permission_create', 'typesc', 'allmembers', 'types', 'typ'));
    }

    public function getTypeStock($id_house, $type)
    {
        $title='Stock card';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $search = null;
        $stypes = Stock_Type::where('fk_household_id', '=', $id_house)->get();
        $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
        if ($type == 'removed') {
            $onetypesupplier = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $as = count($onetypesupplier);
            $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $typ = 'removed';
        } elseif ($type) {
            $onetypesupplier = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->get();
            $typ = 'lowest';
        } else {
            $as = DB::table('stock_card')->count();
            $typ = 'null';
        }
        $types = Supplier_Type::all();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('ResourceManagement/cardlist', compact('house', 'search', 'stypes', 'title', 'permission_create', 'allstocks', 'as', 'allmembers', 'types', 'typ'));
    }

    public function SortFilteredStock($id_house, $type)
    {
        $title='Stock card';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->get();
        $types = Supplier_Type::all();
        $search = null;
        $stypes = Stock_Type::where('fk_household_id', '=', $id_house)->get();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        if ($type == 'removed') {
            $onetypesupplier = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $as = count($onetypesupplier);
            switch ($_POST['orderBy']) {
                case 'newest':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->orderBy('id_Stock_card', 'desc')->get();
                    break;
                case 'asc':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->orderBy('Name')->get();
                    break;
                case '':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
                    break;
            }
            $typ = 'removed';
        }
        elseif ($type != null) {
            $onetypesupplier = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            switch ($_POST['orderBy']) {
                case 'newest':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->orderBy('id_Warehouse_place', 'desc')->get();
                    break;
                case 'asc':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->orderBy('Warehouse_name')->get();
                    break;
                case '':
                    $allstocks = Stock_Card::where('fk_Home', '=', $id_house)->where('fk_Stock_type', '=', $type)->where('removed', '=', 0)->get();
                    break;
            }
            $typ = Stock_Type::where('id_Stock_type', '=', $type)->first();
        } else {
            $typ = "null";
        }
        return view('ResourceManagement/cardlist', compact('house', 'search', 'stypes', 'title', 'permission_create', 'allmembers', 'allstocks', 'as', 'types', 'typ'));
    }

    public function searchStock($Id, Request $request, $filter_id)
    {
        $title='Stock card';
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $search = $request->input('search');
        $stypes = Stock_Type::where('fk_household_id', '=', $Id)->get();
        if ($filter_id > 0) {
            $allstocks = Stock_Card::where('fk_Home', '=', $Id)->where('fk_Stock_type', '=', $filter_id)->where('removed', '=', 0)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = 'lowest';
        } elseif ($filter_id == 0) {
            $allstocks = Stock_Card::where('fk_Home', '=', $Id)->where('removed', '=', 1)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = 'removed';
        } else {
            $allstocks = Stock_Card::where('fk_Home', '=', $Id)->where('removed', '=', 0)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = "null";
        }

        $as = count($allstocks);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $Id)->first();
        return view('ResourceManagement/cardlist', compact('house', 'title', 'stypes', 'permission_create', 'allmembers', 'allstocks', 'as', 'types', 'typ', 'search'));
    }

    public function activateStock($id_house, $Id)
    {
        $data = Stock_Card::where('id_Stock_card', '=', $Id)->update(
            [
                'removed' => 0,
                'removed_date' => null
            ]
        );
        return redirect()->route('getTypeStock', ['id_house' => $id_house, 'type' => 'removed'])->with('success', 'Resource card activated');
    }
    public function deleteStock($type_id)
    {
        $data = Stock_Card::where('id_Stock_card', '=', $type_id)->delete();
        return Redirect::back()->with('success', 'Stock deleted');
    }
    public function deleteWare($type_id)
    {
        $data = Warehouse_place::where('id_Warehouse_place', '=', $type_id)->delete();
        return Redirect::back()->with('success', 'Warehouse deleted');
    }
}
