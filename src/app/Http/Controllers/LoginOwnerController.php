<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginOwnerController extends Controller
{
	private $_dbSelect;
	private $_newArray;
	
     function LoginOwner(Request $array)
	{
		$input = $array->all();
		
		$MatchToken = "";
		
		$this->_dbSelect = DB::select('SELECT ID, username 
												FROM users 
												WHERE username = ? and password = ?', 
												[$input["UserName"], $input["UserPassword"]]);
				
		if(count($this->_dbSelect) == 1)
		{
			$MatchToken = true;
			$this->_newArray = array("MatchToken" => $MatchToken);
		}
		else
		{
			$MatchToken = false;
			$this->_newArray = array("MatchToken" => $MatchToken);
		}
		
		return $newArray = $this->_newArray;
	}
}
