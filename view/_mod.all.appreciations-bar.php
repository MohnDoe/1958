<?php
$array_users_who_like = Array();
$total_appreciations_pos = $total_appreciations_neg = 0;
if(isset($_ThingToGetAppreciations)){
	if(method_exists($_ThingToGetAppreciations, "get_users_who_like")){
		$array_users_who_like = call_user_func(Array($_ThingToGetAppreciations, "get_users_who_like"));
	}
	// $_id_thing = $_ThingToGetAppreciations->id;
	$class = get_class($_ThingToGetAppreciations);
	$_id_thing = $_ThingToGetAppreciations->id;
	switch ($class) {
		case 'Critique':
			$_type_thing = "review";
			$_text_thing = "cette critique";
			break;
		case 'MicroCritique':
			$_type_thing = "microreview";
			$_text_thing = "cette micro-critique";
			break;
		case 'ListGames':
			$_type_thing = "list";
			$_text_thing = "cette liste";
			break;
		default:
			# code...
			break;
	}
	if(method_exists($_ThingToGetAppreciations, "get_nb_appreciations_pos")){
		$total_appreciations_pos = call_user_func(Array($_ThingToGetAppreciations, "get_nb_appreciations_pos"));
	}
	if(method_exists($_ThingToGetAppreciations, "get_nb_appreciations_neg")){
		$total_appreciations_neg = call_user_func(Array($_ThingToGetAppreciations, "get_nb_appreciations_neg"));
	}
	$total_appreciations = $total_appreciations_pos+$total_appreciations_neg;
	$percent_appreciations_pos = 50;
	if($total_appreciations>0){
		$percent_appreciations_pos = $total_appreciations_pos/$total_appreciations*100;
	}
}
?>
<!-- <span class="title-osw-16">Ils aiment <span class="nb_likes">(<?php echo $total_appreciations_pos;?>)</span></span> -->
<section id="container-appreciations-bar-module">
	<section id="container-bar-appreciation">
		<section class="bar-appreciation">
			<div class="fill" style="width:<?php echo $percent_appreciations_pos;?>%"></div>
		</section>
		<span class="statistiques-likes">
			<?php
				$class_like = (isset($_UserLogged) AND $_UserLogged->is_liking($_ThingToGetAppreciations))?"pressed":"";
				$class_dislike = (isset($_UserLogged) AND $_UserLogged->is_disliking($_ThingToGetAppreciations))?"pressed":"";

			?>
			<div data-authenticity-token="<?php echo $_UserLogged->generate_authenticity_token("like_".$_type_thing."_".$_id_thing);?>" class="appreciation-action-like appreciation-button tipsy-top <?php echo $class_like;?>" title="J'aime <?php echo $_text_thing;?>" data-url="./<?php echo $_type_thing;?>s/<?php echo $_ThingToGetAppreciations->id;?>/like"></div>
			<span class="number">
				<?php echo $total_appreciations_pos;?>
			</span>
			<div data-authenticity-token="<?php echo $_UserLogged->generate_authenticity_token("dislike_".$_type_thing."_".$_id_thing);?>" class="appreciation-action-dislike appreciation-button tipsy-top <?php echo $class_dislike;?>" title="Je n'aime pas <?php echo $_text_thing;?>" data-url="./<?php echo $_type_thing;?>s/<?php echo $_ThingToGetAppreciations->id;?>/dislike"></div>
			<span class="number">
				<?php echo $total_appreciations_neg;?>
			</span>
		</span>
	</section>
	<section id="container-users-like">
<?php
if(count($array_users_who_like)>0 AND FALSE){
	for ($i=0; $i < count($array_users_who_like); $i++) {
		$UserLike = $array_users_who_like[$i]; 
?>
	<a href="<?php echo $UserLike->lien;?>">
		<span class="container-picture-user-like">
			<img src="<?php echo $UserLike->arrayUrlPictures[30];?>" class="picture-user-like tipsy-top" title="<?php echo $UserLike->displayName;?>">
		</span>
	</a>
<?php
	}
}
?>
	</section>
</section>