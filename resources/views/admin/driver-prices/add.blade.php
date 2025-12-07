
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/driver-prices/' ,'method' => 'Post','files' => true]) !!}
                {!! Form::hidden('driver_id', $driver_id, []) !!}
                @include('admin.driver-prices.form')
                {!!Form::close() !!}
</div>
<!-- end row -->
