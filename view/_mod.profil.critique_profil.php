<?php
	$_User->init_profil_Critique();
	if(!is_null($_User->profil_Critique) AND $_User->profil_Critique){
	$_Critique = $_User->profil_Critique;
	$_circleSize = 100;
	$_arrayNotes = $_Critique->arrayNotes;
?>
<section class="container-critique-profil">
	<div class="container-note">
		<div class="container-c-note c-note-moyenne">
			<span class="note" data-note="<?php echo $_arrayNotes['moyenne'];?>"><?php echo round($_arrayNotes['moyenne']);?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<div class="container-cover-critique">
		<img src="<?php echo $_Critique->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>" class="cover-critique">
	</div>
</section>
<?php
	}
?>