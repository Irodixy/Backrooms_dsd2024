<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
	private $_dbSelect;
	private $_newArray;
	
	
	public function QuerySelect($ID, $array)
	{
		$MatchToken = "";
		$temporary = [];
		switch($ID)
		{
			//Interface 1 frontend::Login ==> database::Login
			case 1:
			//Interface 19 web::Login ==> database::Login (Login)
			case 19:
			//USER LOGIN
			
				//echo $ID;
				//print_r($array);
				
				$this->_dbSelect = DB::select('SELECT ID, username 
												FROM users 
												WHERE username = ? and password = ?', 
												[$array["UserName"], $array["PassWord"]]);
				
				if(count($this->_dbSelect) == 1)
				{
					$MatchToken = true;
					$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["UserName"],
										"MatchToken" => $MatchToken);
				}
				else
				{
					$MatchToken = false;
					$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => NULL,
										"MatchToken" => $MatchToken);
				}
				
			break;
			
			//Interface 3 frontend::Store ==> database::Store
			//SHOW STORE INFO
			case 3:
				//Select info of STORE
				$this->_dbSelect = DB::select('SELECT s.ID AS storeId, s.name AS storeName, s.type, s.description AS StoreDescription 
												FROM store s
												WHERE s.name LIKE ?', 
												['%' . $array["StoreName"] . '%']);
				
				//Variables to save data and help reorganize the future JSON file			
				$StoreList = [];
				$location = [];
					$items = [];
					$save = [];
					
				//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						//store Id is necessary to get more necessary data from DB, so we use an "if"
						//to use this key value in all other select querys
						if($key === "storeId")
						{
							$save[$key] = $x;
							
							$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
												FROM store s, location l
												WHERE s.ID = l.IDStore
												AND s.ID = ?', 
												[$x]);
												
							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									foreach($Obj as $key => $y)
									{
										$location[$key] = $y;
									}
								}
							}
							else
							{
								$location = "ERROR, location not found";
							}

							$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.imgName AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
												FROM item i, store s
												WHERE s.ID = ?
												AND i.IDStore = s.ID', 
												[$x]);

							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									$oneItem = [];
									foreach($Obj as $key => $y)
									{
										$oneItem[$key] = $y;
									}
									array_push($items, $oneItem);
								}
							}
							else
							{
								$items = array("ERROR, item not found");
							}
						}
						else if($key === "storeName")
						{
							$save[$key] = $x;
						}
						else if($key === "StoreDescription")
						{
							$save['location'] = $location;
							$save['items'] = $items;
							$save[$key] = $x;
							
							array_push($StoreList, $save);
						}
					}				
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
											"StoreList" => $StoreList);
			break;
			
			//Interface 23 web::Management ==> database::Administrator
			//SHOW STORE INFO TO ADMIN
			case 23:
				$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, s.ID, s.name AS StoreName
									FROM users u, store s
									WHERE s.IDOwner = u.ID');

				$StoreData = [];
				$save = [];
				$StoreLocation = [];
				$AvgRate = [];			
				print_r($this->_dbSelect);
				foreach($this->_dbSelect as $Obj)
				{
					$i = 0;
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						if($key != "ID")
						{
							$save[$key] = $x;
						}
						else 
						{
							$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
												FROM store s, location l
												WHERE s.ID = l.IDStore
												AND s.ID = ?', 
												[$x]);
												
							foreach($temporary as $Objs)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $y)
								{
									$StoreLocation[$keys] = $y;
								}
							}
							
							$temporary = DB::select('SELECT AVG(rating) AS AvgRate
												FROM store s, feedback f
												WHERE s.ID = f.IDStore
												AND s.ID = ?', 
												[$x]);
												
							foreach($temporary as $Objs)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $y)
								{
									$AvgRate = $y;
								}
							}
							
							$save["StoreLocation"] = $StoreLocation;
							$save["AvgRate"] = $AvgRate;
						}

						if($i >= 3)
						{
							array_push($StoreData, $save);
							$i = 0;
						}
						else
						{
							$i++;
						}
						
					}
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
										"StoreData" => $StoreData);
			break;
			
			//Interface 5 database::HuntedStore ==> frontend::HuntedStore
			//DISPLAY SEARCH STORE HISTORY TO CUSTOMER
			case 5:
				$this->_dbSelect = DB::select('SELECT s.ID, s.name, h.date_time 
												FROM store s, huntedstore h, users u 
												WHERE u.username = ? 
												AND u.ID = h.IDUser 
												AND h.IDStore = s.ID', 
												[$array["CurrentUser"]]);
												
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
							
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
											"HuntedStoreIdList" => $temporary);
			break;
			
			//Interface 6 frontend::Item ==> database:Item
			//DISPLAY TO CUSTOMER ITEMS THAT HE/SHE SEARCH
			case 6:
				$this->_dbSelect = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, 
												i.imgName AS ItemImage, i.IDStore AS ItemStoreId
												FROM item i
												WHERE i.name LIKE ?', 
												['%' . $array["ItemName"] . '%']);
												
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
					$temporary = array("ERROR, no item found");
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
											"ItemList" => $temporary);
			break;
			
			//Interface 7 frontend::Map ==> database::Map
			case 7:
			//SHOW STORES AROUND X DISTANCE FROM USER
				$this->_dbSelect = DB::select(
						'SELECT s.ID AS storeId, s.name AS storeName, s.type, s.description AS StoreDescription 
						FROM store s, location l  
						WHERE ( 6371 * acos( cos( radians(?) ) *
								cos( radians( l.latitude ) ) *
								cos( radians( l.longitude ) - radians(?) ) +
								sin( radians(?) ) *
								sin( radians( l.latitude ) ) ) ) <= ? 
								AND s.ID = l.IDStore 
						ORDER BY name ASC', 
						[$array["MyLocation"]["latitude"], $array["MyLocation"]["longitude"], 
						$array["MyLocation"]["latitude"], $array["maxDistance"]]);
				
				//Variables to save data and help reorganize the future JSON file			
				$StoreList = [];
				$location = [];
					$items = [];
					$save = [];
				
				switch($array["RequestType"])
				{
					//CUSTOMER SEES STORES WITHIN X DISTANCE
					case 1:
					//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
					foreach($this->_dbSelect as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							//store Id is necessary to get more necessary data from DB, so we use an "if"
							//to use this key value in all other select querys
							if($key === "storeId")
							{
								$save[$key] = $x;
								
								$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
													FROM store s, location l
													WHERE s.ID = l.IDStore
													AND s.ID = ?', 
													[$x]);
													
								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										foreach($Obj as $key => $y)
										{
											$location[$key] = $y;
										}
									}
								}
								else
								{
									$location = "ERROR, location not found";
								}

								$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.imgName AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
													FROM item i, store s
													WHERE s.ID = ?
													AND i.IDStore = s.ID', 
													[$x]);

								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										$oneItem = [];
										foreach($Obj as $key => $y)
										{
											$oneItem[$key] = $y;
										}
										array_push($items, $oneItem);
									}
								}
								else
								{
									$items = array("ERROR, item not found");
								}
							}
							else if($key === "storeName")
							{
								$save[$key] = $x;
							}
							else if($key === "StoreDescription")
							{
								$save['location'] = $location;
								$save['items'] = $items;
								$save[$key] = $x;
								
								array_push($StoreList, $save);
							}
						}				
					}
					
					$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
												"StoreList" => $StoreList);
				break;
				
				//USER SEES STORES RECOMENDED FROM AI
				case 2:
					//CODE TO REQUEST INFO FROM AI!!!!
					//WILL BE SAVED IN SOME VARIABLE OR SAME PROPERTY, AND THEN ALL CODE IS NORMAL FROM HERE!
					// WILL BE NECESSARY OPTIMIZATION OF THE CODE!!! VERY REPETIVE!!
				
				
				//SELECT FOR INTERFACE 13!!!!!!
				
					//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
					foreach($this->_dbSelect as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							//store Id is necessary to get more necessary data from DB, so we use an "if"
							//to use this key value in all other select querys
							if($key === "storeId")
							{
								$save[$key] = $x;
								
								$temporary = DB::select('SELECT i.ID AS ItemId, i.name AS ItemName, i.price AS ItemPrice, i.description AS ItemDescription, i.imgName AS ItemImage, i.IDStore AS ItemStoreId, s.name AS ItemStoreName
													FROM item i, store s
													WHERE s.ID = ?
													AND i.IDStore = s.ID', 
													[$x]);

								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										$oneItem = [];
										foreach($Obj as $key => $y)
										{
											$oneItem[$key] = $y;
										}
										array_push($items, $oneItem);
									}
								}
								else
								{
									$items = array("ERROR, item not found");
								}
							}
							else if($key === "storeName")
							{
								$save[$key] = $x;
							}
							else if($key === "StoreDescription")
							{
								$save['items'] = $items;
								$save[$key] = $x;
								
								array_push($StoreList, $save);
							}
						}				
					}
					
					$newArray = array("InterfaceId" => 13, "CurrentUser" => $array["CurrentUser"], "StoreList" => $StoreList);
					
					$pickStores = new IdentifyInterface();
					$pickStores->InterfaceID($newArray["InterfaceId"], $newArray);
					
					
					//Select info of USER FOR INTERFACE 14
					
					$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, u.email AS UserEmail 
													FROM users u
													WHERE u.username = ?', 
													[$array["CurrentUser"]]);
					
					//Variables to save data and help reorganize the future JSON file			
					$UserData = [];
					$Interests = [];
					$HuntedStoreIdList = [];
					$save = [];
						
					//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
					foreach($this->_dbSelect as $Obj)
					{
						//Here we separete each result from DB in their diferent keys and values
						foreach($Obj as $key => $x)
						{
							//store Id is necessary to get more necessary data from DB, so we use an "if"
							//to use this key value in all other select querys
							if($key === "UserId")
							{
								$save[$key] = $x;
								
								$temporary = DB::select('SELECT i.*
													FROM users u, interests i
													WHERE u.ID = ?
													AND u.ID = i.IDUser', 
													[$x]);
													
								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										foreach($Obj as $key => $y)
										{
											$Interests[$key] = $y;
										}
									}
								}
								else
								{
									$Interests = "ERROR, interests not found";
								}

								$temporary = DB::select('SELECT IDStore AS StoreId, date_time AS VisitTime
													FROM huntedstore
													WHERE IDUser = ?', 
													[$x]);

								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										$oneSearch = [];
										foreach($Obj as $key => $y)
										{
											$oneSearch[$key] = $y;
										}
										array_push($HuntedStoreIdList, $oneSearch);
									}
								}
								else
								{
									$HuntedStoreIdList = array("ERROR, item not found");
								}
							}
							else 
							{
								$save[$key] = $x;
							}
						}				
					}
					
					$save['Interests'] = $Interests;
					$save['HuntedStoreIdList'] = $HuntedStoreIdList;

					array_push($UserData, $save);
					
					$newArray = array("InterfaceId" => 14, "CurrentUser" => $array["CurrentUser"],
												"UserData" => $UserData);
											
					$pickUser = new IdentifyInterface();
					$pickUser->InterfaceID($newArray["InterfaceId"], $newArray);
					
					//AFTER SENDING ALL THE NEED INFO, REQUEST A GET TO RECEIVE THE NECESSARY RECOMMENDATION!!!
					
					$getRecommendation = new IdentifyInterface();
					$this->_newArray = $getRecommendation->InterfaceID(16);
					break;
				}
			break;
			
			//Interface 20 web::Management ==> database::Administrator
			//SHOW USER_X CUSTOMER_X(S) INFO
			case 20:
				$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.bday AS Birthday, u.email AS Email
												FROM users u');
				
				$UserData = [];
				$save = [];
				$Interests = [];	
					
				foreach($this->_dbSelect as $Obj)
				{
					$i = 0;
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						if($key != "ID")
						{
							$save[$key] = $x;
						}
						else 
						{
							$save[$key] = $x;
							
							$temporary = DB::select('SELECT i.*
												FROM users u, interests i
												WHERE u.ID = ?
												AND u.ID = i.IDUser',
												[$x]);
												
							foreach($temporary as $Objs)
							{
								//Here we separete each result from DB in their diferent keys and values
								foreach($Objs as $keys => $y)
								{
									$Interests[$keys] = $y;
								}
							}
							
							$save["Interests"] = $Interests;
						}

						if($i >= 3)
						{
							array_push($UserData, $save);
							$i = 0;
						}
						else
						{
							$i++;
						}
					}
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
										"UserData" => $UserData);
			break;
			
			//Interface11 database::Customer ==> frontend:: Customer
			//SHOW USER THEIR INFO
			case 11:
				$this->_dbSelect = DB::select('SELECT u.ID, u.username AS UserName, u.bday AS Birthday
												FROM users u
												WHERE u.username = ?',
												[$array["CurrentUser"]]);
				
				$save = [];
				$Interests = [];	
					
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						if($key != "ID")
						{
							$save[$key] = $x;
						}
						else
						{
							$temporary[$key] = $x;
						}
					}
				}

				$this->_dbSelect = DB::select('SELECT i.*
												FROM users u, interests i
												WHERE u.ID = ?
												AND u.ID = i.IDUser',
												[$temporary["ID"]]);
												
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						$Interests[$key] = $x;
					}
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
												"UserName" => $save["UserName"], "Birthday" => $save["Birthday"], 
												"Interests" => $Interests);
					
			break;
			
			//Interface 14 database::Customer ==> algorithm::user
			//SHOW TO ALGORITHM THE INTERESTS OF USER
			/*case 14:						
				//Select info of USER
				$this->_dbSelect = DB::select('SELECT u.ID AS UserId, u.username AS UserName, u.email AS UserEmail 
												FROM users u
												WHERE u.username = ?', 
												[$array["CurrentUser"]]);
				
				//Variables to save data and help reorganize the future JSON file			
				$UserData = [];
				$Interests = [];
				$HuntedStoreIdList = [];
				$save = [];
					
				//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						//store Id is necessary to get more necessary data from DB, so we use an "if"
						//to use this key value in all other select querys
						if($key === "UserId")
						{
							$save[$key] = $x;
							
							$temporary = DB::select('SELECT i.*
												FROM users u, interests i
												WHERE u.ID = ?
												AND u.ID = i.IDUser', 
												[$x]);
												
							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									foreach($Obj as $key => $y)
									{
										$Interests[$key] = $y;
									}
								}
							}
							else
							{
								$Interests = "ERROR, interests not found";
							}

							$temporary = DB::select('SELECT IDStore AS StoreId, date_time AS VisitTime
												FROM huntedstore
												WHERE IDUser = ?', 
												[$x]);

							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									$oneSearch = [];
									foreach($Obj as $key => $y)
									{
										$oneSearch[$key] = $y;
									}
									array_push($HuntedStoreIdList, $oneSearch);
								}
							}
							else
							{
								$HuntedStoreIdList = array("ERROR, item not found");
							}
						}
						else 
						{
							$save[$key] = $x;
						}
					}				
				}
				
				$save['Interests'] = $Interests;
				$save['HuntedStoreIdList'] = $HuntedStoreIdList;

				array_push($UserData, $save);
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
											"UserData" => $UserData);
			break;*/
			
			//NEED TO UPDATE -> KNOW MORE ABOUT!
			//Interface 15 database::Item ==> algorithm::recommendation algorithm
			//??
			case 15:
				/*$this->_dbSelect = DB::select('SELECT i.*
												FROM item i
												WHERE ??????????', 
												[????????????????]);*/
			break;
			
			/*case 17:
				$this->_dbSelect = DB::select('SELECT s.ID AS StoreId, f.comment, f.rating
												FROM feedback f, users u, store s
												WHERE u.username = ?
												AND u.ID = f.IDUser
												AND s.ID = f.IDStore 
												AND f.IDStore IS NOT NULL', [$array["CurrentUser"]]);
	
				$Feedback = [];	
				
				foreach($this->_dbSelect as $Obj)
				{
					$oneFeedback = [];
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						$oneFeedback[$key] = $x;
					}
					array_push($Feedback, $oneFeedback);
				}
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
										"Feedback" => $Feedback);
			break;*/
			
			//Interface 26 web::Analytics ==> database::Analytics
			//ADMIN SEES SELECT RATINGS TO REFLECT
			case 26:
				$this->_dbSelect = DB::select('SELECT comments, rating
												FROM feedback, 
												WHERE rating >= 3
												AND u.username = ?', 
												[$array["CurrentUser"]]);
			break;
			
			case 27:
				$this->_dbSelect = DB::select('SELECT s.ID, s.name AS StoreName
												FROM users u, store s 
												WHERE u.username = ?
												AND s.IDOwner = u.ID', 
												[$array["UserName"]]);
												
				$location = [];
				$Feedback = [];
					$save = [];
												
				//Because an instance is created, we need to separete first the diferent rows that comes from the DB	
				foreach($this->_dbSelect as $Obj)
				{
					//Here we separete each result from DB in their diferent keys and values
					foreach($Obj as $key => $x)
					{
						//store Id is necessary to get more necessary data from DB, so we use an "if"
						//to use this key value in all other select querys
						if($key === "ID")
						{
							$temporary = DB::select('SELECT l.latitude, l.longitude, l.country, l.state, l.city, l.street, l.number, l.floor, l.zipcode
													FROM store s, location l
													WHERE s.ID = l.IDStore
													AND s.ID = ?', 
													[$x]);
												
							if(count($temporary) > 0)
							{						
								foreach($temporary as $Obj)
								{
									foreach($Obj as $key => $y)
									{
										$location[$key] = $y;
									}
								}
							}
							else
							{
								$location = "ERROR, location not found";
							}

							$temporary = DB::select('SELECT AVG(rating) AS AvgRate
													FROM store s, feedback f
													WHERE s.ID = f.IDStore
													AND s.ID = ?', 
													[$x]);
							
							if(count($temporary) > 0)
							{							
								foreach($temporary as $Objs)
								{
									//Here we separete each result from DB in their diferent keys and values
									foreach($Objs as $keys => $y)
									{
										$AvgRate = $y;
									}
								}
							}
							else
							{
								$AvgRate = array("ERROR, Average not possible to calculate");
							}
							
							$temporary = DB::select('SELECT 
													FROM item i, store s
													WHERE s.ID = ?
													AND i.IDStore = s.ID', 
													[$x]);

								if(count($temporary) > 0)
								{						
									foreach($temporary as $Obj)
									{
										$oneFeedback = [];
										foreach($Obj as $key => $y)
										{
											$oneFeedback[$key] = $y;
										}
										array_push($Feedback, $oneFeedback);
									}
								}
								else
								{
									$Feedback = array("ERROR, Feedbacks not found");
								}
						}
						else
						{
							$save[$key] = $x;
							$save['StoreLocation'] = $location;
							$save['AvgRate'] = $AvgRate;
							$save['Feedback'] = $Feedback;

							array_push($StoreList, $save);
						}
					}				
				}
				
				$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"],
											"StoreList" => $StoreList);
		break;
			
			case 28:
				$this->_dbSelect = DB::select('SELECT u.username, s.name, i.name, f.comment, f.rating, f.date
												FROM feedback f
												LEFT JOIN users u ON u.ID = f.IDUser 
												LEFT JOIN store s ON s.ID = f.IDStore AND f.IDStore IS NOT NULL
												LEFT JOIN item i ON i.ID = f.IDItem AND f.IDItem IS NOT NULL
												WHERE u.username = ?', [$array["username"]]);
			break;
		}
		return $newArray = $this->_newArray;
		//return $newArray = $this->_dbSelect;
	}
}
?>