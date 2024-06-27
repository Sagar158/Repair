<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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


        $customers=Customer::orderBy('id','desc');
        if($request->has('search') && $request->search!=""){
            $customers=$customers->where('name','LIKE','%'.$request->search.'%')->orWhere('phone','LIKE','%'.$request->search.'%');
        }
        $customers=$customers->paginate(20);
        return  view('admin.customers.index',compact('customers'));
    }


    public function create()
    {
        return view('admin.customers.create');
    }


    public function store(Request $request)
    {
       $request->validate([
          "name"=>"required",
          "phone"=>"required",
          "post_code"=>"required",
           "customer_type"=>"required",
       ]);

       $customer=Customer::create($request->all());
       if($customer){
           return back()->with('message','Customer Added Successfully');
       }else{
           return back()->with('hasError','Something went wrong');
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }


    public function edit(Customer $customer)
    {
       return view('admin.customers.edit',compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            "name"=>"required",
            "phone"=>"required",
            "post_code"=>"required",
            "customer_type"=>"required",
        ]);

        $customer->name=$request->name;
        $customer->phone=$request->phone;
        $customer->post_code=$request->post_code;
        $customer->customer_type=$request->customer_type;
        $customer->mobile=$request->mobile;
        $customer->email=$request->email;
        $customer->address=$request->address;
        $customer->notes=$request->notes;

        if($customer->save()){
            return back()->with('message','Customer Updated Successfully');
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }


    public function destroy(Customer $customer)
    {
        if($customer->delete()){
            return  back()->with('message','Customer Deleted Successfully');
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }
}
