<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class test_ai extends Controller
{
	private $_dbSelect;
	
	function interface7Simulation()
	{
		
	}
	
    function interface13 (Request $x)
	{
		$input = $x->all();
		
		$array = json_decode('{
		"InterfaceId": 7,
		"CurrentUser": "Paul_update",
		"MyLocation": 
		{
			"latitude": ' . $input["MyLocation"]["latitude"] . ',
			"longitude": ' . $input["MyLocation"]["longitude"] . '
		},
		"RequestType": 1
		}');
		$userName = "Paul_update";
		$save = Http::post('http://13.79.99.190/api/interface7', $array);
		//$save = Http::post('http://192.168.56.102/laravel/api/interface7', $array);
		$interface13 = $save->json();
		$interface13["InterfaceId"] = 13;
		
		$interface14 = $this->interface14($userName);
			
		$jsonFile = array("Interface13" => $interface13, "Interface14" => $interface14);
		//print_r($jsonFile);
		$save = Http::post('https://u69070-9b28-34756fc5.westb.seetacloud.com:8443/require_recommendation', $jsonFile);
		$SuccessToken = $save->json();
		var_dump($SuccessToken);
		/*if(count($SuccessToken) > 0)
		{
			if ($SuccessToken == false)
			{
				return json_decode('{"SuccessMsg": "AI received, but something didn\'t work. Please try again later!"}');
			}
			else
			{
				return json_decode('{"SuccessMsg": "AI received and successfuly used the data!"}');
			}
		}
		else
		{
			return json_decode('{"ERROR": "Fail to send information to server. Please try again later"}');
		}*/
	}
	
	function interface14 ($name)
	{
		$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, u.email AS UserEmail 
											FROM users u
											WHERE u.username = ?', 
											[$name]);

		//Variables to save data and help reorganize the future JSON file			
		$UserData = [];
		$Interests = [];
		$HuntedStoreIdList = [];
		$save = [];
		$string = "";

		if(count($this->_dbSelect) == 1)
		{
			//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
			foreach($this->_dbSelect as $Obj)
			{
				//Here we separete each result from DB in their diferent keys and values
				foreach($Obj as $key => $x)
				{
					//store Id is necessary to get more necessary data from DB, so we use an "if"
					//to use this key value in all other select querys
					if($key == "UserId")
					{
						$save[$key] = $x;
						
						$temporary = DB::select('SELECT i.*
											FROM users u, interests i
											WHERE u.ID = ?
											AND u.ID = i.IDUser', 
											[$x]);
											
						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								foreach($Obj as $key => $y)
								{
									if($key != "IDUser")
									{
										$Interests[$key] = $key;
									}
								}
							}
						}
						else
						{
							$Interests = "ERROR, interests not found";
						}
						//ADAPT TO STRING TO BE COMPATABLE WITH OTHER GROUPS CODE (NOT RECOMENDED!!!!!)
						$values = array_values($Interests);
						$string = implode(',', $values);

						$temporary = DB::select('SELECT IDStore AS StoreId, date_time AS VisitTime
											FROM huntedstore
											WHERE IDUser = ?', 
											[$x]);

						if(count($temporary) > 0)
						{						
							foreach($temporary as $Obj)
							{
								$oneSearch = [];
								foreach($Obj as $key => $y)
								{
									$oneSearch[$key] = $y;
								}
								array_push($HuntedStoreIdList, $oneSearch);
							}
						}
						else
						{
							$HuntedStoreIdList = array("ERROR, item not found");
						}
					}
					else 
					{
						$save[$key] = $x;
					}
				}				
			}
			$save['Interests'] = $string;
			$save['HuntedStoreIdList'] = $HuntedStoreIdList;

			$UserData = $save;
			
			$pickUser = array("InterfaceId" => 14, "CurrentUser" => $name, "UserData" => $UserData);
			return $pickUser;
		}
		else if (count($this->_dbSelect) == 0)
		{
			return json_decode('{"ERROR": "User not Found!"}');
		}
		else
		{
			return json_decode('{"ERROR": "Multipel users Found!"}');
		}
	}
	
	function interface16 ()
	{
		
	}
	
	function interface17 ()
	{
	
	}
}
