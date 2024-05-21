<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginCustomerController extends Controller
{
	private $_dbSelect;
	
    function LoginCustomer(Request $array)
	{
		$input = $array->all();
		$MatchToken = "";
		
		$this->_dbSelect = DB::select('SELECT first_login 
												FROM users 
												WHERE username = ? and password = ?', 
												[$input["UserName"], $input["PassWord"]]);
				

		if(count($this->_dbSelect) == 1)
		{
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					if($key == "first_login" AND $x == true)
					{
						$MatchToken = true;
						$this->_newArray = array("InterfaceId" => 1, "CurrentUser" => $input["UserName"],
										"MatchToken" => $MatchToken, "FirstLogin" => true);
										
						$update = DB::update('UPDATE users
												SET first_login = false
												WHERE username = ?',
												[$input["UserName"]]);
					}
					else
					{
						$MatchToken = true;
						$this->_newArray = array("InterfaceId" => 1, "CurrentUser" => $input["UserName"],
										"MatchToken" => $MatchToken, "FirstLogin" => false);
					}
				}
			}
		}
		else
		{
			$MatchToken = false;
			$this->_newArray = array("InterfaceId" => 1, "CurrentUser" => NULL,
								"MatchToken" => $MatchToken);
		}
		
		return $newArray = $this->_newArray;
	}
}
