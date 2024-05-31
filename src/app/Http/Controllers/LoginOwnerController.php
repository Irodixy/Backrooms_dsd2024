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
		
		//VERIFY PASSWORD!!!!
		$StoredPass = DB::select('SELECT password
								FROM users
								WHERE username = ?',
								[$input["UserName"]]);
						
		$password = $StoredPass[0]->password;

		if(password_verify($input["UserPassword"], $password))
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
