<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationOwnerController extends Controller
{
	private $_dbInsert;
	private $_dbSelect;
	private $_newArray;
	
    function RegistrationOwner(Request $array)
	{
		$MatchToken = "";
		
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$input["UserName"]]);
		if(!$this->_dbSelect)
		{
			$this->_dbInsert = DB::insert('INSERT into users (username, password, type) 
								values (?, ?, ?)', 
								[$input["UserName"], $input["UserPassword"], "owner"]);
								
			if($this->_dbInsert == 1)
			{
				$MatchToken = true;
				$this->_newArray = array("MatchToken" => $MatchToken);
			}
			else
			{
				$MatchToken = false;
				$this->_newArray = array("MatchToken" => $MatchToken);
			}
		}
		else
		{
			$this->_newArray = "ERROR, username already exists";
		}
		return $newArray = $this->_newArray;
	}
}
