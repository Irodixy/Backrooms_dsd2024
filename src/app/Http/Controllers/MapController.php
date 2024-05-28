<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    function DisplayMap (Request $array)
	{
		$input = $array->all();

		if(!array_key_exists("maxDistance", $input))
		{
			$input["maxDistance"] = 5;
		}
		
		$temporary = [];

		$this->_dbSelect = DB::select(
						'SELECT s.ID AS storeId, s.name AS storeName, s.type, s.description AS StoreDescription 
						FROM store s, location l  
						WHERE ( 6371 * acos( cos( radians(?) ) *
								cos( radians( l.latitude ) ) *
								cos( radians( l.longitude ) - radians(?) ) +
								sin( radians(?) ) *
								sin( radians( l.latitude ) ) ) ) <= ? 
								AND s.ID = l.IDStore 
						ORDER BY name ASC', 
						[$input["MyLocation"]["latitude"], $input["MyLocation"]["longitude"], 
						$input["MyLocation"]["latitude"], $input["maxDistance"]]);
				
		//Variables to save data and help reorganize the future JSON file			
		$StoreList = [];
		$location = [];
			$items = [];
			$save = [];
		
		switch($array["RequestType"])
		{
			//CUSTOMER SEES STORES WITHIN X DISTANCE
			case 1:
			//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					//store Id is necessary to get more necessary data from DB, so we use an "if"
					//to use this key value in all other select querys
					if($key === "storeId")
					{
						$save[$key] = $x;
						
						$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
											FROM store s, location l
											WHERE s.ID = l.IDStore
											AND s.ID = ?', 
											[$x]);
											
						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								foreach($Obj as $key => $y)
								{
									$location[$key] = $y;
								}
							}
						}
						else
						{
							$location = json_decode('{"ERROR": "location not found"}');
						}

						$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.image AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
											FROM item i, store s
											WHERE s.ID = ?
											AND i.IDStore = s.ID', 
											[$x]);

						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								$oneItem = [];
								foreach($Obj as $key => $y)
								{
									if($key == "ItemImage")
									{
										$oneItem[$key] = base64_encode($y);
									}
									else
									{
										$oneItem[$key] = $y;
									}
								}
								array_push($items, $oneItem);
							}
						}
						else
						{
							$items = json_decode('{"ERROR": "items not found"}');
						}
					}
					else if($key === "storeName")
					{
						$save[$key] = $x;
					}
					else if($key === "StoreDescription")
					{
						$save['location'] = $location;
						$save['items'] = $items;
						$save[$key] = $x;
						
						array_push($StoreList, $save);
					}
				}				
			}
			$this->_newArray = array("InterfaceId" => 7, "CurrentUser" => $array["CurrentUser"], "StoreList" => $StoreList);
			return $newArray = $this->_newArray;
		break;
		
		//USER SEES STORES RECOMENDED FROM AI
		case 2:
			//CODE TO REQUEST INFO FROM AI!!!!
			//WILL BE SAVED IN SOME VARIABLE OR SAME PROPERTY, AND THEN ALL CODE IS NORMAL FROM HERE!
			// WILL BE NECESSARY OPTIMIZATION OF THE CODE!!! VERY REPETIVE!!
		
		
		//SELECT FOR INTERFACE 13!!!!!!
		
			//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					//store Id is necessary to get more necessary data from DB, so we use an "if"
					//to use this key value in all other select querys
					if($key === "storeId")
					{
						$save[$key] = $x;
						
						$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.image AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
											FROM item i, store s
											WHERE s.ID = ?
											AND i.IDStore = s.ID', 
											[$x]);

						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								$oneItem = [];
								foreach($Obj as $key => $y)
								{
									$oneItem[$key] = $y;
								}
								array_push($items, $oneItem);
							}
						}
						else
						{
							$items = array("ERROR, item not found");
						}
					}
					else if($key === "storeName")
					{
						$save[$key] = $x;
					}
					else if($key === "StoreDescription")
					{
						$save['items'] = $items;
						$save[$key] = $x;
						
						array_push($StoreList, $save);
					}
				}				
			}
			
			$pickStores = array("InterfaceId" => 13, "CurrentUser" => $array["CurrentUser"], "StoreList" => $StoreList);
			
			$SuccessToken = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $pickStores);
			
			if ($SuccessToken == false)
			{
				return "Fail to send information to server. Please try again later";
			}
			
			
			//Select info of USER FOR INTERFACE 14
			
			$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, u.email AS UserEmail 
											FROM users u
											WHERE u.username = ?', 
											[$array["CurrentUser"]]);
			
			//Variables to save data and help reorganize the future JSON file			
			$UserData = [];
			$Interests = [];
			$HuntedStoreIdList = [];
			$save = [];
				
			//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					//store Id is necessary to get more necessary data from DB, so we use an "if"
					//to use this key value in all other select querys
					if($key === "UserId")
					{
						$save[$key] = $x;
						
						$temporary = DB::select('SELECT i.*
											FROM users u, interests i
											WHERE u.ID = ?
											AND u.ID = i.IDUser', 
											[$x]);
											
						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								foreach($Obj as $key => $y)
								{
									$Interests[$key] = $y;
								}
							}
						}
						else
						{
							$Interests = "ERROR, interests not found";
						}
						//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
						$values = array_values($Interests);
						$string = implode(',', $values);

						$temporary = DB::select('SELECT IDStore AS StoreId, date_time AS VisitTime
											FROM huntedstore
											WHERE IDUser = ?', 
											[$x]);

						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								$oneSearch = [];
								foreach($Obj as $key => $y)
								{
									$oneSearch[$key] = $y;
								}
								array_push($HuntedStoreIdList, $oneSearch);
							}
						}
						else
						{
							$HuntedStoreIdList = array("ERROR, item not found");
						}
					}
					else 
					{
						$save[$key] = $x;
					}
				}				
			}
			
			$save['Interests'] = $string;
			$save['HuntedStoreIdList'] = $HuntedStoreIdList;

			array_push($UserData, $save);
			
			$pickUser = array("InterfaceId" => 14, "CurrentUser" => $array["CurrentUser"], "UserData" => $UserData);
									
			$SuccessToken = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $pickUser);
			
			if ($SuccessToken == false)
			{
				return "Fail to send information to server. Please try again later";
			};
			
			//AFTER SENDING ALL THE NEED INFO, REQUEST A GET TO RECEIVE THE NECESSARY RECOMMENDATION!!!
			
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
