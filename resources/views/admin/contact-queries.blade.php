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
                                    <h4 class=" text-dark font-weight-bold col-9">Queries</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">S.No.</th>
                                    <th class="text-white">Query Title</th>
                                    <th class="text-white">Message Box</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($queries as $query)
                                    <tr>
                                        <td>{{$query->id}}</td>
                                        <td>
                                            <div class="font-15">{{$query->title}}</div>
                                        </td>
                                        <td>{{$query->message}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$queries->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
