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
                                    <h4 class=" text-dark font-weight-bold col-9">Work Orders</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">id</th>
                                    <th class="text-white">Customer Name</th>
                                    <th class="text-white">Contractor Name</th>
                                    <th class="text-white">Customer Location</th>
                                    <th class="text-white">Date</th>
                                    <th class="text-white">Time</th>
                                    <th class="text-white">Category Name</th>
                                    <th class="text-white">Urgency</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jobs as $job)
                                    <tr>
                                        <td>{{$loop->iteration + $jobs->firstItem() - 1}}</td>
                                        <td>
                                            <div class="font-15">{{$job->customer != null ? $job->customer->name : '-'}}</div>
                                        </td>
                                        <td>
                                            <div class="font-15">{{$job->contractor != null ? $job->contractor->name : '-'}}</div>
                                        </td>
                                        <td>{{$job->location != null ? $job->location->getFormattedAddress() : '-'}}</td>
                                        <td>{{formattedDate($job->date)}}</td>
                                        <td>{{formattedTime($job->start_time)}}</td>
                                        <td>{{$job->category != null ? $job->category->name : '-'}}</td>
                                        <td>{{$job->urgency}}</td>
                                        <td>{{ucfirst($job->status)}}</td>
                                        <td>
                                            <a href="#" class="btn btn-icon btn-dark">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    {{$jobs->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
