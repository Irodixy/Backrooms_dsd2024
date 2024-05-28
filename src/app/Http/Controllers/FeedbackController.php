<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    function SaveFeedbackStore (Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		$save = [];
			
		//do a try (still not done)
		$temporary = DB::select('SELECT ID AS userId
							FROM users 
							WHERE username = ?', 
							[$input["CurrentUser"]]);
		
		foreach($temporary as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				$save[$key] = $x;
			}
		}

		$this->_dbInsert = DB::insert('insert into feedback (IDUser, IDStore, comment, rating) 
					values (?, ?, ?, ?)', 
					[$save["userId"], $input["Feedback"]["StoreId"], $input["Feedback"]["comment"], $input["Feedback"]["rating"]]);
					
		if($this->_dbInsert == 1)
		{
			$input["InterfaceId"] = 17;
			//$sendFeedbackStore = Http::post('https://u10536-9aae-47f62ca6.neimeng.seetacloud.com:6443/require_recommendation', $input);
			
			$sendFeedbackStore = false;
			
			if ($sendFeedbackStore == false)
			{
				$AiReturn = json_decode('{"ERROR": "algorithm didn\'t receive the data"}');
				
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken, "AiReturn" => $AiReturn);
			}
			else
			{
				$AIreturn = true;
				
				$SuccessToken = true;
				$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken, "AiReturn" => $AiReturn);
			}
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("InterfaceId" => 8, "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		return $newArray = $this->_newArray;
	}
	
	function SaveFeedbackItem (Request $array)
	{
		$input = $array->all();
		
		$SuccessToken = "";
		$save = [];
			
		//do a try (still not done)
		$temporary = DB::select('SELECT ID AS userId
							FROM users 
							WHERE username = ?', 
							[$input["CurrentUser"]]);
		
		foreach($temporary as $Obj)
		{
			//Here we separete each result from DB in their diferent keys and values
			foreach($Obj as $key => $x)
			{
				$save[$key] = $x;
			}
		}
	
		$this->_dbInsert = DB::insert('INSERT into feedback (IDUser, IDItem, comment, rating) 
					values (?, ?, ?, ?)', 
					[$save["userId"], $input["Feedback"]["ItemId"], $input["Feedback"]["comment"], $input["Feedback"]["rating"]]);
					
		if($this->_dbInsert == 1)
		{
			$SuccessToken = true;
			$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		else
		{
			$SuccessToken = false;
			$this->_newArray = array("InterfaceId" => $input["InterfaceId"], "CurrentUser" => $input["CurrentUser"], 
								"successful_token" => $SuccessToken);
		}
		return $newArray = $this->_newArray;
	}
}
