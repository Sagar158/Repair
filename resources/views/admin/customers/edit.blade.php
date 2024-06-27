@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('admin.customer.update',$customer->id)}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <h4 class="form-section"> Edit Customer</h4>
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" id="name" value="{{$customer->name}}" class="form-control" required name="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Mobile <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" value="{{$customer->phone}}" class="form-control" required name="phone">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Telephone</label>
                                            <input type="text" maxlength="255" value="{{$customer->mobile}}" class="form-control" name="mobile">
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Post Code <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" value="{{$customer->post_code}}" class="form-control" required name="post_code">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Customer Type <span class="text-danger fa-lg">*</span></label>
                                            <select class="form-control" required name="customer_type">
                                                <option value="Retail" @if($customer->customer_type=="Retail"){{'selected'}}@endif>Retail</option>
                                                <option value="Corporate" @if($customer->customer_type=="Corporate"){{'selected'}}@endif>Corporate</option>
                                                <option value="Trade" @if($customer->customer_type=="Trade"){{'selected'}}@endif>Trade</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Email </label>
                                            <input type="email" maxlength="255" value="{{$customer->email}}" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label >Address</label>
                                            <textarea class="form-control" rows="2" name="address">{{$customer->address}}</textarea>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label >Notes</label>
                                            <textarea class="form-control" rows="2" name="notes">{{$customer->notes}}</textarea>

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
