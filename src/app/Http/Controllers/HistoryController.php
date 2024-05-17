<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    function SeeHistory ($CurrentUser)
	{
		$temporary = [];
		
		$this->_dbSelect = DB::select('SELECT s.ID, s.name, h.date_time 
												FROM store s, huntedstore h, users u 
												WHERE u.username = ? 
												AND u.ID = h.IDUser 
												AND h.IDStore = s.ID', 
												[$CurrentUser]);
												
		if(count($this->_dbSelect) > 0)
		{						
			foreach($this->_dbSelect as $Obj)
			{
				$oneItem = [];
				foreach($Obj as $key => $x)
				{
					$oneItem[$key] = $x;
				}
				array_push($temporary, $oneItem);
			}
		}
		else
		{
			$temporary = array("ERROR, no History to show");
		}
					
		$this->_newArray = array("InterfaceId" => 5, "CurrentUser" => $CurrentUser,
									"HuntedStoreIdList" => $temporary);
		return $newArray = $this->_newArray;
	}
}
