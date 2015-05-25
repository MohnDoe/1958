<?php

Class User{

	static $limit_nbActivityToGet = 7;
	static $limit_nbBadgesProfilToGet = 5;
	static $limit_nbNotificationGlobalToGet = 7;
	static $limit_nbNotificationFollowingsToGet = 7;
	static $array_accepted_thing_post_comment = Array('review', 'microreview', 'list');


	// informations de profil
	public $id;
	public $displayName;
	public $nickname;
	public $twitter;
	public $steam;
	public $facebook;
	public $site;
	public $bio;
	public $location;
	public $nbPoints;
	public $urlPicture;
	
	public $idRank;
	public $nameRank;
	public $showIconRank = false;
	public $arrayUrlIconRank = Array();

	public $idBadgeProfil;
	public $BadgeProfil;

	// connexions informations
	public $email;
	public $birthday;
	public $lastCo;
	public $pass;
	public $dateInscription;
	public $salt;

	public $arrayUrlPictures = Array(); // contient les url des images de profil de l'utilisateur selon les taille
	// 30, 60, 100, "original"
	public $arrayEvaluations = Array(); // contient le nombre d'appréciation positives et negatives reçu par un utilisateur
	// "positives", "negatives", 'total'
	public $nbCritiques; // nombre de critique rédigé par l'utilisateur
	public $nbMicrosCritiques; // nombre de MicrosCritiques rédigé par l'utilisateur
	public $lien; // le lien vers le profil de l'utilisateur
	public $arrayID_followings = Array(); // IDs des gens suivis par l'utilisateur
	public $arrayID_followers = Array(); // IDs des gens qui suives l'utilisateur
	public $arrayGlobalActitivy = Array(); // activités
	public $arrayNbAppreciations = Array(); // nombre d'appréciation donné
	// "positives", "négatives", "total";
	public $nbGamesHave; // nombre de jeu qu'il possède

	public $arrayBadgesProfil = Array();

	public $idMicroCritiqueProfil;
	public $profil_MicroCritique;
	// micro critique du profil

	public $idCritiqueProfil;
	public $profil_Critique;

	public $array_notifications_global = Array();
	public $nbNotificationGlobal_notviewed;

	public $array_notifications_followings = Array();
	public $nbNotificationFollowings_notviewed;
	

	public function __construct($idUser = null){
		if(!is_null($idUser)){
			$this->id = $idUser;
			$this->init_profil_informations();
		}
	}

	public function user_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableUtilisateurs.' WHERE idUser = '.$this->id.' LIMIT 1');
		return $req->fetch();
	}

	public function init_profil_informations(){
		if($data = $this->user_exists()){
			$this->displayName        = $data['displayNameUser'];
			$this->nickname           = $data['nickUser'];
			$this->twitter            = $data['twitterUser'];
			$this->steam              = $data['steamUser'];
			$this->facebook           = $data['facebookUser'];
			$this->site               = $data['siteUser'];
			$this->bio                = $data['bioUser'];
			$this->location           = $data['locationUser'];
			$this->nbPoints           = $data['ptsUser'];
			$this->urlPicture         = $data['urlPPUser'];
			
			$this->lien               = "./@".$this->nickname;
			
			$this->arrayUrlPictures     = $this->getArrayUrlPictures();
			$this->arrayEvaluations     = $this->getArrayEvaluations();
			$this->nbCritiques          = $this->getNbCritiques();
			$this->nbMicrosCritiques    = $this->getNbMicrosCritiques();
			$this->arrayID_followings   = $this->getArrayID_followings();
			$this->arrayID_followers    = $this->getArrayID_followers();
			$this->arrayNbAppreciations = $this->getArrayNbAppreciations();
			$this->nbGamesHave          = $this->getNbGamesHave();

			$this->idMicroCritiqueProfil = $data['idMccrtProfilUser'];
			$this->idBadgeProfil         = $data['idBadgeProfilUser'];

			$this->idRank = $data['idRankUser'];

			switch ($this->idRank) {
				case "1":
					$this->showIconRank = true;
					$this->nameRank = "Staff 1958";
					$this->arrayUrlIconRank = Array(
						20 => PREFIX_URL.FOLDER_SMALLBADGES."/dev-20.png",
						32 => PREFIX_URL.FOLDER_SMALLBADGES."/dev-32.png"
					);
					break;
				
				default:
					# code...
					break;
			}
		}
	}

	public function init_settings(){
		if($data = $this->user_exists()){
			$this->email = $data['emailUser'];
		}
	}
	public function init_profil_BadgeProfil(){
		if($this->idBadgeProfil != -1 AND $this->idBadgeProfil != 0 AND !is_null($this->idBadgeProfil)){
			$this->BadgeProfil = new Badge($this->idBadgeProfil, $this->id);
		}else{
			$arrayBadgeUser = Badge_LVL::progress_default($this->id);
			$this->BadgeProfil = new Badge(Badge::getID_by_code($arrayBadgeUser['currentCode']), $this->id);
		}
	}

	public function get_arrayBadgesProfil(){
		$d = Array();
		$req = DB::$db->query('SELECT idBadgeHaveBadge AS idBadge FROM '.DB::$tableHaveBadge.' WHERE idUserHaveBadge = "'.$this->id.'" ORDER BY dateOptentionBadge DESC LIMIT '.self::$limit_nbBadgesProfilToGet);
		while($data = $req->fetch()){
			$d[] = new Badge($data['idBadge'], $this->id);
		}
		return $d;
	}
	public function init_arrayBadgesProfil(){
		$this->arrayBadgesProfil = $this->get_arrayBadgesProfil();
	}

	public function getNbGamesHave(){
		$req = DB::$db->query('SELECT count(*) AS result FROM '.DB::$tableHaveGame.' WHERE idUserHaveGame = '.$this->id.' LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

	public function get_arrayIDsGamesHave(){
		$req = DB::$db->query('SELECT idJeuHaveGame AS idGame FROM '.DB::$tableHaveGame.' WHERE idUserHaveGame = '.$this->id.' LIMIT 50');
		$d = Array();
		while($data = $req->fetch()){
			$d[] = $data['idGame'];
		}
		return $d;
	}
	public function getNbGamesWant(){
		$req = DB::$db->query('SELECT count(*) AS result FROM '.DB::$tableWantPlay.' WHERE idUserWantPlay = '.$this->id.' LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

	public function get_arrayIDsGamesWant(){
		$req = DB::$db->query('SELECT idJeuWantPlay AS idGame FROM '.DB::$tableWantPlay.' WHERE idUserWantPlay = '.$this->id.' LIMIT 50');
		$d = Array();
		while($data = $req->fetch()){
			$d[] = $data['idGame'];
		}
		return $d;
	}

	public function get_arrayIDsGamesWantAndHave(){
		$d = array_merge($this->get_arrayIDsGamesWant(), $this->get_arrayIDsGamesHave());
		return $d;
	}

	public function get_nextToReadCritique($idCritique){
		$d = Array();
		$arrayIDsGamesWantAndHave = $this->get_arrayIDsGamesWantAndHave();
		$arrayCritiquesNextToRead = Array();
		for ($i=0; $i < count($arrayIDsGamesWantAndHave) ; $i++) { 
			$Game = new Game($arrayIDsGamesWantAndHave[$i]);
			$arrayCritiquesNextToRead = array_merge($arrayCritiquesNextToRead, $Game->get_arrayCritiques());
		}
		// shuffle($arrayCritiquesNextToRead);
		return $arrayCritiquesNextToRead[rand(0, count($arrayCritiquesNextToRead)-1)];
	}
	public function getArrayNbAppreciations(){
		$arrayNbAppreciations = Array();

		$req = DB::$db->query('SELECT count(*) as result FROM '.DB::$tableAppreciations.' WHERE idUserAppr = "'.$this->id.'" AND typeAppr = "P" LIMIT 1');
		$data = $req->fetch();
		$arrayNbAppreciations['positives'] = $data['result'];

		$req = DB::$db->query('SELECT count(*) as result FROM '.DB::$tableAppreciations.' WHERE idUserAppr = "'.$this->id.'" AND typeAppr = "N" LIMIT 1');
		$data = $req->fetch();
		$arrayNbAppreciations['negatives'] = $data['result'];

		$arrayNbAppreciations['total'] = $arrayNbAppreciations['positives']+$arrayNbAppreciations['negatives'];
		return $arrayNbAppreciations;
	}
	public function getArrayUrlPictures(){

		$arrayUrlPictures = Array();
		$arraySizesPictures = Array(30, 60, 100, "original");


		if(strstr($this->urlPicture, ";")){
			$urlData = explode(';', $this->urlPicture);
		}else{
			$urlData = array('','jpg');
		}

		for ($i=0; $i < count($arraySizesPictures); $i++) { 
			$size = $arraySizesPictures[$i];
			$urlPictureUser = $this->id."/".$this->id."_".$urlData[0]."_".$size.".".$urlData[1];
			$file = PATH_USERS_PICTURES_FOLDER.'/'.$urlPictureUser;
			if(!file_exists($file)){
				// $urlPictureUser = '.'.FOLDER_USERS_PICTURES."/404_nopicture_".$size.".jpg";
				$urlPictureUser = '.'.FOLDER_USERS_PICTURES.'/nopic_'.$size.'.jpg';
			}else{
				$urlPictureUser = PREFIX_URL.FOLDER_USERS_PICTURES.'/'.$urlPictureUser;
			}

			$arrayUrlPictures[$size] = $urlPictureUser;
		}

		return $arrayUrlPictures;
	}

	public function getArrayEvaluations(){

		$reqP = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' LEFT JOIN '.DB::$tableCritiques.' ON '.DB::$tableCritiques.'.idCritique = '.DB::$tableAppreciations.'.idCritiqueAppr LEFT JOIN '.DB::$tableMicrosCritiques.' ON '.DB::$tableMicrosCritiques.'.idMccrt = '.DB::$tableAppreciations.'.idMccrtAppr LEFT JOIN '.DB::$tableListes.' ON '.DB::$tableListes.'.idUserList = '.DB::$tableAppreciations.'.idListAppr WHERE '.DB::$tableAppreciations.'.typeAppr = "P" AND ( '.DB::$tableCritiques.'.idUserCritique = "'.$this->id.'" OR '.DB::$tableMicrosCritiques.'.idUserMccrt = "'.$this->id.'" OR '.DB::$tableListes.'.idUserList = "'.$this->id.'" ) LIMIT 1');
		$dataP = $reqP->fetch();
		$nbPos = intval($dataP['result']);
		$reqN = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' LEFT JOIN '.DB::$tableCritiques.' ON '.DB::$tableCritiques.'.idCritique = '.DB::$tableAppreciations.'.idCritiqueAppr LEFT JOIN '.DB::$tableMicrosCritiques.' ON '.DB::$tableMicrosCritiques.'.idMccrt = '.DB::$tableAppreciations.'.idMccrtAppr LEFT JOIN '.DB::$tableListes.' ON '.DB::$tableListes.'.idUserList = '.DB::$tableAppreciations.'.idListAppr WHERE '.DB::$tableAppreciations.'.typeAppr = "N" AND ( '.DB::$tableCritiques.'.idUserCritique = "'.$this->id.'" OR '.DB::$tableMicrosCritiques.'.idUserMccrt = "'.$this->id.'" OR '.DB::$tableListes.'.idUserList = "'.$this->id.'" ) LIMIT 1');
		$dataN = $reqN->fetch();
		$nbNeg = intval($dataN['result']);

		$arrayEvaluations = Array('positives'=> $nbPos, 'negatives'=>$nbNeg, 'total'=>($nbPos+$nbNeg));
	}

	public function getNbCritiques(){
		$req = DB::$db->query('SELECT COUNT(*) AS nb FROM '.DB::$tableCritiques.' WHERE idUserCritique = "'.$this->id.'"');
		$data = $req->fetch();
		return $data['nb'];
	}

	public function getNbMicrosCritiques(){
		$req = DB::$db->query('SELECT COUNT(*) AS nb FROM '.DB::$tableMicrosCritiques.' WHERE idUserMccrt = "'.$this->id.'"');
		$data = $req->fetch();
		return $data['nb'];
	}

	public function getArrayID_followings(){
		$req = DB::$db->query('SELECT idUserToFollowFL AS result FROM '.DB::$tableFollowings.' WHERE idUserWhoFollowFL = "'.$this->id.'"');
		$d = Array();
		while ($data = $req->fetch()){
			$d[] = $data['result'];
		}
		return $d;
	}
	public function getArrayID_followers(){
		$req = DB::$db->query('SELECT idUserWhoFollowFL AS result FROM '.DB::$tableFollowings.' WHERE idUserToFollowFL = "'.$this->id.'"');
		$d = Array();
		while ($data = $req->fetch()){
			$d[] = $data['result'];
		}
		return $d;
	}

	public function getLastMicroCritique(){
		$req = DB::$db->query('SELECT idMccrt AS result FROM '.DB::$tableMicrosCritiques.' WHERE idUserMccrt = "'.$this->id.'" ORDER BY dateMccrt LIMIT 1');
		if($data = $req->fetch()){
			return new MicroCritique($data['result'], true, false);
		}
		return false;
	}

	public function getLastCritique(){
		$req = DB::$db->query('SELECT idCritique AS result FROM '.DB::$tableCritiques.' WHERE idUserCritique = "'.$this->id.'" ORDER BY dateCritique LIMIT 1');
		if($data = $req->fetch()){
			return new Critique($data['result']);
		}
		return false;
	}

	public function init_profil_Critique(){
		$this->profil_Critique = $this->getLastCritique();
	}
	public function set_want_game($idGame){
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableWantPlay.' WHERE idUserWantPlay = "'.$this->id.'" AND idJeuWantPlay = "'.$idGame.'" LIMIT 1');
		if(!$data_check = $req_check->fetch()){
			$this->del_have_game($idGame);
			//if line doesn't exist
			//add line
			$req_add = DB::$db->query('INSERT INTO '.DB::$tableWantPlay.' (`idWantPlay`, `idUserWantPlay`, `idJeuWantPlay`) VALUES ("",'.$this->id.','.$idGame.')');
			$this->add_transaction(
				'game', 
				array(
					"action"  => "set_want_game",
					"id_game" => $idGame
					)
				);
			Notification::notify_people('following_want_game', Array(
				'id_user_who_want' => $this->id,
				'id_game_wanted' => $idGame)
			);
			// Users::givePoints($idUser, 'WANT_GAME', null);
			// Badge_WANT::event_default($idUser);
			// Badge_WANTGAMESNR::event_default($idUser);
			// Badge_WANTGAMEBR::event_default($idUser, $idGame);
			return true;
		}else{
			return false;
		}
	}
	public function set_have_game($idGame){
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableHaveGame.' WHERE idUserHaveGame = "'.$this->id.'" AND idJeuHaveGame = "'.$idGame.'" LIMIT 1');
		if(!$data_check = $req_check->fetch()){
			$this->del_want_game($idGame);
			//if line doesn't exist
			//add line
			$req_add = DB::$db->query('INSERT INTO '.DB::$tableHaveGame.' (`idHaveGame`, `idUserHaveGame`, `idJeuHaveGame`) VALUES ("",'.$this->id.','.$idGame.')');
			$this->add_transaction(
				'game', 
				array(
					"action"  => "set_have_game",
					"id_game" => $idGame
					)
				);
			Notification::notify_people('following_have_game', Array(
				'id_user_who_have' => $this->id,
				'id_game_haved' => $idGame)
			);
			// Users::givePoints($idUser, 'WANT_GAME', null);
			// Badge_WANT::event_default($idUser);
			// Badge_WANTGAMESNR::event_default($idUser);
			// Badge_WANTGAMEBR::event_default($idUser, $idGame);
			return true;
		}else{
			return false;
		}
	}
	public function del_have_game($idGame){
		/*
		Cette fonction supprimer la ligne du jeu que l'utilisateur veut
		*/
		//check line
		if($this->is_having_game($idGame)){
			//if line doesn't exist
			//delete line
			$req_del = DB::$db->query('DELETE FROM `'.DB::$tableHaveGame.'` WHERE idUserHaveGame = "'.$this->id.'" AND idJeuHaveGame = "'.$idGame.'"');
			// Users::givePoints($idUser, 'WANT_GAME', null,'take');
			// DB::addTransaction($idUser, $idGame, null, null, null, null, null, null, "DEL_WANT_GAME", "GAME");
			$this->add_transaction(
				'game', 
				array(
					"action"  => "del_have_game",
					"id_game" => $idGame
					)
				);
			return true;
		}else{
			return false;
		}
	}
	public function del_want_game($idGame){
		/*
		Cette fonction supprimer la ligne du jeu que l'utilisateur veut
		*/
		//check line
		if($this->is_wanting_game($idGame)){
			//if line doesn't exist
			//delete line
			$req_del = DB::$db->query('DELETE FROM `'.DB::$tableWantPlay.'` WHERE idUserWantPlay = "'.$this->id.'" AND idJeuWantPlay = "'.$idGame.'"');
			// Users::givePoints($idUser, 'WANT_GAME', null,'take');
			// DB::addTransaction($idUser, $idGame, null, null, null, null, null, null, "DEL_WANT_GAME", "GAME");
			$this->add_transaction(
				'game', 
				array(
					"action"  => "del_want_game",
					"id_game" => $idGame
					)
				);
			return true;
		}else{
			return false;
		}
	}
	public function is_wanting_game($idGame){
		/*
		return true si l'utilisateur veut le jeu indiqué
		*/
		//check line
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableWantPlay.' WHERE idUserWantPlay = "'.$this->id.'" AND idJeuWantPlay = "'.$idGame.'" LIMIT 1');
		if($data_check = $req_check->fetch()){
			return true;
		}else{
			return false;
		}
	}
	public function is_having_game($idGame){
		/*
		return true si l'utilisateur veut le jeu indiqué
		*/
		//check line
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableHaveGame.' WHERE idUserHaveGame = "'.$this->id.'" AND idJeuHaveGame = "'.$idGame.'" LIMIT 1');
		if($data_check = $req_check->fetch()){
			return true;
		}else{
			return false;
		}
	}
	public function getArrayGlobalActivity(){
		/*
		Cette fonction retourne les activités lié à un utilisateur
		*/
		$reqWhere = DB::$tableTransactions.'.idUserTransaction = "'.$this->id.'"';

		$aActionActivity = array('SET_APPR_GAME_P', 'NOTE_GAME', 'BADGE_RECEIVED', 'SET_HAVE_GAME', 'SET_WANT_GAME', 'CREATE_CRT');

		// création partie requête filtre action
		$reqActionActivity = "AND( ";
		for ($i=0; $i < count($aActionActivity); $i++) { 
			$reqActionActivity .= DB::$tableTransactions.'.actionTransaction = "'.$aActionActivity[$i].'" OR ';
		}
		$reqActionActivity = substr($reqActionActivity, 0, -4);
		$reqActionActivity .= ")";
		///////
		// parti join requete table
		$reqJoin = 'LEFT JOIN '.DB::$tableBadges.' ON '.DB::$tableTransactions.'.idBadgeTransaction = '.DB::$tableBadges.'.idBadge ';
		$reqJoin .= 'LEFT JOIN '.DB::$tableNotesGame.' ON '.DB::$tableTransactions.'.idNoteTransaction = '.DB::$tableNotesGame.'.idNote ';
		$reqJoin .= 'LEFT JOIN '.DB::$tableCritiques.' ON '.DB::$tableTransactions.'.idCritiqueTransaction = '.DB::$tableCritiques.'.idCritique ';
		$reqJoin .= 'LEFT JOIN '.DB::$tableGames.' ON '.DB::$tableTransactions.'.idJeuTransaction = '.DB::$tableGames.'.idJeu ';
		$reqJoin .= 'LEFT JOIN '.DB::$tableUtilisateurs.' ON '.DB::$tableTransactions.'.idUserTransaction = '.DB::$tableUtilisateurs.'.idUser';
		////
		// parti champs requête
		//badge
		$reqFields = DB::$tableBadges.'.idBadge AS idBadgeActivity, ';
		//jeu
		
		$reqFields .= DB::$tableCritiques.'.idCritique AS idCritiqueActivity, ';
		$reqFields .= DB::$tableNotesGame.'.totalNote AS totalNote, ';
		$reqFields .= DB::$tableGames.'.idJeu AS idJeuActivity, ';
		//date
		$reqFields .= DB::$tableTransactions.'.dateTransaction AS dateActivity, '.DB::$tableTransactions.'.actionTransaction AS actionActivity, '.DB::$tableTransactions.'.idTransaction AS idActivity';
		////

		$limit = self::$limit_nbActivityToGet;

		$req = DB::$db->query('SELECT '.$reqFields.' FROM `'.DB::$tableTransactions.'` '.$reqJoin.' WHERE '.$reqWhere.' '.$reqActionActivity.' ORDER BY '.DB::$tableTransactions.'.dateTransaction DESC LIMIT '.$limit);
		
		$result = Array();
		while($data = $req->fetch()){
			$activity['actionActivity'] = $data['actionActivity'];
			if($data['idJeuActivity'] != 0 AND !is_null($data['idJeuActivity'])){
				$activity['gameActivity'] = new Game($data['idJeuActivity']);
			}
			if($data['idCritiqueActivity'] != 0 AND !is_null($data['idCritiqueActivity'])){
				$activity['critiqueActivity'] = new Critique($data['idCritiqueActivity']);
			}
			if($data['idBadgeActivity'] != 0 AND !is_null($data['idBadgeActivity'])){
				$activity['badgeActivity'] = new Badge($data['idBadgeActivity'], $this->id);
			}
			if($data['totalNote'] != 0 AND !is_null($data['totalNote'])){
				$activity['noteActivity'] = $data['totalNote']*10;
			}

			$activity['dateActivity'] = $data['dateActivity'];
			// fin activity

			list($dateActivity, $heureActivity) = explode(" ", $activity['dateActivity']);

			$result[$dateActivity][$activity['actionActivity']][] = $activity;
			$activity = Array();
		}
		krsort($result);
		return $result;
	}

	public function init_arrayGlobalActivity(){
		$this->arrayGlobalActivity = $this->getArrayGlobalActivity();
	}

	public function init_profil_MicroCritique(){
		if($this->idMicroCritiqueProfil != null AND $this->idMicroCritiqueProfil != 0){
			$this->profil_MicroCritique = new MicroCritique($this->idMicroCritiqueProfil, true, false);
		}else{
			$this->profil_MicroCritique = $this->getLastMicroCritique();
		}
	}

	public function get_array_notifications_global($page = 1){
		$limit = self::$limit_nbNotificationGlobalToGet;
		$array_types_global = Notification::$array_types_global;
		$premiereEntree=($page-1)*$limit;
		
		$array_notifications_global = Array();
		$requete = "";
		$requete .= "SELECT idNotification FROM ".DB::$tableNotifications." WHERE idUserNotification = '".$this->id."' ";
		$requete .= "AND ( ";
		for ($i=0; $i < count($array_types_global) ; $i++) { 
			$type = $array_types_global[$i];
			$requete .= 'typeNotification = "'.$type.'" OR ';
		}
		$requete = substr($requete, 0, -4).") ";
		$requete .= "ORDER BY dateNotification DESC ";
		$requete .= "LIMIT ".$premiereEntree.", ".$limit;

		// var_dump($requete); die();
		$req = DB::$db->query($requete);
		while ($data = $req->fetch()) {
			$array_notifications_global[] = new Notification($data['idNotification']);
		}
		return $array_notifications_global;

	}
	public function init_array_notifications_global($page = 1){
		$this->array_notifications_global = $this->get_array_notifications_global($page);
	}

	public function get_array_notifications_followings($page = 1){
		$limit = self::$limit_nbNotificationFollowingsToGet;
		$array_types_followings = Notification::$array_types_followings;
		$premiereEntree=($page-1)*$limit;
		
		$array_notifications_followings = Array();
		$requete = "";
		$requete .= "SELECT idNotification FROM ".DB::$tableNotifications." WHERE idUserNotification = '".$this->id."' ";
		$requete .= "AND ( ";
		for ($i=0; $i < count($array_types_followings) ; $i++) { 
			$type = $array_types_followings[$i];
			$requete .= 'typeNotification = "'.$type.'" OR ';
		}
		$requete = substr($requete, 0, -4).") ";
		$requete .= "ORDER BY dateNotification DESC ";
		$requete .= "LIMIT ".$premiereEntree.", ".$limit;

		// var_dump($requete); die();
		$req = DB::$db->query($requete);
		while ($data = $req->fetch()) {
			$array_notifications_followings[] = new Notification($data['idNotification']);
		}
		return $array_notifications_followings;

	}
	public function init_array_notifications_followings($page = 1){
		$this->array_notifications_followings = $this->get_array_notifications_followings($page);
	}
	public function get_nbNotificationGlobal_notviewed(){
		$array_types_global = Notification::$array_types_global;
		
		$nbNotificationGlobal_notviewed = Array();
		$requete = "";
		$requete .= "SELECT COUNT(*) AS result FROM ".DB::$tableNotifications." WHERE idUserNotification = '".$this->id."' ";
		$requete .= "AND ( ";
		for ($i=0; $i < count($array_types_global) ; $i++) { 
			$type = $array_types_global[$i];
			$requete .= 'typeNotification = "'.$type.'" OR ';
		}
		$requete = substr($requete, 0, -4).") AND viewedNotification = 0";

		// var_dump($requete); die();
		$req = DB::$db->query($requete);
		$data = $req->fetch();
		return $data['result'];

	}
	public function init_nbNotificationGlobal_notviewed(){
		$this->nbNotificationGlobal_notviewed = $this->get_nbNotificationGlobal_notviewed();
	}

	public function get_nbNotificationFollowings_notviewed(){
		$array_types_followings = Notification::$array_types_followings;
		
		$nbNotificationFollowings_notviewed = Array();
		$requete = "";
		$requete .= "SELECT COUNT(*) AS result FROM ".DB::$tableNotifications." WHERE idUserNotification = '".$this->id."' ";
		$requete .= "AND ( ";
		for ($i=0; $i < count($array_types_followings) ; $i++) { 
			$type = $array_types_followings[$i];
			$requete .= 'typeNotification = "'.$type.'" OR ';
		}
		$requete = substr($requete, 0, -4).") AND viewedNotification = 0";

		// var_dump($requete); die();
		$req = DB::$db->query($requete);
		$data = $req->fetch();
		return $data['result'];

	}
	public function init_nbNotificationFollowings_notviewed(){
		$this->nbNotificationFollowings_notviewed = $this->get_nbNotificationFollowings_notviewed();
	}

	public function generate_authenticity_token($name = ""){
		//token f
		$authenticity_token = md5(uniqid().rand(1,1958).md5($name));
		if(!isset($_SESSION['UserLogged']['authenticity_token'][$name])){
			$_SESSION['UserLogged']['authenticity_token'][$name] = $authenticity_token;
			return $authenticity_token;
		}else{
			return $_SESSION['UserLogged']['authenticity_token'][$name];
		}
	}

	public function post_comment($content, $on_what, $id_what){
		$req_content = strip_tags($content);
		if(in_array($on_what, self::$array_accepted_thing_post_comment)){
			$req_id_list = $req_id_crt = $req_id_mccrt = -1;
			$real_what = "";
			switch ($on_what) {
				case 'review':
					$req_id_crt = $id_what;
					$real_what = "crt";
					break;
				case 'microreview':
					$req_id_mccrt = $id_what;
					$real_what = "mccrt";
					break;
				case 'list':
					$req_id_list = $id_what;
					$real_what = "list";
					break;
				
				default:
					# code...
					break;
			}

			$req = DB::$db->prepare('INSERT INTO `'.DB::$tableCommentaires.'`
				(`idCommentaire`, `idUserCommentaire`, `idMicroCritiqueCommentaire`, `idCritiqueCommentaire`, `idListeCommentaire`, `contenueCommentaire`, `dateCommentaire`)
				VALUES ("", :idUser, :idMccrtToComment, :idCrtToComment,  :idListToComment, :contentComment, NOW())');
			$req->execute(
				array(
					':idUser'           => $this->id,
					':idCrtToComment'   => $req_id_crt,
					':idListToComment'  => $req_id_list,
					':idMccrtToComment' => $req_id_mccrt,
					':contentComment'   => $req_content
				));
			$idCreatedComment = DB::$db->lastInsertId();

			$this->add_transaction(
				'comment', 
				array(
					"id_comment"           => $idCreatedComment,
					"what_is_commented"    => $real_what,
					"id_what_is_commented" => $id_what
					)
				);
			Notification::notify_people(
				'comment',
				array(
					"id_author_comment"    => $this->id,
					"id_comment"           => $idCreatedComment,
					"what_is_commented"    => $real_what,
					"id_what_is_commented" => $id_what
					)
				);
			return $idCreatedComment;
		}else{
			return false;
		}
	}

	public function add_transaction($action, $params){
		$_transaction_id_game = $_transaction_id_note = $_transaction_id_comment = $_transaction_id_badge = $_transaction_id_mccrt = $_transaction_id_crt = $_transaction_id_list = $_transaction_pts = $_transaction_action = $_transaction_type = null;
		
		$_transaction_type = strtoupper($action);

		switch ($action) {
			case 'comment':
				$_id_comment = $params['id_comment'];
				$_id_what_is_commented = $params['id_what_is_commented'];
				$_what_is_commented = $params['what_is_commented'];
				$_transaction_id_comment = $_id_comment;
				switch ($_what_is_commented) {
					case 'crt':
						$_transaction_id_crt = $_id_what_is_commented;
						break;
					case 'mccrt':
						$_transaction_id_mccrt = $_id_what_is_commented;
						break;
					case 'list':
						$_transaction_id_list = $_id_what_is_commented;
						break;
					default:
						# code...
						break;
				}
				$_transaction_action = 'POST_COMMENT_'.strtoupper($_what_is_commented);
			break;
			case 'note':
				$_id_note = $params['id_note'];
				$_id_author_note = $params['id_author_note'];
				$_what_is_noted = $params['what_is_noted'];
				$_id_what_is_noted = $params['id_what_is_noted'];
				$_transaction_id_note = $_id_note;
				switch ($_what_is_noted) {
					case 'game':
						$_transaction_id_game = $_id_what_is_noted;
						break;
					
					default:
						# code...
						break;
				}
				$_transaction_action = 'NOTE_'.strtoupper($_what_is_noted);
			break;
			case 'create_crt':
				$_transaction_id_crt = $params['id_critique'];
				$_transaction_action = strtoupper($action);
				$_transaction_type = "CRT";
			break;
			case 'create_mccrt':
				$_transaction_id_crt = $params['id_micro_critique'];
				$_transaction_action = strtoupper($action);
				$_transaction_type = "MCCRT";
			break;
			case 'view':
				switch ($params['what_is_viewed']) {
					case 'game':
						$_transaction_id_game = $params['id_what_is_viewed'];
						break;
					case 'list':
						$_transaction_id_list = $params['id_what_is_viewed'];
						break;
					case 'crt':
						$_transaction_id_crt = $params['id_what_is_viewed'];
						break;
					case 'mccrt':
						$_transaction_id_mccrt = $params['id_what_is_viewed'];
						break;
					
					default:
						# code...
						break;
				}
				$_transaction_action = "VIEW_".strtoupper($params['what_is_viewed']);
			break;
			case 'game':
				$_transaction_action = strtoupper($params['action']);
				$_transaction_id_game = $params['id_game'];
			break;
			case 'appr':
				$_action_appr = $params['action_appr'];
				$_type_appr = $params['type_appr'];
				$_what_is_appr = $params['what_is_appr'];
				switch ($_what_is_appr) {
					case 'game':
						$_transaction_id_game = $params['id_what_is_appr'];
						break;
					case 'list':
						$_transaction_id_list = $params['id_what_is_appr'];
						break;
					case 'crt':
						$_transaction_id_crt = $params['id_what_is_appr'];
						break;
					case 'mccrt':
						$_transaction_id_mccrt = $params['id_what_is_appr'];
						break;
					
					default:
						# code...
						break;
				}
				$_transaction_action = strtoupper($_action_appr."_APPR_".$_what_is_appr."_".$_type_appr);
			break;
			
			default:
				# code...
				break;
		}
		DB::addTransaction(
			$this->id, 
			$_transaction_id_game,
			$_transaction_id_comment,
			$_transaction_id_badge,
			$_transaction_id_mccrt,
			$_transaction_id_crt,
			$_transaction_id_list,
			$_transaction_pts,
			$_transaction_action,
			$_transaction_type,
			$_transaction_id_note
		);
	}

	public function mark_notif_as_read($idNotification){
		DB::$db->query('UPDATE '.DB::$tableNotifications.' SET viewedNotification = 1 WHERE idNotification = "'.$idNotification.'" AND idUserNotification = '.$this->id);
	}

	public function send_notes_game($idGame, $arrayNotes){
		$req = DB::$db->prepare('INSERT INTO `notes_game`(`dateNote`, `idGameNote`, `gameplayNote`, `graphismNote`, `boNote`, `lifetimeNote`, `storyNote`, `totalNote`, `idUserNote`)
													VALUES (NOW(), :idGame, :gameplayNote, :graphismNote, :boNote, :lifetimeNote, :storyNote, :totalNote, :idUser)');
		$req->execute(array(
			':idGame'       => $idGame,
			':idUser'       => $this->id,
			':gameplayNote' => $arrayNotes['gameplay'],
			':graphismNote' => $arrayNotes['graphism'],
			':boNote'       => $arrayNotes['bo'],
			':lifetimeNote' => $arrayNotes['lifetime'],
			':storyNote'    => $arrayNotes['story'],
			':totalNote'    => (($arrayNotes['gameplay']+$arrayNotes['graphism']+$arrayNotes['bo']+$arrayNotes['lifetime']+$arrayNotes['story'])/5)
			));
		$idCreatedNote = DB::$db->lastInsertId();
		$arrayNotes['moyenne'] = (($arrayNotes['gameplay']+$arrayNotes['graphism']+$arrayNotes['bo']+$arrayNotes['lifetime']+$arrayNotes['story'])/5);
		Notification::notify_people('following_note', Array(
			'id_note' => $idCreatedNote,
			'id_author_note' => $this->id,
			'what_is_noted' => "game",
			'id_what_is_noted' => $idGame,
			'arrayNotes' => $arrayNotes
			));
		$this->add_transaction(
			'note', 
			array(
				'id_note' => $idCreatedNote,
				'id_author_note' => $this->id,
				'what_is_noted' => "game",
				'id_what_is_noted' => $idGame,
				'arrayNotes' => $arrayNotes
				)
			);
	}

	public function post_critique($idGame, $arrayCritique){

		$moyenne = ($arrayCritique['notes']['gameplay']
			+$arrayCritique['notes']['graphism']
			+$arrayCritique['notes']['bo']
			+$arrayCritique['notes']['story']
			+$arrayCritique['notes']['lifetime'])/5;
		$nbWordsCritique = str_word_count(strip_tags($arrayCritique['content']));
		$req = DB::$db->prepare('INSERT INTO `'.DB::$tableCritiques.'`(`idJeuCritique`, `idUserCritique`, `titreCritique`, `noteGameplayCritique`, `noteGraphismCritique`, `noteBOCritique`, `noteStoryCritique`, `noteLifetimeCritique`, `noteMoyenneCritique`, `contenueCritique`, `dateCritique`, `viewsCritique`, `populariteCritique`, `nbWordsCritique`)
		VALUES (:idGame, :idUser, :titleCrt, :noteGR, :noteGR, :noteBO, :noteST, :noteLT, :noteM, :contentCrt ,NOW(),0,0 , :nbWordsCritique)');
		$req->execute(array(
					':idGame'          =>$idGame,
					':idUser'          =>$this->id,
					':titleCrt'        =>substr($arrayCritique['title'], 0, 100),
					':noteGP'          =>$arrayCritique['notes']['gameplay'],
					':noteGR'          =>$arrayCritique['notes']['graphism'],
					':noteBO'          =>$arrayCritique['notes']['bo'],
					':noteST'          =>$arrayCritique['notes']['story'],
					':noteLT'          =>$arrayCritique['notes']['lifetime'],
					':noteM'           =>$moyenne,
					':contentCrt'      =>$arrayCritique['content'],
					':nbWordsCritique' =>$nbWordsCritique
			));
		$idCreatedCRT = DB::$db->lastInsertId();
		$this->add_transaction("create_crt", Array(
			'id_critique' => $idCreatedCRT,
			));

		Notification::notify_people(
				'following_post',
				array(
					"id_poster"    => $this->id,
					"what_is_posted"    => 'crt',
					"id_what_is_posted" => $idCreatedCRT
					)
				);

		/* BADGES ET TOUT */

		return $idCreatedCRT;
	}
	public function post_microcritique($idGame, $content){
		$nbWordsMicroCritique = str_word_count(strip_tags(substr($content, 0, 140)));
		$req = DB::$db->prepare('INSERT INTO `'.DB::$tableMicrosCritiques.'`(`idMccrt`, `idJeuMccrt`, `idUserMccrt`, `contenueMccrt`, `dateMccrt`, `nbWordsMccrt`) VALUES ("",:idGame, :idUser, :contentMccrt, NOW(), :nbWordsMccrt)');
		$req->execute(array(
					':idGame'       => $idGame,
					':idUser'       => $this->id,
					':contentMccrt' => $content,
					':nbWordsMccrt' => $nbWordsMicroCritique
			));
		$idCreatedMCCRT = DB::$db->lastInsertId();
		$this->add_transaction("create_mccrt", Array(
			'id_micro_critique' => $idCreatedMCCRT,
			));

		Notification::notify_people(
				'following_post',
				array(
					"id_poster"         => $this->id,
					"what_is_posted"    => 'mccrt',
					"id_what_is_posted" => $idCreatedMCCRT
					)
				);

		/* BADGES ET TOUT */

		return $idCreatedMCCRT;
	}

	public function user_follows($idUser){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableFollowings.' WHERE idUserToFollowFL = "'.$idUser.'" AND idUserWhoFollowFL = "'.$this->id.'" LIMIT 1');
		if($req->fetch()){
			return true;
		}
		return false;
	}
	public function follow_someone($idUser){
		$exec = DB::$db->query('INSERT INTO  `'.DB::$tableFollowings.'` ( `idFL` , `idUserWhoFollowFL` , `idUserToFollowFL` , `dateFollowFL` ) VALUES ( NULL ,  "'.$this->id.'",  "'.$idUser.'",  NOW());');
		return $exec;
	}
	public function unfollow_someone($idUser){
		$exec = DB::$db->query('DELETE FROM `'.DB::$tableFollowings.'` WHERE idUserWhoFollowFL = "'.$this->id.'" AND idUserToFollowFL = "'.$idUser.'"');
		return $exec;
	}

	public function is_liking($Thing){
		$class = get_class($Thing);
		switch ($class) {
			case 'Critique':
				$id_search = "idCritiqueAppr";
				break;
			case 'MicroCritique':
				$id_search = "idMccrtAppr";
				break;
			case 'ListGames':
				$id_search = "idListAppr";
				break;
			default:
				return false;
				break;
		}
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableAppreciations.' WHERE idUserAppr = "'.$this->id.'" AND '.$id_search.' = "'.$Thing->id.'" AND typeAppr = "P" LIMIT 1');
		if($data_check = $req_check->fetch()){
			return true;
		}else{
			return false;
		}
	}
	public function is_disliking($Thing){
		$class = get_class($Thing);
		switch ($class) {
			case 'Critique':
				$id_search = "idCritiqueAppr";
				break;
			case 'MicroCritique':
				$id_search = "idMccrtAppr";
				break;
			case 'ListGames':
				$id_search = "idListAppr";
				break;
			default:
				return false;
				break;
		}
		$req_check = DB::$db->query('SELECT * FROM '.DB::$tableAppreciations.' WHERE idUserAppr = "'.$this->id.'" AND '.$id_search.' = "'.$Thing->id.'" AND typeAppr = "N" LIMIT 1');
		if($data_check = $req_check->fetch()){
			return true;
		}else{
			return false;
		}
	}

	public function set_appreciation($Thing, $like_or_dislike){
		$idListAppr = $idCritiqueAppr = $idMccrtAppr = $idJeuAppr = "NULL";
		if($this->user_exists()){
			$class = get_class($Thing);
			switch ($class) {
				case 'Critique':
					$what_is_appr = "crt";
					$id_appr = "idCritiqueAppr";
					$idCritiqueAppr = $Thing->id;
					break;
				case 'MicroCritique':
					$what_is_appr = "mccrt";
					$id_appr = "idMccrtAppr";
					$idMccrtAppr = $Thing->id;
					break;
				case 'ListGames':
					$what_is_appr = "list";
					$id_appr = "idListAppr";
					$idListAppr = $Thing->id;
					break;
				case 'Game':
					$what_is_appr = "game";
					$id_appr = "idJeuAppr";
					$idJeuAppr = $Thing->id;
					break;
				default:
					return false;
					break;
			}
			if($like_or_dislike == "like"){
				$typeAppr = "P";
				if(!$this->is_liking($Thing)){
					if($this->is_disliking($Thing)){
						$this->del_appreciation($Thing, "dislike");
					}
					DB::$db->query('INSERT INTO `'.DB::$tableAppreciations.'`(`idUserAppr`, `idListAppr`, `idCritiqueAppr`, `idMccrtAppr`, `idJeuAppr`, `typeAppr`)
																	VALUES ('.$this->id.','.$idListAppr.','.$idCritiqueAppr.','.$idMccrtAppr.','.$idJeuAppr.',"'.$typeAppr.'")');
					$this->add_transaction(
						'appr', 
						array(
							'what_is_appr'    => $what_is_appr,
							'id_what_is_appr' => $Thing->id,
							'action_appr'     => "set",
							'type_appr'       => $typeAppr
							)
						);
				}
			}else if($like_or_dislike == "dislike"){
				$typeAppr = "N";
				if(!$this->is_disliking($Thing)){
					if($this->is_liking($Thing)){
						$this->del_appreciation($Thing, "like");
					}
					DB::$db->query('INSERT INTO `'.DB::$tableAppreciations.'`(`idUserAppr`, `idListAppr`, `idCritiqueAppr`, `idMccrtAppr`, `idJeuAppr`, `typeAppr`)
																	VALUES ('.$this->id.','.$idListAppr.','.$idCritiqueAppr.','.$idMccrtAppr.','.$idJeuAppr.',"'.$typeAppr.'")');
					$this->add_transaction(
						'appr', 
						array(
							'what_is_appr'    => $what_is_appr,
							'id_what_is_appr' => $Thing->id,
							'action_appr'     => "set",
							'type_appr'       => $typeAppr
							)
						);
				}
			}
		}
	}

	public function del_appreciation($Thing, $like_or_dislike){
		$idListAppr = $idCritiqueAppr = $idMccrtAppr = $idJeuAppr = "IS NULL";
		if($this->user_exists()){
			$class = get_class($Thing);
			switch ($class) {
				case 'Critique':
					$what_is_appr = "crt";
					$id_appr = "idCritiqueAppr";
					$idCritiqueAppr = "= ".$Thing->id;
					break;
				case 'MicroCritique':
					$what_is_appr = "mccrt";
					$id_appr = "idMccrtAppr";
					$idMccrtAppr = "= ".$Thing->id;
					break;
				case 'ListGames':
					$what_is_appr = "list";
					$id_appr = "idListAppr";
					$idListAppr = "= ".$Thing->id;
					break;
				case 'Game':
					$what_is_appr = "game";
					$id_appr = "idJeuAppr";
					$idJeuAppr = "= ".$Thing->id;
					break;
				default:
					return false;
					break;
			}
			if($like_or_dislike == "like"){
				$typeAppr = "P";
				if($this->is_liking($Thing)){
					DB::$db->query('DELETE FROM `'.DB::$tableAppreciations.'` WHERE `idUserAppr` = '.$this->id.' AND `idListAppr` '.$idListAppr.' AND `idCritiqueAppr` '.$idCritiqueAppr.' AND `idMccrtAppr` '.$idMccrtAppr.' AND  `idJeuAppr` '.$idJeuAppr.' AND `typeAppr` = "'.$typeAppr.'"');
					$this->add_transaction(
						'appr', 
						array(
							'what_is_appr'    => $what_is_appr,
							'id_what_is_appr' => $Thing->id,
							'action_appr'     => "del",
							'type_appr'       => $typeAppr
							)
						);
				}
			}else if($like_or_dislike == "dislike"){
				$typeAppr = "N";
				if($this->is_disliking($Thing)){
					DB::$db->query('DELETE FROM `'.DB::$tableAppreciations.'` WHERE `idUserAppr` = '.$this->id.' AND `idListAppr` '.$idListAppr.' AND `idCritiqueAppr` '.$idCritiqueAppr.' AND `idMccrtAppr` '.$idMccrtAppr.' AND  `idJeuAppr` '.$idJeuAppr.' AND `typeAppr` = "'.$typeAppr.'"');					
					$this->add_transaction(
						'appr', 
						array(
							'what_is_appr'    => $what_is_appr,
							'id_what_is_appr' => $Thing->id,
							'action_appr'     => "del",
							'type_appr'       => $typeAppr
							)
						);
				}
			}
		}
	}

	/* STATIC */
	static function getID_allUsers(){
		$req = DB::$db->query('SELECT idUser AS result FROM '.DB::$tableUtilisateurs.' WHERE 1');
		$d = Array();
		while ($data = $req->fetch()){
			$d[] = $data['result'];
		}
		return $d;
	}

	static function getID_by_username($username){
		$req = DB::$db->prepare('SELECT idUser AS result FROM '.DB::$tableUtilisateurs.' WHERE nickUser = :letter LIMIT 1');
		$req->execute(array(':letter' => $username));
		if($data = $req->fetch()){
			return $data['result'];
		}
		return false;
	}

	static function getNbMembers(){
		// retourne le nombre d'inscrit sur le site
		$req = DB::$db->query('SELECT count(*) AS result FROM '.DB::$tableUtilisateurs.' WHERE 1 LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}


}
?>