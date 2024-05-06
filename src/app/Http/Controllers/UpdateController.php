<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
	private $_dbUpdate;
	private $_newArray;
	
	public function QueryUpdate($ID, $array)
	{
		$SuccessToken = "";
		switch($ID)
		{
			//Interface 21 web::Management ==> database::Administrator (Update userData)
			//ADMIN UPDATES USER INFO
			case 21:
			//Interface 10 frontend::Customer ==> database:: Customer
			//USER UPDATES THEIR INFO
			case 10:
				$temporary = DB::select('SELECT ID
												FROM users 
												WHERE username = ? and password = ?', 
												[$array["UserName"], $array["PassWord"]]);

				if (count($temporary) == 1)
				{
					//i.array_keys($array["interests"])[?] = ?, -> dependes of how many interest it is.
					//Possible UPDATE!!!! ->build IT before going in here!
					
					$query = new QueryBuilderController();
					$string = $query -> StringBuilder("UPDATE", $ID, $array);
					foreach ( $temporary as $x)
					{
						foreach ($x as $y)
						{
							$values = $query -> ArrayBuilder("UPDATE", $ID, $y, $array);
						}
					}
					print_r($values);
					echo $string;
					
					$this->_dbUpdate = DB::update($string, $values);
					
					if($this->_dbUpdate == 1)
					{
						$SuccessToken = true;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
											"SuccessToken" => $SuccessToken);
					}
					else
					{
						$SuccessToken = false;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
											"SuccessToken" => $SuccessToken);
					}
				}
				else
				{
					$this->_dbUpdate = "ERROR, User not Found!";
				}
			break;
			
			//UPDATE BY ADMIN OF OWNER ACCOUNT + STORE AND LOCATION
			/*case ??:
				$this->_dbSelect = DB::select('SELECT ID
												FROM users 
												WHERE ID = ?', 
												[$array["UserId"]]);
				if (count($this->_dbSelect) == 1)
				{
					//i.array_keys($array["interests"])[?] = ?, -> dependes of how many interest it is.
					//Possible UPDATE!!!! ->build IT before going in here! 
					$this->_dbUpdate = DB::update('UPDATE users u, location l, store s
													SET u.username = ?, 
													u.password = ?, 
													l.latitude = ?, 
													l.longitude = ?, 
													l.country = ?, 
													l.state = ?, 
													l.city = ?, 
													l.street = ?, 
													l.number = ?, 
													l.floor = ?, 
													l.zipcode = ?,
													s.name = ?, 
													s.type = ?, 
													s.description = ?,
													WHERE u.ID = ?
                                                    AND u.ID = s.IDOwner
													AND s.IDLocation = l.ID', 
										[$array[$array["username"]], $array["password"], $array["StoreLocation"]["latitude"], $array["StoreLocation"]["longitude"], 
										$array["StoreLocation"]["country"], $array["StoreLocation"]["state"], $array["StoreLocation"]["city"], 
										$array["StoreLocation"]["street"], $array["StoreLocation"]["number"], $array["StoreLocation"]["floor"], 
										$array["StoreLocation"]["zipcode"], $array["StoreLocation"]["latitude"], $array["StoreInfo"]["name"], 
										$array["StoreInfo"]["type"], $array["StoreInfo"]["description"], $array["UserId"]]);
				}
				else
				{
					return "ERROR";
				}
			break;*/
		}
		return $newArray = $this->_newArray;
	}
}
?>