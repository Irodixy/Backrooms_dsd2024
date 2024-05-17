<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationCustomerController extends Controller
{
    function RegistrationCustomer(Request $array)
	{
		$SuccessToken = "";
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
}
