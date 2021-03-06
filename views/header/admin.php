<!doctype html>
<html>
<head>
	<title><?php echo SYSTEM_TITLE; ?></title>
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/menu.css" />
	<link rel="stylesheet" href="<?php echo URL; ?>public/jquery_ui/jquery-ui.css" />
	<?php
		if (isset($this->css)) 
		{
			foreach ($this->css as $css)
			{
				echo '<link rel="stylesheet" href="'.URL.'views/'.$css.'" />';
			}
		}
	?>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/custom.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/jquery_ui/jquery-ui.js"></script>
	<?php
		if (isset($this->js)) 
		{
			foreach ($this->js as $js)
			{
				echo '<script type="text/javascript" src="'.URL.'views/'.$js.'"></script>';
			}
		}
	?>
</head>
<body>

<?php Session::init(); ?>
	
<div id="header">
	<div id="headerlogo">
	<img src="public/images/star2.png"><br/>
	</div>
	
	<img id="star"src="starlogo.jpg"><br/>
	<div class = 'divHeaderLogout'>
		<a href = "<?php echo URL; ?>h_logout"><img src="<?php echo URL; ?> name="Log Out" height="30" width="60"></a>
	</div>
</div>
	
<div id="content">
	
	