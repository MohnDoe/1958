<?php
Class Game{

	protected static $nbLastsMicrosCritiquesToGet = 5;
	protected static $nbLastsCritiquesToGet = 10;
	protected static $nbListsInToGet = 5;
	static $limit_nbCritiquesToGet = 10;

	public $id;
	public $name;
	public $slug;
	public $synopsis;
	public $releaseDate;
	public $releaseDate_letter;
	public $classification;
	public $urlPochette;
	public $urlBandeAnnonce;
	public $views;
	public $popularite;
	public $dateBeginFeatured;
	public $dateEndFeatured;

	public $Genre; // TODO : BDD & CLASS
	public $idDeveloper;
	public $Developer; 
	public $Platform; // TODO : CLASS
	public $idEditor;
	public $Editor; 

	/* NOT IN DB */
	public $arrayAvgNotes; // notes moyennes dans les différentes branches
	// "gameplay", "graphism", "bo", "story", "lifetime", "moyenne"
	public $nbUsersWant; // nombre d'utilisateurs voulant ce jeu
	public $nbUsersPlay; // nombre d'utilisateurs jouant à ce jeu
	public $arrayAppreciations; // contient le nombre d'appréciation positives, négatives et le total;
	// "positives", "negatives", "total"
	public $nbCritiques; // nombre de critiques sur ce jeu
	public $nbNotes; // nombre de notes sur ce jeu
	public $nbMicrosCritiques; // nombre de micros-critiques
	public $arrayLastsMicrosCritiques; // les X dernières micros-critiques de ce jeu
	// ne contient que des objets de class MicroCritique
	public $arrayUrlCovers; // array des urls vers les images du serveur du jeu
	// "original", "big", "normal", "small", "xsmall"
	public $arrayIsInLists; // lists dans lesquelles le jeu est présent
	public $lien; // lien pour les hrefs
	public $arrayCritiques; // lists des critiques du jeu
	public $nbPyongs;

	/* END ATTRIBUTS */

	public function __construct($idGame = null){
		if(!is_null($idGame)){
			$this->id = $idGame;
			$this->init();
		}
	}

	public function game_exists(){
		/* est ce que le jeu existe ? */
		$req = DB::$db->query("SELECT * FROM ".DB::$tableGames." WHERE idJeu = $this->id LIMIT 1");
		return $req->fetch();
	}

	public function init(){
		/* initialisation des attributs du jeu */
		if($data = $this->game_exists()){
			$this->name               = $data['nomJeu'];
			$this->slug               = $data['slugJeu'];

			if(is_null($this->slug) OR $this->slug == ""){
				$this->slug = url_slug($this->name);
				$this->setSlug($this->slug);
			}

			$this->synopsis           = $data['synopsisJeu'];
			$this->releaseDate        = $data['releaseDateJeu'];
			$this->releaseDate_letter = DB::date_to_letter($this->releaseDate);
			$this->classification     = $data['classificationJeu'];
			$this->urlPochette        = $data['urlPochetteJeu'];
			$this->urlBandeAnnonce    = $data['urlBandeAnnonceJeu'];
			$this->views              = $data['viewsJeu'];
			$this->popularite         = $data['populariteJeu'];
			$this->dateBeginFeatured  = $data['dateBeginFeaturedJeu'];
			$this->dateEndFeatured    = $data['dateEndFeaturedJeu'];
			
			/* TO DO !!!*/
			$this->idDeveloper = $data['idDevJeu'];
			// $this->Platform  = new Platform($data['idPlatform']);
			$this->idEditor    = $data['idEditJeu'];
			
			/* OTHER ATTR */
			// $this->nbCritiques        = $this->get_nbCritiques();
			// $this->nbNotes            = $this->get_nbNotes();
			// $this->arrayAvgNotes      = $this->get_arrayAvgNotes();
			// $this->nbUsersWant        = $this->get_nbUsersWant();
			// $this->nbUsersPlay        = $this->get_nbUsersPlay();
			// $this->arrayAppreciations = $this->get_arrayAppreciations();
			// $this->nbMicrosCritiques  = $this->get_nbMicrosCritiques();
			// $this->arrayUrlCovers     = $this->get_arrayUrlCovers();
			// $this->nbPyongs           = $this->get_nbPyongs();

			$this->lien                      = './'.PART_ONE_LINK_GAME.'/'.$this->slug.'-'.$this->id;
		}
	}
	public function init_arrayLastsMicrosCritiques(){
		$this->arrayLastsMicrosCritiques = $this->get_arrayLastsMicrosCritiques();
	}

	public function get_nbPyongs(){
		return Pyong::get_nb_pyongs('game', $this->id);
	}
	public function get_Developer(){
		return new Developer($this->idDeveloper);
	}
	public function get_Editor(){
		return new Editor($this->idEditor);
	}

	public function get_arrayAvgNotes(){
		$arrayNotesCritiques = $this->getAvgNotesCritiques();
		$arrayNotesSingleNote = $this->getAvgNotesSingleNote();

		$avgNotes = Array();

		if($this->init_or_get('nbNotes') <= 0 AND $this->init_or_get('nbCritiques') > 0){
			$avgNotes = $arrayNotesCritiques;
		}else if($this->init_or_get('nbCritiques') <= 0 AND $this->init_or_get('nbNotes') >0){
			$avgNotes = $arrayNotesSingleNote;
		}else{
			if($this->init_or_get('nbCritiques') > 0 AND $this->init_or_get('nbNotes') > 0){
				$avgNotes['gameplay'] = ($arrayNotesCritiques['gameplay'] + $arrayNotesSingleNote['gameplay'])/2;
				$avgNotes['graphism'] = ($arrayNotesCritiques['graphism'] + $arrayNotesSingleNote['graphism'])/2;
				$avgNotes['bo']       = ($arrayNotesCritiques['bo'] + $arrayNotesSingleNote['bo'])/2;
				$avgNotes['story']    = ($arrayNotesCritiques['story'] + $arrayNotesSingleNote['story'])/2;
				$avgNotes['lifetime'] = ($arrayNotesCritiques['lifetime'] + $arrayNotesSingleNote['lifetime'])/2;
				$avgNotes['moyenne']  = ($arrayNotesCritiques['moyenne'] + $arrayNotesSingleNote['moyenne'])/2;
			}else{
				$avgNotes = $arrayNotesCritiques;
			}
		}

		return $avgNotes;
	}
	public function getAvgNotesCritiques(){
		/* retourne les notes moyennes du jeu (array) */
		$req = DB::$db->query('SELECT AVG(noteGameplayCritique) AS avgGameplay,
										AVG(noteGraphismCritique) AS avgGraphism,
										AVG(noteBOCritique) AS avgBO,
										AVG(noteStoryCritique) AS avgStory,
										AVG(noteLifetimeCritique) AS avgLifetime,
										AVG(noteMoyenneCritique) AS avgMoyenne
										FROM '.DB::$tableCritiques.' WHERE idJeuCritique = '.$this->id.' LIMIT 1');
		$data = $req->fetch();

		$avgNotesCritiques = Array();
		$avgNotesCritiques['gameplay'] = $data['avgGameplay'];
		$avgNotesCritiques['graphism'] = $data['avgGraphism'];
		$avgNotesCritiques['bo']       = $data['avgBO'];
		$avgNotesCritiques['story']    = $data['avgStory'];
		$avgNotesCritiques['lifetime'] = $data['avgLifetime'];
		$avgNotesCritiques['moyenne']  = $data['avgMoyenne'];

		return $avgNotesCritiques;
	}
	public function getAvgNotesSingleNote(){
		$req = DB::$db->query('SELECT AVG(gameplayNote) AS avgGameplay, 
										AVG(graphismNote) AS avgGraphism, 
										AVG(boNote) AS avgBO, 
										AVG(storyNote) AS avgStory, 
										AVG(lifetimeNote) AS avgLifetime, 
										AVG(totalNote) AS avgMoyenne 
										FROM '.DB::$tableNotesGame.' WHERE idGameNote = '.$this->id.' LIMIT 1');
		$data = $req->fetch();

		$avgNotesSingleNote = Array();
		$avgNotesSingleNote['gameplay'] = $data['avgGameplay']*10;
		$avgNotesSingleNote['graphism'] = $data['avgGraphism']*10;
		$avgNotesSingleNote['bo']       = $data['avgBO']*10;
		$avgNotesSingleNote['story']    = $data['avgStory']*10;
		$avgNotesSingleNote['lifetime'] = $data['avgLifetime']*10;
		$avgNotesSingleNote['moyenne']  = $data['avgMoyenne']*10;

		return $avgNotesSingleNote;
	}

	public function get_nbUsersWant(){
		/* retourne le nombre d'utilisateur voulant le jeu */
		$req = DB::$db->query('SELECT COUNT(*) AS nb FROM '.DB::$tableWantPlay.' WHERE idJeuWantPlay = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();

		return $data['nb'];
	}

	public function get_nbUsersPlay(){
		/* retourne le nombre d'utilisateur jouant le jeu */
		$req = DB::$db->query('SELECT COUNT(*) AS nb FROM '.DB::$tableHaveGame.' WHERE idJeuHaveGame = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();

		return $data['nb'];
	}

	public function get_arrayAppreciations(){
		/* (array) retourne un table contenant le nombres d'appréciations positives, négatives et le total */
		$reqP = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' WHERE idJeuAppr = "'.$this->id.'" AND typeAppr = "P" LIMIT 1');
		$dataP = $reqP->fetch();

		$reqN = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableAppreciations.' WHERE idJeuAppr = "'.$this->id.'" AND typeAppr = "N" LIMIT 1');
		$dataN = $reqN->fetch();

		$total = $dataP['result']+$dataN['result'];
		$arrayAppreciations = Array(
			"positives" => $dataP['result'],
			"negatives" => $dataN['result'],
			"total"     => $total
		);
		return $arrayAppreciations;
	}
	public function get_arrayCritiques($page = 1, $sort = "popularite"){
		switch ($sort) {
			case 'popularite':
				$orderReq = "ORDER BY populariteCritique DESC";
				break;
			case 'positive':
				$orderReq = "ORDER BY noteMoyenneCritique DESC";
				break;
			case 'negative':
				$orderReq = "ORDER BY noteMoyenneCritique ASC";
				break;
			
			default:
				$orderReq = "";
				break;
		}
		$limit = self::$limit_nbCritiquesToGet;
		$premiereEntree=($page-1)*$limit;
		$req = DB::$db->query('SELECT idCritique FROM '.DB::$tableCritiques.' WHERE idJeuCritique = '.$this->id.' '.$orderReq.' LIMIT '.$premiereEntree.', '.$limit);
		$d = Array();
		while($data = $req->fetch()){
			$d[] = new Critique($data['idCritique']);
		}

		return $d;
	}
	public function get_nbCritiques(){
		/* retourne le nombre de critiques sur ce jeu */
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM `'.DB::$tableCritiques.'` WHERE idJeuCritique = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();

		return $data['result'];
	}

	public function getNbMicrosCritques(){
		/* retourne le nombre de micros-critiques sur ce jeu */
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM `'.DB::$tableMicrosCritiques.'` WHERE idJeuMccrt = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();

		return $data['result'];
	}

	public function get_arrayLastsMicrosCritiques(){
		/* RETOURNE les X dernières MicrosCritiques du jeu sous forme d'ARRAY de MicroCritique */
		$req = DB::$db->query('SELECT idMccrt AS result FROM '.DB::$tableMicrosCritiques.' WHERE idJeuMccrt = '.$this->id.' ORDER BY dateMccrt DESC LIMIT '.self::$nbLastsMicrosCritiquesToGet);
		$arrayLastsMicrosCritiques = Array();
		while($data = $req->fetch()){
			$arrayLastsMicrosCritiques[] = new MicroCritique($data['result'], false);
		}
		return $arrayLastsMicrosCritiques;
	}

	public function add_popularite($value = 1){
		/* incrémente la popularité du jeu par la valeur indiqué (par default : 1) */
		DB::$db->query('UPDATE `'.DB::$tableGames.'` SET `populariteJeu`=populariteJeu+'.$value.' WHERE idJeu = '.$this->id);
		$this->popularite = $this->popularite+$value;
	}

	public function add_views($value = 1){
		/* incrémente le nombre de vue du jeu par la valeur indiqué (par défault : 1) */
		$req = DB::$db->query('UPDATE `'.DB::$tableGames.'` SET `viewsJeu`=viewsJeu+'.$value.' WHERE idJeu = '.$this->id);
		$this->views = $this->views+$value;

	}

	public function get_arrayUrlCovers(){
		/* retourne les url vers les fichiers images des covers du jeu */

		$arrayUrlCovers = Array();
		$arraySizesCovers = Array("original", "big", "normal", "small", "xsmall");

		if(!is_null($this->urlPochette) && !empty($this->urlPochette)){
			$versionCover = $this->urlPochette;
		}else{
			$versionCover = md5($this->id);
		}

		for ($i=0; $i < count($arraySizesCovers) ; $i++) { 
			$sizeCover = $arraySizesCovers;

			$urlCoverGame = $sizeCover[$i]."/".$this->id."_".$versionCover."_original.jpg";
			$file = PATH_GAMES_COVERS_FOLDER.'/'.$urlCoverGame;
			$fileVerif =file_exists($file);
			// $file_headers = @get_headers($file);
			// if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
			if(!$fileVerif){
				$urlCoverGame = PREFIX_URL.FOLDER_GAMES_COVERS."/404_nocover_".$sizeCover[$i].".jpg";
				$file = PATH_GAMES_COVERS_FOLDER."/404_nocover_".$sizeCover[$i].".jpg";
			}else{
				$urlCoverGame = PREFIX_URL.FOLDER_GAMES_COVERS.'/'.$urlCoverGame;
				// $urlCoverGame = PATH_GAMES_COVERS_FOLDER.'/'.$urlCoverGame;
			}
			list($widthCover, $heightCover) = getimagesize($file);
			$arrayUrlCovers[$sizeCover[$i]] = array($urlCoverGame, $widthCover, $heightCover);
		}

		return $arrayUrlCovers;
	}

	public function get_nbNotes(){
		$req = DB::$db->query('SELECT COUNT(*) AS result FROM '.DB::$tableNotesGame.' WHERE idGameNote = "'.$this->id.'" LIMIT 1');
		$data = $req->fetch();
		return $data['result'];

	}
	public function getUrlCover_by_size($sizeCover="normal"){
		/* retourne les url vers les fichiers images des covers du jeu */

		if(!is_null($this->urlPochette) && !empty($this->urlPochette)){
			$versionCover = $this->urlPochette;
		}else{
			$versionCover = md5($this->id);
		}


		$urlCoverGame = $sizeCover."/".$this->id."_".$versionCover."_original.jpg";
		$file = PATH_GAMES_COVERS_FOLDER.'/'.$urlCoverGame;
		$fileVerif =file_exists($file);
		$file_headers = @get_headers($file);
		// if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
		if(!$fileVerif){
			$urlCoverGame = PREFIX_URL.FOLDER_GAMES_COVERS."/404_nocover_".$sizeCover.".jpg";
			$file = PATH_GAMES_COVERS_FOLDER."/404_nocover_".$sizeCover.".jpg";
		}else{
			$urlCoverGame = PREFIX_URL.FOLDER_GAMES_COVERS.'/'.$urlCoverGame;
			// $urlCoverGame = PATH_GAMES_COVERS_FOLDER.'/'.$urlCoverGame;
		}
		list($widthCover, $heightCover) = getimagesize($file);
		return array($urlCoverGame, $widthCover, $heightCover);
	}

	public function get_arrayIsInLists(){
		$req = DB::$db->query('SELECT idList AS result FROM '.DB::$tableGamesInList.' WHERE idJeuInList = "'.$this->id.'" LIMIT '.self::$nbListsInToGet);
		$arrayIsInLists = Array();
		while($data = $req->fetch()){
			$List = new ListGames($data['result']);
			$List->init_games();
			$arrayIsInLists[] = $List;
		}
		return $arrayIsInLists;
	}

	public function init_arrayIsInLists(){
		$this->arrayIsInLists = $this->get_arrayIsInLists();
	}

	public function setSlug($slug){
		$req = DB::$db->prepare('UPDATE '.DB::$tableGames.' SET slugJeu=:slug WHERE idJeu = :idGame');
		$req->execute(array(':slug'=> $slug, ':idGame'=>$this->id));
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

	/* STATICS */
	static function cronPopulariteGames(){
		/*~#
		Cette fonction divise la popularité de tout les jeux par un ratio de tel sorte qu'une popularité de 20 ne vallent que la moitié de lendemain
		Cette fonction est à excécuter toutes les 10 minutes.

		Il y a 1440 minutes dans une journée, donc 144 dizaines de minutes.
		*/
		//Ratio à calculer
		$ratio = RATIO_POP_MULTIPLI;

		$req = DB::$db->query('UPDATE '.DB::$tableGames.' SET populariteJeu=(populariteJeu*'.$ratio.') WHERE 1');
	}
}

?>