<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Parsed Cloud</title>
<link rel="stylesheet" type="text/css" href="_css/reset.css"/>
<link rel="stylesheet" type="text/css" href="_css/main.css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/base.js"></script>
</head>
<body>
	<div id="wrapper">
	   <div id="header">
		  <div id="logo"><a href="index.php"><img src="_images/logo.jpg" alt="Logo tekst" /></a>
		  </div><!-- end of logo -->
		  <div id="navigation">
			  <div id="loginBtns">
                            <?php if (!$this->logedIn) { ?>
                                <a href="#" class="show_hideReg"><img src="_images/registerHere.png" alt="Register" /></a>
                                <a href="#" class="show_hide"><img src="_images/login.png" alt="Login" /></a>
                            <?php } else { ?>
                                <a href="?p=logout" style="float: right;"><img src="_images/logout.png" alt="Logout" /></a>
                            <?php } ?>
			  </div>
		  </div><!-- end of navigation -->
		  <div id="share">
                      <?php if(isset($this->error)) { ?>
                      <span class="error">
                          <?=$this->error?>
                      </span>
                      <?php } ?>
                      <?php if(isset($this->message)) { ?>
                      <span class="success">
                          <?=$this->message?>
                      </span>
                      <?php } ?>
			 <div id="btns">
				<a href="" target="_blank"><img src="_images/twitter.png" alt="Twitter" /></a>
				<a href="" target="_blank"><img src="_images/flickr.png" alt="Flickr" />
				<a href="" target="_blank"><img src="_images/facebook.png" alt="Facebook share" /></a>
				<a href="" target="_blank"><img src="_images/linkedin.png" alt="Linkedin share" /></a>
				<a href="" target="_blank"><img src="_images/rss.png" alt="Rss" /></a>
			</div>
		  </div><!-- end of share -->
	   </div><!-- end of header -->
	   
	   <!-- Login Form -->
	   
	   <div id="loginForm" class="slidingDiv">
	   
		  <form method="post" action="" >
		  <div id="inputs">
			<span id="sprytextfield1">
				<input type="text" name="username" placeholder="Username..." />
				<span class="textfieldRequiredMsg">!</span>
			</span>
			<span id="sprytextfield2">
		  <input type="password" id="password" name="password" placeholder="Password..." />
		  <input type="hidden" name="action" value="login" />
		  <span class="textfieldRequiredMsg">!</span></span>
		  </div><!--end of inputs -->
		  
		  <input type="submit" name="login"  value=""/>
		 </form>
	   </div>
	   
	   <!-- end of loginForm -->
	   
	   <!-- Registration Form -->
	   
	   <div id="RegForm" class="slidingDivReg">
		  <form method="post" action="index.php" >
		  <div id="inputs">
			  <input type="text" name="username" placeholder="Choose Username" />
			  <input type="password" name="password"  placeholder="Choose Password" />
			  <input type="text" name="email" placeholder="Enter Email" />
			  <input type="hidden" name="action" value="registration" />
		  </div>
		  
		  <input type="submit" name="register"  value=""/>
		 </form>
	   </div>
	   
	   <!-- end of RegForm -->
	   
	  <div id="mainBody">
			<div id="sideNav"> <a href="index.php"><img src="_images/twit_selected.png" alt="Twitter" /></a>
				<img src="_images/rss_normal.png" alt="Twitter" />
				<img src="_images/bookmarks_normal.png" alt="Twitter" />
				<img src="_images/places_normal.png" alt="Twitter" />
			</div><!--end of sideNav -->
			<div id="banners">
			   <div id="add1">
				 <img src="_images/banner1.jpg" width="468" height="60" />
			   </div>
			   <div id="add2">
				  <img src="_images/banner2.jpg" width="468" height="60" />
			   </div>
			</div>
			
			<!-- Feeds -->
			
			<div id="feeds">
			
				<div id="firstFeed">
                                    <Feeds>
                                    <?php if($firstFeed) {?>
                                        <h2>@<?=$firstFeed['name']?></h2>
                                        <?php foreach ($firstFeed['tweets'] as $tweet) { ?>
                                            <div class="twittDiv">
                                                <b><?= $tweet['created'] ?></b> <br>
                                                <?= $tweet['text'] ?> <br>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                    <form method="post" action="">
                                        <input type="text" name="name" placeholder="Tweeter Name">
                                        <input type="hidden" name="action" value="first_feed">
                                        <input type="submit" name="submit" value="Save"/>
                                    </form>
                                    <?php } ?>
                                    </Feeds>
				</div>
				<div id="secondFeed">
                                    <Feeds>
                                    <?php if($secondFeed) {?>
                                        <h2>@<?=$secondFeed['name']?></h2>
                                        <?php foreach ($secondFeed['tweets'] as $tweet) { ?>
                                            <div class="twittDiv">
                                                <b><?= $tweet['created'] ?></b> <br>
                                                <?= $tweet['text'] ?> <br>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                    <form method="post" action="">
                                        <input type="text" name="name" placeholder="Tweeter Name">
                                        <input type="hidden" name="action" value="second_feed">
                                        <input type="submit" name="submit" value="Save"/>
                                    </form>
                                    <?php } ?>
                                    </Feeds>
				</div>
				<div id="thirdFeed">
                                    <Feeds>
                                    <?php if($thirdFeed) {?>
                                        <h2>@<?=$thirdFeed['name']?></h2>
                                        <?php foreach ($thirdFeed['tweets'] as $tweet) { ?>
                                            <div class="twittDiv">
                                                <b><?= $tweet['created'] ?></b> <br>
                                                <?= $tweet['text'] ?> <br>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                    <form method="post" action="">
                                        <input type="text" name="name" placeholder="Tweeter Name">
                                        <input type="hidden" name="action" value="third_feed">
                                        <input type="submit" name="submit" value="Save"/>
                                    </form>
                                    <?php } ?>
                                    </Feeds>
				</div>
			</div>
			
			<!-- end of Feeds -->
			
	   </div><!-- end of mainBody -->
	   <div id="footer">
		  <div id="copyright">
			 <h4>All rights reserved</h4>
		  </div><!-- end of copyright -->
	   </div><!-- end of footer -->
	</div><!-- end of wrapper -->
</body>
</html>
