<?php
	if(isset($_User)){
	$_User->init_arrayBadgesProfil();
	// var_dump($_User->arrayBadgesProfil);
?>
<section id="barre-profil-badges">
	<span class="big-title">Les derniers badges de <?php echo $_User->displayName;?></span>
	<?php
		for ($i=0; $i < count($_User->arrayBadgesProfil) ; $i++) { 
			$Badge = $_User->arrayBadgesProfil[$i];
	?>
	<section class="container-badge">
		<a href="<?php echo $Badge->lien;?>">
			<img src="<?php echo $Badge->arrayUrlIllustrations[100];?>" class="illustration-badge"/>
		</a>
		<span class="name-badge name-badge-<?php echo $Badge->scarcityClass;?>"><?php echo $Badge->name;?></span>
	</section>
	<?php
		}
	?>
</section>
<?php
	}
?>