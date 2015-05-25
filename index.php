<?php
	require_once "./model/core.php";
	include_once "./controler/check.userlogged.php";
	include_once "./controler/link.switch.php";
?>

<html>
<head>
	<title><?php echo $_TITLE_PAGE_META;?></title>
	<meta charset="utf-8">
	<base href="<?php echo PROJECT_FOLDER;?>/" >
	<meta name="viewport" content="width=device-width">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:800,600,400,300,300italic,600italic,700' rel='stylesheet' type='text/css'></head>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/css/grphsm.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/css/tipsy.css">
	<script type="text/javascript">
	//<![CDATA[
	var CURRENT_USER = <?php echo $_CURRENT_USER_JS;?>;
	//]]>
	</script>
</head>
<body>
<section id="loader">
	<section id="container-text-loader">
		<span class="text-loader">0011 0001 0011 1001 0011 0101 0011 1000 0100 1001 0101 0011 0100 0010 0100 0001 0100 0011 0100 1011</span>
	</section>
</section>
<section id="page">
	<?php
		include_once "./view/_topbar.php";
		include_once "./view/_navbar.php";
		include_once "./view/".$_PAGE_TO_SHOW;
	?>
</section>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/jquery-2.0.3.min.js"></script>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/jquery.bxslider.min.js"></script>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/masonery.min.js"></script>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/tispy.js"></script>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/mustache.js"></script>
<script src="<?php echo PREFIX_URL_RELATIF.STATIC_URL;?>/lib/js/physics.js"></script>
<section id="time-script">
	<?php
		$time_elapsed_us = round((microtime(true) - $__START), 3);
		echo $time_elapsed_us." s";
	?>
</section>
</body>
</html>