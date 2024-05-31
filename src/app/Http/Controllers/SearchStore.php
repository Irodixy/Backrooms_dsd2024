<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchStore extends Controller
{
	private $_dbSelect;
	private $_newArray;
	
    function SearchStore(Request $array)
	{
		$input = $array->all();
		
		$MatchToken = "";
		$temporary = [];
		
		if(!array_key_exists("StoreName", $input))
		{
			$input["StoreName"] = "";
		}
		//Select info of STORE
				$this->_dbSelect = DB::select('SELECT s.ID AS storeId, s.name AS storeName, s.type, s.description AS StoreDescription 
												FROM store s
												WHERE s.name LIKE ?', 
												['%' . $input["StoreName"] . '%']);
				
				//Variables to save data and help reorganize the future JSON file			
				$StoreList = [];
				$location = [];
					$items = [];
					$save = [];
			if(count($this->_dbSelect) > 0)
			{				
				//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						//store Id is necessary to get more necessary data from DB, so we use an "if"
						//to use this key value in all other select querys
						if($key == "storeId")
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

							$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.image AS ItemImage, i.base_image,  i.IDStore AS ItemStoreId, s.name AS ItemStoreName
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
										else if($key == "base_image")
										{
											$oneItem["ItemImage"] = $y . ',' . $oneItem["ItemImage"];
										}
										else
										{
											$oneItem[$key] = $y;
										}
									}
									print_r($oneItem);
									array_push($items, $oneItem);
								}
							}
							else
							{
								$items = json_decode('{"ERROR": "item not found!"}');
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
			}
			else
			{
				json_decode('{"ERROR": "No Store found!"}');
			}
		$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"] ,"StoreList" => $StoreList);
		
		return $newArray = $this->_newArray;
	}
}
