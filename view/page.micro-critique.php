<?php
	if(isset($_MicroCritique) OR $_PAGE_CREATE_MICROCRITIQUE){

		if(isset($_MicroCritique)){
			$_PAGE_CREATE_MICROCRITIQUE = false;
			$_type_page = "micro-critique";
			$_GameMicroCritique = $_MicroCritique->Game;
			$_AuthorMicroCritique = $_MicroCritique->Author;
		}else if($_PAGE_CREATE_MICROCRITIQUE){
			$_type_page = "createmccrt";
			$_GameMicroCritique = $_Game;
			$_AuthorMicroCritique = $_UserLogged;
		}
		//gestion popularite et vues jeu
		if($_type_page == "micro-critique"){
			if(!isset($_SESSION['view']['mccrt'][$_MicroCritique->id])){
				$_SESSION['view']['mccrt'][$_MicroCritique->id] = time()+(60*30);
				$_MicroCritique->add_popularite(1);
				$_MicroCritique->add_views(1);
				if(isset($_UserLogged)){
					$_UserLogged->add_transaction('view', Array(
						'what_is_viewed' => "mccrt",
						'id_what_is_viewed' => $_MicroCritique->id
					));
					// Badge_VIEWEDmccrt::event_default($_SESSION['UserLogged']['id']);
				}
			}elseif($_SESSION['view']['mccrt'][$_MicroCritique->id] <= time()){
				$_SESSION['view']['mccrt'][$_MicroCritique->id] = time()+(60*30);
				$_MicroCritique->add_popularite(1);
				$_MicroCritique->add_views(1);
				if(isset($_UserLogged)){
					$_UserLogged->add_transaction('view', Array(
						'what_is_viewed' => "mccrt",
						'id_what_is_viewed' => $_MicroCritique->id
					));
					// Badge_VIEWEDmccrt::event_default($_SESSION['UserLogged']['id']);
				}
			}
		}
?>
<section id="page-micro-critique">
	<section id="container-main-top">
		<?php
			if($_PAGE_CREATE_MICROCRITIQUE){
		?>
		<?php
			}
		?>
		<section class="container-mccrt-author center-ver">
			<?php
				if($_PAGE_CREATE_MICROCRITIQUE){
			?>
			<form name="create-micro-critique-form" data-max-length="140" data-length="0" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>/post" autocomplete="off" data-authenticity-token="<?php echo $_UserLogged->generate_authenticity_token("post_mccrt_".$_Game->id);?>">	
				<span class="the-mccrt editable postContent rangeMove empty" contenteditable="true" id="the-mccrt" name="the-mccrt">
					<span class="placeholder">Ecrivez votre micro-critique ...</span>
				</span>
				<input type="submit" class="submit-button-basic" id="send-create-micro-critique" value="J'ai lu et j'accepte ce que je viens d'écrire, envoyer !"/>
			</form>
			<?php
				}else{
			?>
			<span class="the-mccrt"><?php echo $_MicroCritique->content;?></span>
			<?php
				}
			?>
		</section>
		<section class="background-mccrt" style="background-image: url('<?php echo $_GameMicroCritique->init_or_get('arrayUrlCovers', Array('big'))['big'][0];?>');"></section>
	</section>
	<section id="container-rest-mccrt">
		<section id="container-interaction-mccrt">
			<section id="container-left">
				<section id="container-real-left">
					<?php
						if(!$_PAGE_CREATE_MICROCRITIQUE){
							$_side_buttons_Thing = $_MicroCritique;
							include_once "_mod.side-buttons.php";
						}
					?>
					<span class="by-who">Micro-critique du jeu <a href="<?php echo $_GameMicroCritique->lien;?>"><?php echo $_GameMicroCritique->name;?></a> proposée par <a href="<?php echo $_AuthorMicroCritique->lien;?>"><?php echo $_AuthorMicroCritique->displayName;?></a></span>
					<?php
						if(!$_PAGE_CREATE_MICROCRITIQUE){
							$_ThingToGetComments = $_ThingToGetAppreciations = $_MicroCritique;
							include "_mod.all.appreciations-bar.php";
							include "_mod.all.commentaires.php";
						}
					?>
				</section>
			</section>
			<section id="container-right">
				<section id="container-real-right">
					<section class="container-cover-game">
						<img class="cover" src="<?php echo $_GameMicroCritique->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>"/>
					</section>
					<?php
						$_Game = $_GameMicroCritique;
						$_INFOS_GAME_DISPLAY_TITLE = true;
						include "_mod.game.informations_undercover.php";
						if(!$_PAGE_CREATE_MICROCRITIQUE){
							$_ToShare = $_MicroCritique;
							include "_mod.all.share-buttons.php";
						}
						$_Author = $_AuthorMicroCritique;
						include "_mod.all.about-author.php";
					?>
				</section>
			</section>
		</section>
	</section>
</section>
<?php
	}
?>