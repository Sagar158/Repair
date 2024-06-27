@extends('admin.layouts.main')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6" style="padding-left: 40px">

                    <br>
                    <h2>Job# {{$job->job_number}}</h2>
                    <table>
                        <tr>
                            <td>Name: </td>
                            <td>{{@$job->customer->name}}</td>
                        </tr>
                        <tr>
                            <td>Mobile: </td>
                            <td>{{@$job->customer->phone}}</td>
                        </tr>
                        <tr>
                            <td>Telephone: </td>
                            <td>{{@$job->customer->mobile}}</td>
                        </tr>
                        <tr>
                            <td>Address: </td>
                            <td>{{@$job->customer->address}}</td>
                        </tr>

                        <tr>
                            <td>PostCode: </td>
                            <td>{{@$job->customer->post_code}}</td>
                        </tr>
                        <tr>
                            <td>Email: </td>
                            <td>{{@$job->customer->email}}</td>
                        </tr>

                    </table>
                </div>

                <div class="col-sm-6" style="padding-left: 40px">
                    <a target="popup" onclick="window.open('{{route('admin.job.print',$job->id)}}','name','width=1000,height=700')" class="btn btn-primary btn-sm" href="{{route('admin.job.print',$job->id)}}">Print Receipt</a>

                @if($job->status=="Ready for Despatch" || $job->status=="Despatched")
                        @else
                   <form action="{{route('admin.job.update_status')}}" method="POST">
                       @csrf
                       <input type="hidden" name="job_id" value="{{$job->id}}">
                       <div class="row">
                           <select class="form-control col-4" name="status">
                               <option @if(@$job->status=="Queued"){{'selected'}}@endif value="Queued">Queued</option>
                               <option @if(@$job->status=="In Progress"){{'selected'}}@endif value="In Progress">In Progress</option>
                               <option @if(@$job->status=="Waiting for parts"){{'selected'}}@endif value="Waiting for parts">Waiting for parts</option>
                               <option @if(@$job->status=="Waiting for Customer Reply"){{'selected'}}@endif value="Waiting for Customer Reply">Waiting for Customer Reply</option>
                               <option @if(@$job->status=="Unresolved"){{'selected'}}@endif value="Unresolved">Unresolved</option>

                           </select>
                           <button type="submit" class="btn btn-success col-2 ml-2">Change</button>
                       </div>
                       <input type="hidden" value="{{$job->id}}" name="job_id">

                   </form>

                        <button onclick="ready_dispatch()" class="btn btn-primary btn-block">Ready for Despatch</button>


                    @endif
                        @if($job->status=="Ready for Despatch")

                        <button onclick="dispatch()" class="btn btn-success btn-block">Despatch!</button>
                        @endif


                        @if($job->status=="Ready for Despatch" || $job->status=="Despatched")
                        @else
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Parts Used</button>

                        @endif

                   <h5>Repair Location: {{$job->job_location}}</h5>
                    <h5>Date Booked In: {{date('d/m/Y',strtotime($job->created_at))}}</h5>

                    <table>
                        <tr>
                            <td>Booked In By: </td>
                            <td>{{@$job->booked_by->name}}</td>
                        </tr>
                        <tr>
                            <td>Job Type: </td>
                            <td>{{@$job->job_type}}</td>
                        </tr>
                        <tr>
                            <td>System Type: </td>
                            <td>{{@$job->system_type}}</td>
                        </tr>
                        <tr>
                            <td>Warranty Job: </td>
                            <td>{{@$job->warranty_job}}</td>
                        </tr>
                        <tr>
                            <td>Estimated Labour Cost: </td>
                            <td>£{{number_format((float)@$job->est_labour_cost, 2, '.', '')}}</td>
                        </tr>
                        <tr>
                            <td>Estimated Parts Cost: </td>
                            <td>£{{number_format((float)@$job->est_parts_cost, 2, '.', '')}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @if($job->status=="Despatched")
            <div class="row" style="margin-top: 40px">
                <div class="col-sm-6">
                    <h4>Despatched Info:</h4>
                    <?php

                    $cost=0.00;

                    $parts=json_decode($job->parts);
                    if (is_array($parts) && count($parts)>0){
                        foreach ($parts as $part){
                            $cost=$cost+(float)$part->price;
                        }
                    }

                    $total_cost=$cost+(float)$job->labour_cost;
                    ?>
                    <table>
                        <tr>
                            <td>Despatched By: </td>
                            <td>{{@$job->_despatched_by->name}}</td>
                        </tr>
                        <tr>
                            <td>Despatched At: </td>
                            <td>{{date('d/m/Y',strtotime(@$job->despatch_at))}}</td>
                        </tr>
                        <tr>
                            <td>Customer have Receipt: </td>
                            <td>{{@$job->despatch_receipt}}</td>
                        </tr>
                        <tr>
                            <td>Parts Cost:</td>
                            <td>£{{number_format((float)$cost, 2, '.', '')}}</td>
                        </tr>
                        <tr>
                            <td>Labour Cost:</td>
                            <td>£{{number_format((float)$job->labour_cost, 2, '.', '')}}</td>
                        </tr>
                        <tr>

                            <td>Total Cost: </td>
                            <td>£{{number_format((float)$total_cost, 2, '.', '')}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                   @if($job->despatch_receipt=="No")
                       <table>
                           <tr>
                               <td>Customer Verify Option:</td>
                               <td>{{$job->customer_verify_option}}</td>
                           </tr>
                           @if($job->customer_verify_option=="Call Customer")
                           <tr>
                               <td>Customer Phone Verification:</td>
                               <td>{{$job->customer_verify_phone}}</td>

                           </tr>
                          @endif

                           @if($job->customer_verify_option=="Check ID")
                               <tr>
                                   <td>Check ID Method:</td>
                                   <td>{{$job->customer_id_method}}</td>

                               </tr>
                               <tr>
                                   <td>Document Number:</td>
                                   <td>{{$job->document_number}}</td>
                               </tr>
                           @endif

                           @if($job->customer_verify_option=="Override")
                               <tr>
                                   <td>Reason for Override:</td>
                                   <td>{{$job->reason_for_override}}</td>

                               </tr>
                           @endif
                           <tr>
                               <td>Statisfied with Despatch:</td>
                               <td>{{$job->satisfied}}</td>
                           </tr>
                       </table>

                   @endif


                </div>
            </div>
            @endif
            <div class="row" style="margin-top: 40px">
                <div class="col-sm-6">
                    <h4>Job Description:</h4>
                    <p>
                        {{$job->description}}
                    </p>
                </div>
                <div class="col-sm-6">
                    <h4>Additional Notes:</h4>
                    <p>
                        {{$job->additional_notes}}
                    </p>
                </div>
                <div class="col-sm-12">
                    <h4>Customer Item Left:</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Item</th>
                            <th>Identifier</th>
                            <th>Item Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $items=json_decode($job->items);

                        ?>
                        @if(is_array($items) && count($items)>0 )
                            @foreach($items as $item)
                                <tr>
                                    <td>{{@$item->qty}}</td>
                                    <td>{{@$item->item}}</td>
                                    <td>{{@$item->identifier}}</td>
                                    <td>{{@$item->notes}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                    <h4>Parts Used:</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Item Name</th>
                            <th>SN</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $parts=json_decode($job->parts);

                        ?>
                        @if(is_array($parts) && count($parts)>0 )
                            @foreach($parts as $part)
                                <tr>
                                    <td>{{@$part->sku}}</td>
                                    <td>{{@$part->qty}}</td>
                                    <td>{{@$part->item}}</td>
                                    <td>{{@$part->sn}}</td>
                                    <td>{{@$part->price}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                    <form action="{{route('admin.job.add_comment')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$job->id}}" name="job_id">
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" rows="4" required name="comment"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Add Comment</button>
                    </form>

                    @foreach($job->comments as $comment)
                        <div class="border border-black p-2">
                            <b>{{@$comment->user->name}}</b>
                            <p>{{$comment->comment}}
                            <br>
                                <span>{{$comment->created_at}}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection
@section('modals')

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{route('admin.job.add_parts')}}">
                    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD Parts Used</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                        <input type="hidden" name="job_id" value="{{$job->id}}">
                        <div class="repeater2" style="margin-left: 10px;">

                            <div data-repeater-list="parts" class="ml-2">
                                <?php
                                $parts=json_decode($job->parts);

                                ?>

                                @if(is_array($parts) && count($parts) >0)
                                    @foreach($parts as $part)
                                            <div data-repeater-item class="mt-2 row">

                                                <input type="text" placeholder="SKU" value="{{@$part->sku}}" class="form-control required col-2 mr-1" id="sku" name="sku" required>
                                                <input type="text" onfocus="(this.type='number')" value="{{@$part->qty}}" onblur="(this.type='text')" min="0" step="1" placeholder="Qty" class="form-control required col-1 mr-1 qty" value="1" name="qty" required>
                                                <input type="text" placeholder="Item Name" value="{{$part->item}}" class="form-control required col-2 mr-1" id="item" name="item" required>
                                                <input type="text" placeholder="SN" value="{{@$part->sn}}" class="form-control required col-2 mr-1" id="sn" name="sn" required>
                                                <input type="number" min="0" placeholder="price" value="{{@$part->price}}" class="form-control required col-2 mr-1" id="price" name="price" required>
                                                <i data-repeater-delete class="fa fa-trash text-danger" style="font-size: 35px;cursor: pointer"></i>
                                            </div>
                                    @endforeach
                                @else
                                        <div data-repeater-item class="mt-2 row">

                                            <input type="text" placeholder="SKU" class="form-control required col-2 mr-1" id="sku" name="sku" required>
                                            <input type="text" onfocus="(this.type='number')" onblur="(this.type='text')" min="0" step="1" placeholder="Qty" class="form-control required col-1 mr-1 qty" value="1" name="qty" required>
                                            <input type="text" placeholder="Item Name" class="form-control required col-2 mr-1" id="item" name="item" required>
                                            <input type="text" placeholder="SN" class="form-control required col-2 mr-1" id="sn" name="sn" required>
                                            <input type="number" min="0" placeholder="price" class="form-control required col-2 mr-1" id="price" name="price" required>
                                            <i data-repeater-delete class="fa fa-trash text-danger" style="font-size: 35px;cursor: pointer"></i>
                                        </div>

                                @endif

                            </div>
                            <input data-repeater-create type="button" class="btn btn-success mt-2" value="Add Items"/>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{route('admin.job.ready_to_dispatch')}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Despatch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="job_id" value="{{$job->id}}">
                       <div class="form-group">
                           <label>Notes for customer</label>
                           <textarea class="form-control" rows="3" name="customer_notes"></textarea>
                       </div>
                        <div>
                            <label>Labour Cost</label>
                            <input type="number" min="0" class="form-control" name="labour_cost" id="labour_cost" required>
                        </div>
                        <div class="form-group" id="reason_div" style="display: none">
                            <label>Reason for 0 cost</label>
                            <input type="text" class="form-control" name="reason" id="reason">
                        </div>
                        <?php
                        $cost=0.00;

                        $parts=json_decode($job->parts);
                        if (is_array($parts) && count($parts)>0){
                            foreach ($parts as $part){
                                $cost=$cost+(float)$part->price;
                            }
                        }
                        ?>

                        <p class="mt-2">Parts Cost: <span id="parts_cost" class="text-primary">{{$cost}}</span></p>
                        <p>Total Cost: <span id="total_cost" class="text-primary">{{$cost}}</span></p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" id="despatch_form" action="{{route('admin.job.despatch')}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Provide Dispatch Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <input type="hidden" name="job_id" value="{{$job->id}}">
                        <div class="form-group">
                            <label>Does the customer have the Repair receipt?</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="receipt" id="inlineRadio1" value="Yes">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="receipt" id="inlineRadio2" value="No">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </div>
                        <div class="form-group" id="customer_verify_option_div" style="display: none" >
                            <label>Verify Customer ID</label>
                            <select class="form-control col-6" name="customer_verify_option" id="customer_verify_option">
                                <option value="">--select--</option>
                                <option value="Call Customer">Call Customer</option>
                                <option value="Check ID">Check ID</option>
                                <option value="Override">Override</option>
                                <option value="Cancel">Cancel</option>
                            </select>
                        </div>

                        <div class="row" id="customer_verify_div" style="display: none">
                             <div class="col-sm-12" id="call_customer_div" style="display: none">
                                 <div class="form-group">
                                     <p>Call customer On Mobile: {{$job->customer_phone}} OR Telephone : {{$job->customer_mobile}}</p>
                                    <label>Customer Verified?</label>
                                     <select class="form-control col-6" name="customer_verify_phone" id="customer_verify_phone">
                                        <option value="">--select--</option>
                                        <option value="Yes">Yes</option>
                                         <option value="No">No</option>
                                    </select>
                                 </div>
                             </div>
                        </div>
                        <div  id="check_id_method_div" style="display: none">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>ID Method</label>
                                    <select class="form-control"  name="customer_id_method" id="customer_id_method">
                                           <option value="">--select--</option>
                                           <option value="Driving License">Driving License</option>
                                        <option value="Passport">Passport</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Document Number</label>
                                   <input type="text" class="form-control" name="document_number" id="document_number">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="override_div" style="display: none">

                                <div class="form-group col-12">
                                    <label>Reason for Override</label>
                                    <textarea class="form-control" rows="3" id="reason_for_override" name="reason_for_override"></textarea>
                                </div>

                        </div>

                        <div id="satisfy_div" class="row" style="display: none">
                           <div class="form-group col-12">
                               <label>Are you Satisfied</label>
                               <select class="form-control col-6" name="satisfied" id="satisfied">
                                    <option value="">--select--</option>
                                   <option value="Yes">Yes</option>
                                   <option value="No">No</option>
                               </select>
                           </div>
                        </div>

                        <div id="user_verify_div" class="row" style="display: none">
                            <h6 class="col-sm-12">Verify Yourself!</h6>
                            <div class="form-group col-sm-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>

        $(document).ready(function (){
            $('input[type=radio][name=receipt]').change(function() {

                $('#call_customer_div').hide();
                $('#customer_verify_phone').prop('required',false);
                $('#customer_verify_phone').val("");

                $('#check_id_method_div').hide();
                $('#customer_id_method').prop('required',false);
                $('#document_number').prop('required',false);

                $('#customer_id_method').val("");
                $('#document_number').val("");

                $('#override_div').hide();
                $('#reason_for_override').prop('required',false);
                $('#reason_for_override').val("");

                $('#satisfy_div').hide();
                $('#satisfied').prop('required',false);
                $('#satisfied').val("");


                if (this.value == 'Yes') {
                    $('#user_verify_div').show();

                    $('#customer_verify_option_div').hide();
                    $('#customer_verify_option').prop('required',false);
                    $('#customer_verify_option').val("");
                }
                else if (this.value == 'No') {
                    $('#user_verify_div').hide();


                    $('#customer_verify_option_div').show();
                    $('#customer_verify_option').prop('required',true);

                }
            });

            $('#customer_verify_option').on('change',function (){
               var val=$(this).val();

                $('#call_customer_div').hide();
                $('#customer_verify_phone').prop('required',false);
                $('#customer_verify_phone').val("");

                $('#check_id_method_div').hide();
                $('#customer_id_method').prop('required',false);
                $('#document_number').prop('required',false);

                $('#customer_id_method').val("");
                $('#document_number').val("");

                $('#override_div').hide();
                $('#reason_for_override').prop('required',false);
                $('#reason_for_override').val("");

                $('#satisfy_div').hide();
                $('#satisfied').prop('required',false);
                $('#satisfied').val("");

                $('#user_verify_div').hide();

               if (val!=""){
                   $('#customer_verify_div').show();
                   if (val =="Call Customer"){
                       $('#call_customer_div').show();
                       $('#customer_verify_phone').prop('required',true);

                       $('#satisfy_div').show();
                       $('#satisfied').prop('required',true);
                   }
                   else if (val =="Check ID"){
                       $('#check_id_method_div').show();
                       $('#customer_id_method').prop('required',true);
                       $('#document_number').prop('required',true);

                       $('#satisfy_div').show();
                       $('#satisfied').prop('required',true);
                   }
                   else if (val=="Override"){
                       $('#override_div').show();
                       $('#reason_for_override').prop('required',true);

                       $('#satisfy_div').show();
                       $('#satisfied').prop('required',true);

                   }
                   else{
                        document.getElementById('despatch_form').reset();
                        $('#exampleModal3').modal('hide');

                        $('#customer_verify_option_div').hide();
                        $('#customer_verify_option').prop('required',false);

                       $('#satisfy_div').hide();
                       $('#satisfied').prop('required',false);
                       $('#satisfied').val("");


                   }

               }else{
                   alert('Please Select option');
               }
            });



            $('#satisfied').on('change',function (){
               var val=$(this).val();
               if (val =="Yes"){
                   $('#user_verify_div').show();

               }
            });




            $('#labour_cost').on('change',function (){

                var cost=$(this).val();
                if (cost==0){

                    $('#total_cost').text($('#parts_cost').text());

                    $('#reason_div').show();
                    $('#reason').prop('required',true);
                }else{
                    var parts_cost=parseFloat($('#parts_cost').text());
                    cost = parseFloat(cost);
                    $('#total_cost').text(parts_cost+cost);

                    $('#reason_div').hide();
                    $('#reason').prop('required',false);
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
                isFirstItemUndeletable: false
            });


        });

        function ready_dispatch(){
            let choice=confirm("Are you ready to despatch?");
            if (choice){
                $('#exampleModal2').modal('show');
            }
        }

        function dispatch(){
            let choice=confirm("Would you like to despatch this repair?");
            if (choice){

                    $('#exampleModal3').modal('show');


            }
        }

        @if (session()->has('job_id'))
        window.open('{{route('admin.job.print',session('job_id'))}}','name','width=1000,height=700');
        @endif


    </script>

@endsection
