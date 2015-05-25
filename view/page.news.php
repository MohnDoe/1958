<?php
	$_BIG_NEWS = new Critique(3);
?>
<section id="page-news">
	<section id="container-big-one-news">
		<span class="big-title-news"><?php echo $_BIG_NEWS->Game->name;?></span>
		<span class="sub-title-news"><?php echo $_BIG_NEWS->title;?></span>
		<a href="<?php echo $_BIG_NEWS->lien;?>">
			<span class="learn-more-button-big-news">Lire la suite</span>
		</a>
		<div class="background-big-one-news" style="background-image:url('<?php echo $_BIG_NEWS->Game->init_or_get('arrayUrlCovers', Array('big'))['big'][0];?>');"></div>
		<div class="degrade-big-one-news"></div>
	</section>
	<section id="container-other-news">
		<section id="other-news">
			<?php
				$_OTHER_NEWS_1_TYPE = "critique";
				$_OTHER_NEWS_1 = new Critique(32);
			?>
			<a href="<?php echo $_OTHER_NEWS_1->lien;?>">
				<section class="container-one-news container-news-<?php echo $_OTHER_NEWS_1_TYPE;?>">
					<span class="type-news"><?php echo $_OTHER_NEWS_1_TYPE;?></span>
					<div class="informations-critique">
						<span class="title-critique"><?php echo $_OTHER_NEWS_1->title;?></span>
						<span class="author-critique"><?php echo $_OTHER_NEWS_1->Author->displayName;?></span>
					</div>
					<section class="background-one-news" style="background-image:url('<?php echo $_OTHER_NEWS_1->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');"></section>
				</section>
			</a>
			<?php
				$_OTHER_NEWS_2_TYPE = "sortie";
				$_OTHER_NEWS_2 = new Game(2260);
			?>
			<a href="<?php echo $_OTHER_NEWS_2->lien;?>">
				<section class="container-one-news container-news-game">
					<span class="type-news">Sortie</span>
					<div class="container-informations-game">
						<span class="title-game"><?php echo $_OTHER_NEWS_2->name;?></span>
						<div class="container-cover-game">
							<img src="<?php echo $_OTHER_NEWS_2->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>" class="cover-game">
						</div>
					</div>
					<section class="background-one-news" style="background-image:url('<?php echo $_OTHER_NEWS_2->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');"></section>
				</section>
			</a>
			<?php
				$_OTHER_NEWS_3_TYPE = "populaire";
				$_OTHER_NEWS_3 = new Game(7635);
			?>
			<a href="<?php echo $_OTHER_NEWS_3->lien;?>">
				<section class="container-one-news container-news-game">
					<span class="type-news">Populaire</span>
					<div class="container-informations-game">
						<span class="title-game"><?php echo $_OTHER_NEWS_3->name;?></span>
						<div class="container-cover-game">
							<img src="<?php echo $_OTHER_NEWS_3->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>" class="cover-game">
						</div>
					</div>
					<section class="background-one-news" style="background-image:url('<?php echo $_OTHER_NEWS_3->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');"></section>
				</section>
			</a>
			<?php
				$_OTHER_NEWS_4_TYPE = "micro-critique";
				$_OTHER_NEWS_4 = new MicroCritique(145);
			?>
			<a href="<?php echo $_OTHER_NEWS_4->lien;?>">
				<section class="container-one-news container-news-micro-critique container-one-news-collspan-2">
					<span class="type-news">Micro-Critique</span>
					<section class="background-one-news" style="background-image:url('<?php echo $_OTHER_NEWS_4->Game->init_or_get('arrayUrlCovers', Array('normal'))['normal'][0];?>');"></section>
				</section>
			</a>
		</section>
	</section>
</section>