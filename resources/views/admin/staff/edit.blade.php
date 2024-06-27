@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('admin.staff.update',$user->id)}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <h4 class="form-section">Edit Staff</h4>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" id="name" class="form-control" value="{{$user->name}}" required name="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>UserName <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255"  class="form-control" value="{{$user->username}}" required name="username">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger fa-lg">*</span></label>
                                            <input type="email" id="email" class="form-control" value="{{$user->email}}" required name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6"></div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password">Password </label>
                                            <input type="password" id="password" min="6" class="form-control" name="password" >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password </label>
                                            <input type="password" id="password_confirmation" min="6" class="form-control" name="password_confirmation" >
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
    <script>

    </script>
@endsection
