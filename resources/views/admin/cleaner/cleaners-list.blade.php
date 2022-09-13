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
                                    <h4 class=" text-dark font-weight-bold col-9">Cleaners</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Phone No</th>
                                    <th class="text-white">Email Address</th>
                                    <th class="text-white">Category</th>
                                    <th class="text-white">Start Time</th>
                                    <th class="text-white">End Time</th>
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
                                            <div class="font-15">{{$user->contact_no}}</div>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->category ? $user->category->name : null}}</td>
                                        <td>{{$user->working_start_time != null ? formattedTime($user->working_start_time) : '-'}}</td>
                                        <td>{{$user->working_end_time != null ? formattedTime($user->working_end_time) : '-'}}</td>
                                        <td>
                                            <select class="form-control" name="status">
                                                <option {{$user->status == 'active' ? 'selected' : null}}>Active
                                                </option>
                                                <option {{$user->status == 'inactive' ? 'selected' : null}}>Inactive
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <!-- Button to Open the Modal -->
                                            <button type="button" class="btn btn-icon btn-dark" data-toggle="modal"
                                                    data-target="#viewDetailModal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$users->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>


                            <!-- The Modal View -->
                            <div class="modal fade" id="viewDetailModal">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content modal-dialog-scrollable">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="text-white">Cleaner View</h4>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="font-15 text-center"><img
                                                                src="../assets/images/male.png"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Full Name</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">Kevin Smith</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Phone Number</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">(417) 646-8377</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Email Address</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">smith@gmail.com</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Category</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">Abrasives</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Start Time</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">10:00 AM</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">End Time</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">02:00 PM</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Status</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold">Active</div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>


                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-dismiss="modal">Close
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection
