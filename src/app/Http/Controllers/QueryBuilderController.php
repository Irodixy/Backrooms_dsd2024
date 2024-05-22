<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
 
class QueryBuilderController extends Controller
{
	
	private $_dbSelect;
    
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
					else if ($key == "Interests")
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
			else if ($id == 21)
			{
				unset($array["UserId"]);
				$newString = $type . " users u, interests i SET ";
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "Interests" AND $key != "UserPassword")
					{
						$add = strtolower($key);
						$newString = $newString . "u." . "$add = ?, ";
					}
					else if($key == "UserPassword")
					{
						$add = str_replace("user", "", strtolower($key));
						$newString = $newString . "u." . "$add = ?, ";
					}
					else if ($key == "Interests")
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
			else if ($id == 24)
			{
				unset($array["StoreId"]);
				unset($array["UserId"]);
				$newString = $type . " users u, store s, location l SET ";
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "StoreLocation" AND $key != "StoreName" AND $key != "UserPassword")
					{
						$add = strtolower($key);
						$newString = $newString . "u." . "$add = ?, ";
					}
					else if($key == "UserPassword")
					{
						$add = str_replace("user", "", strtolower($key));
						$newString = $newString . "u." . "$add = ?, ";
					}
					else if ($key == "StoreName")
					{
							$add = str_replace("store", "", strtolower($key));
							$newString = $newString . "s." . "$add = ?, ";
					}
					else if ($key == "StoreLocation")
					{
						foreach ($x as $keys => $y)
						{
							$add = strtolower($keys);
							$newString = $newString . "l." . "$add = ?, ";
						}
					}
				}
				$newString = substr($newString, 0, -2);
				$newString = $newString . " WHERE u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
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
			else if($id == 21)
			{
				unset($array["UserId"]);
				$newArray = [];
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "Interests")
					{
						array_push($newArray, $x);
					}
					else if ($key == "Interests")
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
			else if($id == 24)
			{
				$this->_dbSelect = DB::select('SELECT name
												FROM store
												WHERE IDOwner = ?',
												[$idUser]);
												
				if ($this->_dbSelect == 1)
				{
					foreach($this->_dbSelect as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $StoreName => $input)
						{
							unset($array["UserId"]);
							$newArray = [];
							foreach ($array as $key => $x)
							{
								if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "StoreLocation" AND $key != "StoreName" AND $key != "StoreId")
								{
									array_push($newArray, $x);
								}
								else if ($key == "StoreName")
								{
									array_push($newArray, $x);
								}
								else if ($key == "StoreLocation")
								{
									foreach ($x as $keys => $y)
									{
										array_push($newArray, $y);
									}
								}
							}
							array_push($newArray, $idUser);
							array_push($newArray, $array["StoreId"]);
							array_push($newArray, $input);
							return $newArray;
						}
					}
				}
				else
				{
					return "ERROR founded, please try again later";
				}
				
				
				/*unset($array["UserId"]);
				$newArray = [];
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "StoreLocation" AND $key != "StoreName" AND $key != "StoreId")
					{
						array_push($newArray, $x);
					}
					else if ($key == "StoreName")
					{
						array_push($newArray, $x);
					}
					else if ($key == "StoreLocation")
					{
						foreach ($x as $keys => $y)
						{
							array_push($newArray, $y);
						}
					}
				}
				array_push($newArray, $idUser);
				array_push($newArray, $array["StoreId"]);
				array_push($newArray, $array["StoreId"]);
				return $newArray;*/
			}
		}
	}
}
?>