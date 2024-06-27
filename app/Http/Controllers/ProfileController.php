<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile(){
        return view('admin.profile');
    }

    public function update_profile(Request  $request){
        $request->validate([
            "name"=>"required",
            "email"=>"required|email|unique:users,email,".Auth::user()->id,
            "username"=>"required|unique:users,username,".Auth::user()->id,
        ]);

        $user=Auth::user();
        $user->name=$request->name;
        $user->username=$request->username;
        $user->email=$request->email;
        if($request->has('password') && $request->password!=""){
            $request->validate([
                "old_password"=>"required",
                "password"=>"required|min:6|confirmed"
            ]);

            if(Hash::check($request->old_password,$user->password)){
                $user->password=Hash::make($request->password);
            }else{
                return back()->with('hasError','Password is Invalid');
            }


        }

        if($user->save()){
            return back()->with('message','Profile Updated');
        }else{
            return back()->with('hasError','Error');
        }
    }
}
