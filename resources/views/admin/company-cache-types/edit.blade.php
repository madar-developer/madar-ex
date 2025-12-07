
<div class="row">
    {!!Form::model($row,['url' => ['/dashboard/company-cache-types/'.$row->id] , 'method' => 'PATCH','files'=>true]) !!}
    @include('admin.company-cache-types.form')
    {!!Form::close() !!}
</div>