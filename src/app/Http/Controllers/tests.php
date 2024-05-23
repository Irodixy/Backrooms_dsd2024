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
	
	function sendPostUpdateProfileOwner () //ACTUALLY, ONLY UPDATES STORE INFO!!!!
	{
		$array = [
		"UserName" => "owner2",
		"StoreName" => "Continente",
		"StoreLocation" => "43.949735,125.439307",
		"StoreFloor" => "111"];
		return Http::post('http://192.168.56.102/laravel/api/interface28', $array);	
	}
	
	function sendPostSeeItems ()
	{
		$array = [
		"UserName" => "owner2",
		"StoreName" => "Continente",
		"StoreLocation" => "43.949735,125.439307",
		"StoreFloor" => "111"];
		return Http::post('http://192.168.56.102/laravel/api/interface29', $array);	
	}
	
	function sendPostUpdateItems ()
	{
		$array = [
		"UserName" => "owner",
		"ItemId" => "4",
		"ItemName" => "foto1",
		"ItemPrice" => "111",
		"ItemDescription" => "macacadas e tentativa de por imagem",
		"ItemImage" => "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"];
		return Http::post('http://192.168.56.102/laravel/api/interface30', $array);	
	}
	
	function sendPostInsertItems ()
	{
		$array = [
		"UserName" => "owner2",
		"StoreName" => "Continente",
		"StoreLocation" => "43.949735,125.439307",
		"StoreFloor" => "111"];
		return Http::post('http://192.168.56.102/laravel/api/interface30', $array);	
	}
	
	function sendPostDeleteItems ()
	{
		$array = [
		"UserName" => "owner2",
		"StoreName" => "Continente",
		"StoreLocation" => "43.949735,125.439307",
		"StoreFloor" => "111"];
		return Http::post('http://192.168.56.102/laravel/api/interface31', $array);	
	}
}