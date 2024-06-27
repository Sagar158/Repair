<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
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
   public function index(){
        $jobs=Job::count();
        $customers=Customer::count();

   	return view('admin.dashboard',compact('jobs','customers'));
   }
}
