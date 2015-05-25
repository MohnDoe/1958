<?php
	if(isset($_Game)){
		if(isset($_EDIT_GAME) && $_EDIT_GAME){
			$_class_editable = "editable autosuggestion-on";
			$_content_editable = 'contenteditable="true"';
		}else{
			$_content_editable = '';
			$_class_editable = "";
		}
?>
<section id="container-informations-game">
	<?php
		if($_INFOS_GAME_DISPLAY_TITLE){
	?>
		<div class="big-title"><?php echo $_Game->name;?></div>
	<?php
		}
	?>
	<ul>
		<li class="info"><span class="label">Développeur</span><span class="the-info <?php echo $_class_editable;?>" <?php echo $_content_editable;?> data-type="developer" data-id="<?php echo $_Game->init_or_get('Developer')->id;?>" data-url-autosugestion="./search/developers"><?php echo $_Game->init_or_get('Developer')->name;?></span></li>
		<li class="info"><span class="label">Editeur</span><span class="the-info <?php echo $_class_editable;?>" <?php echo $_content_editable;?> data-type="editor" data-id="<?php echo $_Game->init_or_get('Editor')->id;?>" data-url-autosugestion="./search/editors"><?php echo $_Game->init_or_get('Editor')->name;?></span></li>
		<li class="info"><span class="label">Sortie</span><span class="the-info <?php echo $_class_editable;?>" <?php echo $_content_editable;?>><?php echo $_Game->releaseDate_letter;?></span></li>
	</ul>
</section>
<?php
	}
?>