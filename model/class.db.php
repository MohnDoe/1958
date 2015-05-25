<?php
/*
Cette classe contient toutes les informations necessaire au bon fonctionnement d'1958.
Les noms des tables de BDD, les points paccordés à chaque action
*/
Class DB{
	
	static $db;

	//tables
	static $tableGames             = "jeux";
	static $tableFollowings        = "followings";
	static $tableGamemashVotes     = "gamemash_votes";
	static $tableGamemashScores    = "gamemash_scores";
	static $tableNotifications     = "notifications";
	static $tableWantPlay          = "wanttoplay";
	static $tableHaveGame          = "havegame";
	static $tableHaveBadge         = "havebadge";
	static $tableBadges            = "badges";
	static $tableCritiques         = "critiques";
	static $tableMicrosCritiques   = "micros_critiques";
	static $tablePlatforms         = "platforms";
	static $tableUtilisateurs      = "users";
	static $tableDevelopers        = "developers";
	static $tableEditors           = "editors";
	static $tableAppreciations     = "appreciations";
	static $tableCommentaires      = "commentaires";
	static $tableImagesGame        = "imagesjeu";
	static $tableListes            = "listsgames";
	static $tableGamesInList       = "isinlist";
	static $tableTransactions      = "transactions";
	static $tableRequestInvitation = "emailsrequest";
	static $tableRecovery          = "recoverykeys";
	static $tableMissing           = "missingcover";
	static $tableNotesGame         = "notes_game";
	static $tablePyongs            = "pyongs";

	//points
	protected static $pts_ADD_GAME_ACCEPTED         = 50; //la proposition d'ajout d'un jeu(venant d'un utilisateur) est acceptée
	protected static $pts_MODIF_GAME_ACCEPTED       = 25; //la proposition de modification d'un jeu est acceptée
	protected static $pts_PROP_SCREEN_GAME_ACCEPTED = 25; //la proposition de screen est acceptée
	protected static $pts_PROP_BA_GAME_ACCEPTED     = 25; //la proposition est bande-annonce est acceptée
	protected static $pts_PROP_ADD_GAME             = 25; //l'utilisateur propose l'ajout d'un jeu
	protected static $pts_CRT_CREATED               = 25; //l'utilisateur poste une critique
	protected static $pts_PROP_MODIF_GAME           = 20; //l'utilisateur propose la modification d'un jeu
	protected static $pts_PROP_SCREEN_GAME          = 20; //l'utilisateur propose un screen d'un jeu
	protected static $pts_PROP_BA_GAME              = 20; //l'utilisaeur propose une bande annonce pour un jeu
	protected static $pts_INVITE_FRIEND_BETA        = 15; //l'utilisateur invite un ami à la bêta
	protected static $pts_MCCRT_CREATED             = 15; // l'utilisateur poste une micro-critique
	protected static $pts_FLAG_BUG                  = 10; //l'utilisateur signale un bug
	protected static $pts_LIST_CREATED              = 10; //l'utilisateur crée un liste
	protected static $pts_PROP_IDEA                 = 5; //l'utilisateur propose une amélioration/idée pour le site
	protected static $pts_POS_CRT_RECEIVED          = 5; //l'utilisateur reçoit une appréciation positive sur sa critique
	protected static $pts_POST_COMMENT_CRT          = 4; //l'utilisateur poste une commentaire sur une critique
	protected static $pts_POST_COMMENT_LIST         = 3; //l'utilisateur poste une commentaire sur une critique
	protected static $pts_POS_LIST_RECEIVED         = 3; //l'utilisateur reçoit une appréciation positive sur sa liste 
	protected static $pts_POST_COMMENT_GAME         = 3; //l'utilisateur poste une commentaire sur un jeu non-sorti
	protected static $pts_POS_MCCRT_RECEIVED        = 2; //l'utilisateur reçoit une appréciation positive sur sa micro-critique
	protected static $pts_POS_CRT                   = 2; //l'utilisateur donne une appréciation positive sur une critique
	protected static $pts_POS_LIST                  = 1; //l'utilisateur donne une appréciation positive sur une liste
	protected static $pts_POS_GAME                  = 1; //l'utilisateur donne une appréciation positive sur un jeu
	protected static $pts_POS_MCCRT                 = 1; //l'utilisateur donne une appréciation positive sur une micro-critique
	protected static $pts_HAVE_GAME                 = 1; //l'utilisateur possède un jeu
	protected static $pts_WANT_GAME                 = 1; //l'utilisateur veut un jeu
	protected static $pts_NEG_LIST                  = 0; //l'utilisateur donne une appréciation négative sur une liste
	protected static $pts_CONNECT                   = 0; //l'utilisateur se connecte
	protected static $pts_NEG_CRT                   = 0; //l'utilisateur donne une appréciation négative sur une critique
	protected static $pts_ADD_GAME_LIST             = 0; //l'utilisateur ajoute une jeu à une liste
	protected static $pts_NEG_GAME                  = 0; //l'utilisateur donne une appréciation négative sur un jeu
	protected static $pts_MODIF_PROFIL              = 0; //l'utilisateur modifie son profil
	protected static $pts_NEG_MCCRT                 = -1; //l'utilisateur donne une appréciation négative sur une micro-critique
	protected static $pts_NEG_LIST_RECEIVED         = -2; //l'utilisateur reçoit une appréciation négative sur une liste
	protected static $pts_NEG_CRT_RECEIVED          = -2; //l'utilisateur reçoit une appréciation négative sur une critique
	protected static $pts_NEG_MCCRT_RECEIVED        = -5; //l'utilisateur reçoit une appréciation négative sur une micro-critique
	protected static $pts_MODIF_GAME_REFUSED        = -25; //la proposition de mofications d'un jeu est refusée
	protected static $pts_PROP_SCREEN_GAME_REFUSED  = -25; //la proposition de screen d'un jeu est refusée
	protected static $pts_PROP_BA_GAME_REFUSED      = -25; //la proposition de bande anonce d'un jeu est refusée
	protected static $pts_ADD_GAME_REFUSED          = -50; //la proposition d'ajout d'un jeu est refusée

	function __construct(){
		try{
			$bdd = new PDO('mysql:host='.HOSTNAME.';dbname='.DBNAME, USER_DB, PASS_DB);
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Aide à l'erreur
			$utf8 = $bdd->query('SET NAMES \'utf8\';');
		}catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
		}
		self::$db = $bdd;
	}

	public static function addTransaction($idUser, $idGame=null, $idComment=null, $idBadge=null, $idMccrt=null, $idCrt = null, $idList = null, $pts = null, $action=null, $type = null, $idNote = null){
		/*
		Ajout d'une transaction
		*/
		$table = self::$tableTransactions;
		self::$db->query('SET FOREIGN_KEY_CHECKS = 0');
		$req = self::$db->query('INSERT INTO `'.$table.'`(`idTransaction`, `idUserTransaction`, `idJeuTransaction`, `idCommentaireTransaction`, `idBadgeTransaction`, `idMccrtTransaction`, `idCritiqueTransaction`, `idListTransaction`, `dateTransaction`, `ptsTransaction`, `actionTransaction`, `typeTransaction` , `idNoteTransaction`) VALUES ("","'.$idUser.'","'.$idGame.'","'.$idComment.'","'.$idBadge.'","'.$idMccrt.'","'.$idCrt.'","'.$idList.'",NOW(),"'.$pts.'","'.$action.'","'.$type.'" ,"'.$idNote.'")');
		return true;
	}

	static function date_to_letter($date){
		$date = date("d/m/Y", strtotime($date));
		$date = explode("/", $date);
		$jour = $date[0];
		$mois = $date[1];
		$annee = $date[2];
		switch($mois) {
			case '1': $mois   = 'Janvier'; break;
			case '2': $mois  = 'Février'; break;
			case '3': $mois     = 'Mars'; break;
			case '4': $mois     = 'Avril'; break;
			case '5': $mois       = 'Mai'; break;
			case '6': $mois      = 'Juin'; break;
			case '7': $mois      = 'Juillet'; break;
			case '8': $mois    = 'Août'; break;
			case '9': $mois = 'Septembre'; break;
			case '10': $mois   = 'Octobre'; break;
			case '11': $mois  = 'Novembre'; break;
			case '12': $mois  = 'Decembre'; break;
			default: $mois          =''; break;
		}

		return $jour." ".$mois." ".$annee;
	}

	static function datetime_to_letter($datetime){
		$datetime = explode(" ", $datetime);
		$date = DB::date_to_letter($datetime[0]);

		return $date." ".$datetime[1];
	}
}
$DB = new DB();