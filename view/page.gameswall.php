<?php
	// $_Gameswall = new Gameswall($_TYPE_GAMESWALL);
?>
<section id="page-gameswall">
	<ol id="games-in-wall">
<?php
	for ($i=0; $i < count($_Gameswall->arrayGames) ; $i++) { 
		$Game = $_Gameswall->arrayGames[$i];
		$class_container_game_featured = "";
		$colspan_container_game_featured = "";
		$cover_size = "small";
		if($i == 0){
			$class_container_game_featured = "featured-game";
			$colspan_container_game_featured = "data-ss-colspan='2'";
			$cover_size = "normal";
		}
?>
	<!--  -->
		<!-- <a href="<?php echo $Game->lien;?>" class="container-game <?php echo $class_container_game_featured;?>" <?php echo $colspan_container_game_featured;?>>
			<section class="bordurShadow"></section>
			<img src="<?php echo $Game->init_or_get('arrayUrlCovers', Array($cover_size))[$cover_size][0];?>" class="game-cover" height="<?php echo $Game->init_or_get('arrayUrlCovers', Array($cover_size))[$cover_size][2];?>" width="<?php echo $Game->init_or_get('arrayUrlCovers', Array($cover_size))[$cover_size][1];?>"/>
		</a> -->
		<a href="<?php echo $Game->lien;?>" class="container-game container-game-b <?php echo $class_container_game_featured;?>" style="background-image:url('<?php echo $Game->init_or_get('arrayUrlCovers', Array($cover_size))[$cover_size][0];?>');" <?php echo $colspan_container_game_featured;?>>
			<section class="bordurShadow"></section>
			<section class="overlay-container-game">
				<div class="container-informations-game">
					<span class="title-game"><?php echo $Game->name;?></span>
					<span class="note-game"><?php echo round($Game->init_or_get('arrayAvgNotes')['moyenne'],1);?></span>
					<span class="reviews-game"><?php echo $Game->init_or_get('nbCritiques');?> critiques</span>
				</div>
			</section>
		</a>
<?php
	}
?>
	</ol>
<?php
	include "_mod.gameswall.featured-stuff.php";
?>
</section>