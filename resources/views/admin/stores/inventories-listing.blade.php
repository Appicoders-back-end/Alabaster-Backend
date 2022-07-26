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
                                    <h4 class=" text-dark font-weight-bold col-9">{{__('Inventories')}}</h4>
                                    <button type="button" class="btn btn-danger col-3" data-toggle="modal"
                                            data-target="#addModal">
                                        Add
                                    </button>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inventories as $inventory)
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$inventory->name}}</div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-dark ml-2"
                                                    onClick="return openEditModal({{$inventory}})"><i
                                                    class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{route('admin.inventories.delete', $inventory->id)}}" type="button" class="btn btn-icon btn-danger ml-2"
                                                    onClick="return confirm('Are you sure you want to delete?')"><i
                                                    class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 col-md-7 mt-3">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$inventories->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal Add -->
    <div class="modal fade" id="addModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-white text center">Add Inventory</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('admin.inventories.store')}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-body ">
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Inventory Name</label>
                            <input name="name" type="text" class="mb-2 border-dark form-control validate">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-dark">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- The Modal Edit -->
    <div class="modal fade" id="editModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-white text center">Edit Inventory</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('admin.inventories.update')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="id" id="edit_id" value="">
                    <div class="modal-body ">
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Inventory Name</label>
                            <input name="name" id="edit_name" type="text"
                                   class="mb-2 border-dark form-control validate">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-dark">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        function openEditModal(inventory) {
            $('#edit_id').val(inventory.id);
            $('#edit_name').val(inventory.name);

            $('#editModal').modal('show');
        }
    </script>
@endsection
