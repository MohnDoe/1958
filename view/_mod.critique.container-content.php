<?php
	if(isset($_Critique) OR $_PAGE_CREATE_CRITIQUE){
		$class_form_content_critique = "";
		$title_notes = "Les notes";
		if($_PAGE_CREATE_CRITIQUE){
				$title_notes = "Cliquez sur les cercles pour attribuer vos notes";
			$class_form_content_critique = "container-form-critique";
		}
?>
<section class="container-content-critique <?php echo $class_form_content_critique;?>">
	<?php
		if($_PAGE_CREATE_CRITIQUE){
	?>
		<form name="create-critique-form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>/post" autocomplete="off" data-authenticity-token="<?php echo $_UserLogged->generate_authenticity_token("post_crt_".$_Game->id);?>">	
	<?php
		}

		if(!$_PAGE_CREATE_CRITIQUE){
			$_side_buttons_Thing = $_Critique;
			include_once "_mod.side-buttons.php";
		}

		if($_PAGE_CREATE_CRITIQUE){
	?>
		<h1 class="title-critique editable postTitle rangeMove empty" contenteditable="true" id="title-critique" name="title" data-max-length="100" data-length="0">
			<span class="placeholder"><?php echo $_title_critique;?></span>
		</h1>
	<?php
		}else{
	?>
	<h1 class="title-critique"><?php echo $_title_critique;?></h1>
	<?php
		}
		if($_PAGE_CREATE_CRITIQUE){
	?>
		<p class="content-critique editable postContent rangeMove empty" contenteditable="true" id="content-critique" name="content">
			<span class="placeholder">Ecrivez votre critique ...</span>
		</p>
	<?php
		}else{
			echo $_Critique->content;
		}
	?>
	<section id="container-notes-in-critique">
	
		<span class="title-notes"><?php echo $title_notes;?></span>
		<?php
			$_circle_note_form = $_PAGE_CREATE_CRITIQUE;
			
			$_circleSize = 70;
			$_SHOW_MOYENNE = true;
			if(!$_circle_note_form){
				$_arrayNotes = $_Critique->arrayNotes;
			}
			include "_mod.game.notes_circles.php";
		?>
	</section>
	<?php
		if($_PAGE_CREATE_CRITIQUE){
	?>
		<div>
			<input type="submit" class="submit-button-basic" id="send-create-critique" value="J'ai lu et j'accepte ce que je viens d'écrire, envoyer !"/>
		</div>
	</form>
	<?php
		}
	?>
	<div style="clear:both;"></div>
</section>
<?php
	}
?>