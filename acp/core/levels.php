<?php
$tpl = $STYLE->open('acp/levels.tpl');
$page_title .=  ' > Level Manager';
$description = '';
$metatags = '';

if (isset($_POST['addlevel']) && $_POST['addlevel'] == '1') {
	$xp = $secure->clean($_POST['experience']);
	$img = $secure->clean($_POST['image']);
	$type = $secure->clean($_POST['name']);
	$value = $secure->clean($_POST['number']);
	$db->query("INSERT INTO `levels`(`id`, `level`, `experience`, `img`) VALUES ('$value','$type','$xp','$img')");
	$system->message($type . ' add!', 'This level has been added successfully!', './?s=game&module=levels', L_CONTINUE);
}
if (isset($_POST['submit-level'])) {
	$level = $secure->clean($_POST['name']);
	$number = $secure->clean($_POST['number']);
	$xp = $secure->clean($_POST['xp']);
	$img = $secure->clean($_POST['image']);
	if (isset($_POST['id'])) {
		$db->query("UPDATE `levels` SET `level`='$level',`id`='$number',`experience`='$xp',`img`='$img' WHERE id = '" . $_POST['id'] . "'");
		$system->message(L_UPDATED, 'This level has been updated successfully!', './?s=game&module=missions', L_CONTINUE);
	}
}
if (isset($_POST['delete-level'])) {
	if (isset($_POST['id'])) {
		$db->query("DELETE FROM `levels` WHERE id = '" . $_POST['id'] . "'");
		$system->message(L_UPDATED, 'This level has been deleted successfully!', './?s=game&module=missions', L_CONTINUE);
	}
}

$imgs = '';
foreach (scandir('./../images/ranks') as $file) {
	if ($file == "." || $file == "..") continue;
	$imgs .= '<img src="./../images/ranks/' . $file . '" class="profile-level"/>';
	$imgs .= '<input type="radio" name="image" value="' . substr($file, 0, strpos($file, '.')) . '"/>';
}
$which = $db->query("SELECT * FROM `levels` ORDER BY `levels`.`id` ASC");
$them = '';
$temp = $STYLE->getcode('level', $tpl);
if ($which->rowCount() > 0) {
	while ($w = $which->fetch()) {
		$them .= $user->image($w['img'], 'ranks', './../', 'profile-level');
		$them .= $STYLE->tags($temp, array(
			"NUMBER" => $w['id'],
			"NAME" =>	$w['level'],
			"XP" => $w['experience'],
			"IMG" => $w['img']
		));
	}
}
$tpl = str_replace(array($STYLE->getcode('level', $tpl)), '', $tpl);
$tpl = $STYLE->tags($tpl, array(

	"IMAGES" => $imgs,
	"LEVELS" => $them
));