<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ControllerQueryInsert extends Controller
{
	public QueryInsert($ID, $array) {
		switch($ID)
		{
			//Interface 4 frontend::HuntedStore ==> database::HuntedStore
			case 4:
				//do a try (still not done)
				DB::insert('insert into huntedStore (IDUser, IDStore) values(?, ?)', [$array["IDUser"], $array["IDStore"]]);
			break;
			//Interface 8 frontend::Feedback2Store ==> database::Feedback2Store
			case 8:
				DB::insert('insert into feedback (IDUser, IDStore, comment, rating) 
							values (?, ?, ?, ?)', 
							[$array["IDUser"], $array["IDStore"], $array["comment"], $array["rating"]]);
			break;
			//Interface 9 frontend::Feedback2Item ==> database::Feedback2Item
			case 9:
				DB::insert('INSERT into feedback (IDUser, IDItem, comment, rating) 
							values (?, ?, ?, ?)', 
							[$array["IDUser"], $array["IDItem"], $array["comment"], $array["rating"]]);
			break;
			//Interface 12 frontend::Registration ==> database::Registration
			case 12:
			//Interface 18	web::Registration ==> database::Registration
			case 18:
			//REGISTRATION OF USER
				$check = DB::select('SELECT username
									FROM users
									WHERE username = ?', 
									[$array["username"]]);
				if($check == 0)
				{
					DB::insert('INSERT into users (username, password, bday, type) 
							values (?, ?, ?, ?)', 
							[$array["username"], $array["password"], $array["bday"], $array["type"]]);
				}
				else
				{
					return "ERROR";
				}
				
				return $token = true;
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
	}
}
?>

INSERT into users
(username, password, email)
VALUES ("Owner Matias", "kidjkifsjfsf", "matias23@gmail.com");

INSERT into owner
(IDUser)
VALUE ((SELECT ID FROM users WHERE username = "Owner Matias" AND password = "kidjkifsjfsf"));

INSERT into store
(IDOwner, name, type, description)
VALUES ((SELECT ID
		FROM users
		WHERE username = "Owner Matias"
		AND password = "kidjkifsjfsf"), "Comi Bem", "marisqueira", "Coma o melhor camarão da sua vida");
        
INSERT into location 
(IDStore, latitude, longitude, country, state, city, street, number, floor, zipcode) 
VALUES ((SELECT ID
		FROM store
		WHERE name = "Comi Bem"),41.2910451618087, -7.753595445490844, "Portugal","" , "Vila Real", "Rua Mioleira", 2, "5º", "4560-555");