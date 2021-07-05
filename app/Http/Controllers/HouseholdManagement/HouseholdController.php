<?php


namespace App\Http\Controllers\HouseholdManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\addHousehold;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\ResourceManagement\Warehouse_place;
use App\Models\UserManagement\Locked_Warehouses;
use App\Models\UserManagement\Permissions;
use App\Models\UserManagement\User_Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\HouseholdResource\Home;
use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\Home_Member;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HouseholdController extends Controller
{
    public function index($id_house)
    {
        $owner = User::all();
        $house = Home::where('id_Home', $id_house)->first();
        $types = Supplier_Type::all();
        $allmembers = Home_Member::all();
        $memc = Home_Member::where('household_id', '=', $id_house)->count();
        return view('HouseholdManagement/household', compact('house', 'memc', 'types', 'owner', 'allmembers'));
    }

    public function createHousehold()
    {
        return view('HouseholdManagement/addHousehold');
    }

    public function addHousehold(addHousehold $request)
    {
        $dt = Carbon::now();
        $dt->toArray('Y-m-d');
        Home::Create([
            'Name' => $request->input('Name'),
            'Address' => $request->input('Address'),
            'Phone' => $request->input('Phone'),
            'Alternative_address' => $request->input('Alternative_address'),
            'City' => $request->input('City'),
            'removed' => 0,
            'created_at' => $dt
        ]);

        $this->addOwnerAsMember();

        return redirect()->route('home')->with('success','Household created');
    }

    public function addOwnerAsMember()
    {
        $homes = Home::all()->last();
        Home_Member::Create([
            'users_id' => Auth::user()->id,
            'household_id' => $homes->id_Home,
            'owner' => 1
        ]);
        User_Permission::Create([
            'fk_user_id' => Auth::user()->id,
            'fk_permission_id' => 7,
            'restricted' => 0,
            'fk_household_id' => $homes->id_Home
        ]);
        User_Permission::Create([
            'fk_user_id' => Auth::user()->id,
            'fk_permission_id' => 8,
            'restricted' => 0,
            'fk_household_id' => $homes->id_Home
        ]);
        User_Permission::Create([
            'fk_user_id' => Auth::user()->id,
            'fk_permission_id' => 9,
            'restricted' => 0,
            'fk_household_id' => $homes->id_Home
        ]);
    }

    public function confirmEditHousehold(Request $request, $Id)
    {
        $validator = Validator::make(
            [
                'Name' => $request->input('Name'),
                'Address' => $request->input('Address'),
                'Phone' => $request->input('Phone'),
                'Alternative_address' => $request->input('Alternative_address'),
                'City' => $request->input('City')
            ],
            [
                'Name' => 'required|min:3|max:30',
                'Address' => 'required|min:3|max:50',
                'City' => 'required|min:3|max:20',
            ]
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }
        else
        {
            $data = Home::where('id_Home', '=', $Id)->update(
                [
                    'Name' => $request->input('Name'),
                    'Address' => $request->input('Address'),
                    'Phone' => $request->input('Phone'),
                    'Alternative_address' => $request->input('Alternative_address'),
                    'City' => $request->input('City')
                ]
            );
        }
        return Redirect::back()->with('success', 'Household information was edited');
    }

    public function manageHousehold($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $house = Home::where('id_Home','=', $Id)->first();
        return view('HouseholdManagement/manageHousehold', compact('house','allmembers', 'types'));
    }

    public function removeHousehold($Id)
    {
        $dt = Carbon::now();
        $dt->toArray('Y-m-d');
        $data = Home::where('id_Home', '=', $Id)->update(
            [
                'removed' => 1,
                'removed_date' => $dt
            ]
        );
        return Redirect::to('/home')->with('Household removed');
    }

    //manage users
    public function manageHouseholdMembers($Id)
    {
        $types = Supplier_Type::all();
        $house = Home::where('id_Home','=', $Id)->first();
        $allmembers = Home_Member::where('household_id', '=', $Id)->get();
        $countmembers = count($allmembers);
        $users = User::all();
        $user_perm = User_Permission::where('fk_household_id', '=', $Id)->get();
        return view('HouseholdManagement/manageHouseholdMembers', compact('house', 'countmembers', 'user_perm', 'types', 'users', 'allmembers'));
    }

    public function manageMembersEdit($Id, $user)
    {
        $types = Supplier_Type::all();
        $house = Home::where('id_Home','=', $Id)->first();
        $allmembers = Home_Member::where('household_id', '=', $Id)->get();
        $userinfo = User::where('id', '=', $user)->first();
        $user_perm = User_Permission::where('fk_household_id', '=', $Id)->get();
        $perms = Permissions::all();
        $locked = Locked_Warehouses::where('household_id', '=', $Id)->where('user_id', '=', $user)->get();
        $warehouses = Warehouse_place::where('fk_Home', '=', $Id)->get();
        return view('HouseholdManagement/membersEdit', compact('house', 'warehouses', 'locked', 'perms', 'user_perm', 'types', 'allmembers', 'userinfo'));
    }

    public function confirmAddPermission($Id, $user, $permission)
    {
        $data = User_Permission::where('fk_household_id', '=', $Id)->where('fk_user_id', '=', $user)->where('fk_permission_id', '=', $permission)->update(
            [
                'restricted' => 0
            ]
        );
        return Redirect::back()->with('success', 'Member permission was edited');
    }
    public function confirmRemovePermission($Id, $user, $permission)
    {
        $data = User_Permission::where('fk_household_id', '=', $Id)->where('fk_user_id', '=', $user)->where('fk_permission_id', '=', $permission)->update(
            [
                'restricted' => 1
            ]
        );
        return Redirect::back()->with('success', 'Member permission was edited');
    }

    public function unlockWarehouse($Id, $user, $warehouse)
    {
        Locked_Warehouses::where('household_id', '=', $Id)->where('user_id', '=', $user)->where('warehouse_id', '=', $warehouse)->delete();
        return Redirect::back()->with('success', 'Warehouse unlocked');
    }

    public function lockWarehouse(Request $request, $Id, $user)
    {
        $warehouseplace = $request->input('warehouse_id');
        if($warehouseplace != 0) {
            Locked_Warehouses::Create([
                'household_id' => $Id,
                'user_id' => $user,
                'warehouse_id' => $request->input('warehouse_id')
            ]);
            return Redirect::back()->with('success', 'Warehouse place was locked');
        } else {
            return Redirect::back()->with('error', 'No place selected');
        }
    }

    public function removeMember($Id, $user)
    {
        Home_Member::where('users_id', '=', $user)->where('household_id', '=', $Id)->delete();
        User_Permission::where('fk_user_id', '=', $user)->where('fk_household_id', '=', $Id)->delete();
        return redirect()->route('manageHouseholdMembers', ['Id' => $Id])->with('success', 'Member removed from household');
    }

    public function activateHousehold($Id)
    {
        $data = Home::where('id_Home', '=', $Id)->update(
            [
                'removed' => 0,
                'removed_date' => null
            ]
        );
        return Redirect::to('/home')->with('success', 'Household activated');
    }
}
