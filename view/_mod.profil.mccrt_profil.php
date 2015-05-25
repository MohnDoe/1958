<?php
	$_User->init_profil_MicroCritique();
	if(!is_null($_User->profil_MicroCritique) AND $_User->profil_MicroCritique){
	$_MicroCritique = $_User->profil_MicroCritique;
?>
<section class="container-micro-critique-profil">
	<div class="content-mccrt">
		<?php echo $_MicroCritique->content;?>
		<!-- <span class="content-game"><?php echo $_MicroCritique->Game->name;?></span> -->
	</div>
	<div class="container-background-image" style="background-image: url('<?php echo $_MicroCritique->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');"></div>
</section>
<?php	
	}
?>