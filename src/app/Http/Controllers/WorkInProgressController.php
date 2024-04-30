<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class WorkInProgressController extends Controller
{
	public function index()
	{
		$users = DB::select('SELECT * FROM users u');
		$interests = DB::select('SELECT u.username, i.* FROM users u, interests i WHERE u.ID = i.IDUser');
		//print_r($users);
		//print_r($interests);
		return view('/DBTest', ['users' => $users]);
	}
	
	public function workFile()
	{
		//$uploadFile = new PreparationController()
		//$uploadFile->uploadFile(
		
		/*$jsonFile = 'app/json/data.json';
		$registerFile = new JsonController();
		$info = $registerFile->transformJsonToString($jsonFile);
		
		$doQuery = new IdentifyInterface();
		$resultQuery = $doQuery->InterfaceID($info["interfaceID"], $info);
		*/
		//With the data colected, will input it on DB
		//DB::insert('insert into users (username, email, password, bday, name) values (?, ?, ?, ?, ?)', [$info["username"], $info["email"], $info["password"], $info["bday"], "Gus"]);
		//return view('showRead', ['info' => $info]);
		$users = "oi";
		
	}
}


?>