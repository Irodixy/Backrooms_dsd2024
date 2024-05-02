<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
 
class QueryBuilderController extends Controller
{
    /**
     * Show a list of all of the application's users!
     */
    public function StringBuilder()
    {
        $users = DB::select('select * from users where id=6');
		print_r($users);
        return view('/showRead', ['users' => $users]);
    }
	
	public function ArrayBuilder()
}
?>