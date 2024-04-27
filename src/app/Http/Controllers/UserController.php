<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
 
class UserController extends Controller
{
    /**
     * Show a list of all of the application's users!
     */
    public function index(): View
    {
        $users = DB::select('select * from users where id=6');
		dd($users);
        return view('/showRead', ['users' => $users]);
    }
}
?>