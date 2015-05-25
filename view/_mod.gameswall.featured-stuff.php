<?php

?>
<section class="featured-stuff">
	<section class="container-slideshow-micros-critiques">
		<section class="slideshow-micros-critiques">
<?php
	$MicroCritique1 = new MicroCritique(241);
	$MicroCritique2 = new MicroCritique(118);
?>	
			<section class="micro-critique">
				<div class="container-content-micro-critique center-ver">
					<span class="content-micro-critique">
						<?php echo $MicroCritique1->content;?>
					</span>
				</div>
				<div class="background-micro-critique" style="background-image:url('<?php echo $MicroCritique1->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');">
				</div>
			</section>
			<section class="micro-critique">
				<div class="container-content-micro-critique center-ver">
					<span class="content-micro-critique">
						<?php echo $MicroCritique2->content;?>
					</span>
				</div>
				<div class="background-micro-critique" style="background-image:url('<?php echo $MicroCritique2->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');">
				</div>
			</section>
		</section>
	</section>
</section>