<?php
	if(isset($_List)){
?>
<section id="top-list">
	<section id="shadow-top-list"></section>
	<section class="container-background-list">
	<?php
		$size_cover_background = "normal";
		for ($i=0; $i < count($_List->arrayGames); $i++) { 
			$Game = $_List->arrayGames[$i];
	?>
		<div class="container-cover-game"><img src="<?php echo $Game->init_or_get('arrayUrlCovers', Array($size_cover_background))[$size_cover_background][0];?>" class="cover-game"/></div>
	<?php
			if($i == 5){break;}
		}
	?>
	</section>
	<section class="container-informations-list">
		<section class="author-and-stuff">
			<img src="<?php echo $_List->Author->arrayUrlPictures[100];?>" class="author-pic">
			<span class="title-list"><?php echo $_List->name;?></span>
			<span class="games-list"><?php echo $_List->nbGames;?> jeux</span>
			<span class="list-by">Par <a href="<?php echo $_List->Author->lien;?>"><?php echo $_List->Author->displayName;?></a></span>
			<span class="description-list"><?php echo $_List->description;?></span>
		</section>
		<section class="statistiques">
			<span class="time">Créée le <?php echo DB::datetime_to_letter($_List->dateCreation);?> (Dernières modifications : <?php echo DB::datetime_to_letter($_List->dateLastModif);?>)</span>
			<span class="right-infos-list">
				<?php
					$percent_barLike_list = 50;
					if($_List->arrayAppreciations['total'] > 0){
						$percent_barLike_list = $_List->arrayAppreciations['positives']/$_List->arrayAppreciations['total']*100;
					}
				?>
				<span class="like-text-list">Vue <?php echo $_List->views;?> fois - <?php echo $_List->arrayAppreciations['positives'];?> personnes apprècient cette liste</span>
				<span class="like-bar-list"><span class="like" style="width: <?php echo $percent_barLike_list;?>%"></span></span>
			</span>
		</section>
	</section>
</section>
<?php
	}
?>