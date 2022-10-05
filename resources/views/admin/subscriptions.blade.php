@extends('admin.layouts.app')
@section('content')
    <!-- Start Page title and tab -->
    <div class="mt-4">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane active" id="">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-danger col-3" data-toggle="modal" data-target="#addModal">
                                Add
                            </button>
                            <!-- Add Modal -->
                            <div class="modal fade" id="addModal" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title text-white text center">Add Subscription</h4>
                                            <button type="button" class="close text-white" data-dismiss="modal">×
                                            </button>
                                        </div>
                                        <form action="{{route('subscriptions.store')}}" method="post">
                                            {{csrf_field()}}
                                            <!-- Modal body -->
                                            <div class="modal-body ">
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Package Name</label>
                                                    <input name="package_name" type="text" class=" mb-2 border-dark form-control validate" required>
                                                </div>
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Select Plan</label>
                                                    <select name="interval_time" class="form-control border-dark" id="interval_time">
                                                        <option value="week">Weekly Plan</option>
                                                        <option value="month">Monthly Plan</option>
                                                        <option value="year">Yearly Plan</option>
                                                    </select>
                                                </div>
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Amount</label>
                                                    <input name="price" type="number" class=" mb-2 border-dark form-control validate" required>
                                                </div>

                                                <div class="md-form mr-3 ml-3 mt-2 mb-3">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Description</label>
                                                    <textarea name="description" class="form-control border-dark" id="exampleFormControlTextarea1" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button class="btn btn-dark">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit Modal -->
                            <div class="modal fade text-left" id="editModal" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title text-white">Edit Monthly
                                                Plan</h4>
                                            <button type="button" class="close text-white"
                                                    data-dismiss="modal">×
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <form action="" method="post" id="edit_form">
                                            {{csrf_field()}}
                                            <input type="hidden" name="id" id="edit_id" value="">
                                            <!-- Modal body -->
                                            <div class="modal-body ">
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Package Name</label>
                                                    <input name="package_name" id="edit_package_name" type="text" class=" mb-2 border-dark form-control validate" required>
                                                </div>
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Select Plan</label>
                                                    <select name="interval_time" id="edit_interval_time" class="form-control border-dark">
                                                        <option value="week">Weekly Plan</option>
                                                        <option value="month">Monthly Plan</option>
                                                        <option value="year">Yearly Plan</option>
                                                    </select>
                                                </div>
                                                <div class="md-form mr-3 ml-3 mt-2">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Amount</label>
                                                    <input name="price" id="edit_price" type="number" class=" mb-2 border-dark form-control validate" required>
                                                </div>

                                                <div class="md-form mr-3 ml-3 mt-2 mb-3">
                                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Description</label>
                                                    <textarea name="description" id="edit_description" class="form-control border-dark" id="exampleFormControlTextarea1" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button class="btn btn-dark">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        @foreach($subscriptions as $subscription)
                            <div class="col-sm-12 col-lg-4">
                            <div class="card ">
                                <div class="card-body text-center">
                                    <h1 class=" font-weight-bold card-category">{{$subscription->package_name}}</h1>
                                    <div class="display-5 my-4 font-weight-bold">${{$subscription->price}} <small>{{$subscription->interval_time}}</small>
                                    </div>
                                    <ul class="list-unstyled leading-loose">
                                        <li><i class="fe fe-check text-danger mr-2" aria-hidden="true"></i>
                                            {{$subscription->description}}
                                        </li>
                                    </ul>
                                    <div class="text-center mt-6">
                                        <button type="button" class="btn btn-danger mt-3" onClick="return openEditModal({{$subscription}})">
                                            Edit
                                        </button>
                                        <a href="{{route('admin.delete_subscription', $subscription->id)}}" class="btn btn-dark mt-3" onclick="return confirm('Are you sure you want to delete?')">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function openEditModal(subs) {
            let updateUrl = {!! json_encode(route('admin.update_subscription')) !!}
            $('#edit_id').val(subs.id);
            $('#edit_package_name').val(subs.package_name);
            $('#edit_price').val(subs.price);
            $('#edit_interval_time').val(subs.interval_time);
            $('#edit_description').text(subs.description);

            $('#edit_form').attr('action', updateUrl);
            $('#editModal').modal('show');
        }
    </script>
@endsection
