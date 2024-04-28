<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SelectController;

class IdentifyInterface extends Controller
{
	public function InterfaceID($ID, $array) 
	{
		if($ID==1 or $ID==3 or $ID==5 or $ID==6 or $ID==7 or $ID==11 or $ID==13 or $ID==14 or $ID==15 or $ID==19 or $ID==20 or $ID==28)
		{
			$ObjSelect = new SelectController();
			$newArray = $ObjSelect->QuerySelect($ID, $array);
			return ($newArray);
		}
		else if($ID==4 or $ID==8 or $ID==9 or $ID==12 or $ID==18 or $ID==20 or $ID==24)
		{
			$ObjInsert = new InsertController();
			$newArray = $ObjInsert->QueryInsert($ID, $array);
			return ($newArray);
		}
		else if($ID==10 or $ID==21)
		{
			$ObjUpdate = new ControllerQueryUpdate($ID, $array);
			return $ObjUpdate;
		}
		else if($ID==22)
		{
			
		}
	}
}
?>