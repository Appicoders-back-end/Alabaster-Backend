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
                                    @php
                                        $contactNo = $user->contact_no != null ? formattedNumber($user->contact_no) : "-";
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$user->name}}</div>
                                        </td>
                                        <td>
                                            <div class="font-15" id="{{$user->id}}_contact_no">{{$contactNo}}</div>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->category ? $user->category->name : null}}</td>
                                        <td>{{$user->working_start_time != null ? formattedTime($user->working_start_time) : '-'}}</td>
                                        <td>{{$user->working_end_time != null ? formattedTime($user->working_end_time) : '-'}}</td>
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
                                            <button type="button" class="btn btn-icon btn-dark" onClick="return viewDetail({{$user}})">
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
                                                        <div class="font-15 text-center"><img id="image" src="" width="300"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Name</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="name"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Phone Number</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="contact_no"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Email Address</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="email"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Category</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="category"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Work Hours</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="work_time"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Break Hours</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="break_time"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div class="font-15">Status</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div class="font-15 font-weight-bold" id="status"></div>
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
@section('script')
    <script>
        function viewDetail(user)
        {
            console.log(user);
            let imageUrl = {!! json_encode(url('/storage/uploads')) !!}

            // Original time in 24-hour format
            const startoriginalTime = user.working_start_time;
            const starttwelveHourTime = new Date(`2000-01-01T${startoriginalTime}`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            console.log(starttwelveHourTime); // Output: "05:33 PM"

            const endoriginalTime = user.working_end_time;
            const endtwelveHourTime = new Date(`2000-01-01T${endoriginalTime}`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            console.log(endtwelveHourTime); // Output: "05:33 PM"

            const breakstartoriginalTime = user.break_time_in;
            const breakstarttwelveHourTime = new Date(`2000-01-01T${breakstartoriginalTime}`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            console.log(breakstarttwelveHourTime); // Output: "05:33 PM"

            const breakendoriginalTime = user.break_time_out;
            const breakendtwelveHourTime = new Date(`2000-01-01T${breakendoriginalTime}`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            console.log(breakendtwelveHourTime); // Output: "05:33 PM"

            $('#name').text(user.name);
            $('#contact_no').text($('#'+user.id + '_contact_no').text());
            $('#email').text(user.email);
            $('#category').text(user.category != null ? user.category.name : '-');
            $('#status').text(user.status).css('textTransform', 'capitalize');
            $('#work_time').text(starttwelveHourTime + ' To ' + endtwelveHourTime ? starttwelveHourTime + ' To ' + endtwelveHourTime : '-');
            $('#break_time').text(breakstarttwelveHourTime + ' To ' + breakendtwelveHourTime ? breakstarttwelveHourTime + ' To ' + breakendtwelveHourTime : '-');
            if(user.profile_image != null){
                $('#image').removeClass('d-none').attr('src', imageUrl+'/'+user.profile_image);
            } else {
                $('#image').addClass('d-none');
            }

            $('#viewDetailModal').modal('show');
        }

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
