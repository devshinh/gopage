<?php
session_start();
require_once 'library/class.user.php';
$user_login = new USER();
//print_r($user_login);
if($user_login->is_logged_in()!="")
{
	$stmt = $user_login->runQuery("SELECT * FROM tbl_users WHERE user_id=:uid");
	$stmt->execute(array(":uid"=>$_SESSION['userSession']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$user_login->redirect('index.php');
}



if(isset($_POST['btn-login']))
{
	$email = trim($_POST['email']);
	$upass = trim($_POST['password']);
	
	if($user_login->login($email,$upass))
	{
		$user_login->redirect('index.php');
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
  <body id="loginpage">
    <header class="row">
    	<div id="hero">
    		<img src="dist/style_assets/USJ-header-image.jpg" data-interchange="[dist/style_assets/USJ-header-image.jpg, (default)], [dist/style_assets/USJ-header-image-2x.jpg, (large)], [dist/style_assets/USJ-header-image-2x.jpg, (retina)]" >
    	</div>
      
      <div class="head-wrap column">
        <div class=" top-bar">
          <div id="logo">
            <a href="/" class="logo ">Logo</a>
          </div>

        </div>

        <div class="large-12 columns text-center slogan">
          <form action="login.php" method="post">

				<div class="row">
            	<div class="large-12 columns text-center">
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
            <div class="row">
              <div class="large-6 columns">
                <div class="form-field">
                  <label for="email">Email:
                    <input name="email" type="email" placeholder="Email ID" maxlength="60" />
                  </label>
                </div>
              </div>
              <div class="large-6 columns">
                <div class="form-field">
                  <label for="password">Password:
                    <input name="password" type="password" maxlength="60" />
                  </label>
                </div>
              </div>
              <div class="large-12 columns">
                <input class="button yellow narrow-1" value="Login" name="btn-login" type="submit" />
               
              </div>

              </div>
              
            </div>
            
          </form>
          
        </div>
      </div>
    </header>
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
    <script src="js/foundation.util.mediaQuery.js"></script>    
    <script src="js/foundation.interchange.js"></script>
    <script src="js/custom/app.js"></script>
    <script>
    	$(document).foundation();
	 </script>
  </body>
</html>
