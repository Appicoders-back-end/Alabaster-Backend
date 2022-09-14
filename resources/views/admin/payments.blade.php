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
                                    <h4 class=" text-dark font-weight-bold col-9">Payments</h4>
                                </div>
                                <thead class="bg-dark text-center">
                                <tr>
                                    <th class="text-white">Sr. No.</th>
                                    <th class="text-white">Host Name</th>
                                    <th class="text-white">Payment</th>
                                    <th class="text-white">Subscription Type</th>
                                    <th class="text-white">Status</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                @foreach($payments as $payment)
                                    <tr>
                                        <td><div class="font-15">{{$payment->id}}</div> </td>
                                        <td>
                                            <div class="font-15">{{$payment->user->name}}</div>
                                        </td>
                                        <td>${{$payment->price}}</td>
                                        <td>{{$payment->plan ? ucfirst($payment->plan->interval_time) : '-'}}</td>
                                        <td>{{$payment->user ? ucfirst($payment->user->status) : '-'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$payments->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
