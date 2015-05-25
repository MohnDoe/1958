<?php
	
?>
<section id="right-container">
	<section id="container-global-activity">
<?php
	$HTML_group_activity_action_begin = '<section class="group-activity-action">';
	$HTML_group_activity_action_end = '</section>';
	$_User->init_arrayGlobalActivity();
	foreach ($_User->arrayGlobalActivity as $key => $value) {
		// les dates
		$date = $key;
		$arrayActivities = $value;

		foreach ($arrayActivities as $key => $value) {
			// les activités
			$actionActitivy = $key;
			$arrayActivities = $value;

			$arrayCorrespondanceCodeActivity = array(
				'SET_APPR_GAME_P' => 'Liked',
				'BADGE_RECEIVED'  => 'Reward', 
				'SET_HAVE_GAME'   => 'Played', 
				'SET_WANT_GAME'   => 'Wanted',
				'CREATE_CRT'      => 'Critiques',
				'NOTE_GAME'       => 'Rate'
			);

			$titleGroupActivity = $arrayCorrespondanceCodeActivity[$actionActitivy];
			$HTML_title_activity = '<span class="title-group-activity">'.$titleGroupActivity.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="date-group-activity">'.DB::date_to_letter($date).'</span></span>';

			echo $HTML_group_activity_action_begin;
			echo $HTML_title_activity;


			for ($i=0; $i < count($arrayActivities); $i++) { 
				// une activité
				$arrayActivity = $arrayActivities[$i];
				$class_activity = "game";
				if($arrayActivity['actionActivity'] == "BADGE_RECEIVED"){
					$class_activity = "badge";
					//badge reçu
					$titleActivity         = $arrayActivity['badgeActivity']->name;
					$subTitleActivity      = $arrayActivity['badgeActivity']->objectif;
					
					$howMany               = $arrayActivity['badgeActivity']->nbUsersHave." le possèdent";
					
					$howMany .= " | ";

					// $howMany .= "<span class='".$arrayActivity['badgeActivity']->scarcityClass."'>";
					$howMany .= $arrayActivity['badgeActivity']->scarcityText;
					// $howMany .= "</span>";

					$urlBackgroundActivity = $arrayActivity['badgeActivity']->arrayUrlIllustrations[200];
					$lienActivity          = $arrayActivity['badgeActivity']->lien;

				}else if($arrayActivity['actionActivity'] == "CREATE_CRT"){
					// critique
					$titleActivity         = $arrayActivity['critiqueActivity']->Game->name;
					$subTitleActivity      = "Noté ".$arrayActivity['critiqueActivity']->arrayNotes['moyenne']."/100";
					$howMany               = "Noté ".round($arrayActivity['critiqueActivity']->Game->init_or_get('arrayAvgNotes')['moyenne'])."/100 (".$arrayActivity['critiqueActivity']->Game->init_or_get('nbCritiques')." critiques)";
					$urlBackgroundActivity = $arrayActivity['critiqueActivity']->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];
					$lienActivity          = $arrayActivity['critiqueActivity']->lien;

				}else if($arrayActivity['actionActivity'] == "NOTE_GAME"){
					// critique
					$titleActivity         = $arrayActivity['gameActivity']->name;
					$subTitleActivity      = "Noté ".$arrayActivity['noteActivity']."/100";
					$howMany               = "Noté ".round($arrayActivity['gameActivity']->init_or_get('arrayAvgNotes')['moyenne'])."/100 (".$arrayActivity['gameActivity']->init_or_get('nbNotes')." notes)";
					$urlBackgroundActivity = $arrayActivity['gameActivity']->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];
					$lienActivity          = $arrayActivity['gameActivity']->lien;

				}else{
					// AUTRE
					$titleActivity         = $arrayActivity['gameActivity']->name;
					$subTitleActivity      = "".$arrayActivity['gameActivity']->init_or_get('Developer')->name;
					$urlBackgroundActivity = $arrayActivity['gameActivity']->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];
					$lienActivity          = $arrayActivity['gameActivity']->lien;
					switch ($arrayActivity['actionActivity']) {
						case 'SET_APPR_GAME_P':
							$howMany = $arrayActivity['gameActivity']->init_or_get('arrayAppreciations')['positives'].' aiment';
							break;
						case 'SET_HAVE_GAME':
							$howMany = $arrayActivity['gameActivity']->init_or_get('nbUsersPlay').' y jouent';
							break;
						case 'SET_WANT_GAME':
							$howMany = $arrayActivity['gameActivity']->init_or_get('nbUsersPlay').' le veulent';
							break;
						
						default:
							$howMany = $arrayActivity['gameActivity']->init_or_get('arrayAppreciations')['positives'].' aiment';
							break;
					}
				}
				?>
			<a href="<?php echo $lienActivity;?>" class="activity activity-<?php echo $class_activity;?>">
				<div class="informations-activity">
					<span class="title-activity"><?php echo $titleActivity;?></span>
					<span class="subtitle-activity"><?php echo $subTitleActivity;?></span>
					<span class="how-many"><?php echo $howMany;?></span>
				</div>
				<div class="gradient"></div>
				<div class="container-background-activity" style="background-image:url('<?php echo $urlBackgroundActivity;?>')"></div>
			</a>
			<!-- fin .activity -->
				<?php
			}
			echo $HTML_group_activity_action_end;
		}
		?>
		<!-- fin .groupe-activity-action -->
	<?php
	}
?>
	</section>
</section>