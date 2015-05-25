<?php
Class Notification{

	static $array_types_global = Array('followed', 'like', 'comment', 'badge');
	public $idUser;
	public $User;
	public $Badge;
	public $date;
	public $is_viewed;
	public $bigType; // global ou following
	public $type;
	public $lien;

	public $time_ago;
	/*
		followed : quelqu'un a suivit l'utilisateur
			array_infos :
				'UserWhoFollow'
		like : quelqu'un a aimé quelque chose qu'il a posté
			array_infos
				'UserWhoLike'
				'whatIsLiked'
				'ThingLiked'
		comment :
			array_infos
				'UserWhoComment'
				'whatIsCommented'
				'ThingCommented'
		badge
			array_infos
				''
	*/
	static $array_types_followings = Array('following_post','following_note', 'following_recommand', 'following_like', 'following_want_game', 'following_have_game', 'following_badge');
	/*
		following_post : 
			array_infos:
				'UserWhoPost'
				'whatIsPosted'
				'ThingPosted'
		following_recommand
			array_infos:
				'UserWhoRecommand'
				'whatIsRecommanded'
				'ThingRecommanded'
		following_like
			array_infos:
				'UserWhoLike'
				'whatIsLiked'
				'ThingLiked'
		following_badge
		following_want_game
		following_have_game
	*/
	public $array_infos;

	public function __construct($idNotification = null, $initUser = false){
		if(!is_null($idNotification)){
			$this->id = $idNotification;
			$this->init();
			if($initUser){
				$this->init_user();
			}
		}
	}

	public function notification_exists(){
		$req = DB::$db->query('SELECT * FROM `'.DB::$tableNotifications.'` WHERE idNotification = "'.$this->id.'" LIMIT 1');
		return $req->fetch();
	}

	public function init_user(){
		$this->User = new User($this->idUser);
	}
	public function init(){
		if($data = $this->notification_exists()){
			$this->idUser = $data['idUserNotification'];
			$this->date = $data['dateNotification'];
			$this->time_ago = time_ago_datetime($this->date);
			$this->is_viewed = ($data['viewedNotification'] == 1);
			$this->type = $data['typeNotification'];
			$infoNotification = json_decode($data['infoNotification'], true);
			switch ($this->type) {
				case 'badge':
					$this->array_infos['Badge'] = new Badge($data['idBadgeNotification'], $this->idUser);
					$this->lien = $this->array_infos['Badge']->lien;
					break;

				case 'followed':
					$this->array_infos['UserWhoFollow'] = new User($infoNotification['idUserWhoFollow']);
					$this->lien = $this->array_infos['UserWhoFollow']->lien;
					break;

				case 'like':
					$this->array_infos['UserWhoLike'] = new User($infoNotification['idUserLike']);
					$this->array_infos['whatIsLiked'] = $infoNotification['whatIsLoved'];
					switch ($this->array_infos['whatIsLiked']) {
						case 'crt':
							$this->array_infos['ThingLiked'] = new Critique($infoNotification['idThingLoved']);
							break;
						case 'mccrt':
							$this->array_infos['ThingLiked'] = new MicroCritique($infoNotification['idThingLoved']);
							break;
						case 'list':
							$this->array_infos['ThingLiked'] = new ListGames($infoNotification['idThingLoved']);
							break;
						default:
							# code...
							break;
					}
					$this->lien = $this->array_infos['ThingLiked']->lien;
					break;
				case 'comment':
					$this->array_infos['UserWhoComment'] = new User($infoNotification['idUserComment']);
					$this->array_infos['whatIsCommented'] = $infoNotification['whatIsCommented'];
					switch ($this->array_infos['whatIsCommented']) {
						case 'crt':
							$this->array_infos['ThingCommented'] = new Critique($infoNotification['idThingCommented']);
							break;
						case 'mccrt':
							$this->array_infos['ThingCommented'] = new MicroCritique($infoNotification['idThingCommented']);
							break;
						case 'list':
							$this->array_infos['ThingCommented'] = new ListGames($infoNotification['idThingCommented']);
							break;
						default:
							# code...
							break;
					}
					$this->lien = $this->array_infos['ThingCommented']->lien;
					break;


				/* FOLLOWINGS */
				case 'followings_badge':
					$this->array_infos['UserBadged'] = new User($infoNotification['idUserBadged']);
					$this->array_infos['Badge'] = new Badge($data['idBadgeNotification'], $this->idUser);
					$this->lien = $this->array_infos['Badge']->lien;
					break;

				case 'following_post':
					$this->array_infos['UserWhoPost'] = new User($infoNotification['idUserWhoPost']);
					$this->array_infos['whatIsPosted'] = $infoNotification['whatIsPosted'];
					switch ($this->array_infos['whatIsPosted']) {
						case 'crt':
							$this->array_infos['ThingPosted'] = new Critique($infoNotification['idThingPosted']);
							break;
						case 'mccrt':
							$this->array_infos['ThingPosted'] = new MicroCritique($infoNotification['idThingPosted']);
							break;
						case 'list':
							$this->array_infos['ThingPosted'] = new ListGames($infoNotification['idThingPosted']);
							break;
						default:
							# code...
							break;
					}
					$this->lien = $this->array_infos['ThingPosted']->lien;
					break;
				case 'following_want_game':
					$this->array_infos['UserWhoWant'] = new User($infoNotification['idUserWhoWant']);
					$this->array_infos['GameWanted'] = new Game($infoNotification['idGameWanted']);
					$this->lien = $this->array_infos['GameWanted']->lien;
					break;

				case 'following_have_game':
					$this->array_infos['UserWhoHave'] = new User($infoNotification['idUserWhoHave']);
					$this->array_infos['GameHaved'] = new Game($infoNotification['idGameHaved']);
					$this->lien = $this->array_infos['GameHaved']->lien;
					break;

				case "following_recommand":
					$this->array_infos['UserWhoRecommand'] = new User($infoNotification['idUserWhoRecommand']);
					$this->array_infos['whatIsRecommanded'] = $infoNotification['whatIsRecommanded'];
					switch ($this->array_infos['whatIsRecommanded']) {
						case 'crt':
							$this->array_infos['ThingRecommanded'] = new Critique($infoNotification['idThingRecommanded']);
							break;
						case 'review':
							$this->array_infos['ThingRecommanded'] = new Critique($infoNotification['idThingRecommanded']);
							break;
						case 'mccrt':
							$this->array_infos['ThingRecommanded'] = new MicroCritique($infoNotification['idThingRecommanded']);
							break;
						case 'list':
							$this->array_infos['ThingRecommanded'] = new ListGames($infoNotification['idThingRecommanded']);
							break;
						case 'game':
							$this->array_infos['ThingRecommanded'] = new Game($infoNotification['idThingRecommanded']);
							break;
						default:
							# code...
							break;
					}
					$this->lien = $this->array_infos['ThingRecommanded']->lien;
					break;

				case "following_like":
					$this->array_infos['UserWhoLike'] = new User($infoNotification['idUserWhoLike']);
					$this->array_infos['whatIsLiked'] = $infoNotification['whatIsLiked'];
					switch ($this->array_infos['whatIsLiked']) {
						case 'crt':
							$this->array_infos['ThingLiked'] = new Critique($infoNotification['idThingLiked']);
							break;
						case 'mccrt':
							$this->array_infos['ThingLiked'] = new MicroCritique($infoNotification['idThingLiked']);
							break;
						case 'list':
							$this->array_infos['ThingLiked'] = new ListGames($infoNotification['idThingLiked']);
							break;
						default:
							# code...
							break;
					}
					$this->lien = $this->array_infos['ThingLiked']->lien;
					break;
				case "following_note":
					$this->array_infos['UserWhoNote'] = new User($infoNotification['idUserWhoNote']);
					$this->array_infos['whatIsNoted'] = $infoNotification['whatIsNoted'];
					$this->array_infos['arrayNotes'] = $infoNotification['arrayNotes'];
					switch ($this->array_infos['whatIsNoted']) {
						case 'game':
							$this->array_infos['ThingNoted'] = new Game($infoNotification['idThingNoted']);
							break;
						
						default:
							# code...
							break;
					}
					break;
				default:
					$this->array_infos['text'] = $infoNotification['text'];
					$this->lien = "./notifications";

					break;
			}

			if(in_array($this->type, self::$array_types_global)){
				$this->bigType = "global";
			}else{
				$this->bigType = "followings";
			}
		}
	}
	/* STATIC */
	static function send_notification($_notification_id_user, $_notification_type, $_notification_id_badge, $_notification_array_infos, $_notification_date){
		/*
		Cette fonction crée un notification
		*/
		DB::$db->query('SET FOREIGN_KEY_CHECKS=0;');
		$req_add_notif = DB::$db->prepare('INSERT INTO `'.DB::$tableNotifications.'`
			(`idNotification`, `idUserNotification`, `idBadgeNotification`, `dateNotification`, `viewedNotification`, `typeNotification`, `infoNotification`)
			VALUES ("",:idUser,:idBadgeNotif,:dateNotif,0,:typeNotif,:infoNotif)');
		$req_add_notif->execute(array(
			':idUser'       => $_notification_id_user,
			':idBadgeNotif' => $_notification_id_badge,
			':dateNotif'    => $_notification_date,
			':typeNotif'    => $_notification_type,
			':infoNotif'    => json_encode($_notification_array_infos)
			));
		DB::$db->query('SET FOREIGN_KEY_CHECKS=1;');
		return true;
	}
	static function notify_people($type, $params){
		$_notification_type = $type;
		$_array_IDs_people = Array();
		switch ($type) {
			case 'comment':
				$id_author_comment    = $params['id_author_comment'];
				$id_comment           = $params['id_comment'];
				$what_is_commented    = $params['what_is_commented'];
				$id_what_is_commented = $params['id_what_is_commented'];

				$_notification_array_infos = Array(
					"idUserComment"    => $id_author_comment,
					"whatIsCommented"  => $what_is_commented,
					"idThingCommented" => $id_what_is_commented
					);
				
				$_notification_id_badge = -1;

				$_array_IDs_people = Comment::get_all_IDs_has_comment($what_is_commented, $id_what_is_commented);

				if($what_is_commented == "crt"){
					array_push($_array_IDs_people, Critique::get_id_author($id_what_is_commented));
				}
				if($what_is_commented == "list"){
					array_push($_array_IDs_people, ListGames::get_id_author($id_what_is_commented));
				}
				if($what_is_commented == "mccrt"){
					array_push($_array_IDs_people, MicroCritique::get_id_author($id_what_is_commented));
				}

				$_array_IDs_people = array_values(array_diff($_array_IDs_people, [$id_author_comment]));
				break;
			case "following_note":
				$_notification_id_badge = -1;
				$id_author_note = $params['id_author_note'];
				$what_is_noted = $params['what_is_noted'];
				$id_what_is_noted = $params['id_what_is_noted'];


				$_notification_array_infos = Array(
					'idUserWhoNote' => $id_author_note,
					'whatIsNoted' => $what_is_noted,
					'idThingNoted' => $id_what_is_noted,
					'arrayNotes' => $params['arrayNotes']
					);
				$UserNote = new User($id_author_note);
				$UserNote->getArrayID_followers();
				$_array_IDs_people = $UserNote->arrayID_followers;
				break;
			case "following_recommand":
				$_notification_id_badge = -1;
				$id_pyonger = $params['id_pyonger'];
				$what_is_pyonged = $params['what_is_pyonged'];
				$id_what_is_pyonged = $params['id_what_is_pyonged'];
				$_notification_array_infos = Array(
					'idUserWhoRecommand' => $id_pyonger,
					'whatIsRecommanded'  => $what_is_pyonged,
					'idThingRecommanded' => $id_what_is_pyonged
					);
				$UserPyong = new User($id_pyonger);
				$UserPyong->getArrayID_followers();
				$_array_IDs_people = $UserPyong->arrayID_followers;
				break;
			case "following_post":
				$_notification_id_badge = -1;
				$id_poster = $params['id_poster'];
				$what_is_posted = $params['what_is_posted'];
				$id_what_is_posted = $params['id_what_is_posted'];
				$_notification_array_infos = Array(
					'idUserWhoPost' => $id_poster,
					'whatIsPosted'  => $what_is_posted,
					'idThingPosted' => $id_what_is_posted
					);
				$UserPost = new User($id_poster);
				$UserPost->getArrayID_followers();
				$_array_IDs_people = $UserPost->arrayID_followers;
				break;
			case "following_want_game":
				$_notification_id_badge = -1;
				$_notification_array_infos = Array(
					'idUserWhoWant' => $params['id_user_who_want'],
					'idGameWanted'  => $params['id_game_wanted'],
					);
				$UserPost = new User($params['id_user_who_want']);
				$UserPost->getArrayID_followers();
				$_array_IDs_people = $UserPost->arrayID_followers;
				break;
			case "following_have_game":
				$_notification_id_badge = -1;
				$_notification_array_infos = Array(
					'idUserWhoHave' => $params['id_user_who_have'],
					'idGameHaved'  => $params['id_game_haved'],
					);
				$UserPost = new User($params['id_user_who_have']);
				$UserPost->getArrayID_followers();
				$_array_IDs_people = $UserPost->arrayID_followers;
				break;
			case "followed":
				$_notification_id_badge = -1;
				$_array_IDs_people = Array($params['id_followed']);
				$_notification_array_infos = Array(
					'idUserWhoFollow' => $params['id_follower']
				);
				break;
			
			default:
				# code...
				break;
		}

		for ($i=0; $i < count($_array_IDs_people) ; $i++) { 
			$_notification_id_user = $_array_IDs_people[$i];
			Notification::send_notification(
				$_notification_id_user,
				$_notification_type,
				$_notification_id_badge,
				$_notification_array_infos,
				date("Y-m-d H:i:s")
			);
		}

	}

}
?>