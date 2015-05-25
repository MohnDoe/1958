<?php

$_PAGE_NEED_CONNECTION = true;
$_PAGE_CREATE_CRITIQUE = false;
$_PAGE_TO_SHOW = "page.gameswall.php";
// $_PAGE_TO_SHOW = "page.news.php";
$_TYPE_GAMESWALL = "pop";

$_TITLE_PAGE_META = "De vrais critiques de vrais joueurs";

if(isset($_GET['superpage']) AND !empty($_GET['superpage'])){
	if($_GET['superpage'] == "news"){
		$_PAGE_NEED_CONNECTION = false;
		$_PAGE_TO_SHOW = "page.news.php";

		$_TITLE_PAGE_META = "Actualité";

	}else if($_GET['superpage'] == "settings"){
		$_PAGE_NEED_CONNECTION = true;
		$_PAGE_TO_SHOW = "page.settings.php";

		$_TITLE_PAGE_META = "Vos paramètres";

	}
}else if(isset($_GET['action']) AND !empty($_GET['action'])){
	if($_GET['action'] == "createcrt"){
		if(isset($_GET['idGameCrt']) AND is_numeric($_GET['idGameCrt'])){
			$_Game = new Game($_GET['idGameCrt']);
			if($_Game->game_exists()){
				// if(isset($_GET['slugGame']) OR empty($_GET['slugGame'])){
				// 	if($_GET['slugGame'] != $_Game->slug){
				// 		// header('Location: ./'.$_Game->lien);
				// 	}
				// }
				$_PAGE_NEED_CONNECTION = true;
				$_PAGE_CREATE_CRITIQUE = true;
				$_PAGE_TO_SHOW = "page.critique.php";

				$_TITLE_PAGE_META = "Rédiger une critique de ".$_Game->name;
			}
		}
	}else if($_GET['action'] == "createmccrt"){
		if(isset($_GET['idGameMccrt']) AND is_numeric($_GET['idGameMccrt'])){
			$_Game = new Game($_GET['idGameMccrt']);
			if($_Game->game_exists()){
				// if(isset($_GET['slugGame']) OR empty($_GET['slugGame'])){
				// 	if($_GET['slugGame'] != $_Game->slug){
				// 		// header('Location: ./'.$_Game->lien);
				// 	}
				// }
				$_PAGE_NEED_CONNECTION = true;
				$_PAGE_CREATE_MICROCRITIQUE = true;
				$_PAGE_TO_SHOW = "page.micro-critique.php";

				$_TITLE_PAGE_META = "Rédiger une micro-critique de ".$_Game->name;
			}
		}	
	}
}else if(isset($_GET['typegameswall']) AND !empty($_GET['typegameswall'])){
	if(in_array($_GET['typegameswall'], $__ALLOWED_TYPE_GAMESWALL)){
		$_Gameswall = new Gameswall($_GET['typegameswall']);
	}
}else if(isset($_GET['idGame']) AND is_numeric($_GET['idGame'])){
	/* PAGE GAME */
	$_Game = new Game($_GET['idGame']);
	
	if($_Game->game_exists()){
		if(isset($_GET['slugGame']) OR empty($_GET['slugGame'])){
			if($_GET['slugGame'] != $_Game->slug){
				// header('Location: ./'.$_Game->lien);
			}
		}
		$_PAGE_NEED_CONNECTION = false;
		$_SOUS_PAGE_TO_SHOW = "all";
		if(!isset($_GET['actionGame'])){
			$_EDIT_GAME = false;
		}else{
			$_EDIT_GAME = true;
		}
		$_PAGE_TO_SHOW = "page.game.php";
		$_TITLE_PAGE_META = $_Game->name;
		if(isset($_GET['viewGame']) AND !empty($_GET['viewGame'])){
			if(in_array($_GET['viewGame'], $__ALLOWED_VIEWS_GAME)){
				$_SOUS_PAGE_TO_SHOW = $_GET['viewGame'];
			}
		}


	}
}else if(isset($_GET['idCrt']) AND is_numeric($_GET['idCrt'])){
	/* PAGE CRITIQUE */
	$_Critique = new Critique($_GET['idCrt']);

	if($_Critique->critique_exists()){
		$_PAGE_NEED_CONNECTION = false;
		$_PAGE_TO_SHOW = "page.critique.php";
		$_TITLE_PAGE_META = "Critique de ".$_Critique->Game->name." par ".$_Critique->Author->displayName." - ".$_Critique->title;
	}


}else if(isset($_GET['idList']) AND is_numeric($_GET['idList'])){
	/* PAGE CRITIQUE */
	$_List = new ListGames($_GET['idList']);

	if($_List->listgames_exists()){
		$_List->init_games();
		$_PAGE_NEED_CONNECTION = false;
		$_PAGE_TO_SHOW = "page.liste.php";
		$_TITLE_PAGE_META = "Vos paramètres";
	}
}else if(isset($_GET['idBadge']) AND is_numeric($_GET['idBadge'])){
	/* PAGE CRITIQUE */
	$_Badge = new Badge($_GET['idBadge']);
	
	if($_Badge->badge_exists()){
		$_PAGE_NEED_CONNECTION = false;
		$_PAGE_TO_SHOW = "page.badge.php";
		if(isset($_GET['idUserBadge']) AND is_numeric($_GET['idUserBadge'])){
			$_UserBadge = new User($_GET['idUserBadge']);
			if(!$_UserBadge->user_exists()){
				$_UserBadge = null;
			}
		}else{
			$_UserBadge = null;
		}
	}
}else if(isset($_GET['idMccrt']) AND is_numeric($_GET['idMccrt'])){
	/* PAGE MICRO CRITIQUE */
	$_MicroCritique = new MicroCritique($_GET['idMccrt']);
	
	if($_MicroCritique->micro_critique_exists()){
		$_PAGE_NEED_CONNECTION = false;
		$_PAGE_TO_SHOW = "page.micro-critique.php";
		$_TITLE_PAGE_META = "Micro-critique de ".$_MicroCritique->Game->name." par ".$_MicroCritique->Author->displayName;
	}

}else if(isset($_GET['username']) AND !empty($_GET['username'])){
	$IDUser = User::getID_by_username($_GET['username']);
	if($IDUser){
		$_User = new User($IDUser);
		if($_User->user_exists()){
			$_PAGE_NEED_CONNECTION = false;
			$_PAGE_TO_SHOW = "page.profil.php";
			$_TITLE_PAGE_META = "Profil de ".$_User->displayName;
		}
	}
}else{
	$_Gameswall = new Gameswall($_TYPE_GAMESWALL);
}

$_TITLE_PAGE_META .= " — 1958";

?>