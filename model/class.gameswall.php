<?php

	Class Gameswall{

		static $nbMaxGamesToGet = 46;

		public $type;
		public $arrayGames = Array(); // liste de jeux dans le gameswall

		public function __construct($type="pop"){
			$this->type = $type;
			$this->init();
		}

		public function init(){
			$this->arrayGames = $this->getGames();
		}

		public function getGames(){
			switch ($this->type) {
				case 'random':
					$req_part_search = "ORDER BY RAND()";
					break;
				case 'soon':
					$req_part_search = "AND releaseDateJeu > curdate() ORDER BY populariteJeu";
					break;
				
				default:
					$req_part_search = "ORDER BY populariteJeu";
					break;
			}
			$req_part_more = "";
			if(!is_null($this->arrayGames) AND count($this->arrayGames)>0){
				$req_part_more = "AND (";
				for ($i=0; $i < count($this->arrayGames) ; $i++) { 
					$Game = $this->arrayGames[$i];
					$ID = $lastGame->id;
					$req_part_more .= 'idJeu != "'.$ID.'" AND ';
				}
				$req_part_more = substr($req_part_more, 0, -4);
				$req_part_more .= ")";
			}


			$req = DB::$db->query('SELECT idJeu FROM '.DB::$tableGames.' WHERE 1 '.$req_part_more.' '.$req_part_search.' DESC LIMIT '.self::$nbMaxGamesToGet);
			$arrayGames = Array();
			while($data = $req->fetch()){
				$arrayGames[] = new Game($data['idJeu']);
			}
			return $arrayGames;
		}
	}

?>