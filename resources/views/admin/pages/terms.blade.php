@extends('admin.layouts.app')
@section('content')
    <!-- Start Page title and tab -->
    <div class="mt-4">
        <div class="container-fluid">
            <div class="card">
                <div class="all-users">
                    <h4 class="text-center text-dark font-weight-bold">Terms and Service</h4>
                </div>
                <form action="{{route('admin.update-page')}}" method="post" id="editForm">
                    @csrf
                    <input type="hidden" name="id" value="{{$page->id}}">
                    <input type="hidden" name="page" value="terms">
                    <textarea name="data" id="text-editor" class="mt-2 form-control validate" rows="10" placeholder="Edit Privacy Policy">{!! $page->terms !!}</textarea>
                </form>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                </div>
                <div class="col-md-6 col-sm-12 text-md-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <button type="submit" form="editForm" class="btn btn-dark">Save</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.ckeditor.com/4.14.0/full/ckeditor.js"></script>
    <script>CKEDITOR.replace('text-editor');</script>
@endsection
