@extends('admin.layouts.main')
@section('content')
    <!-- Zero configuration table -->
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customers  <a class="btn btn-success float-right" href="{{route('admin.customer.create')}}">Add New</a></h4>
                    </div>
                    <div class="card-content">
                        <form action="{{route('admin.customer.index')}}" method="GET">
                            @csrf
                              <button type="submit" class="btn btn-success float-right mr-2">Search</button>
                            <input type="search" name="search" required value="{{@request()->get('search')}}" class="form-control col-3 mr-2 float-right" placeholder="Search User">

                        </form>
                        <div class="card-body card-dashboard table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Telephone</th>
                                    <th>Post Code</th>
                                    <th>Customer Type</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Notes</th>
                                    <th>Added By</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>

                                        <td>{{$customer->name}}</td>
                                        <td>{{$customer->phone}}</td>
                                        <td>{{$customer->mobile}}</td>
                                        <td>{{$customer->post_code}}</td>
                                        <td>{{$customer->customer_type}}</td>
                                        <td>{{$customer->email}}</td>

                                        <td>{{$customer->address}}</td>
                                        <td>{{$customer->notes}}</td>
                                        <td>{{@$customer->user->name}}</td>
                                        <td>{{date('d/m/Y',strtotime($customer->created_at))}}</td>

                                        <td>

                                            <a href="{{route('admin.customer.edit',$customer->id)}}"><i class="fa fa-pencil fa-lg mr-2 text-warning"></i></a>
                                            <a href="javascript:;" onclick="confirmDelete('{{$customer->id}}')"><i class="fa fa-trash fa-lg text-danger"></i></a>
                                            <form id="delete-user-{{$customer->id}}" action="{{ route('admin.customer.destroy', $customer->id) }}" method="POST" style="display: none;">
                                                {{ method_field('DELETE') }}
                                                {{csrf_field()}}
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$customers->links()}}
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
