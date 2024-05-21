<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileCustomerController extends Controller
{
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
	
	function SeeProfile ($CurrentUser)
	{
		$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.bday AS Birthday
												FROM users u
												WHERE u.username = ?',
												[$CurrentUser]);
				
		$save = [];
		$Interests = [];	
			
		foreach($this->_dbSelect as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				if($key != "ID")
				{
					$save[$key] = $x;
				}
				else
				{
					$temporary[$key] = $x;
				}
			}
		}

		$this->_dbSelect = DB::select('SELECT i.*
										FROM users u, interests i
										WHERE u.ID = ?
										AND u.ID = i.IDUser',
										[$temporary["ID"]]);
										
		foreach($this->_dbSelect as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				$Interests[$key] = $x;
			}
		}
		
		$this->_newArray = array("InterfaceId" => 11, "CurrentUser" => $CurrentUser,
										"UserName" => $save["UserName"], "Birthday" => $save["Birthday"], 
										"Interests" => $Interests);
		return $newArray = $this->_newArray;
					
	}
}