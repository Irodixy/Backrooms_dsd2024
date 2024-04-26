<?php
	/* This page is to be included in all pages. It may contain a navigation menu. The user must be checked to be authenticated or not and proceed accordingly.
	 */

	//check if a session is already started to avoid warnings.
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
   }
   
   echo '
			<nav>
				<ul id="menuH">
					<li class="col-3 col-s-3"><a href="index.php" title="Go Home">Home</a></li>
					<li class="col-3 col-s-3"><a href="loginForm.php" title="Go Login">Login</a></li>
					<li class="col-3 col-s-3"><a href="register.php" title="Go Register">Register</a></li>
				</ul>
			</nav>
			';
?>