@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/worktimes/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('admin.work-times.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
<script>
 
</script>
@endsection