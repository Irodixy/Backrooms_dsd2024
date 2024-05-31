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
				unset($array["UserId"]);
				if(array_key_exists("Interests", $array) AND count($array) >= 4)
				{
					$newString = $type . " users u, interests i SET ";
				}
				else if(array_key_exists("Interests", $array) AND count($array) <= 3)
				{
					$newString = $type . " interests i SET ";
				}
				else
				{
					$newString = $type . " users u SET ";
				}
				
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
				
				if(array_key_exists("Interests", $array) AND count($array) >= 4)
				{
					$newString = $newString . " WHERE u.ID = ? AND u.ID = i.IDUser";
				}
				else if(array_key_exists("Interests", $array) AND count($array) <= 3)
				{
					$newString = $newString . " WHERE i.IDUser = ?";
				}
				else
				{
					$newString = $newString . " WHERE u.ID = ?";
				}
				
				return $newString;
			}
			else if ($id == 21)
			{
				unset($array["UserId"]);
				if(array_key_exists("Interests", $array) AND count($array) >= 4)
				{
					$newString = $type . " users u, interests i SET ";
				}
				else if(array_key_exists("Interests", $array) AND count($array) <= 3)
				{
					$newString = $type . " interests i SET ";
				}
				else
				{
					$newString = $type . " users u SET ";
				}
				
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
				
				if(array_key_exists("Interests", $array) AND count($array) >= 4)
				{
					$newString = $newString . " WHERE u.ID = ? AND u.ID = i.IDUser";
				}
				else if(array_key_exists("Interests", $array) AND count($array) <= 3)
				{
					$newString = $newString . " WHERE i.IDUser = ?";
				}
				else
				{
					$newString = $newString . " WHERE u.ID = ?";
				}
				
				return $newString;
			}
			else if ($id == 24 OR $id == 28)
			{
				unset($array["StoreId"]);
				unset($array["UserId"]);
				
				$newString = $type;
				
				if(array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " users u, store s, location l,";
				}
				else if(array_key_exists("StoreName", $array) AND !array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " users u, store s,";
				}
				else if((array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)) AND !array_key_exists("StoreName", $array) AND !array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " users u,";
				}
				$newString = substr($newString, 0, -1);
				$newString = $newString . " SET ";

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
				
				$newString = $newString . " WHERE";
				if(array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
				}
				else if(array_key_exists("StoreName", $array) AND !array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?)";
				}
				else if((array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)) AND !array_key_exists("StoreName", $array) AND !array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " u.ID = ?";
				}
				
				/*if(array_key_exists("StoreLocation", $array) AND array_key_exists("StoreName", $array) AND (array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
				}
				
				if(array_key_exists("StoreLocation", $array) AND array_key_exists("StoreName", $array))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
				}
				
				if(array_key_exists("StoreLocation", $array) AND (array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
				}
				
				if(array_key_exists("StoreName", $array) AND (array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?)";
				}
				
				if(array_key_exists("StoreLocation", $array))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?) AND s.ID = l.IDStore";
				}
				
				if(array_key_exists("StoreName", $array))
				{
					$newString = $newString . " u.ID = ? AND u.ID = s.IDOwner AND (s.ID = ? OR s.name = ?)";
				}
				
				if(array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array))
				{
					$newString = $newString . " u.ID = ?";
				}*/
				
				return $newString;
			}
			/*else if ($id == 28)
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
			}*/
			else if ($id == 30)
			{
				unset($array["ItemId"]);
				unset($array["UserName"]);
				
				$newString = $type . " item i, users u, store s SET ";
				foreach ($array as $key => $x)
				{
					if ($key != "InterfaceId" AND $key != "CurrentUser")
					{
						$add = str_replace("item", "", strtolower($key));
						$newString = $newString . "i." . "$add = ?, ";
					}
				}
				$newString = substr($newString, 0, -2);
				$newString = $newString . " WHERE u.username = ? AND u.ID = s.IDOwner AND s.ID = i.IDStore AND i.ID = ?";
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
				unset($array["UserId"]);
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
			else if($id == 24 OR $id == 28)
			{
				$storename = "";
				if(array_key_exists("StoreName", $array) OR array_key_exists("StoreLocation", $array))
				{
					$this->_dbSelect = DB::select('SELECT name
												FROM store
												WHERE IDOwner = ?',
												[$idUser]);
												
					if (count($this->_dbSelect) == 1)
					{
						foreach($this->_dbSelect as $Obj)
						{
							//Here we separete each result from DB in their diferent keys and values
							foreach($Obj as $StoreName => $input)
							{
								$storename = $input;
							}
						}
					}
					else
					{
						return '{"ERROR": "No StoreName found connected to this Owner account"}';
					}
				}
				
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
				
				if(!isset($array["StoreId"]))
				{
					$array["StoreId"] = "0";
				}
				
				if((array_key_exists("UserName", $array) OR array_key_exists("UserPassword", $array)) AND !array_key_exists("StoreName", $array) AND !array_key_exists("StoreLocation", $array))
				{
					array_push($newArray, $idUser);
				}
				else
				{
					array_push($newArray, $idUser);
					array_push($newArray, $array["StoreId"]); //THIS ONE IS THE OPTIMAL OPTION (owner can have more then one store)
					array_push($newArray, $storename); //THIS ONE IS ONLY TO ADAPT TO THE CODE OT OTHER GROUPS (in case owner can only have one store)
				}

				return $newArray;
			}
			/*else if($id == 28)
			{
				$this->_dbSelect = DB::select('SELECT name
												FROM store
												WHERE IDOwner = ?',
												[$idUser]);
												
				if (count($this->_dbSelect) == 1)
				{
					foreach($this->_dbSelect as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $StoreName => $input)
						{
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
							
							if(!isset($array["StoreId"]))
							{
								$array["StoreId"] = "0";
							}
							
							array_push($newArray, $idUser);
							array_push($newArray, $array["StoreId"]); //THIS ONE IS THE OPTIMAL OPTION (owner can have more then one store)
							array_push($newArray, $input); //THIS ONE IS ONLY TO ADAPT TO THE CODE OT OTHER GROUPS (in case owner can only have one store)
							return $newArray;
						}
					}
				}
				else
				{
					return $newArray = []; //NEED TO FIND OTHER OPTION!!!! OR MAKE A TRY ON UPDATE!!!!
				}
			}*/
			else if ($id == 30)
			{
				unset($array["ItemId"]);
				$this->_dbSelect = DB::select('SELECT name
												FROM item
												WHERE ID = ?',
												[$idUser]);
												
				if (count($this->_dbSelect) == 1)
				{
					$newArray = [];
					foreach ($array as $key => $x)
					{
						if ($key != "InterfaceId" AND $key != "CurrentUser" AND $key != "UserName")
						{
							array_push($newArray, $x);
						}
					}
					array_push($newArray, $array["UserName"]);
					array_push($newArray, $idUser);
					return $newArray;
				}
				else
				{
					return '{"ERROR": "No ItemName found connected to this IDItem"}';
				}
			}
		}
	}
}
?>