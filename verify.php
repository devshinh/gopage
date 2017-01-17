<?php
require_once 'library/class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code']))
{
	$user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = base64_decode($_GET['id']);
	$tokenCode = $_GET['code'];
	
	$statusY = 1;
	$statusN = 0;
	
	$stmt = $user->runQuery("SELECT user_id, active FROM tbl_users WHERE user_id=:uID AND tokenCode=:tokenCode LIMIT 1");
	$stmt->execute(array(":uID"=>$id,":tokenCode"=>$tokenCode));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0)
	{
		if($row['active']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE tbl_users SET active=:status WHERE user_id=:uID");
			$stmt->bindparam(":status",$statusY);
			$stmt->bindparam(":uID",$id);
			$stmt->execute();	
			
			$msg = "
		           <div class='alert alert-success'>
				   <button class='close' data-dismiss='alert'>&times;</button>
					  <strong>WoW !</strong>  Your Account is Now Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";	
		}
		else
		{
			$msg = "
		           <div class='alert alert-error'>
				   <button class='close' data-dismiss='alert'>&times;</button>
					  <strong>sorry !</strong>  Your Account is allready Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";
		}
	}
	else
	{
		$msg = "
		       <div class='alert alert-error'>
			   <button class='close' data-dismiss='alert'>&times;</button>
			   <strong>sorry !</strong>  No Account Found : <a href='signup.php'>Signup here</a>
			   </div>
			   ";
	}	
}

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoPage Careers</title>
    <link rel="stylesheet" href="dist/css/foundation.css">
    <link rel="stylesheet" href="dist/css/app.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/font-awesome.min.css">
  </head>
  <body id="login">
    <div class="container">
		<p><?php if(isset($msg)) { echo $msg; } ?> </p>
    </div> <!-- /container -->
    
    <?php
    
    $footer = file_get_contents('./assets/sections/footer.html', FILE_USE_INCLUDE_PATH);
    echo $footer; 
    ?>
    <script src="js/vendor/jquery/dist/jquery.js"></script>
    <script src="js/vendor/what-input/dist/what-input.js"></script>
    <script src="js/foundation.core.js"></script>
    <script src="js/custom/app.js"></script>
  </body>
</html>