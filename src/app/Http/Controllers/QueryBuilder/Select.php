<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ControllerQuerySelect extends Controller
	
	private $_dbSelect
	
	public QuerySelect($ID, $array)
	{
		switch($ID)
		{
			//Interface 1 frontend::Login ==> database::Login
			case 1:
			//Interface 19 web::Login ==> database::Login (Login)
			case 19:
			//USER LOGIN
				$this->_dbSelect = DB::select('SELECT ID, username, bday 
												FROM users 
												WHERE array_keys($array)[2]=? and array_keys($array)[3]=?', 
												[$array["username"], $array["password"]]);
			break;
			
			//Interface 3 frontend::Store ==> database::Store
			//SHOW STORE INFO
			case 3:
				this->_dbSelect = DB::select('SELECT s.name, s.type, s.description, l.*
												FROM store s
												JOIN location l ON u.IDLocation = l.ID
												WHERE s.name = ?', 
												["%$array["StoreName"]%"]);
			
				//$this->_dbSelect = DB::table('store')->where('name', '%$array["StoreName"]%')->get();
			break;
			
			//Interface 23 web::Management ==> database::Administrator
			//SHOW STORE INFO TO ADMIN
			case 23:
				this->_dbSelect = DB::select('SELECT s.name, s.type, s.description, l.*
												FROM store s
												JOIN location l ON u.IDLocation = l.ID');
			break;
			
			//Interface 5 database::HuntedStore ==> frontend::HuntedStore
			case 5:
				$this->_dbSelect = DB::select('select s.name, h.date_time 
												from store s, huntedstore h, users u 
												where u.username=? and u.ID = h.IDUser and h.IDStore = s.ID', 
												[$array["username"]]);
			break;
			
			//Interface 6 frontend::Item ==> database:Item
			case 6:
				$this->_dbSelect = DB::table('item')->where('name', '%$array["ItemName"]%')->get();
			break;
			
			//Interface 13 database::Store ==> algorithm::Store
			case 13:
			//Interface 7 frontend::Map ==> database::Map
			case 7:
			//SHOW STORES AROUND X FROM USER
				/*
				$this->_dbSelect = store::select('id', 'name', 'latitude', 'longitude')
									->selectRaw(
										'( 6371 * acos( cos( radians(?) ) *
										cos( radians( latitude ) ) *
										cos( radians( longitude ) - radians(?) ) +
										sin( radians(?) ) *
										sin( radians( latitude ) ) ) ) AS distance',
										[$userLat, $userLng, $userLat]
									)
									->having('distance', '<=', $maxDistance)
									->orderBy('distance', 'asc')
									->get();
									$array["UserLatitude"]
									*/
				switch($array["RequestType"])
				{
					case 1:
						$this->_dbSelect = DB::select(
						'SELECT s.name, s.type, s.description, l.latitude, l.longitude 
						FROM store s, location l  
						WHERE ( 6371 * acos( cos( radians(?) ) *
								cos( radians( l.latitude ) ) *
								cos( radians( l.longitude ) - radians(?) ) +
								sin( radians(?) ) *
								sin( radians( l.latitude ) ) ) ) <= $array["maxDistance"] 
								AND l.ID = s.IDLocation 
						ORDER BY name ASC', [$array["UserLatitude"], $array["UserLongitude"], $array["UserLatitude"]])->get();
					break;
					
					//CONFIRMAR PONTO DE EXCLAMAÇÃO, LINHA 76!!!!!
					case 2:
						$this->_dbSelect = DB::select(
						'SELECT s.name, s.type, s.description, l.latitude, l.longitude 
						FROM store s, location l  
						WHERE ( 6371 * acos( cos( radians(?) ) *
								cos( radians( l.latitude ) ) *
								cos( radians( l.longitude ) - radians(?) ) +
								sin( radians(?) ) *
								sin( radians( l.latitude ) ) ) ) <= $array["maxDistance"] 
								AND l.ID = s.IDLocation 
								AND s.type IN (".implode(',', array_fill(0, count($array["type"]), '?')).") 
						ORDER BY name ASC', [$array["UserLatitude"], $array["UserLongitude"], $array["UserLatitude"]])->get();
					break;
			break;
			
			//Interface 20 web::Management ==> database::Administrator
			//SHOW USER_X CUSTOMER_X(S) INFO
			case 20:
			//Interface11 database::Customer ==> frontend:: Customer
			//SHOW USER THEIR INFO
			case 11:
				$this->_dbSelect = DB::select('SELECT u.*, i.*
												FROM users u
												JOIN interests i ON u.IDInterests = i.ID');
			break;
			
			//Interface 14 database::Customer ==> algorithm::user
			//SHOW TO ALGORITHM THE INTERESTS OF USER
			case 14:
				$this->_dbSelect = DB::select('SELECT u.ID, i.*
												FROM users u
												JOIN interests i ON u.IDInterests = i.ID
												WHERE u.ID = ?', 
												[$array["IDUser"]]);
			break;
			
			//NEED TO UPDATE -> KNOW MORE ABOUT!
			//Interface 15 database::Item ==> algorithm::recommendation algorithm
			//??
			case 15:
				$this->_dbSelect = DB::select('SELECT i.*
												FROM item i
												WHERE ??????????', 
												[????????????????]);
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
	}
}
?>