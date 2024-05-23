<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationCustomerController extends Controller
{
	private $_newArray;
	private $_dbInsert;
    function RegistrationCustomer(Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		$temporary = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$input["UserName"]]);
		if(!$temporary)
		{
			$this->_dbInsert = DB::insert('INSERT into users (username, password) 
								values (?, ?)', 
								[$input["UserName"], $input$input["PassWord"]]);
								
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
			$this->_newArray = json_decode('{"ERROR": "username already exists"}');
		}
		return $newArray = $this->_newArray;
	}
}
