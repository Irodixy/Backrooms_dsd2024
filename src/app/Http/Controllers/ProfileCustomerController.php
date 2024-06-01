<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileCustomerController extends Controller
{
	private $_dbSelect;
	private $_dbUpdate;
	private $_newArray;
	
    function UpdateProfile (Request $array)
	{
		$input = $array->all();
		if(array_key_exists("PassWord", $input))
		{
			$input["PassWord"] = password_hash($input["PassWord"], PASSWORD_DEFAULT);
		}
		
		$SuccessToken = "";
		
		$temporary = DB::select('SELECT ID AS UserId
								FROM users 
								WHERE username = ?
								AND type = "customer"', 
								[$input["CurrentUser"]]);
																			 

		if (count($temporary) == 1)
		{
			$input["UserId"] = $temporary[0]->UserId;
			
			if(array_key_exists("UserName", $input))
			{
				$username = DB::select('SELECT ID
									FROM users
									WHERE username = ?',
									[$input["UserName"]]);
									
				if(count($username) == 1)
				{
					foreach($username as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							if($input["UserId"] != $x)
							{
								return json_decode('{"ERROR": "Username already been used by other account"}');
							}	
						}
					}
				}
			}
			
			if(array_key_exists("Email", $input))
			{
				$email = DB::select('SELECT ID
									FROM users
									WHERE email = ?',
									[$input["Email"]]);
									
				if(count($email) == 1)
				{
					foreach($email as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							if($input["UserId"] != $x)
							{
								return json_decode('{"ERROR": "Email already been used by other account"}');
							}	
						}
					}
				}
			}
			
			/*
			if(array_key_exists("Interests", $input))
			{
				//ADAPT TO ARRAY TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
				$liteInterests = DB::select('SELECT *
											FROM interests
											WHERE IDUser = ?', 
											[$input["UserId"]]);
									
				if(count($liteInterests) == 1)
				{				
					foreach($liteInterests as $Objs)
					{
						$values_array = array_map("strtolower", explode(',', $input["Interests"]));

						//number of Interests to update!!!
						$number = count($values_array);
						
						$i = 0;
						$save =[];
						
						//counts how many interests to update already exist on DB
						$count = 0;
						$changeInterests = [];
						
						$countDBInterests = 0;
						
						//Each round is a new INTERESTS of DB
						foreach($Objs as $key => $x)
						{
							if($key != "IDUser")
							{
								//count the amount of interests columns in DB
								$countDBInterests++;
								foreach($values_array as $keys => $y)
								{
									//if one interest of DB is equal to another to update!
									if($key == $y)
									{
										$count++;
										$changeInterests[$y] = mt_rand(1, 100);
									}
								}
							}
						}						
						
						//if count = number, them the interests to update are all already in DB
						if($count == $number)
						{
							$input["Interests"] = [];
							$input["Interests"] = $changeInterests;
						}
						else if($count < $number) //else it means one or more are not in DB, and need to be added!
						{
							//this one connects with the values coming from frontend
							foreach($values_array as $keys => $y)
							{
								$countAgain = 0;
								//this one connects with the table Interests from DB
								foreach($Objs as $key => $x)
								{
									if($key != "IDUser")
									{
										//this one calculates if interest is different from all the ones already in DB
										if($key != $y)
										{
											$countAgain++;
										}
										
										if($countAgain == $countDBInterests)
										{
											$FirstPart = <<<EOT
															SELECT COUNT(*) INTO @column_exists
															FROM information_schema.COLUMNS
															WHERE TABLE_SCHEMA = 'project'
															AND TABLE_NAME = 'interests'
															AND COLUMN_NAME = '
															EOT;
											$SecondPart = <<<EOT
															';
															SET @query = IF(@column_exists = 0, 'ALTER TABLE interests ADD COLUMN 
															EOT;
											$ThirdPart = <<<EOT
															 INTEGER(5) DEFAULT 0 ;',
																'SELECT "Column already exists";');

															PREPARE stmt FROM @query;
															EXECUTE stmt;
															DEALLOCATE PREPARE stmt;
															EOT;
														
											$string = $FirstPart . $y . $SecondPart . $y . $ThirdPart;
											//echo $string;											
											DB::unprepared($string);
											
											$constrain = "ALTER TABLE interests ADD CONSTRAINT chk_" . $y . " CHECK (". $y . " BETWEEN 0 AND 100);";
											DB::unprepared($constrain);
											$changeInterests[$y] = mt_rand(1, 100);
										}
									}
								}
							}
							$input["Interests"] = [];
							$input["Interests"] = $changeInterests;
						}
					}
				}
				else if(count($liteInterests) == 0)
				{
					return json_decode('{"ERROR": "Interests not found, contact the admin!"}');
				}
				else
				{
					return json_decode('{"ERROR": "Multiply interests found, contact the admin!"}');
				}	
			}
			*/ //A MORE COMPLEX TABLE OF INTERESTS, READY FOR MORE SPECIFICATION FOR AI GROUP
	
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $input["InterfaceId"], $input);
			$values = $query -> ArrayBuilder("UPDATE", $input["InterfaceId"], $input["UserId"], $input);
			if(is_string($values))
			{
				return json_decode($values);
			}
			
			//print_r($values);
			//echo $string;
			
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate >= 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
			
		}
		else
		{
			$this->_newArray = array("ERROR" => "User not Found!");
		}
		
		return $newArray = $this->_newArray;
	}
	
	function SeeProfile (Request $array)
	{
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.birthday AS Birthday, i.interests AS Interests
												FROM users u, interestsV2 i
												WHERE u.username = ?
												AND u.ID = i.IDUser',
												[$input["CurrentUser"]]);
		
		/*$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.birthday AS Birthday
												FROM users u
												WHERE u.username = ?',
												[$input["CurrentUser"]]);*/
				
		$save = [];
		$Interests = [];	
		
		if(count($this->_dbSelect) == 1)
		{
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					if($key != "ID")
					{
						$save[$key] = $x;
					}
					
					/*else
					{
						$temporary = DB::select('SELECT i.*
												FROM users u, interests i
												WHERE u.ID = ?
												AND u.ID = i.IDUser',
												[$x]);
															
							foreach($temporary as $Objs)
							{
								$oneInterest = [];
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $y)
								{
									if($keys != "IDUser")
									{
										if($y > 0)
										{
											$oneInterest[$keys] = $keys;
										}
									}
								}
								$Interests = $oneInterest;
							}
							//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
							$values = array_values($Interests);
							$string = implode(',', $values);
					}*/
				}
			}
			$this->_newArray = array("InterfaceId" => 11, "CurrentUser" => $input["CurrentUser"], "UserName" => $save["UserName"], "Birthday" => $save["Birthday"], "Interests" => $save["Interests"]);
			
			/*$this->_newArray = array("InterfaceId" => 11, "CurrentUser" => $input["CurrentUser"], "UserName" => $save["UserName"], "Birthday" => $save["Birthday"], "Interests" => $string);*/
		}
		
		else if(count($this->_dbSelect) == 0)
		{
			$this->_newArray = json_decode('{"ERROR": "Someting didn\'t work. No User found with CurrentUser Username!"}');
		}
		
		else
		{
			$this->_newArray = json_decode('{"ERROR": "Someting didn\'t work. No User found with CurrentUser Username!"}');
		}
		
		return $newArray = $this->_newArray;		
	}
}