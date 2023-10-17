@extends('admin.layouts.app')

@section('content')
    <!-- Start Page title and tab -->
    <div class="mt-4">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card-body">
                            <h4 class="text-dark font-weight-bold col-9">Edit Store</h4>
                            
                            <form method="post" action="{{route('admin.stores.update')}}"  method="post" enctype="multipart/form-data">
                                @csrf
                                
                                <input type="hidden" name="id" id="edit_id" value="{{$data->id}}">

                                <div class="md-form mr-3 ml-2 mt-3">
                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Store Name</label>
                                    <input name="name" type="text" class="mb-2 border-dark form-control validate"
                                           placeholder="Enter Store Name" value="{{ $data->name }}">
                                </div>
                                <div class="row row mr-2 ml-2">
                                    <h5 class="mt-3">Locations</h5>
                                </div>
                                
                                <?php $count = 0 ?>
                                <input type="hidden" id="addresscount" value="{{ $storeaddress->count() }}">
                                
                                @foreach ($storeaddress as $item)
                                    <div class="row mr-2 ml-1">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">{{__('Address')}}</label>

                                                <input type="hidden" name="addresses[{{$count}}][address_id]" value="{{$item['id']}} ">
                                                <input id="address[{{$count}}]" name="addresses[{{$count}}][address]" type="text" class="form-control"
                                                placeholder="Enter address" value="{{ $item['address'] }}">
                                                
                                                <input type="hidden" id="latitude[{{$count}}]" name="addresses[{{$count}}][latitude]" value="{{ $item['lat'] }}">
                                                <input type="hidden" id="longitude[{{$count}}]" name="addresses[{{$count}}][longitude]" value="{{ $item['lng'] }}">
                                            </div>
                                        </div>
                                    </div>
                                <?php $count++ ?>
                                @endforeach
                                <div class="row mr-2 ml-1" id="items">
                                </div>
                                
                                <div class="col-md-12">
                                    <a class="btn btn-success mr-2" href="javascript:;" onClick="return AddAddressRow()"
                                    style="float: right;">+</a>
                                    <a class="btn btn-success mr-2 pr-3" href="javascript:;" onClick="return RemAddressRow()"
                                       style="float: right;">-</a>
                                </div>
                                

                                <div class="md-form mr-3 ml-2 mt-3">
                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Upload Image</label>
                                    <input name="image" type="file" class="mb-2 border-dark form-control validate"
                                           accept="image/*">
                                </div>
                                
                                <div class="col-md-12 mt-2">
                                    <label></label>
                                    {{-- <a href="{{$data->getImageUrl()}}" target="_blank"
                                        class="text-primary text-center">
                                        <small>View previous uploaded ( Picture )</small>
                                    </a> --}}
                                    <br>
                                    <small>Previous Uploaded ( Image )</small>
                                    <img src="{{$data->getImageUrl()}}" alt="" style="width:250px">
                                    <input type="hidden" name="previmg" value="{{ $data->image }}">
                                </div>

                                <button type="submit" class="btn btn-dark me-2 mt-4 ml-2">Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3NUxL1BZ3S4v69vZExUtXdbFRAQEiMcE&callback=initAutocomplete&libraries=places" defer></script>
    <script>
        var addressesIndex = $('#addresscount').val() - 1;

        function initAutocomplete() {
            for (let i = 0; i <= addressesIndex; i++) {
                const addressField = document.getElementById(`address[${i}]`);
                const latitudeField = document.getElementById(`latitude[${i}]`);
                const longitudeField = document.getElementById(`longitude[${i}]`);

                if (addressField) {
                    autocomplete = new google.maps.places.Autocomplete(addressField, {
                        componentRestrictions: { country: ["us"] },
                        fields: ["address_components", "geometry"],
                        types: ["address"],
                    });

                    addressField.focus();

                    autocomplete.addListener("place_changed", () => {
                        fillInAddress(autocomplete, latitudeField, longitudeField);
                    });
                }
            }
        }

        function fillInAddress(autocomplete, latitudeField, longitudeField) {
            const place = autocomplete.getPlace();
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            latitudeField.value = lat;
            longitudeField.value = lng;
        }

        window.initAutocomplete = initAutocomplete;

        function AddAddressRow() {
            addressesIndex++;

            $('#items').append(`
            <div class="col-md-12 mb-3" id="location_${addressesIndex}">
                <input id="address[${addressesIndex}]" name="addresses[${addressesIndex}][address]" type="text" class="form-control"
                value="{{old('address')}}" placeholder="Enter address">
                
                <input type="hidden" id="latitude[${addressesIndex}]" name="addresses[${addressesIndex}][latitude]" value="{{old('latitude')}}">
                <input type="hidden" id="longitude[${addressesIndex}]" name="addresses[${addressesIndex}][longitude]" value="{{old('longitude')}}">
            </div>
            `);

            // Initialize autocomplete for the newly added address field
            initAutocomplete();
        }

        function RemAddressRow() {
            $('#location_' + addressesIndex).remove();
            addressesIndex--;
        }
    </script>

@endsection
