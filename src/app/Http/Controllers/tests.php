<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class tests extends Controller
{
    function sendPostLogin () //TESTED
	{
		$array = ["InterfaceId"=> 1,
		"CurrentUser"=> "NULL",
		"UserName"=> "Paul",
		"PassWord"=> "ooooooo"];
	
		return Http::post('http://192.168.56.102/laravel/api/interface1', $array);
	}
	
	function sendPostSearchStore ($type = "0")
	{
		switch($type)
		{
			//SEARCH EMPTY NAME (TESTED)
			case 0:
				$array = ["InterfaceId"=> 3,
				"CurrentUser"=> "Paul",
				"storeName"=> ""];
			break;
			
			//SEARCH PART NAME (TESTED)
			case 1:
				$array = ["InterfaceId"=> 3,
				"CurrentUser"=> "Paul",
				"storeName"=> "on"];
			break;
			
			//SEARCH FULL NAME (TESTED)
			case 2:
				$array = ["InterfaceId"=> 3,
				"CurrentUser"=> "Paul",
				"storeName"=> "Continente"];
			break;
		}
		
		return Http::post('http://192.168.56.102/laravel/api/interface3', $array);
	}
	
	function sendPostHistory ()
	{
		$array = ["InterfaceId"=> 1,
		"CurrentUser"=> "Paul"];
	
		return Http::post('http://192.168.56.102/laravel/api/interface5', $array);
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
	
	function sendPostProfileCustomer ($type)
	{
		switch($type)
		{
		//update full
			case 1:
				$array = [
				"UserId"=> "test4",
				"UserName"=> "test4",
				"UserPassword"=> "oooooo",
				"Birthday"=> "",
				"Interests"=> 
				[
					"interest2"=> "1"
				],
				"Email"=> ""];
			break;
			//update part
			case 2:
				$array = [
				"UserId"=> "test4",
				"UserName"=> "test4",
				"UserPassword"=> "oooooo",
				"Birthday"=> "",
				"Interests"=> 
				[
					"interest2"=> "1"
				],
				"Email"=> ""];
			break;
		}
		
		return Http::post('http://192.168.56.102/laravel/api/interface10', $array);	
	}
	
	function sendPostRegistrationCustomer() 
	{
		$array = ["InterfaceId"=> 12,
		"CurrentUser"=> "NULL",
		"UserName"=> "Paul",
		"PassWord"=> "dsdsdsddsd"];
		
		return Http::post('http://192.168.56.102/laravel/api/interface12', $array);	
	}
	
	function sendPostOwnerRegistration() //TESTED
	{
		$array = [
		"UserName"=> "Higgs",
		"UserPassword"=> "hehehehe"];
		
		return Http::post('http://192.168.56.102/laravel/api/interface18', $array);	
	}
	
	function sendPostOwnerLogin() //TESTED
	{
		$array = [
		"UserName"=> "Higgs",
		"UserPassword"=> "hehehehe"];
		
		return Http::post('http://192.168.56.102/laravel/api/interface19', $array);	
	}
	
	function sendPostProfileUser ($type = "0")
	{
		switch($type)
		{
			//select base on random 2 number/letter (TESTED)
			case 0:
				$letter = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 2))), 1, 2);
				//$letter = "Paul";
				return Http::get('http://192.168.56.102/laravel/api/interface20/' . $letter);
			break;
			
			//insert full (TESTED)
			case 1:
				$array = [
				"UserId"=> 12,
				"UserName"=> "Bea",
				"UserPassword"=> "124578",
				"Birthday"=> "2004-12-28",
				"Interests"=> 
				[
					"interest1"=> 0,
					"interest2"=> 0,
					"interest3"=> 0
				],
				"Email"=> "belatrix@gmail.com"];
			break;
			//update full (TESTED)
			case 2:
				$array = [
				"UserId"=> 5,
				"UserName"=> "test44",
				"UserPassword"=> "hhfhfhfhfhf",
				"Birthday"=> "2015-02-02",
				"Interests"=> 
				[
					"interest1"=> "1",
					"interest2"=> "1",
					"interest3"=> "0"
				],
				"Email"=> "oi"];
			break;
			//update part (TESTED)
			case 3:
				$array = [
				"UserId"=> 5,
				"UserName"=> "test69",
				"Interests"=> 
				[
					"interest2"=> "1"
				],
				"Email"=> ""];
			break;
			
			//delete (TESTED)
			case 4:
				$array = [
				"UserId"=> 12];
				
				return Http::post('http://192.168.56.102/laravel/api/interface22', $array);	
			break;
		}

		return Http::post('http://192.168.56.102/laravel/api/interface21', $array);	
	}
	
	function sendPutProfileOwner ()
	{
		$array = [
        "InterfaceId"=> 24,
        "CurrentUser"=> "admin1",
        "UserId"=> 6,
        "UserName"=> "owner2",
        "UserPassword"=> "looooool",
		"StoreId" => 3,
        "StoreName"=> "Quinta de Santo Manuel",
        "StoreLocation"=> [
            "latitude"=> 41.30534628446056,
            "longitude"=> -7.709742110948364,
            "country" => "Espanha",
            "state" => "Novo",
            "city"=> "Vila Real",
            "street"=> "Rua das Quintass",
            "number"=> "8",
            "floor"=> "2",
            "zipcode"=> "1234-567"
        ]];
		return Http::post('http://192.168.56.102/laravel/api/interface24', $array);	
	}
	
	function sendPostAnalytics ()
	{
		$array = [];
		return Http::post('http://192.168.56.102/laravel/api/interface26', $array);	
	}
	
	function sendPostSeeProfileOwner ()
	{
		$array = [
		"UserName" => "owner2"];
		return Http::post('http://192.168.56.102/laravel/api/interface27', $array);	
	}
}