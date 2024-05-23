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
	
    function UpdateProfile (Request $array)
	{
		$input = $array->all();

		$SuccessToken = "";
		$this->_dbSelect = DB::select('SELECT ID
									FROM users 
									WHERE username = ?', 
									[$input["UserName"]]);
									
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
						$values_array = explode(',', $input["StoreLocation"]);
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
								$save[$key] = $input["StoreFloor"];
							}
						}
						unset($input["StoreFloor"]);
						$input["StoreLocation"] = $save;
					}
				}
			}
			
			$ID = 28;
			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $input);
			foreach ($this->_dbSelect as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $input);
				}
			}
			print_r($values);
			echo $string;
			
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
									"SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
									"SuccessToken" => $SuccessToken);
			}
		}
		else
		{
			$this->_dbUpdate = "ERROR, User not Found!";
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
								$AvgRate = array("ERROR, Average not possible to calculate");
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
		
		return $newArray = $this->_newArray;
	}
}
