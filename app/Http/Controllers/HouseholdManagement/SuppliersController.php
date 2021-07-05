<?php

namespace App\Http\Controllers\HouseholdManagement;

use App\Http\Controllers\Controller;
use App\Models\HouseholdResource\Stock_Supplier;
use App\Models\HouseholdResource\Supplier;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\UserManagement\Home_Member;
use App\Models\UserManagement\User_Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\HouseholdResource\Home;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class SuppliersController extends Controller
{
    public function index($id_house)
    {
        $house = Home::where('id_Home', '=', $id_house)->first();
        $homesupp = Supplier::where('fk_household_id', '=', $id_house)->get();
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $typ = null;
        $typesc = count($homesupp);
        $search = null;
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('HouseholdManagement/SuppliersManagement/suppliers', compact('house', 'search', 'permission_create', 'typ', 'typesc', 'homesupp', 'allmembers', 'types'));
    }

    public function SortSuppliers(Request $request, $id_house)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $types = Supplier_Type::all();
        $typ = null;
        $onetypesupplier = Supplier::all();
        $search = null;
        $typesc = count($onetypesupplier);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        switch ($_POST['orderBy']) {
            case 'newest':
                $homesupp = Supplier::where('fk_household_id', '=', $id_house)->orderBy('supplier_id', 'desc')->get();
                break;
            case 'asc':
                $homesupp = Supplier::where('fk_household_id', '=', $id_house)->orderBy('Name')->get();
                break;
            case '':
                $homesupp = Supplier::where('fk_household_id', '=', $id_house)->get();
                break;
        }
        return view('HouseholdManagement/SuppliersManagement/suppliers', compact('house', 'search', 'permission_create', 'homesupp', 'typesc', 'allmembers', 'types', 'typ'));
    }

    public function getType($id_house, $type)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $search = null;
        $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 0)->get();
        if ($type == 'removed') {
            $onetypesupplier = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 1)->get();
            $typesc = count($onetypesupplier);
            $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 1)->get();
            $typ = 'removed';
        } elseif ($type) {
            $onetypesupplier = Supplier::where('fk_type_id', '=', $type)->where('removed', '=', 0)->get();
            $typesc = count($onetypesupplier);
            $typ = Supplier_Type::where('type_id', '=', $type)->first();
        } else {
            $typesc = DB::table('supplier_type')->count();
            $typ = null;
        }
        $types = Supplier_Type::all();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        return view('HouseholdManagement/SuppliersManagement/suppliers', compact('house', 'search', 'permission_create', 'homesupp', 'typesc', 'allmembers', 'types', 'typ'));
    }

    public function SortFilteredSuppliers($id_house, $type)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $homesupp = Supplier::where('fk_household_id', '=', $id_house)->get();
        $types = Supplier_Type::all();
        $search = null;
        $onetypesupplier = Supplier::where('fk_type_id', '=', $type)->get();
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $id_house)->first();
        $typesc = count($onetypesupplier);
        if ($type == 'removed') {
            switch ($_POST['orderBy']) {
                case 'newest':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 1)->orderBy('supplier_id', 'desc')->get();
                    break;
                case 'asc':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 1)->orderBy('Name')->get();
                    break;
                case '':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('removed', '=', 1)->get();
                    break;
            }
            $typ = 'removed';
        } elseif ($type != null) {
            switch ($_POST['orderBy']) {
                case 'newest':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('fk_type_id', '=', $type)->where('removed', '=', 0)->orderBy('supplier_id', 'desc')->get();
                    break;
                case 'asc':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('fk_type_id', '=', $type)->where('removed', '=', 0)->orderBy('Name')->get();
                    break;
                case '':
                    $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('fk_type_id', '=', $type)->where('removed', '=', 0)->get();
                    break;
            }
            $typ = Supplier_Type::where('type_id', '=', $type)->first();
        } else {
            $typ = null;
        }
        return view('HouseholdManagement/SuppliersManagement/suppliers', compact('house', 'search', 'permission_create', 'allmembers', 'homesupp', 'typesc', 'types', 'typ'));
    }

    public function createSupplier($Id)
    {
        $title = "Suppliers";
        $types = Supplier_Type::where('fk_household_id', '=', $Id)->get();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        return view('HouseholdManagement/SuppliersManagement/addSuppliers', compact('allmembers', 'title', 'types', 'house'));
    }

    public function createSupplierType($Id)
    {
        $title = "Supplier type";
        $types = Supplier_Type::where('fk_household_id', '=', $Id)->get();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        return view('HouseholdManagement/SuppliersManagement/addSuppliers', compact('allmembers', 'title', 'types', 'house'));
    }

    public function addSuppliers(Request $request, $Id, $title)
    {
        if ($title == 'Supplier type') {
            $validator = Validator::make(
                [
                    'Name' => $request->input('Name')
                ],
                [
                    'Name' => 'required|min:3|max:50'
                ]
            );
            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                Supplier_Type::Create([
                    'fk_household_id' => $Id,
                    'Name' => $request->input('Name'),
                    'Description' => $request->input('Description')
                ]);
                $title = 'Supplier type';
                return redirect()->route('manageType', ['Id' => $Id, 'title' => $title])->with('success', 'Supplier Type created');
            }
        } else {
            $validator = Validator::make(
                [
                    'fk_type_id' => $request->input('fk_type_id'),
                    'Name' => $request->input('Name'),
                    'Address' => $request->input('Address')
                ],
                [
                    'fk_type_id' => 'required',
                    'Name' => 'required|min:3|max:30',
                    'Address' => 'required|min:3|max:60'
                ]
            );
            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                Supplier::Create([
                    'fk_household_id' => $Id,
                    'fk_type_id' => $request->input('fk_type_id'),
                    'Name' => $request->input('Name'),
                    'Address' => $request->input('Address'),
                    'City' => $request->input('City'),
                    'Phone' => $request->input('Phone'),
                    'removed' => 0
                ]);

                return redirect()->route('suppliers', ['id_house' => $Id])->with('success', 'Supplier created');
            }
        }
    }

    public function confirmEditSupplier(Request $request, $Id)
    {
        $data = Supplier::where('supplier_id', '=', $Id)->update(
            [
                'fk_type_id' => $request->input('fk_type_id'),
                'Name' => $request->input('Name'),
                'Address' => $request->input('Address'),
                'City' => $request->input('City'),
                'Phone' => $request->input('Phone')
            ]
        );
        $s = Supplier::where('supplier_id', '=', $Id)->first();
        return redirect()->route('suppliers', ['id_house' => $s->fk_household_id])->with('success', 'Supplier information was edited');
    }

    public function manageSupplierEdit($id_house, $Id)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $homesupp = Supplier::where('fk_household_id', '=', $id_house)->where('supplier_id', '=', $Id)->first();
        $types = Supplier_Type::where('fk_household_id', '=', $id_house)->get();
        $nowtype = Supplier_Type::where('type_id', '=', $homesupp->fk_type_id)->first();
        return view('HouseholdManagement/SuppliersManagement/supplierEdit', compact('homesupp', 'house', 'nowtype', 'allmembers', 'types'));
    }

    public function manageSupplier($id_house, $Id)
    {
        $types = Supplier_Type::where('fk_household_id', '=', $id_house)->get();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $homesupp = Supplier::where('supplier_id', '=', $Id)->first();
        $nowtype = Supplier_Type::where('type_id', '=', $homesupp->fk_type_id)->first();

        $permission_edit = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 8)->where('fk_household_id', '=', $id_house)->first();
        $permission_delete = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 9)->where('fk_household_id', '=', $id_house)->first();
        return view('HouseholdManagement/SuppliersManagement/supplier', compact('homesupp', 'permission_edit', 'permission_delete', 'types', 'house', 'nowtype', 'allmembers'));
    }

    public function removeSupplier($Id)
    {
        $s = 0;
        $homesupplier = Supplier::where('supplier_id', '=', $Id)->first();
        $stocks = Stock_Supplier::where('fk_supplier', '=', $Id)->get();
        $s = count($stocks);
        $homeid = $homesupplier->fk_household_id;
        $dt = Carbon::now();
        $dt->toArray('Y-m-d');
        if ($s == 0) {
            $data = Supplier::where('supplier_id', '=', $Id)->update(
                [
                    'removed' => 1,
                    'removed_date' => $dt
                ]
            );
            return redirect()->route('suppliers', ['id_house' => $homeid])->with('success', 'Supplier removed');
        } else {
            return redirect()->route('suppliers', ['id_house' => $homeid])->with('error', 'There is stocks which has this supplier. Delete stocks before deleting supplier.');
        }
    }

    public function confirmEditSupplierType(Request $request, $Id)
    {
        $data = Supplier_Type::where('type_id', '=', $Id)->update(
            [
                'Name' => $request->input('Name'),
                'Description' => $request->input('Description')
            ]
        );
        $s = Supplier_Type::where('type_id', '=', $Id)->first();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $s->fk_household_id)->first();
        $types = Supplier_Type::all();
        $sup_type = Supplier_Type::where('fk_household_id', '=', $s->fk_household_id)->get();
        $sc = count($sup_type);
        $title = 'Supplier type';
        $stype = Supplier_Type::where('type_id', '=', $Id)->first();
        return redirect()->route('manageType', ['Id' => $s->fk_household_id, 'title' => $title])->with('success', 'Supplier type information was edited');
    }

    public function manageSupplierEditType($id_house, $Id)
    {
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $id_house)->first();
        $types = Supplier_Type::all();
        $title = 'Supplier type';
        $stype = Supplier_Type::where('type_id', '=', $Id)->first();
        return view('ResourceManagement/editType', compact('stype', 'types', 'house', 'title', 'allmembers'));
    }

    public function searchSupplier($Id, Request $request, $filter_id)
    {
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $house = Home::where('id_Home', '=', $Id)->first();
        $search = $request->input('search');
        if ($filter_id > 0) {
            $homesupp = Supplier::where('fk_household_id', '=', $Id)->where('removed', '=', 0)->where('fk_type_id', '=', $filter_id)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = Supplier_Type::where('type_id', '=', $filter_id)->first();
        } elseif ($filter_id == 0) {
            $homesupp = Supplier::where('fk_household_id', '=', $Id)->where('removed', '=', 1)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = 'removed';
        } else {
            $homesupp = Supplier::where('fk_household_id', '=', $Id)->where('removed', '=', 0)->where('Name', 'LIKE', "%{$search}%")->get();

            $typ = null;
        }

        $typesc = count($homesupp);
        $permission_create = User_Permission::where('fk_user_id', '=', Auth::user()->id)->where('fk_permission_id', '=', 7)->where('fk_household_id', '=', $Id)->first();
        return view('HouseholdManagement/SuppliersManagement/suppliers', compact('house', 'permission_create', 'allmembers', 'homesupp', 'typesc', 'types', 'typ', 'search'));
    }

    public function activateSupplier($id_house, $Id)
    {
            $data = Supplier::where('supplier_id', '=', $Id)->update(
                [
                    'removed' => 0,
                    'removed_date' => null
                ]
            );
            return redirect()->route('getType', ['id_house' => $id_house, 'type' => 'removed'])->with('success', 'Supplier activated');
    }

    public function deleteSupplier($type_id)
    {
        $data = Supplier::where('supplier_id', '=', $type_id)->delete();
        return Redirect::back()->with('success', 'Supplier deleted');
    }
}
