@extends('admin.layout.app')
@section('style')
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
@endsection
@section('header')
<!-- Page title -->
                          
                           
                                {{--  <li> 
                                    <div class="add-btn">
                                        <a href="{{ url('/dashboard/companies/create') }}" type="submit" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
                                    </div> 
                                </li>  --}}
                           
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/companies/' ,'method' => 'Post','files' => true]) !!}
                @include('admin.companies.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY&language=ar"></script>
    <script type="text/javascript">
        var map;
        var marker;
        var autocomplete;
        var geocoder = new google.maps.Geocoder();

        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    updateMarkerAddress(responses[0].formatted_address);
                } else {
                    updateMarkerAddress('Cannot determine address at this location.');
                }
            });
        }

        function updateMarkerStatus(str) {
            document.getElementById('markerStatus').innerHTML = str;
        }

        function updateMarkerPosition(latLng) {
            document.getElementById('info').innerHTML = [
                latLng.lat(),
                latLng.lng()
            ].join(', ');
            document.getElementById('lat').value = latLng.lat();
            document.getElementById('lng').value = latLng.lng();
        }

        function updateMarkerAddress(str) {
            document.getElementById('autocomplete').value = str;
        }

        function initialize() {
            var latLng = new google.maps.LatLng(24.7255553, 47.1027146);
            map = new google.maps.Map(document.getElementById('mapCanvas'), {
                zoom: 8,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            marker = new google.maps.Marker({
                position: latLng,
                title: 'Point A',
                map: map,
                draggable: true
            });

            // Update current position info.
            updateMarkerPosition(latLng);
            geocodePosition(latLng);

            // Add dragging event listeners.
            google.maps.event.addListener(marker, 'dragstart', function() {
                updateMarkerAddress('Dragging...');
            });

            google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerStatus('Dragging...');
                updateMarkerPosition(marker.getPosition());
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                updateMarkerStatus('Drag ended');
                geocodePosition(marker.getPosition());
            });

            // Initialize autocomplete.
            var inputField = document.getElementById('autocomplete');
            autocomplete = new google.maps.places.Autocomplete(inputField);
            google.maps.event.addListener(
                autocomplete, 'place_changed',
                function() {
                    var place = autocomplete.getPlace();
                    if (place.geometry) {
                        var location = place.geometry.location;
                        map.panTo(location);
                        map.setZoom(12);
                        marker.setMap(map);
                        marker.setPosition(location);
                        updateMarkerPosition(marker.getPosition());
                    }
                });

            google.maps.event.addListener(map, 'idle', function() {
                autocomplete.setBounds(map.getBounds());
            });
        }
        // Updates autocomplete object.
        function updateOptions() {
            // Set types, if any.
            var desired_types = [];
            var types = document.controls.type;
            for (var i = 1; i < types.length; i++) {
                if (types[i].checked) {
                    desired_types = [types[i].value];
                    break;
                }
            }
            autocomplete.setTypes(desired_types);

            // Set country, if any.
            var country = document.controls.country.value;
            if (country) {
                autocomplete.setComponentRestrictions({
                    'country': country
                });
            } else {
                autocomplete.setComponentRestrictions({});
            }
        }

        // Onload handler to fire off the app.
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection