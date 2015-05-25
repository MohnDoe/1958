<?php

	$_ACCEPTED_CLASS_SIDE_BUTTONS = Array('Game', 'Critique', 'MicroCritique', 'ListGames');	
	if(isset($_side_buttons_Thing)){
		if(in_array(get_class($_side_buttons_Thing), $_ACCEPTED_CLASS_SIDE_BUTTONS)){
			switch (get_class($_side_buttons_Thing)) {
				case 'Game':
					$tooltip_pyong = "Recommander ce jeu à vos abonnés !";
					$type_pyong = "game";
					$destination_url = "games";
					break;
				case 'Critique':
					$tooltip_pyong = "Recommander cette critique à vos abonnés !";
					$type_pyong = "review";
					$destination_url = "reviews";
					break;
				case 'MicroCritique':
					$tooltip_pyong = "Recommander cette micro-critique à vos abonnés !";
					$type_pyong = "microreview";
					$destination_url = "microreviews";
					break;
				
				default:
					# code...
					break;
			}
		}
?>
	<section class="side-buttons">
		<div class="pyong-action prevent-default love-rapgenius tipsy-right" title="<?php echo $tooltip_pyong;?>">
			<div class="how-many-pyong"><?php echo number_format($_side_buttons_Thing->init_or_get('nbPyongs'));?></div>
			<?php 
				$class_pyong_button = (isset($_UserLogged) AND (Pyong::can_user_pyong($_UserLogged->id, $type_pyong, $_side_buttons_Thing->id)))?"":"pressed";
				$text_pyong_button = (isset($_UserLogged) AND (Pyong::can_user_pyong($_UserLogged->id, $type_pyong, $_side_buttons_Thing->id)))?"SPAAACE!":"I'M IN SPACE!";
			?>
			<a href="./<?php echo $destination_url."/".$_side_buttons_Thing->id;?>/pyong" class="pyong-button <?php echo $class_pyong_button;?>" data-authenticity-token ="<?php echo $_UserLogged->generate_authenticity_token("pyong_".$type_pyong."_".$_side_buttons_Thing->id);?>"><?php echo $text_pyong_button;?></a>
			<div style="clear:both;"></div>
		</div>
		<span class="views line"><?php echo number_format($_side_buttons_Thing->views);?> vues</span>
		<div style="clear:both;"></div>
	</section>
<?php
	}
?>