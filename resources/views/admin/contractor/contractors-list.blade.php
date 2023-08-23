@extends('admin.layouts.app')
@section('content')
    <!-- Start Page title and tab -->
    <div class="mt-4">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane active" id="Staff-all">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                                <div class="all-users row">
                                    <h4 class=" text-dark font-weight-bold col-9">Contractors</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Phone No</th>
                                    <th class="text-white">Email Address</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$user->name}}</div>
                                        </td>
                                        <td>
                                            <div class="font-15">{{$user->contact_no ? formattedNumber($user->contact_no) : '-'}}</div>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            <select class="form-control" name="status" onchange="return saveStatus(this.value, {{$user->id}})">
                                                <option value="active" {{$user->status == 'active' ? 'selected' : null}}>Active
                                                </option>
                                                <option value="inactive" {{$user->status == 'inactive' ? 'selected' : null}}>Inactive
                                                </option>
                                                <option value="delete" {{$user->status == 'delete' ? 'selected' : null}}>Delete
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <!-- Button to Open the Modal -->
                                            <a href="{{route('admin.contractorDetail', $user->id)}}" type="button" class="btn btn-icon btn-dark" >
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    {{$users->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function saveStatus(status,id)
        {
            var url = "{{ url('admin/updateUserStatus') }}/"+id;
            var csrf ="{{csrf_token()}}"
            $.post(url,{_token:csrf,status:status},function(e){
                if (e.success) {
                    alert(e.message);
                } else {
                    alert("Something went wrong");
                }
            });
        }
    </script>
@endsection
