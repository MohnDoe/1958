<?php
	if(isset($_List)){
		if(!isset($_SESSION['view']['list'][$_List->id])){
			$_SESSION['view']['list'][$_List->id] = time()+(60*30);
			$_List->add_popularite(1);
			$_List->add_views(1);
			if(isset($_UserLogged)){
				$_UserLogged->add_transaction('view', Array(
					'what_is_viewed' => "list",
					'id_what_is_viewed' => $_List->id
				));
				// Badge_VIEWEDlist::event_default($_SESSION['UserLogged']['id']);
			}
		}elseif($_SESSION['view']['list'][$_List->id] <= time()){
			$_SESSION['view']['list'][$_List->id] = time()+(60*30);
			$_List->add_popularite(1);
			$_List->add_views(1);
			if(isset($_UserLogged)){
				$_UserLogged->add_transaction('view', Array(
					'what_is_viewed' => "list",
					'id_what_is_viewed' => $_List->id
				));
				// Badge_VIEWEDlist::event_default($_SESSION['UserLogged']['id']);
			}
		}
?>
<section id="page-liste">
	<?php
		include "_mod.list.top-list.php";
		include "_mod.list.container-list.php";
	?>
</section>
<?php
	}
?>