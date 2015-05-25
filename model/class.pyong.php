<?php
	Class Pyong
	{

		static $limit_time_repyong_minutes = 720;


		static function add_pyong($idPyonger, $typePyong, $idThingPyong){
			if(self::can_user_pyong($idPyonger, $typePyong, $idThingPyong)){
				$U = new User($idPyonger);
				$arrayID_pyonged = $U->getArrayID_followers();
				$serializedUsersPyonged = json_encode($arrayID_pyonged);
				$nbUsersPyonged = count($arrayID_pyonged);
				$req = DB::$db->prepare('INSERT INTO `'.DB::$tablePyongs.'`(`idUserPyong`, `typePyong`, `idThingPyong`, `usersGettingPyongedPyong`, `listUsersGettingPyongedPyong`, `datePyong`)
					VALUES (:idPyonger, :typePyong, :idThingPyong, :nbUsersPyonged, :serializedUsersPyonged, NOW())');
				$req->execute(array(
					':idPyonger'              => $idPyonger,
					':typePyong'              => $typePyong,
					':idThingPyong'           => $idThingPyong,
					':nbUsersPyonged'         => $nbUsersPyonged,
					':serializedUsersPyonged' => $serializedUsersPyonged
					));

				return true;
			}
			return false;
		}

		static function can_user_pyong($idPyonger, $typePyong, $idThingPyong){
			$req = DB::$db->query('SELECT datePyong FROM `'.DB::$tablePyongs.'` WHERE idUserPyong = "'.$idPyonger.'" AND idThingPyong = "'.$idThingPyong.'" AND typePyong = "'.$typePyong.'" ORDER BY datePyong DESC LIMIT 1');
			if($data = $req->fetch()){
				$dateLastPyong = date($data['datePyong']);
				$dateLastPyong = strtotime($dateLastPyong);
				$dateNow = date("Y-m-d H:i:s");
				$dateNow = strtotime($dateNow);

				$diff = abs($dateNow - $dateLastPyong) / 60;
				if($diff >= self::$limit_time_repyong_minutes){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}

		}

		static function get_nb_pyongs($typePyong, $idThingPyong){
			$req = DB::$db->query('SELECT COUNT(*) AS result FROM `'.DB::$tablePyongs.'` WHERE idThingPyong = "'.$idThingPyong.'" AND typePyong = "'.$typePyong.'" ORDER BY datePyong DESC LIMIT 1');
			$data = $req->fetch();
			return $data['result'];
		}
	}
?>