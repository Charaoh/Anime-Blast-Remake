<?php
	$tpl = $STYLE->open('acp/community-emojis.tpl');
	$page_title .=  ' > Forum Emojis';
	$description = '';
	$metatags = '';
	$message = '';
	if (isset($_POST['delete']) && !empty($_POST['emoji'])) {
		// Check if exists;
		$check = $db->query("SELECT * FROM `emoji` WHERE `emoji`.`id` = '".$_POST['emoji']."'");
		if($check->rowCount() > 0){
			$db->query("DELETE FROM `emoji` WHERE `emoji`.`id` = '".$_POST['emoji']."'");
			$message = '<p>Emoji deleted successfully.</p>';
		}	
	}
	if (isset($_POST['edit']) && !empty($_POST['emoji'])) {
		// Check if exists;
		$check = $db->query("SELECT * FROM `emoji` WHERE `emoji`.`id` = '".$_POST['emoji']."'");
		if($check->rowCount() > 0){
			$name = $secure->clean($_POST['name']);
			$code = $secure->clean($_POST['code']);
			$replacement = addslashes($_POST['replacement']);
			$db->query("UPDATE `emoji` SET `name` = '".$name."', `code` = '".$code."', `replacement` = '".$replacement."'	WHERE `emoji`.`id` = '".$_POST['emoji']."'");
			$message = '<p>Emoji updated successfully.</p>';
		}	
	}
	if (isset($_POST['new'])) {
		// Name, Code, Replacement
		$name = $secure->clean($_POST['name']);
		$code = $secure->clean($_POST['code']);
		$replacement = addslashes($_POST['replacement']);
		$db->query("INSERT INTO `emoji` (`id`, `name`, `code`, `replacement`) VALUES (NULL, '".$name."', '".$code."', '".$replacement."');");
		$message = '<p>Emoji inserted!</p>';
	}
	$list = '';
	$template = $STYLE->getcode('option', $tpl);
	$emojis = $db->query("SELECT * FROM `emoji` ORDER BY `emoji`.`id` DESC");
	while($emoji = $emojis->fetch()){
		$list .= $STYLE->tags($template, array("ID"=>$emoji['id'],"NAME"=>$emoji['name'],"CODE"=>$emoji['code'],"REPLACEMENT"=>$emoji['replacement'], "VALUE"=> htmlspecialchars($emoji['replacement'])));
	}
	if(empty($list))
		$list = 'No emojis found';
	$tpl = str_replace(array( $STYLE->getcode('option', $tpl)), '', $tpl);
	$tpl = $STYLE->tags($tpl, array("LIST"=>$list,"MESSAGE"=>$message));
	$output .= $tpl;
?>