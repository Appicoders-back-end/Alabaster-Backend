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
                                    <h4 class=" text-dark font-weight-bold col-9">Stores</h4>
                                    {{-- <button type="button" class="btn btn-danger col-3" onClick="return openAddModal()">Add</button> --}}
                                    <a href="{{ route('admin.create.stores') }}" type="button" class="btn btn-danger col-3">Add</a>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Store Name</th>
                                    <th class="text-white">Locations</th>
                                    <th class="text-white">Image</th>
                                    <th class="text-white">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stores as $store)
                                    <tr>
                                        <td>
                                            <div class="font-15">{{$store->name}}</div>
                                        </td>
                                        <td>
                                            @if($store->locations->count() > 0)
                                                <ul>
                                                    @foreach($store->locations as $storeLocation)
                                                        <li>{{$storeLocation->getFormattedAddress()}}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td><img width="60" src="{{$store->getImageUrl()}}"></td>
                                        <td>
                                            <!-- Button to Open the Modal -->
                                            <a hidden href="{{route('admin.store_inventories', $store->id)}}" type="button"
                                               class="btn btn-icon btn-dark"> <i class="fa fa-eye"></i></a>

                                            {{-- <button type="button" class="btn btn-icon btn-dark ml-2" onClick="return openEditModal({{$store}})"><i class="fas fa-edit"></i></button> --}}
                                            <a href="{{route('admin.edit.stores', $store->id)}}" type="button"
                                                class="btn btn-icon btn-dark"> <i class="fas fa-edit"></i></a>

                                            <a href="{{route('admin.stores.delete', $store->id)}}" type="button" class="btn btn-icon btn-danger ml-2"
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
                                    {{$stores->appends(request()->all())->links('vendor.pagination.bootstrap-4')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal Add -->
    <div class="modal fade" id="addStoreModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-white text center">Add Store</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('admin.stores.store')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body ">
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Store Name</label>
                            <input name="name" type="text" class="mb-2 border-dark form-control validate"
                                   placeholder="Enter Store Name">
                        </div>
                        <div class="row row mr-2 ml-3">
                            <h5 class="mt-3">Locations</h5>
                        </div>
                        <div class="row mr-2 ml-2 locations">
                            <div class="md-form col-md-4">
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">Street</label>
                                <input name="addresses[0][street]" type="text"
                                       class="mb-2 border-dark form-control validate">
                            </div>
                            <div class="md-form col-md-4">
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">State</label>
                                <input name="addresses[0][state]" type="text"
                                       class="mb-2 border-dark form-control validate">
                            </div>
                            <div class="md-form col-md-4">
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">Zipcode</label>
                                <input name="addresses[0][zipcode]" type="text"
                                       class="mb-2 border-dark form-control validate">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <a class="btn btn-success mr-2" href="javascript:;" onClick="return AddAddressRow()"
                               style="float: right;">+</a>
                        </div>
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Upload Image</label>
                            <input name="image" type="file" class="mb-2 border-dark form-control validate"
                                   accept="image/*">
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
                    <h4 class="modal-title text-white text center">Edit Store</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('admin.stores.update')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" id="edit_id" value="">
                    <div class="modal-body ">
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Store Name</label>
                            <input name="name" id="edit_name" type="text" class="mb-2 border-dark form-control validate"
                                   placeholder="Enter Store Name">
                        </div>
                        <div class="row row mr-2 ml-3">
                            <h5 class="mt-3">Locations</h5>
                        </div>
                        <div class="row mr-2 ml-2 locations" id="edit-locations">
                        </div>
                        <div class="col-md-12">
                            <a class="btn btn-success mr-2" href="javascript:;" onClick="return AddAddressRow()"
                               style="float: right;">+</a>
                        </div>
                        <div class="md-form mr-3 ml-3 mt-3">
                            <label data-error="wrong" data-success="right" for="defaultForm-pass">Upload Image</label>
                            <input name="image" type="file" class="mb-2 border-dark form-control validate"
                                   accept="image/*">
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
        function AddAddressRow() {
            // let addressesIndex = 0;
            console.log(addressesIndex);
            $('.locations').append(`<div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Street</label>
                                        <input name="addresses[${addressesIndex}][street]" type="text" class="mb-2 border-dark form-control validate">
                                    </div>
                                    <div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">State</label>
                                        <input name="addresses[${addressesIndex}][state]" type="text" class="mb-2 border-dark form-control validate">
                                    </div>
                                    <div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Zipcode</label>
                                        <input name="addresses[${addressesIndex}][zipcode]" type="text" class="mb-2 border-dark form-control validate">
                                    </div>`);
            addressesIndex++;
        }

        function openEditModal(store) {
            $('#edit_id').val(store.id);
            $('#edit_name').val(store.name);
            $('.locations').empty();
            if (store.locations.length > 0) {
                addressesIndex = store.locations.length;
                store.locations.forEach(function (address, key) {
                    $('#edit-locations').append(`<input type="hidden" name="addresses[${key}][address_id]" value="${address.id}">
                                    <div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Street</label>
                                        <input name="addresses[${key}][street]" type="text" class="mb-2 border-dark form-control validate" value="${address.street}">
                                    </div>
                                    <div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">State</label>
                                        <input name="addresses[${key}][state]" type="text" class="mb-2 border-dark form-control validate" value="${address.state}">
                                    </div>
                                    <div class="md-form col-md-4">
                                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Zipcode</label>
                                        <input name="addresses[${key}][zipcode]" type="text" class="mb-2 border-dark form-control validate" value="${address.zipcode}">
                                    </div>`);
                });
            }
            $('#editModal').modal('show');
        }

        function openAddModal() {
            addressesIndex = 1;

            $('#edit-locations').empty();
            $('#addStoreModal').modal('show');
        }
    </script>


@endsection
