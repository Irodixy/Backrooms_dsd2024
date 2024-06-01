<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerProfileController extends Controller
{
	private $_dbSelect;
	private $_dbInsert;
	private $_dbUpdate;
	private $_dbDelete;
	private $_newArray;
	
	function UpdateOrInsert (Request $array)
	{
		$input = $array->all();
		
		if(array_key_exists("UserId", $input))
		{
			$this->_dbSelect = DB::select('SELECT ID
											FROM users 
											WHERE ID = ?', 
											[$input["UserId"]]);

			if(count($this->_dbSelect) == 1)
			{
				return $this->UpdateOwner($input);
			}
			else if(count($this->_dbSelect) == 0)
			{
				return $this->InsertOwner($input);
			}
			else
			{
				return json_decode('{"ERROR": "Something went wrong, please try again later"}');
			}
		}
		else
		{
			return json_decode('{"ERROR": "UserId needed, but not exist. Contact Admin!"}');
		}
	}
	
    function SeeOwner(Request $array)
	{
		$input = $array->all();
		
		if(!isset($input["OwnerName"]))
		{
			$input["OwnerName"] = "";
		}
		
		$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, s.ID, s.name AS StoreName
									FROM users u, store s
									WHERE s.IDOwner = u.ID
									AND u.username LIKE ?',
									['%' . $input["OwnerName"] . '%']);

		$StoreData = [];
		$save = [];
		$StoreLocation = "";
		$StoreFloor = "";
		$AvgRate = [];			
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
					$temporary = DB::select('SELECT l.latitude, l.longitude, l.floor
										FROM store s, location l
										WHERE s.ID = l.IDStore
										AND s.ID = ?', 
										[$x]);
										
										/* $temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
										FROM store s, location l
										WHERE s.ID = l.IDStore
										AND s.ID = ?', 
										[$x]); */ //ALL INFO IN TABLE LOCATION, IF EVERY NECESSARY TO SEND, USE THIS CODE!!!!
										
					foreach($temporary as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $y)
						{
							//ADAPTATION TO USE IN CODE OF OTHER GROUPS!!!
							if($keys == "latitude")
							{
								$StoreLocation = $y;
							}
							else if($keys == "longitude")
							{
								$StoreLocation = $StoreLocation . "," . $y;
							}
							else
							{
								$StoreFloor = $y;
							}
							//$StoreLocation[$keys] = $y;
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
					$save["StoreFloor"] = $StoreFloor;
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
		$newArray = array("StoreData" => $StoreData);
		return $newArray;
	}
	
	function UpdateOwner ($array)
	{
		if(array_key_exists("UserPassword", $array))
		{
			$array["UserPassword"] = password_hash($array["UserPassword"], PASSWORD_DEFAULT);
		}
		
		$temporary = DB::select('SELECT username
								FROM users 
								WHERE ID = ?', 
								[$array["UserId"]]);
								
		//ADAPT THE VALUES OF OTHER GROUPS TO BE USE BY MY CODE!!!!
		if(array_key_exists("StoreLocation", $array) AND array_key_exists("StoreFloor", $array))
		{
			$values_array = explode(',', $array["StoreLocation"]);
			$array["StoreLocation"] =[];
			if(array_key_exists("StoreLocation", $array))
			{
				$array["StoreLocation"]["latitude"] = $values_array[0];
				$array["StoreLocation"]["longitude"] = $values_array[1];
			}
			
			if(array_key_exists("StoreFloor", $array))
			{
				$array["StoreLocation"]["floor"] = $array["StoreFloor"];
				unset($array["StoreFloor"]);
			}
		}
		else if(array_key_exists("StoreLocation", $array))
		{
			$values_array = explode(',', $array["StoreLocation"]);
			$array["StoreLocation"] =[];
			
			$array["StoreLocation"]["latitude"] = $values_array[0];
			$array["StoreLocation"]["longitude"] = $values_array[1];
		}
		else if(array_key_exists("StoreFloor", $array))
		{
			$array["StoreLocation"] =[];
			$array["StoreLocation"]["floor"] = $array["StoreFloor"];
			unset($array["StoreFloor"]);
		}
	
		
		

		if (count($temporary) == 1)
		{
			if(array_key_exists("UserName", $array))
			{
				$username = DB::select('SELECT ID
									FROM users
									WHERE username = ?',
									[$array["UserName"]]);
									
				if(count($username) == 1)
				{
					foreach($username as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							if($array["UserId"] != $x)
							{
								return json_decode('{"ERROR": "Username already been used by other account"}');
							}	
						}
					}
				}
			}
			
			if(array_key_exists("Email", $array))
			{
				$email = DB::select('SELECT ID
									FROM users
									WHERE email = ?',
									[$array["Email"]]);
									
				if(count($email) == 1)
				{
					foreach($email as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							if($array["UserId"] != $x)
							{
								return json_decode('{"ERROR": "Email already been used by other account"}');
							}	
						}
					}
				}
			}
			
			if(array_key_exists("StoreName", $array))
			{
				$storename = DB::select('SELECT IDOwner
									FROM store
									WHERE name = ?',
									[$array["StoreName"]]);
									
				if(count($storename) == 1)
				{
					foreach($storename as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							if($array["UserId"] != $x)
							{
								return json_decode('{"ERROR": "Username already been used by other account"}');
							}	
						}
					}
				}
			}
			
			$ID = 24;
			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $array);
			foreach ( $temporary as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $array["UserId"], $array);
					if(is_string($values))
					{
						return json_decode($values);
					}
				}
			}
			//print_r($values);
			//echo $string;
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate >= 1)
			{
				$SuccessToken = true;
				$newArray = array("SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$newArray = array("SuccessToken" => $SuccessToken);
			}
		}
		else
		{
			$newArray = json_decode('{"ERROR": "User not Found!"}');
		}
		return $newArray;
	}
	
	function InsertOwner ($array)
	{
		$SuccessToken = "";
		
		$this->_dbSelect = DB::select('SELECT ID
										FROM users
										WHERE username = ?', 
										[$array["UserName"]]);
																	
		if(count($this->_dbSelect) == 0)
		{
			if(array_key_exists("UserPassword", $array))
			{
				$array["UserPassword"] = password_hash($array["UserPassword"], PASSWORD_DEFAULT);
			}
			
			$this->_dbInsert = DB::insert('INSERT into users (ID, username, password, type) 
											values (?, ?, ?, ?)', 
											[$array["UserId"], $array["UserName"], $array["UserPassword"], "owner"]);
											
			if($this->_dbInsert == 1)
			{
				$check = DB::select('SELECT ID 
									FROM users 
									WHERE username = ?', 
									[$array["UserName"]]);
				if(count($check) == 1)
				{
					$UserId = "";
					foreach($check as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $x)
						{
							$UserId = $x;
						}
					}
					
					$anotherTemporay = DB::insert('INSERT INTO store
											(name, IDOwner) 
											VALUES (?, ?)', 
											[$array["StoreName"], $UserId]);
											
					if($anotherTemporay == 1)
					{
						$anotherCheck = DB::select('SELECT ID 
													FROM store
													WHERE IDOwner = ?
													AND name = ?', 
													[$UserId, $array["StoreName"]]);
											
						if(count($anotherCheck) == 1)
						{
							$StoreId = "";
							foreach($anotherCheck as $Objs)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $x)
								{
									$StoreId = $x;
								}
							}
							
							//$values_array[0] is latitude and $values_array[1] is longitude!!!!!
							$values_array = explode(',', $array["StoreLocation"]);
							
							
							/* $location = DB::insert('INSERT into location 
													(IDStore, latitude, longitude, country, state, city, street, number, floor, zipcode) 
													VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
													[$check, $array["StoreLocation"]["latitude"], $array["StoreLocation"]["longitude"], $array["StoreLocation"]["country"],
													$array["StoreLocation"]["state"], $array["StoreLocation"]["city"], $array["StoreLocation"]["street"], 
													$array["StoreLocation"]["number"], $array["StoreLocation"]["floor"], $array["StoreLocation"]["zipcode"]]); */
													//THIS IS FULL INSERT OF LOCATION, BUT FOR NOW IS NOT IN USE
													
							$location = DB::insert('INSERT INTO location 
													(IDStore, latitude, longitude, floor) 
													VALUES (?, ?, ?, ?)', 
													[$StoreId, $values_array[0], $values_array[1], $array["StoreFloor"]]);
											
							if($location == 1)
							{
								$this->_newArray = array("SuccessToken" => true);
							}
							else
							{
								$this->_newArray = json_decode('{"ERROR": "Store location could not be inserted, please try again later"}');
							}
						}
						else
						{
							$this->_newArray = json_decode('{"ERROR": "Store should had been inserted, but returns null"}');
						}
					}
					else
					{
						$this->_newArray = json_decode('{"ERROR": "Store could not be inserted, please try again later"}');
					}
				}
				else
				{
					$this->_newArray = json_decode('{"ERROR": "Owner account should had been inserted, but returns null"}');
				}
			}
			else
			{
				$this->_newArray = json_decode('{"ERROR": "Owner account could not be inserted, please try again later"}');
			}	
		}
		else
		{
			$this->_newArray = json_decode('{"ERROR": "Owner account already exist"}');
		}
		return $newArray = $this->_newArray;
	}
	
	function DeleteOwner (Request $array)
	{
		$input = $array->all();
		
		$this->_dbDelete = DB::delete('DELETE FROM users
										WHERE ID = ?
										AND type = "owner"',
										[$input["UserId"]]);
												
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
