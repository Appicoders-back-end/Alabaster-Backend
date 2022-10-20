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
                                <form method="post" action="{{route('admin.update_store_inventories')}}" id="inventoriesForm">
                                    @csrf
                                    <input type="hidden" name="store_id" value="{{$store->id}}">
                                    @foreach($inventories as $key => $inventory)
                                        <tr>
                                            <td>
                                                <div class="font-15">{{$inventory->name}}</div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="inventories[{{$key}}][id]" value="{{$inventory->id}}">
                                                <input type="number" name="inventories[{{$key}}][quantity]" class="form-control" value="{{$inventory->pivot->quantity}}" min="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                </form>
                                </tbody>
                            </table>
                            <div class="all-users row">
                                <button type="submit" form="inventoriesForm" class="btn btn-danger col-3">
                                    Update
                                </button>
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
