<?php
session_start();
require_once 'library/class.user.php';

$reg_user = new USER();
$userok = 0;
$state = "";
if($reg_user->is_logged_in()!="")
{
	//$reg_user->redirect('index.php');
}
/*
if (isset($_SESSION['userSession'])!="") {
	header("Location: home.php");
}
*/
//require_once 'library/dbconnect.php';

function get_user_fields() {
	$user_field_arr = array('username', 'email', 'password', 'firstname', 'lastname', 'coverletter', 'state', 'city', 'zip', 'resume', 'phone', 'emailoptin', 'active', 'deleted' );
	return $user_field_arr;
}
	
function validateData(){
	$error = array();
	$validateArr = array();
	if($_POST['password'] != $_POST['confirmpassword'] ){
		$error['code'] = 'password';
		$error['text'] = 'Passwords should match.';
	}
	if(strlen(trim($_POST['password'])) < 6 ) {
		$error['code'] = 'password';
		$error['text'] = 'Password should be atleast 6 characters long.';	
	}
	
	return $error;
}	

function getError($err, $msg = ''){
	
	switch($err){
		case 'password':
			if($msg != '') {								
				$text = $msg;
			}else {
				$text = "Please check your password.";	
			}				
		break;
		
		case 2:
			$text = "<strong>Sorry !</strong>  email already exists , Please Try another one";
		break;
		
		case 3:
			if($msg != ''){
				$text = $msg;		
			}else{
				$text = "<strong>Sorry !</strong>  File is not good, Please Try another one";					
			}

		
		break;
			
	}
	$error =	"<div class='alert alert-error'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<p>$text</p>
		  		</div>";
		  
	return $error;	  
}

function uploadFile(){
	$target_dir = "uploads/resume/";
	$resumeType = $_FILES["resumefile"]["type"];
	$resumeSize = $_FILES["resumefile"]["size"];
	$allowedResumeTypes = array('pdf', 'doc', 'docx', 'application/x-pdf', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword');
	$filename = $_FILES["resumefile"]["name"];
	
	$resumeSizeLimit = 4000000;
	
	 $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['resumefile']['tmp_name']),
        array('pdf', 'doc', 'docx', 'application/x-pdf', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'),
        true
    )) {
    		return array('error' => true, 'text' => 'Invalid file format.');
        //throw new RuntimeException('Invalid file format.');
    }
     
	// if(in_array($resumeType, $allowedResumeTypes) && ($resumeSize < $resumeSizeLimit)){
	if( ($resumeSize < $resumeSizeLimit)){	
		
		$target_file = $target_dir . time() .'-'. basename($_FILES["resumefile"]["name"]) ;
		$resume_filename = time() .'-'. basename($_FILES["resumefile"]["name"]) ;
		$uploadOk = 1;
		
		
		
		if (!move_uploaded_file( $_FILES['resumefile']['tmp_name'],  './uploads/resume/'.$resume_filename )
    	) {
    		return array('error' => true, 'text' => 'Failed to move uploaded file.');
   	   // throw new RuntimeException('Failed to move uploaded file.');
   	}
    
		return array('name' => $target_file, 'error' => false);
	}else{
		return array('error' => true);	
	}
		
}

