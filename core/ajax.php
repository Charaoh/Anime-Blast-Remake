<?php

// Overwrite original file security...
/* if(!defined('SITE')){

  } */
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
	require('index.php');
	exit;
}

require("../inc/db.php");
require("../inc/function.php");
require("../inc/config.php");
$system = new system();
$secure = new secure();
$user = new user();
$db = new db();
$db->connect($dbhost, $dbuser, $dbpassword, $dbmaster);

session_name($system->data('session'));
session_start();
date_default_timezone_set('UTC');
// CHECK IF USER IS LOGGED IN
if (!isset($_SESSION[$system->data('Universal_Session') . '_id'])) {
	$email = '';
} else {
	$email = $_SESSION[$system->data('Universal_Session') . '_id'];
}
if (!isset($_SESSION[$system->data('Universal_Session') . '_lpip'])) {
	$lpip = '';
} else {
	$lpip = $_SESSION[$system->data('Universal_Session') . '_lpip'];
}
$account = $db->fetch("SELECT * FROM accounts WHERE id = '$email' AND lpip = '$lpip'");

$url = $system->data('url');
$path = $system->data('path');
$siteaddress = "$url/";
if ($path) {
	$siteaddress .= "$path/";
}
$current_url = preg_replace('/^([^&]*).*$/', '$1', str_replace(array($siteaddress), '', $system->current_url()));
$document_root = $_SERVER['DOCUMENT_ROOT'];
$root = "$document_root/$path";

