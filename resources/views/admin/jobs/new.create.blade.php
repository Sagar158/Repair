@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-content">
                    <div class="px-3">
                        <form class="form" action="{{route('admin.job.store')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section"> Add New Job</h4>



                               <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-check form-check-inline">
                                           <input class="form-check-input" checked type="radio" name="type" id="inlineRadio1" value="existing">
                                           <label class="form-check-label" for="inlineRadio1">Existing Customer</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                           <input class="form-check-input" type="radio" name="type" id="inlineRadio2" value="new">
                                           <label class="form-check-label" for="inlineRadio2">New Customer</label>
                                       </div>
                                   </div>



                               </div>

                                <div class="row mt-2" id="filter_row">
                                    <div class="col-sm-6">
                                        <label>Select Customer <span class="text-danger fa-lg">*</span></label>
                                        <select class="form-control" required name="customer_id" id="customer_id">
                                        </select>
                                    </div>
                                </div>


                                <div class="row mt-2" id="customer_row" style="display: none">
                                    <div class="col-sm-2">
                                        <label>Name <span class="text-danger fa-lg">*</span></label>
                                        <input type="text" maxlength="255" class="form-control" autocomplete="off" name="name" id="name">
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label >Mobile <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" id="phone" class="form-control" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label >Telephone</label>
                                            <input type="text" maxlength="255" id="mobile" class="form-control" name="mobile">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label >Post Code <span class="text-danger fa-lg">*</span></label>
                                            <input type="text" maxlength="255" id="post_code" class="form-control" name="post_code">
                                        </div>
                                    </div>



                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Email </label>
                                            <input type="email" maxlength="255" id="email" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Address</label>
                                            <input type="text" name="address" class="form-control" id="address">
                                        </div>
                                    </div>


                                </div>
                                <h5>Job Details</h5>

                                <div class="row">
                                    <div class="col-sm-2">
                                        <label>Job Type <span class="text-danger fa-lg">*</span></label>
                                        <select class="form-control" required name="job_type">
                                            <option value="">--select--</option>
                                            <option value="Repair">Repair</option>
                                            <option value="Upgrade">Upgrade</option>
                                            <option value="Both">Both</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>System Type <span class="text-danger fa-lg">*</span></label>
                                        <select class="form-control" required name="system_type">
                                            <option value="">--select--</option>
                                            <option value="PC">PC</option>
                                            <option value="Laptop">Laptop</option>
                                            <option value="Mobile Phone">Mobile Phone</option>
                                            <option value="Printer">Printer</option>
                                            <option value="Monitor">Monitor</option>
                                            <option value="PC Component">PC Component</option>


                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Warranty Job <span class="text-danger fa-lg">*</span></label>
                                        <select class="form-control" required name="warranty_job">
                                            <option value="">--select--</option>
                                            <option value="Warranty">Warranty</option>
                                            <option value="Not In Warranty">Not In Warranty</option>
                                            <option value="Partial Warranty">Partial Warranty</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label >Estimated Labour Cost</label>
                                              <div class="input-group mb-3">
                                                  <input min="0" type="number" step="0.01"  value="0.00" maxlength="255"  class="form-control" name="est_labour_cost">
                                                  <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon3">£</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label >Estimated Parts Cost</label>
                                            <div class="input-group mb-3">
                                                <input min="0" type="number" step="0.01" value="0.00" maxlength="255"  class="form-control" name="est_parts_cost">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">£</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label >Job Description</label>
                                               <textarea class="form-control" rows="4" name="description" id="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label >Additional Notes</label>
                                            <textarea class="form-control" rows="4" name="additional_notes" id="additional_notes"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <h6>Items Left By Customer</h6>
                                        <div class="repeater2" style="margin-left: 10px;">

                                            <div data-repeater-list="items" class="ml-2">
                                                <div data-repeater-item class="mt-2 row">

                                                    <input type="text" onfocus="(this.type='number')" onblur="(this.type='text')" min="0" step="1" placeholder="Qty" class="form-control required col-1 mr-1 qty" value="1" name="qty" required>
                                                    <input type="text" placeholder="Item" class="form-control required col-3 mr-1" id="item" name="item" required>
                                                    <input type="text" placeholder="Identifier" class="form-control required col-3 mr-1" id="identifier" name="identifier" required>
                                                    <input type="text" placeholder="Item Notes" class="form-control required col-3 mr-1" id="notes" name="notes">
                                                    <i data-repeater-delete class="fa fa-trash text-danger fa-lg" style="font-size: 38px"></i>
                                                </div>
                                            </div>
                                            <input data-repeater-create type="button" class="btn btn-success mt-2" value="Add More"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-raised btn-raised btn-primary">
                                        <i class="fa fa-check-square-o"></i> Save
                                    </button>
                                </div>

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

    @if (session()->has('message'))
    window.open('{{route('admin.job.print',session('job_id'))}}','name','width=1000,height=700');
    @endif

    let name=$('#name');
    let phone=$('#phone');
    let email=$('#email');
    let post_code=$('#post_code');
    let mobile=$('#mobile');
    let address=$('#address');

    let customer_id=$('#customer_id');

    $('#customer_id').select2({
        ajax: {
            url: '{{url('api/search/user')}}',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
                },
            processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data.customers
                };
            }
        }
    });

    $('input[type=radio][name=type]').change(function() {
        if (this.value == 'new') {
            name.prop('required',true);
            phone.prop('required',true);
            post_code.prop('required',true);
            $('#customer_id').prop('required',false);
            $('#customer_row').show();
            $('#filter_row').hide();

        }
        else{
            $('#customer_id').prop('required',false);

            phone.prop('required',false);
            email.prop('required',false);
            post_code.prop('required',false);
            mobile.prop('required',false);
            address.prop('required',false);
            $('#customer_row').hide();
            $('#filter_row').show();
        }
    });


    $('.repeater2').repeater({
        // (Optional)
        // start with an empty list of repeaters. Set your first (and only)
        // "data-repeater-item" with style="display:none;" and pass the
        // following configuration flag
        initEmpty: false,
        // (Optional)
        // "defaultValues" sets the values of added items.  The keys of
        // defaultValues refer to the value of the input's name attribute.
        // If a default value is not specified for an input, then it will
        // have its value cleared.
        defaultValues: {
         'qty':'1'
        },
        // (Optional)
        // "show" is called just after an item is added.  The item is hidden
        // at this point.  If a show callback is not given the item will
        // have $(this).show() called on it.
        show: function () {
            $(this).slideDown();
        },

        // (Optional)
        // "hide" is called when a user clicks on a data-repeater-delete
        // element.  The item is still visible.  "hide" is passed a function
        // as its first argument which will properly remove the item.
        // "hide" allows for a confirmation step, to send a delete request
        // to the server, etc.  If a hide callback is not given the item
        // will be deleted.
        hide: function (deleteElement) {
            if(confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            }
        },
        // (Optional)
        // You can use this if you need to manually re-index the list
        // for example if you are using a drag and drop library to reorder
        // list items.

        // (Optional)
        // Removes the delete button from the first list item,
        // defaults to false.
        isFirstItemUndeletable: true
    });





</script>
@endsection
