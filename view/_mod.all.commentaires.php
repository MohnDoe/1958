<?php
	if($_POST){
		require_once "../model/core.php";
		if(isset($_POST['comment']) AND !empty($_POST['comment'])){
			$_comment                    = $_POST['comment'];
			$_comment_content            = strip_tags($_comment['content']);
			$_comment_on_what            = $_comment['on_what'];
			$_comment_id_what            = $_comment['id_what'];
			$_comment_authenticity_token = $_comment['authenticity_token'];

			if(isset($_SESSION['UserLogged']['authenticity_token']["comment_on_".$_comment_on_what."_".$_comment_id_what]) AND isset($_SESSION['UserLogged']['id'])){
				$_session_authenticity_token = $_SESSION['UserLogged']['authenticity_token']["comment_on_".$_comment_on_what."_".$_comment_id_what];
				if($_session_authenticity_token == $_comment_authenticity_token){
					$_UserLogged = new User($_SESSION['UserLogged']['id']);
					$succes_id_generate = $_UserLogged->post_comment($_comment_content, $_comment_on_what, $_comment_id_what);
					$json_reponse['comment_id'] = $succes_id_generate;
					if($succes_id_generate){
						$json_reponse['comment_date'] = date("Y-m-d H:i:s");
						$json_reponse['comment_content'] = str_replace('\n', "<br/>", $_comment['content']);
					}
				}
				unset($_SESSION['UserLogged']['authenticity_token']["comment_on_".$_comment_on_what."_".$_comment_id_what]);
			}
			echo json_encode($json_reponse);
		}
		return;
	}
	$_show_container = true;
	$_next_page = 2;
	$_show_show_more = true;
	if(isset($_GET['typeThing']) AND isset($_GET['idThing'])){
		require_once "../model/core.php";
		$_show_container = false;
		$_type_thing = $_GET['typeThing'];
		$_id_thing = $_GET['idThing'];
		switch ($_type_thing) {
			case 'review':
				$_ThingToGetComments = new Critique($_id_thing);
				break;

			case 'microreview':
				$_ThingToGetComments = new MicroCritique($_id_thing);
				break;

			case 'list':
				$_ThingToGetComments = new ListGames($_id_thing);
				break;
			
			default:
				# code...
				break;
		}
	}
	if(isset($_GET['page']) AND is_numeric($_GET['page'])){
		$_next_page = $_GET['page']+1;
	}
	if(isset($_ThingToGetComments) AND method_exists($_ThingToGetComments, "get_commentaires")){
		$_id_thing = $_ThingToGetComments->id;
		$_array_Comments = $_ThingToGetComments->get_commentaires($_next_page-1);
		switch (get_class($_ThingToGetComments)) {
			case 'Critique':
				$_type_thing = "review";
				break;
			case 'MicroCritique':
				$_type_thing = "microreview";
				break;
			case 'ListGames':
				$_type_thing = "list";
				break;
			default:
				# code...
				break;
		}
		$class = get_class($_ThingToGetComments);
		$total_commentaires = call_user_func(Array($_ThingToGetComments, "get_nb_commentaires"));
		$restant_commentaires = $total_commentaires-(($_next_page-1)*$class::$limit_nbCommentairesToGet);
		if(0 >= $restant_commentaires){
			$_show_show_more = false;
		}

?>
<?php
	if($_show_container){
?>
<span class="title-osw-16">Les commentaires ... contructifs ou pas</span>
<section id="container-commentaires-module">
<!-- <span class="total-commentaires"><?php echo $total_commentaires;?> commentaires</span> -->
<?php
	}
		if($_show_show_more){
	?>
	<a href="./comments/<?php echo $_type_thing;?>/<?php echo $_id_thing;?>/<?php echo $_next_page;?>" id="show-more-comments-button" class="show-more-comments-button prevent-default">Afficher plus de commentaires (+<?php echo $restant_commentaires;?>)</a>
	<?php
		}
	?>	
		<?php
			if(count($_array_Comments)>0){
				for ($i=0; $i < count($_array_Comments) ; $i++) { 
					$Comment = $_array_Comments[$i];
		?>
		<section class="container-com" id="comid_<?php echo $Comment->id;?>">
			<a href="<?php $Comment->Author->lien;?>">
				<section class="container-pp-user">
					<img src="<?php echo $Comment->Author->arrayUrlPictures[60];?>" class="pp-user">
					<section class="flech-user"></section>
				</section>
			</a>
			<section class="content-com">
				<span class="infos-com">
					<a class="username" href="<?php echo $Comment->Author->lien;?>"><?php echo $Comment->Author->displayName;?></a>
					<?php
						if($Comment->Author->showIconRank){
						?>
					<span class="smallbadge" style="margin-left: 5px;">
						<img src="<?php echo $Comment->Author->arrayUrlIconRank[20];?>" class="tipsy-right" style="height:16px; width:16px;" title="<?php echo strtoupper($Comment->Author->nameRank);?>">
					</span>
					<?php
						}
					?>
					<span class="date-com" title="<?php echo $Comment->date;?>"><?php echo $Comment->time_ago;?></span>
				</span>
				<span class="comment"><?php echo str_replace("\n", "<br/>", $Comment->content);?></span>
			</section>
		</section>
		<?php
				}
			}

			if(isset($_UserLogged)){
				$text_compozer = "Donner son avis serait une excellente idée, non ?";
				$url_picture_compozer = $_UserLogged->arrayUrlPictures[60];
			}else{
				$text_compozer = "Se connecter et donner son avis serait une merveilleuse idée, c'est sûr !";
				$User = new User(0);
				$url_picture_compozer = '.'.FOLDER_USERS_PICTURES.'/nopic_60.jpg';
			}
		if($_show_container){
?>
		<section id="template-mustache-comment" style="display:none; visibility:hidden;">
			<section class="container-com" id="comid_{{comment_id}}">
				<a href="<?php $_UserLogged->lien;?>">
					<section class="container-pp-user">
						<img src="<?php echo $_UserLogged->arrayUrlPictures[60];?>" class="pp-user">
						<section class="flech-user"></section>
					</section>
				</a>
				<section class="content-com">
					<span class="infos-com">
						<a class="username" href="<?php echo $_UserLogged->lien;?>"><?php echo $_UserLogged->displayName;?></a>
						<?php
							if($_UserLogged->showIconRank){
						?>
						<span class="smallbadge" style="margin-left: 5px;">
							<img src="<?php echo $_UserLogged->arrayUrlIconRank[20];?>" class="tipsy-right" style="height:16px; width:16px;" title="<?php echo strtoupper($_UserLogged->nameRank);?>">
						</span>
						<?php
							}
						?>
						<span class="date-com" title="{{comment_date}}">À l'instant</span>
					</span>
					<span class="comment">{{{comment_content}}}</span>
				</section>
			</section>
		</section>
		<section class="container-com compoze-com">
			<section class="container-pp-user">
				<img src="<?php echo $url_picture_compozer;?>" class="pp-user">
				<section class="flech-user"></section>
			</section>
			<section class="content-com">
				<form name="post_comment" method="POST" action="./comments" id="post_comment_form">
					<textarea class="compozer" name="comment[content]" placeholder="<?php echo $text_compozer;?>"></textarea>
					<input type="hidden" name="comment[on_what]" value="<?php echo $_type_thing;?>">
					<input type="hidden" name="comment[id_what]" value="<?php echo $_id_thing;?>">
					<input type="hidden" name="comment[authenticity_token]" value="<?php echo $_UserLogged->generate_authenticity_token("comment_on_".$_type_thing."_".$_id_thing);?>">
					<input type="submit" class="zone-envoyer" value="">
				</form>
			</section>
		</section>
</section>
<?php
		}
	}
?>