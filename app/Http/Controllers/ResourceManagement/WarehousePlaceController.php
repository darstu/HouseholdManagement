<?php

namespace App\Http\Controllers\ResourceManagement;

use App\Http\Controllers\Controller;
use App\Models\HouseholdResource\Home;
use App\Models\ResourceManagement\Warehouse_place;
use App\Models\UserManagement\Home_Member;
use App\Models\UserManagement\Locked_Warehouses;
use App\Models\UserManagement\User_Permission;
use Illuminate\Http\Request;
use App\Models\HouseholdResource\Supplier_Type;
use Illuminate\Support\Facades\Auth;

class WarehousePlaceController extends Controller
{
    public function index($id_house)
    {
        $house = Home::where('id_Home', '=', $id_house)->first();
        $allmembers = Home_Member::all();
        $title='Warehouse place';
        $wares=Warehouse_place::all();
        $typ = null;
        $search = null;
        $warehousesfor = Warehouse_place::where('fk_Home', '=', $id_house)->get();
        $types = Supplier_Type::all();
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
        $as= count($allstocks);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('ResourceManagement/cardlist', compact('types', 'search', 'warehousesfor', 'typ', 'permission_create', 'allstocks', 'lockedwarehouse', 'as', 'wares', 'title', 'allmembers', 'house'));
    }

    public function createWarehouseCard($Id)
    {
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $title = 'Warehouse place';
        $allwares = Warehouse_place::where('fk_Home', '=', $Id)->get();
        $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('removed', '=', 0)->get();
        $as= count($allstocks);
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        return view('ResourceManagement/addCard', compact('allmembers', 'lockedwarehouse', 'as', 'allwares', 'types', 'house', 'title'));
    }

    public function SortWarehouse($id_house)
    {
        $title='Warehouse place';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $types = Supplier_Type::all();
        $typ = null;
        $wares=Warehouse_place::all();
        $onetypesupplier = Warehouse_place::all();
        $search = null;
        $warehousesfor = Warehouse_place::where('fk_Home', '=', $id_house)->get();
        $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
        $as= count($allstocks);
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        $typesc = count($onetypesupplier);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        switch ($_POST['orderBy']) {
            case 'newest':
                $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->orderBy('id_Warehouse_place', 'desc')->get();
                break;
            case 'asc':
                $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->orderBy('Warehouse_name')->get();
                break;
            case '':
                $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
                break;
        }
        return view('ResourceManagement/cardlist', compact('house', 'title', 'warehousesfor', 'allstocks', 'as', 'lockedwarehouse', 'wares', 'search', 'permission_create', 'typesc', 'allmembers', 'types', 'typ'));
    }

    public function getTypeWare($id_house, $type)
    {
        $title='Warehouse place';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $warehousesfor = Warehouse_place::where('fk_Home', '=', $id_house)->get();
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        $search = null;
        $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 0)->get();
        if ($type == 'removed') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $as = count($onetypesupplier);
            $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $typ = 'removed';
        } elseif ($type == 'highest') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->get();
            $typ = 'highest';
        } elseif ($type == 'lowest') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->get();
            $typ = 'lowest';
        } else {
            $as = DB::table('warehouse_place')->count();
            $typ = 'null';
        }
        $types = Supplier_Type::all();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('ResourceManagement/cardlist', compact('house', 'search', 'warehousesfor', 'lockedwarehouse', 'title', 'permission_create', 'allstocks', 'as', 'allmembers', 'types', 'typ'));
    }

    public function SortFilteredWarehouse($id_house, $type)
    {
        $title='Warehouse place';
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->get();
        $warehousesfor = Warehouse_place::where('fk_Home', '=', $id_house)->get();
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        $types = Supplier_Type::all();
        $search = null;
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        if ($type == 'removed') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
            $as = count($onetypesupplier);
            switch ($_POST['orderBy']) {
                case 'newest':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->orderBy('id_Warehouse_place', 'desc')->get();
                    break;
                case 'asc':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->orderBy('Warehouse_name')->get();
                    break;
                case '':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('removed', '=', 1)->get();
                    break;
            }
            $typ = 'removed';
        } elseif ($type == 'highest') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            switch ($_POST['orderBy']) {
                case 'newest':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->orderBy('id_Warehouse_place', 'desc')->get();
                    break;
                case 'asc':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->orderBy('Warehouse_name')->get();
                    break;
                case '':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->where('removed', '=', 0)->get();
                    break;
            }
            $typ = 'highest';
        }
        elseif ($type == 'lowest') {
            $onetypesupplier = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->get();
            $as = count($onetypesupplier);
            switch ($_POST['orderBy']) {
                case 'newest':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->orderBy('id_Warehouse_place', 'desc')->get();
                    break;
                case 'asc':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->orderBy('Warehouse_name')->get();
                    break;
                case '':
                    $allstocks = Warehouse_place::where('fk_Home', '=', $id_house)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->get();
                    break;
            }
            $typ = 'lowest';
        } else {
            $typ = null;
        }
        return view('ResourceManagement/cardlist', compact('house', 'search', 'warehousesfor', 'lockedwarehouse', 'title', 'permission_create', 'allmembers', 'allstocks', 'as', 'types', 'typ'));
    }

    public function searchWarehouse($Id, Request $request, $filter_id)
    {
        $title='Warehouse place';
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $lockedwarehouse = Locked_Warehouses::where('user_id', '=', Auth::user()->id)->get();
        $search = $request->input('search');
        $warehousesfor = Warehouse_place::where('fk_Home', '=', $Id)->get();
        if ($filter_id == -2) {
            $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('fk_Warehouse_place', '=', null)->where('removed', '=', 0)->where('Warehouse_name', 'LIKE', "%{$search}%")->get();

            $typ = 'highest';
        }
        elseif ($filter_id == -1) {
            $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('fk_Warehouse_place', '!=', null)->where('removed', '=', 0)->where('Warehouse_name', 'LIKE', "%{$search}%")->get();

            $typ = 'lowest';
        } elseif ($filter_id == -3) {
            $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('removed', '=', 1)->where('Warehouse_name', 'LIKE', "%{$search}%")->get();

            $typ = 'removed';
        } else {
            $allstocks = Warehouse_place::where('fk_Home', '=', $Id)->where('removed', '=', 0)->where('Warehouse_name', 'LIKE', "%{$search}%")->get();

            $typ = "null";
        }

        $as = count($allstocks);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $Id)->first();
        return view('ResourceManagement/cardlist', compact('house', 'lockedwarehouse', 'warehousesfor', 'title', 'permission_create', 'allmembers', 'allstocks', 'as', 'types', 'typ', 'search'));
    }

    public function activateWarehouse($id_house, $Id)
    {
        $data = Warehouse_place::where('id_Warehouse_place', '=', $Id)->update(
            [
                'removed' => 0,
                'removed_date' => null
            ]
        );
        return redirect()->route('getTypeWare', ['id_house' => $id_house, 'type' => 'removed'])->with('success', 'Storage activated');
    }

}
