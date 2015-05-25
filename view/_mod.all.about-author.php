<?php
	if(isset($_Author)){
?>
<div class="very-big-title"><?php echo "À propos de $_Author->displayName";?></div>
<section class="container-about-author">
	<div class="container-user-picture">
		<img src="<?php echo $_Author->arrayUrlPictures[100];?>" class="user-picture"/>
	</div>
	<ul class="informations-author">
		<li class="info"><b><?php echo number_format($_Author->nbPoints);?></b> points</li>
		<?php
			$percentEvaluations = 50;
			if($_Author->arrayEvaluations['total']>0){
				$percentEvaluations = $_Author->arrayEvaluations['positives']/$_Author->arrayEvaluations['total']*100;
				$percentEvaluations = round($percentEvaluations, 1);
			}
		?>
		<li class="info"><b><?php echo $percentEvaluations;?></b>% éval. positives</li>
		<li class="info"><b><?php echo $_Author->nbCritiques;?></b> critiques</li>
		<li class="info"><b><?php echo $_Author->nbMicrosCritiques;?></b> micros-critique</li>
	</ul>
	<a href="<?php echo $_Author->lien;?>">
		<div class="button-see-profil">Voir Profil</div>
	</a>
</section>
<?php
	}
?>