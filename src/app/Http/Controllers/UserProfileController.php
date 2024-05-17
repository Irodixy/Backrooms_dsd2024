<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    function SeeUser($userName = "")
	{
		$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.bday AS Birthday, u.email AS Email
										FROM users u
										WHERE u.username = ?',
										['%' . $userName . '%']);
				
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
										
					foreach($temporary as $Objs)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Objs as $keys => $y)
						{
							$Interests[$keys] = $y;
						}
					}
					
					$save["Interests"] = $Interests;
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
		
		$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
								"UserData" => $UserData);
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
			$this->_dbInsert = DB::insert('INSERT into users (username, password) 
								values (?, ?)', 
								[$array["UserName"], $array["PassWord"]]);
								
			if($this->_dbInsert == 1)
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
			$this->_newArray = "ERROR, username already exists";
		}
		return $newArray = $this->_newArray;
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
