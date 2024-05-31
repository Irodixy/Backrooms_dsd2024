<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FeedbackController extends Controller
{
    function SaveFeedbackStore (Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		$save = [];
			
		//do a try (still not done)
		$temporary = DB::select('SELECT ID AS userId
							FROM users 
							WHERE username = ?', 
							[$input["CurrentUser"]]);
		
		foreach($temporary as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				$save[$key] = $x;
			}
		}

		$this->_dbInsert = DB::insert('insert into feedback (IDUser, IDStore, comment, rating) 
					values (?, ?, ?, ?)', 
					[$save["userId"], $input["Feedback"]["StoreId"], $input["Feedback"]["comment"], $input["Feedback"]["rating"]]);
					
		if($this->_dbInsert == 1)
		{
			$input["InterfaceId"] = 17;
			$input["Feedback"]["UserId"] = $save["userId"];
			
			$ItemsData = $this->AINeedsItems($input);
			
			
			
			$LocationData = $this->AINeedsLocation($input);
			
			$address = $LocationData["number"] . " " . $LocationData["street"] . ", " . $LocationData["city"] . ", " . $LocationData["zipcode"];
			
			$location = array("latitude" => $LocationData["latitude"], "longitude" => $LocationData["longitude"], "address" => $address);
			
			$StoreData = $this->AINeedsStore($input);
			
			$StoreInfo = array("storeId" => $input["Feedback"]["StoreId"], "StoreName" => $StoreData["name"], "location" => $location, "items" => $ItemsData, "storeDescription" => $StoreData["description"]);
			
			
			
			
			
			
			$interface17 = $input;
			unset($interface17["Feedback"]);
			$interface17["Feedback"] = array("Store" => $StoreInfo, "comment" => $input["Feedback"]["comment"], "rating" => $input["Feedback"]["rating"], "UserId" => $save["userId"]);
			
			//print_r($interface17);
			$sendFeedbackStore = Http::post('https://u69070-9b28-34756fc5.westb.seetacloud.com:8443/require_recommendation', $interface17);
			print_r($sendFeedbackStore);
			if ($sendFeedbackStore)
			{
				$AiReturn = true;
				
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken, "AiReturn" => $AiReturn);
			}
			else
			{
				$AiReturn = json_decode('{"ERROR": "algorithm didn\'t receive the data"}');
				
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken, "AiReturn" => $AiReturn);
			}
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		//return $newArray = $this->_newArray;
	}
	
	function SaveFeedbackItem (Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		$save = [];
			
		//do a try (still not done)
		$temporary = DB::select('SELECT ID AS userId
							FROM users 
							WHERE username = ?', 
							[$input["CurrentUser"]]);
		
		foreach($temporary as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				$save[$key] = $x;
			}
		}
	
		$this->_dbInsert = DB::insert('INSERT into feedback (IDUser, IDItem, comment, rating) 
					values (?, ?, ?, ?)', 
					[$save["userId"], $input["Feedback"]["ItemId"], $input["Feedback"]["comment"], $input["Feedback"]["rating"]]);
					
		if($this->_dbInsert == 1)
		{
			$SuccessToken = true;
			$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		return $newArray = $this->_newArray;
	}
	
	function AINeedsItems ($array)
	{
		$ItemList = [];
		
		$this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.type AS ItemCategory, i.description AS ItemDescription
										FROM item i
										WHERE i.IDStore = ?', 
										[$array["Feedback"]["StoreId"]]);
												
		if(count($this->_dbSelect) > 0)
		{						
			foreach($this->_dbSelect as $Obj)
			{
				$oneItem = [];
				foreach($Obj as $key => $x)
				{
						$oneItem[$key] = $x;
				}
				array_push($ItemList, $oneItem);
			}
		}
		else
		{
			$ItemList = json_decode('{"ERROR": "No item found!"}');
		}
		return $ItemList;
	}
	
	function AINeedsLocation ($array)
	{
		$location = [];
		
		$this->_dbSelect = DB::select('SELECT street, number, city, zipcode, latitude, longitude
										FROM location
										WHERE IDStore = ?',
										[$array["Feedback"]["StoreId"]]);
										
		if(count($this->_dbSelect) > 0)
		{						
			foreach($this->_dbSelect as $Obj)
			{
				foreach($Obj as $key => $x)
				{
					$location[$key] = $x;
				}
			}
		}
		else
		{
			$location = json_decode('{"ERROR": "No item found!"}');
		}
		return $location;
	}
	
	function AINeedsStore ($array)
	{
		$store = [];
		
		$this->_dbSelect = DB::select('SELECT description, name
										FROM store
										WHERE ID = ?',
										[$array["Feedback"]["StoreId"]]);
										
		if(count($this->_dbSelect) > 0)
		{						
			foreach($this->_dbSelect as $Obj)
			{
				foreach($Obj as $key => $x)
				{
					$store[$key] = $x;
				}
			}
		}
		else
		{
			$store = json_decode('{"ERROR": "No item found!"}');
		}
		return $store;
	}
}
