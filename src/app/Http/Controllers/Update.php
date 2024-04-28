<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ControllerQueryUpdate extends Controller
{
	private $_dbUpdate
	private $_dbSelect
	
	public function QueryUpdate($ID, $array)
	{
		switch($ID)
		{
			//Interface 21 web::Management ==> database::Administrator (Update userData)
			//ADMIN UPDATES USER INFO
			case 21:
			//Interface 10 frontend::Customer ==> database:: Customer
			//USER UPDATES THEIR INFO
			case 10:
				$this->_dbSelect = DB::select('SELECT ID
												FROM users 
												WHERE array_keys($array)[2]=? and array_keys($array)[3]=?', 
												[$array["username"], $array["password"]]);
				if (count($this->_dbSelect) == 1)
				{
					//i.array_keys($array["interests"])[?] = ?, -> dependes of how many interest it is.
					//Possible UPDATE!!!! ->build IT before going in here! 
					$this->_dbUpdate = DB::update('UPDATE users u, interests i
													SET u.array_keys($array)[2] = ?, 
													u.array_keys($array)[3] = ?, 
													u.array_keys($array)[4] = ?, 
													i.array_keys($array["interests"])[0] = ?, 
													i.array_keys($array["interests"])[1] = ?, 
													i.array_keys($array["interests"])[2] = ?, 
													(...)
													WHERE u.ID = ?
                                                    AND u.IDInterests = i.ID', 
										[$array["array_keys($array)[2]"], $array["password"], $array["bday"], 
										$array["interests"][0], $array["interests"][1], $array["interests"][2], $this->_dbSelect]);
				}
				else
				{
					return "ERROR";
				}
			break;
			
			//UPDATE BY ADMIN OF OWNER ACCOUNT + STORE AND LOCATION
			case ??:
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
			break;
		}
	}
}
?>