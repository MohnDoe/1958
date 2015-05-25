<?php
	if(isset($_List)){
?>
<section id="content-list">
	<?php
		for ($i=0; $i < count($_List->arrayGames) ; $i++) { 
			$Game = $_List->arrayGames[$i];
	?>
	<section class="game-container">
		<section class="container-cover-game">
			<a href="<?php echo $Game->lien;?>">
				<img src="<?php echo $Game->init_or_get('arrayUrlCovers', Array('small'))['small'][0];?>" class="cover-game"/>
			</a>
		</section>
		<section class="informations-game">
			<a href="<?php echo $Game->lien;?>">
				<span class="info title-game"><?php echo $Game->name;?></span>
			</a>
			<span class="info">Développeur : <?php echo $Game->init_or_get('Developer')->name;?></span>
			<span class="info">Editeur : <?php echo $Game->init_or_get('Editor')->name;?></span>
			<span class="info">Sortie : <?php echo $Game->releaseDate_letter;?></span>
		</section>
		<section class="notes-games">
			<?php
				$note_global_game = $note_followings = "--";
				if(!is_null($Game->init_or_get('arrayAvgNotes')['moyenne'])){
					$note_global_game = round($Game->init_or_get('arrayAvgNotes')['moyenne']);
				}
			?>
			<div class="note note-global"><?php echo $note_global_game;?></div>
			<div class="note note-followings"></div>
		</section>
	</section>
	<?php
		}
	?>
</section>
<?php
	}
?>