<?php
#INSERT YOUR PHP HERE
// Initialize the session

session_start();
require_once "config.php";
if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
    exit;
}

$email_error = $username_error = $name_err = $location_err = $bio_err = $website_err = $photo_err = $change_sucss = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $data_words = array();
  $data_domains = array();
  $stmt = $link->prepare("UPDATE customer SET email=?, username=?, location=?, bio=?, website=?, name=? WHERE id=?");
  $stmt->bind_param("ssssssi", $param_email, $param_username, $param_location, $param_bio, $param_website, $param_name, $param_id);

  $param_email = trim($_POST["email"]);
  $param_username = trim($_POST["username"]);
  $param_location = trim($_POST["location"]);
  $param_bio = trim($_POST["bio"]);
  $param_website = trim($_POST["website"]);
  $param_name = trim($_POST["name"]);

  $stmt_blacklist_words = "SELECT words FROM blacklistwords";
  $stmt_blacklist_domains = "SELECT domains FROM blacklistdomains";

  if(empty($param_email)){
    $email_error = "Email can not be empty.";
  }elseif(!filter_var($param_email, FILTER_VALIDATE_EMAIL)){
    $email_error = "Use a valid email address.";
  }elseif(strlen($param_email) > 50){
    $email_error = "Email is too long.";
  }else{
    $result_domains = mysqli_query($link,$stmt_blacklist_domains);
    while($row_domains = $result_domains->fetch_assoc()) {
      $data_domains[] = $row_domains;
    }

    foreach($data_domains as $domain){
      $esc_domains = $domain["domains"];
      if(preg_match("/$esc_domains/",$param_email)){
        $email_error = "This is a blocked domain.";
      }
    }
  }

  if(empty($param_username)){
    $username_error = "Username can not be empty.";
  }elseif(preg_match("/[^a-zA-Z\.\_\-]/",$param_username)){
    $username_error = "Username must be alphanumeric (underscores,dots and hyphens are allowed).";
  }elseif(strlen($param_username) > 50){
    $username_error = "Username is too long.";
  }else{
    $result_words = mysqli_query($link,$stmt_blacklist_words);
    while($row_words = $result_words->fetch_assoc()) {
      $data_words[] = $row_words;
    }

    foreach($data_words as $word){
      $esc_words = $word["words"];
      if(preg_match("/$esc_words/",$param_username)){
        $username_error = "Username can't contain swear words.";
      }
    }
  }

  if(!preg_match("/^[a-zA-Z ]*$/",$param_name)){
    $name_err = "You cannot use symbols or numbers in name.";
  }elseif(substr_count($param_name, ' ') > 1){
      $name_err = "This is not a vaild name.";
    }elseif(strlen($param_name) > 50){
      $name_err = "Name is too long.";
    }else{
      foreach($data_words as $word){
        $esc_words = $word["words"];
        if(preg_match("/$esc_words/",$param_name)){
          $name_err = "Name can't contain swear words.";
        }
      }
    }

    if(preg_match("/[^a-zA-Z0-9 ,]/",$param_location)){
      $location_err = "Location can't contain symbols.";
    }elseif(strlen($param_location) > 50){
      $location_err = "Location is too long.";
    }else{
      foreach($data_words as $word){
        $esc_words = $word["words"];
        if(preg_match("/$esc_words/",$param_location)){
          $location_err = "Location can't contain swear words.";
        }
      }
    }

    if(strlen($param_bio) > 280){
      $bio_err = "Bio is too long.";
    }else{
    foreach($data_words as $word){
      $esc_words = $word["words"];
      if(preg_match("/$esc_words/",$param_bio)){
        $bio_err = "Bio can't contain swear words.";
      }
    }
  }


