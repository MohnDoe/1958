<?php
	if($_POST AND isset($_POST['action']) AND isset($_GET['action_appr'])){
		require_once "../model/core.php";

		$del_or_set = $_POST['action'];
		$like_or_dislike = $_GET['action_appr'];

		if(isset($_SESSION['UserLogged']['id'])){
			$UserLogged = new User($_SESSION['UserLogged']['id']);

			if(isset($_GET['idGame']) AND is_numeric($_GET['idGame'])){

				$Game = new Game($_GET['idGame']);
				if(isset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_game_'.$_GET['idGame']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_game_'.$_GET['idGame']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Game->game_exists()){
							if($del_or_set == "del"){
								$UserLogged->del_appreciation($Game, $like_or_dislike);
							}else if($del_or_set == "set"){
								$UserLogged->set_appreciation($Game, $like_or_dislike);
								$UserLogged->arrayUrlIconRank();
							}
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_game_'.$_GET['idGame']]);
					
				}
				// echo Pyong::get_nb_pyongs('game', $_GET['idGame']);
			}else if(isset($_GET['idCrt']) AND is_numeric($_GET['idCrt'])){

				$Crt = new Critique($_GET['idCrt']);
				if(isset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_review_'.$_GET['idCrt']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_review_'.$_GET['idCrt']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Crt->critique_exists()){

							if($del_or_set == "del"){
								$UserLogged->del_appreciation($Crt, $like_or_dislike);
							}else if($del_or_set == "set"){
								$UserLogged->set_appreciation($Crt, $like_or_dislike);
							}

							// Pyong::add_pyong($UserLogged->id, "review", $Crt->id);
							// Notification::notify_people('following_recommand', Array(
							// 	'id_pyonger'         => $UserLogged->id,
							// 	'what_is_pyonged'    => 'crt',
							// 	'id_what_is_pyonged' => $Crt->id
							// 	));
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_review_'.$_GET['idCrt']]);
					
				}
				// echo Pyong::get_nb_pyongs('review', $_GET['idCrt']);

			}else if(isset($_GET['idMccrt']) AND is_numeric($_GET['idMccrt'])){

				$Mccrt = new MicroCritique($_GET['idMccrt']);
				if(isset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_microreview_'.$_GET['idMccrt']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_microreview_'.$_GET['idMccrt']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Mccrt->micro_critique_exists()){
							if($del_or_set == "del"){
								$UserLogged->del_appreciation($Mccrt, $like_or_dislike);
							}else if($del_or_set == "set"){
								$UserLogged->set_appreciation($Mccrt, $like_or_dislike);
							}
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token'][$like_or_dislike.'_microreview_'.$_GET['idMccrt']]);
					
				}
				// echo Pyong::get_nb_pyongs('microreview', $_GET['idMccrt']);
			}
		}
	}
	return;
?>