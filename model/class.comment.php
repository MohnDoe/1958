<?php
	/**
	* 
	*/
	Class Comment{
		public $id;
		
		public $idAuthor;
		public $Author;
		public $idGame;
		public $Game;
		public $idCritique;
		public $Critique;
		public $idList;
		public $List;
		public $idMicroCritiue;
		public $MicroCritiue;

		public $content;
		public $date;
		public $time_ago;

		function __construct($idComment = null, $initAuthor = true, $initGame = false, $initCritique = false, $initList = false, $initMicroCritique = false)
		{
			if(!is_null($idComment)){
				$this->id = $idComment;
				$this->init();
			}
			if($initAuthor){
				$this->init_Author();
			}
			if($initGame){
				$this->init_Game();
			}
			if($initCritique){
				$this->init_Critique();
			}
			if($initList){
				$this->init_List();
			}
			if($initMicroCritique){
				$this->init_MicroCritique();
			}

		}
		public function comment_exists(){
			$req = DB::$db->query('SELECT * FROM '.DB::$tableCommentaires.' WHERE idCommentaire = '.$this->id.' LIMIT 1');
			return $req->fetch();
		}
		public function init(){
			if($data = $this->comment_exists()){
				$this->idAuthor        = $data['idUserCommentaire'];
				$this->idGame          = $data['idGameCommentaire'];
				$this->idCritique      = $data['idCritiqueCommentaire'];
				$this->idList          = $data['idListeCommentaire'];
				$this->idMicroCritique = $data['idMicroCritiqueCommentaire'];
				$this->content         = $data['contenueCommentaire'];
				$this->date            = $data['dateCommentaire'];

				$this->time_ago = time_ago_datetime($this->date);
			}else{
				return;
			}
		}
		public function init_Author(){
			$this->Author = new User($this->idAuthor);
		}
		public function init_Game(){
			$this->Game = new Game($this->idGame);
		}
		public function init_Critique(){
			$this->Critique = new Critique($this->idCritique);
		}
		public function init_MicroCritiue(){
			$this->MicroCritiue = new MicroCritiue($this->idMicroCritiue);
		}
		public function init_List(){
			$this->List = new ListGames($this->idList);
		}


		/*STATIC*/

		static function get_all_IDs_has_comment($on_what, $id_what){
			switch ($on_what) {
				case 'crt':
					$whereReq = "idCritiqueCommentaire = ".$id_what;
					break;
				case 'mccrt':
					$whereReq = "idMicroCritiqueCommentaire = ".$id_what;
					break;
				case 'list':
					$whereReq = "idListeCommentaire = ".$id_what;
					break;
				default:
					# code...
					break;
			}
			$requete = 'SELECT idUserCommentaire AS ID FROM '.DB::$tableCommentaires.' WHERE '.$whereReq.' GROUP BY idUserCommentaire';
			$req = DB::$db->query($requete);
			$d = Array();
			while ($data = $req->fetch()) {
				$d[] = $data['ID'];
			}
			return $d;
		}
	}
?>