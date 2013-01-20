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
        <link href="content/css/bootstrap.css" rel="stylesheet">
        <link href="content/css/style.css" rel="stylesheet" >
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="content/js/janrain-engage.js"></script>
        <script src="content/js/tinymce/tiny_mce.js" ></script>
        <script src="content/js/tinymce/tinymce_init.js" ></script>
    </head>
    <body>
    	
        <?php echo $_mc->doHeader();?>

        <div class="container" id="main-container" style="background-color: white;">          
            <div class="row">
                <div class="span12">
                    <div class="row">
                        <div class="span8 offset2">
                            <div id="learn-more-wrap">
                                <div class="span8 offset4" id="learn-more-content">
                                	<h2>Snippets at hand</h2>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row" style="padding-top: 40px;">
                <div class="span12">
                    <?php echo $_html;?>
                </div>
            </div>
        </div>
        <div class="container">
            <footer>
                <p class="pull-right muted footer-copyright"><small>ALL RIGHTS RESERVED</small></p>
            </footer>
        </div>
        
		<script src="content/js/lib/mootools-core.js"></script>
		<script src="content/js/lib/mootools-slide.js"></script>
		<script src="content/js/learn-more.js"></script>
        <script src="content/js/alert.js"></script>
        <script src="content/js/ajax.js"></script>
        <script src="content/js/bootstrap-collapse.js"></script>
    </body>
</html>
