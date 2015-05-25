<?php
	if(isset($_GET['idGameNote']) AND is_numeric($_GET['idGameNote'])){
		require_once "../model/core.php";
		$idGameNote = $_GET['idGameNote'];
		if(isset($_POST['notes'])){
			$_array_notes = Array(
					'gameplay' => $_POST['notes']['gameplay'],
					'graphism' => $_POST['notes']['graphism'],
					'bo' => $_POST['notes']['bo'],
					'lifetime' => $_POST['notes']['lifetime'],
					'story' => $_POST['notes']['story']
				);
			$_UserLogged = new User($_SESSION['UserLogged']['id']);
			$_UserLogged->send_notes_game($idGameNote, $_array_notes);
		}
		$_Game = new Game($idGameNote);
	}
	if(isset($_Game)){
?>
<section id="container-ratings-game">
	<div class="box-rate-game box-statistique">
		<div class="line-rate">
			<span class="note-game"><span class="note"><?php echo round($_Game->init_or_get('arrayAvgNotes')['moyenne'],1);?></span>/100</span>
			<span class="statistique-text">
				<?php echo $_Game->init_or_get('nbNotes');?> notes / <?php echo $_Game->init_or_get('nbCritiques'); ?> critiques
			</span>
		</div>
			<div id="begin-noting">NOTER</div>
	</div>
	<div class="box-rate-game">
		<div class="rating gameplay" style="display:none;">
			<div class="line-rate">
				<h6>Gameplay</h6>
				<span class="little">Facile à prendre en mains ou aussi intuitif qu'un tableau de bord de Boeing ?</span>
			</div>
			<ul data-rate="gameplay" id="gameplay_note" rel="graphism">
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>10</li>
			</ul>
		</div>
		<div style="display:none;" class="rating graphism">
			<div class="line-rate">
				<h6>Graphismes</h6>
				<span class="little">Plus vrai que nature ou plus pixélisé qu'un Jackson Pollock 8-bit ?</span>
			</div>
			<ul data-rate="graphism" id="graphism_note" rel="bo">
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>10</li>
			</ul>
		</div>
		<div style="display:none;" class="rating bo">
			<div class="line-rate">
				<h6>Bande-son</h6>
				<span class="little">Elles disent quoi vos oreilles ? Elles s'éclatent ou elles ont éclaté ?</span>
			</div>
			<ul data-rate="bo" id="bo_note" rel="lifetime">
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>10</li>
			</ul>
		</div>
		<div style="display:none;" class="rating lifetime">
			<div class="line-rate">
				<h6>Durée de vie</h6>
				<span class="little">Plus court qu'un épisode de South Park ou plus long qu'une minute devant Le Feux de l'Amour ?</span>							
			</div>
			<ul data-rate="lifetime" id="lifetime_note" rel="story">
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>10</li>
			</ul>
		</div>
		<div style="display:none;" class="rating story">
			<div class="line-rate">
				<h6>Histoire</h6>
				<span class="little">Avez-vous dormi, deviné l'identité du tueur et découvert le fond de l'affaire avant la fin ? Pas bon ça ..</span>							
			</div>
			<ul data-rate="story" id="story_note" rel="end">
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>10</li>
			</ul>
		</div>
		<div style="display:none;" class="totalrating">
			<div class="line-rate">
				<span class="your-notes">Vos notes</span>
				<ul class="finals-rate">
					<li class="gameplay gameplay_note"></li>
					<li class="graphism graphism_note"></li>
					<li class="bo bo_note"></li>
					<li class="lifetime lifetime_note"></li>
					<li class="story story_note"></li>
				</ul>
			</div>
			<div class="line-rate">
				<div id="edit-notes">MODIFIER</div>
				<div id="send-notes" data-url="./note/game/<?php echo $_Game->id;?>">FIN NOTES</div>
			</div>
		</div>
	</div>
</section>
</section>
<?php
	}
?>