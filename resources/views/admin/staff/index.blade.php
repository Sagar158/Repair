@extends('admin.layouts.main')
@section('content')
    <!-- Zero configuration table -->
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Staffs  <a class="btn btn-success float-right" href="{{route('admin.staff.create')}}">Add New</a></h4>
                    </div>
                    <div class="card-content">
                        <form action="{{route('admin.staff.index')}}" method="GET">
                            @csrf
                              <button type="submit" class="btn btn-success float-right mr-2">Search</button>
                            <input type="search" name="search" value="{{@request()->get('search')}}" class="form-control col-3 mr-2 float-right" placeholder="Search User">

                        </form>
                        <div class="card-body card-dashboard table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>UserName</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>

                                        <td>{{$user->name}}</td>
                                        <td>{{$user->username}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->status}}</td>

                                        <td>
                                            @if($user->status=='verified')<a href="{{route('admin.staff.update.status',[$user->id,'blocked'])}}"><i title="Bloc User" class="fa fa-ban fa-lg mr-2 text-danger"></i></a> @else <a href="{{route('admin.staff.update.status',[$user->id,'verified'])}}"><i title="Activate User" class="fa fa-check-circle  fa-lg mr-2 text-success"></i></a>@endif
                                            <a href="{{route('admin.staff.edit',$user->id)}}"><i class="fa fa-pencil fa-lg mr-2 text-warning"></i></a>
                                            <a href="javascript:;" onclick="confirmDelete('{{$user->id}}')"><i class="fa fa-trash fa-lg text-danger"></i></a>
                                            <form id="delete-user-{{$user->id}}" action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" style="display: none;">
                                                {{ method_field('DELETE') }}
                                                {{csrf_field()}}
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$users->links()}}
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
            let choice=confirm("Are you sure, You want to delete Staff");
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
