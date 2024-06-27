<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->role!=0){
                abort(404);
            }
            return $next($request);
        });


    }

    public function index(Request $request)
    {
        $users=User::where('role',1);
        if($request->has('search')){
            $users=$users-> where(function ($query) use ($request) {
                $query->where('name','LIKE','%'.$request->search.'%')->orWhere('username','LIKE','%'.$request->search.'%')->orWhere('email','LIKE','%'.$request->search.'%');
            });
        }

        $users=$users->orderBy('id','desc')->paginate(20);
        return view('admin.staff.index',compact('users'));
    }


    public function create()
    {
        return view('admin.staff.create');
    }


    public function store(Request $request)
    {
       $request->validate([
          "name"=>"required",
           "username"=>"required|unique:users,username",
          "email"=>"required|email|unique:users,email",
          "password"=>"required|min:6|confirmed"
       ]);

       $user=new User();
       $user->name=$request->name;
        $user->username=$request->username;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->role=1;
        if($user->save()){
            return back()->with('message','Staff Member Added');
        }else{
            return  back()->with('hasError','Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
       $user=User::findorfail($id);
       return  view('admin.staff.edit',compact('user'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            "name"=>"required",
            "username"=>"required|unique:users,username,".$id,
            "email"=>"required|email|unique:users,email,".$id,

        ]);

      $user=User::findorfail($id);
        $user->name=$request->name;
        $user->username=$request->username;
        $user->email=$request->email;
        if($request->has('password') && $request->password!=""){
            $request->validate([
                "password"=>"required|min:6|confirmed"
            ]);
            $user->password=Hash::make($request->password);
        }

        if($user->save()){
            return back()->with('message','Staff Member Updated');
        }else{
            return  back()->with('hasError','Something went wrong');
        }
    }


    public function destroy($id)
    {
       $user=User::findorfail($id);
       if($user->delete()){
           return back()->with('message','Staff Member Deleted');
       }else{
           return  back()->with('hasError','Something went wrong');
       }
    }

    public function update_status($id,$status){
        $user=User::find($id);
        $user->status=$status;
        if($user->save()){
            return back()->with('message','Staff Member Status Updated');
        }else{
            return back()->with('hasError','Error Occurred');
        }
    }
}
