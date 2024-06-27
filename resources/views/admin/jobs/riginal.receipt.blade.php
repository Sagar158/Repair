<!DOCTYPE html>
<html lang="en">
<head>
    <title>Job Receipt</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>

         ul{
             list-style: none;
         }

         @media print {
             .print_btn{
                 display: none;
             }
         }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="text-center">
        <button type="button" onclick="window.print()" class="btn btn-primary print_btn">Print</button>
    </div>
    <div class="row" style="margin-top: 30px">
        <div class="col-sm-6">
            <img src="https://dinstech.co.uk/plessrepair/logo_pless.png" style="height: 150px">
        </div>
        <div class="col-sm-3">
            <ul>
                <li><b>Commerce St Branch</b></li>
                <li>47 Commerce St.</li>
                <li>Tradeston</li>
                <li>Glasgow</li>
                <li>G5 BAD</li>
                <li>Tel: 0141 420 3735</li>
            </ul>

        </div>
        <div class="col-sm-3">
            <ul>
                <li><b>Argyle St Branch</b></li>
                <li>960 Argyle St.</li>
                <li>Finneston</li>
                <li>Glasgow</li>
                <li>G3 8LU</li>
                <li>Tel: 0141 204 2755/2753</li>

            </ul>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 40px">
                <h3 class="font-weight-bold">Technical Repairs & Upgrades</h3>
            <br>
            <h4>Job# {{$job->job_number}}</h4>
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
            <h4>Priceless Copy</h4>
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
                @if($job->status=="Despatched")
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

                    <tr>
                        <td><b>Parts Cost:</b></td>
                        <td><b>£{{number_format((float)$cost, 2, '.', '')}}</b></td>
                    </tr>
                    <tr>
                        <td><b>Labour Cost:</b></td>
                        <td><b>£{{number_format((float)$job->labour_cost, 2, '.', '')}}</b></td>
                    </tr>
                    <tr>

                        <td><b>Total Cost:</b> </td>
                        <td><b>£{{number_format((float)$total_cost, 2, '.', '')}}</b></td>
                    </tr>

                @endif
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 40px">
        <div class="col-sm-6">
            <h4>Job Description:</h4>
            <p>
                {{$job->description}}
            </p>
        </div>
        <div class="col-sm-6">
            @if($job->status=="Despatched")
            <h4>Notes for Customer:</h4>
            <p>
                {{$job->customer_notes}}
            </p>
                @endif
        </div>
        <div class="col-sm-12">
            <h4>Customer Item Left:</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Qty</th>
                    <th>Item</th>
                    <th>Identifier</th>

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

                        </tr>
                      @endforeach
                  @endif
                </tbody>
            </table>
        </div>
        @if($job->status=="Despatched")
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
        @endif
    </div>

    <p>
    Terms & Conditions:Available upon request.
    </p>

</div>

<script>
    window.onload=function (){
        window.print();
    }
</script>
</body>
</html>
