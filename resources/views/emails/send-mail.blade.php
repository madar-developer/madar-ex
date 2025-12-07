@extends('emails.layout')
@section('content')
 <?php if (isset($data['msg'])) { ?>
 	<p> {{ @$data['msg'] }}.</p>
 <?php }else {
  if (@$data['password']) {	?>



<p> New Password {{ @$data['password'] }}.</p>

<?php } } ?>

<p style="font-weight: 500;"> Thanks</p>
@endsection
