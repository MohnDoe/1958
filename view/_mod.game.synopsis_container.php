<?php
	if(isset($_Game)){

	$synopsisToShow = $_Game->synopsis;

	if(is_null($synopsisToShow)){
		$synopsisToShow = "Un jeu très cool, sans doute.";
	}
?>
<span class="very-big-title">Histoire</span>
<div class="synopsis-container"><?php echo $synopsisToShow;?></div>
<?php
	}
?>