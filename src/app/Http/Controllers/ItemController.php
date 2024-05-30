<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
	private $_bdSelect;
	private $_newArray;
	private $_dbUpdate;
	private $_dbDelete;
	
    function UpdateOrInsert (Request $array)
	{
		$input = $array->all();
		$this->_dbSelect = DB::select('SELECT ID
										FROM item 
										WHERE ID = ?', 
										[$input["ItemId"]]);

		if(count($this->_dbSelect) == 1)
		{
			return $this->UpdateItem($input);
		}
		else if(count($this->_dbSelect) == 0)
		{
			return $this->InsertItem($input);
		}
		else
		{
			return $this->_newArray = json_decode('{"ERROR": "Something went wrong, please try again later"}');
		}
		
	}
	
	function SeeItem (Request $array)
	{
		$input = $array->all();
		
		//THIS ONE IS THE ADAPTATION FOR THE CODE WITH OTHER GROUPS
		$this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.image AS ItemImage, i.base_image
										FROM item i, store s, users u
										WHERE i.IDStore = s.ID
										AND s.IDOwner = u.ID
										AND u.username = ?', 
										[$input["UserName"]]);
										
		/* $this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.imgName AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
												FROM item i, store s
												WHERE s.ID = ?
												AND i.IDStore = s.ID', 
												[$input["UserName"]]); */ // THIS ONE IS READY FOR OWNER WITH MORE THEN ONE STORE!!!!!!								
		
		$ItemData = [];
		
		if(count($this->_dbSelect) >= 1)
		{
			//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
			foreach($this->_dbSelect as $Obj)
			{
				$oneItem = [];
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					if($key == "ItemImage")
					{
						$oneItem[$key] = base64_encode($x);
					}
					else if($key == "base_image")
					{
						$oneItem["ItemImage"] = $x . ',' . $oneItem["ItemImage"];
					}
					else
					{
						$oneItem[$key] = $x;
					}
				}
				array_push($ItemData, $oneItem);
			}
			$this->_newArray = array("ItemData" => $ItemData);
		}
		else
		{
			$this->_newArray = json_decode('{"ERROR": "items not found"}');
		}
		
		return $newArray = $this->_newArray;									
	}
	
	function InsertItem($array)
	{
		$this->_dbSelect = DB::select('SELECT s.ID
										FROM users u, store s
										WHERE u.username = ?
										AND u.ID = s.IDOwner', 
										[$array["UserName"]]); //ONLY POSSIBLE IF OWNER AS ONLY ONE STORE!!!
															   //FUTURE UPGRADE SHOULD NOT NEED THIS, AND USE ONLY IDStore FOR IDENTIFICATION!!!!!!!
										
		if(count($this->_dbSelect) == 1)
		{
			if(array_key_exists("ItemImage", $array))
			{
				//IMAGE IS CONVERTED IN VARBINARY!!!!
				$prepareImage = explode(',', $array["ItemImage"]);
				$temporary = base64_decode($prepareImage[1]);
				$array["ItemImage"] = $temporary;
			}
			else
			{
				$array["ItemImage"] = "Not Found";
				$prepareImage[0] = NULL;
			}
			
			if(!array_key_exists("ItemDescription", $array))
			{
				$array["ItemDescription"] = NULL;
			}
			
			foreach($this->_dbSelect as $Objs)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Objs as $keys => $x)
				{
					$this->_dbInsert = DB::insert('INSERT INTO item (ID, name, price, description, image, base_image, IDStore) 
													values (?, ?, ?, ?, ?, ?, ?)', 
													[$array["ItemId"] , $array["ItemName"], $array["ItemPrice"], $array["ItemDescription"], 
													$array["ItemImage"], $prepareImage[0], $x]);
													
					if($this->_dbInsert == 1)
					{
						$SuccessToken = true;
						$this->_newArray = array("SuccessToken" => $SuccessToken);
					}
					else
					{
						$SuccessToken = false;
						$this->_newArray = array("SuccessToken" => $SuccessToken);
					}
				}
			}
		}					
		else
		{
			$this->_newArray = json_decode('{"ERROR": "username of Owner not found!"}');
		}

		return $newArray = $this->_newArray;
	}
	
	function UpdateItem($array)
	{
		$this->_dbSelect = DB::select('SELECT ID
										FROM item 
										WHERE ID = ?', 
										[$array["ItemId"]]);

		if (count($this->_dbSelect) == 1)
		{
			if(array_key_exists("ItemImage", $array))
			{
				//IMAGE IS SAVED IN VARBINARY!!!!
				$prepareImage = explode(',', $array["ItemImage"]);
				$temporary = base64_decode($prepareImage[1]);
				$array["ItemImage"] = $temporary;
				$array["ItemBase_image"] = $prepareImage[0];
			}
			
			$ID = 30;
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $array);
			foreach ($this->_dbSelect as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $array);
				}
			}
			/*print_r($values);
			echo($string);*/
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("SuccessToken" => $SuccessToken);
			}
		}
		else
		{
			$this->_newArray = json_decode('{"ERROR": "Item not Found!"}');
		}
		return $newArray = $this->_newArray;	
	}
	
	function DeleteItem(Request $array)
	{
		$input = $array->all();
		
		$this->_dbDelete = DB::delete('DELETE FROM item
										WHERE ID = ?',
										[$input["ItemId"]]);
												
		if($this->_dbDelete == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("SuccessToken" => $SuccessToken);
			}
		return $newArray = $this->_newArray;
	}
}
