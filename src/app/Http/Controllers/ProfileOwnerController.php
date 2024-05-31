<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//THIS CONTROLLER IS FOR STORE UPDATES ONLY!!!!
class ProfileOwnerController extends Controller
{
	private $_dbSelect;
	private $_newArray;
	private $_dbUpdate;
	
	function UpdateOrInsert (Request $array)
	{
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT s.ID
										FROM users u, store s
										WHERE u.username = ?
										AND u.ID = s.IDOwner', 
										[$input["UserName"]]);

		if(count($this->_dbSelect) == 1)
		{
			return $this->UpdateStore($input);
		}
		else if(count($this->_dbSelect) == 0)
		{
			return $this->InsertStore($input);
		}
		else
		{
			return json_decode('{"ERROR": "Something went wrong, please try again later"}');
		}
		
	}
	
	function InsertStore($array)
	{
		$SuccessToken = "";
		
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
											
					$location = DB::insert('INSERT into location 
											(IDStore, latitude, longitude, floor) 
											VALUES (?, ?, ?, ?)', 
											[$StoreId, $values_array [0], $values_array [1], $array["StoreFloor"]]);
									
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
			$this->_newArray = array("SuccessToken" => false);
		}
		
		return $newArray = $this->_newArray;
	}
	
    function UpdateStore($array)
	{
		$SuccessToken = "";
		
		if(array_key_exists("UserPassword", $array))
		{
			$array["UserPassword"] = password_hash($array["UserPassword"], PASSWORD_DEFAULT);
		}
		
		$this->_dbSelect = DB::select('SELECT ID
									FROM users 
									WHERE username = ?', 
									[$array["UserName"]]);
									
		/*$this->_dbSelect = DB::select('SELECT ID
									FROM users 
									WHERE username = ? and password = ?', 
									[$input["UserName"], $input["PassWord"]]); */ //THIS SHOULD BE THE COPRRECT WAY, FRONTEND SENDS THE PASSWORD TO CONFIRM UPDATE!!!!!!!!!!

		if (count($this->_dbSelect) == 1)
		{
			//HERE IS ONLY TO ADAPT THE DATA I RECEIVE TO BE USE FOR MY CODE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ALL THIS FOREACH!!!
			foreach ($this->_dbSelect as $x)
			{
				foreach ($x as $y)
				{
					$array["UserId"] = $y;
					if(array_key_exists("StoreLocation", $array) OR array_key_exists("StoreFloor", $array))
					{
						$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
														FROM store s, location l, users u
														WHERE s.ID = l.IDStore
														AND s.IDOwner = u.ID 
														AND u.ID = ?', 
														[$y]);
														
						/*$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
														FROM store s, location l
														WHERE s.ID = l.IDStore
														AND s.ID = ?', 
														[$y]);*/ //THIS IS FOR WHEN OWNER CAN HAVE MORE THEN ONE STORE!!!!!!!!!
														
						foreach($temporary as $Objs)
						{
							$values_array = explode(',', $array["StoreLocation"]);
							$save =[];
							//Here we separete each result from DB in their diferent keys and values
							foreach($Objs as $key => $z)
							{
								if ($key == "latitude")
								{
									$save[$key] = $values_array[0];
								}
								else if ($key == "longitude")
								{
									$save[$key] = $values_array[1];
								}
								else if ($key == "floor")
								{
									$save[$key] = $array["StoreFloor"];
								}
							}
							unset($array["StoreFloor"]);
							$array["StoreLocation"] = $save;
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
			
			$ID = 28;
			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $array);
			foreach ($this->_dbSelect as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $array);
					if(is_string($values))
					{
						return json_decode($values);
					}
				}
			}
			
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate >= 1)
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
			$this->_newArray = json_decode('{"ERROR": "User not Found!"}');
		}
		return $newArray = $this->_newArray;
	}
	
	function SeeProfile (Request $array)
	{
		//NOT READYT TO WORK FOR OWNERS THAT HAVE MORE THEN ONE STORE!!! DO ALTERNATIVE IN FUTURE!!!!!!
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT s.ID, s.name AS StoreName
												FROM users u, store s 
												WHERE u.username = ?
												AND s.IDOwner = u.ID', 
												[$input["UserName"]]);
												
		$location = [];
		$Feedback = [];
			$save = [];
		$StoreFloor	= ""; //ONLY TO ADAPT TO OTHER GROUPS CODE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!			
		//Because an instance is created, we need to separete first the diferent rows that comes from the DB
		if(count($this->_dbSelect) == 1)
		{
			foreach($this->_dbSelect as $Obj)
			{
						//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					//store Id is necessary to get more necessary data from DB, so we use an "if"
					//to use this key value in all other select querys
					if($key === "ID")
					{
						$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
												FROM store s, location l
												WHERE s.ID = l.IDStore
												AND s.ID = ?', 
												[$x]);
												
						$coordinates = ""; //JUST TO SAVE SOMETHING TO ADAPT MY CODE TO OTHER GROUPS!!! TEMPORARY!			
						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								foreach($Obj as $key => $y)
								{
									$location[$key] = $y;
									
									//SAME HERE, ONLY TO ADPAT!!!
									if($key == "latitude")
									{
										$coordinates = $y;
									}
									else if($key == "longitude")
									{
										$coordinates = $coordinates . "," . $y;
									}
									else if($key == "floor")
									{
										$StoreFloor = $y;
									}
								}
							}
							//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
							$location = ""; //clean everything and make it string
							$location = $coordinates; //transfering values
						}
						else
						{
							$location = "ERROR, location not found";
						}

						$temporary = DB::select('SELECT AVG(rating) AS AvgRate
												FROM store s, feedback f
												WHERE s.ID = f.IDStore
												AND s.ID = ?', 
												[$x]);
						
						if(count($temporary) > 0)
						{							
							foreach($temporary as $Objs)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $y)
								{
									$AvgRate = $y;
								}
							}
						}
						else
						{
							$AvgRate = array("ERROR, Average not possible to calculate"); json_decode('{"ERROR": "algorithm didn\'t receive the data"}');
						}
						
						$temporary = DB::select('SELECT comment, rating
												FROM item i, store s, feedback f
												WHERE s.ID = ?
												AND i.IDStore = s.ID', 
												[$x]);

							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									$oneFeedback = [];
									foreach($Obj as $key => $y)
									{
										$oneFeedback[$key] = $y;
									}
									array_push($Feedback, $oneFeedback);
								}
							}
							else
							{
								$Feedback = array("ERROR, Feedbacks not found");
							}
					}
					else
					{
						$save[$key] = $x; //store name
						$save['StoreLocation'] = $location;
						$save['StoreFloor'] = $StoreFloor; //AGAIN, TO ADPAT TO OTHER GROUPS CODE!!!!!!!!!!!
						$save['AvgRate'] = $AvgRate;
						$save['Feedback'] = $Feedback;

						//array_push($StoreList, $save);
					}
				}				
			}
			
			//$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "StoreList" => $StoreList);
			$this->_newArray = array("StoreName" => $save["StoreName"], "StoreLocation" => $save["StoreLocation"], "StoreFloor" => $save["StoreFloor"], "AvgRate" => $save["AvgRate"], "Feedback" => $save["Feedback"]);
		}
		else if(count($this->_dbSelect) == 0)
		{
			$this->_newArray = json_decode('{"ERROR": "STORE info doesn\'t exist for this username!"}');
		}
		else
		{
			$this->_newArray = json_decode('{"ERROR": "Owner account has more then one store. Server not ready for that!"}');
		}
		
		return $newArray = $this->_newArray;
	}
}
