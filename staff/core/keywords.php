<?php
	$tpl = $STYLE->open('staff/keywords.tpl');
	$page_title .=  ' > Character Keywords';
	$description = '';
	$metatags = '';
	$message = '';
	
	if (isset($_POST['delete']) && !empty($_POST['keyword'])) {
		// Check if exists;
		$check = $db->query("SELECT * FROM `keywords` WHERE `keywords`.`id` = '".$_POST['keyword']."'");
		if($check->rowCount() > 0){
			$db->query("DELETE FROM `keywords` WHERE `keywords`.`id` = '".$_POST['keyword']."'");
			$message = '<p>Keyword deleted successfully.</p>';
		}	
	}
	if (isset($_POST['edit']) && !empty($_POST['keyword'])) {
		// Check if exists;
		$check = $db->query("SELECT * FROM `keywords` WHERE `keywords`.`id` = '".$_POST['keyword']."'");
		if($check->rowCount() > 0){
			$name = $secure->clean($_POST['name']);
			$desc = $secure->clean($_POST['description']);
			$replacement = addslashes($_POST['replacement']);
			$db->query("UPDATE `keywords` SET `keyword` = '".$name."', `description` = '".$desc."', `replacement` = '".$replacement."'	WHERE `keywords`.`id` = '".$_POST['keyword']."'");
			$message = '<p>Keyword updated successfully.</p>';
		}	
	}
	if (isset($_POST['new'])) {
		// Name, Code, Replacement
		$name = $secure->clean($_POST['name']);
		$desc = $secure->clean($_POST['description']);
		$replacement = addslashes($_POST['replacement']);
		$db->query("INSERT INTO `keywords` (`id`, `keyword`, `description`, `replacement`) VALUES (NULL, '".$name."', '".$desc."', '".$replacement."');");
		$message = '<p>Keyword inserted!</p>';
	}
	$list = '';
	$template = $STYLE->getcode('option', $tpl);
	$keywords = $db->query("SELECT * FROM `keywords` ORDER BY `keywords`.`id` DESC");
	while($keyword = $keywords->fetch()){
		$list .= $STYLE->tags($template, array("ID"=>$keyword['id'],"NAME"=>$keyword['keyword'],"DESCRIPTION"=>$keyword['description'],"REPLACEMENT"=>$keyword['replacement'], "VALUE"=> htmlspecialchars($keyword['replacement'])));
	}
	if(empty($list))
		$list = 'No keywords found';
	$tpl = str_replace(array( $STYLE->getcode('option', $tpl)), '', $tpl);
	$tpl = $STYLE->tags($tpl, array("LIST"=>$list,"MESSAGE"=>$message));
	//$output .= $tpl;