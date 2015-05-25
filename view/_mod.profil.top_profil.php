<?php
	$_User->init_profil_BadgeProfil();
?>
<section id="top-profil">
	<div class="top-top-profil">
		<span class="bio-profil"><?php echo $_User->bio;?></span>
	</div>
	<div class="container-picture-profil">
		<img src="<?php echo $_User->arrayUrlPictures[100];?>" class="picture-profil"/>
	</div>
	<span class="name-profil">
		<?php 
			echo $_User->displayName;
			if($_User->showIconRank){
		?>
		<img src="<?php echo $_User->arrayUrlIconRank[20];?>" class="icon-rank tipsy-right" title="<?php echo strtoupper($_User->nameRank);?>">
		<?php
			}
		?>
	</span>
	<?php

	/*

		AFFICHAGE DU BADGE PROFIL
		REMETTRE QUAND LES BADGES SERONT DE NOUVEAU LÀ !
		
		<div class="container-badge-profil">
			<div>
				<a href="<?php echo $_User->BadgeProfil->lien;?>">
					<img src="<?php echo $_User->BadgeProfil->arrayUrlIllustrations[60];?>" class="badge-profil"/>
				</a>
				<span class="name-badge <?php echo $_User->BadgeProfil->scarcityClass;?>"><?php echo $_User->BadgeProfil->name;?></span><br>
				<span class="points-user"><b><?php echo number_format($_User->nbPoints);?></b> points</span>
			</div>
		</div>
	*/
	?>
	<div class="container-subscriptions">
		<?php
			if(isset($_UserLogged) AND $_User->id != $_UserLogged->id){
				if($_UserLogged->user_follows($_User->id)){
					$class_button_follow = "unfollow-button";
					$text_button_follow = "Abonné";
					$hover_text_button_follow = "Se désabonner";
					$title_button_follow = "Ne plus recevoir les critiques de $_User->displayName et ses recommandations";
				}else{
					$class_button_follow = "follow-button";
					$text_button_follow = "S'abonner";
					$hover_text_button_follow = "S'abonner";
					$title_button_follow = "Recevoir les critiques de $_User->displayName et ses recommandations";
				}
		?>
			<div data-authenticity-token="<?php echo $_UserLogged->generate_authenticity_token("follows_user_".$_User->id);?>" title="<?php echo $title_button_follow;?>" class="tipsy-top follow-button-normal follow-user-button <?php echo $class_button_follow;?>" data-url="./users/<?php echo $_User->id;?>/follows" data-text-hover="<?php echo $hover_text_button_follow;?>" data-text-default="<?php echo $text_button_follow;?>"><?php echo $text_button_follow;?></div>
		<?php
			}
		?>
		<span class="statistiques"><?php echo count($_User->arrayID_followings);?> abonnements - <?php echo count($_User->arrayID_followers);?> abonnés</span>
	</div>
</section>