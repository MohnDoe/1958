<section id="topbar">
	<div class="logo"></div>
<?php
	if(isset($_UserLogged)){
		if($_UserLogged->user_exists()){
			$_UserLogged->init_nbNotificationGlobal_notviewed();
			$_UserLogged->init_nbNotificationFollowings_notviewed();
?>
	<div class="user-links">
		<?php
			$class_global_inbox_count = "";
			if($_UserLogged->nbNotificationGlobal_notviewed>0){
				$class_global_inbox_count = "has_unread";
			}
		?>
		<a href="./global_notifications_inbox/1" id="global-notifications-inbox-icon" class="notifications-show-button prevent-default inbox-count <?php echo $class_global_inbox_count;?>">
			<div id="unread-global-notifications-inbox-items-count" class="inbox-count <?php echo $class_global_inbox_count;?>"><?php echo $_UserLogged->nbNotificationGlobal_notviewed;?></div>
		</a>
		<?php
			$class_followings_inbox_count = "";
			if($_UserLogged->nbNotificationFollowings_notviewed>0){
				$class_followings_inbox_count = "has_unread";
			}
		?>
		<a href="./followings_notifications_inbox/1" id="followings-notifications-inbox-icon" class="notifications-show-button prevent-default inbox-count <?php echo $class_followings_inbox_count;?>">
			<div id="unread-followings-notifications-inbox-items-count" class="inbox-count <?php echo $class_followings_inbox_count;?>"><?php echo $_UserLogged->nbNotificationFollowings_notviewed;?></div>
		</a>
		<div class="container-user-picture">
			<a class="user-picture" href="<?php echo $_UserLogged->lien;?>">
				<img src="<?php echo $_UserLogged->arrayUrlPictures[30];?>" class="picture" alt="Avatar de <?php echo $_UserLogged->displayName;?>"/>
			</a>
			<div class="user-details">
				<a href="<?php echo $_UserLogged->lien;?>" class="login"><?php echo $_UserLogged->displayName;?></a>
				<!-- <span class="points-user">
					<a href="<?php echo $_UserLogged->lien;?>" class="points"><?php echo number_format($_UserLogged->nbPoints);?></a>
				</span> -->
				<?php
					if($_UserLogged->showIconRank){
				?>
				<div class="container-rank-icon">
					<img src="<?php echo $_UserLogged->arrayUrlIconRank[20];?>" class="rank-icon tipsy-bottom" title="<?php echo strtoupper($_UserLogged->nameRank);?>">
				</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>
<?php
		}
	}
?>
</section>
<?php
	if(isset($_UserLogged) AND $_UserLogged->user_exists()){
		$_type_notification = "global";
?>
<section id="container-notifications-<?php echo $_type_notification;?>" class="notifications-container loading"></section>
<?php
		$_type_notification = "followings";
?>
<section id="container-notifications-<?php echo $_type_notification;?>" class="notifications-container loading"></section>
<?php
	}
?>