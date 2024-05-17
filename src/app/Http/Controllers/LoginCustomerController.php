<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginCustomerController extends Controller
{
    function LoginCustomer(Request $array)
	{
		$MatchToken = "";
		
		$this->_dbSelect = DB::select('SELECT ID, username 
												FROM users 
												WHERE username = ? and password = ?', 
												[$array["UserName"], $array["PassWord"]]);
				
		if(count($this->_dbSelect) == 1)
		{
			$MatchToken = true;
			$this->_newArray = array("InterfaceId" => 1, "CurrentUser" => $array["UserName"],
								"MatchToken" => $MatchToken);
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
