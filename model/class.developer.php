<?php

Class Developer{

	public $id;
	public $name;
	public $description;
	public $dateCreation;
	public $websiteURL;
	public $views;
	public $popularite;

	public function __construct($idDev = null){
		if(!is_null($idDev)){
			$this->id = $idDev;
			$this->init();
		}
	}

	public function developer_exists(){
		/* est ce que le jeu existe ? */
		$req = DB::$db->query('SELECT * FROM '.DB::$tableDevelopers.' WHERE idDev = '.$this->id.' LIMIT 1');
		return $req->fetch();
	}

	public function init(){
		if($data = $this->developer_exists()){
			$this->name         = $data['nomDev'];
			$this->description  = $data['descrDev'];
			$this->dateCreation = $data['dateCreaDev'];
			$this->websiteURL   = $data['urlSiteDev'];
			$this->views        = $data['viewDev'];
			$this->popularite   = $data['populariteDev'];
		}
	}

	static function search_like($s){
		$req = DB::$db->prepare('SELECT idDev AS ID FROM '.DB::$tableDevelopers.' WHERE nomDev LIKE :search LIMIT 5');
		$req->execute(Array(':search' => '%'.$s.'%'));
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new Developer($data['ID']);
		}
		return $d;
	}
}

?>