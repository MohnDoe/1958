<?php
	// if(!isset($_UserLogged)){return;}
$_show_container = true;
$_next_page = 2;
$_show_show_more = true;
if(isset($_GET['type_notification'])){
	$_type_notification = $_GET['type_notification'];
	$_show_container = false;
	require_once "../model/core.php";
	$_UserLogged = new User($_SESSION['UserLogged']['id']);
	
}
if(isset($_GET['page']) AND is_numeric($_GET['page'])){
	$_next_page = $_GET['page']+1;
}
?>
<?php
	if($_show_container){	
?>
<!-- <section id="container-notification-<?php echo $_type_notification;?>" class="notifications-container"> -->
<?php
	}
?>
	<?php
		if($_type_notification == "global"){
			$_UserLogged->init_array_notifications_global($_next_page-1);
			$arrayNotifications = $_UserLogged->array_notifications_global;
			if(count($arrayNotifications)<User::$limit_nbNotificationGlobalToGet){
				$_show_show_more = false;
			}
		}else if($_type_notification == "followings"){
			$_UserLogged->init_array_notifications_followings($_next_page-1);
			$arrayNotifications = $_UserLogged->array_notifications_followings;
			if(count($arrayNotifications)<User::$limit_nbNotificationFollowingsToGet){
				$_show_show_more = false;
			}
		}

		if(count($arrayNotifications)>0){
			for ($i=0; $i < count($arrayNotifications); $i++) { 
				$Notification = null;
				$Notification = $arrayNotifications[$i];
				$class_notification = "notification_type_".$Notification->type;
				$informations_notifications_HTML = "";
				$text_notification = "";
				switch ($Notification->type) {
					case 'followed':
						$UserWhoFollow = $Notification->array_infos['UserWhoFollow'];
						$text_notification = $UserWhoFollow->displayName." s'est abonné à vous.";
						$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoFollow->lien.'">
									<img src="'.$UserWhoFollow->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoFollow->displayName.'</span>';
						if($UserWhoFollow->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoFollow->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoFollow->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoFollow->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
						break;
				case 'like':
					$UserWhoLike = $Notification->array_infos['UserWhoLike'];
					switch ($Notification->array_infos['whatIsLiked']) {
						case 'crt':
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingLiked'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
							$text_notification = $UserWhoLike->displayName;
							if($_UserLogged->id != $Crt->Author->id){
								$text_notification .= " a aimé la ".$votreWhat." de ".$Crt->Author->displayName.".";
							}else {
								$text_notification .= " a aimé votre ".$votreWhat;
							}

						break;

						case 'mccrt':
							$votreWhat = "micro-critique";
							$Mccrt = $Notification->array_infos['ThingLiked'];
							$informations_notifications_HTML .= '
							<a href="'.$Mccrt->lien.'">
								<section class="content-informations-notification content-mccrt">
									<span class="content-mccrt">'.$Mccrt->content.'</span>
									<span class="game-mccrt">'.$Mccrt->Game->name.'</span>
								</section>
							</a>
							';
							$text_notification = $UserWhoLike->displayName;
							if($_UserLogged->id != $Mccrt->Author->id){
								$text_notification .= " a aimé la ".$votreWhat." de ".$Mccrt->Author->displayName.".";
							}else{
								$text_notification .= " a aimé votre ".$votreWhat;
							}
						break;

						case 'list':
							$votreWhat = "liste";
						break;
						
						default:
							$votreWhat = "bidule";
						break;
					}

					$text_notification = $UserWhoLike->displayName." a aimé votre ".$votreWhat.".";
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoLike->lien.'">
									<img src="'.$UserWhoLike->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoLike->displayName.'</span>';
						if($UserWhoLike->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoLike->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoLike->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoLike->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'comment':

					$UserWhoComment = $Notification->array_infos['UserWhoComment'];

					switch ($Notification->array_infos['whatIsCommented']) {

						case 'crt':
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingCommented'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
							$text_notification = $UserWhoComment->displayName;
							if($UserWhoComment->id == $Crt->Author->id){
								$text_notification .= " a commenté sa ".$votreWhat." du jeu <i>".$Notification->array_infos['ThingCommented']->Game->name."</i>.";
							}else if($_UserLogged->id != $Crt->Author->id){
								$text_notification .= " a commenté la ".$votreWhat." de ".$Crt->Author->displayName." du jeu <i>".$Notification->array_infos['ThingCommented']->Game->name."</i>.";
							}else{
								$text_notification .= " a commenté votre ".$votreWhat;
							}
						break;

						case 'mccrt':
							$votreWhat = "micro-critique";
							$Mccrt = $Notification->array_infos['ThingCommented'];
							$informations_notifications_HTML .= '
							<a href="'.$Mccrt->lien.'">
								<section class="content-informations-notification content-mccrt">
									<span class="content-mccrt">'.$Mccrt->content.'</span>
									<span class="game-mccrt">'.$Mccrt->Game->name.'</span>
								</section>
							</a>
							';
							$text_notification = $UserWhoComment->displayName;
							if($UserWhoComment->id == $Mccrt->Author->id){
								$text_notification .= " a commenté sa ".$votreWhat;
							}else if($_UserLogged->id != $Mccrt->Author->id){
								$text_notification .= " a commenté la ".$votreWhat." de ".$Mccrt->Author->displayName." du jeu <i>".$Notification->array_infos['ThingCommented']->Game->name."</i>.";
							}else{
								$text_notification .= " a commenté votre ".$votreWhat." du jeu <i>".$Notification->array_infos['ThingCommented']->Game->name."</i>.";
							}
						break;

						case 'list':
							$votreWhat = "liste";
						break;
						
						default:
							$votreWhat = "bidule";
						break;

					}

					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoComment->lien.'">
									<img src="'.$UserWhoComment->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoComment->displayName.'</span>';
						if($UserWhoComment->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoComment->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoComment->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoComment->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'badge':
					$Badge = $Notification->array_infos['Badge'];
					$text_notification = "Vous avez obtenu le badge <i>".$Badge->name."</i>.";
					$informations_notifications_HTML .= '
					<a href="'.$Badge->lien.'">
						<section class="content-informations-notification content-badge">
							<section class="container-badge-illustration">
								<img src="'.$Badge->arrayUrlIllustrations[100].'" class="badge-illustration">
							</section>
							<section class="container-informations-badge">
								<span class="information title-badge '.$Badge->scarcityClass.'">'.$Badge->name.'</span>
								<span class="information objectif-badge">'.$Badge->objectif.'</span>
								<span class="information scarcity-badge">'.$Badge->scarcityText.'</span>
								<span class="information">'.$Badge->nbUsersHave.' membres possèdent ce badge</span>
								<span class="information description-badge">'.$Badge->description.'</span>
							</section>
						</section>
					</a>';
				break;

				case 'following_post':

					$UserWhoPost = $Notification->array_infos['UserWhoPost'];

					switch ($Notification->array_infos['whatIsPosted']) {

						case 'crt':
							$votreWhat = "critique";
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingPosted'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
						break;

						case 'mccrt':
							$votreWhat = "micro-critique";
							$Mccrt = $Notification->array_infos['ThingPosted'];
							$informations_notifications_HTML .= '
							<a href="'.$Mccrt->lien.'">
								<section class="content-informations-notification content-mccrt">
									<span class="content-mccrt">'.$Mccrt->content.'</span>
									<span class="game-mccrt">'.$Mccrt->Game->name.'</span>
								</section>
							</a>
							';
						break;

						case 'list':
							$votreWhat = "liste";
						break;
						
						default:
							$votreWhat = "bidule";
						break;
					}

					$text_notification = $UserWhoPost->displayName." a posté une ".$votreWhat." sur le jeu <i>".$Notification->array_infos['ThingPosted']->Game->name."</i>.";
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoPost->lien.'">
									<img src="'.$UserWhoPost->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoPost->displayName.'</span>';
						if($UserWhoPost->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoPost->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoPost->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoPost->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'following_recommand':

					$UserWhoRecommand = $Notification->array_infos['UserWhoRecommand'];

					switch ($Notification->array_infos['whatIsRecommanded']) {

						case 'crt':
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingRecommanded'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
							$text_notification = $UserWhoRecommand->displayName." recommande la ".$votreWhat." de ".$Notification->array_infos['ThingRecommanded']->Author->displayName." du jeu <i>".$Notification->array_infos['ThingRecommanded']->Game->name."</i>.";
						break;
						case 'review':
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingRecommanded'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
							$text_notification = $UserWhoRecommand->displayName." recommande la ".$votreWhat." de ".$Notification->array_infos['ThingRecommanded']->Author->displayName." du jeu <i>".$Notification->array_infos['ThingRecommanded']->Game->name."</i>.";
						break;

						case 'mccrt':
							$votreWhat = "micro-critique";
							$Mccrt = $Notification->array_infos['ThingRecommanded'];
							$informations_notifications_HTML .= '
							<a href="'.$Mccrt->lien.'">
								<section class="content-informations-notification content-mccrt">
									<span class="content-mccrt">'.$Mccrt->content.'</span>
									<span class="game-mccrt">'.$Mccrt->Game->name.'</span>
								</section>
							</a>
							';
							$text_notification = $UserWhoRecommand->displayName." recommande la ".$votreWhat." de ".$Notification->array_infos['ThingRecommanded']->Author->displayName." du jeu <i>".$Notification->array_infos['ThingRecommanded']->Game->name."</i>.";
						break;

						case 'game':
							$votreWhat = "jeu";
							$Game = $Notification->array_infos['ThingRecommanded'];
							$informations_notifications_HTML .= '
							<a href="'.$Game->lien.'">
								<section class="content-informations-notification content-game">
									<span class="title-game">'.$Game->name.'</span>
									<span class="container-cover-game-crt container-cover-game-note">
										<img src="'.$Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game"/>
									</span>
								</section>
							</a>';
							$text_notification = $UserWhoRecommand->displayName." recommande le jeu <i>".$Game->name."</i>.";
						break;

						case 'list':
							$votreWhat = "liste";
						break;
						
						default:
							$votreWhat = "bidule";
						break;
					}

					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoRecommand->lien.'">
									<img src="'.$UserWhoRecommand->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoRecommand->displayName.'</span>';
						if($UserWhoRecommand->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoRecommand->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoRecommand->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoRecommand->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'following_like':

					$UserWhoLike = $Notification->array_infos['UserWhoLike'];

					switch ($Notification->array_infos['whatIsLiked']) {

						case 'crt':
							$votreWhat = "critique";
							$Crt = $Notification->array_infos['ThingLiked'];
							$informations_notifications_HTML .= '
							<a href="'.$Crt->lien.'">
								<section class="content-informations-notification content-crt">
									<span class="title-crt">'.$Crt->title.'</span>
									<span class="container-cover-game-crt">
										<span class="note-crt">'.round($Crt->arrayNotes['moyenne']).'</span>
										<img src="'.$Crt->Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<span class="content-crt">'.substr($Crt->content, 0, 140).'...</span>
									<span class="game-crt">'.$Crt->Game->name.'</span>
								</section>
							</a>';
							break;

						case 'mccrt':
							$votreWhat = "micro-critique";
							$Mccrt = $Notification->array_infos['ThingLiked'];
							$informations_notifications_HTML .= '
							<a href="'.$Mccrt->lien.'">
								<section class="content-informations-notification content-mccrt">
									<span class="content-mccrt">'.$Mccrt->content.'</span>
									<span class="game-mccrt">'.$Mccrt->Game->name.'</span>
								</section>
							</a>
							';
						break;

						case 'list':
							$votreWhat = "liste";
						break;
						
						default:
							$votreWhat = "bidule";
						break;
					}

					$text_notification = $UserWhoLike->displayName." a aimé la ".$votreWhat." de ".$Notification->array_infos['ThingRecommanded']->Author->displayName.".";
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoLike->lien.'">
									<img src="'.$UserWhoLike->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoLike->displayName.'</span>';
						if($UserWhoLike->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoLike->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoLike->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoLike->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'following_note':

					$UserWhoNote = $Notification->array_infos['UserWhoNote'];

					switch ($Notification->array_infos['whatIsNoted']) {

						case 'game':
							$votreWhat = "jeu";
							$Game = $Notification->array_infos['ThingNoted'];
							$arrayNotes = $Notification->array_infos['arrayNotes'];
							$arrayGlobalNotes = $Game->init_or_get('arrayAvgNotes');
							$informations_notifications_HTML .= '
							<a href="'.$Game->lien.'">
								<section class="content-informations-notification content-note-game">
									<span class="title-game">'.$Game->name.'</span>
									<span class="container-cover-game-crt container-cover-game-note">
										<img src="'.$Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game-crt"/>
									</span>
									<div class="container-notes">
										<div class="left">
											<h6>'.$UserWhoNote->displayName.'</h6>
											<div>
												<div class="container-barre-note">
													<div class="barre barre-gameplay" style="width:'.($arrayNotes['gameplay']*10).'%"></div><span class="note">'.round($arrayNotes['gameplay']*10).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-graphism" style="width:'.($arrayNotes['graphism']*10).'%"></div><span class="note">'.round($arrayNotes['graphism']*10).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-bo" style="width:'.($arrayNotes['bo']*10).'%"></div><span class="note">'.round($arrayNotes['bo']*10).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-lifetime" style="width:'.($arrayNotes['lifetime']*10).'%"></div><span class="note">'.round($arrayNotes['lifetime']*10).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-story" style="width:'.($arrayNotes['story']*10).'%"></div><span class="note">'.round($arrayNotes['story']*10).'</span>
												</div>
											</div>
											<span><span class="note-total">'.round($arrayNotes['moyenne']*10,1).'</span></span>
										</div>
										<div class="right">
											<h6>Global</h6>
											<div>
												<div class="container-barre-note">
													<div class="barre barre-gameplay" style="width:'.$arrayGlobalNotes['gameplay'].'%"></div><span class="note">'.round($arrayGlobalNotes['gameplay']).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-graphism" style="width:'.$arrayGlobalNotes['graphism'].'%"></div><span class="note">'.round($arrayGlobalNotes['graphism']).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-bo" style="width:'.$arrayGlobalNotes['bo'].'%"></div><span class="note">'.round($arrayGlobalNotes['bo']).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-lifetime" style="width:'.$arrayGlobalNotes['lifetime'].'%"></div><span class="note">'.round($arrayGlobalNotes['lifetime']).'</span>
												</div>
												<div class="container-barre-note">
													<div class="barre barre-story" style="width:'.$arrayGlobalNotes['story'].'%"></div><span class="note">'.round($arrayGlobalNotes['story']).'</span>
												</div>
											</div>
											<span><span class="note-total">'.round($arrayGlobalNotes['moyenne'],1).'</span></span>
										</div>
									</div>
								</section>
							</a>';

							$text_notification = $UserWhoNote->displayName." a noté le jeu <i>".$Game->name."</i>.";
						break;
						default:
							$votreWhat = "bidule";
						break;
					}

					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoNote->lien.'">
									<img src="'.$UserWhoNote->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoNote->displayName.'</span>';
						if($UserWhoNote->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoNote->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoNote->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoNote->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'following_badge':

					$UserBadged = $Notification->array_infos['UserBadge'];
					$Badge = $Notification->array_infos['Badge'];
					$text_notification = $UserBadged->displayName." a obtenu le badge  <i>".$Badge->name."</i>.";
					$informations_notifications_HTML = '';
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserBadged->lien.'">
									<img src="'.$UserBadged->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserBadged->displayName.'</span>';
						if($UserBadged->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserBadged->arrayUrlIconRank[20].'" title="'.strtoupper($UserBadged->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserBadged->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				case 'following_want_game':

					$Game = $Notification->array_infos['GameWanted'];
					$UserWhoWant = $Notification->array_infos['UserWhoWant'];
					$text_notification = $UserWhoWant->displayName." a envie de jouer à  <i>".$Game->name."</i>.";
					$informations_notifications_HTML = '';
					$informations_notifications_HTML .= '
					<a href="'.$Game->lien.'">
						<section class="content-informations-notification content-game">
							<span class="title-game">'.$Game->name.'</span>
							<span class="container-cover-game-crt container-cover-game-note">
								<img src="'.$Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game"/>
							</span>
						</section>
					</a>';
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoWant->lien.'">
									<img src="'.$UserWhoWant->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoWant->displayName.'</span>';
						if($UserWhoWant->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoWant->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoWant->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoWant->nbPoints).'</b>
									</span>
								</a>
							</div>
						';

				break;


				case 'following_have_game':

					$Game = $Notification->array_infos['GameHaved'];
					$UserWhoHave = $Notification->array_infos['UserWhoHave'];
					$text_notification = $UserWhoHave->displayName." possède  <i>".$Game->name."</i>.";
					$informations_notifications_HTML .= '
					<a href="'.$Game->lien.'">
						<section class="content-informations-notification content-game">
							<span class="title-game">'.$Game->name.'</span>
							<span class="container-cover-game-crt container-cover-game-note">
								<img src="'.$Game->init_or_get('arrayUrlCovers', Array('xsmall'))['xsmall'][0].'" class="cover-game"/>
							</span>
						</section>
					</a>';
					$informations_notifications_HTML .= '
							<div class="bottom-informations-notification informations-user">
								<a href="'.$UserWhoHave->lien.'">
									<img src="'.$UserWhoHave->arrayUrlPictures[30].'" class="user-picture">
									<span class="user-informations-line">
										<span class="user-name">'.$UserWhoHave->displayName.'</span>';
						if($UserWhoHave->showIconRank){
							$informations_notifications_HTML .= '<img class="icon-rank tipsy-top" src="'.$UserWhoHave->arrayUrlIconRank[20].'" title="'.strtoupper($UserWhoHave->nameRank).'"/>';
						}
						$informations_notifications_HTML .= '
										<b>'.number_format($UserWhoHave->nbPoints).'</b>
									</span>
								</a>
							</div>
						';
				break;

				default:
					# code...
					break;
				}

		$class_new_notification = "";
		if(!$Notification->is_viewed){
			$class_new_notification = "new";
			$_UserLogged->mark_notif_as_read($Notification->id);
		}
	?>
	<section class="container-notification <?php echo $class_notification;?> <?php echo $class_new_notification;?>" data-idn="<?php echo $Notification->id;?>">
		<section class="action-summary">
			<span class="text-notification"><?php echo $text_notification;?></span>
			<span class="timeago"><?php echo $Notification->time_ago;?></span>
			<div style="clear:both;"></div>
		</section>
		<section class="container-informations-notification">
			<?php echo $informations_notifications_HTML;?>
			<span class="timeago"><?php echo $Notification->time_ago;?></span>
			<div style="clear:both;"></div>
		</section>
	</section>
	<?php
			}
		}
	?>
	<?php
		if($_show_show_more){

	?>
	<a href="./<?php echo $_type_notification;?>_notifications_inbox/<?php echo $_next_page;?>" id="show-more-notifications-<?php echo $_type_notification;?>-button" class="show-more-notifications-button prevent-default">Afficher plus</a>
	<?php
		}
	?>
	<div style="clear:both;"></div>
<?php
	if($_show_container){	
?>
<!-- </section> -->
<?php
	}
?>