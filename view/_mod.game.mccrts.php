<?php
	if(isset($_Game)){
?>
<span class="big-title">Quelques micros-critiques</span>
<?php
	$_Game->init_or_get('arrayLastsMicrosCritiques');
?>
<section id="container-mccrts-game">
	<?php
	for ($i=0; $i < count($_Game->arrayLastsMicrosCritiques) ; $i++) { 
		$MicroCritique = $_Game->arrayLastsMicrosCritiques[$i];
	?>
		<section class="container-mccrt">
			<a href="<?php echo $MicroCritique->lien;?>">
				<div class="content-mccrt"><?php echo $MicroCritique->content;?></div>
			</a>
			<a href="<?php echo $MicroCritique->Author->displayName;?>">
				<div class="author-mccrt"><?php echo $MicroCritique->Author->displayName;?></div>
			</a>
		</section>
	<?php
	}
	?>
</section>
<?php
	}
?>