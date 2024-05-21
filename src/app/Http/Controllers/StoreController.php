<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
	private $_dbInsert;
	
    function SeeStore ($storeName)
	{
		$temporary = [];
		
		//Select info of STORE
				$this->_dbSelect = DB::select('SELECT s.ID AS storeId, s.name AS storeName, s.type, s.description AS StoreDescription 
												FROM store s
												WHERE s.name LIKE ?', 
												[$storeName]);
				
				//Variables to save data and help reorganize the future JSON file			
				$StoreList = [];
				$location = [];
					$items = [];
					$save = [];
					
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
								$location = "ERROR, location not found";
							}

							$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.imgName AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
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
							$save['location'] = $location;
							$save['items'] = $items;
							$save[$key] = $x;
							
							array_push($StoreList, $save);
						}
					}				
				}
				$this->_newArray = array("InterfaceId" => 3, "StoreList" => $StoreList);
				return $newArray = $this->_newArray;
	}
	
	function SaveHistory(Request $array) 
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
		
		$this->_dbInsert = DB::insert('INSERT INTO huntedstore (IDUser, IDStore) values(?, ?)', 
										[$save["userId"], $input["HuntedStoreIdList"]["StoreId"]);
		if($this->_dbInsert == 1)
		{
			$SuccessToken = true;
			$this->_newArray = array("InterfaceId" => 4, "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("InterfaceId" => 4, "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
		}
		return $newArray = $this->_newArray;
	}
}