// SESSION
$session_location = $_SERVER['REQUEST_URI'];
$session_id = session_id();
$ip = $_SERVER['REMOTE_ADDR'];
if ($account['id']) {
	$id = $account['id'];
	// Find Your Group
	$group_id = $user->group($account['id']);
} else {
	$id = '-1';
}
$online = $system->manageOnline();
// STYLES SYSTEM
if ($account['tpl'] && $system->data('usertemplate') == '1') {
	$template = $account['tpl'];
	if (file_exists("$root/tpl/$template/header.tpl")) {
		$template = $template;
	} else {
		$template = $system->data('template');
	}
} else {
	$template = $system->data('template');
}
require("../inc/parser.php");
$STYLE = new style();
$language = $system->data('language');
require("" . $root . "/lang/$language/index.php");
// PARSE STYLE
$output = '';
$f = '';
if (isset($_POST['f'])) {
	$f = $secure->clean($_POST['f']);
}
switch ($f) {
	case 'acceptMission':
		$m = 0;
		if (isset($_POST['m'])) {
			$m = $secure->clean($_POST['m']);
		}
		$it = $db->query("SELECT * FROM requires WHERE mid = '" . $m . "'");
		if ($it->rowCount() > 0) {
			$c = $db->query("SELECT * FROM complete WHERE account = '" . $account['id'] . "' AND mission = '" . $m . "'");
			if ($c->rowCount() > 0) {
				echo false;
				return;
			}
			$mission = $db->fetch("SELECT * FROM missions WHERE id = '" . $m . "'");
			$not = false;
			if (!empty($mission['missions'])) {
				$check = explode(',', $mission['missions']);
				foreach ($check as $c) {
					if (empty($c)) continue;
					$finished = $db->query("SELECT * FROM complete WHERE account = '" . $account['id'] . "' AND mission = '" . $c . "'");
					if ($finished->rowCount() == 0)
						$not = true;
				}
			}
			if ($not == true) {
				echo false;
				return;
			}
			while ($requirement = $it->fetch()) {
				$db->query("INSERT INTO `progress`(`account`, `mission`, `requirement`, `count`) 
			VALUES ('" . $account['id'] . "','" . $requirement['mid'] . "','" . $requirement['id'] . "','0')");
			}
			echo true;

			return;
		} else {
			echo false;
			return;
		}
		break;
	case 'updateMember':
		$member = $secure->clean($_POST['m']);
		$rank = $secure->clean($_POST['i']);
		$check = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $rank . "'");
		$exists = $db->query("SELECT * FROM `clan-members` WHERE `account_id` = '" . $member . "'");
		if ($check->rowCount() > 0 && $exists->rowCount() > 0) {
			// Update rank
			$db->query("UPDATE `clan-members` SET `rank` = '$rank' WHERE `clan-members`.`account_id` = '$member';");
			echo true;
		} else
			echo false;
		return true;
		break;
	case 'updateSort':
		$items = explode(',', $secure->clean($_POST['i']));
		$ordered = false;
		foreach ($items as $position => $item) {
			$check = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $item . "'");
			if ($check->rowCount() > 0) {
				$check = $check->fetch();
				$db->query("UPDATE `clan-ranks` SET `sort` = '" . $position . "' WHERE `clan-ranks`.`id` = '" . $check['id'] . "';");
				$ordered = true;
			}
		}
		if ($ordered)
			echo true;
		else
			echo false;
		return true;
		break;
	case 'getUCP':
		if ($system->data('siteclosed') == '1' && $system->group_permission($account['group'], 'acp') != '1') {
			$system->redirect('https://www.anime-blast.com');
		}
		$tpl = $STYLE->open('header.tpl');
		$output = $STYLE->getcode('UCP', $tpl);
		$time =  L_TIME . ': ' . $system->time(time(), 'g:i a');
		$mail = $db->query("SELECT id FROM " . $prefix . "_mail WHERE to_id = '" . $account['id'] . "' AND marked = '0'")->rowCount();
		$notification = '';
		if ($mail > 0) {
			$notification = '<p class="sitealert" style="margin-left: -2em;margin-top:-1.4em">' . $mail . '</p>';
		}
		$exp = 0;
		$width = 0;
		$ulevel = $db->query("SELECT * FROM levels WHERE experience < '" . ($account['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");
		if ($ulevel->rowCount() > 0) {
			$ulevel = $ulevel->fetch();
			$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($ulevel['id'] + 1) . "'");
			if (!$next) {
				$width = 100;
				$exp = 'Top Tier';
			} else {
				$exp = $account['experience'] . '/' . $next['experience'];
				$width = round(($account['experience'] / $next['experience']) * 100);
			}
			$ulevel = $user->level($account['experience']);
		} else {
			$ulevel = $db->fetch("SELECT * FROM levels WHERE id = '1'");
			$exp = '0/' . $ulevel['experience'];
			$ulevel = 1;
		}
		$clan = $db->fetch("SELECT * FROM `clan-members` WHERE `account_id` ='" . $account['id'] . "'");
		$menu = '';
		$menu .= (($clan) ? '<a href="' . $siteaddress . 'clan/profile/' . urlencode($db->fieldFetch('clans', $clan['clan_id'], 'name')) . '">Clan Panel</a>' : '<a href="' . $siteaddress . 'clans">Join a clan</a>');
		$menu .= '<a href="' . $siteaddress . '?s=ucp">Account Settings</a>';
		$menu .= '<a href="' . $siteaddress . '?s=ucp&mode=avatar">Change Avatar</a>';
		$menu .= '<a href="' . $siteaddress . '?s=ucp&mode=signature">Change Signature</a>';
		if ($system->group_permission($group_id, 'acp') === '1') {
			$menu .= '<a href="' . $siteaddress . 'acp/">Admin Panel</a>';
		}
		if ($system->group_permission($group_id, 'staff') === '1') {
			$menu .= '<a href="' . $siteaddress . 'staff/">Staff Panel</a>';
		}
		/* if ($system->group_permission($user->group($account['id']), 'rc') === '1') {
			$menu .= '<a href="' . $siteaddress . 'rc/" class="normfont">' . L_RESOLUTION_CENTRE . '</a>';
		} */

		if ($account['experience'] == 0)
			$account['experience'] = 1;
		$me = $db->fetch("SELECT * FROM levels WHERE experience < '" . ($account['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");

		$activate = '';
		if ($system->data('Hide-Exclusives') !== '1') {
			if ($account['activated'] != '1' && strpos($current_url, 'exclusive') === false) {
				$exclusives = $system->data('exclusives');
				if (!empty($exclusives)) {
					$activate = '<a href="' . $siteaddress . 'exclusive">
								<p class="ucp__exclusives"><span class="ucp__exclusives--span">Claim your exclusive character!</span>';
					$exclusives = explode(',', $exclusives);
					foreach ($exclusives as $exclusive) {
						$character = $db->fetch("SELECT * FROM characters WHERE id = '$exclusive'");
						if ($character)
							$activate .= $user->image($exclusive, 'characters', './../', 'ucp__exclusives--img');
					}
					$activate .= '</a>';
				}
			}
		}
		$discount = '';
		if (!empty($system->data('discount')))
			$discount = $system->data('discount') . ' OFF!';
		else
			$output = preg_replace('/\<!-- BEGIN discount -->(.*?)\<!-- END discount -->/is', '', $output);
		if ($clan) {
			$clan = '<a href="./clan/profile/' . urlencode($db->fieldFetch('clans', $clan['clan_id'], 'name')) . '" class="clanlink"><span>' . $db->fieldFetch('clans', $clan['clan_id'], 'abbreviation') . '</span>' . $user->image($clan['clan_id'], 'clans', './../', 'clansneak') . '</a>';
		} else {
			$clan = '';
		}
		$links = '<a href="' . $siteaddress . '?s=report" class="normfont">' . L_REPORT . '</a> | <a href="' . $siteaddress . '?s=tos" class="normfont">' . L_TERMS_OF_SERVICE . '</a> | <a href="' . $siteaddress . '?s=groups" class="normfont">' . L_GROUPS . '</a>';
		$output = $STYLE->tags($output, array("CLAN" => $clan, "DISCOUNT" => $discount, "EXCLUSIVE" => $activate, "LINKS" => $links, "TIME" => $time, "NOTIFICATION" => $notification, "AVATAR" => $user->avatar($account['id'], './../'), "LEVEL" => $ulevel, "EXPERIENCE" => $exp, "WIDTH" => $width, "USERNAME" => $user->name($account['id']), "MENU" => $menu, "VERSION" => $system->data('version')));

		// CHANGE NAVIGATION
		//$output = $STYLE->tags($tpl, array("URL" => $url, "LINKS" => $links, "L_POWERED_BY" => L_POWERED_BY, "RC" => $rc_link, "ACP" => $admin_link, "GLOBAL_MENU" => $global_menu, "AREA" => $page_title, "SITELINK" => '<a href="' . $url . '" class="normfont">' . $sitename . '</a>', "PAGETITLE" => '' . $sitename . ' - ' . strip_tags($page_title), "VERSION" => $version));
		$output = preg_replace('/\<!-- BEGIN logged_out -->(.*?)\<!-- END logged_out -->/is', '', $output);
		echo $output;

		break;
	default:
		echo 'No function declared';
		break;
}

die();