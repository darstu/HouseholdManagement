<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\HouseholdResource\Home;
use App\Models\User;
use App\Models\UserManagement\Home_Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class AccountController extends Controller
{
    public function index()
    {
        $Id = Auth::id();
        $user = User::where('id','=', $Id)->first();
        $houses = Home::all();
        $member = Home_Member::where('users_id', '=', Auth::user()->id)->where('owner', '=', 1)->get();
        return view('UserManagement/account', compact('user', 'member', 'houses'));
    }

    public function confirmEditAccount(Request $request, $Id)
    {
        $validator = Validator::make(
            [
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'email' => $request->input('email'),
                //'password' => $request->input('password'),
                //'password2' => $request->input('password_confirmation')
            ],
            [
                'name' => 'required',
                'surname' => 'max:50',
                'email' => 'required|email|max:30',
                //'password' => 'min:8',
                //'password2' => 'min:8'
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            if($request->input('password')!=null || $request->input('password_confirmation') !=null) {
                $this->validate($request, [
                    'old_password' => 'required'
                ]);
                $user = User::where('id', '=', $Id)->first();
                if (Hash::check($request->input('old_password'), $user->password)) {
                    $slapt = $request->input('password');
                    $slapt1 = $request->input('password_confirmation');
                    if ($slapt == $slapt1) {
                        $data = User::where('id', '=', $Id)->update([
                            'name' => $request->input('name'),
                            'surname' => $request->input('surname'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password'))
                        ]);
                        return Redirect::back()->with('success', 'Information and password changed');
                    } else {
                        return Redirect::back()->withErrors('Passwords do not match');
                    }
                }
                else {
                    return Redirect::back()->withErrors('Old password do not match');
                }
            }
            else{
                $data = User::where('id', '=', $Id)->update([
                    'name'=>$request->input('name'),
                    'surname' => $request->input('surname'),
                    'email' =>$request->input('email')
                ]);
                return Redirect::back()->with('success', 'Information changed');
            }
        }
    }

    public function removeAccount($Id)
    {
        $data = User::where('id', '=', $Id)->update(
            [
                'removed' => 1
            ]
        );
        Auth::logout();
        return redirect()->route('/');
    }

}
