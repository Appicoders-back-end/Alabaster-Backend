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
                                    <h4 class=" text-dark font-weight-bold col-9">Categories</h4>
                                    <button type="button" class="btn btn-danger col-3" data-toggle="modal" data-target="#addCategoryModal">
                                        Add
                                    </button>
                                </div>
                                <thead class="bg-dark">
                                <tr>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Icon</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td><div class="font-15">{{$category->name}}</div></td>
                                        <td><img style="background-color: #000000" width="60" src="{{$category->getImageUrl()}}"></td>
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

    <!-- The Modal Add -->
    <div class="modal fade" id="addCategoryModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-white text center">Add Category</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body ">
                    <div class="md-form mr-3 ml-3 mt-3">
                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Category Name</label>
                        <input type="text" class=" mb-2 border-dark form-control validate" placeholder="Enter Category Name">
                    </div>
                    <div class="md-form mr-3 ml-3 mt-3">
                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Upload Image</label>
                        <input type="file" class=" mb-2 border-dark form-control validate">
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-dark">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
