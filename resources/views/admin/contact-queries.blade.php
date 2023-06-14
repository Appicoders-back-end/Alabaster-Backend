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
                                    <th class="text-white">Username</th>
                                    <th class="text-white">Email</th>
                                    <th class="text-white">Query Title</th>
                                    <th class="text-white">Message Box</th>
                                    <th class="text-white">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($queries as $query)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$query->user ? $query->user->name : null}}</td>
                                        <td>
                                            @if($query->user)
                                                <a href="mailto:{{$query->user->email}}">{{$query->user->email}}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-15">{{$query->title}}</div>
                                        </td>
                                        <td>
                                            <p data-id="{{ $query->id }}" data-message="{{$query->message}}" id="read"
                                               data-toggle="modal"
                                               data-target="#inquiry_message">
                                                {{ (strlen($query->message) > 20)?substr($query->message, 0, 20)." ... Read More
                                                ":$query->message??'-' }}
                                            </p>
                                        </td>
                                        <td>
                                            <a href="{{route('admin.delete-contact-queries', ['id' => $query->id])}}" class="btn btn-danger delete-confirm">Delete</a>
                                        </td>
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

    <div class="modal fade" id="inquiry_message" tabindex="-1" aria-labelledby="inquiry_message" aria-hidden="true">
        <div class="modal-dialog ">
            {{-- modal-dialog-centered--}}
            <div class="modal-content">
                <div class="modal-header newfqheading">
                    <h5 class="modal-title" id="exampleModalLabel">Inquiry Message </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body newfqbody p-3">
                    <p id="read_more"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(function () {

            $(document).on("click", '#read', function () {
                var str = $(this).data('id').length;
                if (str >= 20) {
                    $("#read").css({"cursor": "pointer"});
                }
                $("#read_more").text($(this).data('message'));
            });

            $('.delete-confirm').click(function (event) {
                event.preventDefault();
                var url = $(this).attr("href");
                swal({
                    title: "Are you sure?",
                    text: "You want to cancel it!",
                    icon: "warning",
                    type: "warning",
                    buttons: ["No", "Yes!"],
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Cancel it!'
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location.href = url;
                    }
                });
            });
        });

    </script>
@endsection
