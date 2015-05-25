<?php
	if((isset($_arrayNotes) OR $_circle_note_form) AND isset($_circleSize)){
?>

<section id="container-notes-circles">
	<?php
		if(!$_PAGE_CREATE_CRITIQUE){
			$arrayNotesToShow = Array(
				"gameplay" => round($_arrayNotes['gameplay']),
				"graphism" => round($_arrayNotes['graphism']),
				"bo"       => round($_arrayNotes['bo']),
				"lifetime" => round($_arrayNotes['lifetime']),
				"story"    => round($_arrayNotes['story']),
				"moyenne"  => round($_arrayNotes['moyenne'])
			);
	?>
	<div class="container-note tipsy-bottom" title="Gameplay">
		<div class="container-c-note c-note-gameplay">
			<span class="note" data-note="<?php echo $_arrayNotes['gameplay'];?>"><?php echo $arrayNotesToShow['gameplay'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<div class="container-note tipsy-bottom" title="Graphismes">
		<div class="container-c-note c-note-graphism">
			<span class="note" data-note="<?php echo $_arrayNotes['graphism'];?>"><?php echo $arrayNotesToShow['graphism'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<div class="container-note tipsy-bottom" title="Bande son">
		<div class="container-c-note c-note-bo">
			<span class="note" data-note="<?php echo $_arrayNotes['bo'];?>"><?php echo $arrayNotesToShow['bo'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<div class="container-note tipsy-bottom" title="Durée de vie">
		<div class="container-c-note c-note-lifetime">
			<span class="note" data-note="<?php echo $_arrayNotes['lifetime'];?>"><?php echo $arrayNotesToShow['lifetime'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<div class="container-note tipsy-bottom" title="Histoire">
		<div class="container-c-note c-note-story">
			<span class="note" data-note="<?php echo $_arrayNotes['story'];?>"><?php echo $arrayNotesToShow['story'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<?php
		if($_SHOW_MOYENNE){
	?>
	<div class="container-note container-note-moyenne tipsy-bottom" title="Moyenne">
		<div class="container-c-note c-note-moyenne">
			<span class="note" data-note="<?php echo $_arrayNotes['moyenne'];?>"><?php echo $arrayNotesToShow['moyenne'];?></span>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px" class="colorback"></canvas>
			<canvas height="<?php echo $_circleSize;?>px" width="<?php echo $_circleSize;?>px"></canvas>
		</div>
	</div>
	<?php
		}
	?>
	<?php
		}else{
	?>
	<section class="container-form-notes">
		<section class="container-form-note form-note-gameplay">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="gameplay"/>
			</section>
		</section>
		<section class="container-form-note form-note-graphism">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="graphism"/>
			</section>
		</section>
		<section class="container-form-note form-note-bo">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="bo"/>
			</section>
		</section>
		<section class="container-form-note form-note-story">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="story"/>
			</section>
		</section>
		<section class="container-form-note form-note-lifetime">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="lifetime"/>
			</section>
		</section>
		<section class="container-form-note form-note-average">
			<section class="input-note">
				<input data-size="<?php echo $_circleSize;?>" type="text" data-min="0" data-max="100" value="50" step="5" class="note" name="moyenne"/>
			</section>
		</section>
	</section>
	<?php
		}
	?>
</section>
<?php
	}
?>