<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerProfileController extends Controller
{
    function SeeOwner($ownerName = "")
	{
		$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, s.ID, s.name AS StoreName
									FROM users u, store s
									WHERE s.IDOwner = u.ID
									AND u.username = ?',
									['%' . $ownerName . '%']);

		$StoreData = [];
		$save = [];
		$StoreLocation = [];
		$AvgRate = [];			
		print_r($this->_dbSelect);
		foreach($this->_dbSelect as $Obj)
		{
			$i = 0;
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				if($key != "ID")
				{
					$save[$key] = $x;
				}
				else 
				{
					$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
										FROM store s, location l
										WHERE s.ID = l.IDStore
										AND s.ID = ?', 
										[$x]);
										
					foreach($temporary as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $y)
						{
							$StoreLocation[$keys] = $y;
						}
					}
					
					$temporary = DB::select('SELECT AVG(rating) AS AvgRate
										FROM store s, feedback f
										WHERE s.ID = f.IDStore
										AND s.ID = ?', 
										[$x]);
										
					foreach($temporary as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $y)
						{
							$AvgRate = $y;
						}
					}
					
					$save["StoreLocation"] = $StoreLocation;
					$save["AvgRate"] = $AvgRate;
				}

				if($i >= 3)
				{
					array_push($StoreData, $save);
					$i = 0;
				}
				else
				{
					$i++;
				}
			}
		}
		$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
								"StoreData" => $StoreData);
		return $newArray = $this->_newArray;
	}
	
	function UpdateUser (Request $array)
	{
		$temporary = DB::select('SELECT ID
										FROM users 
										WHERE username = ? and password = ?', 
										[$array["UserName"], $array["UserPassword"]]);

		if (count($temporary) == 1)
		{
			//Possible UPDATE!!!! ->build IT before going in here!
			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $array);
			foreach ( $temporary as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $array);
				}
			}
			print_r($values);
			echo $string;
			
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
		}
		else
		{
			$this->_dbUpdate = "ERROR, User not Found!";
		}
		return $newArray = $this->_newArray;	
	}
	
	function InsertUser (Request $array)
	{
		$temporary = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$array["UserName"]]);
		if(!$temporary)
		{
			$this->_dbInsert = DB::insert('INSERT into users (username, password, type) 
											values (?, ?, ?)', 
											[$array["username"], $array["password"], "owner"]);
											
			if($this->_dbInsert == 1)
			{
				$OwnerAccount = true;
			}
			else
			{
				$OwnerAccount = false;
			}
			
			$this->_dbInsert = DB::insert('INSERT into store
											(name, type) 
											VALUES (?, ?, ?, ?)', 
											[$array["storeName"], $array["storeType"]);
											
			if($this->_dbInsert == 1)
			{
				$Store = true;
			}
			else
			{
				$Store = false;
			}
			
			$check = DB::select('"SELECT ID 
								FROM store 
								WHERE name = ?', 
								[$array["storeName"]]);
								
			if($check == 1)
			{
				$this->_dbInsert = DB::insert('INSERT into location 
											(IDStore, latitude, longitude, country, state, city, street, number, floor, zipcode) 
											VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
											[$check, $array["StoreLocation"]["latitude"], $array["StoreLocation"]["longitude"], $array["StoreLocation"]["country"],
											$array["StoreLocation"]["state"], $array["StoreLocation"]["city"], $array["StoreLocation"]["street"], 
											$array["StoreLocation"]["number"], $array["StoreLocation"]["floor"], $array["StoreLocation"]["zipcode"]]);
											
				if($this->_dbInsert == 1)
				{
					$StoreLocation = true;
				}
				else
				{
					$StoreLocation = false;
				}							
			}
			else
			{
				$Store = false;
				$StoreLocation = false;
			}
			
			$this->_newArray = array("OwnerAccount" => $OwnerAccount, "StoreLocation" => $StoreLocation, 
									"Store" => $Store);
			return $newArray = $this->_newArray;
		}
		else
		{
			return "ERROR";
		}
	}
	
	function DeleteUser ($UserId)
	{
		$this->_dbDelete = DB::delete('DELETE FROM users
												WHERE ID = ?',
												[$UserId]);
												
		if($this->_dbDelete == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => 22, "SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => 22, "SuccessToken" => $SuccessToken);
			}
		return $newArray = $this->_newArray;
	}
}
