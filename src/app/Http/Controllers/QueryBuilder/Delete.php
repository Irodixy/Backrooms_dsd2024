<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ControllerQueryDelete extends Controller
{
	public QueryDelete($ID, $array)
	{
		switch($ID)
		{
			//Interface 22 web::Management ==> database::Administrator
			//ADMIN DELETES USER DATA
			case 22:
				DB::delete ('DELETE FROM interests
							WHERE ID = ?',
							[$array["UserId"]]);
				DB::delete('DELETE FROM users
							WHERE ID = ?',
							[$array["UserId"]]);
			break;
			
			//Interface 25 web::Management ==> database::Administrator
			//ADMIN DELETE STORE DATA
			case 25:
				DB::delete('DELETE FROM users
							WHERE ID = ?',
							[$array["UserId"]]);
			break;
		}
	}
}
?>