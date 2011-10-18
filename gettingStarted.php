
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
	<style>
		p{
			margin-left:25Px;
		}
	</style>
</head>
<body>
<?php if(array_key_exists('success', $_GET)){ 
	require(dirname(__FILE__) . '/inc/prepend.inc.php');?>
	<div style='margin:25Px; padding:10Px; background-color:green;'>
		<h1>Install has run successfully!!!</h1>
		<h1><a href='#step3'>Continue to Step 3</a></h1>	
	</div>
<?php }else{ ?>
<h1>Thank you for using the Schematical Framework</h1>
<?php } ?>
<p>
	Schematical is a Framework that helps you rapidly develop Facebook Applications. It is built to be easy enough for graphical designers to use but flexible enough to allow developers to build whatever they may want 	
</p>
<h3>About the Author</h3>
<p>
	Hello, I'm Matt Lea. My team and I have been building Facebook apps for about 18 months and we have learned quite a lot about Facebook, and Facebook app development. Now we want to share our tools with you. 
	This is currently version 1 and is in a beta release though several Facebook pages are using this Framework already, see <a href='http://www.mattleaconsulting.com' target='_blank'>mattleaconsulting.com</a> for details.
	<br/>
	<b>Lets get started</b>
</p>
<p>
	<i>Note: Please be patient. We are building a better admin and possibly even drag and drop functionality, but because people are asking about this today we are releasing it as is.</i>
<h3>Step 1: Edit /inc/global.config.php</h3>
<p>
	Here is where you put your global configurations. The Schematical Framework allows you to host multiple apps/page tabs on a single install. 
	
</p>
<code>
	/**<br/>
	 * This config file will hold global configurations such as the __DOCROOT__ and DB credentials<br/>
	 */<br/>
	define('__DOCROOT__', '/var/www/html/lab.mattleaconsulting/MFB_export');<br/>
	
	define('__BASE_URL__', ($_SERVER['HTTPS']?'https':'http') . '://lab.mattleaconsulting.com/MFB_export');<br/>
	//define('__DOCROOT__', 'xyz');<br/>
	define('DATABASE_1', serialize(array(<br/>
	&nbsp;&nbsp;&nbsp;'host'=>'your_db_host',<br/>
	&nbsp;&nbsp;&nbsp;'db_name'=>'your_db_name',<br/>
	&nbsp;&nbsp;&nbsp;'user'=>'your_db_username',<br/>
	&nbsp;&nbsp;&nbsp;'password'=>'your_db_password'<br/>
	)));<br/>
	//Facebook app ID for global use(Optional if you defined it in /apps/{your_app_name}/_config.inc.php)<br/>
	define('__GLOBAL_FB_APP_ID__','your_fb_app_id');<br/>
	//Facebook app secret key for global use(Optional if you defined it in /apps/{your_app_name}/_config.inc.php)<br/>
	define('__GLOBAL_FB_APP_SECRET__','your_fb_app_secret_key');<br/>
</code>
<h3>Step 2: Run the install script</h3>
<p>
	Now it is time to install all of the database tables that are needed to run an app.(<i>At the time I write this the only application type available is the contest. I challenge you developers out there to build and circulate your own app types, and I also challenge you designers to skin some themes. <a href='mailto:schematical@mattleaconsulting.com'>Let us know</a> if you want to contribute</i>).<br>
	Go to the following link to <a href='./admin/manualInstall.php?app=contest'>Manually Install the Contest</a>
</p>
<?php if(!array_key_exists('success', $_GET)){ ?>
<p>
	Once you have completed step 2 we will display the rest of the steps
</p>
<?php }else{ ?>

<h3 id='step3'>Step 3: Register your App With Facebook</h3>
<p>
	Start out by visiting <a href='https://developers.facebook.com/apps' target='_blank'>the Facebook Developer App</a> clicking '+ Create new App' and going through the steps of registering an app. 
	Once you have done that you can read through the guide on
	<a href='http://developers.facebook.com/docs/appsonfacebook/pagetabs/' target='_blank'>
		How to set up a Page Tab 
	</a>.
</p>
<p>
	What should you put in the fields?
</p>
	<div style='margin:25Px; padding:10Px; background-color:grey;'>
		<h4>Page Tab Name:</h4>
		What ever you want your page tab to be called
		<h4>Page Tab Url:</h4>
		If you correctly did step 1 then the url should be something like this
		<code>
			<?php echo __BASE_URL__; ?>/contest.php
		</code>
		<h4>Secure Page Tab Url:</h4>
		This is the same as above but use 'https' instead of 'http'. Make sure your server is SSL enabled.
		<h4>Page Tab Edit URL:</h4>
		We currently do not support this, but will hopefully add this functionality soon 
	</div>
<h3>Step 4: Edit your new applications _config.inc.php file</h3>
<p>
	Next your going to need to copy and paste the information from the app you registered in to <b>/apps/{your_app_name}/_config.inc.php</b><br/><br/>
	<code>
		//The Facebook App Id for this specific app<br/>
		define('__CUST_FB_APP_ID__', 'your_facebook_app_id');<br/>
		//The Facebook App Secret Key for this specific app<br/>
		define('__CUST_FB_APP_SECRET__', 'your_facebook_app_secret');	<br/>
	</code>
	

</p>
<h3>Step 5: Add your page tab to facebook</h3>
<p>
	Now you can go back and add your app to any page you control. Instructions can be found a bit lower in the guide on 
	<a href='http://developers.facebook.com/docs/appsonfacebook/pagetabs/' target='_blank'>
		How to set up a Page Tab 
	</a>.
</p>
<h3>Step 6: Edit it</h3>
<p>
	That is it now you can start to edit your app using HTML/CSS and for our more advanced user JavaScript/PHP/MySql.<br/>
	We are working on more comprehensive tutorials, but for now I suggest you start by looking in <b>/apps/{your_app_name}/assets/</b>.
	In this directory you can edit all of the following:<br>
	<ul style='list-style:none;'>
		<li>
			HTML in the 'templates' directory
		</li>
		<li>
			CSS in the 'css' directory
		</li>
		<li>
			Images in the 'images' directory
		</li>
		<li>
			JavaScript in the 'js' directory
		</li>
	</ul>
</p>
<hr />
<h3>Contribute</h3>
<p>
	We need help with all of the following:
	<ul style='list-style:none;'>
		<li>
			Creating documentation and tutorials
		</li>
		<li>
			Graphic Design and creating new themes
		</li>
		<li>
			Development building more functionality and different app types
		</li>
		<li>
			Spreading the word. Please feel free to share this with everyone.
		</li>
	</ul>
	<br/>
	
	If your interested in contributing please contact us at <a href='mailto:schematical@mattleaconsulting.com'>schematical@mattleaconsulting.com</a>

</p>
<h3>Special Thanks To:</h3>
<p>
	My Staff - Because with out them I would be on my own<br />
	Andre Gagnon - <a href='http://designcirc.us' target='_blank'>Design Circus</a> for all graphical design work because I can't design my way out of a card board box<br/>
	The team at <a href='http://enjoy5nines.com/' target='_blank'>5NINES</a> - For helping us with all of our server needs
</p>
<h3>Get Social</h3>
<p>
	Like us, follow us or just plain subscribe:<br>
	<ul style='list-style:none;'>
		<li>
			<a href='http://facebook.com/schematical'>Facebook</a>
		</li>
		<li>
			<a href='http://twitter.com/schematical'>Twitter</a>
		</li>
		<li>
			<a href='http://youtube.com/mattleaconsulting'>Youtube</a>
		</li>
</p>
<?php }?>

</body>
</html>