<?php if(!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<!--[if lte IE 8]> <html class="oldie" lang="en"> <![endif]-->
<!--[if IE 9]> <html class="ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="format-detection" content="telephone=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,400i|PT+Serif:700" rel="stylesheet">
	<title>Kernelization</title>
	<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../dist/css/profiledropdown.css">
	<link rel="stylesheet" href="../dist/css/style.css">
	<link rel="stylesheet" href="css/all.css" />
	<link rel="stylesheet" href="css/animations.css" />
	<link rel="stylesheet" href="../dist/css/custom.css" />
	<link rel="stylesheet" href="css/screen.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/utils/Draggable.min.js"></script>

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<div id="wrapper">
		<div class="wrapper-holder">
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
								<a href="../bootcamps">Bootcamps</a>
							<?php } else { ?>
								<a href="../index.php#bootcamps">Bootcamps</a> <?php } ?>
                        </li>
                        <li>
                            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
							
							<div class="dropdown">
								<a href="#" class="account">
									<img src="../src/img/profile_default.jpg" id="profilepic"/>
								</a>
								<div class="submenu" style="display: none;">
									<ul class="root">
										<li>
											<a href="/dashboard" >Dashboard</a>
										</li>
										<li>
											<a href="../settings.php">Settings</a>
										</li>
										<li>
											<a href="../signout.php">Sign Out</a>
										</li>
									</ul>
								</div>
							</div>
							<script src="../src/js/profiledropdown.js"></script>
							<?php } else { ?>
							<a class="button button-sm button-shadow cd-signup" href="../signup.php" id="signupbtn">Signup</a> <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
		</div>
		<div class="wrapper-holder grey">
			<section id="main" style="padding-top:0;">
				<img src="../src/img/profile_default.jpg" style="margin-left: auto;margin-right:auto;border-radius:160px;padding-bottom:1%;">
				<h2 id="name">Toby Latino</h2>
				<h3 id="username">Pipe-to-Grep</h3>
			</section>
			<hr class="star"/>
			<section id="main">
				<h2 id="stats">Stats</h2>
				<img src="images/ufo.png" class="floating" style="margin-left: 93px;position: absolute;top: -5%;width:16%;" id="ufo">
				<div class="box_timeline_holder">
					<div class="box_timeline">
						<ul>
							<li id="joined" class="whitetext">
								<div class="date">
									<span>11/30/18</span>
								</div>
								<h3>Joined</h3>
								
							</li>
							<li id="badges">
								<div class="date">
									<span>08</span>
								</div>
								<h3>Badges Earned</h3>
								
							</li>
							<li id="projects">
								<div class="date">
									<span>07</span>
								</div>
								<h3>Projects Completed</h3>
							
							</li>
							<li id="points">
								<div class="date"> 
									<span>347</span>
								</div>
								<h3>Total Points</h3>
						
							</li>
							<li id="friends">
								<div class="date">
									<span>420</span>
								</div>
								<h3>Friends</h3>
					
							</li>
						</ul>
					</div>
				</div>
			</section>
			<section id="sec">
				<div class="skills_holder">
					<div class="column">
						<h2>Planets to Conquer:</h2>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
						<ul class="progress">
							<li><span class="photoshop">AI</span></li>
							<li><span class="illustrator">Blockchain</span></li>
							<li><span class="indesign">Hacking</span></li>
							<li><span class="flash">Trading</span></li>
						</ul>
					</div>
					
				</div>
				
			</section>
			
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
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="js/isotope.pkgd.min.js"></script>
	<script src="js/cells-by-row.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/ufo.js"></script>
</body>
</html>