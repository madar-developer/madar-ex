
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
<div class="row">
                {!!Form::model($row , ['url' => ['/dashboard/driver-prices/'.$row->id] , 'method' => 'PATCH','files'=>true]) !!}
                @include('admin.driver-prices.form')
                {!!Form::close() !!}
</div>