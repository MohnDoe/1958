<?php
	if(isset($_Game) AND false){
?>
<span class="big-title">Apparaît dans ces listes</span>
<section id="container-lists-present">
<?php
	$_Game->init_or_get('arrayIsInLists');
	// var_dump($_Game->arrayIsInLists);
	for ($i=0; $i < count($_Game->arrayIsInLists); $i++) { 
		$List = $_Game->arrayIsInLists[$i];
?>
	<a href="<?php echo $List->lien;?>">
		<div class="container-list">
			<div class="overlay-title overlay-list">
				<?php
					echo "<span class='title'>$List->name</span> par ".$List->Author->displayName;
				?>
			</div>
			<div class="overlay-color overlay-list"></div>
			<div class="overlay-shadow overlay-list"></div>
			<div class="overlay-couverture">
					<?php
						for ($j=0; $j < count($List->arrayGames); $j++) { 
							$Game = $List->arrayGames[$j];
							$urlCoverGame = $Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0];
					?>
				<div class="container-cover">
					<img src="<?php echo $urlCoverGame;?>" class="cover">
				</div>
					<?php
						}
					?>
			</div>
		</div>
	</a>
<?php
	}
?>
</section>
<?php
	}
?>