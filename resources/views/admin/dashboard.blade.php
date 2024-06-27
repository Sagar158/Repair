@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class=" col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="px-3 py-3">
                        <div class="media">
                            <div class="media-body text-left">
                                <h3 class="mb-1 success">{{$jobs}}</h3>
                                <span>Jobs</span>
                            </div>
                            <div class="media-right align-self-center">
                                <i class="icon-list success font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="px-3 py-3">
                        <div class="media">
                            <div class="media-body text-left">
                                <h3 class="mb-1 primary">{{$customers}}</h3>
                                <span>Customers</span>
                            </div>
                            <div class="media-right align-self-center">
                                <i class="icon-user-follow primary font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
@endsection
