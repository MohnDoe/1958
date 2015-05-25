<?php
	if($_POST){
		require_once "../model/core.php";
		if(isset($_SESSION['UserLogged']['id'])){
			$UserLogged = new User($_SESSION['UserLogged']['id']);

			if(isset($_GET['idUser']) AND is_numeric($_GET['idUser'])){

				$UserToFollow = new User($_GET['idUser']);
				if(isset($_SESSION['UserLogged']['authenticity_token']['follows_user_'.$_GET['idUser']])){

					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token']['follows_user_'.$_GET['idUser']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $UserToFollow->user_exists()){
							if(isset($_POST['action']) AND !empty($_POST['action'])){
								if($_POST['action'] == 'set'){
									$UserLogged->follow_someone($UserToFollow->id);
									// Pyong::add_pyong($UserLogged->id, "game", $UserToFollow->id);
									Notification::notify_people('followed', Array(
										'id_follower' => $UserLogged->id,
										'id_followed' => $UserToFollow->id
									));
								}else if($_POST['action'] == 'del'){
									$UserLogged->unfollow_someone($UserToFollow->id);
									
								}
							}
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token']['follows_user_'.$_GET['idUser']]);			
				}
			}
		}
	}
	return;
?>