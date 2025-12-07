
<div class="row">
    {!!Form::model($invoice , ['url' => ['/dashboard/invoices/'.$invoice->id] , 'method' => 'PATCH','files'=>true]) !!}
    @include('admin.invoices.form')
    {!!Form::close() !!}
</div>
