<?php
if (!$account)
	$system->redirect('./');
$STYLE->__add('files', 'JAVA', true, '/inc/mission-page.js');
$tpl = $STYLE->open('missions.tpl');
$global_menu = '';
$tpl = str_replace($global_menu, '', $tpl);
$missions = $db->query("SELECT * FROM `missions` ORDER BY `level`+0 ASC");
$todo = '';
$available = '';
$temp = $STYLE->getcode('mission', $tpl);
$class = 'normal';
$animes = $db->query("SELECT * FROM `animes`");
$listing = array();

while ($anime = $animes->fetch()) {
	$groups = explode(',', $anime['who']);
	if (array_search($account['group'], $groups) === false) continue;
	$listing[$anime['name']][0] = '<div class="anime-listing"><h2 style="
    margin-bottom: 0;">' . $user->image($anime['id'], 'animes', './', 'filter') . $anime['name'] . '</h2>';
	$listing[$anime['name']][1] = 'normal';
}

while ($mission = $missions->fetch()) {
	$curent = $temp;
	$finished = $db->query("SELECT * FROM complete WHERE account = '" . $account['id'] . "' AND mission = '" . $mission['id'] . "'");
	if ($finished->rowCount() > 0)
		$finished = true;
	else
		$finished = false;

	if ($mission['hidden'] == '1') continue;
	$requirements = $db->query("SELECT * FROM requires WHERE mid = '" . $mission['id'] . "' ORDER BY id");
	if ($requirements->rowCount() == 0) continue;
	$level = $db->fetch("SELECT * FROM levels WHERE id = '" . $mission['level'] . "'");
	$color = '';
	if ($account['experience'] < $level['experience']) {
		$color = 'style="color:red;"';
		$curent = str_replace(array($STYLE->getcode('view', $curent)), '', $curent);
	}
	$required = '<span ' . $color . '><span style="text-decoration:underline;">At least:</span> ' . $level['level'] . $user->image($level['img'], 'ranks', './', '" style="left: 0px;position:relative;margin:0 !important;vertical-align:bottom;width: 25px;"') . '</span><br/>';
	if (!empty($mission['missions'])) {
		$check = explode(',', $mission['missions']);
		foreach ($check as $c) {
			$complete = $db->query("SELECT * FROM complete WHERE account = '" . $account['id'] . "' AND mission = '" . $c . "'");
			if ($complete->rowCount() == 0)
				$curent = str_replace(array($STYLE->getcode('view', $curent)), '', $curent);
			$required .= '<em>Mission Completed: ' . $db->fieldFetch('missions', $c, 'name') . '</em><br/>';
		}
	}
	$tasks = '';
	while ($requirement = $requirements->fetch()) {
		$check = $db->fetch("SELECT * FROM progress WHERE mission = '" . $mission['id'] . "' AND account = '" . $account['id'] . "' AND requirement = '" . $requirement['id'] . "'");
		$tasks .= '<li>';
		if (abs($check['count'] - $requirement['count']) == 0 || $finished === true) {
			$check['count'] = $requirement['count'];
			$complete++;
			$tasks .= '<i style="color:#36afec;">';
		}
		if (!empty($requirement['description'])) {
			$tasks .= $requirement['description'];
			goto thend;
		}
		if ($requirement['streak'] == 1 && !empty($requirement['winwith']))
			$tasks .= 'Win ' . $requirement['count'] . ' battles in a row with ' . $db->fieldFetch('characters', $requirement['winwith'], 'name');
		if ($requirement['streak'] == 0 && !empty($requirement['winwith']) && empty($requirement['beatacharacter']))
			$tasks .= 'Win with ' . $db->fieldFetch('characters', $requirement['winwith'], 'name') . ' ' . $requirement['count'] . ' time(s)';
		if ($requirement['streak'] == 0 && !empty($requirement['winwith']) && !empty($requirement['beatacharacter']))
			$tasks .= 'Beat ' . $db->fieldFetch('characters', $requirement['beatacharacter'], 'name') . ' with ' . $db->fieldFetch('characters', $requirement['winwith'], 'name') . ' ' . $requirement['count'] . ' time(s)';
		if ($requirement['streak'] == 0 && empty($requirement['winwith']) && !empty($requirement['beatacharacter']))
			$tasks .= 'Beat ' . $db->fieldFetch('characters', $requirement['beatacharacter'], 'name') . ' ' . $requirement['count'] . ' time(s)';
		thend:
		$tasks .= ' ' . (float)$check['count'] . ' / ' . $requirement['count'];
		if (abs($check['count'] - $requirement['count']) == 0)
			$tasks .= '</i>';
		$tasks .= '</li>';
	}
	$rewards = explode('|', $mission['oncomplete']);
	$rewarded = '';
	foreach ($rewards as $reward) {
		$it = explode(':', $reward);
		switch ($it[0]) {
			case 'C':
				$rewarded .= 'Unlocks <b><a href="/?s=viewtopic&c=' . $it[1] . '">' . $db->fieldFetch('characters', $it[1], 'name') . '</a></b><br/>' . $user->image($it[1], 'characters', './', 'character" style="float:unset;"') . '<br/>';
				break;
			case 'G':
				$rewarded .= $it[1] . '<img src="' . $siteaddress . '/tpl/default/img/gold.png" style="width: 25px;"> <br/>';
				break;
		}
	}
	$animeName = $db->fieldFetch('animes', $mission['who'], 'name');
	if (!isset($listing[$animeName])) $listing[$animeName] = array(0 => "", 1 => "");
	$listing[$animeName][0] .= $STYLE->tags($curent, array(
		"CLASS" => $listing[$animeName][1],
		"ID" => $mission['id'],
		"NAME" => $mission['name'],
		"DESCRIPTION" => $mission['description'],
		"IMG" => $user->image($mission['id'], 'missions', './', 'm fl-l'),
		"REQUIREMENTS" => $required,
		"PROGRESS" => (($finished == true) ? 'Completed' : 'Not completed'),
		"GOALS" => $tasks,
		"REWARDS" => $rewarded
	));
	$listing[$animeName][1] = ($listing[$animeName][1] == 'alternate') ? 'normal' : 'alternate';
}
foreach ($listing as $listed) {
	if (strpos($listed[0], 'At least') === false) continue;
	$todo .= $listed[0] . '</div>';
}
$tpl = str_replace(array($STYLE->getcode('mission', $tpl), $global_menu), '', $tpl);
$tpl = $STYLE->tags($tpl, array(
	"MISSIONS" => $todo
));
$output .= $tpl;