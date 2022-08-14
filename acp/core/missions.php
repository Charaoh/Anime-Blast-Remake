<?php
$tpl = $STYLE->open('acp/missions.tpl');
if (isset($_GET['change']) && $_GET['change'] == 'true') {
	$id = (isset($_GET['id'])) ? $secure->clean($_GET['id']) : '';
	if (!empty($id)) {
		if (isset($_POST['Avi'])) {
			$image = $_FILES['image']['name'];
			if ($image) {
				$filename = stripslashes($_FILES['image']['name']);
				$extension = $user->getExtension($filename);
				$extension = strtolower($extension);
				// Make sure it is an image
				if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
					$system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=missions&change=true&id=' . $id, L_CONTINUE);
				}
				// Delete possible existing avatar
				$user->deletethisfile("./../images/missions/" . $id);
				$image_name = time() . '.' . $extension;
				$newname = "./../images/missions/" . $id . ".$extension";
				$copied = copy($_FILES['image']['tmp_name'], $newname);
				$size = filesize("$newname");
				list($width, $height) = getimagesize("$newname");
				unlink($_FILES['image']['tmp_name']);
			}
			if (!isset($copied)) {
				$system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=missions&change=true&id=' . $id, L_CONTINUE);
			} else
                if ($size > $system->data('avatar_filesize')) {
				// Prevent Avatar over File size
				unlink("$newname");
				$error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
				$system->message(L_ERROR, $error_message, './?s=website&module=missions&change=true&id=' . $id, L_CONTINUE);
			} else {
				$system->message(L_UPDATED, 'Mission picture updated!', './?s=website&module=missions&change=true&id=' . $id, L_CONTINUE);
			}
		}
		$tpl = $STYLE->getcode('change', $tpl);
		$tpl = $STYLE->tags($tpl, array(
			"AVATAR" => $user->image($id, 'missions', './../', 'm'),
			"NAME" => $db->fieldFetch('missions', $id, 'name')
		));
	} else {
		$system->redirect('./?s=website&module=missions');
	}
} elseif (isset($_GET['new']) && $_GET['new'] == 'true') {
	if (isset($_POST['submit'])) {
		$title = $secure->clean($_POST['name']);
		$description = $secure->clean($_POST['description']);
		$requires = $secure->clean($_POST['required']);
		$level = $secure->clean($_POST['level']);
		$reward = $secure->clean($_POST['reward']);
		$who = '';
		if (isset($_POST['whichAnime']))
			$who = $secure->clean($_POST['whichAnime']);
		$hidden = '0';
		if (isset($_POST['hiddenMission']))
			$hidden = $secure->clean($_POST['hiddenMission']);
		$db->query("INSERT INTO `missions` (`name`, `description`, `level`, `requires`, `oncomplete`, `hidden`, `who`) VALUES ('$title','$description','$level','$requires','$reward', '$hidden', '$who')");
		$mission = $db->fetch("SELECT * FROM `missions` ORDER BY id DESC LIMIT 1;");
		$mission = $mission['id'];
		$requires = explode(',', $requires);
		foreach ($requires as $requireme) {
			$db->query("UPDATE `requires` SET `mid`='$mission' WHERE id = '" . $requireme . "'");
		}
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			$image = $_FILES['image']['name'];
			if ($image) {
				$filename = stripslashes($_FILES['image']['name']);
				$extension = $user->getExtension($filename);
				$extension = strtolower($extension);
				// Make sure it is an image
				if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
					$system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=missions&new=true', L_CONTINUE);
				}
				$id = $db->fetch("SELECT * FROM `missions` ORDER BY id DESC LIMIT 1;");
				$id = $id['id'];
				$image_name = time() . '.' . $extension;
				$newname = "./../images/missions/" . $id . ".$extension";
				$copied = copy($_FILES['image']['tmp_name'], $newname);
				$size = filesize("$newname");
				list($width, $height) = getimagesize("$newname");
				unlink($_FILES['image']['tmp_name']);
			}
			if (!isset($copied)) {
				$system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=missions&new=true', L_CONTINUE);
			} else
                if ($size > $system->data('avatar_filesize')) {
				// Prevent Avatar over File size
				unlink("$newname");
				$error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
				$system->message(L_ERROR, $error_message, './?s=website&module=missions&new=true', L_CONTINUE);
			}
		}
		$system->message('Mission ready!', 'This mission has been inserted successfully!', './?s=website&module=missions', L_CONTINUE);
	}
	$tpl = $STYLE->getcode('mission', $tpl);
	$tpl = str_replace(array($STYLE->getcode('tag', $tpl)), '', $tpl);
	$tpl = str_replace(array($STYLE->getcode('tag', $tpl)), '', $tpl);
	$animeSelect = '<select id="whichAnime" name="whichAnime">';
	$animes = $db->query("SELECT * FROM animes");
	while ($anime = $animes->fetch()) {
		$animeSelect .= '<option value="' . $anime['id'] . '">' . $anime['name'] . '</option>';
	}
	$animeSelect .= '</select>';
	$tpl = $STYLE->tags($tpl, array(
		"WHO" => $animeSelect,
		"HIDDEN" => '',
		"PICTURE" => '',
		"DESCRIPTION" => '',
		"NAME" => '',
		"LEVEL" => '',
		"REQUIRE" => '',
		"ID" => 'NEW',
		"OC" => '',
		"PREVIOUSLY" => '',
		"WHAT" => 'SUBMIT',
		"ROW" => 'normal'
	));
	$tpl = '<div class="transparent">Missions > New<div style="float:right;"><a href="./?s=website&module=missions"/>Go back to missions page</a></div></div>
