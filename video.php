<?php
session_start();
require_once 'library/class.user.php';
$user_video = new USER();
$msg= "";
if($user_video->is_logged_in()!="")
{	
	$stmt = $user_video->runQuery("SELECT * FROM tbl_users WHERE user_id=:uid");
	$stmt->execute(array(":uid"=>$_SESSION['userSession']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	//$user_login->redirect('home.php');
}

function uploadFile(){
	$target_dir = "uploads/video/";
	$videoType = $_FILES["videofile"]["type"];
	$videoSize = $_FILES["videofile"]["size"];
	$filename = $_FILES["videofile"]["name"];
		
	$allowedVideoTypes = array('video/mp4', 'video/x-flv', 'video/x-ms-wmv', 'video/avi', 'video/msvideo', 'video/x-msvideo', 'application/x-troff-msvideo', 'video/webm', 'video/quicktime', 'video/mpeg', 'video/3gpp' );
	
	$fileSizeLimit = 25000000;
	
	 $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['videofile']['tmp_name']),
        $allowedVideoTypes, true
    )) {
    		return array('error' => true, 'text' => 'Invalid file format.');
        //throw new RuntimeException('Invalid file format.');
    }
     
	// if(in_array($resumeType, $allowedResumeTypes) && ($resumeSize < $resumeSizeLimit)){
	if( ($videoSize < $fileSizeLimit)){	
		
		$target_file = $target_dir . time() .'-'. basename($_FILES["videofile"]["name"]) ;
		$video_filename = time() .'-'. basename($_FILES["videofile"]["name"]) ;
		$uploadOk = 1;
				
		if (!move_uploaded_file( $_FILES['videofile']['tmp_name'],  $target_dir.$video_filename )
    	) {
    		return array('error' => true, 'text' => 'Failed to move uploaded file.');
   	   // throw new RuntimeException('Failed to move uploaded file.');
   	}
    
		return array('name' => $target_file, 'error' => false);
	}else{
		return array('error' => true);	
	}
		
}

if(isset($_POST['video-upload'])) {
	$video_path = '';
	
	$video_link = strip_tags($_POST['videolink']);
	$uid = $row['user_id'];
	
	if(isset($_FILES) && $_FILES['videofile']['size'] > 0){
		$videoArr = uploadFile();
		$videoError = $videoArr['error'];	
		if(!$videoError){
			$video_path = $videoArr['name'];
			$user_video->videoadd($uid, $video_path, $video_link);		
		}else {
			$msg = $videoArr['text'];		
		}		
	}elseif(($_FILES['videofile']['size'] == 0) && (strlen($video_link) == 0) ) {
		$msg = " Please upload file or enter video path";
	}else{
			$user_video->videoadd($uid, $video_path, $video_link);
	}	
	
}
	
	
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoPage Careers Video Form</title>
    <link rel="stylesheet" href="dist/css/foundation.css">
    <link rel="stylesheet" href="dist/css/app.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/font-awesome.min.css">
  </head>
  <body id="videopage">
    <header class="row">
	   <div id="hero">
    		<img src="dist/style_assets/video-submission-header-image.jpg" data-interchange="[dist/style_assets/video-submission-header-image-2x.jpg, (default)], [dist/style_assets/video-submission-header-image-2x.jpg, (retina)]" >
    	</div>
      <div class="head-wrap column">
        <div class=" top-bar">
          <div id="logo">
            <a href="/" class="logo ">Logo</a>
          </div>

        </div>

        <div class="large-12 columns text-center slogan">
          <h1>Submit the perfect elevator pitch video</h1>
          <p>Tell us why you think you are the ideal candidate for the ultimate summer job.</p>
        </div>
      </div>
    </header>
    <section id="outerMost">
      <div class="row" id="content">
        <div class="large-12 columns">
        
        	<?php if($user_video->is_logged_in()!="" ){
        		if((strlen($msg) > 0)) {        		
					echo "<div class='alert alert-success'> <p>";        	
        	 		echo $msg; 
        	 		echo "</p></div>";
        	 	}
          ?>
                     
          <form action="video.php" method="post" enctype="multipart/form-data">
            <div class="row ">
              <div class="large-12 columns">
                <h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h1>
                <p>Integer sit amet diam enim. Curabitur in sodales neque. Phasellus dictum ex sit amet sapien scelerisque viverra.
                  Curabitur mollis lectus ut justo sodales, in cursus ante faucibus. Suspendisse venenatis consequat diam, ornare aliquam turpis auctor quis. Duis condimentum porttitor erat vitae vehicula. Praesent id nunc suscipit, congue tellus ac, posuere neque. Proin eu pharetra felis, eget ullamcorper leo. Phasellus vulputate lobortis faucibus.
                  Aenean mattis orci ut lectus dignissim, quis convallis lacus ornare. Nullam ut eleifend risus.
                  Praesent quis mi arcu. Nam vel leo venenatis, placerat enim a, fringilla ipsum.
                  Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                  Fusce ornare molestie lacinia. Sed interdum nisi nibh.</p>
                  <ul>
                    <li>Nulla velit est, fringilla vitae lectus sed</li>
                    <li>Aliquet molestie tortor. Maecenas nibh ex</li>
                    <li>iaculis at sem porta, sollicitudin ornare diam.</li>
                    <li>Morbi eu massa volutpat, porta mauris aliquet, congue enim.</li>
                  </ul>
                <h2>Jon Smith, show us why you are the ideal candidate.</h2>
                <div class="row">
	                <div class="form-field large-6 medium-9 column">
                    Paste a link to your video below. We recommend using a video sharing website such as <a href="http://youtube.com" >YouTube</a> or <a href="http://vimeo.com" >Vimeo</a>
                    <input name="videolink" type="text" class="video-link" placeholder="Video Link"  />
   	             </div>                
                </div>
                <div class="form-field">
                    <label for="videofile">Or Upload a file
                      <input type="file" id="videofile" name="videofile" />
                    </label>
                    <p><span class="info small-text"><b>Accepted filetypes:</b> MOV, MPEG4, MP4, AVI, WMV, MPEGPS, FLV, 3GPP, WebM</span></p>
                    <p><span class="info small-text"><b>Max Filesize:</b> 25MB</span></p>
                </div>
              </div>
              <div class="large-12 columns ">
                <input type="submit" name="video-upload" class="button yellow wide" value="Submit Your Video" />
              </div>
            </div>
          </form>
				
          	<?php }else{  ?>      
          	    	
          	   <a href="login.php" class="style-trans-not button yellow" >Log in to view this page</a>
          	   
            <?php } ?> 
        </div>
      </div>
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
