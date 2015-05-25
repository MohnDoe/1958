<?php
	if(is_null($_UserBadge)){return;}
	$_UserBadge->init_arrayBadgesProfil();
?>
<section id="user-badge-has">
	<span><b><?php echo $_UserBadge->displayName;?></b> a également obtenu ces supers badges</span>
	<?php
		for ($i=0; $i < count($_UserBadge->arrayBadgesProfil); $i++) { 
			$Badge = $_UserBadge->arrayBadgesProfil[$i];
	?>
		<a href="<?php echo $Badge->lien;?>" class="tipsy-bottom-html" title="<span style='font-size: 12px;' class='<?php echo $Badge->scarcityClass;?>'><?php echo $Badge->name;?></span>">
			<img src="<?php echo $Badge->arrayUrlIllustrations[60];?>" class="illustration-badge">
		</a>
	<?php
		}
	?>
</section