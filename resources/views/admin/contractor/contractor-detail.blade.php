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
                                    <h4 class=" text-dark font-weight-bold col-9">Contractor Detail</h4>
                                </div>
                                <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="font-15 text-center">
                                            @if($contractor->profile_image)
                                                <img id="image"
                                                     src="{{url('/storage/uploads').'/'.$contractor->profile_image}}"
                                                     width="300">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="font-15">Name</div>
                                    </td>
                                    <td width="50%">
                                        <div class="font-15 font-weight-bold" id="name">{{$contractor->name}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="font-15">Phone Number</div>
                                    </td>
                                    <td width="50%">
                                        <div class="font-15 font-weight-bold"
                                             id="contact_no">{{$contractor->contact_no ? formattedNumber($contractor->contact_no) : '-'}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="font-15">Email Address</div>
                                    </td>
                                    <td width="50%">
                                        <div class="font-15 font-weight-bold" id="email">{{$contractor->email}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="font-15">Status</div>
                                    </td>
                                    <td width="50%">
                                        <div class="font-15 font-weight-bold" id="status"
                                             style="text-transform: capitalize;">{{ucfirst($contractor->status)}}
                                        </div>
                                    </td>
                                </tr>
                                @foreach($contractor->addresses as $address)
                                    <tr class="address-row">
                                        <td width="50%">
                                            <div class="font-15">Address {{$loop->iteration}}</div>
                                        </td>
                                        <td width="50%">
                                            <div
                                                class="font-15 font-weight-bold">{{sprintf("%s, %s, %s.", $address->street, $address->state, $address->zipcode)}}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                                <div class="all-users row">
                                    <h4 class=" text-dark font-weight-bold col-9">Contractor Companies</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Phone No</th>
                                    <th class="text-white">Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contractor->contractorCompanies as $company)
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$company->name}}</div>
                                        </td>
                                        <td>
                                            <div
                                                class="font-15">{{$company->contact_no}}
                                            </div>
                                        </td>
                                        <td>{{$company->address}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    {{-- $users->appends(request()->all())->links('vendor.pagination.bootstrap-4') --}}
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
@endsection
