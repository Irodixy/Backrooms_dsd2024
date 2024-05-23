<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchItemController extends Controller
{
    function SearchItem ($itemName ="")
	{
		$temporary = [];
		
		$this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, 
												i.imgName AS ItemImage, i.IDStore AS ItemStoreId
												FROM item i
												WHERE i.name LIKE ?', 
												['%' . $itemName . '%']);
												
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
			$temporary = array("ERROR" => "No item found!");
		}
		
		$this->_newArray = array("InterfaceId" => 6, "ItemList" => $temporary);
		return $newArray = $this->_newArray;
	}
}
