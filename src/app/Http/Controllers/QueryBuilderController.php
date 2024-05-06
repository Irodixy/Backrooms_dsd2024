<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
 
class QueryBuilderController extends Controller
{
    /**
     * Show a list of all of the application's users!
     */
    public function StringBuilder($type, $id, $array)
    {
		if ($type == "UPDATE")
		{
			if ($id == 10)
			{
				$newString = $type . " users u, interests i SET ";
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "Interests")
					{
						$add = strtolower($key);
						$newString = $newString . "u." . "$add = ?, ";
					}
					if ($key == "Interests")
					{
						foreach ($x as $keys => $y)
						{
							$add = strtolower($keys);
							$newString = $newString . "i." . "$add = ?, ";
						}
					}
				}
				$newString = substr($newString, 0, -2);
				$newString = $newString . " WHERE u.ID = ? AND u.ID = i.IDUser";
				return $newString;
			}
		}
    }
	
	public function ArrayBuilder($type, $id, $idUser, $array)
	{
		if ($type == "UPDATE")
		{
			if ($id == 10)
			{
				$newArray = [];
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "Interests")
					{
						array_push($newArray, $x);
					}
					if ($key == "Interests")
					{
						foreach ($x as $keys => $y)
						{
							array_push($newArray, $y);
						}
					}
				}
				array_push($newArray, $idUser);
				return $newArray;
			}
		}
	}
}
?>