if(!empty($param_website)){
    $tld = explode('.', $param_website);
    if(substr_count($param_website, '.') < 1 || !end($tld) || !$tld[0]){
      $website_err = "This is not a valid website.";
    }
  }
  if(strlen($param_website) > 50 && empty($website_err)){
    $website_err = "Website is too long.";
  }else{
    foreach($data_words as $word){
      $esc_words = $word["words"];
      if(preg_match("/$esc_words/",$param_website)){
        $website_err = "Website can't contain swear words.";
      }
    }

    if(empty($website_err)){
      foreach($data_domains as $domain){
        $esc_domains = $domain["domains"];
        if(preg_match("/$esc_domains/",$param_website)){
          $website_err = "This is a blocked domain.";
        }
      }
    }
  }

  if(empty($email_error) && empty($username_error) && empty($name_err) && empty($location_err) && empty($website_err) && empty($bio_err)){
      $param_email = mysqli_real_escape_string($link, $param_email);
      $param_username = mysqli_real_escape_string($link, $param_username);
      $param_location = mysqli_real_escape_string($link, $param_location);
      $param_bio = mysqli_real_escape_string($link, $param_bio);
      $param_website = mysqli_real_escape_string($link, $param_website);
      $param_name = mysqli_real_escape_string($link, $param_name);
      $param_id = $_SESSION["id"];
      $stmt->execute();


      $stmt->close();

      $stmt = $link->prepare("SELECT profilephoto_id FROM customer WHERE id=?");
      $stmt->bind_param("i", $param_id);
      $param_id = $_SESSION["id"];
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($profilephoto_id);
      $stmt->fetch();


      if($_FILES['file']['name']){
      if($profilephoto_id){


        $fileTmpDestination = $_FILES['file']['tmp_name'];
        $size = getimagesize($fileTmpDestination);
        if($size[0] && $size[1]){
          $stmt->close();

          $fileName = $_FILES['file']['name'];
          $fileSize = $_FILES['file']['size'];
          $fileError = $_FILES['file']['error'];
          $fileType = $_FILES['file']['type'];

          $fileExt = explode('.', $fileName);
          $fileActualExt = strtolower(end($fileExt));

          $allowed = array('jpg', 'jpeg', 'png');

          if(in_array($fileActualExt, $allowed)){

            if($fileError === 0){

              if($fileSize < 10000000){

                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'src/img/profilephotos/'.$fileNameNew;
                move_uploaded_file($fileTmpDestination, $fileDestination);

                $stmt = $link->prepare("UPDATE customer SET profilephoto_id=? WHERE id=?");
                $stmt->bind_param("si", $param_fileNameNew, $param_id);
                $param_id = $_SESSION["id"];
                $param_fileNameNew = $fileNameNew;
                $stmt->execute();

                $deletFilePath = 'src/img/profilephotos/'.$profilephoto_id;
                unlink($deletFilePath);

              }else{
                $photo_err = "Image upload failed,Your Image is too big.";
              }
            }else{
                $photo_err = "Image upload failed.";
            }

          }else{
            $photo_err = "Image upload failed,This Image extension is not supported.";
          }
        }else{
            $photo_err = "Image upload failed.";
        }

      }else{

        $fileTmpDestination = $_FILES['file']['tmp_name'];
        $size = getimagesize($fileTmpDestination);
        if($size[0] && $size[1]){
          $stmt->close();

          $fileName = $_FILES['file']['name'];
          $fileSize = $_FILES['file']['size'];
          $fileError = $_FILES['file']['error'];
          $fileType = $_FILES['file']['type'];

          $fileExt = explode('.', $fileName);
          $fileActualExt = strtolower(end($fileExt));

          $allowed = array('jpg', 'jpeg', 'png');

          if(in_array($fileActualExt, $allowed)){

            if($fileError === 0){

              if($fileSize < 10000000){

                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'src/img/profilephotos/'.$fileNameNew;
                move_uploaded_file($fileTmpDestination, $fileDestination);

                $stmt = $link->prepare("UPDATE customer SET profilephoto_id=? WHERE id=?");
                $stmt->bind_param("si", $param_fileNameNew, $param_id);
                $param_id = $_SESSION["id"];
                $param_fileNameNew = $fileNameNew;
                $stmt->execute();

              }else{
                $photo_err = "Image upload failed,Your Image is too big.";
              }
            }else{
              $photo_err = "Image upload failed.";
            }

          }else{
            $photo_err = "Image upload failed,This Image extension is not supported.";

          }
        }else{
          $photo_err = "Image upload failed.";
        }
      }
}

  }

  if(empty($email_error) && empty($username_error) && empty($photo_err) && empty($name_err) && empty($location_err) && empty($website_err) && empty($bio_err)){
    $change_sucss = "Settings changed successfully.";
  }
}
  $stmt = $link->prepare("SELECT email, username, location, bio, website, name, profilephoto_id FROM customer WHERE id = ?");
  $stmt->bind_param("i", $param_id);
  $param_id = $_SESSION["id"];

  if($stmt->execute()){
    $stmt->store_result();
    $stmt->bind_result($email, $username, $location, $bio ,$website, $name, $profilephoto_id);
    if($stmt->fetch()){
      $placeholderemail = $email;
      $placeholderusername = $username;
      $placeholderlocation = $location;
      $placeholderbio = $bio;
      $placeholderwebsite = $website;
      $placeholdername = $name;

      if($profilephoto_id){
        $placeholderimage_path = 'src/img/profilephotos/'.$profilephoto_id;
      }else{
        $placeholderimage_path = 'src/img/profile_default.jpg';
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
									<img src="<?php echo htmlspecialchars($placeholderimage_path) ?>" id="profilepic"/>
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
				  <a href="settings.php" class="list-group-item list-group-item-action active">Basic Information</a>
				  <a href="resetpassword.php" class="list-group-item list-group-item-action">Change Password</a>
				  <a href="notification.php" class="list-group-item list-group-item-action">Notifications</a>
				  <a href="deleteaccount.php" class="list-group-item list-group-item-action">Delete Account</a>
				</div>
			  </div>

			  <!-- edit form column -->
			  <div class="col-md-9 personal-info" style="padding-left: 10%;">

				<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
          <span class="success-block"><?php echo $change_sucss; ?></span>
				  <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
					<label class="col-lg-3 control-label">Name:</label>
					<div class="col-lg-8">
					  <input name="name" class="form-control" type="text" placeholder="Jane Doe" value="<?php echo htmlspecialchars($placeholdername) ?>">
            <span class="help-block"><?php echo $name_err; ?></span>
					</div>
				  </div>
				  <div class="form-group <?php echo (!empty($email_error)) ? 'has-error' : ''; ?>">
					<label class="col-lg-3 control-label">Email:</label>
					<div class="col-lg-8">
					  <input name="email" class="form-control" type="text" placeholder="janesemail@gmail.com" value="<?php echo htmlspecialchars($placeholderemail) ?>">
            <span class="help-block"><?php echo $email_error; ?></span>
					</div>
				  </div>
				  <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
					<label class="col-lg-3 control-label">Username:</label>
					<div class="col-lg-8">
					  <input name="username" class="form-control" type="text" value="<?php echo htmlspecialchars($placeholderusername) ?>">
            <span class="help-block"><?php echo $username_error; ?></span>
					</div>
				  </div>
				  <div class="form-group <?php echo (!empty($location_err)) ? 'has-error' : ''; ?>">
					<label class="col-lg-3 control-label">Location:</label>
					<div class="col-lg-8">
					  <input name="location" class="form-control" type="text" placeholder="Louisiana, United States" value="<?php echo htmlspecialchars($placeholderlocation) ?>">
            <span class="help-block"><?php echo $location_err; ?></span>
					</div>
				  </div>
				  <div class="form-group <?php echo (!empty($bio_err)) ? 'has-error' : ''; ?>">
					<label class="col-lg-3 control-label">Bio:</label>
					<div class="col-lg-8">
					  <textarea name="bio" class="form-control" type="text"><?php echo htmlspecialchars($placeholderbio) ?></textarea>
            <span class="help-block"><?php echo $bio_err; ?></span>
					</div>
				  </div>
				  <div class="form-group <?php echo (!empty($website_err)) ? 'has-error' : ''; ?>">
					<label class="col-md-3 control-label">Website:</label>
					<div class="col-md-8">
					  <input name="website" class="form-control" type="text" placeholder="https://google.com" value="<?php echo htmlspecialchars($placeholderwebsite) ?>">
            <span class="help-block"><?php echo $website_err; ?></span>
					</div>
				  </div>
				  <br>
				  <div class="form-group" <?php echo (!empty($photo_err)) ? 'has-error' : ''; ?>>
						<img src="<?php echo htmlspecialchars($placeholderimage_path) ?>" class="avatar img-circle col-lg-3" alt="avatar" id="imageprofile"><br>
						<label class="col-md-3 control-label" style="max-width:100%;">Upload Different Photo</label>
						<div class="col-md-8">
							<input type="file" name="file" class="form-control" onchange="showImage.call(this)">
              <span class="help-block"><?php echo $photo_err; ?></span>
              <script>
              function showImage(){
                    if(this.files && this.files[0]){
                      var obj = new FileReader();
                      obj.onload = function(data){
                      var image = document.getElementById("imageprofile");
                      image.src  = data.target.result;
                      var ext = image.src.split(":");
                      ext = ext[1].split(";");
                      ext = ext[0].split("/");
                      ext = ext[1].toLowerCase();
                      if(ext != "jpeg" && ext != "png" && ext != "jpg"){
                        image.src = "src/img/profile_default.jpg";
                      }
                    }
                    obj.readAsDataURL(this.files[0]);
                    }
                  }
              </script>
						</div>

				  </div>
				  <div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-8">
					  <input type="submit" class="btn btn-primary" value="Save Changes">
					  <span></span>
					  <input type="button" class="btn btn-default" value="Cancel" onclick="window.location.href='index.php'">
					</div>
				  </div>
				</form>
			  </div>
		  </div>
		</div>
		<hr>

        <footer class="site-footer">
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

</body>
</html>
