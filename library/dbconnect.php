<?php

	 $DBhost = "localhost";
	 $DBuser = "root";
	 $DBpass = "pasS";
	 $DBname = "mysqli_login";

	 $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);

     if ($DBcon->connect_errno) {
         die("ERROR : -> ".$DBcon->connect_error);
     }
