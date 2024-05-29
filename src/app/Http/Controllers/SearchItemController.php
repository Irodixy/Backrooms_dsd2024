<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchItemController extends Controller
{
    function SearchItem (Request $array)
	{
		$input = $array->all();
		
		$ItemList = [];
		
		$this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, 
												i.image AS ItemImage, i.base_image, i.IDStore AS ItemStoreId
												FROM item i
												WHERE i.name LIKE ?', 
												['%' . $input["ItemName"] . '%']);
												
		if(count($this->_dbSelect) > 0)
		{						
			foreach($this->_dbSelect as $Obj)
			{
				$oneItem = [];
				foreach($Obj as $key => $x)
				{
					if($key == "ItemImage")
					{
						$oneItem[$key] = base64_encode($x);
					}
					else if($key == "base_image")
					{
						$oneItem["ItemImage"] = $x . $oneItem["ItemImage"];
					}
					else if($key == "ItemStoreId")
					{
						$oneItem[$key] = $x;
						
						$temporary = DB::select('SELECT name AS ItemStoreName
												FROM store
												WHERE ID = ?', 
												[$x]);
												
						if(count($temporary) == 1)
						{
							foreach($temporary as $id)
							{
								foreach($id as $keys => $y)
								{
									$oneItem[$keys] = $y;
								}
							}
						}
						else
						{
							$oneItem[$keys] = json_decode('{"ERROR": "Impossible result, no store name found!"}');
						}
					}
					else
					{
						$oneItem[$key] = $x;
					}
				}
				array_push($ItemList, $oneItem);
			}
		}
		else
		{
			$ItemList = json_decode('{"ERROR": "No item found!"}');
		}
		
		$this->_newArray = array("InterfaceId" => 6, "CurrentUser" => $input["CurrentUser"], "ItemList" => $ItemList);
		return $newArray = $this->_newArray;
	}
}
