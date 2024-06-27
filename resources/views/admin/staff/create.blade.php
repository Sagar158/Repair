@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('admin.staff.store')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section"> Add New Staff</h4>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" id="name" class="form-control" required name="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>UserName <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255"  class="form-control" required name="username">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger fa-lg">*</span></label>
                                            <input type="email" maxlength="255" id="email" class="form-control" required name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6"></div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password">Password <span class="text-danger fa-lg">*</span></label>
                                            <input type="password" maxlength="255" id="password" min="6" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password <span class="text-danger fa-lg">*</span></label>
                                            <input type="password" maxlength="255" id="password_confirmation" min="6" class="form-control" name="password_confirmation" required>
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
