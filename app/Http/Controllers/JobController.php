<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Customer;
use App\Job;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{

    public function index(Request $request)
    {
        $jobs=Job::orderBy('id','desc');
        if($request->has('search') && $request->search!=""){
            $jobs=$jobs->where('customer_name','LIKE','%'.$request->search.'%')
                ->orwhere('customer_phone','LIKE','%'.$request->search.'%')
                ->orwhere('customer_mobile','LIKE','%'.$request->search.'%')
                ->orwhere('customer_postcode','LIKE','%'.$request->search.'%')
                ->orwhere('customer_email','LIKE','%'.$request->search.'%')
                ->orwhere('customer_address','LIKE','%'.$request->search.'%');
        }
        if($request->has('job_no') && $request->job_no!=""){
            $jobs=$jobs->where('job_number','LIKE','%'.$request->job_no.'%');
        }

        if($request->has('status') && $request->status!=""){
            $jobs=$jobs->where('status',$request->status);
        }

        $statuses=Status::all();
         $jobs=$jobs->paginate(20);
        return view('admin.jobs.index',compact('jobs','statuses'));
    }


    public function create()
    {

        return  view('admin.jobs.create');
    }


    public function store(Request $request)
    {

       $request->validate([
           "type"=>"required",
           "job_type"=>"required",
           "system_type"=>"required",
           "warranty_job"=>"required",
       ]);

       $job=new Job();
       $job->user_id=Auth::user()->id;
       $job->job_location=Session::get('location');
       $job->job_number=time();

        if($request->type=="new"){
            $customer=new Customer();

            $customer->name=$request->name;
            $customer->phone=$request->phone;
            $customer->post_code=$request->post_code;
            $customer->mobile=$request->mobile;
            $customer->email=$request->email;
            $customer->address=$request->address;

            $customer->user_id=Auth::user()->id;

            $customer->save();
            $job->customer_id=$customer->id;

            $job->customer_name=$request->name;
            $job->customer_phone=$request->phone;
            $job->customer_postcode=$request->post_code;
            $job->customer_mobile=$request->mobile;
            $job->customer_email=$request->email;
            $job->customer_address=$request->address;


        }else{
            $job->customer_id=$request->customer_id;

            $customer=Customer::findorfail($request->customer_id);

            $job->customer_name=$customer->name;
            $job->customer_phone=$customer->phone;
            $job->customer_postcode=$customer->post_code;
            $job->customer_mobile=$customer->mobile;
            $job->customer_email=$customer->email;
            $job->customer_address=$customer->address;
        }




        $job->job_type=$request->job_type;
        $job->system_type=$request->system_type;
        $job->warranty_job=$request->warranty_job;
        $job->est_labour_cost=$request->est_labour_cost;
        $job->est_parts_cost=$request->est_parts_cost;
        $job->items=json_encode($request->items);
        $job->description=$request->description;
        $job->additional_notes=$request->additional_notes;
        $job->status="Queued";
        if($job->save()){
            return back()->with('message','Job has been added successfully')->with('job_id',$job->id);
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }


    public function show(Job $job)
    {
        $statuses=Status::all();
        return view('admin.jobs.show',compact('job','statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        //
    }

    public function print_receipt($id){
        $job=Job::findorfail($id);
        return view('admin.jobs.receipt',compact(
            'job'
        ));
    }

    public function my_jobs(Request $request){
        $jobs=Job::orderBy('id','desc')->where('assigned_to',Auth::user()->id);

        if($request->has('search') && $request->search!=""){
            $jobs=$jobs->where(function ($query) use ($request) {
                $query-> where('customer_name', 'LIKE', '%' . $request->search . '%')
                    ->orwhere('customer_phone', 'LIKE', '%' . $request->search . '%')
                    ->orwhere('customer_mobile', 'LIKE', '%' . $request->search . '%')
                    ->orwhere('customer_postcode', 'LIKE', '%' . $request->search . '%')
                    ->orwhere('customer_email', 'LIKE', '%' . $request->search . '%')
                    ->orwhere('customer_address', 'LIKE', '%' . $request->search . '%');
            });
        }
        if($request->has('job_no') && $request->job_no!=""){
            $jobs=$jobs->where('job_number','LIKE','%'.$request->job_no.'%');
        }

        if($request->has('status') && $request->status!=""){
            $jobs=$jobs->where('status',$request->status);
        }

        $jobs=$jobs->paginate(20);

        $statuses=Status::all();


        return view('admin.jobs.my_jobs',compact('jobs','statuses'));
    }


    public function assign_job($id){
        $job=Job::findorfail($id);
        $job->assigned_to=Auth::user()->id;
        if($job->save()){
            return redirect(route('admin.job.my_jobs'))->with('message','Job Assigned Successfully');
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }

    function super_unique($array,$key)
    {
        $temp_array = [];
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] =& $v;
        }
        $array = array_values($temp_array);
        return $array;

    }

    public function search_user(Request $request){

        $return_arr=[];
        $search=$request->search;
        if($search!=""){
            $data_array=explode(' ',$search);

            foreach ($data_array as $item){
                $customers=Customer::where('name','LIKE','%'.$item.'%')
                    ->orWhere('phone','LIKE','%'.$item.'%')->orWhere('mobile','LIKE','%'.$item.'%')
                    ->orWhere('post_code','LIKE','%'.$item.'%')->get();

                foreach ($customers as $customer){
                    $return_arr[]=array('id'=>$customer->id,'text'=>$customer->name." (".$customer->phone.")-(".$customer->post_code.")-(".$customer->address.")");
                }
            }

            $return_arr=$this->super_unique($return_arr, 'id');


        }
        return response()->json(['status'=>true,'customers'=>$return_arr]);


    }


    public function add_parts(Request $request){
        $request->validate([
           "job_id"=>"required",
        ]);

        $job=Job::findorfail($request->job_id);
        $job->parts=json_encode($request->parts);
        if($job->save()){
            return back()->with('message','Parts Added Successfully');
        }else{
            return back()->with('hasError','Something went wrong');
        }

    }

    public function add_comment(Request $request){
        $request->validate([
           "job_id"=>"required",
           "comment"=>"required"
        ]);
        $comment=new Comment();
        $comment->user_id=Auth::user()->id;
        $comment->job_id=$request->job_id;
        $comment->comment=$request->comment;
        if($comment->save()){
            return back()->with('message','Comment Added');
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }

    public function update_status(Request $request){
        $request->validate([
           "job_id"=>"required",
           "status"=>"required"
        ]);
        $job=Job::findorfail($request->job_id);
        $job->status=$request->status;
        if($job->save()){
            return back()->with('message','Status Updated');
        }else{
            return back()->with('hasError','Something went wrong');
        }
    }


    public function ready_to_dispatch(Request $request){

        $request->validate([
           "job_id"=>"required"
        ]);

        $job=Job::findorfail($request->job_id);
        $job->customer_notes=$request->customer_notes;
        $job->labour_cost=$request->labour_cost;
        $job->reason=$request->reason;
        $job->status="Ready for Despatch";
        if($job->save()){
            return back()->with('message','Job is Ready For Despatch');
        }else{
            return back()->with('hasError','Something went wrong');

        }
    }

    public function despatch(Request $request){
        $request->validate([
           "job_id"=>"required",
           "receipt"=>"required",
           "email"=>"required",
           "password"=>"required"
        ]);

        $job=Job::findorfail($request->job_id);
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
             $user_email=Auth::user()->email;
        }else{
            $user_email=Auth::user()->username;
        }
        if($request->email=$user_email && Hash::check($request->password, Auth::user()->password)){

            if($request->receipt=="Yes"){
                $job->status="Despatched";
                $job->despatched_by=Auth::user()->id;
                $job->despatch_receipt=$request->receipt;
                $job->despatch_at=now();
                if($job->save()){
                    return back()->with('message','Job Despatched Successfully')->with('job_id',$job->id);
                }else{
                    return back()->with('hasError','Something went wrong');
                }

      }
            else{
            $job->customer_verify_option=$request->customer_verify_option;
            if($request->customer_verify_option=="Call Customer"){

                $job->customer_verify_phone=$request->customer_verify_phone;
                $job->satisfied=$request->satisfied;

                $job->status="Despatched";
                $job->despatched_by=Auth::user()->id;
                $job->despatch_receipt=$request->receipt;
                $job->despatch_at=now();

            }
            elseif($request->customer_verify_option=="Check ID"){

                $job->customer_id_method=$request->customer_id_method;
                $job->document_number=$request->document_number;
                $job->satisfied=$request->satisfied;


                $job->status="Despatched";
                $job->despatched_by=Auth::user()->id;
                $job->despatch_receipt=$request->receipt;
                $job->despatch_at=now();
            }

            elseif($request->customer_verify_option=="Override"){

                $job->reason_for_override=$request->reason_for_override;
                $job->satisfied=$request->satisfied;


                $job->status="Despatched";
                $job->despatched_by=Auth::user()->id;
                $job->despatch_receipt=$request->receipt;
                $job->despatch_at=now();
            }

                if($job->save()){
                    return back()->with('message','Job Despatched Successfully')->with('job_id',$job->id);
                }else{
                    return back()->with('hasError','Something went wrong');
                }
        }
        }else{
            return back()->with('hasError','Email or Password is incorrect');
        }
    }
}
