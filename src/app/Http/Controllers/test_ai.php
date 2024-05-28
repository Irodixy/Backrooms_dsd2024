<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class test_ai extends Controller
{
	function interface7Simulation()
	{
		
	}
	
    function interface13 (Request $x)
	{
		$input = $x->all();
		
		$array = json_decode('{
		"InterfaceId": 7,
		"CurrentUser": "Paul",
		"MyLocation": 
		{
			"latitude": ' . $input["MyLocation"]["latitude"] . ',
			"longitude": ' . $input["MyLocation"]["longitude"] . '
		},
		"RequestType": 1
		}');

		$save = Http::post('http://13.79.99.190/api/interface7', $array);
		//$save = Http::post('http://192.168.56.102/laravel/api/interface7', $array);
		$pickStores = $save->json();
		$pickStores["InterfaceId"] = 13; 
			
		$save = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $pickStores);
		$SuccessToken = $save->json();
		if(count($SuccessToken) > 0
		{
			if ($SuccessToken == false)
			{
				return json_decode('{"SuccessMsg": "AI received, but something didn\'t work. Please try again later!"}');
			}
			else
			{
				return json_decode('{"SuccessMsg": "AI received and successfuly used the data!"}');
			}
		}
		else
		{
			return json_decode('{"ERROR": "Fail to send information to server. Please try again later"}');
		}
	}
	
	function interface14 ()
	{
		
	}
	
	function interface16 ()
	{
		
	}
	
	function interface17 ()
	{
	
	}
}
