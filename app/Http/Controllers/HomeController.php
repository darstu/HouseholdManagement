<?php

namespace App\Http\Controllers;

use App\Models\HouseholdResource\Removed_Home;
use App\Models\UserManagement\Home_Member;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HouseholdResource\Home;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list = Home_Member::where('users_id', Auth::id())->get('household_id')->toArray();
        if(sizeof($list)>1){
            $households=Home::whereIn('id_Home',$list)->get();
            session(['Households'=>$households]);
            session(['MultipleHouseholds'=>true]);
        }elseif (sizeof($list)==1){
            $household=Home::whereIn('id_Home',$list)->first();
            session(['houseID'=>$household->id_Home]);
            session(['MultipleHouseholds'=>false]);
        }
        else{
            session(['MultipleHouseholds'=>false]);
            session(['houseID'=>0]);
        }
        $member = Home_Member::where('users_id', '=', Auth::user()->id)->get();
        $owner = User::all();
        $houses = Home::all();
        $allmembers = Home_Member::all();
        $count = count($member);
        $house_count = 0;
        foreach ($member as $m) {
            foreach ($houses as $house) {
                if ($m->household_id == $house->id_Home and $house->removed == 0) {
                    $house_count = $house_count + 1;
                }
            }
        }
        return view('home', compact('houses', 'house_count', 'owner', 'member', 'allmembers', 'count'));
    }
}
