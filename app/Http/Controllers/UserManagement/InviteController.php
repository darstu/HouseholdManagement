<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Supplier_Type;
use App\Models\User;
use App\Models\UserManagement\Home_Member;
use App\Models\UserManagement\Invite;
use App\Models\UserManagement\User_Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\createInvite;
use Illuminate\Support\Facades\DB;

class InviteController extends Controller
{
    public function index()
    {
        $homes = Home::all();
        $owner = User::all();
        $invites = Invite::all();
        $allmembers = Home_Member::all();
        return view('UserManagement/invites', compact('invites', 'owner', 'homes', 'allmembers'));
    }

    public function inviteMember($Id)
    {
        $allmembers = Home_Member::all();
        $types = Supplier_Type::all();
        $allusers = User::where('removed', '=', 0)->get();
        $rows = [];
        foreach($allusers as $user)
        {
            if($user->surname == null)
            {
                $rows[] = $user->name . " " . $user->id;
            }
            else
                {
                $rows[] = $user->name . " " . $user->surname . " " . $user->id;
            }
        }
        $house = Home::where('id_Home','=', $Id)->first();
        return view('UserManagement/createInvite', compact('house', 'rows', 'allmembers', 'types'));
    }

    public function createInvite(createInvite $request)
    {
        function strrevpos($instr, $needle)
        {
            $rev_pos = strpos (strrev($instr), strrev($needle));
            if ($rev_pos===false) return false;
            else return strlen($instr) - $rev_pos - strlen($needle);
        }
        function after_last($thisa, $inthata)
        {
            if (!is_bool(strrevpos($inthata, $thisa)))
            {
                return substr($inthata, strrevpos($inthata, $thisa)+strlen($thisa));
            }
        }
        $count=0;
        $allinvites = Invite::all();
        $allmembers = Home_Member::all();
        $receiver_id = after_last(' ', $request->input('fk_receiver_id'));
        $nonexist = User::where('id', '=', $receiver_id)->get();
        $n = count($nonexist);
        if($n == 0)
        {
            return Redirect::back()->with('error', 'User does not exist');
        }
        foreach ($allmembers as $mem)
        {
            if ($mem->users_id == $receiver_id and $mem->household_id == $request->input('fk_Home')) {
                $count = $count + 1;
            }
        }
        foreach ($allinvites as $inv) {
            if ($inv->receiver_id == $receiver_id and $inv->fk_household_id == $request->input('fk_Home')) {
                $count = $count + 1;
            } elseif ($receiver_id == Auth::user()->id) {
                $count = $count + 1;
            }
        }
        if($count == 0)
        {
            Invite::Create([
                'fk_household_id' => $request->input('fk_Home'),
                'fk_sender_id' => Auth::user()->id,
                'fk_receiver_id' => $receiver_id,
                'Message' => $request->input('Message')
            ]);
            return Redirect::back()->with('success', 'Invite sent');
        }
        else
        {
            return Redirect::back()->with('error', 'User already belongs to this household');
        }
    }

    public function deleteInvite($Id, $call)
    {
        if ($call == 'accept')
        {
            $invite = Invite::where('invite_id', '=', $Id)->first();
            Home_Member::Create([
                'users_id' => Auth::user()->id,
                'household_id' => $invite->fk_household_id,
                'owner' => 0
            ]);
            User_Permission::Create([
                'fk_user_id' => Auth::user()->id,
                'fk_permission_id' => 7,
                'restricted' => 0,
                'fk_household_id' => $invite->fk_household_id
            ]);
            User_Permission::Create([
                'fk_user_id' => Auth::user()->id,
                'fk_permission_id' => 8,
                'restricted' => 0,
                'fk_household_id' => $invite->fk_household_id
            ]);
            User_Permission::Create([
                'fk_user_id' => Auth::user()->id,
                'fk_permission_id' => 9,
                'restricted' => 0,
                'fk_household_id' => $invite->fk_household_id
            ]);
            Invite::where('invite_id', '=', $Id)->delete();
            return Redirect::to('/invites')->with('success','You were added to household');
        }
        elseif ($call == 'delete')
        {
            Invite::where('invite_id', '=', $Id)->delete();
            return Redirect::to('/invites');
        }
    }
}
