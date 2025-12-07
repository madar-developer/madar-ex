
<style type="text/css">
    #mapCanvas{
        width: 100%;
        height: 300px;
    }
</style>
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/company/company-company-addresses/' ,'method' => 'Post','files' => true]) !!}
                {!! Form::hidden('company_id', $company_id, []) !!}
                @include('company.company-adress.form')
                {!!Form::close() !!}
</div>
<!-- end row -->
