<?php

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function get_user_fields() {
		$user_field_arr = array('username', 'email', 'password', 'firstname', 'lastname', 'coverletter', 'state', 'city', 'zip', 'resume', 'phone', 'emailoptin', 'active', 'deleted', 'tokenCode' );
		return $user_field_arr;
	}	
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	public function register($uname, $email, $password, $fname, $lname, $coverletter, $state, $city, $zip, $resumefile, $phone, $emailoptin, $active, $deleted, $tokenCode)
	{
		try
		{							
			$password = md5($password);
			$stmt = $this->conn->prepare("INSERT INTO tbl_users(username,email,password,firstname,lastname,coverletter,state,city,zip,resume,phone,emailoptin,active,deleted,tokenCode) 
			                                             VALUES(:username, :email, :password, :firstname, :lastname, :coverletter, :state, :city, :zip, :resume, :phone, :emailoptin, :active, :deleted, :tokenCode)");
			$stmt->bindparam(":username",$uname);
			$stmt->bindparam(":email",$email);
			$stmt->bindparam(":password",$password);
			$stmt->bindparam(":firstname",$fname);
			$stmt->bindparam(":lastname",$lname);
			$stmt->bindparam(":coverletter",$coverletter);
			$stmt->bindparam(":state",$state);
			$stmt->bindparam(":city",$city);
			$stmt->bindparam(":zip",$zip);
			$stmt->bindparam(":resume",$resumefile);
			$stmt->bindparam(":phone",$phone);
			$stmt->bindparam(":emailoptin",$emailoptin);
			$stmt->bindparam(":active",$active);
			$stmt->bindparam(":deleted",$deleted);
			$stmt->bindparam(":tokenCode",$tokenCode);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function videoadd($uid, $video_path, $video_link)
	{
		try
		{				
			$stmt = $this->conn->prepare("INSERT INTO user_videos(uid, video_path,video_link) 
			                                             VALUES(:uid, :video_path, :video_link)");
			$stmt->bindparam(":uid",$uid);
			$stmt->bindparam(":video_path",$video_path);
			$stmt->bindparam(":video_link",$video_link);                                            
			$stmt->execute();	
			return $stmt;                                   
		} 
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}	
		
	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE email=:email");
			$stmt->execute(array(":email"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['active']==1)
				{
					if($userRow['password']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['user_id'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.googlemail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="noreply@gopage.com";  
		$mail->Password="G0p@&e_ADM!N1";            
		$mail->SetFrom('noreply@gopage.com','GoPage Careers');
		//$mail->AddReplyTo("atulshin@gmail.com","GoPage");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}