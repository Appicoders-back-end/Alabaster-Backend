@extends('admin.layouts.app')
@section('content')
    <!-- Start Page title and tab -->
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane active" id="">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-danger col-3" data-toggle="modal"
                                    data-target="#myModal2">
                                Add
                            </button>
                            <!-- The Modal -->
                            <div class="modal fade" id="myModal2" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title text-white text center">Add Subscription</h4>
                                            <button type="button" class="close text-white" data-dismiss="modal">×
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body ">
                                            <div class="md-form mr-3 ml-3 mt-2">
                                                <label data-error="wrong" data-success="right"
                                                       for="defaultForm-pass">Select Plan</label>
                                                <select class="form-control border-dark"
                                                        id="exampleFormControlSelect1">
                                                    <option>Monthly Plan</option>
                                                    <option>Quarterly Plan</option>
                                                    <option>Yearly Plan</option>
                                                </select>
                                            </div>
                                            <div class="md-form mr-3 ml-3 mt-2">
                                                <label data-error="wrong" data-success="right"
                                                       for="defaultForm-pass">Amount</label>
                                                <input type="number"
                                                       class=" mb-2 border-dark form-control validate">
                                            </div>

                                            <div class="md-form mr-3 ml-3 mt-2 mb-3">
                                                <label data-error="wrong" data-success="right"
                                                       for="defaultForm-pass">Description</label>
                                                <textarea class="form-control border-dark"
                                                          id="exampleFormControlTextarea1" rows="3"></textarea>
                                            </div>

                                        </div>
                                        <div class="modal-footer d-flex justify-content-center">
                                            <button class="btn btn-dark">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-body mt-4">
                        <div class="container">
                            <div class="row clearfix ">
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
                                                <button type="button" class="btn btn-danger mt-3"
                                                        data-toggle="modal" data-target="#myModal3">
                                                    Edit
                                                </button>
                                                <div class="modal fade text-left" id="myModal3"
                                                     style="display: none;" aria-hidden="true">
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
                                                            <div class="modal-body ">
                                                                <div class="md-form mr-3 ml-3 mt-2">
                                                                    <label data-error="wrong" data-success="right"
                                                                           for="defaultForm-pass">Select
                                                                        Plan</label>
                                                                    <select class="form-control border-dark"
                                                                            id="exampleFormControlSelect1">
                                                                        <option>Monthly Plan</option>
                                                                        <option>Quarterly Plan</option>
                                                                        <option>Yearly Plan</option>
                                                                    </select>
                                                                </div>
                                                                <div class="md-form mr-3 ml-3 mt-2">
                                                                    <label data-error="wrong" data-success="right"
                                                                           for="defaultForm-pass">Amount</label>
                                                                    <input type="number"
                                                                           class=" mb-2 border-dark form-control validate">
                                                                </div>

                                                                <div class="md-form mr-3 ml-3 mt-2 mb-3">
                                                                    <label data-error="wrong" data-success="right"
                                                                           for="defaultForm-pass">Description</label>
                                                                    <textarea class="form-control border-dark"
                                                                              id="exampleFormControlTextarea1"
                                                                              rows="3"></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer d-flex justify-content-center">
                                                                <button class="btn btn-dark">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-dark mt-3">
                                                    Delete
                                                </button>
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
        </div>
    </div>
@endsection
