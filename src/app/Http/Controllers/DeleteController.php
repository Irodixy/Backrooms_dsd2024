<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
	private $_dbDelete;
	private $_newArray;
	
	public function QueryDelete($ID, $array)
	{
		switch($ID)
		{
			//Interface 22 web::Management ==> database::Administrator
			//ADMIN DELETES USER DATA
			case 22:
				$this->_dbDelete = DB::delete('DELETE FROM users
												WHERE ID = ?',
												[$array["UserId"]]);
												
				if($this->_dbDelete == 1)
					{
						$SuccessToken = true;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
					}
					else
					{
						$SuccessToken = false;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
					}
			break;
			
			//Interface 25 web::Management ==> database::Administrator
			//ADMIN DELETE STORE DATA
			case 25:
				$this->_dbDelete = DB::delete('DELETE FROM users
												WHERE ID = ?',
												[$array["UserId"]]);
							
				if($this->_dbDelete == 1)
					{
						$SuccessToken = true;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
					}
					else
					{
						$SuccessToken = false;
						$this->_newArray = array("InterfaceId" => $array["InterfaceId"], "CurrentUser" => $array["CurrentUser"], "SuccessToken" => $SuccessToken);
					}
			break;
		}
		return $newArray = $this->_newArray;
	}
}
?>