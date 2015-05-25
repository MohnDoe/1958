<?php
	if(isset($_Critique) OR $_PAGE_CREATE_CRITIQUE){
?>
<section id="container-right">
	<section id="container-real-right">
		<section class="container-cover-game">
			<img class="cover" src="<?php echo $_GameCritique->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>"/>
		</section>
		<?php 
			$_Game = $_GameCritique;
			$_INFOS_GAME_DISPLAY_TITLE = true;
			include "_mod.game.informations_undercover.php";
			$_Author = $_AuthorCritique;
			include "_mod.all.about-author.php";
			if(!$_PAGE_CREATE_CRITIQUE){
				$_ToShare = $_Critique;
				include "_mod.all.share-buttons.php";
			}
		?>
	</section>
</section>
<?php
	}
?>