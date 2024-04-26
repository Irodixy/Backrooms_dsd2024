<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WorkInProgressController extends Controller
{
	public function convertFile()
	{
		$jsonFile = 'app/json/data.json';
		$registerFile = new JsonController();
		$info = $registerFile->transformJsonToString($jsonFile);
		
		$doQuery = new IdentifyInterface();
		$resultQuery = $doQuery->InterfaceID($info["interfaceID"], $info);
		
		//With the data colected, will input it on DB
		//DB::insert('insert into users (username, email, password, bday, name) values (?, ?, ?, ?, ?)', [$info["username"], $info["email"], $info["password"], $info["bday"], "Gus"]);
		//return view('showRead', ['info' => $info]);
	}
}
?>