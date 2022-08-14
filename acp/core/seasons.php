<?php
$tpl = $STYLE->open('acp/season.tpl');
$season = $db->fetch("SELECT * FROM `season` WHERE end = '' LIMIT 1;");
if (isset($_GET['end']) && $_GET['end'] == 'true') {

	if (!isset($_POST['confirmed'])) {
		$system->confirm('End ' . $season['season'], 'Are you sure?', './?s=website&module=season');
	}
	// Give out all the rewards and mail the users about them
	$onend = explode(',', $season['rewards']);
	$ranked = '';
	foreach ($onend as $key => $reward) {
		if (empty($reward)) continue;
		$accounts = $db->query("SELECT * FROM `accounts` ORDER BY `experience` DESC");
		$number = 1;
		while ($member = $accounts->fetch()) {
			$ranked .= 'a' . $member['id'] . ',r' . $number . ',w' . $member['wins'] . ',l' . $member['loses'] . ',e' . $member['experience'] . ',s' . $member['highest_streak'];
			if (strpos($reward, 'e') !== false) {
				if ($member['experience'] >= substr($reward, strpos($reward, '/') + 1)) {
					$item = $db->fetch("SELECT * FROM `items` WHERE id='" . substr($reward, strpos($reward, 'e') + 1, strpos($reward, '/') - 1) . "'");
					switch ($item['name']) {
						case 'character':
							$characters = $member['characters'] . ',' . $item['value'];
							$db->query("UPDATE `accounts` SET `characters`='$characters' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with the character <b>' . $db->fieldFetch('characters', $item['value'], 'name') . '</b>');
							break;
						case 'bc':
							$gold = $member['gold'] + $item['value'];
							$db->query("UPDATE `accounts` SET `gold`='$gold' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with <b>' . $item['value'] . '</b> blast coins!');
							break;
						case 'xp':
							$xp = $member['experience'] + $item['value'];
							$db->query("UPDATE `accounts` SET `experience`='$xp' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with <b>' . $item['value'] . '</b> experience points!');
							break;
					}
				}
			} else {
				if ($number == substr($reward, strpos($reward, '/') + 1)) {
					$item = $db->fetch("SELECT * FROM `items` WHERE id='" . substr($reward, strpos($reward, 'e') + 1, strpos($reward, '/') - 1) . "'");
					switch ($item['name']) {
						case 'character':
							$characters = $member['characters'] . ',' . $item['value'];
							$db->query("UPDATE `accounts` SET `characters`='$characters' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with the character <b>' . $db->fieldFetch('characters', $item['value'], 'name') . '</b>');
							break;
						case 'bc':
							$gold = $member['gold'] + $item['value'];
							$db->query("UPDATE `accounts` SET `gold`='$gold' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with <b>' . $item['value'] . '</b> blast coins!');
							break;
						case 'xp':
							$xp = $member['experience'] + $item['value'];
							$db->query("UPDATE `accounts` SET `experience`='$xp' WHERE id = '" . $member['id'] . "'");
							$system->mail($member['id'], '-1', $season['season'], $season['season'] . ' has ended! Congratulations you have been rewarded with <b>' . $item['value'] . '</b> experience points!');
							break;
					}
				}
			}
			$number++;
		}
	}
	$db->query("UPDATE `accounts` SET `wins`='0',`loses`='0',`streak`='0',`highest_streak`='0',`experience`='0',`gold`='0',`equiped_team`='0',`cookies`='0',`team`='0'");
	$db->query("UPDATE `season` SET `end`='" . time() . "', `ranked`='$ranked' WHERE id = '" . $season['id'] . "'");
	$db->query("INSERT INTO `season`(`season`,`start`) VALUES ('Season " . ($season['id'] + 1) . "','" . time() . "')");
	$system->message('Ended ' . $season['season'], 'Season ended! rewards have been sent..', './?s=website&module=season', L_CONTINUE);
}
if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	$onend = explode(',', $season['rewards']);
	foreach ($onend as $key => $reward) {
		if ($key == $id)
			unset($onend[$key]);
	}
	$onend = implode(',', $onend);
	$db->query("UPDATE `season` SET `rewards`='$onend' WHERE id = '" . $season['id'] . "'");
	$system->message($season['season'], 'Reward has been removed from this season', './?s=website&module=season', L_CONTINUE);
}
if (isset($_POST['submit'])) {
	$type = $secure->clean($_POST['type']);
	$item = $secure->clean($_POST['item']);
	$value = $secure->clean($_POST['value']);
	$rewards = $season['rewards'];
	if (!empty($rewards))
		$rewards .= ',';
	$rewards .= (($type == 'exp') ? 'e' : 'r') . $item . '/' . $value;
	$db->query("UPDATE `season` SET `rewards`='$rewards' WHERE id = '" . $season['id'] . "'");
	$system->message($season['season'], 'Reward has been inserted for this season', './?s=website&module=season', L_CONTINUE);
}
$onend = explode(',', $season['rewards']);
$result = '';
foreach ($onend as $key => $reward) {
	if (empty($reward)) continue;
	if (strpos($reward, 'e') !== false)
		$result .= 'Reward item id ' . substr($reward, strpos($reward, 'e') + 1, strpos($reward, '/') - 1) . ' for players with experience ' . substr($reward, strpos($reward, '/') + 1);
	else
		$result .= 'Reward item id ' . substr($reward, strpos($reward, 'r') + 1, strpos($reward, '/') - 1) . ' for player ranked ' . substr($reward, strpos($reward, '/') + 1);
	$result .= '<div class="fl-r"><a href="./?s=website&module=season&remove=' . $key . '">Remove this reward?</a></div><br/>';
}
if (empty($result))
	$result = 'No rewards assigned currently';

$tpl = $STYLE->tags($tpl, array(
	"NAME" => $season['season'],
	"START" => $system->time($season['start']),
	"SEASON" => $result
));