<div class="content">' . $tpl . '</div>';
} else {
	if (isset($_POST['addrequire']) && $_POST['addrequire'] == '1') {
		$counter = (isset($_POST['counter'])) ? $secure->clean($_POST['counter']) : 0;
		$description = (isset($_POST['description'])) ? $secure->clean($_POST['description']) : '';
		$win = (isset($_POST['win'])) ? $secure->clean($_POST['win']) : '';
		$beata = (isset($_POST['beata'])) ? $secure->clean($_POST['beata']) : '';
		$streak = (isset($_POST['streak'])) ? $secure->clean($_POST['streak']) : 0;
		$db->query("INSERT INTO `requires`(`streak`, `beatacharacter`, `winwith`, `count`, `description`) VALUES ('$streak','$beata','$win','$counter','$description')");
		$system->message('Requirement added!', 'This requirement has been added successfully!', './?s=website&module=missions', L_CONTINUE);
	}
	if (isset($_POST['submit'])) {
		$title = $secure->clean($_POST['name']);
		$description = $secure->clean($_POST['description']);
		$requires = $secure->clean($_POST['required']);
		$level = $secure->clean($_POST['level']);
		$reward = $secure->clean($_POST['reward']);
		$previous = $secure->clean($_POST['previous']);
		$who = '';
		if (isset($_POST['whichAnime']))
			$who = $secure->clean($_POST['whichAnime']);
		$hidden = '0';
		if (isset($_POST['hiddenMission']))
			$hidden = $secure->clean($_POST['hiddenMission']);
		if (isset($_POST['id'])) {
			$requires = explode(',', $requires);
			foreach ($requires as $requireme) {
				$db->query("UPDATE `requires` SET `mid`='" . $_POST['id'] . "' WHERE id = '" . $requireme . "'");
			}
			$requires = implode(',', $requires);
			$db->query("UPDATE `missions` SET `name`='$title',`description`='$description',`missions`='$previous',`requires`='$requires',`level`='$level',`oncomplete`='$reward',`who`='$who',`hidden`='$hidden' WHERE id = '" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This mission has been updated successfully!', './?s=website&module=missions', L_CONTINUE);
		}
	}

	if (isset($_POST['submit-required'])) {
		$streak = $secure->clean($_POST['streak']);
		$beata = $secure->clean($_POST['beata']);
		$win = $secure->clean($_POST['win']);
		$count = $secure->clean($_POST['counter']);
		$description = $secure->clean($_POST['description']);
		if (isset($_POST['id'])) {
			$db->query("UPDATE `requires` SET `streak`='$streak',`beatacharacter`='$beata',`winwith`='$win',`count`='$count',`description`='$description' WHERE id = '" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This requirement has been updated successfully!', './?s=website&module=missions', L_CONTINUE);
		}
	}
	if (isset($_POST['delete-required'])) {
		if (isset($_POST['id'])) {
			$db->query("DELETE FROM `requires` WHERE id = '" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This requirement has been deleted successfully!', './?s=website&module=missions', L_CONTINUE);
		}
	}
	if (isset($_POST['delete'])) {
		if (isset($_POST['id'])) {
			$db->query("DELETE FROM `missions` WHERE id = '" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This mission has been deleted successfully!', './?s=website&module=missions', L_CONTINUE);
		}
	}

	if (isset($_GET['requirements'])) {
		$which = $db->query("SELECT * FROM `requires`");
		$required = '';
		$temp = $STYLE->getcode('require', $tpl);
		if ($which->rowCount() > 0) {
			while ($w = $which->fetch()) {
				$required .= $STYLE->tags($temp, array(
					"ID" => $w['id'],
					"STREAK" => $w['streak'],
					"BEAT" =>	$w['beatacharacter'],
					"WIN" => $w['winwith'],
					"COUNT" => $w['count'],
					"DESCRIPTION" => $w['description']
				));
			}
		}
		$tpl = $STYLE->getcode('requirements', $tpl);
		$tpl = $STYLE->tags($tpl, array(
			"REQUIREMENTS" => $required
		));
	} else {
		$missions = $db->query("SELECT * FROM missions");
		$available = '';
		$temp = $STYLE->getcode('mission', $tpl);
		if ($missions->rowCount() > 0) {
			$row = 'alternate';
			while ($mission = $missions->fetch()) {
				$animeSelect = '<select id="whichAnime" name="whichAnime">';
				$animes = $db->query("SELECT * FROM animes");
				while ($anime = $animes->fetch()) {
					$check = "";
					if ($anime['id'] == $mission['who'])
						$check = " selected=true";
					$animeSelect .= '<option value="' . $anime['id'] . '"' . $check . '>' . $anime['name'] . '</option>';
				}
				$animeSelect .= '</select>';
				// WHAT ANIME ?
				if ($row == 'normal')
					$row = 'alternate';
				else
					$row = 'normal';
				$hidden = "";
				if ($mission['hidden'] == '1') {
					$hidden = ' checked=true';
				}
				$temp = str_replace(array($STYLE->getcode('picture', $temp)), '', $temp);
				$available .= $STYLE->tags($temp, array(
					"WHO" => $animeSelect,
					"HIDDEN" => $hidden,
					"PICTURE" => $user->image($mission['id'], 'missions', './../', 'm'),
					"DESCRIPTION" => $mission['description'],
					"PREVIOUSLY" => $mission['missions'],
					"NAME" => $mission['name'],
					"LEVEL" => $mission['level'],
					"REQUIRE" => $mission['requires'],
					"ID" => $mission['id'],
					"OC" => $mission['oncomplete'],
					"WHAT" => 'UPDATE',
					"ROW" => $row
				));
			}
		} else {
			$available = 'No missions!';
		}
		$tpl = $STYLE->getcode('missionspage', $tpl);
		//$tpl = str_replace(array($STYLE->getcode('mission', $tpl), $STYLE->getcode('change', $tpl), $STYLE->getcode('level', $tpl), $STYLE->getcode('require', $tpl)), '', $tpl);
		$tpl = $STYLE->tags($tpl, array(
			"MISSIONS" => $available
		));
	}
}