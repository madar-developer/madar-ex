@extends('company.layout.app')
@section('style')
<link href="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"  rel="stylesheet">
<link href="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" >
<link href="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
@endsection
@section('header')
                         
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/company/company-orders/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('company.orders.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
    <script src="{{ asset('/adminto/assets/plugins/moment/moment.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script>





    $(document).on('submit','.class1',function(){
        
           if($('select[name=receiving_city]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=same_zone]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=type_of_car]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=shipping_size]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=case]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=return_package]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=plug_type]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=payment_method]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=city]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=store_or_company]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
    });
    
    
    // Date Picker
jQuery('.datepicker').datepicker();
jQuery('.datepicker-autoclose').datepicker({
    autoclose: true,
    todayHighlight: true
});
jQuery('.timepicker').timepicker({
    defaultTIme : false
});

$(document).on('change', 'select[name=city]', function(){
        var cityID = $(this).val();    
        //alert(cityID);
        if(cityID){
            $.ajax({
            type:"GET",
            url:"{{url('dashboard/city-id')}}?city_id="+cityID,
            success:function(res){               
                if(res){
                    $("#cities").empty();
                    //$("#cities").append('<option>Select</option>');
                    $.each(res,function(key,value){
                        $("#cities").append('<option value="'+key+'">'+value+'</option>');
                    });
                    console.log(res);
            
                }else{
                $("#cities").empty();
                }
            }
            });
        }else{
            $("#cities").empty();

        }   
    });

</script>
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