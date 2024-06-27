@extends('admin.layouts.main')
@section('content')
    <!-- Zero configuration table -->
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">My Jobs </h4>
                    </div>
                    <div class="card-content">
                        <form action="{{route('admin.job.my_jobs')}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-success float-right mr-2">Search</button>
                            <input type="search" name="job_no" value="{{@request()->get('job_no')}}" class="form-control col-3 mr-2 float-right" placeholder="Search JOB Number">

                        </form>
                        <form action="{{route('admin.job.my_jobs')}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-success float-right mr-2">Search</button>
                            <input type="search" name="search" value="{{@request()->get('search')}}" class="form-control col-3 mr-2 float-right" placeholder="Search Customer">

                        </form>
                        <form action="{{route('admin.job.my_jobs')}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-success float-right mr-2">Search</button>
                            <select class="form-control col-3 mr-2 float-right" name="status">
                                <option value="">--select status--</option>
                                <option @if(@request()->get('status')=="Queued"){{'selected'}}@endif value="Queued">Queued</option>
                                <option @if(@request()->get('status')=="Waiting for parts"){{'selected'}}@endif value="Waiting for parts">Waiting for parts</option>
                                <option @if(@request()->get('status')=="Waiting for Customer Reply"){{'selected'}}@endif value="Waiting for Customer Reply">Waiting for Customer Reply</option>
                                <option @if(@request()->get('status')=="Ready for Despatch"){{'selected'}}@endif value="Ready for Despatch">Ready for Despatch</option>
                                <option @if(@request()->get('status')=="Despatched"){{'selected'}}@endif value="Despatched">Despatched</option>

                            </select>
                        </form>

                        <div class="card-body card-dashboard table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                <tr>
                                    <th>Job Number</th>
                                    <th>Customer Name</th>
                                    <th>Customer Mobile</th>
                                    <th>Customer PostCode</th>
                                    <th>Date Booked In</th>

                                    <th>System Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jobs as $job)
                                    <tr>
                                        <?php

                                        $color="";
                                        if ($job->status=="Queued"){
                                            $color="#f70521";
                                        }elseif ($job->status=="Waiting for parts"){
                                            $color="#ffb914";
                                        }
                                        elseif ($job->status=="Waiting for Customer Reply"){
                                            $color="##ff14dc";
                                        }
                                        elseif ($job->status=="Ready for Despatch"){
                                            $color="#1bed0c";
                                        }


                                        ?>


                                        <td>{{$job->job_number}}</td>
                                        <td>{{@$job->customer_name}}</td>
                                            <td>{{$job->customer_phone}}</td>
                                            <td>{{$job->customer_postcode}}</td>
                                        <td>{{date('d/m/Y',strtotime($job->created_at))}}</td>

                                        <td>{{$job->system_type}}</td>
                                        <td>{{$job->job_location}}</td>

                                            <td style="color:{{$color}}">{{@$job->status}}</td>
                                        <td>
                                            <a href="{{route('admin.job.show',$job->id)}}" target="_blank" class="btn btn-warning btn-sm">Job Details</a>

                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$jobs->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Zero configuration table -->
@endsection
@section('scripts')
    <script>

        function confirmDelete(id) {
            let choice=confirm("Are you sure, You want to delete Customer");
            if (choice){
                document.getElementById("delete-user-"+id).submit();
            }
        }
        $(document).ready( function () {
            $('#usertable').DataTable({
                "bPaginate":false
            });
        });
    </script>
@endsection
