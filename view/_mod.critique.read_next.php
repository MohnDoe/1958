<?php
	if(isset($_UserLogged) AND isset($_Critique)){
		$_CritiqueNextToRead = $_UserLogged->get_nextToReadCritique($_Critique->id);
	}
?>
<section id="head-background-critique">
	<section id="container-title-and-author">
		<div class="center-ver" style="display:block; height:auto; width:100%;">
			<span class="title-critique"><?php echo $_CritiqueNextToRead->title;?></span>
			<span class="author-critique">Critique de <a href="<?php echo $_CritiqueNextToRead->Game->lien;?>"><?php echo $_CritiqueNextToRead->Game->name;?></a> par <a href=""><?php echo $_CritiqueNextToRead->Author->displayName;?></a></span>
			<div style="clear:both;"></div>
		</div>
	</section>
	<section class="background-crt" style="background-image: url('<?php echo $_CritiqueNextToRead->Game->init_or_get('arrayUrlCovers', Array('big'))['big'][0];?>');"></section>
</section>