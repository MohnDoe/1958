<?php
	if(isset($_Game)){
		$arrayCritiques = $_Game->get_arrayCritiques();
		for ($i=0; $i < count($arrayCritiques); $i++) { 
			$Critique = $arrayCritiques[$i];
			$totalNote = $Critique->arrayNotes['gameplay']+$Critique->arrayNotes['graphism']+$Critique->arrayNotes['bo']+$Critique->arrayNotes['story']+$Critique->arrayNotes['lifetime'];
			$percent = Array('gameplay'=>20, 'graphism' => 20, 'bo'=>20, 'story'=>20, 'lifetime'=>20);
			if($totalNote > 0){
				$percent['gameplay'] = $Critique->arrayNotes['gameplay']/$totalNote*100;
				$percent['graphism'] = $Critique->arrayNotes['graphism']/$totalNote*100;
				$percent['bo'] = $Critique->arrayNotes['bo']/$totalNote*100;
				$percent['story'] = $Critique->arrayNotes['story']/$totalNote*100;
				$percent['lifetime'] = $Critique->arrayNotes['lifetime']/$totalNote*100;
			}
?>	
			<a href="<?php echo $Critique->lien;?>">
				<section class="container-critique-game">
					<span class="note-critique"><span class="note"><?php echo $Critique->arrayNotes['moyenne'];?></span>/100</span>
					<span class="title-critique"><?php echo $Critique->title;?></span>
					<section class="content-critique-game"><?php echo substr(strip_tags($Critique->content), 0, 255);?>...</section>
					<section class="barre-bottom-critique">
						<div class="barre barre-gameplay tipsy-top-html" style="width: <?php echo $percent['gameplay'];?>%;" title="Gameplay<br/><span style='font-size:24px; font-weight:bold;'><?php echo $Critique->arrayNotes['gameplay'];?></span>/100"></div>
						<div class="barre barre-graphism tipsy-top-html" style="width: <?php echo $percent['graphism'];?>%;" title="Graphisme<br/><span style='font-size:24px; font-weight:bold;'><?php echo $Critique->arrayNotes['graphism'];?></span>/100"></div>
						<div class="barre barre-bo tipsy-top-html" style="width: <?php echo $percent['bo'];?>%;" title="Bande-Son<br/><span style='font-size:24px; font-weight:bold;'><?php echo $Critique->arrayNotes['bo'];?></span>/100"></div>
						<div class="barre barre-story tipsy-top-html" style="width: <?php echo $percent['story'];?>%;" title="Histoire<br/><span style='font-size:24px; font-weight:bold;'><?php echo $Critique->arrayNotes['story'];?></span>/100"></div>
						<div class="barre barre-lifetime tipsy-top-html" style="width: <?php echo $percent['lifetime'];?>%;" title="Durée de vie<br/><span style='font-size:24px; font-weight:bold;'><?php echo $Critique->arrayNotes['lifetime'];?></span>/100"></div>
					</section>
				</section>
			</a>
<?php
		}
	}
?>