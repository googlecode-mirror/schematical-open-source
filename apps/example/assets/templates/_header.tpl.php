<?php $this->RenderAdminBar(); ?>
<link href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic" rel="stylesheet" type="text/css">
</link>
<link href="http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold" rel="stylesheet" type="text/css">
</link>
<link href='http://fonts.googleapis.com/css?family=Homemade+Apple' rel='stylesheet' type='text/css'>
</link>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>

 
 <div class="nav">
<div class="container_12">
<div class="grid_5">
    <div class="logocontainer">
        <!--Container for Absolute Positioning -->
        <div class="logo">
            <!--Logo in h1 for SEO reasons -->
            <h1> <a href="/"> <img alt="Matt Lea Consulting" src="/assets/default/images/mlclogo.png"> </a> </h1>
        </div>
    </div>
</div>

<div class="ruler"></div>
</div>
<!-- <?php echo $this->TplName();  ?> -->
</div>
<div class="callout subpage">
    <div class="dotted">
        <div class="container_12">
            <div class="grid_12">
                <div class="center">
                	<h1>Showcase Your Facebook Apps</h1>
                	<div class="carrot">
                        <h2>^</h2>
                    </div>
                    <div class="awesome">
                        <h2>Awesome</h2>
                    </div>
                </div>
                <div class="rightalign">
                                    </div>
            </div>
        </div>
    </div>
    <div class="rulertop"></div>
    <!--TopRuler Absolute Positioned -->
    <div class="rulerbottom"></div>
    <!--BottomRuler Absolute Positioned -->
    <div class="clear"></div>
</div>
<div class="grid_7"> 
	<!--Navigation -->
	<div class="nav2 clearfix">
	   <ul id="button">
		<li><MFBLink tpl='index' href='#' <?php if ($this->TplMatch('index')) { echo 'class="active"'; } ?>>Home</MFBLink></li>
		<li><MFBLink tpl='entryForm' href='#' <?php if ($this->TplMatch('entryForm')) { echo 'class="active"'; } ?>>Enter Sweepstakes</MFBLink></li>
		<li><MFBLink tpl='displayEntries' href='#' <?php if ($this->TplMatch('displayEntries')) { echo 'class="active"'; } ?>>View Entries</MFBLink></li>
		<li><MFBLink tpl='privacyPolicy' href='#' <?php if ($this->TplMatch('privacyPolicy')) { echo 'class="active"'; } ?>>Official Rules</MFBLink></li>
	    
	</ul>
</div>