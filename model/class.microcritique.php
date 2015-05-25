<?php

Class MicroCritique{
	static $limit_nbCommentairesToGet = 5;
	static $limit_nbUsersWhoLikeToGet = 30;

	public $id;
	public $Game;
	public $Author;
	public $content;
	public $date;
	public $nbWords;
	public $lien;

	public $popularite;
	public $views;

	public $nbPyongs;

	public function __construct($idMccrt=null, $initGame = true, $initAuthor = true){
		if(!is_null($idMccrt)){
			$this->id = $idMccrt;
			$this->init($initGame, $initAuthor);
		}
	}

	public function micro_critique_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableMicrosCritiques.' WHERE idMccrt = '.$this->id.' LIMIT 1');
		return $req->fetch();
	}

	public function init($initGame = true, $initAuthor = true){
		if ($data = $this->micro_critique_exists()){
			if($initGame ||true){
				$this->Game = new Game($data['idJeuMccrt']);
			}
			if($initAuthor){
				$this->Author = new User($data['idUserMccrt']);
			}
			$this->content = $data['contenueMccrt'];
			$this->date = $data['dateMccrt'];
			$this->nbWords = $data['nbWordsMccrt'];
			$this->popularite = $data['populariteMccrt'];
			$this->views = $data['viewsMccrt'];
			$this->lien = './'.PART_ONE_LINK_MCCRT.'/'.$this->Game->slug.'-'.$this->Game->id.'/'.$this->id;
		}
	}

	public function get_nbPyongs(){
		return Pyong::get_nb_pyongs('microreview', $this->id);
	}

	public function get_commentaires($page = 1){
		$limit = self::$limit_nbCommentairesToGet;
		$premiereEntree=($page-1)*$limit;
		$req = DB::$db->query('SELECT idCommentaire AS idComment FROM '.DB::$tableCommentaires.' WHERE idMicroCritiqueCommentaire = "'.$this->id.'" ORDER BY dateCommentaire DESC LIMIT '.$premiereEntree.', '.$limit);
		$d = array();
		while($data = $req->fetch()){
			$d[] = new Comment($data['idComment']);
		}
		return array_reverse($d);
	}

	public function get_nb_commentaires(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableCommentaires.' WHERE idMicroCritiqueCommentaire = "'.$this->id.'" LIMIT 1');
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
		DB::$db->query('UPDATE `'.DB::$tableMicrosCritiques.'` SET `populariteMccrt`=populariteMccrt+'.$value.' WHERE idMccrt = '.$this->id);
		$this->popularite = $this->popularite+$value;
	}

	public function add_views($value = 1){
		/* incrémente le nombre de vue du jeu par la valeur indiqué (par défault : 1) */
		$req = DB::$db->query('UPDATE `'.DB::$tableMicrosCritiques.'` SET `viewsMccrt`=viewsMccrt+'.$value.' WHERE idMccrt = '.$this->id);
		$this->views = $this->views+$value;
	}
	public function get_users_who_like(){
		$req = DB::$db->query('SELECT idUserAppr AS idUser FROM '.DB::$tableAppreciations." WHERE idMccrtAppr = ".$this->id.' AND typeAppr = "P" LIMIT '.self::$limit_nbUsersWhoLikeToGet);
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new User($data['idUser']);
		}
		return $d;
	}
	public function get_nb_appreciations_pos(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations." WHERE idMccrtAppr = ".$this->id.' AND typeAppr = "P" LIMIT 1');
		if($data = $req->fetch()){
			return $data['result'];
		}
		return;
	}
	public function get_nb_appreciations_neg(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations." WHERE idMccrtAppr = ".$this->id.' AND typeAppr = "N" LIMIT 1');
		if($data = $req->fetch()){
			return $data['result'];
		}
		return;
	}




	/*STATIC*/

	static function get_id_author($idMccrt){
		$req = DB::$db->query('SELECT idUserMccrt AS result FROM '.DB::$tableMicrosCritiques.' WHERE idMccrt = "'.$idMccrt.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];
	}

}
?>