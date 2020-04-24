<?php
if(!isset($_SESSION)) session_start();
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = $successmessage = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

      $filter_email = trim($_POST["signup-email"]);
      $filter_username = trim($_POST["signup-username"]);
      $filter_password = trim($_POST["signup-password"]);
      $filter_confirm_password = trim($_POST["confirm_password"]);

      $stmt_blacklist_words = "SELECT words FROM blacklistwords";
      $stmt_blacklist_domains = "SELECT domains FROM blacklistdomains";


    // Validate username
    if(empty($filter_username)){
        $username_err = "Username can not be empty.";
    }elseif(preg_match("/[^a-zA-Z\.\_\-]/",$filter_username)){
        $username_err = "Username must be alphanumeric (underscores,dots and hyphens are allowed).";
    }elseif(strlen($filter_username) > 50){
        $username_err = "Username is too long.";
    }else{
      $result_words = mysqli_query($link,$stmt_blacklist_words);
      while($row_words = $result_words->fetch_assoc()) {
        $data_words[] = $row_words;
      }

      foreach($data_words as $word){
        $esc_words = $word["words"];
        if(preg_match("/$esc_words/",$filter_username)){
          $username_err = "Username can't contain swear words.";
        }
      }
      if(empty($username_err)){
            // Prepare a select statement
            $sql = "SELECT id FROM customer WHERE username = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = mysqli_real_escape_string($link, $filter_username);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "This username is already taken.";
                    } else{
                        $username = mysqli_real_escape_string($link, $filter_username);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);

      }
    }

	if(empty($filter_email)){
        $email_err = "Please enter email.";
	}elseif (!filter_var($filter_email, FILTER_VALIDATE_EMAIL)) {
		$email_err = "Use a valid email address";
    }elseif(strlen($filter_email) > 50){
      $email_err = "Email is too long.";
    }else{
      $result_domains = mysqli_query($link,$stmt_blacklist_domains);
      while($row_domains = $result_domains->fetch_assoc()) {
        $data_domains[] = $row_domains;
      }

      foreach($data_domains as $domain){
        $esc_domains = $domain["domains"];
        if(preg_match("/$esc_domains/",$filter_email)){
          $email_err = "This is a blocked domain.";
        }
      }
      if(empty($email_err)){
    		// Prepare a select statement
            $sql = "SELECT id FROM customer WHERE email = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                // Set parameters
                $param_email = mysqli_real_escape_string($link, $filter_email);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "This email is already taken.";
                    } else{
                        $email = mysqli_real_escape_string($link, $filter_email);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    		}

      }
    }
    // Validate password
    if(empty($filter_password)){
        $password_err = "Please enter a password.";
    }elseif(strlen($filter_password) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }else{
        $password = mysqli_real_escape_string($link, $filter_password);
    }

    // Validate confirm password
    if(empty($filter_confirm_password)){
        $confirm_password_err = "Please confirm password.";
    }else{
        $confirm_password = $filter_confirm_password;
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }



    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO customer (username, password, email) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);

            // Set parameters
            $param_username = mysqli_real_escape_string($link, $username);
			      $param_email = mysqli_real_escape_string($link, $email);
            $param_password = password_hash(mysqli_real_escape_string($link, $password), PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
				// session_start();
				// Store data in session variables
				$_SESSION["signedup"] = true;

				// Redirect user to welcome page
				header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
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
    <div class="body-wrap boxed-container">
        <header class="site-header">
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
							<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
								<a href="/bootcamps">Bootcamps</a>
							<?php } else { ?>
								<a href="index.php#bootcamps">Bootcamps</a> <?php } ?>
                        </li>
                        <li>
                            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
							<div class="dropdown">
								<a href="#" class="account">
									<img src="src/img/profile_default.jpg" id="profilepic"/>
								</a>
								<div class="submenu" style="display: none;">
									<ul class="root">
										<li>
											<a href="/dashboard" >Dashboard</a>
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
							<a class="button button-sm button-shadow cd-signup" href="index.php" id="signupbtn">Home</a> <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

		<div class="register">
                <div class="row">
                    <div class="col-md-3 register-left">
                        <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
                        <h3>Welcome</h3>
                        <p>You are moments away from the most immersive classroom of your life!</p>
						<form action="login.php">
                        <input type="submit" name="" value="Login"/><br/>
						</form>
                    </div>
                    <div class="col-md-9 register-right">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h3 class="register-heading">Sign Up</h3>
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="row register-form">
                                    <div class="col-md-6">
										<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                            <input type="email" class="form-control" name="signup-email" placeholder="Email *" value="<?php echo $email; ?>" />
											<span class="help-block"><?php echo $email_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                            <input type="text" class="form-control" name="signup-username" placeholder="Username *" value="<?php echo $username; ?>" />
											<span class="help-block"><?php echo $username_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                            <input type="password" class="form-control" name="signup-password" placeholder="Password *" value="<?php echo $password; ?>" />
											<span class="help-block"><?php echo $password_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password *" value="<?php echo $confirm_password; ?>" />
											<span class="help-block"><?php echo $confirm_password_err; ?></span>
										</div>
                                        <input type="submit" class="btnRegister"  value="Register"/>
                                    </div>
                                </div>
								</form>
                            </div>
						</div>
                    </div>
                </div>

            </div>

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
