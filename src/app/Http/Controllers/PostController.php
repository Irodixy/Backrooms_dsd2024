<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    function PostRequest($ID, $array)
	{
		switch($ID)
		{
			case 13:
				$response = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $array);
			break;
			
			case 14:
				$response = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $array);
			break;
			
			case 17:
				$response = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $array);
			break;
		}
		
	}
	
	function GetRequest($ID)
	{
		switch($ID)
		{
			case 16:
				$response = Http::get('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation');

				if ($response->ok()) 
				{
				  return $response;
				} 
				else 
				{
				  return ("Recommendation not possible for error of the system, please try again later :)");
				}
			break;
		}
	}
}
