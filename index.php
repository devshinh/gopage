<?php
session_start();
require_once 'library/class.user.php';
$user_login = new USER();

if($user_login->is_logged_in()!="")
{	
	$stmt = $user_login->runQuery("SELECT * FROM tbl_users WHERE user_id=:uid");
	$stmt->execute(array(":uid"=>$_SESSION['userSession']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	//$user_login->redirect('home.php');
}
if(isset($_POST['btn-login']))
{
	$email = trim($_POST['email']);
	$upass = trim($_POST['password']);
	
	if($user_login->login($email,$upass))
	{		
	//	$user_login->redirect('index.php');
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
  <body id="usj-landing">
    <header class="row">
      <div class="head-wrap column">
        <div class=" top-bar">
          <div id="logo">
            <a href="/" class="logo ">Logo</a>
          </div>
          <div class="login ">
          	<?php if($user_login->is_logged_in()!=""){ 
					echo "<a href='' class='username'>". $row['firstname'] ."</a>";         	
          	
          	?>
          		<a href="logout.php" class="hide style-trans-not logout-link" >Log Out</a>
          	<?php }else{ ?>          	
          	   <a href="login.php" class="style-trans button login-link" >Log In</a>
          	   
            <?php } ?>     
            
          </div>
        </div>

        <div class="large-12 columns text-center slogan">
          <h1>The Ultimate Summer Job!</h1>
          <p>Become a GoPage Team Member.</p>
          <a href="signup.php" class="button yellow narrow">Apply Now</a>
          <div>
          <?php 
		if(isset($_GET['inactive']))
		{
			?>
            <div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
				<strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it. 
			</div>
            <?php
		}
		?>
		 <?php
        if(isset($_GET['error']))
		{
			?>
            <div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				<strong>Wrong Details!</strong> 
			</div>
            <?php
		}
		?>
          </div>
        </div>
      </div>  
    </header>

    <section id="outerMost">

      <section class="row feature-video">
        <div class="large-12 columns">
          <div id="main-video" >
				<video width="100%" controls poster="dist/style_assets/video-image.jpg" onclick="this.play();" >
  					<source src="dist/video/USJ-Video.mp4" type="video/mp4">
  					
  						Your browser does not support HTML5 video.
				</video>          
           </div>
        </div>
      </section>

      <section class="row" id="content">
        <div class="large-12 columns">
          <div class="row">
            <div class="large-12 columns text-center">
              <a  href="signup.php" class="button yellow wide">Apply Now</a>
            </div>
          </div>
          <div class="row blocks">
            <div class="large-4 columns text-center col-1">
            	 <div class="text-center img-col">
            	 	<div class="img-wrap"><img src="dist/style_assets/flexible-job-icon.png" /></div>
                	<h4>Flexible Job</h4>
                	<p>Work according to your own schedule, from fulltime to part time. The more time you invest, the more you'll succeed. "Be your own Boss."</p>
                </div>
            </div>
            <div class="large-4 columns text-center col-2">
            	 <div class="text-center img-col">
            	 	<div class="img-wrap"><img src="dist/style_assets/great-pay-icon.png" /></div>
                	<h4>Great Pay</h4>
                	<p>Earn agressive sign-up bonuses for every business brought on board. Unlike most jobs. GoPage pays you based on results. Themore business you generate, the more you make. There are no caps on how much you can earn.</p>
                </div>  	
            </div>
            <div class="large-4 columns text-center col-3">
              	<div class="text-center img-col">
              		 <div class="img-wrap"><img src="dist/style_assets/recurring-revenues-icon.png" /></div>
	                <h4>Recurring Revenues</h4>
   	             <p>Earn an additional 20% ongoing monthly residual on each business you on-board for up to three years.</p>
                </div>
            </div>

          </div>
          <div class="row">
            <div class="large-12 columns text-center">
              <a href="signup.php" class="button yellow wide">Apply Now</a>
            </div>
          </div>


      </div>
    </section>
  </section>
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