<?php
	if($_POST){
		require_once "../model/core.php";
		$json_r = Array();
		$json_r['s'] = false;
		if(isset($_POST['title']) AND isset($_POST['content']) AND isset($_POST['notes'])){
			if(isset($_SESSION['UserLogged']['authenticity_token']["post_crt_".$_GET['idGameCrt']]) AND isset($_SESSION['UserLogged']['id'])){
				$_session_at = $_SESSION['UserLogged']['authenticity_token']["post_crt_".$_GET['idGameCrt']];
				if(isset($_POST['at'])){
					$_post_at = $_POST['at'];
					if($_session_at == $_post_at){
						$_post_notes = $_POST['notes'];
						$_post_content = str_replace("</div>", "</p>", (str_replace("<div>", "<p>", $_POST['content'])));
						$_post_content = strip_tags($_post_content, '<p><br>');
						$_post_title = strip_tags($_POST['title']);
						$_array_critique = Array(
							'notes' => $_post_notes,
							'content' => $_post_content,
							'title' => $_post_title
							);
						$UserLogged = new User($_SESSION['UserLogged']['id']);
						if($idCreatedCrt = $UserLogged->post_critique($_GET['idGameCrt'], $_array_critique)){
							$CreatedCritique = new Critique($idCreatedCrt);
							$json_r['u'] = $CreatedCritique->lien;
							$json_r['s'] = true;
						}else{
							$json_r['e'] = "Une erreur serveur est survenue, my bad.";
						}
					}else{
						$json_r['e'] = "Veuillez rafraichir la page et recommencer.";
					}
					unset($_SESSION['UserLogged']['authenticity_token']["post_crt_".$_GET['idGameCrt']]);
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