function getStateList($stateUser = NULL){
	$selected = $stateList = "";
	$selectorState = array('Alberta', 'British Columbia'); 
  
   foreach($selectorState as $state){
   	
		if($stateUser == $state) {			
			$selected = "selected";
		}else {
			$selected = "";
		}	
			$stateList .= '<option value="'.$state.'" '.$selected.' >'.$state.'</option>';			   
   }
	return $stateList;
} 


	
if(isset($_POST['signup-submit'])) {
	$msg = $resumeName = $emailoptin = "";
	
	$validateArr = validateData();
			
	// vars set
	$fname = strip_tags($_POST['firstname']);
	$lname = strip_tags($_POST['lastname']);
	$coverletter = strip_tags($_POST['coverletter']);
	$state = strip_tags($_POST['state']);
	$city = strip_tags($_POST['city']);
	$zip = strip_tags($_POST['zip']);
	
	$phone = strip_tags($_POST['phone']);
	$uname = $email = strip_tags($_POST['email']);
	$password = strip_tags($_POST['password']);
	$emailoptin = $emailoptin;
	$code = md5(uniqid(rand()));
	// vars set end		
		
	//$stateList = getStateList($state);	
		
	if(count($validateArr) > 0 ){		
		$msg = getError($validateArr['code'], $validateArr['text']);		
	} else {		
		$valuelist = '';
		$user_field_arr = get_user_fields();
	//print_r($user_field_arr);
		$active = $deleted = 0;
		$static_fields = array('active'=>$active, 'deleted' => $deleted);
	if(isset($_POST['emailoptin'])){
		$emailoptin = strip_tags($_POST['emailoptin']);	
	}else{
		$emailoptin = 0;	
	}

	
	
	$stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE email=:email");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($stmt->rowCount() > 0)
	{
		$msg .= getError(2);
	}	else {
		$resumeArr = uploadFile();
		$resumeError = $resumeArr['error'];
		if(!$resumeError){
			$resumeName = $resumeArr['name'];	
		}
		$resumefile = $resumeName;
		if($resumeError){ 
			$errortext = $resumeArr['text'];
	 		$msg = getError(3, $errortext);
		} elseif($reg_user->register($uname, $email, $password, $fname, $lname, $coverletter, $state, $city, $zip, $resumefile, $phone, $emailoptin, $active, $deleted, $code))
		{			
			$id = $reg_user->lasdID();		
			$key = base64_encode($id);
			$id = $key;
			
			$message = "Hello $uname,
						<br /><br />
						Welcome to GoPage Careers!<br/>
						To complete your registration  please , just click following link<br/>
						<br /><br />
						<a href='http://144.217.29.87/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
						<br /><br />
						Thanks,";
						
			$subject = "Confirm Registration";
						
			$reg_user->send_mail($email,$message,$subject);	
			$msg = "
					<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Success!</strong>  We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account. 
			  		</div>
					";
			$userok = 1;		
		}
		else
		{
			echo "sorry , Query could no execute...";
		}		
	}
	
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
  <body id="signup">
    <header class="row">
      <div class="head-wrap column">
        <div class=" top-bar">
          <div id="logo">
            <a href="index.php" class="logo ">Logo</a>
          </div>

        </div>

        <div class="large-12 columns text-center slogan">
          <h1>Tell Us About Yourself</h1>
        </div>
      </div>
    </header>
    <section id="outerMost">
      <section class="row" id="content">
        <div class="large-12 columns">
        <?php
        
	        if (isset($msg)) {
					echo $msg;
				}    
				if(!$userok){    
        ?>
          <form  method="post" enctype="multipart/form-data" name="signupform" class="signupform">
            <div class="row ">
              <div class="large-6 columns">
                <div class="row">
                  <div class="large-6 columns">
                    <label for="firstname">Your Name</label>
                    <input name="firstname" type="text" class="half" required maxlength="30" placeholder="First Name" value="<?php if(isset($fname)){ echo $fname; } ?>" />
                  </div>
                  <div class="large-6 columns">
                    <label for="lastname" class="text-hide">Last Name</label>
                    <input name="lastname" type="text"  class="half" required maxlength="30" placeholder="Last Name" value="<?php if(isset($lname)){ echo $lname; } ?>" />
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <label for="name">Cover Letter (Optional) <textarea placeholder="Cover Letter" name="coverletter"> </textarea> </label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-6 columns">
                    <label for="name">State/Province <select name="state" required >
                    	<option value="">Select State</option>
                    	<?php 
            				echo getStateList($state);      	
				                   	
                    	?>							                    
                    </select> </label>                    
                  </div>

                  <div class="large-6 columns city">
                    <label for="name">City <input name="city" type="text" placeholder="Enter City" maxlength="30" required value="<?php if(isset($city)){ echo $city; } ?>" /> </label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-6 columns">
                    <label for="zip">Zip/Postal Code <input name="zip" type="text" placeholder="Enter Postal Code" maxlength="10" required value="<?php if(isset($zip)){ echo $zip; } ?>" /> </label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-6 columns">
                    <label for="file">Upload Your Resume
                      <input type="file" id="fileinput" name="resumefile" />
                    </label>
                    <span class="info small-text">PDF or Word documents onyl Max file 4MB.</span>
                  </div>
                </div>
              </div>
              <div class="large-6 columns">
                <div class="row">
                  <div class="large-12 columns">
                    <label for="phone">Phone Number (Optional)</label>
                    <input name="phone" type="text" placeholder="" maxlength="30" value="<?php if(isset($phone)){ echo $phone; } ?>" />
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <label for="email">Email <input name="email" type="email" placeholder="Enter Email" maxlength="60" required value="<?php if(isset($email)){ echo $email; } ?>" />
                      <span class="small-text">We'll never share your email with anyone else.</span>
                     </label>
                   </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <label for="password">Password <input name="password" type="password" maxlength="30" required /> </label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <label for="confirmpassword">Confirm Password <input name="confirmpassword" maxlength="30" type="password" required /> </label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <label for="opt-in">Opt/in for emails from us</label>
                    <input type="checkbox" name="emailoptin" value="1" /> <span class="small-text">I give GoPage permission to contact me by email.</span>
                  </div>
                </div>

              </div>
            </div>
            <div class="row">
              <div class="large-12 columns text-center">
                <input type="submit" name="signup-submit" class="button yellow wide" value="Submit Your Application" />
              </div>
            </div>
          </form>
			<?php } ?>
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
