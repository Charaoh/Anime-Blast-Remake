<?php
if (empty($_GET["c"])) {
	header("Location: ../");
	die();
}
header("Content-Type: application/json; charset=UTF-8");
require("db.php");
require("function.php");
$system = new system();
$secure = new secure();
$user = new user();
require("config.php");
// LOAD DATABASE
$db = new db();
$db->connect($dbhost, $dbuser, $dbpassword, $dbmaster);
$characters = $db->query("SELECT * FROM `characters`");
$database = array();
$obj = explode(',', $secure->clean($_GET['c']));
if ($characters->rowCount() > 0) {
	while ($character = $characters->fetch()) {
		if (!empty($obj) && array_search($character['id'], $obj) === false) continue;
		$_skills = array();
		$passives = explode(',', $character['passive']);
		if (!empty($character['passive']))
			$character['skills'] .= ',' . $character['passive'];
		$skills = explode(',', $character['skills']);
		$keys = array();
		foreach ($skills as $_ => $skill) {
			$keys[] = $skill;
		}
		foreach ($skills as &$skill) {
			$alternative = false;
			if (strpos($skill, 'alt') !== false) {
				$alternative = true;
				$skill = substr($skill, 0, -3);
			}
			$s = $db->query("SELECT * FROM `skills` WHERE `id` = '" . $skill . "'");
			if ($s->rowCount() === 0) continue;
			$s = $s->fetch();
			$effects = explode(',', $s['effects']);
			foreach ($effects as $effect) {
				if ($db->fieldFetch('effects', $effect, 'replace') !== 'undefined' && $db->fieldFetch('effects', $effect, 'replace') !== '') {
					$replacements = true;
					$ez = explode('|', $db->fieldFetch('effects', $effect, 'replace'));
					do {
						if (!empty($salt))
							$ez = $salt;
						$salt = array();
						foreach ($ez as $looks) {
							if (in_array($looks, $keys) || in_array($looks . 'alt', $keys)) continue;
							$keys[] = $looks;
							if (strpos($looks, 'alt') === false)
								$skills[] = $looks . 'alt';
							else
								$skills[] = $looks;
							$alt = $db->query("SELECT * FROM skills WHERE id = '" . $looks . "'")->fetch();
							if ($alt) {
								$aeffects = explode(',', $alt['effects']);
								foreach ($aeffects as $ae) {
									if ($db->fieldFetch('effects', $ae, 'replace') !== 'undefined' && $db->fieldFetch('effects', $ae, 'replace') !== '') {
										$as = explode('|', $db->fieldFetch('effects', $ae, 'replace'));
										foreach ($as as $askill) {
											if (in_array($askill, $keys)) continue;
											$salt[] = $askill . 'alt';
										}
									}
								}
							}
						}
						if (empty($salt))
							$replacements = false;
					} while ($replacements === true);
				}
			}
			$passive = false;
			if (array_search($skill, $passives) !== false)
				$passive = true;
			$classes = explode(',', $s['classes']);
			foreach ($classes as &$class) {
				$class = $user->image($db->fieldFetch('classes', $class, 'name'), 'classes', './../', 'fl-l" title="Skill Class ' . $db->fieldFetch('classes', $class, 'name'));
				if ($class === "undefined")
					$class = 'None';
			}
			if (empty($classes))
				continue;
			$classes = implode('', $classes);
			$_skills[] = array("ID" => $skill, "IMAGE" => $user->image($skill, 'skills', './../', 'skill fl-l" title="' . $s['name'] . '"'), "NAME" => $s['name'], "DESCRIPTION" => $s['desc'], "COST" => $s['cost'], "COOLDOWN" => $s['cooldown'], "CLASSES" => $classes, "PASSIVE" => $passive, "ALTERNATIVE" => $alternative);
		}
		if (empty($_skills))
			continue;
		$database[$character['id']] = array("NAME" => $character['name'], "DESCRIPTION" => $character['desc'], "HEALTH" => $character['health'], "MANA" => $character['health'], "SKILLS" => $_skills);
	}
}
$database = json_encode($database);

echo $database;

$db->close();