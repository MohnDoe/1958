<?php

Class Editor{
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

	public function editor_exists(){
		$req = DB::$db->query('SELECT * FROM '.DB::$tableEditors.' WHERE idEdit = '.$this->id.' LIMIT 1');
		return $req->fetch();
	}

	public function init(){
		if($data = $this->editor_exists()){
			$this->name         = $data['nomEdit'];
			$this->description  = $data['descrEdit'];
			$this->dateCreation = $data['dateCreaEdit'];
			$this->websiteURL   = $data['urlSiteEdit'];
			$this->views        = $data['viewEdit'];
			$this->popularite   = $data['populariteEdit'];
		}
	}

	static function search_like($s){
		$req = DB::$db->prepare('SELECT idEdit AS ID FROM '.DB::$tableEditors.' WHERE nomEdit LIKE :search LIMIT 5');
		$req->execute(Array(':search' => '%'.$s.'%'));
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new Editor($data['ID']);
		}
		return $d;
	}
}

?>