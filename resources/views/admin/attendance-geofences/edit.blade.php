@extends('admin.layout.app')
@section('style')
<style type="text/css">
    #mapCanvas { width: 100%; height: 400px; margin-top: 10px; }
</style>
@endsection
@section('content')
<div class="row">
    {!! Form::model($geofence, ['url' => ['/dashboard/attendance-geofences/' . $geofence->id], 'method' => 'PATCH']) !!}
    @include('admin.attendance-geofences.form')
    {!! Form::close() !!}
</div>
@endsection
@section('script')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY&language=ar"></script>
<script type="text/javascript">
    var map, marker, circle, autocomplete;
    var defaultLat = {{ $geofence->latitude ?: 24.7255553 }};
    var defaultLng = {{ $geofence->longitude ?: 47.1027146 }};

    function updateCircle() {
        var radius = parseInt(document.getElementById('radius_meters').value) || 100;
        if (circle) circle.setMap(null);
        if (marker) {
            circle = new google.maps.Circle({
                map: map,
                center: marker.getPosition(),
                radius: radius,
                fillColor: '#4285F4',
                fillOpacity: 0.2,
                strokeColor: '#4285F4',
                strokeOpacity: 0.8,
                strokeWeight: 2
            });
        }
    }

    function updateMarkerPosition(latLng) {
        document.getElementById('lat').value = latLng.lat();
        document.getElementById('lng').value = latLng.lng();
        updateCircle();
    }

    function initialize() {
        var latLng = new google.maps.LatLng(defaultLat, defaultLng);
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 15,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: 'مركز الدائرة'
        });
        updateMarkerPosition(latLng);

        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerPosition(marker.getPosition());
        });

        document.getElementById('radius_meters').addEventListener('input', updateCircle);

        var inputField = document.getElementById('autocomplete');
        autocomplete = new google.maps.places.Autocomplete(inputField);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (place.geometry) {
                var location = place.geometry.location;
                map.panTo(location);
                map.setZoom(16);
                marker.setPosition(location);
                updateMarkerPosition(location);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
