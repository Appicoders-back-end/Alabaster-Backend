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
                                    <h4 class=" text-dark font-weight-bold col-9">{{__('Edit Store Inventories')}}</h4>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Inventory Name</th>
                                    <th class="text-white">Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inventories as $inventory)
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$inventory->name}}</div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" value="{{$inventory->pivot->quantity}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
