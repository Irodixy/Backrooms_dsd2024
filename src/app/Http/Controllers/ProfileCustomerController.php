<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileCustomerController extends Controller
{
	private $_dbSelect;
	private $_dbUpdate;
	private $_newArray;
	
    function UpdateProfile (Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		
		$temporary = DB::select('SELECT ID
								FROM users 
								WHERE username = ?', 
								[$input["CurrentUser"]]);
																			 

		if (count($temporary) == 1)
		{			
			$query = new QueryBuilderController();
			$string = $query -> StringBuilder("UPDATE", $input["InterfaceId"], $input);
			foreach ( $temporary as $x)
			{
				foreach ($x as $y)
				{
					$values = $query -> ArrayBuilder("UPDATE", $input["InterfaceId"], $y, $input);
				}
			}
			//print_r($values);
			//echo $string;
			
			$this->_dbUpdate = DB::update($string, $values);
			
			if($this->_dbUpdate == 1)
			{
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
			else
			{
				$SuccessToken = false;
				$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], "SuccessToken" => $SuccessToken);
			}
		}
		else
		{
			$this->_newArray = array("ERROR" => "User not Found!");
		}
		return $newArray = $this->_newArray;
	}
	
	function SeeProfile (Request $array)
	{
		$input = $array->all();
		
		$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.birthday AS Birthday
												FROM users u
												WHERE u.username = ?',
												[$input["CurrentUser"]]);
				
		$save = [];
		$Interests = [];	
		
		if(count($this->_dbSelect) == 1)
		{
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
					$this->_dbSelect = DB::select('SELECT i.*
													FROM users u, interests i
													WHERE u.ID = ?
													AND u.ID = i.IDUser',
													[$x]);
															
							foreach($this->_dbSelect as $Obj)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Obj as $keys => $y)
								{
									if($keys != "IDUser")
									{
										$Interests[$key] = $y;
									}
								}
							}
							//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
							$values = array_values($Interests);
							$string = implode(',', $values);
					}
				}
			}
			
			$this->_newArray = array("InterfaceId" => 11, "CurrentUser" => $input["CurrentUser"], "UserName" => $save["UserName"], "Birthday" => $save["Birthday"], "Interests" => $string);
		}
		
		else if(count($this->_dbSelect) == 0)
		{
			$this->_newArray = json_decode('{"ERROR": "Someting didn\'t work. No User found with CurrentUser Username!"}');
		}
		
		else
		{
			$this->_newArray = json_decode('{"ERROR": "Someting didn\'t work. No User found with CurrentUser Username!"}');
		}
		
		return $newArray = $this->_newArray;		
	}
}