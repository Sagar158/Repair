@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('update.profile')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section"> Edit Profile</h4>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" id="name" class="form-control" required name="name" value="{{Auth::user()->name}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="username">UserName <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" id="username" class="form-control" required name="username" value="{{Auth::user()->username}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger fa-lg">*</span></label>
                                            <input type="email" id="email" class="form-control" required name="email" value="{{Auth::user()->email}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="password">Old Password </label>
                                            <input type="password" id="password" min="6" class="form-control" name="old_password">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="password">New Password </label>
                                            <input type="password" id="password" min="6" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password </label>
                                            <input type="password" id="password_confirmation" min="6" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>




                                </div>

                            </div>

                            <div class="form-actions text-right">
                                <button type="submit" class="btn btn-raised btn-raised btn-primary">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')

@endsection
