<?php
Class Badge{

	public $id;
	public $name;
	public $description;
	public $objectif;
	public $urlImage;
	public $type;
	public $is_secret;
	public $code;

	public $idUser;
	public $lien;

	public $nbUsersHave; // nombre d'utilisateur possédant ce badge
	
	public $scarcityPercent; // [float] rareté du badge % d'utilisateur le possédant
	public $scarcityText;
	public $scarcityClass;
	
	public $arrayUrlIllustrations = Array();
	

	static $nbMembers; // nombre de membre sur le site

	public function __construct($idBadge = null, $idUser = null){
		self::$nbMembers = User::getNbMembers();
		if(!is_null($idBadge)){
			$this->idUser = $idUser;
			$this->id = $idBadge;
			$this->init();
		}
	}

	public function badge_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableBadges.' WHERE idBadge = "'.$this->id.'" LIMIT 1');
		return $req->fetch();
	}

	public function init(){
		if($data = $this->badge_exists()){
			$this->name        = str_replace("\"", "&quot;", $data['nomBadge']);
			$this->description = $data['descriptionBadge'];
			if($this->description == null OR $this->description == ""){
				$this->description = "Ce badge, aussi cool soit-il ne dispose pas de description pour le moment. Ce qui est fort regrettable car tout badge mérite sa description, n'est ce pas ? C'est pour cette raison qu'en ce moment même une équipe de singes diplômés d'une école de communication prestigieuse rédigent la plus belle et parlante des descriptions de badge qui soit au monde !";
			}
			$this->objectif    = $data['objectifBadge'];
			
			$this->urlImage    = $data['urlImgBadge'];
			$this->type        = $data['typeBadge'];
			$this->is_secret   = ($data['secret'] == 1);
			$this->code        = $data['codeBadge'];
			
			if(!is_null($this->idUser)){
				$this->lien = "./".PART_ONE_LINK_BADGE."/".$this->id."/".$this->idUser;
			}else{
				$this->lien = "./".PART_ONE_LINK_BADGE."/".$this->id;
				
			}

			$this->nbUsersHave           = $this->getNbUsersHave();
			$this->arrayUrlIllustrations = $this->get_arrayUrlIllustrations();
			
			$this->scarcityPercent                          = ($this->nbUsersHave/self::$nbMembers*100);
			list($this->scarcityText, $this->scarcityClass) = $this->init_scarcityTextClass();

			if($this->is_secret){
				$this->objectif = "Faire des trucs secrets !";
				$this->description = "Ce badge est classé parmi les badges secrets, ceux dont on n'ose pas prononcer le nom, ni même l'objectif ! Enquêtez, si vous en avez le courage.";
				$this->scarcityText = "Badge secret";
				$this->scarcityClass = "scarcity-secret";
			}
		}
	}

	public function init_scarcityTextClass(){
		$scarcityBadge = $this->scarcityPercent;
		if($scarcityBadge <= 1){
			$textScarcityBadge = "Badge unique";
			$classScarcityBadge = "scarcity-unique";
		}else if($scarcityBadge <=5){
			$textScarcityBadge = "Badge légendaire";
			$classScarcityBadge = "scarcity-legend";
		}else if($scarcityBadge <= 10){
			$textScarcityBadge = "Badge épique";
			$classScarcityBadge = "scarcity-epic";
		}else if($scarcityBadge <= 15){
			$textScarcityBadge = "Badge rare";
			$classScarcityBadge = "scarcity-rare";
		}else if($scarcityBadge <= 50){
			$textScarcityBadge = "Badge non-commun";
			$classScarcityBadge = "scarcity-uncom";
		}else if($scarcityBadge <= 80){
			$textScarcityBadge = "Badge commun";
			$classScarcityBadge = "scarcity-com";
		}else{
			$textScarcityBadge = "Badge répandu";
			$classScarcityBadge = "scarcity-rep";
		}

		return array($textScarcityBadge, $classScarcityBadge);
	}


	public function have_user($idUser){
		/* EST CE QU'UN utilisateur a ce badge */
		$req = DB::$db->query('SELECT * FROM '.DB::$tableHaveBadge.' WHERE idBadgeHaveBadge = "'.$this->id.'" AND idUserHaveBadge = "'.$idUser.'" LIMIT 1');
		return $req->fetch();
	}

	public function getNbUsersHave(){
		$req = DB::$db->query('SELECT count(*) AS result FROM '.DB::$tableHaveBadge.' WHERE idBadgeHaveBadge = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];	
	}

	public function get_arrayUrlIllustrations(){
		$sizeArray = Array(300, 200, 100, 60, 30);
		$arrayUrlIllustrations = Array();
		for ($i=0; $i < count($sizeArray) ; $i++) { 
			$size = $sizeArray[$i];
			$urlImgBadge = $this->code."_".$size.".png";
			$file = PATH_BADGES_FOLDER.'/'.$urlImgBadge;
			if(!file_exists($file)){
				$urlImgBadge = '.'.FOLDER_BADGES."/TEST_000_".$size.".png";
			}else{
				$urlImgBadge = '.'.FOLDER_BADGES.'/'.$urlImgBadge;
			}

			$arrayUrlIllustrations[$size] = $urlImgBadge;
		}

		return $arrayUrlIllustrations;
	}

	/* STATIC */

	static function getID_by_code($code){
		$req = DB::$db->query('SELECT idBadge AS result FROM '.DB::$tableBadges.' WHERE codeBadge = "'.$code.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

	static function award_user($idUser, $codeBadge){
		$ptsSup = 0;
		$idBadge = self::getID_by_code($codeBadge);
		$Badge = new Badge($idBadge);
		if($Badge->badge_exists()){
			// le badge existe
			if(!$Badge->have_user($idUser)){
				// il ne l'a pas
				DB::$db->query('INSERT INTO `'.DB::$tableHaveBadge.'`(`idHaveBadge`, `idBadgeHaveBadge`, `idUserHaveBadge`, `dateOptentionBadge`, `codeBadgeHaveBadge`) VALUES ("","'.$idBadge.'","'.$idUser.'",NOW(),"'.$codeBadge.'")');
				DB::addTransaction($idUser, null, null, $idBadge, null, null, null, $ptsSup, "BADGE_RECEIVED", "BADGE");
			}else{return false;}
		}else{return false;}
	}

	static function get_progress($currentStats, $numPalier, $className){
		$nbPalier = count($className::$ARRAY_CODE_PALIER);
		if($numPalier>=1){
			$keys_array_CODE_PALIER = array_keys($className::$ARRAY_CODE_PALIER);
			$key_CURRENT_PALIER = $keys_array_CODE_PALIER[$numPalier-1];
			$value_CURRENT_PALIER = $className::$ARRAY_CODE_PALIER[$key_CURRENT_PALIER];
			if($currentStats >= $value_CURRENT_PALIER ){
				// palier dépasser, affichons le suivant
				$currentPalierPts = $value_CURRENT_PALIER;
				$currentPalierNum = $numPalier;
				if($nbPalier != $numPalier){
					// le palier n'est pas le dernier
					$key_NEXT_PALIER = $keys_array_CODE_PALIER[$numPalier];
					$value_NEXT_PALIER = $className::$ARRAY_CODE_PALIER[$key_NEXT_PALIER];

					$nextPalierPts = $value_NEXT_PALIER;
					$nextPalierNum = $numPalier+1;

					$nextCode = $key_NEXT_PALIER;
					$currentCode = $key_CURRENT_PALIER;

					$progress = ($currentStats/*-$currentPalierPts*/)*100/($nextPalierPts/*-$currentPalierPts*/);
				}else{
					// le palier est le dernier, alors afficher progression à 100
					$nextPalierPts = $value_CURRENT_PALIER;
					$nextPalierNum = $numPalier;
					$nextCode = false;	
					$currentCode = $key_CURRENT_PALIER;
					$progress = 100;
				}
			}else{
				// palier trop élever, on déscend
				$numPalier--;
				return self::get_progress($currentStats, $numPalier, $className);
			}
		}else{
			// premier palier pas encore atteint !
			$keys_array_CODE_PALIER = array_keys($className::$ARRAY_CODE_PALIER);
			$key_CURRENT_PALIER = $keys_array_CODE_PALIER[0];
			$value_CURRENT_PALIER = $className::$ARRAY_CODE_PALIER[$key_CURRENT_PALIER];

			$currentPalierPts = $currentPalierNum = 0;
			$nextPalierPts = $value_CURRENT_PALIER;
			$nextPalierNum = 1;
			$nextCode = $key_CURRENT_PALIER;
			$currentCode = false;
			$progress = ($currentStats-$currentPalierPts)*100/($nextPalierPts-$currentPalierPts);
		}
		$result = array(
						'currentPalierNum' => $currentPalierNum,
						'currentPalierPts' => $currentPalierPts,
						'currentStats'     => $currentStats,
						'nextPalierPts'    => $nextPalierPts,
						'nextPalierNum'    => $nextPalierNum,
						'nextCode'         => $nextCode,
						'currentCode'      => $currentCode,
						'progress'         => $progress
						);
		return $result;
	}

	static function try_award($idUser, $statistique){
		foreach (self::$ARRAY_CODE_PALIER as $key => $value) {
			$CODE = $key;
			$PALIER = $value;
			if($statistique >= $PALIER){
				self::award_user($idUser, $CODE);
			}else{
				break;
			}
		}
	}

}
/*
                                                     .##.......########.##.....##.########.##......
                                                     .##.......##.......##.....##.##.......##......
                                                     .##.......##.......##.....##.##.......##......
                                                     .##.......######...##.....##.######...##......
                                                     .##.......##........##...##..##.......##......
                                                     .##.......##.........##.##...##.......##......
                                                     .########.########....###....########.########
*/
Class Badge_LVL extends Badge{

	static $ARRAY_CODE_PALIER = Array(
		"LVL_000" => 0,
		"LVL_001" => 10,
		"LVL_002" => 20,
		"LVL_003" => 30,
		"LVL_004" => 50,
		"LVL_005" => 80,
		"LVL_006" => 130,
		"LVL_007" => 210,
		"LVL_008" => 340,
		"LVL_009" => 550,
		"LVL_010" => 890,
		"LVL_011" => 1440,
		"LVL_012" => 2330,
		"LVL_013" => 3770,
		"LVL_014" => 6100,
		"LVL_015" => 9870,
		"LVL_016" => 15970,
		"LVL_017" => 25840,
		"LVL_018" => 41810,
		"LVL_019" => 67650,
		"LVL_020" => 109460,
	);

	static function event_default($idUser){
		$UserBadged = new User($idUser);
		self::try_award($idUser, $UserBadged->nbPoints);
	}

	static function progress_default($idUser){
		$UserBadged = new User($idUser);
		return self::get_progress($UserBadged->nbPoints, count(self::$ARRAY_CODE_PALIER), get_class());
	}
}
/*
.##....##.########......######..########..########.........########...#######...######..########.########.########.........
.###...##.##.....##....##....##.##.....##....##............##.....##.##.....##.##....##....##....##.......##.....##........
.####..##.##.....##....##.......##.....##....##............##.....##.##.....##.##..........##....##.......##.....##........
.##.##.##.########.....##.......########.....##....#######.########..##.....##..######.....##....######...##.....##.#######
.##..####.##.....##....##.......##...##......##............##........##.....##.......##....##....##.......##.....##........
.##...###.##.....##....##....##.##....##.....##............##........##.....##.##....##....##....##.......##.....##........
.##....##.########......######..##.....##....##............##.........#######...######.....##....########.########.........
*/
Class Badge_NB_CRT extends Badge{
	/*
	Catégorie : Critique
	Type badge : Evolutif
	Concernant le nombre de critiques postées par un utilisateur
	*/

	//codes badges
	static $ARRAY_CODE_PALIER = Array(
		"NB_CRT_005" => 5,
		"NB_CRT_010" => 10,
		"NB_CRT_020" => 20,
		"NB_CRT_050" => 50,
		"NB_CRT_075" => 75,
		"NB_CRT_100" => 100,
		"NB_CRT_200" => 200,
	);
	static function event_default($idUser){
		$UserBadged = new User($idUser);
		self::try_award($idUser, $UserBadged->nbCritiques);
	}
	static function progress_default($idUser){
		$UserBadged = new User($idUser);
		return self::get_progress($UserBadged->nbCritiques,count(self::$ARRAY_CODE_PALIER), get_class());
	}
}
/*
.##....##.########........###....########..########..########.
.###...##.##.....##......##.##...##.....##.##.....##.##.....##
.####..##.##.....##.....##...##..##.....##.##.....##.##.....##
.##.##.##.########.....##.....##.########..########..########.
.##..####.##.....##....#########.##........##........##...##..
.##...###.##.....##....##.....##.##........##........##....##.
.##....##.########.....##.....##.##........##........##.....##
..........######...####.##.....##.########.##....##........
.........##....##...##..##.....##.##.......###...##........
.........##.........##..##.....##.##.......####..##........
.#######.##...####..##..##.....##.######...##.##.##.#######
.........##....##...##...##...##..##.......##..####........
.........##....##...##....##.##...##.......##...###........
..........######...####....###....########.##....##........
*/
Class Badge_NB_APPR extends Badge{
	/*
	Catégorie : Appréciation
	Type badge: evolutif
	Concernant le nombre d'appréciation posté par l'utilisateur
	*/
	//codes badges
	static $ARRAY_CODE_PALIER = Array(
		"NB_APPR_0005" => 5,
		"NB_APPR_0010" => 10,
		"NB_APPR_0020" => 20,
		"NB_APPR_0050" => 50,
		"NB_APPR_0075" => 75,
		"NB_APPR_0100" => 100,
		"NB_APPR_0200" => 200,
		"NB_APPR_0300" => 300,
		"NB_APPR_0500" => 500,
		"NB_APPR_0800" => 800,
		"NB_APPR_1300" => 1300,
		"NB_APPR_2100" => 2100,
		"NB_APPR_3000" => 3000,
	);

	static function event_default($idUser){
		$UserBadged = new User($idUser);
		self::try_award($idUser, $UserBadged->arrayNbAppreciations['total']);
	}

	static function progress_default($idUser){
		$UserBadged = new User($idUser);
		return self::get_progress($UserBadged->arrayNbAppreciations['total'],count(self::$ARRAY_CODE_PALIER), get_class());
	}
}
/*
.##....##.########.....##.....##..######...######..########..########
.###...##.##.....##....###...###.##....##.##....##.##.....##....##...
.####..##.##.....##....####.####.##.......##.......##.....##....##...
.##.##.##.########.....##.###.##.##.......##.......########.....##...
.##..####.##.....##....##.....##.##.......##.......##...##......##...
.##...###.##.....##....##.....##.##....##.##....##.##....##.....##...
.##....##.########.....##.....##..######...######..##.....##....##...
.........########...#######...######..########.########.########.........
.........##.....##.##.....##.##....##....##....##.......##.....##........
.........##.....##.##.....##.##..........##....##.......##.....##........
.#######.########..##.....##..######.....##....######...##.....##.#######
.........##........##.....##.......##....##....##.......##.....##........
.........##........##.....##.##....##....##....##.......##.....##........
.........##.........#######...######.....##....########.########.........
*/
Class Badge_NB_MCCRT extends Badge{
	/*
	Catégorie : Micros-Critiques
	Type badge : Evolutif
	Concernant le nombre de micros-critiques postées par un utilisateur
	*/
	//codes badges
	static $ARRAY_CODE_PALIER = Array(
		"NB_MCCRT_005" => 5,
		"NB_MCCRT_010" => 10,
		"NB_MCCRT_020" => 20,
		"NB_MCCRT_050" => 50,
		"NB_MCCRT_075" => 75,
		"NB_MCCRT_100" => 100,
		"NB_MCCRT_200" => 200,
		"NB_MCCRT_500" => 500,
	);

	static function event_default($idUser){
		$UserBadged = new User($idUser);
		self::try_award($idUser, $UserBadged->nbMicrosCritiques);
	}

	static function progress_default($idUser){
		$UserBadged = new User($idUser);
		return self::get_progress($UserBadged->nbMicrosCritiques, count(self::$ARRAY_CODE_PALIER), get_class());
	}
}
/*
..######...#######..##.......##.......########..######..########.####..#######..##....##
.##....##.##.....##.##.......##.......##.......##....##....##.....##..##.....##.###...##
.##.......##.....##.##.......##.......##.......##..........##.....##..##.....##.####..##
.##.......##.....##.##.......##.......######...##..........##.....##..##.....##.##.##.##
.##.......##.....##.##.......##.......##.......##..........##.....##..##.....##.##..####
.##....##.##.....##.##.......##.......##.......##....##....##.....##..##.....##.##...###
..######...#######..########.########.########..######.....##....####..#######..##....##
.........##....##.########......######......###....##.....##.########..######.........
.........###...##.##.....##....##....##....##.##...###...###.##.......##....##........
.........####..##.##.....##....##.........##...##..####.####.##.......##..............
.#######.##.##.##.########.....##...####.##.....##.##.###.##.######....######..#######
.........##..####.##.....##....##....##..#########.##.....##.##.............##........
.........##...###.##.....##....##....##..##.....##.##.....##.##.......##....##........
.........##....##.########......######...##.....##.##.....##.########..######.........
*/
Class Badge_COLLECT extends Badge{
	/*
	Catégorie : Jeux
	Type : Evolutif
	Nombre de jeux possédés par un utilisateur
	*/

	static $ARRAY_CODE_PALIER = Array(
	//codes badges
		"COLLECT_005"
		=> 5,
		"COLLECT_010"
		=> 10,
		"COLLECT_020"
		=> 20,
		"COLLECT_050"
		=> 50,
		"COLLECT_075"
		=> 75,
		"COLLECT_100"
		=> 100,
		"COLLECT_200"
		=> 200,
		"COLLECT_350"
		=> 350,
		"COLLECT_750"
		=> 750,
		"COLLECT_1000"
		=> 1000,
	);

	static function event_default($idUser){
		$UserBadged = new User($idUser);
		self::try_award($idUser, $UserBadged->nbGamesHave);
	}

	static function progress_default($idUser){
		$UserBadged = new User($idUser);
		return self::get_progress($UserBadged->nbGamesHave, count(self::$ARRAY_CODE_PALIER), get_class());
	}
}

/*
.##........#######...#######..##.....##....###.....######..########
.##.......##.....##.##.....##.##.....##...##.##...##....##.##......
.##.......##.....##.##.....##.##.....##..##...##..##.......##......
.##.......##.....##.##.....##.##.....##.##.....##.##.......######..
.##.......##.....##.##..##.##.##.....##.#########.##.......##......
.##.......##.....##.##....##..##.....##.##.....##.##....##.##......
.########..#######...#####.##..#######..##.....##..######..########
*/
Class Badge_LOQUACE extends Badge{
	/*
	Catégorie : Jeux
	Type : Evolutif
	Nombre de jeux possédés par un utilisateur
	*/

	static $ARRAY_CODE_PALIER = Array(
	//codes badges
		"LOQUACE_1"
		=> 2000
	);

	static function event_default($idUser, $nbChar){
		self::try_award($idUser, $nbChar);
	}
	public static function try_award($idUser, $nbCharCrt){
		if($nbCharCrt >= 2000){
			self::award_user($idUser, "LOQUACE_1");
		}
	}
}
/*
.########.....###....########......######......###....##.....##.########
.##.....##...##.##...##.....##....##....##....##.##...###...###.##......
.##.....##..##...##..##.....##....##.........##...##..####.####.##......
.########..##.....##.##.....##....##...####.##.....##.##.###.##.######..
.##.....##.#########.##.....##....##....##..#########.##.....##.##......
.##.....##.##.....##.##.....##....##....##..##.....##.##.....##.##......
.########..##.....##.########......######...##.....##.##.....##.########
*/
Class Badge_BADGAME extends Badge{
	/*
	Catégorie : Jeux
	Type : Evolutif
	Obtenu si une note de critique descend sous un seuil

	*/

	static $ARRAY_CODE_PALIER = Array(
	//codes badges
		"BADGAME_1"
		=> 20
	);

	static function event_default($idUser, $nbChar){
		self::try_award($idUser, $nbChar);
	}
	public static function try_award($idUser, $noteCrt){
		if($noteCrt <= 20){
			self::award($idUser, "BADGAME_1");
		}
	}
}
Class Badge_MEANGAME extends Badge{
	/*
	Catégorie : Critique
	Type : Fixe 
	Obtenu si une note de critique est égale à la moyenne
	*/

	const _NOTE_MEAN_CRT_MIN = 45;
	const _NOTE_MEAN_CRT_MAX = 55;

	const _BADGE_CODE = "MEANGAME_1";

	public static function event_default($idUser, $noteCrt){
		self::try_award($idUser, $noteCrt);
	}

	public static function try_award($idUser, $noteCrt){
		if($noteCrt >= self::_NOTE_MEAN_CRT_MIN AND $noteCrt <= self::_NOTE_MEAN_CRT_MIN){
			self::award_user($idUser, self::_BADGE_CODE);
		}
	}
}
Class Badge_GOODGAME extends Badge{
	/*
	Catégorie : Critique
	Type : Fixe 
	Obtenu si une note de critique descend sous un seuil
	*/

	const _NOTE_GOOD_CRT = 90;

	const _BADGE_CODE = "GOODGAME_1";

	public static function event_default($idUser, $noteCrt){
		self::try_award($idUser, $noteCrt);
	}

	public static function try_award($idUser, $noteCrt){
		if($noteCrt >= self::_NOTE_GOOD_CRT){
			self::award_user($idUser, self::_BADGE_CODE);
		}
	}
}
?>