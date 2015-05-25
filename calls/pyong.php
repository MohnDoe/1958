<?php
	if($_POST){
		require_once "../model/core.php";
		if(isset($_SESSION['UserLogged']['id'])){
			$UserLogged = new User($_SESSION['UserLogged']['id']);

			if(isset($_GET['idGame']) AND is_numeric($_GET['idGame'])){

				$Game = new Game($_GET['idGame']);
				if(isset($_SESSION['UserLogged']['authenticity_token']['pyong_game_'.$_GET['idGame']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token']['pyong_game_'.$_GET['idGame']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Game->game_exists()){
							Pyong::add_pyong($UserLogged->id, "game", $Game->id);
							Notification::notify_people('following_recommand', Array(
								'id_pyonger'         => $UserLogged->id,
								'what_is_pyonged'    => 'game',
								'id_what_is_pyonged' => $Game->id
								));
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token']['pyong_game_'.$_GET['idGame']]);
					
				}
				echo Pyong::get_nb_pyongs('game', $_GET['idGame']);
			}else if(isset($_GET['idCrt']) AND is_numeric($_GET['idCrt'])){

				$Crt = new Critique($_GET['idCrt']);
				if(isset($_SESSION['UserLogged']['authenticity_token']['pyong_review_'.$_GET['idCrt']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token']['pyong_review_'.$_GET['idCrt']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Crt->critique_exists()){
							Pyong::add_pyong($UserLogged->id, "review", $Crt->id);
							Notification::notify_people('following_recommand', Array(
								'id_pyonger'         => $UserLogged->id,
								'what_is_pyonged'    => 'crt',
								'id_what_is_pyonged' => $Crt->id
								));
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token']['pyong_review_'.$_GET['idCrt']]);
					
				}
				echo Pyong::get_nb_pyongs('review', $_GET['idCrt']);
			}else if(isset($_GET['idMccrt']) AND is_numeric($_GET['idMccrt'])){

				$Mccrt = new MicroCritique($_GET['idMccrt']);
				if(isset($_SESSION['UserLogged']['authenticity_token']['pyong_microreview_'.$_GET['idMccrt']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token']['pyong_microreview_'.$_GET['idMccrt']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Mccrt->micro_critique_exists()){
							Pyong::add_pyong($UserLogged->id, "microreview", $Mccrt->id);
							Notification::notify_people('following_recommand', Array(
								'id_pyonger'         => $UserLogged->id,
								'what_is_pyonged'    => 'mccrt',
								'id_what_is_pyonged' => $Mccrt->id
								));
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token']['pyong_microreview_'.$_GET['idMccrt']]);
					
				}
				echo Pyong::get_nb_pyongs('microreview', $_GET['idMccrt']);
			}
		}
	}
	return;
?>