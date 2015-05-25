<?php
	if(isset($_Critique) OR $_PAGE_CREATE_CRITIQUE){
		if(isset($_Critique)){
			$_type_page = "critique";
			//gestion popularite et vues jeu
			if(!isset($_SESSION['view']['crt'][$_Critique->id])){
				$_SESSION['view']['crt'][$_Critique->id] = time()+(60*30);
				$_Critique->add_popularite(1);
				$_Critique->add_views(1);
				if(isset($_UserLogged)){
					$_UserLogged->add_transaction('view', Array(
						'what_is_viewed' => "crt",
						'id_what_is_viewed' => $_Critique->id
					));
					// Badge_VIEWEDcrt::event_default($_SESSION['UserLogged']['id']);
				}
			}elseif($_SESSION['view']['crt'][$_Critique->id] <= time()){
				$_SESSION['view']['crt'][$_Critique->id] = time()+(60*30);
				$_Critique->add_popularite(1);
				$_Critique->add_views(1);
				if(isset($_UserLogged)){
					$_UserLogged->add_transaction('view', Array(
						'what_is_viewed' => "crt",
						'id_what_is_viewed' => $_Critique->id
					));
					// Badge_VIEWEDcrt::event_default($_SESSION['UserLogged']['id']);
				}
			}
		}else if($_PAGE_CREATE_CRITIQUE){
			$_type_page = "createcrt";
		}

		if($_type_page == "critique"){
			$_title_critique = $_Critique->title;
			$_GameCritique = $_Critique->Game;
			$_AuthorCritique = $_Critique->Author;
		}else if ($_type_page == "createcrt"){
			$_title_critique = "Titre de votre critique, un truc bien accrocheur ...";
			$_GameCritique = $_Game;
			$_AuthorCritique = $_UserLogged;
		}
?>
<section id="page-critique">
	<section id="head-background-critique">
		<section id="container-title-and-author">
			<div class="center-ver" style="display:block; height:auto; width:100%;">
				<span class="title-critique"><?php echo $_title_critique;?></span>
				<span class="author-critique">Critique de <a href="<?php echo $_GameCritique->lien;?>"><?php echo $_GameCritique->name;?></a> par <a href=""><?php echo $_AuthorCritique->displayName;?></a></span>
				<div style="clear:both;"></div>
			</div>
		</section>
		<section class="background-crt" style="background-image: url('<?php echo $_GameCritique->init_or_get('arrayUrlCovers', Array('big'))['big'][0];?>');"></section>
	</section>
	<section id="container-view-critique">
	<?php
		include "_mod.critique.left_content.php";
		include "_mod.critique.right_content.php";
	?>
	</section>
	<?php
		// if($_UserLogged){
		// 	include '_mod.critique.read_next.php';
		// }
	?>
</section>
<?php
	}
?>