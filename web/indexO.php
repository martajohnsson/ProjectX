<?php
require_once 'controller/MasterController.php';
$_mc = new MasterController();
$_html = $_mc->doControll();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Snippt</title>
        <!--<link rel="stylesheet" href="content/css/reset.css" />-->
        <!--<link rel="stylesheet" href="content/css/style.css" />-->

        <link href="content/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
          body {
            padding-top: 60px;
            padding-bottom: 40px;
          }
        </style>
        <link href="content/css/bootstrap-responsive.css" rel="stylesheet" >
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="content/js/janrain-engage.js"></script>
        <script src="content/js/tinymce/tiny_mce.js" ></script>
        <script src="content/js/tinymce/tinymce_init.js" ></script>
    </head>
    <body>
    	
        <div class="container">
        	<?php echo $_mc->doHeader();?>
        	
        	<div id="learn-more-wrap">
    		<div id="learn-more-content">
            	<h2>Snippets at hand</h2>
            	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        	</div>
        </div>
        	
            <div class="content">
                <?php echo $_html;?>
            </div>
            <p id="copy">ALL RIGHTS RESERVED</p>
        </div>
        
        <footer>
            
        </footer>
        
		<script src="content/js/lib/mootools-core.js"></script>
		<script src="content/js/lib/mootools-slide.js"></script>
		<script src="content/js/learn-more.js"></script>
        <script src="content/js/alert.js"></script>
        <script src="content/js/ajax.js"></script>
        
        <script src="content/js/bootstrap-transition.js"></script>
        <script src="content/js/bootstrap-alert.js"></script>
        <script src="content/js/bootstrap-modal.js"></script>
        <script src="content/js/bootstrap-dropdown.js"></script>
        <script src="content/js/bootstrap-scrollspy.js"></script>
        <script src="content/js/bootstrap-tab.js"></script>
        <script src="content/js/bootstrap-tooltip.js"></script>
        <script src="content/js/bootstrap-popover.js"></script>
        <script src="content/js/bootstrap-button.js"></script>
        <script src="content/js/bootstrap-collapse.js"></script>
        <script src="content/js/bootstrap-carousel.js"></script>
        <script src="content/js/bootstrap-typeahead.js"></script>
    </body>
</html>
