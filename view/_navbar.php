<?php
	
	$class_onglet_pop = $class_onglet_soon = $class_onglet_random = "";
	if(isset($_GET['typegameswall']) AND !empty($_GET['typegameswall'])){
		$class_onglet_pop = $class_onglet_soon = $class_onglet_random = "";
		switch ($_GET['typegameswall']) {
			case 'pop':
				$class_onglet_pop = "actif";
				break;
			case 'soon':
				$class_onglet_soon = "actif";
				break;
			case 'random':
				$class_onglet_random = "actif";
				break;
			
			default:
				$class_onglet_pop = "actif";
				break;
		}
	}
?>
<section id="navbar">
	<a href="./populaires">
		<div class="onglet <?php echo $class_onglet_pop;?>">Populaires</div>
	</a>
	<a href="./prochainement">
		<div class="onglet <?php echo $class_onglet_soon;?>">Prochainement</div>
	</a>
	<a href="./random">		
		<div class="onglet <?php echo $class_onglet_random;?>">Au hasard</div>
	</a>
	<!-- <a href="./news">
		<div class="onglet right news">1958 NEWS</div>
	</a> -->
</section>