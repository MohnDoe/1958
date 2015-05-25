<?php
	require_once "../model/core.php";
	if(isset($_GET['typeSearch'])){
		if($_GET['typeSearch'] == "developers"){
			$type_result = "developer";
			$arrayResults = Developer::search_like($_POST['search']);
		}else if($_GET['typeSearch'] == "editors"){
			$type_result = "editor";
			$arrayResults = Editor::search_like($_POST['search']);
		}
	}

	if(isset($arrayResults)){
		for ($i=0; $i < count($arrayResults) ; $i++) { 
			$Result = $arrayResults[$i];
?>
	<div class="container-result" data-id="<?php echo $Result->id;?>" data-type="<?php echo $type_result;?>">
		<span class="title-result"><?php echo $Result->name;?></span>
	</div>
<?php
		}
	}else{
?>
	<span class="no-result">Aucun résultat</span>
<?php
	}
?>