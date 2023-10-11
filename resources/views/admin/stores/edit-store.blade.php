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
                                <div class="md-form mr-3 ml-2 mt-3">
                                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Store Name</label>
                                    <input name="name" type="text" class="mb-2 border-dark form-control validate"
                                           placeholder="Enter Store Name">
                                </div>
                                <div class="row row mr-2 ml-2">
                                    <h5 class="mt-3">Locations</h5>
                                </div>
                                <div class="row mr-2 ml-1">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{__('Address')}}</label>
                                            <input id="address[0]" name="addresses[0][address]" type="text" class="form-control"
                                                   value="{{old('address')}}"placeholder="Enter address">

                                            <input type="hidden" id="latitude[0]" name="addresses[0][latitude]" value="{{old('latitude')}}">
                                            <input type="hidden" id="longitude[0]" name="addresses[0][longitude]" value="{{old('longitude')}}">
                                        </div>
                                    </div>
                                </div>
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
        
        var addressesIndex = 1;
        
        function AddAddressRow() {
            addressesIndex++;

            $('#items').append(`
            <div class="col-md-12 mb-3" id="location_${addressesIndex}">
                <input id="address[${addressesIndex}]" name="addresses[${addressesIndex}][address]" type="text" class="form-control"
                value="{{old('address')}}"placeholder="Enter address">
                
                <input type="hidden" id="latitude[${addressesIndex}]" name="addresses[${addressesIndex}][latitude]" value="{{old('latitude')}}">
                <input type="hidden" id="longitude[${addressesIndex}]" name="addresses[${addressesIndex}][longitude]" value="{{old('longitude')}}">
            </div>
            `);

            // Google Location Suggestions
            let addressElements = document.getElementById(`address[1]`);
            console.log("hello" + addressElements);

            function initAutocomplete() {
    
                const addressField = document.getElementById(`address[${addressesIndex}]`);
                const latitudeField = document.getElementById(`latitude[${addressesIndex}]`);
                const longitudeField = document.getElementById(`longitude[${addressesIndex}]`);

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

            function fillInAddress(autocomplete, latitudeField, longitudeField) {
                const place = autocomplete.getPlace();
                const lat = place.geometry.location.lat();
                const lng = place.geometry.location.lng();

                latitudeField.value = lat;
                longitudeField.value = lng;

                initAutocomplete();
            }
            window.initAutocomplete = initAutocomplete;
        }

        function RemAddressRow() {
            $('#location_'+addressesIndex).remove();

            addressesIndex--;
        }

        // Google Location Suggestions
        let autocompletes = [];

        let addressElements = document.getElementById(`address[0]`);
        console.log("hello" + addressElements);
        function initAutocomplete() {
            
            const addressField = document.getElementById(`address[0]`);
            const latitudeField = document.getElementById(`latitude[0]`);
            const longitudeField = document.getElementById(`longitude[0]`);

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

        function fillInAddress(autocomplete, latitudeField, longitudeField) {
            const place = autocomplete.getPlace();
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            latitudeField.value = lat;
            longitudeField.value = lng;

            initAutocomplete(); 
        }

        window.initAutocomplete = initAutocomplete;
    </script>
@endsection
