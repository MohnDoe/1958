<?php

Class Critique{
	
	static $limit_nbCommentairesToGet = 5;
	static $limit_nbUsersWhoLikeToGet = 30;
	public $id;
	public $Game;
	public $Author;
	public $title;

	public $arrayNotes = Array();

	public $content;

	public $date;
	public $views;
	public $popularite;
	public $nbWords;

	public $nbPyongs;

	public $lien;

	public function __construct($idCritique=null, $initGame = true){
		if(!is_null($idCritique)){
			$this->id = $idCritique;
			$this->init($initGame);
		}
	}

	public function critique_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableCritiques. ' WHERE idCritique = ' .$this->id. ' LIMIT 1');
		return $req->fetch();
	}

	public function init(){
		if($data = $this->critique_exists()){
			$this->Game   = new Game($data['idJeuCritique']);
			$this->Author = new User($data['idUserCritique']);
			
			$this->title  = $data['titreCritique'];
			if($this->title == "" OR is_null($this->title)){
				$this->title = "Critique de ".$this->Author->displayName;
			}


			$this->arrayNotes = Array(
				"graphism" => $data['noteGraphismCritique'],
				"gameplay" => $data['noteGameplayCritique'],
				"bo"       => $data['noteBOCritique'],
				"story"    => $data['noteGraphismCritique'],
				"lifetime" => $data['noteLifetimeCritique'],
				"moyenne"  => $data['noteMoyenneCritique']
			);
			
			$this->content    = $data['contenueCritique'];
			$this->date       = $data['dateCritique'];
			$this->views      = $data['viewsCritique'];
			$this->popularite = $data['populariteCritique'];
			$this->nbWords    = $data['nbWordsCritique'];
			
			$this->lien       = "./".PART_ONE_LINK_CRT."/".url_slug($this->Game->name)."-".$this->Game->id."/".$this->id;
		}
	}
	public function get_nbPyongs(){
		return Pyong::get_nb_pyongs('review', $this->id);
	}
	public function get_commentaires($page = 1){
		$limit = self::$limit_nbCommentairesToGet;
		$premiereEntree=($page-1)*$limit;
		$req = DB::$db->query('SELECT `idCommentaire` AS idComment FROM ' .DB::$tableCommentaires.' WHERE idCritiqueCommentaire = "'.$this->id.'" ORDER BY dateCommentaire DESC LIMIT '.$premiereEntree.', '.$limit);
		$d = array();
		while($data = $req->fetch()){
			$d[] = new Comment($data['idComment']);
		}
		return array_reverse($d);
	}
	public function get_nb_commentaires(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableCommentaires.' WHERE idCritiqueCommentaire = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

	public function init_or_get($nameVariable, $params = null){
		if($nameVariable == "arrayUrlCovers"){
			if(!isset($this->{$nameVariable}[$params[0]]) OR $this->{$nameVariable}[$params[0]] == "" OR empty($this->{$nameVariable}[$params[0]])){
				$this->{$nameVariable}[$params[0]] = $this->getUrlCover_by_size($params[0]);
			}
		}else{
			if(!isset($this->{$nameVariable})){
				if(is_null($params)){
					$this->{$nameVariable} = call_user_func(array( $this, "get_".$nameVariable ));
				}else{
					$this->{$nameVariable} = call_user_func_array(array( $this, "get_".$nameVariable ), $params);
				}
			}
		}
		return $this->{$nameVariable};
	}

	public function add_popularite($value = 1){
		/* incrémente la popularité du jeu par la valeur indiqué (par default : 1) */
		DB::$db->query('UPDATE `'.DB::$tableCritiques.'` SET `populariteCritique`=populariteCritique+'.$value.' WHERE idCritique = '.$this->id);
		$this->popularite = $this->popularite+$value;
	}

	public function add_views($value = 1){
		/* incrémente le nombre de vue du jeu par la valeur indiqué (par défault : 1) */
		$req = DB::$db->query('UPDATE `'.DB::$tableCritiques.'` SET `viewsCritique`=viewsCritique+'.$value.' WHERE idCritique = '.$this->id);
		$this->views = $this->views+$value;

	}
	public function get_users_who_like(){
		$req = DB::$db->query('SELECT idUserAppr AS idUser FROM '.DB::$tableAppreciations." WHERE idCritiqueAppr = ".$this->id.' AND typeAppr = "P" LIMIT '.self::$limit_nbUsersWhoLikeToGet);
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new User($data['idUser']);
		}
		return $d;
	}
	public function get_nb_appreciations_pos(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations." WHERE idCritiqueAppr = ".$this->id.' AND typeAppr = "P" LIMIT 1');
		if($data = $req->fetch()){
			return $data['result'];
		}
		return;
	}
	public function get_nb_appreciations_neg(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations." WHERE idCritiqueAppr = ".$this->id.' AND typeAppr = "N" LIMIT 1');
		if($data = $req->fetch()){
			return $data['result'];
		}
		return;
	}
    /*STATIC*/

	static function get_id_author($idCritique){
		$req = DB::$db->query('SELECT idUserCritique as result FROM '.DB::$tableCritiques.' WHERE idCritique = "'.$idCritique.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

}

?>