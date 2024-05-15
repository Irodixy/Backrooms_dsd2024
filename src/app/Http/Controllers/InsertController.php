<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InsertController extends Controller
{
	private $_dbInsert;
	private $_newArray;
	
	public function QueryInsert($ID, $array) 
	{
		$SuccessToken = "";
		switch($ID)
		{
			//Interface 4 frontend::HuntedStore ==> database::HuntedStore
			case 4:
			$save = [];
			
				//do a try (still not done)
				$temporary = DB::select('SELECT ID AS userId
									FROM users 
									WHERE username = ?', 
									[$array["CurrentUser"]]);
				
				foreach($temporary as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						$save[$key] = $x;
					}
				}
				
				$this->_dbInsert = DB::insert('INSERT into huntedstore (IDUser, IDStore) values(?, ?)', 
												[$save["userId"], $array["HuntedStoreIdList"]]);
				if($this->_dbInsert == 1)
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
			break;
			
			//Interface 8 frontend::Feedback2Store ==> database::Feedback2Store
			case 8:
				$save = [];
			
				//do a try (still not done)
				$temporary = DB::select('SELECT ID AS userId
									FROM users 
									WHERE username = ?', 
									[$array["CurrentUser"]]);
				
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
							[$save["userId"], $array["Feedback"]["StoreId"], $array["Feedback"]["comment"], $array["Feedback"]["rating"]]);
							
				if($this->_dbInsert == 1)
				{
					$SuccessToken = true;
					$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
										"SuccessToken" => $SuccessToken);
			
					$array["InterfaceId"] = 17;
					$sendFeedbackStore = new IdentifyInterface();
					$sendFeedbackStore->InterfaceID($array["InterfaceId"], $array);
				}
				else
				{
					$SuccessToken = false;
					$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], 
										"SuccessToken" => $SuccessToken);
				}
			break;
			
			//Interface 9 frontend::Feedback2Item ==> database::Feedback2Item
			case 9:
				$save = [];
			
				//do a try (still not done)
				$temporary = DB::select('SELECT ID AS userId
									FROM users 
									WHERE username = ?', 
									[$array["CurrentUser"]]);
				
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
							[$save["userId"], $array["Feedback"]["ItemId"], $array["Feedback"]["comment"], $array["Feedback"]["rating"]]);
							
				if($this->_dbInsert == 1)
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
			break;
			//Interface 12 frontend::Registration ==> database::Registration
			case 12:
			//Interface 18	web::Registration ==> database::Registration
			case 18:
			//REGISTRATION OF USER
				$temporary = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$array["UserName"]]);
				if(!$temporary)
				{
					$this->_dbInsert = DB::insert('INSERT into users (username, password) 
										values (?, ?)', 
										[$array["UserName"], $array["PassWord"]]);
										
					if($this->_dbInsert == 1)
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
					$this->_newArray = "ERROR, username already exists";
				}
			break;

			//Interface 24 web::Management ==> database::Administrator
			//ADMIN CREATES NEW OWNER ACCOUNT + LOCATION AND STORE
			case 24:
				$check = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$array["username"]]);
				if($check == 0)
				{
					DB::insert('INSERT into users (username, password, bday, type) 
							values (?, ?, ?, ?)', 
							[$array["username"], $array["password"], $array["bday"], "owner"]);
				}
				else
				{
					return "ERROR";
				}
				
				DB::insert('INSERT into location 
							(latitude, longitude, country, state, city, street, number, floor, zipcode) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$array["latitude"], $array["longitude"], $array["country"], $array["state"], 
							$array["city"], $array["street"], $array["number"], $array["floor"], $array["zipcode"]]);
				$check = DB::select('"SELECT ID 
										FROM location 
										WHERE latitude = ?
										AND longitude = ?', [$array["latitude"], $array["longitude"]]);
				DB::insert('INSERT into store
							(name, type, description, IDLocation) 
							VALUES (?, ?, ?, ?)', 
							[$array["storeName"], $array["storeType"], $array["storeDescription"], 
							$check]);
				
				return $token = true;
			break;
			
			//Interface 30 web::Item ==> database::Item
			//OWNER UPDATE ITEM DATA OF STORE X
			case 28:

			break;
			
			//Interface 30 web::Item ==> database::Item
			//OWNER UPDATE ITEM DATA OF STORE X
			case 30:

			break;
		}
		return $newArray = $this->_newArray;//array("oi" => "oi");
	}
}
?>