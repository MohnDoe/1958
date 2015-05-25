<?php
	if($_POST){
		require_once "../model/core.php";
		if(isset($_SESSION['UserLogged']['id'])){
			$UserLogged = new User($_SESSION['UserLogged']['id']);
			if(isset($_GET['idGame']) AND is_numeric($_GET['idGame'])){
				$Game = new Game($_GET['idGame']);
				if(isset($_SESSION['UserLogged']['authenticity_token']['have_game_'.$_GET['idGame']])){
					$_session_authenticity_toke = $_SESSION['UserLogged']['authenticity_token']['have_game_'.$_GET['idGame']];
					if(isset($_POST['at']) AND $_session_authenticity_toke==$_POST['at']){
						if($UserLogged->user_exists() AND $Game->game_exists()){
							if(isset($_POST['action'])){
								if($_POST['action'] == "remove"){
									$UserLogged->del_have_game($Game->id);
								}else if($_POST['action'] == "add"){
									$UserLogged->set_have_game($Game->id);
								}
							}
						}
					}
					unset($_SESSION['UserLogged']['authenticity_token']['have_game_'.$_GET['idGame']]);
				}
			}
		}
	}
	return;
?>