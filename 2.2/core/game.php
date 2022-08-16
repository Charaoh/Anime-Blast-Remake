<?php

if (!defined('SITE')) {
	require('index.php');
	exit;
}

if ($system->group_permission($account['group'], 'ga') == '0')
	$system->redirect('./');
// As always handle structure variables
$tpl = '';
$mode = '';
$game = new game();
if (isset($_GET['mode'])) {
	$mode = $secure->clean($_GET['mode']);
}
// Reconnect to closed match
$check = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' ORDER BY `id` DESC LIMIT 1;");
if ($check->rowCount()  > 0 && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $where['mode'] !== 'battle') {
	$check = $check->fetch();
	$me = ($account['id'] == $check['id-0']) ? '0' : '1';
	if ($check['check-' . $me] == '0')
		$system->redirect('./battle');
}

// Prevent f5
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	$searching = $db->query("SELECT * FROM `matches` WHERE `id-0` ='" . $account['id'] . "' AND `check` = '' AND `timeend` = ''");
	if ($searching->rowCount() > 0) {
		$db->query("DELETE FROM `matches` WHERE `matches`.`id-0` = '" . $account['id'] . "' AND `matches`.`timeend` = ''");
	}
}


if ($mode == 'selection') {

	$p = '';
	$characters = $db->query("SELECT * FROM `characters` ORDER BY who ASC, sort DESC");
	$c = '';
	$filters = $db->query("SELECT * FROM `animes` ORDER BY id ASC");
	$filter = '';
	if ($filters->rowCount() > 0) {
		while ($f = $filters->fetch()) {
			$who = explode(',', $f['who']);
			$key = array_search($account['group'], $who);
			if (isset($key) && $who[$key] == $account['group']) {
				if ($template !== 'default')
					$filter .= $user->image($f['id'], 'animes', './', 'filter', preg_replace('/\s+/', '_', $f['name']));
				else
					$filter .= $user->image($f['id'], 'animes', './', 'filter', $f['name']);
			}
		}
	}

	if ($characters->rowCount() > 0) {

		while ($character = $characters->fetch()) {

			// Using account var update every loop since we update fields
			$account = $db->fetch("SELECT * FROM accounts WHERE id='" . $account['id'] . "'");

			// Check if a certain group can't see the character
			if (!empty($character['who'])) {
				$who = $db->fetch("SELECT * FROM animes WHERE id='" . $character['who'] . "'");
				$who = explode(',', $who['who']);
				$key = array_search($account['group'], $who);
				if (isset($key) && $who[$key] != $account['group']) {

					// If character that is not unlocked and is on your team, remove it
					$team = explode(',', $account['team']);
					foreach ($team as $me => $val) {
						if ($val == $character['id']) {
							unset($team[$me]);
						}
					}
					$team = implode(',', $team);
					$db->query("UPDATE accounts SET team = '" . $team . "' WHERE id = '" . $account['id'] . "'");
					continue;
				}
			}

			$skills = explode(',', $character['skills']);
			$f2 = array(preg_replace('/\s+/', '_', $db->fieldFetch('animes', $character['who'], 'name')));
			$replacements = array();
			foreach ($skills as $skill) {
				$s = $skill;
				$classes = explode(',', $db->fieldFetch('skills', $s, 'classes'));
				foreach ($classes as $class) {
					if (array_search($db->fieldFetch('classes', $class, 'name'), $f2) !== false) continue;
					$f2[] = $db->fieldFetch('classes', $class, 'name');
				}
				$effects = explode(',', $db->fieldFetch('skills', $s, 'effects'));
				foreach ($effects as $effect) {
					$e = $db->fetch("SELECT * FROM `effects` WHERE `id` ='" . $effect . "'");
					if ($e) {
						foreach ($e as $item => $value) {
							if (empty($item) || is_numeric($item))
								continue;
							if (empty($value))
								continue;
							if ($item == 'id' || $item == 'duration' || $item == 'target' || $item == 'description')
								continue;
							if ($item == 'replace' || $item == 'if') {
								$replaced = explode('|', $value);
								foreach ($replaced as $replacing) {
									$replacements[] = $replacing;
								}
							}
							if (array_search($item, $f2) !== false)
								continue;
							$f2[] = $item;
						}
					}
				}
			}
			if (!empty($replacements)) {
				foreach ($replacements as $key => $s) {
					$classes = explode(',', $db->fieldFetch('skills', $s, 'classes'));
					foreach ($classes as $class) {
						if (array_search($db->fieldFetch('classes', $class, 'name'), $f2) !== false) continue;
						$f2[] = $db->fieldFetch('classes', $class, 'name');
					}
					$effects = explode(',', $db->fieldFetch('skills', $s, 'effects'));
					foreach ($effects as $effect) {
						$e = $db->fetch("SELECT * FROM `effects` WHERE `id` ='" . $effect . "'");
						if ($e) {
							foreach ($e as $item => $value) {
								if (empty($item) || is_numeric($item))
									continue;
								if (empty($value))
									continue;
								if ($item == 'id' || $item == 'duration' || $item == 'target' || $item == 'description')
									continue;
								if ($item == 'replace' || $item == 'if') {
									$replaced = explode('|', $value);
									foreach ($replaced as $replacing) {
										$replacements[] = $replacing;
									}
								}
								if (array_search($item, $f2) !== false)
									continue;
								$f2[] = $item;
							}
						}
					}
				}
				unset($s);
			}

			$f2 = implode(' ', $f2);


			// Start decompiling the characters and checking if there unlocked or in the team
			$ucharacters = explode(',', $account['characters']);
			$key = array_search($character['id'], $ucharacters);
			if (isset($key) && $ucharacters[$key] == $character['id']) {
				$who = $db->fetch("SELECT * FROM animes WHERE id='" . $character['who'] . "'");
				$c .= '<div class="' . $f2 . '">' . $user->image($character['id'], 'characters', './', 'character', '', $who['name'])  . '</div>';
			} else {
				// If character that is not unlocked and is on your team, remove it
				$team = explode(',', $account['team']);
				foreach ($team as $me => $val) {
					if ($val == $character['id']) {
						unset($team[$me]);
					}
				}
				$team = implode(',', $team);
				$db->query("UPDATE accounts SET team = '" . $team . "' WHERE id = '" . $account['id'] . "'");
				$who = $db->fetch("SELECT * FROM animes WHERE id='" . $character['who'] . "'");
				// Check if group of only can be seen if unlocked
				if ($system->data('Only') == $character['who'])
					continue;
				$c .= '<div class="' . $f2 . '"><p class="locked">' . $user->image($character['id'], 'characters', './', 'character', '', $who['name']) . '</p><span class="lock"></span></div>';
			}
		}
	}
	$rank = $db->fetch("SELECT * FROM levels WHERE experience < '" . ($account['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");
	$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
	$ladderrank = 'Not ranked in ladder';
	$key = 1;
	$max = $db->fetch("SELECT * FROM levels ORDER BY experience DESC LIMIT 1");
	while ($me = $ranked->fetch()) {
		if ($me['id'] == $account['id']) {
			$ladderrank = 'Ladderrank #' . $key;
			if ($key == 1) {
				if ($max['id'] == $rank['id']) {
					$rank['img'] = '1st';
					$ladderrank = 'The Champion!';
				}
			}
		}
		$key++;
	}

	$avatar = $user->image($account['id'], 'avatars');
	$animes = '';
	$cls = '';
	$effects = '';
	if ($template !== 'default') {
		$animes = $filter;
		$filter = '';
		$cls = $db->query("SELECT * FROM `classes`");
		if ($cls->rowCount() > 0) {
			$store = '';
			$countme = 0;
			while ($clas = $cls->fetch()) {
				if ($countme == 2) {
					$store .= '<br class="clearfix">';
					$countme = 0;
				}
				$store .= '<p id="' . $clas['name'] . '" class="class" style="background:' . $clas['color'] . ';">' . $clas['name'] . '</p>';
				$countme++;
			}
			$cls = $store;
		} else {
			$cls = '<p>No classes found</p>';
		}
		//$effects = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'effects'");
		$effects = $db->query("SHOW COLUMNS FROM effects");
		if ($effects->rowCount() > 0) {
			$store = '';
			// Change the name that's shown for the effect
			$prettyEffect = array("remove" => "Skill Remove", "invul" => "Invulnerability", "destroy-dd" => "Destructible Defense Destruction", "targetme" => "Change Target", "no-death" => "Immortality", "dr" => "Damage Reduction", "dd" => "Destructible Defense", "removeM" => "Remove Mana", "manaGain" => "Mana Gain", "show-mana" => "Expose ManaBar", "drainM" => "Drain Mana");
			// Effects to not show in the filter container
			$ignore = array("ally", "id", "duration", "increase-manaRem", "externalManaGain", "reverseTargetToCaster", "condition", "target", "set-skill", "key", "also", "ignore", "count", "self", "if", "not", "reset", "no-resurrect", "specify", "no-ignore", "description");
			$stored = array();
			while ($effect = $effects->fetch()) {
				if (array_search($effect['Field'], $ignore) !== FALSE) continue;
				if (array_search($effect['Field'], $stored, TRUE)) continue;
				$pretty = $effect['Field'];
				if (isset($prettyEffect[$pretty]))
					$pretty = $prettyEffect[$pretty];
				$store .= '<p id="' . $effect['Field'] . '">' . $pretty . '</p>';
				$stored[] = $effect['Field'];
			}
			$effects = $store;
		} else {
			$effects = '<p>No effects found</p>';
		}
		$wings = '';
		$avatar = $user->image($account['id'], 'avatars', './', 'avatar');
		if (file_exists('./images/ranks/new/' . $rank['img'] . '-1.png'))
			$wings = $user->image($rank['img'] . '-1', 'ranks/new', './', 'ranks wings');
		if ($rank['img'] == '1st')
			$wings .= $user->image($rank['img'] . '-1', 'ranks/new', './', 'ranks wings right');
		if (!isset($account['border'])) {
			$avatar = $wings . $user->image($rank['img'] . '-2', 'ranks/new', './', 'ranks back') . $avatar . ($rank['img'] == '7' || $rank['img'] == '1st' ? $user->image($rank['img'] . '-3', 'ranks/new', './', 'ranks front" style="margin-top: 38%;left: -11px;"') : $user->image($rank['img'] . '-3', 'ranks/new', './', 'ranks front'));
		} else {
			$avatar = $user->border($account, $avatar);
		}
	}
	$account = $db->fetch("SELECT * FROM accounts WHERE id='" . $account['id'] . "'");
	$clan = $db->fetch("SELECT * FROM `clan-members` WHERE `account_id` ='" . $account['id'] . "'");
	if ($clan) {
		$us = $clan['clan_id'];
		$clan = '<a href="./clan/profile/' . $db->fieldFetch('clans', $us, 'name') . '" target="_blank">' . $user->image($us, 'clans', './', 'clan') . $db->fieldFetch('clans', $us, 'name') . '</a>';
	} else {
		$clan = 'Clanless';
	}


	// Get teams
	$teams = $db->query("SELECT * FROM `teams` WHERE `account` = '" . $account['id'] . "' ORDER BY `teams`.`id` DESC");
	if ($teams->rowCount() > 0) {
		$ttemp = '';
		$selected = '';
		while ($tt = $teams->fetch()) {

			if ($tt['id'] == $account['equiped_team'])
				$selected = '<p id="team" class="' . $tt['id'] . ' selected">' . $tt['name'] . ' <br>' . $tt['wins'] . ' - ' . $tt['loses'] . ' ( ' . $tt['highest_streak'] . ' )<span class="delete">Remove</span></p>';
			else
				$ttemp .= '<p id="team" class="' . $tt['id'] . '">' . $tt['name'] . ' <br>' . $tt['wins'] . ' - ' . $tt['loses'] . ' ( ' . $tt['highest_streak'] . ' )<span class="delete">Remove</span></p>';
		}
		$teams = $selected . $ttemp;
	} else {
		$teams = '<p>Manage your teams here!</p>';
	}

	$equiped = '';

	$equiped = explode(',', $account['team']);
	foreach ($equiped as &$e) {
		if (empty($e))
			continue;
		$e = $user->image($e, 'characters', './', 'character');
	}
	if (empty($account['equiped_team']) && count($equiped) == 3) {
		$equiped[] = '<img class="shuffle" src="./tpl/beta/css/images/shuffle.png"><img class="save" src="./tpl/beta/css/images/save.png">';
	}
	$equiped = implode('', $equiped);
	if (empty($equiped))
		$equiped = '<p class="team-text">No team</p>';
	if ($template !== 'default') {
		$item_tpl = '<div id="{ID}" class="i">
						<span class="ititle"><span class="discount">{DISCOUNT}</span>{TITLE}</span>
						<p class="price"><span>{PRICE}</span></p>
						<div>{PREV}</div>
					</div>';

		$shop = $db->query("SELECT * FROM sales WHERE seller = '-1' ORDER BY id DESC");
		$consale = '';
		$gsale = '';
		$ssale = '';
		if ($shop->rowCount() > 0) {

			while ($i = $shop->fetch()) {
				$item = $item_tpl;
				// Title with experation data
				$title = $i['title'];
				// Characters ? show pictures of them
				$images = '';
				$type = false;
				$t = explode(',', $i['items']);
				foreach ($t as $k => $it) {
					$it = $db->query("SELECT * FROM items WHERE id = '" . $it . "'");
					if ($it->rowCount() > 0) {
						$it = $it->fetch();
						switch ($it['name']) {
							case 'character':
								$character = $db->fetch("SELECT * FROM characters WHERE id='" . $it['value'] . "'");
								if ($character) {
									$mycharacters = explode(',', $account['characters']);
									$unlocked = '';
									if (array_search($character['id'], $mycharacters) !== false) {
										unset($t[$k]);
										$unlocked = 'locked';
									}
									$images .= $user->image($character['id'], 'characters', './', $unlocked);
									$images .= $user->image($character['id'], 'characters/slanted', './', $unlocked . ' alts');
								}
								break;
							default:
								$type = $it['name'];
								// Check if I have this item otherwise lets empty boys
								// Item exists, check my inventory if I have it
								$ihave = $db->query("SELECT * FROM `inventory` WHERE `account` = '" . $account['id'] . "' AND `item` = '" . $it['id'] . "'");
								if ($ihave->rowCount() > 0)
									unset($t[$k]);
								break;
						}
					}
				}
				if (empty($t))
					continue;

				// Check for discount!
				$discount = $system->data('discount');
				if (!empty($discount)) {
					// There is a discount happening!!! 
					$percent = str_replace('%', '', $discount);
					$new = $i['value'] - ($i['value'] * ($percent / 100));
					$i['value'] = $new;
				}

				$final = $STYLE->tags($item, array(
					"ID" => $i['id'],
					"TITLE" => $i['description'],
					"DISCOUNT" => ((!empty($discount)) ? $discount . ' OFF!' : ''),
					"PREV" => (!empty($i['thumbnail']) ? '<img class="thumbnail" src="' . $i['thumbnail'] . '"/>' : $images),
					"PRICE" => $i['value']
				));

				if ($type === false)
					$consale .= $final;
				elseif ($type = 'sfx')
					$ssale .= $final;
				else
					$gsale .= $final;
			}
		}

		// List Templates
		if (empty($account['tpl']))
			$account['tpl'] = $system->data('template');
		$user_template = $account['tpl'];
		$skipT = true;
		$skipA = true;
		if ($system->data('usertemplate') == '1')
			$skipT = false;
		elseif ($system->group_permission($account['group'], 'templates') !== '0')
			$skipT = false;
		$temps = '';
		$directory = @opendir('./tpl/');
		while ($file = readdir($directory)) {
			if ($skipT === true) break;

			if ($file != "index.php" && $file != "." && $file != "..") {
				if ($file !==  $system->data('template')) {
					// Check if bought
					$check = $db->query("SELECT * FROM `items` WHERE `name` = 'template' AND `value` = '" . $file . "'");
					if ($check->rowCount() > 0) {
						$check = $check->fetch();
						$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '" . $account['id'] . "' AND `item` = '" . $check['id'] . "'");
						if ($checkInventory->rowCount() === 0) {
							continue;
						}
					} else {
						continue;
					}
				}
				$temps .= '<option value="' . $file . '">' . $file . '</option>';
			}
		}
		if ($system->group_permission($account['group'], 'sfx') !== '0')
			$skipA = false;
		$sfx = '';
		$directory = @opendir('./sound/');
		while ($file = readdir($directory)) {
			if ($skipA === true) break;
			if ($file != "index.php" && $file != "." && $file != "..") {
				if ($file !==  $system->data('default-sfx')) {
					// Check if bought

					$check = $db->query("SELECT * FROM `items` WHERE `name` = 'sfx' AND `value` = '" . $file . "'");
					if ($check->rowCount() > 0) {
						$check = $check->fetch();
						$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '" . $account['id'] . "' AND `item` = '" . $check['id'] . "'");
						if ($checkInventory->rowCount() === 0) {
							continue;
						}
					} else {
						continue;
					}
				}
				$sfx .= '<option value="' . $file . '">' . $file . '</option>';
			}
		}
	}
	$mybg = '';
	if (!empty($account['bg'])) {
		if (strpos($account['bg'], ')') !== false && strlen($account['bg']) > 2)
			$mybg = substr($account['bg'], 1, strpos($account['bg'], ')') - 1);
		if (strlen($mybg) > 0)
			$mybg = ' style="background: url(' . $mybg . ') 0 0 no-repeat; background-size:cover;"';
	}
	$tips = $db->query("SELECT * FROM tips ORDER BY id DESC");
	$tipsy = '';
	if ($tips->rowCount() > 0) {
		while ($i = $tips->fetch()) {
			$tipsy .= '<p class="tip">Tips: ' . $i['tipsy'] . '</p>';
		}
	}
	if (empty($tipsy))
		$tipsy = '<p class="tip">Tips: Have a good day mate!</p>';

	$s = $STYLE->open('selection.tpl');
	// Event
	// Santa items =>
	$inventory = $game->getInventory();
	if ($account['notified'] == '1') {
		$s = str_replace(array($STYLE->getcode('event', $s)), '', $s);
	}

	// Check if AI bot is enabled
	if ($system->data('AI_Battles') == 'undefined' || $system->data('AI_Battles') == "")
		$s = str_replace(array($STYLE->getcode('AI', $s)), '', $s);


	$s = $STYLE->tags($s, array(
		"TIPS" => $tipsy,
		"POPUP" => $p,
		"CHARACTERS" => $c,
		"AVATAR" => $avatar,
		"SHORTY" => ((strlen($account['name']) > 10) ? substr($account['name'], 0, 9) . '...' : $account['name']),
		"USERNAME" => $account['name'],
		"RATIO" => 'Ratio ' . $account['wins'] . ' - ' . $account['loses'] . ' ( ' . (($account['streak'] < 0) ? (float)$account['streak'] : '+ ' . (float)$account['streak']) . ' )',
		"GOLD" => $account['gold'] . ' BC',
		// Christmass event
		"COOKIES" => $account['cookies'],
		"INVENTORY" => $inventory,
		"RNAME" => $rank['level'],
		"CLAN" => $clan,
		"TEAMS" => $teams,
		"RANK" => $user->image($rank['img'], 'ranks', './', 'rank'),
		"LR" => $ladderrank,
		"GROUP" => $user->specialRank($account['id']),
		"FILTERS" => $filter,
		"ANIMES" => $animes,
		"CLASSES" => $cls,
		"EFFECTS" => $effects,
		"EQUIPED" => $equiped,
		"CSALES" => (empty($consale) ? '<p style="transform: skewX(30deg);">No items available to buy.</p>' : $consale),
		"SSALES" => (empty($ssale) ? '<p style="transform: skewX(30deg);">No items available to buy.</p>' : $ssale),
		"GSALES" => (empty($gsale) ? '<p style="transform: skewX(30deg);">No items available to buy.</p>' : $gsale),
		"BG" => $mybg,
		"CIBG" => (!empty($account['bg']) ? substr($account['bg'], strpos($account['bg'], ')') + 1) : ''),
		"CSBG" => (!empty($account['bg']) ? substr($account['bg'], 1, strpos($account['bg'], ')') - 1) : ''),
		"TEMPLATES" => $temps,
		"SFX" => $sfx
	));
	$output .= $s;
	if ($system->isMobile() && $template !== 'default') {
		$STYLE->__add('files', 'CSS', '', '/css/smobile.css');
	}
	$STYLE->__add('files', 'JAVA', '', '/java/jquery.js?v=');
	$STYLE->__add('files', 'JAVA', '', '/java/jquery-ui.js');
	$STYLE->__add('files', 'JAVA', '', '/java/jquery.ui.touch-punch.min.js');
	$STYLE->__add('files', 'JAVA', '', '/java/howler.js');
	$STYLE->__add('files', 'JAVA', '', '/java/player.js');
	$STYLE->__add('files', 'JAVA', '', '/java/sweetalert.js');
	$STYLE->__add('files', 'CSS', '', '/css/selection.css');
	$STYLE->__add('files', 'JAVA', '', '/java/2.js');
	$STYLE->__add('files', 'JAVA', '', '/java/1.js');
	$STYLE->__add('files', 'JAVA', '', '/java/spin.min.js');
	$STYLE->__set('title', 'Character Selection - ' . $system->data('sitename'));
	if ($template === 'default')
		$output .= '<audio id="background_audio" loop preload="auto">
	<source src="./tpl/default/sound/selection.mp3" />
</audio>¿';
	else {
		$out = array();
		foreach (glob('./sound/' . $account['sfx'] . '/*.mp3') as $index => $filename) {
			$p = pathinfo($filename);
			$out[$index]['file'] = $p['filename'];
			$out[$index]['howl'] = null;
		}
		$output .= '<script>let gVersion = "' . $version . '",mvol="' . (isset($account['mvol']) && $account['mvol'] !== "" ? $account['mvol'] : $system->data('default-volume-music')) . '", vsfx="' . (isset($account['vsfx']) && $account['vsfx'] !== "" ? $account['vsfx'] : $system->data('default-volume-sfx')) . '", sfx = ' . json_encode($out) . ', SFXpackage = "' . $account['sfx'] . '"; Object.freeze(SFXpackage); Object.freeze(sfx); </script>';
	}
} elseif ($mode == 'search') {
	$account = $db->fetch("SELECT * FROM accounts WHERE id = '" . $account['id'] . "'");
	$type = '';
	if (isset($_GET['type'])) {
		$type = $secure->clean($_GET['type']);
	}

	if ($type == 'private' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		$team = array_unique(explode(',', $account['team']));
		if (count($team) !== 3)
			$system->redirect('./ingame');
		$match = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND `timeend` = '' AND (`status` !='winner' OR `status` !='loser') AND `type` = 'private' AND `resulted` = '0'");
		if ($match->rowCount() > 0) {
			echo 'Found! Redirecting...';
			$system->redirect('./battle');
			return;
		} else {

			if (isset($_POST['name'])) {

				if (!empty($_POST['name'])) {

					$oname = $secure->clean($_POST['name']);
					$ocheck = $db->query("SELECT * FROM accounts WHERE name = '" . $oname . "'");
					if ($ocheck->rowCount()  > 0) {
						// User exists
						$oaccount = $ocheck->fetch();
						if ($oaccount['id'] !== $account['id']) {
							// Find if he is searching for you
							$osearch = $db->query("SELECT * FROM matches WHERE `id-1` = '" . $account['id'] . "' AND timeend = '' AND `check` = '' AND `type` = 'private' AND `resulted` = '0'");
							if ($osearch->rowCount() == 0) {
								// No record

								$tpl .= '<h1>Searching for ' . $oaccount['name']  . '</h1><a class="goback cancel" href="#" name="cancel">Cancel</a>';
								$team = explode(',', $account['team']);
								$nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '', "passive" => '');
								foreach ($team as $key => $character) {
									$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
									if ($key == 0) {
										$outty = $chara['id'] . ';';
										$nteam['healths'] .= $chara['health'];
										$nteam['manas'] .= $chara['mana'];
									} else {
										$outty .= '|' . $chara['id'] . ';';
										$nteam['healths'] .= '|' . $chara['health'];
										$nteam['manas'] .= '|' . $chara['mana'];
									}

									$skills = explode(',', $chara['skills']);
									if (!empty($nteam['cooldowns']))
										$nteam['cooldowns'] .= '|';
									foreach ($skills as $key => $skill) {
										$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
										$cooldown = '0';
										if (!empty($sdata['starting_cooldown']))
											$cooldown = $sdata['starting_cooldown'];
										if ($key > 0) {
											$outty .= ',';
											$nteam['cooldowns'] .= ',';
										}
										$outty .= $skill . ':' . $sdata['cost'];
										$nteam['cooldowns'] .= $skill . ':' . $cooldown;
									}
									$nteam['team'] .= $outty;
									$outty = '';
								}

								$db->query("INSERT INTO `matches` (`id-0`,`id-1`,`time`,`status`,`t-0`,`h-0`,`m-0`,`c-0`,`t-1`,`h-1`,`m-1`,`c-1`,`turns`,`timeend`,`type`,`reward`,`check`,`active`)
										VALUES ('" . $account['id'] . "', '" . $oaccount['id'] . "', '1=" . time() . "','playerTurn','" . $nteam['team'] . "','" . $nteam['healths'] . "','" . $nteam['manas'] . "','" . $nteam['cooldowns'] . "','','','','','','','private','','','')");
							} else {
								echo 'Found ' . $oaccount['name']  . '! redirecting..';
								// Record exists set check true redirect
								$rand1 = rand(0, 1);
								$rand2 = rand(0, 1);
								do {
									$rand2 = rand(0, 1);
								} while ($rand1 == $rand2);
								$match = $db->fetch("SELECT * FROM matches WHERE `id-1` = '" . $account['id'] . "' AND timeend = '' AND `check` = '' AND `type` = 'private' AND `resulted` = '0'");
								$oteam = explode(',', $account['team']);
								$nteam['passive'] = '';
								$nteam['team'] = explode('|', $match['t-0']);
								foreach ($nteam['team'] as $key => $character) {

									$character = substr($character, 0, strpos($character, ';'));
									$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
									if (!empty($chara['passive'])) {
										$passives = explode(',', $chara['passive']);

										foreach ($passives as $passive) {
											if (empty($passive))
												continue;
											/*if(!empty($nteam['passive']))
												$nteam['passive'] .= '/';*/
											$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
											$effects = explode(',', $skill['effects']);
											$_add = '';
											$targets = explode('|', $skill['targets']);
											foreach ($targets as $target) {

												foreach ($effects as $effect) {
													$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
													if ($data->rowCount() == 0)
														continue;
													$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
													$continue = false;
													switch ($data['target']) {
														case 'S':
															if (strpos($target, 'S') === false)
																$continue = true;
															break;
														case 'A':
															if (strpos($target, 'A') === false)
																$continue = true;
															break;
														case 'E':
															if (strpos($target, 'E') === false)
																$continue = true;
															break;
														default:

															break;
													}
													if ($continue === true)
														continue;
													if (!empty($_add))
														$_add .= ',';
													$custom = '';
													if (!empty($data['dd']))
														$custom = '*' . $data['dd'];
													$_add .= $data['id'] . ';' . $data['duration'] . $custom;
												}
												$amount = $skill['amount'];
												for ($a = 0; $a < $amount; $a++) {
													if (!empty($nteam['passive']))
														$nteam['passive'] .= '/';
													if ($target == 'S')
														$nteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $key;
													elseif (strpos($target, 'E') !== false) {
														$nteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '0';
														$nteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '1';
														$nteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '2';
													} elseif (strpos($target, 'A') !== false) {
														for ($x = 0; $x <= 2; $x++) {
															if ($x == $key)
																continue;
															if (!empty($nteam['passive']) && $x > 0)
																$nteam['passive'] .= '/';
															$nteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $x;
														}
													}
												}
											}
										}
									}
								}

								$onteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '', "passive" => '');
								foreach ($oteam as $key => $character) {
									$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
									if ($key == 0) {
										$outty = $chara['id'] . ';';
										$onteam['healths'] .= $chara['health'];
										$onteam['manas'] .= $chara['mana'];
									} else {
										$outty .= '|' . $chara['id'] . ';';
										$onteam['healths'] .= '|' . $chara['health'];
										$onteam['manas'] .= '|' . $chara['mana'];
									}

									$skills = explode(',', $chara['skills']);
									if (!empty($onteam['cooldowns']))
										$onteam['cooldowns'] .= '|';
									foreach ($skills as $s => $skill) {
										$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
										$cooldown = '0';
										if (!empty($sdata['starting_cooldown']))
											$cooldown = $sdata['starting_cooldown'];
										if ($s > 0) {
											$outty .= ',';
											$onteam['cooldowns'] .= ',';
										}
										$outty .= $skill . ':' . $sdata['cost'];
										$onteam['cooldowns'] .= $skill . ':' . $cooldown;
									}

									if (!empty($chara['passive'])) {
										$passives = explode(',', $chara['passive']);

										foreach ($passives as $passive) {
											if (empty($passive))
												continue;
											$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
											$effects = explode(',', $skill['effects']);
											$_add = '';
											$targets = explode('|', $skill['targets']);
											foreach ($targets as $target) {

												foreach ($effects as $effect) {
													$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
													if ($data->rowCount() == 0)
														continue;
													$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
													$continue = false;
													switch ($data['target']) {
														case 'S':
															if (strpos($target, 'S') === false)
																$continue = true;
															break;
														case 'A':
															if (strpos($target, 'A') === false)
																$continue = true;
															break;
														case 'E':
															if (strpos($target, 'E') === false)
																$continue = true;
															break;
														default:

															break;
													}
													if ($continue === true)
														continue;

													if (!empty($_add))
														$_add .= ',';
													$custom = '';
													if (!empty($data['dd']))
														$custom = '*' . $data['dd'];
													$_add .= $data['id'] . ';' . $data['duration'] . $custom;
												}
												$amount = $skill['amount'];
												for ($a = 0; $a < $amount; $a++) {
													if (!empty($onteam['passive']))
														$onteam['passive'] .= '/';
													if ($target == 'S') {
														$onteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $key;
													} elseif (strpos($target, 'E') !== false) {
														$onteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '0';
														$onteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '1';
														$onteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '2';
													} elseif (strpos($target, 'A') !== false) {
														for ($x = 0; $x <= 2; $x++) {
															if ($x == $key)
																continue;
															if (!empty($onteam['passive']) && $x > 0)
																$onteam['passive'] .= '/';
															$onteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $x;
														}
													}
												}
											}
										}
									}
									$onteam['team'] .= $outty;
									$outty = '';
								}

								$active = $onteam['passive'] . (!empty($onteam['passive']) ? '/' : '') . $nteam['passive'];
								$db->query("UPDATE `matches` SET `active` = '" . $active . "', `id-" . $rand1 . "` = '" . $match['id-0'] . "', `t-" . $rand1 . "` = '" . $match['t-0'] . "',`m-" . $rand1 . "` = '" . $match['m-0'] . "',`c-" . $rand1 . "` = '" . $match['c-0'] . "',`h-" . $rand1 . "` = '" . $match['h-0'] . "',`id-" . $rand2 . "` = '" . $account['id'] . "', `check` = '1',`time` = '1=" . time() . "',`t-" . $rand2 . "`='" . $onteam['team'] . "',`h-" . $rand2 . "`='" . $onteam['healths'] . "',`m-" . $rand2 . "`='" . $onteam['manas'] . "',`c-" . $rand2 . "`='" . $onteam['cooldowns'] . "' WHERE `matches`.`id` = '" . $match['id'] . "'");
								$system->redirect('./battle');
							}
						} else {
							$tpl .= $system->message('Error', 'You can not face yourself!', '', 'Continue');
						}
					} else {
						$tpl .= $system->message('Error', 'The username you supplied doesnt exist!', '', 'Continue');
					}
				} else {

					$tpl .= $system->message('Error', 'Please fill out the username field before submitting!', '', 'Continue');
				}
			} else {

				$searching = $db->query("SELECT * FROM `matches` WHERE `id-0` ='" . $account['id'] . "' AND `timeend` = '' AND `check` = '' AND `type` = 'private' AND `resulted` = '0'");
				if ($searching->rowCount() > 0) {
					// Searching
					$opponent = $searching->fetch();
					$opponent = $user->value($opponent['id-1'], 'name');
					$tpl .= '<h1>Searching for ' . $opponent  . '</h1><a class="goback cancel" href="#">Cancel</a>';
				} else {
					$tpl = $STYLE->open('private.tpl');
				}
			}
		}
	} elseif ($type == 'ladder' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		// Team check
		$team = array_unique(explode(',', $account['team']));
		if (count($team) !== 3)
			$system->redirect('./ingame');
		$search = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND (`status` !='winner' OR `status` !='loser') AND `timeend` = ''  AND `resulted` = '0' AND `type` = 'ladder' LIMIT 1");
		if ($search->rowCount() > 0) {
			$search = $search->fetch();
			if ($search['check'] == '1') {
				echo 'Found Redirecting...';
				$system->redirect('./battle');
				return;
			} else {
				$output = $STYLE->open('ladder.tpl');
				$output = $STYLE->tags($output, array(
					"TYPE" => 'ladder'
				));
				return;
			}
		}

		// Search a new record
		$search = $db->query("SELECT * FROM `matches` WHERE `id-1` = '0' AND `check` = '' AND `timeend` = '' AND (`status` !='winner' OR `status` !='loser') AND `type` = 'ladder' AND `resulted` = '0' LIMIT 1");
		if ($search->rowCount() > 0) {
			$search = $search->fetch();
			$opponent = $db->query("SELECT * FROM `accounts` WHERE id = '" . $search['id-0'] . "'");
			$opponent = $opponent->fetch();
			if ($opponent['id'] !== $account['id']) {
				if ((abs($account['experience'] - $opponent['experience'])) <= $system->data('Experience_Range')) {
					echo 'Found! Redirecting...';
					$rand1 = rand(0, 1);
					$rand2 = rand(0, 1);
					do {
						$rand2 = rand(0, 1);
					} while ($rand1 == $rand2);
					//$match = $db->fetch("SELECT * FROM matches WHERE `id-1` = '" . $account['id'] . "' AND timeend = '' AND `check` = '' AND `type` = 'private' AND `resulted` = '0'");
					$oteam = explode(',', $account['team']);
					$onteam['passive'] = '';
					$onteam['team'] = explode('|', $search['t-0']);
					foreach ($onteam['team'] as $key => $character) {
						$character = substr($character, 0, strpos($character, ';'));
						$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
						if (!empty($chara['passive'])) {
							$passives = explode(',', $chara['passive']);
							foreach ($passives as $passive) {
								if (empty($passive))
									continue;

								$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
								$effects = explode(',', $skill['effects']);
								$_add = '';
								$targets = explode('|', $skill['targets']);
								foreach ($targets as $target) {
									foreach ($effects as $effect) {
										$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
										if ($data->rowCount() == 0)
											continue;
										$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
										$continue = false;
										switch ($data['target']) {
											case 'S':
												if (strpos($target, 'S') === false)
													$continue = true;
												break;
											case 'A':
												if (strpos($target, 'A') === false)
													$continue = true;
												break;
											case 'E':
												if (strpos($target, 'E') === false)
													$continue = true;
												break;
											default:

												break;
										}
										if ($continue === true)
											continue;
										if (!empty($_add))
											$_add .= ',';
										$custom = '';
										if (!empty($data['dd']))
											$custom = '*' . $data['dd'];
										$_add .= $data['id'] . ';' . $data['duration'] . $custom;
									}
									$amount = $skill['amount'];
									for ($a = 0; $a < $amount; $a++) {
										if (!empty($onteam['passive']))
											$onteam['passive'] .= '/';
										if ($target == 'S')
											$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $key;
										elseif (strpos($target, 'E') !== false) {
											$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '0';
											$onteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '1';
											$onteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '2';
										} elseif (strpos($target, 'A') !== false) {
											for ($x = 0; $x <= 2; $x++) {
												if ($x == $key)
													continue;
												if (!empty($onteam['passive']))
													$onteam['passive'] .= '/';
												$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $x;
											}
										}
									}
								}
							}
						}
					}

					$nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '', "passive" => '');
					foreach ($oteam as $key => $character) {
						$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
						if ($key == 0) {
							$outty = $chara['id'] . ';';
							$nteam['healths'] .= $chara['health'];
							$nteam['manas'] .= $chara['mana'];
						} else {
							$outty .= '|' . $chara['id'] . ';';
							$nteam['healths'] .= '|' . $chara['health'];
							$nteam['manas'] .= '|' . $chara['mana'];
						}

						$skills = explode(',', $chara['skills']);
						if (!empty($nteam['cooldowns']))
							$nteam['cooldowns'] .= '|';
						foreach ($skills as $s => $skill) {
							$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
							$cooldown = '0';
							if (!empty($sdata['starting_cooldown']))
								$cooldown = $sdata['starting_cooldown'];
							if ($s > 0) {
								$outty .= ',';
								$nteam['cooldowns'] .= ',';
							}
							$outty .= $skill . ':' . $sdata['cost'];
							$nteam['cooldowns'] .= $skill . ':' . $cooldown;
						}

						if (!empty($chara['passive'])) {
							$passives = explode(',', $chara['passive']);

							foreach ($passives as $passive) {
								if (empty($passive))
									continue;

								$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
								$effects = explode(',', $skill['effects']);
								$_add = '';
								$targets = explode('|', $skill['targets']);
								foreach ($targets as $target) {

									foreach ($effects as $effect) {
										$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
										if ($data->rowCount() == 0)
											continue;
										$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
										$continue = false;
										switch ($data['target']) {
											case 'S':
												if (strpos($target, 'S') === false)
													$continue = true;
												break;
											case 'A':
												if (strpos($target, 'A') === false)
													$continue = true;
												break;
											case 'E':
												if (strpos($target, 'E') === false)
													$continue = true;
												break;
											default:

												break;
										}
										if ($continue === true)
											continue;

										if (!empty($_add))
											$_add .= ',';
										$custom = '';
										if (!empty($data['dd']))
											$custom = '*' . $data['dd'];
										$_add .= $data['id'] . ';' . $data['duration'] . $custom;
									}
									$amount = $skill['amount'];
									for ($a = 0; $a < $amount; $a++) {
										if (!empty($nteam['passive']))
											$nteam['passive'] .= '/';
										if ($target == 'S') {
											$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $key;
										} elseif (strpos($target, 'E') !== false) {
											$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '0';
											$nteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '1';
											$nteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '2';
										} elseif (strpos($target, 'A') !== false) {
											for ($x = 0; $x <= 2; $x++) {
												if ($x == $key)
													continue;
												if (!empty($nteam['passive']))
													$nteam['passive'] .= '/';
												$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $x;
											}
										}
									}
								}
							}
						}
						$nteam['team'] .= $outty;
						$outty = '';
					}

					$active = $onteam['passive'] . (!empty($onteam['passive']) ? '/' : '') . $nteam['passive'];
					$db->query("UPDATE `matches` SET `active` = '" . $active . "', `id-" . $rand1 . "` = '" . $search['id-0'] . "', `t-" . $rand1 . "` = '" . $search['t-0'] . "',`m-" . $rand1 . "` = '" . $search['m-0'] . "',`c-" . $rand1 . "` = '" . $search['c-0'] . "',`h-" . $rand1 . "` = '" . $search['h-0'] . "',`id-" . $rand2 . "` = '" . $account['id'] . "', `check` = '1',`time` = '1=" . time() . "',`t-" . $rand2 . "`='" . $nteam['team'] . "',`h-" . $rand2 . "`='" . $nteam['healths'] . "',`m-" . $rand2 . "`='" . $nteam['manas'] . "',`c-" . $rand2 . "`='" . $nteam['cooldowns'] . "' WHERE `matches`.`id` = '" . $search['id'] . "'");
					$system->redirect('./battle');
				} else {
					$tpl = $STYLE->open('ladder.tpl');
					$tpl = $STYLE->tags($tpl, array(
						"TYPE" => 'ladder'
					));
				}
			} else {
				$tpl = $STYLE->open('ladder.tpl');
				$tpl = $STYLE->tags($tpl, array(
					"TYPE" => 'ladder'
				));
			}
		} else {
			$team = explode(',', $account['team']);
			$nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '');
			foreach ($team as $key => $character) {
				$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
				if ($key == 0) {
					$outty = $chara['id'] . ';';
					$nteam['healths'] .= $chara['health'];
					$nteam['manas'] .= $chara['mana'];
				} else {
					$outty .= '|' . $chara['id'] . ';';
					$nteam['healths'] .= '|' . $chara['health'];
					$nteam['manas'] .= '|' . $chara['mana'];
				}
				$skills = explode(',', $chara['skills']);
				if (!empty($nteam['cooldowns']))
					$nteam['cooldowns'] .= '|';
				foreach ($skills as $key => $skill) {
					$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
					$cooldown = '0';
					if (!empty($sdata['starting_cooldown']))
						$cooldown = $sdata['starting_cooldown'];
					if ($key > 0) {
						$outty .= ',';
						$nteam['cooldowns'] .= ',';
					}
					$outty .= $skill . ':' . $sdata['cost'];
					$nteam['cooldowns'] .= $skill . ':' . $cooldown;
				}
				$nteam['team'] .= $outty;
				$outty = '';
			}
			$db->query("INSERT INTO `matches` (`id-0`,`id-1`,`time`,`status`,`t-0`,`h-0`,`m-0`,`c-0`,`t-1`,`h-1`,`m-1`,`c-1`,`turns`,`timeend`,`type`,`reward`,`check`,`active`)
										VALUES ('" . $account['id'] . "', '0', '1=" . time() . "','playerTurn','" . $nteam['team'] . "','" . $nteam['healths'] . "','" . $nteam['manas'] . "','" . $nteam['cooldowns'] . "','','','','','','','ladder','','','')");
			$tpl = $STYLE->open('ladder.tpl');
			$tpl = $STYLE->tags($tpl, array(
				"TYPE" => 'ladder'
			));
		}
	} elseif ($type == 'quick' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		// Team check
		$team = array_unique(explode(',', $account['team']));
		if (count($team) !== 3)
			$system->redirect('./ingame');
		$search = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND (`status` !='winner' OR `status` !='loser') AND `timeend` = ''  AND `resulted` = '0' AND `type` = 'quick' LIMIT 1");
		if ($search->rowCount() > 0) {
			$search = $search->fetch();
			if ($search['check'] == '1') {
				echo 'Found Redirecting...';
				$system->redirect('./battle');
				return;
			} else {
				$output = $STYLE->open('ladder.tpl');
				$output = $STYLE->tags($output, array(
					"TYPE" => 'quick'
				));
				return;
			}
		}

		// Search a new record
		$search = $db->query("SELECT * FROM `matches` WHERE `id-1` = '0' AND `check` = '' AND `timeend` = '' AND (`status` !='winner' OR `status` !='loser') AND `type` = 'quick' AND `resulted` = '0' LIMIT 1");
		if ($search->rowCount() > 0) {
			$search = $search->fetch();
			$opponent = $db->query("SELECT * FROM `accounts` WHERE id = '" . $search['id-0'] . "'");
			$opponent = $opponent->fetch();
			if ($opponent['id'] !== $account['id']) {
				/* if((abs($account['experience']-$opponent['experience'])) <= $system->data('Experience_Range')){ */
				echo 'Found! Redirecting...';
				$rand1 = rand(0, 1);
				$rand2 = rand(0, 1);
				do {
					$rand2 = rand(0, 1);
				} while ($rand1 == $rand2);
				//$match = $db->fetch("SELECT * FROM matches WHERE `id-1` = '" . $account['id'] . "' AND timeend = '' AND `check` = '' AND `type` = 'private' AND `resulted` = '0'");
				$oteam = explode(',', $account['team']);
				$onteam['passive'] = '';
				$onteam['team'] = explode('|', $search['t-0']);
				foreach ($onteam['team'] as $key => $character) {
					$character = substr($character, 0, strpos($character, ';'));
					$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
					if (!empty($chara['passive'])) {
						$passives = explode(',', $chara['passive']);
						foreach ($passives as $passive) {
							if (empty($passive))
								continue;

							$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
							$effects = explode(',', $skill['effects']);
							$_add = '';
							$targets = explode('|', $skill['targets']);
							foreach ($targets as $target) {
								foreach ($effects as $effect) {
									$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
									if ($data->rowCount() == 0)
										continue;
									$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
									$continue = false;
									switch ($data['target']) {
										case 'S':
											if (strpos($target, 'S') === false)
												$continue = true;
											break;
										case 'A':
											if (strpos($target, 'A') === false)
												$continue = true;
											break;
										case 'E':
											if (strpos($target, 'E') === false)
												$continue = true;
											break;
										default:

											break;
									}
									if ($continue === true)
										continue;
									if (!empty($_add))
										$_add .= ',';
									$custom = '';
									if (!empty($data['dd']))
										$custom = '*' . $data['dd'];
									$_add .= $data['id'] . ';' . $data['duration'] . $custom;
								}
								$amount = $skill['amount'];
								for ($a = 0; $a < $amount; $a++) {
									if (!empty($onteam['passive']))
										$onteam['passive'] .= '/';
									if ($target == 'S')
										$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $key;
									elseif (strpos($target, 'E') !== false) {
										$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '0';
										$onteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '1';
										$onteam['passive'] .= '/1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . '2';
									} elseif (strpos($target, 'A') !== false) {
										for ($x = 0; $x <= 2; $x++) {
											if ($x == $key)
												continue;
											if (!empty($onteam['passive']))
												$onteam['passive'] .= '/';
											$onteam['passive'] .= '1=' . $rand1 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . $x;
										}
									}
								}
							}
						}
					}
				}

				$nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '', "passive" => '');
				foreach ($oteam as $key => $character) {
					$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
					if ($key == 0) {
						$outty = $chara['id'] . ';';
						$nteam['healths'] .= $chara['health'];
						$nteam['manas'] .= $chara['mana'];
					} else {
						$outty .= '|' . $chara['id'] . ';';
						$nteam['healths'] .= '|' . $chara['health'];
						$nteam['manas'] .= '|' . $chara['mana'];
					}

					$skills = explode(',', $chara['skills']);
					if (!empty($nteam['cooldowns']))
						$nteam['cooldowns'] .= '|';
					foreach ($skills as $s => $skill) {
						$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
						$cooldown = '0';
						if (!empty($sdata['starting_cooldown']))
							$cooldown = $sdata['starting_cooldown'];
						if ($s > 0) {
							$outty .= ',';
							$nteam['cooldowns'] .= ',';
						}
						$outty .= $skill . ':' . $sdata['cost'];
						$nteam['cooldowns'] .= $skill . ':' . $cooldown;
					}

					if (!empty($chara['passive'])) {
						$passives = explode(',', $chara['passive']);

						foreach ($passives as $passive) {
							if (empty($passive))
								continue;

							$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
							$effects = explode(',', $skill['effects']);
							$_add = '';
							$targets = explode('|', $skill['targets']);
							foreach ($targets as $target) {

								foreach ($effects as $effect) {
									$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
									if ($data->rowCount() == 0)
										continue;
									$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
									$continue = false;
									switch ($data['target']) {
										case 'S':
											if (strpos($target, 'S') === false)
												$continue = true;
											break;
										case 'A':
											if (strpos($target, 'A') === false)
												$continue = true;
											break;
										case 'E':
											if (strpos($target, 'E') === false)
												$continue = true;
											break;
										default:

											break;
									}
									if ($continue === true)
										continue;

									if (!empty($_add))
										$_add .= ',';
									$custom = '';
									if (!empty($data['dd']))
										$custom = '*' . $data['dd'];
									$_add .= $data['id'] . ';' . $data['duration'] . $custom;
								}
								$amount = $skill['amount'];
								for ($a = 0; $a < $amount; $a++) {
									if (!empty($nteam['passive']))
										$nteam['passive'] .= '/';

									if ($target == 'S') {
										$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $key;
									} elseif (strpos($target, 'E') !== false) {
										$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '0';
										$nteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '1';
										$nteam['passive'] .= '/1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand1 . '2';
									} elseif (strpos($target, 'A') !== false) {
										for ($x = 0; $x <= 2; $x++) {
											if ($x == $key)
												continue;
											if (!empty($nteam['passive']))
												$nteam['passive'] .= '/';
											$nteam['passive'] .= '1=' . $rand2 . $key . ':' . $skill['id'] . '[' . $_add . ']' . $rand2 . $x;
										}
									}
								}
							}
						}
					}
					$nteam['team'] .= $outty;
					$outty = '';
				}

				$active = $onteam['passive'] . (!empty($onteam['passive']) ? '/' : '') . $nteam['passive'];
				$db->query("UPDATE `matches` SET `active` = '" . $active . "', `id-" . $rand1 . "` = '" . $search['id-0'] . "', `t-" . $rand1 . "` = '" . $search['t-0'] . "',`m-" . $rand1 . "` = '" . $search['m-0'] . "',`c-" . $rand1 . "` = '" . $search['c-0'] . "',`h-" . $rand1 . "` = '" . $search['h-0'] . "',`id-" . $rand2 . "` = '" . $account['id'] . "', `check` = '1',`time` = '1=" . time() . "',`t-" . $rand2 . "`='" . $nteam['team'] . "',`h-" . $rand2 . "`='" . $nteam['healths'] . "',`m-" . $rand2 . "`='" . $nteam['manas'] . "',`c-" . $rand2 . "`='" . $nteam['cooldowns'] . "' WHERE `matches`.`id` = '" . $search['id'] . "'");
				$system->redirect('./battle');
			} else {
				$tpl = $STYLE->open('ladder.tpl');
				$tpl = $STYLE->tags($tpl, array(
					"TYPE" => 'quick'
				));
			}
		} else {
			$team = explode(',', $account['team']);
			$nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '');
			foreach ($team as $key => $character) {
				$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
				if ($key == 0) {
					$outty = $chara['id'] . ';';
					$nteam['healths'] .= $chara['health'];
					$nteam['manas'] .= $chara['mana'];
				} else {
					$outty .= '|' . $chara['id'] . ';';
					$nteam['healths'] .= '|' . $chara['health'];
					$nteam['manas'] .= '|' . $chara['mana'];
				}
				$skills = explode(',', $chara['skills']);
				if (!empty($nteam['cooldowns']))
					$nteam['cooldowns'] .= '|';
				foreach ($skills as $key => $skill) {
					$sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
					$cooldown = '0';
					if (!empty($sdata['starting_cooldown']))
						$cooldown = $sdata['starting_cooldown'];
					if ($key > 0) {
						$outty .= ',';
						$nteam['cooldowns'] .= ',';
					}
					$outty .= $skill . ':' . $sdata['cost'];
					$nteam['cooldowns'] .= $skill . ':' . $cooldown;
				}
				$nteam['team'] .= $outty;
				$outty = '';
			}
			$db->query("INSERT INTO `matches` (`id-0`,`id-1`,`time`,`status`,`t-0`,`h-0`,`m-0`,`c-0`,`t-1`,`h-1`,`m-1`,`c-1`,`turns`,`timeend`,`type`,`reward`,`check`,`active`)
										VALUES ('" . $account['id'] . "', '0', '1=" . time() . "','playerTurn','" . $nteam['team'] . "','" . $nteam['healths'] . "','" . $nteam['manas'] . "','" . $nteam['cooldowns'] . "','','','','','','','quick','','','')");
			$tpl = $STYLE->open('ladder.tpl');
			$tpl = $STYLE->tags($tpl, array(
				"TYPE" => 'quick'
			));
		}
	} elseif ($type == 'ai' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		// Team check
		$team = array_unique(explode(',', $account['team']));
		if (count($team) !== 3)
			$system->redirect('./ingame');
		$difficulty = (($_GET['difficulty'] == 'one') ? '1' : (($_GET['difficulty'] == 'two') ? '2' : '3'));
		$search = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND (`status` !='winner' OR `status` !='loser') AND `timeend` = ''  AND `resulted` = '0' AND `type` = 'ai' LIMIT 1");
		if ($search->rowCount() > 0) {
			$search = $search->fetch();
			if ($search['check'] == '1') {
				echo 'Found...';
				$system->redirect('./battle');
				return;
			} else {
				$output = $STYLE->open('ai.tpl');
				$output = $STYLE->tags($output, array(
					"TYPE" => 'AI'
				));
				return;
			}
		}

		// Start a new battle
		$team = explode(',', $account['team']);
		$team = $game->setupMatch($team);
		$AI = $system->data('AI_Account');
		$santa = $db->query("SELECT * FROM `accounts` WHERE `id` = $AI ORDER BY `id` DESC")->fetch();
		$santasHelpers = explode(',', $santa['team']);
		$santasHelpers = $game->setupMatch($santasHelpers);
		$team['passive'] = '';
		$team['team'] = explode('|', $team['team']);
		foreach ($team['team'] as $key => $character) {
			$character = substr($character, 0, strpos($character, ';'));
			$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
			if (!empty($chara['passive'])) {
				$passives = explode(',', $chara['passive']);
				foreach ($passives as $passive) {
					if (empty($passive))
						continue;
					$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
					$effects = explode(',', $skill['effects']);
					$_add = '';
					$targets = explode('|', $skill['targets']);
					foreach ($targets as $target) {
						foreach ($effects as $effect) {
							$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
							if ($data->rowCount() == 0)
								continue;
							$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
							$continue = false;
							switch ($data['target']) {
								case 'S':
									if (strpos($target, 'S') === false)
										$continue = true;
									break;
								case 'A':
									if (strpos($target, 'A') === false)
										$continue = true;
									break;
								case 'E':
									if (strpos($target, 'E') === false)
										$continue = true;
									break;
								default:

									break;
							}
							if ($continue === true)
								continue;
							if (!empty($_add))
								$_add .= ',';
							$custom = '';
							if (!empty($data['dd']))
								$custom = '*' . $data['dd'];
							$_add .= $data['id'] . ';' . $data['duration'] . $custom;
						}
						$amount = $skill['amount'];
						for ($a = 0; $a < $amount; $a++) {
							if (!empty($team['passive']))
								$team['passive'] .= '/';
							if ($target == 'S')
								$team['passive'] .= '1=0' . $key . ':' . $skill['id'] . '[' . $_add . ']0' . $key;
							elseif (strpos($target, 'E') !== false) {
								$team['passive'] .= '1=0' . $key . ':' . $skill['id'] . '[' . $_add . ']10';
								$team['passive'] .= '/1=0' . $key . ':' . $skill['id'] . '[' . $_add . ']11';
								$team['passive'] .= '/1=0' . $key . ':' . $skill['id'] . '[' . $_add . ']12';
							} elseif (strpos($target, 'A') !== false) {
								for ($x = 0; $x <= 2; $x++) {
									if ($x == $key)
										continue;
									if (!empty($team['passive']))
										$team['passive'] .= '/';
									$team['passive'] .= '1=0' . $key . ':' . $skill['id'] . '[' . $_add . ']0' . $x;
								}
							}
						}
					}
				}
			}
		}
		$team['team'] = implode('|', $team['team']);
		$santasHelpers['passive'] = '';
		$santasHelpers['team'] = explode('|', $santasHelpers['team']);
		foreach ($santasHelpers['team'] as $key => $character) {
			$chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
			if (!empty($chara['passive'])) {
				$passives = explode(',', $chara['passive']);

				foreach ($passives as $passive) {
					if (empty($passive))
						continue;

					$skill = $db->fetch("SELECT * FROM skills WHERE id='" . $passive . "'");
					$effects = explode(',', $skill['effects']);
					$_add = '';
					$targets = explode('|', $skill['targets']);
					foreach ($targets as $target) {
						foreach ($effects as $effect) {
							$data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
							if ($data->rowCount() == 0)
								continue;
							$data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
							$continue = false;
							switch ($data['target']) {
								case 'S':
									if (strpos($target, 'S') === false)
										$continue = true;
									break;
								case 'A':
									if (strpos($target, 'A') === false)
										$continue = true;
									break;
								case 'E':
									if (strpos($target, 'E') === false)
										$continue = true;
									break;
								default:

									break;
							}
							if ($continue === true)
								continue;

							if (!empty($_add))
								$_add .= ',';
							$custom = '';
							if (!empty($data['dd']))
								$custom = '*' . $data['dd'];
							$_add .= $data['id'] . ';' . $data['duration'] . $custom;
						}
						$amount = $skill['amount'];
						for ($a = 0; $a < $amount; $a++) {
							if (!empty($nteam['passive']))
								$santasHelpers['passive'] .= '/';

							if ($target == 'S') {
								$santasHelpers['passive'] .= '1=1' . $key . ':' . $skill['id'] . '[' . $_add . ']1' . $key;
							} elseif (strpos($target, 'E') !== false) {
								if (strpos($target, '/') !== false) {
									$_who = substr($target, strpos($target, '/') + 1);
									if ($_who != 'a') {
										$santasHelpers['passive'] .= '1=0' . $_who . ':' . $skill['id'] . '[' . $_add . ']00';
										continue;
									}
								}
								$santasHelpers['passive'] .= '1=00:' . $skill['id'] . '[' . $_add . ']00';
								$santasHelpers['passive'] .= '/1=01:' . $skill['id'] . '[' . $_add . ']01';
								$santasHelpers['passive'] .= '/1=02:' . $skill['id'] . '[' . $_add . ']02';
							} elseif (strpos($target, 'A') !== false) {
								for ($x = 0; $x <= 2; $x++) {
									if ($x == $key)
										continue;
									if (!empty($santasHelpers['passive']))
										$santasHelpers['passive'] .= '/';
									$santasHelpers['passive'] .= '1=1' . $x . ':' . $skill['id'] . '[' . $_add . ']1' . $x;
								}
							}
						}
					}
				}
			}
		}
		$santasHelpers['team'] = implode('|', $santasHelpers['team']);
		$active = $santasHelpers['passive'] . (!empty($santasHelpers['passive']) ? '/' : '') . $team['passive'];
		$db->query("INSERT INTO `matches` (`id-0`,`id-1`,`time`,`status`,`t-0`,`h-0`,`m-0`,`c-0`,`t-1`,`h-1`,`m-1`,`c-1`,`turns`,`timeend`,`type`,`reward`,`check`,`active`,`difficulty`)
										VALUES ('" . $account['id'] . "', '" . $santa['id'] . "', '1=" . time() . "','playerTurn','" . $team['team'] . "','" . $team['healths'] . "','" . $team['manas'] . "','" . $team['cooldowns'] . "',
                                        '" . $santasHelpers['team'] . "','" . $santasHelpers['healths'] . "','" . $santasHelpers['manas'] . "','" . $santasHelpers['cooldowns'] . "','','','ai','','1','" . $active . "', '" . $difficulty . "')");
		$tpl = $STYLE->open('ai.tpl');
		$tpl = $STYLE->tags($tpl, array(
			"TYPE" => 'AI'
		));
	} else {
		$system->redirect('./ingame');
	}
	$output = $tpl;
} elseif ($mode == 'battle') {

	include('./' . $version . '/core/battle.php');
} else {
	$system->redirect('./');
}