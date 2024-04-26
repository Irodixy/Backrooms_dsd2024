<?php
	
	namespace App\Http\Controllers;
 
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\DB;
	use Illuminate\View\View;
	
	//require_once('choose.blade.php');
	   
	class Register {
		private $_username;
		private $_password;
		private $_email;
		public function index($username, $pass, $mail): View
		{
			$this->_username = $username;
			$this->_password = $pass;
			$this->_email = $mail;
        $users = DB::connection('laravel')->select('select * from user');
 
        //return view('user.index', ['users' => $users]);
		}
	}
	
	if ( !empty($_POST) ){ #Code execution only enters the if after a first form submission (with or without data in the form fields).	
		$costumer = new Register;
		echo $costumer->index($_POST['username'], $_POST['password'], $_POST['email']);
	}
?>
<!DOCTYPE html>
<html lang="pt">
<head>		
<meta charset="utf-8" />
<title>Workshop - Desenvolvimento Web em PHP (Laravel)</title>
<body>
<header>
<div class="container">
     <div class="row">
        <div class="col-8 col-s-8">
			<div id="logo">
				<img src="images/image2.png" alt="">
			</div>
		</div>
	
		<div class="col-4 col-s-4">
			<h3> Faça o seu registo!</h3>

<form action="" method="POST">
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username"><br>
  
  <label for="email">Email:</label><br>
  <input type="text" id="email" name="email"><br>

  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password"><br>
 
  <input type="submit" value="Submit">
</form> 
</div>
	</div>
</div>

</body>
</html>