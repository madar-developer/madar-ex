<?php 


function getMsgCode($msg = 'success')
{
	$codes = [
		'success'   		         	=> 200,
		'validationErrors'	         	=> 402,
		'somethingWrong'	         	=> 100,
		'invalidApiToken'            	=> 111,
		'notFound'						=> 404, //something not found like an ad
		'notUser'						=> 409, //something not found like an ad
		'authFailed'					=> 103,
		'tokenNotFound'	    	     	=> 115,
		'wrongPhoneVerifyNum'			=> 405,
		'wrongForgetPassVerifyNum'		=> 406,
		'waitBeforeResend'				=> 410,
		'error'							=> 409,
		'allAreBooked'					=> 505,
		'no_unit_id'					=> 601,
		'block'							=> 602,
		'driver_basic_info'				=> 603,
		'driver_not_reviewed'			=> 614,
		'driver_not_active'				=> 615,
		'driver_bus_notFound'			=> 604,
		'not_has_trip'					=> 605,
	];

	return $codes[$msg];
}



 ?>