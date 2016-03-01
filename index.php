<?php

	/*
		UserPie Version: 1.0
		http://userpie.com
		

	*/

require_once("models/config.php");

if(!isUserLoggedIn())
{ 
 include('login.php'); 
	
 } else { 

header("Location: usermain.php"); 	 

} ?>
