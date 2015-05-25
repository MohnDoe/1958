<?php
	$_UserLogged = new User(17);
	$_SESSION['UserLogged']['id'] = $_UserLogged->id;
	$_UserLogged->init_profil_informations();
	$_CURRENT_USER = 0;
	if(isset($_UserLogged)){
		$_CURRENT_USER = [];
		$_CURRENT_USER['id']               = $_UserLogged->id;
		$_CURRENT_USER['login']            = $_UserLogged->nickname;
		$_CURRENT_USER['rank']['id']       = $_UserLogged->idRank;
		$_CURRENT_USER['avatars_url'][30]  = $_UserLogged->arrayUrlPictures[30];
		$_CURRENT_USER['avatars_url'][60]  = $_UserLogged->arrayUrlPictures[60];
		$_CURRENT_USER['avatars_url'][100] = $_UserLogged->arrayUrlPictures[100];
		$_CURRENT_USER['points']['total']  = $_UserLogged->nbPoints;
		$_CURRENT_USER_JS = json_encode($_CURRENT_USER);
	}else{
		$_CURRENT_USER_JS = 0;
	}
?>