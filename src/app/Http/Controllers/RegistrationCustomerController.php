<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationCustomerController extends Controller
{
	private $_newArray;
	private $_dbInsert;
	private $_dbSelect;
	
    function RegistrationCustomer(Request $array)
	{
		$input = $array->all();
		if($input["PassWord"])
		{
			$input["PassWord"] = password_hash($input["PassWord"], PASSWORD_DEFAULT);
		}
		
		
		$SuccessToken = "";
		$this->_dbSelect = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$input["UserName"]]);
		if(!$this->_dbSelect)
		{
			$this->_dbInsert = DB::insert('INSERT into users (username, password) 
								values (?, ?)', 
								[$input["UserName"], $input["PassWord"]]);
								
			if($this->_dbInsert == 1)
			{
				$temporarySelect = DB::select('SELECT ID
										FROM users
										WHERE username = ?',
										[$input["UserName"]]);
			
				if($temporarySelect)
				{
					foreach($temporarySelect as $Obj)
					{
						foreach($Obj as $key => $x)
						{
							$temporaryInsert = DB::insert('INSERT into interests (IDUser) 
													values (?)', 
													[$x]);
													
							if($temporaryInsert == 1)
							{
								$SuccessToken = true;
								$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "successful_token" => $SuccessToken);
							}
							else
							{
								$SuccessToken = true;
								$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "successful_token" => $SuccessToken, "ERROR" => "Table Interests not updated. See why!");
							}
						}
					}
				}
				else
				{
					$SuccessToken = false;
					$this->_newArray = array("successful_token" => $SuccessToken, "ERROR" => "Registration successful but Username not found! Contact Admin!");
				}
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "successful_token" => $SuccessToken);
			}
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("successful_token" => $SuccessToken, "ERROR" => "username already exists");
		}
		return $newArray = $this->_newArray;
	}
}
