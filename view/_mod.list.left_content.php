<?php
	if(isset($_List)){
?>
<section class="left-content">
	<?php
		include "_mod.list.the_list.php";
		$_ThingToGetComments = $_List;
		include "_mod.all.commentaires.php";	
	?>
</section>
<?php		
	}
?>