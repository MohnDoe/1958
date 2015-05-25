<section id="container-global-notes">
	<?php
		$moyenneToShow = round($_Game->init_or_get('arrayAvgNotes')['moyenne'],1);
		$percentLike = "--";
		if($_Game->init_or_get('arrayAppreciations')['total']>0){
			$percentLike = round($_Game->init_or_get('arrayAppreciations')['positives']/$_Game->init_or_get('arrayAppreciations')['total']*100);
		}
		$nbAvis = $_Game->init_or_get('nbCritiques')+$_Game->init_or_get('nbNotes');
	?>
	<span class="text-note"><?php echo "Noté <b>$moyenneToShow</b> par <b><a href='".$_Game->lien."/critiques'>$nbAvis</a></b> utilisateurs | <b>$percentLike</b>% aiment";?></span>
	<?php
		$_circleSize = 70;
		$_SHOW_MOYENNE = false;
		$_arrayNotes = $_Game->init_or_get('arrayAvgNotes');
		include "_mod.game.notes_circles.php"
	?>
</section>
<?php
	include "_mod.game.left_incontent.php";
	include "_mod.game.right_incontent.php";
?>