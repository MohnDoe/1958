<?php
	if(isset($_Game)){

	//gestion popularite et vues jeu
	if(!isset($_SESSION['view']['game'][$_Game->id])){
		$_SESSION['view']['game'][$_Game->id] = time()+(60*30);
		$_Game->add_popularite(1);
		$_Game->add_views(1);
		if(isset($_UserLogged)){
			$_UserLogged->add_transaction('view', Array(
				'what_is_viewed' => "game",
				'id_what_is_viewed' => $_Game->id
			));
			// Badge_VIEWEDGAME::event_default($_SESSION['UserLogged']['id']);
		}
	}elseif($_SESSION['view']['game'][$_Game->id] <= time()){
		$_SESSION['view']['game'][$_Game->id] = time()+(60*30);
		$_Game->add_popularite(1);
		$_Game->add_views(1);
		if(isset($_UserLogged)){
			$_UserLogged->add_transaction('view', Array(
				'what_is_viewed' => "game",
				'id_what_is_viewed' => $_Game->id
			));
			// Badge_VIEWEDGAME::event_default($_SESSION['UserLogged']['id']);
		}
	}
?>
<section id="page-game">
	<section id="background-game-container">
		<section class="background-game" style="background-image: url('<?php echo $_Game->init_or_get('arrayUrlCovers', Array('big'))['big'][0];?>');"></section>
	</section>
	<section id="container-view-game">
		<section id="container-left">
			<section id="container-real-left">
				<section class="container-cover-game container-cover-game-to-margin">
					<?php
						$_side_buttons_Thing = $_Game;
						include_once "_mod.side-buttons.php";
					?>
					<img class="cover" src="<?php echo $_Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>"/>
				</section>
				<?php
					$_INFOS_GAME_DISPLAY_TITLE = false; 
					include "_mod.game.informations_undercover.php";
				?>
			</section>
		</section>
		<section id="container-right">
			<section id="container-head-game">
				<?php
					if($_EDIT_GAME){
						$_class_editable = "editable";
						$_content_editable = 'contenteditable="true"';
					}else{
						$_content_editable = '';
						$_class_editable = "";
					}
				?>
				<span class="title-game <?php echo $_class_editable;?>" <?php echo $_content_editable;?> name="title-game"><?php echo $_Game->name;?></span>
				<span class="little-infos"><?php echo $_Game->releaseDate_letter;?></span>
				<?php
					include_once "_mod.game.notes.php";
				?>
			<section id="container-bar-actions-game">
				<div class="actions-buttons">
					<a href="./micro-review/<?php echo $_Game->slug."-".$_Game->id."/create";?>">
						<div class="action act-write write-game-critique-button">Rédiger une micro-critique</div>
					</a>
					<a href="./review/<?php echo $_Game->slug."-".$_Game->id."/create";?>">
						<div class="action act-write write-game-critique-button">Rédiger une critique</div>
					</a>
					<?php
						$class_want_game_button = (isset($_UserLogged) AND $_UserLogged->is_wanting_game($_Game->id))?"pressed":"";
						$class_have_game_button = (isset($_UserLogged) AND $_UserLogged->is_having_game($_Game->id))?"pressed":"";
					?>
					<div data-authenticity-token ="<?php echo $_UserLogged->generate_authenticity_token("want_game_".$_Game->id);?>" class="action act-want want-game-button <?php echo $class_want_game_button;?>" data-url="./games/<?php echo $_Game->id;?>/want">Liste de souhaits</div>
					<div data-authenticity-token ="<?php echo $_UserLogged->generate_authenticity_token("have_game_".$_Game->id);?>" class="action act-add-collec have-game-button <?php echo $class_have_game_button;?>" data-url="./games/<?php echo $_Game->id;?>/have">Collection</div>
				</div>
				<div class="share-buttons">
					<div class="share share-twitter"></div>
					<div class="share share-facebook"></div>
				</div>
			</section>
			<section id="container-bar-nav-game">
				<?php
					$classNav_all = $classNav_critiques = $classNav_microscritiques = $classNav_lists = $classNav_screenshots = $classNav_videos = null;
					switch ($_SOUS_PAGE_TO_SHOW) {
						case 'all':
							$classNav_all = "actif";
							break;
						case 'critiques':
							$classNav_critiques = "actif";
							break;
						case 'micros-critiques':
							$classNav_microscritiques = "actif";
							break;
						case 'listes':
							$classNav_lists = "actif";
							break;
						case 'screenshots':
							$classNav_screenshots = "actif";
							break;
						case 'videos':
							$classNav_videos = "actif";
							break;
						
						default:
							# code...
							break;
					}
				?>
				<ul class="navs">
					<a href="<?php echo $_Game->lien;?>">
						<li class="n <?php echo $classNav_all;?>">Vue d'ensemble</li>
					</a>
					<a href="<?php echo $_Game->lien;?>/critiques">
						<li class="n <?php echo $classNav_critiques;?>">Critiques</li>
					</a>
					<a href="<?php echo $_Game->lien;?>/micros-critiques">
						<li class="n <?php echo $classNav_microscritiques;?>">Micros-Critiques</li>
					</a>
					<?php

					/*<a href="<?php echo $_Game->lien;?>/listes">
						<li class="n <?php echo $classNav_lists;?>">Listes</li>
					</a>
					<a href="<?php echo $_Game->lien;?>/screenshots">
						<li class="n <?php echo $classNav_screenshots;?>">Photos</li>
					</a>
					<a href="<?php echo $_Game->lien;?>/videos">
						<li class="n <?php echo $classNav_videos;?>">Vidéos</li>
					</a>*/
					?>
				</ul>
			</section>
			<section id="container-content-game">
				<?php include "_page.game.".$_SOUS_PAGE_TO_SHOW.".php";?>
			</section>
		</section>
	</section>
</section>
<?php
	}
?>