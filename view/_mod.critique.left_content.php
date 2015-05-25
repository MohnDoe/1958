<?php
	if(isset($_Critique) OR $_PAGE_CREATE_CRITIQUE){
?>
<section id="container-left">
	<section id="container-real-left">
		<?php 
			include "_mod.critique.container-content.php";
			if(!$_PAGE_CREATE_CRITIQUE){
				$_ThingToGetComments = $_ThingToGetAppreciations = $_Critique;
				include "_mod.all.appreciations-bar.php";
				include "_mod.all.commentaires.php";
			}
		?>
	</section>
</section>
<?php
	}
?>