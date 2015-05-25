<?php
	if(isset($_Badge)){
?>
<section id="page-badge">
	<section id="top-badge">
		<section class="container-badge-illustration">
			<img src="<?php echo $_Badge->arrayUrlIllustrations[300];?>" class="badge-illustration">
			<?php
				if(!is_null($_UserBadge)){
					$textHaveBadge = "ne possède pas ce badge";
					if($_Badge->have_user($_UserBadge->id)){
						$textHaveBadge = "possède ce badge";
					}
			?>
				<span class="have-badge"><a href="<?php echo $_UserBadge->lien;?>" class="user-badge-name"><?php echo $_UserBadge->displayName;?></a> <?php echo $textHaveBadge;?></span>
			<?php
				}
			?>
		</section>
		<section class="container-informations-badge">
			<span class="information title-badge <?php echo $_Badge->scarcityClass;?>"><?php echo $_Badge->name;?></span>
			<span class="information objectif-badge"><?php echo $_Badge->objectif;?></span>
			<span class="information scarcity-badge"><?php echo $_Badge->scarcityText;?></span>
			<span class="information"><?php echo $_Badge->nbUsersHave;?> membres possèdent ce badge</span>
			<span class="information description-badge"><?php echo $_Badge->description;?></span>
			<span class="information end-badge">_end badge <?php echo $_Badge->id;?> /// <?php echo round($_Badge->scarcityPercent*100);?>_</span>
		</section>
	</section>
	<?php
		if(!is_null($_UserBadge)){
			include_once "_mod.badge.userbadge-has.php";
		}
	?>
</section>
<?php
	}
?>