<?php
	if($_POST){
		require_once "../model/core.php";
		$json_r = Array();
		$json_r['s'] = false;
		if(isset($_POST['content'])){
			if(isset($_SESSION['UserLogged']['authenticity_token']["post_mccrt_".$_GET['idGameMccrt']]) AND isset($_SESSION['UserLogged']['id'])){
				$_session_at = $_SESSION['UserLogged']['authenticity_token']["post_mccrt_".$_GET['idGameMccrt']];
				if(isset($_POST['at'])){
					$_post_at = $_POST['at'];
					if($_session_at == $_post_at){
						$_post_content = strip_tags($_POST['content']);
						$UserLogged = new User($_SESSION['UserLogged']['id']);
						if($idCreatedMccrt = $UserLogged->post_microcritique($_GET['idGameMccrt'], $_post_content)){
							$CreatedMicroCritique = new MicroCritique($idCreatedMccrt);
							$json_r['u'] = $CreatedMicroCritique->lien;
							$json_r['s'] = true;
						}else{
							$json_r['e'] = "Une erreur serveur est survenue, my bad.";
						}
					}else{
						$json_r['e'] = "Veuillez rafraichir la page et recommencer.";
					}
					unset($_SESSION['UserLogged']['authenticity_token']["post_mccrt_".$_GET['idGameMccrt']]);
				}else{
					$json_r['e'] = "Veuillez rafraichir la page et recommencer.";
				}
			}else{
				$json_r['e'] = "Vous devez vous connecter avant de pouvoir poster !";
			}
		}else{
			$json_r['e'] = "Veuillez remplir tout les champs.";
		}
		echo json_encode($json_r);

	}
?>