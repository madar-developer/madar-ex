@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
<!-- Page title -->
{{--  <li> 
    <div class="add-btn">
        <a href="{{ url('/dashboard/users/create') }}" type="submit" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
    </div> 
</li>  --}}
                         
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
    {{--  <div class="card-box">  --}}
                {!!Form::open( ['url' => '/dashboard/users/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('admin.users.form')
                {!!Form::close() !!}
    {{--  </div>  --}}
</div>
<!-- end row -->

@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdF7p8rFeZiKlle55Wou-a0LYxMv6A52k" async defer></script>




<script type="text/javascript">

    $(document).ready(function(){
        // var vip = $('input[name=vip]').val();
        if ($('input[name=vip]').checked) {
            $('.vip-account').show();
        }else{
            $('.vip-account').hide();
        }
    });


    
$(document).on('submit','.class1',function(){
    if($('select[name=country]').val() === ''){
     $(".append").append('<p style="color:red;"> this field is required </p>');
        return false;
    }
    if($('select[name=city]').val() === ''){
        $(".append").append('<p style="color:red;"> this field is required </p>');
           return false;
       }
       if($('select[name=user_type]').val() === ''){
        $(".append").append('<p style="color:red;"> this field is required </p>');
           return false;
       }
}); 





    function switchaerych(o){
        // var vip = $(this).val();
        if (o.checked) {
            $('.vip-account').show();
        }else{
            $('.vip-account').hide();
        }
    }
    </script>
<script type="text/javascript">
    var map = null;
    var marker = null;
        function initMap() {
            $('#mapContainer').slideDown(function(){
                map = new google.maps.Map(document.getElementById('map'), {
                  center: {lat: 26.8206, lng: 30.8025},
                  zoom: 6,
                  disableDoubleClickZoom: true,
                });

                google.maps.event.addListener(map, 'click', function(event) {
                   placeMarker(event.latLng);
                });
            });        
        }


        function placeMarker(location) {
            
            if(marker !== null){
                marker.setMap(null);
            }            
            updateLocation(location.lat(),location.lng());
            marker = new google.maps.Marker({
                position: location, 
                map: map
            });
            marker.addListener('click', function(){
                updateLocation(0,0);
                marker.setMap(null);
            });
        }

        function updateLocation(lat,lng){
            $('#lat').val(lat);
            $('#lng').val(lng);
        }


</script>

// to make the elecet field is required

 

    /////////////////////////////////
<script type="text/javascript">
    var mapD = null;
    var markerD = null;
        function initMapD() {
            $('#mapContainerD').slideDown(function(){
                mapD = new google.maps.Map(document.getElementById('mapD'), {
                  center: {lat: 26.8206, lng: 30.8025},
                  zoom: 6,
                  disableDoubleClickZoom: true,
                });

                google.maps.event.addListener(mapD, 'click', function(event) {
                   placeMarkerD(event.latLng);
                });
            });        
        }


        function placeMarkerD(location) {
            
            if(markerD !== null){
                markerD.setMap(null);
            }            
            updateLocationD(location.lat(),location.lng());
            markerD = new google.maps.Marker({
                position: location, 
                map: mapD
            });
            markerD.addListener('click', function(){
                updateLocationD(0,0);
                markerD.setMap(null);
            });
        }

        function updateLocationD(lat,lng){
            $('#lat1').val(lat);
            $('#lng1').val(lng);
        }


        



</script>
@endsection