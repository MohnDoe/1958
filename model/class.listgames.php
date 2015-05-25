<?php

Class ListGames{

	static $limit_nbCommentairesToGet = 5;
	static $limit_nbUsersWhoLikeToGet = 30;

	public $id;
	public $Author;
	public $name;
	public $description;
	public $dateCreation;
	public $dateLastModif;
	public $views;
	public $popularite;
	public $lien;


	public $arrayGames = Array(); // les jeux de la list
	public $nbGames; // nombres de jeu présent dans la liste
	public $arrayAppreciations = Array(); // appréciations de la list
	// "positives", "negatives", "total"

	public function __construct($idList=null){
		if(!is_null($idList)){
			$this->id = $idList;
			$this->init();
		}
	}

	public function listgames_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableListes.' WHERE idList = '.$this->id.' LIMIT 1');
		return $req->fetch();
	}

	public function init(){
		// $_BENCHMARK = benchmark_test();
		if($data = $this->listgames_exists()){
			$this->Author        = new User($data['idUserList']);
			$this->name          = $data['nomList'];
			$this->description   = $data['descriptionList'];
			$this->dateCreation  = $data['dateCreaList'];
			$this->dateLastModif = $data['dateLastModList'];
			$this->views         = $data['viewsList'];
			$this->popularite    = $data['populariteList'];
			$this->lien          = './'.PART_ONE_LINK_LIST.'/'.url_slug($this->name).'-'.$this->id;
			
			$this->nbGames            = $this->getNbGames();
			$this->arrayAppreciations = $this->getAppreciations();
		}
	}

	public function get_games($limit = 10){
		$req = DB::$db->query('SELECT '.DB::$tableGames.'.idJeu AS idJeu FROM '.DB::$tableGamesInList.' LEFT JOIN '.DB::$tableGames.' ON '.DB::$tableGamesInList.'.idJeuInList = '.DB::$tableGames.'.idJeu WHERE idList = "'.$this->id.'" ORDER BY dateAjoutInList DESC LIMIT '.$limit);
		$arrayGames = Array();
		while($data = $req->fetch()){
			$arrayGames[] = new Game($data['idJeu']);
		}
		return $arrayGames;
	}

	public function init_games($limit = 10){
		$this->arrayGames = $this->get_games($limit);
	}

	public function getNbGames(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableGamesInList.' LEFT JOIN '.DB::$tableGames.' ON '.DB::$tableGamesInList.'.idJeuInList = '.DB::$tableGames.'.idJeu WHERE idList = "'.$this->id.'"');
		$data = $req->fetch();
		return $data['result'];
	}

	public function getAppreciations(){
		/* (array) retourne un table contenant le nombres d'appréciations positives, négatives et le total */
		$reqP = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' WHERE idListAppr = "'.$this->id.'" AND typeAppr = "P" LIMIT 1');
		$dataP = $reqP->fetch();

		$reqN = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' WHERE idListAppr = "'.$this->id.'" AND typeAppr = "N" LIMIT 1');
		$dataN = $reqN->fetch();

		$total = $dataP['result']+$dataN['result'];
		$arrayAppreciations = Array(
			"positives" => $dataP['result'],
			"negatives" => $dataN['result'],
			"total"     => $total
		);
		return $arrayAppreciations;
	}

	public function convert_games_to_array(){
		$d = Array();
		for ($i=0; $i < count($this->arrayGames) ; $i++) { 
			$d[] = $this->arrayGames[$i]->id;
		}
		return $d;
	}

	public function getSimilaresLists(){
		$this->init_games();

		$limit = 5;
		$arrayAllLists = self::getAllListsID();

		$arrayResult = Array();

		for ($i=0; $i < count($arrayAllLists) ; $i++) { 
			if($arrayAllLists[$i] != $this->id){
				$lev = 0;
				$List = new ListGames($arrayAllLists[$i]);
				$List->init_games();
				$lev = self::levenshtein_lists($this, $List);
				$arrayResult[$List->id] = $lev;
			}
		}

		return $arrayResult;
	}

	static function getAllListsID(){
		$req = DB::$db->query('SELECT idList AS result FROM '.DB::$tableListes.' WHERE 1');
		$d = Array();
		while($data = $req->fetch()){
			$d[] = $data['result'];
		}
		return $d;
	}
	static function levenshtein_lists($List1,$List2) {

		$s = $List1->convert_games_to_array();
		$t = $List2->convert_games_to_array();


	    $m = count($s);
	    $n = count($t);
	 
	    for($i=0;$i<=$m;$i++) $d[$i][0] = $i;
	    for($j=0;$j<=$n;$j++) $d[0][$j] = $j;
	 
	    for($i=1;$i<=$m;$i++) {
	        for($j=1;$j<=$n;$j++) {
	            $c = ($s[$i-1] == $t[$j-1])?0:1;
	            $d[$i][$j] = min($d[$i-1][$j]+1,$d[$i][$j-1]+1,$d[$i-1][$j-1]+$c);
	        }
	    }
	 
	    return $d[$m][$n];
	}
	public function get_commentaires($page = 1){
		$limit = self::$limit_nbCommentairesToGet;

		$premiereEntree=($page-1)*$limit;
		$req = DB::$db->query('SELECT idCommentaire AS idComment FROM '.DB::$tableCommentaires.' WHERE idListeCommentaire = "'.$this->id.'" ORDER BY dateCommentaire DESC LIMIT '.$premiereEntree.', '.$limit);
		$d = array();
		while($data = $req->fetch()){
			$d[] = new Comment($data['idComment']);
		}
		return array_reverse($d);
	}
	public function get_nb_commentaires(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableCommentaires.' WHERE idListeCommentaire = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

	public function add_popularite($value = 1){
		/* incrémente la popularité du jeu par la valeur indiqué (par default : 1) */
		DB::$db->query('UPDATE `'.DB::$tableListes.'` SET `populariteList`=populariteList+'.$value.' WHERE idList = '.$this->id);
		$this->popularite = $this->popularite+$value;
	}

	public function add_views($value = 1){
		/* incrémente le nombre de vue du jeu par la valeur indiqué (par défault : 1) */
		$req = DB::$db->query('UPDATE `'.DB::$tableListes.'` SET `viewsList`=viewsList+'.$value.' WHERE idList = '.$this->id);
		$this->views = $this->views+$value;

	}

	public function get_users_who_like(){
		$req = DB::$db->query('SELECT idUserAppr AS idUser FROM '.DB::$tableAppreciations." WHERE idListAppr = ".$this->id.' AND typeAppr = "P" LIMIT '.self::$limit_nbUsersWhoLikeToGet);
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new User($data['idUser']);
		}
		return $d;
	}
	/* STATIC */
	static function get_id_author($idList){
		$req = DB::$db->query('SELECT idUserList AS result FROM '.DB::$tableListes.' WHERE idList = "'.$idList.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}
}

?>