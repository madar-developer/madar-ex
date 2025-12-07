<div class="row">
    {!!Form::open( ['url' => '/dashboard/transfers/' ,'method' => 'Post','files' => true]) !!}
    @include('admin.transfers.form')
    {!!Form::close() !!}
</div>