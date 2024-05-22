<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileOwnerController extends Controller
{
	private $_dbSelect;
	private $_newArray;
	private $_dbUpdate;
	
    function UpdateProfile (Request $array)
	{
		$SuccessToken = "";
		$temporary = DB::select('SELECT ID
												FROM users 
												WHERE username = ? and password = ?', 
												[$array["UserName"], $array["PassWord"]]);
		print_r($array["UserName"]);
		if (count($temporary) == 1)
		{
			//i.array_keys($array["interests"])[?] = ?, -> dependes of how many interest it is.
			//Possible UPDATE!!!! ->build IT before going in here!
			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $array["InterfaceId"], $array);
			foreach ( $temporary as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $array["InterfaceId"], $y, $array);
				}
			}
			//print_r($values);
			//echo $string;
			
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
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT s.ID, s.name AS StoreName
												FROM users u, store s 
												WHERE u.username = ?
												AND s.IDOwner = u.ID', 
												[$input["UserName"]]);
												
				$location = [];
				$Feedback = [];
					$save = [];
												
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
													FROM item i, store s
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
							$save[$key] = $x;
							$save['StoreLocation'] = $location;
							$save['AvgRate'] = $AvgRate;
							$save['Feedback'] = $Feedback;

							array_push($StoreList, $save);
						}
					}				
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "StoreList" => $StoreList);
					
	}
}
