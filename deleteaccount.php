<?php
#INSERT YOUR PHP HERE
// Initialize the session
session_start();
require_once "config.php";
if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
    exit;
}


////////////////////////////////////////////////////////////////////////////////


$stmt = $link->prepare("SELECT profilephoto_id FROM customer WHERE id = ?");
$stmt->bind_param("i", $param_id);
$param_id = $_SESSION["id"];

if($stmt->execute()){
  $stmt->store_result();
  $stmt->bind_result($profilephoto_id);
  if($stmt->fetch()){
    if($profilephoto_id){
      $placeholderimage_path = 'src/img/profilephotos/'.$profilephoto_id;
    }else{
      $placeholderimage_path = 'src/img/profile_default.jpg';
    }
  }
}




///////////////////////////////////////////////////////////////////////////////
$password = "";
$password_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){

if(empty(trim($_POST["conform-password"]))){
  $password_err = "Please confirm with your password.";
}else{
  $password = mysqli_real_escape_string($link, trim($_POST["conform-password"]));


  $stmt = $link->prepare("SELECT password, profilephoto_id FROM customer WHERE id = ?");
  $stmt->bind_param("i", $param_id);
  $param_id = $_SESSION["id"];
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($hashed_password, $profilephoto_id);
  $stmt->fetch();

  if(password_verify($password, $hashed_password)){

      $stmt->close();


      $stmt = $link->prepare("DELETE FROM customer WHERE id=?");
      $stmt->bind_param("i", $param_id);
      $param_id = $_SESSION["id"];

      $stmt->execute();
      if($profilephoto_id){
        $deletFilePath = 'src/img/profilephotos/'.$profilephoto_id;
        unlink($deletFilePath);
      }
      $_SESSION = array();
      session_destroy();
      header("location: index.php");
      exit;


  } else{
    
      $password_err = "The password you entered was not valid.";
  }
}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kernelization</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i|PT+Serif:700" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/profiledropdown.css">
	<link rel="stylesheet" href="dist/css/style.css">
	<link rel="stylesheet" href="dist/css/custom.css">
	<link rel="stylesheet" href="dist/css/cbox.css">
	<link rel="stylesheet" href="dist/css/signup2.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="src/js/custom.js"></script>

</head>
<body class="is-boxed has-animations">
    <div class="body-wrap boxed-container" style="background: #11171f;">
        <header class="site-header settings">
            <div class="container" id="nav">
                <div class="site-header-inner" id="siteheaderinner">
                    <div class="brand header-brand">
                        <h1 class="m-0">
                            <a href="#">
                                <!--<img src="src/img/kernel.png" width="20%">-->
                            </a>
                        </h1>
                    </div>
                    <ul class="header-links list-reset m-0">
                        <li>
                            <a href="#">About</a>
                        </li>
						<li>
                            <a href="#">Bootcamp</a>
                        </li>
						<li>
                            <a href="#">Projects</a>
                        </li>
                        <li>
                            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
							<div class="dropdown">
								<a href="#" class="account">
									<img src="<?php echo $placeholderimage_path ?>" id="profilepic"/>
								</a>
								<div class="submenu" style="display: none;">
									<ul class="root">
										<li>
											<a href="#">Dashboard</a>
										</li>
										<li>
											<a href="settings.php">Settings</a>
										</li>
										<li>
											<a href="signout.php">Sign Out</a>
										</li>
									</ul>
								</div>
							</div>
							<script src="src/js/profiledropdown.js"></script><?php } else { ?>
							<a class="button button-sm button-shadow cd-signup" href="signup.php" id="signupbtn">Home</a> <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

		<div class="profilewrap">
			<div class="row" style="margin-left: 5%;">
			  <!-- left column -->
			  <div class="col-md-3">
				<div class="list-group">
				  <a href="settings.php" class="list-group-item list-group-item-action">Basic Information</a>
				  <a href="resetpassword.php" class="list-group-item list-group-item-action">Change Password</a>
				  <a href="notification.php" class="list-group-item list-group-item-action">Notifications</a>
				  <a href="deleteaccount.php" class="list-group-item list-group-item-action active">Delete Account</a>
				</div>
			  </div>

			   <!-- edit form column -->
			  <div class="col-md-9 personal-info" style="padding-left: 10%;">

				<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
         <div class="col-lg-8">
           <input name="conform-password" class="form-control" type="password" name="password" placeholder="Password Confirmation">
           <span class="help-block"><?php echo $password_err; ?></span>
         </div>
         </div>
				  <div class="form-group">
					<label class="control-label" style="padding-left:15px;">You may delete your account at any time. However, this action is irreversible.</label>
					<div class="col-md-8">
					  <input type="submit" class="btn btn-danger" value="I understand, delete my account">
					</div>
				  </div>
				</form>
			  </div>
		  </div>
		</div>
		<hr>

        <footer class="site-footer deletefooter">
            <div class="container">
                <div class="site-footer-inner">
                    <div class="brand footer-brand">
                        <a href="#">

                        </a>
                    </div>
                    <ul class="footer-links list-reset">
                        <li>
                            <a href="#">Contact</a>
                        </li>
                        <li>
                            <a href="#">About us</a>
                        </li>
                        <li>
                            <a href="#">FAQ's</a>
                        </li>
                        <li>
                            <a href="#">Support</a>
                        </li>
                    </ul>
                    <ul class="footer-social-links list-reset">
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Facebook</span>
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.023 16L6 9H3V6h3V4c0-2.7 1.672-4 4.08-4 1.153 0 2.144.086 2.433.124v2.821h-1.67c-1.31 0-1.563.623-1.563 1.536V6H13l-1 3H9.28v7H6.023z" fill="#0EB3CE"/>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Twitter</span>
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 3c-.6.3-1.2.4-1.9.5.7-.4 1.2-1 1.4-1.8-.6.4-1.3.6-2.1.8-.6-.6-1.5-1-2.4-1-1.7 0-3.2 1.5-3.2 3.3 0 .3 0 .5.1.7-2.7-.1-5.2-1.4-6.8-3.4-.3.5-.4 1-.4 1.7 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.5-.4C.7 7.7 1.8 9 3.3 9.3c-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3.1 2.3-1.1.9-2.5 1.4-4.1 1.4H0c1.5.9 3.2 1.5 5 1.5 6 0 9.3-5 9.3-9.3v-.4C15 4.3 15.6 3.7 16 3z" fill="#0EB3CE"/>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Google</span>
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.9 7v2.4H12c-.2 1-1.2 3-4 3-2.4 0-4.3-2-4.3-4.4 0-2.4 2-4.4 4.3-4.4 1.4 0 2.3.6 2.8 1.1l1.9-1.8C11.5 1.7 9.9 1 8 1 4.1 1 1 4.1 1 8s3.1 7 7 7c4 0 6.7-2.8 6.7-6.8 0-.5 0-.8-.1-1.2H7.9z" fill="#0EB3CE"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                    <div class="footer-copyright">&copy; 2018 Kernelization, all rights reserved</div>
                </div>
            </div>
        </footer>
    </div>
    <script src="dist/js/main.min.js"></script>
	<script src="src/js/checkbox.js"></script>
</body>
</html>
