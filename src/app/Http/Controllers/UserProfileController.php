<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
	private $_dbSelect;
	private $_dbInsert;
	private $_dbUpdate;
	private $_dbDelete;
	private $_newArray;
	
	function UpdateOrInsert (Request $array)
	{
		$input = $array->all();
		$this->_dbSelect = DB::select('SELECT ID
										FROM users 
										WHERE ID = ?', 
										[$input["UserId"]]);

		if(count($this->_dbSelect) == 1)
		{
			return $this->UpdateUser($input);
		}
		else if(count($this->_dbSelect) == 0)
		{
			return $this->InsertUser($input);
		}
		else
		{
			return json_decode('{"ERROR": "Something went wrong, please try again later"}');
		}
		
	}
	
    function SeeUser(Request $array)
	{
		$input = $array->all();
		
		if(!isset($input["UserSearched"]))
		{
			$array["UserSearched"] = "";
		}
		
		$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.birthday AS Birthday, u.email AS Email
										FROM users u
										WHERE u.username LIKE ?',
										['%' . $input["UserSearched"] . '%']);
				
		$UserData = [];
		$save = [];
		$Interests = [];	

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
					$save[$key] = $x;
					
					$temporary = DB::select('SELECT i.*
										FROM users u, interests i
										WHERE u.ID = ?
										AND u.ID = i.IDUser',
										[$x]);
					
					$string = "";
					foreach($temporary as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $y)
						{
							if($keys != "IDUser")
							{
								$Interests[$keys] = $y;
							}
						}
						//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
						$values = array_values($Interests);
						$string = implode(',', $values);
					}
					
					$save["Interests"] = $string;
				}

				if($i >= 3)
				{
					array_push($UserData, $save);
					$i = 0;
				}
				else
				{
					$i++;
				}
			}
		}
		
		$this->_newArray = array("UserData" => $UserData);
		return $newArray = $this->_newArray;
	}
	
	function UpdateUser ($array)
	{
		$temporary = DB::select('SELECT ID
								FROM users 
								WHERE ID = ?', 
								[$array["UserId"]]);

		if (count($temporary) == 1)
		{
			//ADAPT TO ARRAY TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
			$liteInterests = DB::select('SELECT *
										FROM interests
										WHERE ID = ?', 
										[$array["UserId"]]);
										
			foreach($temporary as $Objs)
				{
					$values_array = explode(',', $array["Interests"]);
					$i = 0;
					$save =[];
					//Here we separete each result from DB in their diferent keys and values
					foreach($Objs as $key => $x)
					{
						if ($key != "IDUser")
						{
							$save[$key] = $values_array[$i];
							$i++;
						}
					}
					$array["Interests"] = [];
					array_push($array["Interests"], $save);
				}
			
			$ID = 21;
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $ID, $array);
			foreach ( $temporary as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $array);
				}
			}
			
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
			$this->_newArray = json_decode('{"ERROR": "User not Found!"}');
		}
		return $newArray = $this->_newArray;	
	}
	
	function InsertUser ($array)
	{
		$this->_dbInsert = DB::insert('INSERT into users (ID ,username, password, birthday) 
							values (?, ?, ?, ?)', 
							[$array["UserId"] , $array["UserName"], $array["UserPassword"], $array["Birthday"]]);
							
		if($this->_dbInsert == 1)
		{
			$temporary = DB::select('SELECT ID
									FROM users 
									WHERE username = ?', 
									[$array["UserName"]]);
									
			foreach($temporary as $Objs)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Objs as $keys => $x)
				{
					$interst_insert = DB::insert('INSERT INTO interests (IDUser)
													VALUES (?)',
													[$x]);
													
					if($interst_insert == 1)
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
			$SuccessToken = false;
			$this->_newArray = array("SuccessToken" => $SuccessToken);
		}

		return $newArray = $this->_newArray;
	}
	
	function DeleteUser (Request $array)
	{
		$input = $array->all();
		
		$this->_dbDelete = DB::delete('DELETE FROM users
										WHERE ID = ?',
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
