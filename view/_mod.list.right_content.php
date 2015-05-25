<?php
	if(isset($_List)){
?>
<section class="right-content">
	<?php
		$_Author = $_List->Author;
		include "_mod.all.about-author.php";
		$_ToShare = $_List;
		include "_mod.all.share-buttons.php";
	?>
</section>
<?php
	}
?>