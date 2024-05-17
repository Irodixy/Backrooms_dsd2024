<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class tests extends Controller
{
    function sendPostLogin ()
	{
		$array = ["InterfaceId"=> 1,
    "CurrentUser"=> "NULL",
    "UserName"=> "Paul",
    "PassWord"=> "ooooooo"];
		return Http::post('http://192.168.56.102/laravel/api/login', $array);
		
	}
	
	function sendPostMap ()
	{
		$array = [
        "InterfaceId"=> 7,
        "CurrentUser"=> "john_doe",
        "MyLocation"=> 
		[
			"latitude"=> 41.306238821053235,
			"longitude"=> -7.682987726801529
		]
		,
		"maxDistance"=> 4,
        "RequestType"=> "1"];
		return Http::post('http://192.168.56.102/laravel/api/map', $array);	
	}
	
	function sendPutProfileCustomer ()
	{
		$array = [
        "InterfaceId"=> 10,
        "CurrentUser"=> "test4",
		"UserName"=> "test4",
		"PassWord"=> "oooooo",
        "Interests"=> 
		[
			"interest2"=> "0"
		]];
		return Http::put('http://192.168.56.102/laravel/api/profile_customer', $array);	
	}
}
