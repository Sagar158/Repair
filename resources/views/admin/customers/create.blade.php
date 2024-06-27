@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('admin.customer.store')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section"> Add New Customer</h4>
                                <div class="row">
                                     <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" id="name" class="form-control" required name="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Mobile<span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" class="form-control" required name="phone">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Telephone</label>
                                            <input type="text" maxlength="255" class="form-control" name="mobile">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Post Code <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" class="form-control" required name="post_code">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Customer Type <span class="text-danger fa-lg">*</span></label>
                                            <select class="form-control" required name="customer_type">
                                                 <option value="Retail">Retail</option>
                                                <option value="Corporate">Corporate</option>
                                                <option value="Trade">Trade</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Email </label>
                                            <input type="email" maxlength="255" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label >Address</label>
                                            <textarea class="form-control" rows="2" name="address"></textarea>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label >Notes</label>
                                            <textarea class="form-control" rows="2" name="notes"></textarea>

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
