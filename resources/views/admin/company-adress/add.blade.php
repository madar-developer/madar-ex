
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/company-addresses/' ,'method' => 'Post','files' => true]) !!}
                {!! Form::hidden('company_id', $company_id, []) !!}
                @include('admin.company-adress.form')
                {!!Form::close() !!}
</div>
<!-- end row -->
