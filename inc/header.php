<?php

/*
 * Project Name: KaiBB - http://www.kaibb.co.uk
 * Author: Christopher Shaw
 * This file belongs to KaiBB, it may be freely modified but this notice, and all copyright marks must be left
 * intact. See COPYING.txt
 */

require("db.php");
require("function.php");
require("config.php");

$system = new system();
$secure = new secure();
$user = new user();

// LOAD DATABASE
$db = new db();
$db->connect($dbhost, $dbuser, $dbpassword, $dbmaster);

// SESSION
session_name($system->data('session'));
session_start();
date_default_timezone_set('UTC');

// CHECK IF USER IS LOGGED IN
if (isset($_COOKIE[$system->data('Universal_Session') . '_id']))
	$_SESSION[$system->data('Universal_Session') . '_id'] = $_COOKIE[$system->data('Universal_Session') . '_id'];

if (isset($_COOKIE[$system->data('Universal_Session') . '_lpip']))
	$_SESSION[$system->data('Universal_Session') . '_lpip'] = $_COOKIE[$system->data('Universal_Session') . '_lpip'];

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
/*if($account){
	if($account['group'] == 5)
	{
		ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
	}
}*/
if ($path) {
	$siteaddress = "$url/$path/";
} else {
	$siteaddress = "$url/";
}
$current_url = $secure->clean($_SERVER['QUERY_STRING']);
if ($current_url) {
	parse_str($current_url, $where);
}
$version = $system->data('version');
if ($account['group'] == '3' && $system->group_permission($account['group'], 'version') != $system->data('version'))
	$version = $system->group_permission($account['group'], 'version');
if ($account['group'] == '4' && $system->group_permission($account['group'], 'version') != $system->data('version'))
	$version = $system->group_permission($account['group'], 'version');
if ($account['group'] == '5' && $system->group_permission($account['group'], 'version') != $system->data('version'))
	$version = $system->group_permission($account['group'], 'version');
if ($account['group'] == '6' && $system->group_permission($account['group'], 'version') != $system->data('version'))
	$version = $system->group_permission($account['group'], 'version');

$document_root = $_SERVER['DOCUMENT_ROOT'];
$root = "$document_root/$path";

// SESSION
$session_location = $_SERVER['REQUEST_URI'];
$session_id = session_id();
$ip = $_SERVER['REMOTE_ADDR'];
$landscape = false;
if ($account['id']) {
	$id = $account['id'];
} else {
	$id = '-1';
}
$online = $system->manageOnline();

// STYLES SYSTEM
$template = $system->data('template');
if (!empty($account['tpl']) && $system->data('usertemplate') == '1')
	$template = $account['tpl'];

if (!empty($account['tpl']) && $system->group_permission($account['group'], 'templates') !== '0')
	$template = $account['tpl'];
require("parser.php");
$STYLE = new style();

// LANGUAGE
$language = $system->data('language');
require("$root/lang/$language/index.php");

// PARSE STYLE
$output = '';
$description = '';
$metatags = '';
$landscape = '';
$tpl = $STYLE->open('header.tpl');

// CHANGE NAVIGATION
// Find Your Group
$group_id = $user->group($account['id']);
$current_url = preg_replace('/^([^&]*).*$/', '$1', str_replace(array($siteaddress), '', $system->current_url()));
$activate = "";
if ($account) {
	$mail = $db->query("SELECT id FROM " . $prefix . "_mail WHERE to_id = '" . $account['id'] . "' AND marked = '0'")->rowCount();
	$notification = '';
	if ($mail > 0) {
		$notification = '<p class="sitealert" style="margin-left: -2em;margin-top:-1.4em">' . $mail . '</p>';
	}
	$time =  L_TIME . ': ' . $system->time(time(), 'g:i A');
	$exp = 0;
	$width = 0;
	$ulevel = $db->query("SELECT * FROM levels WHERE experience < '" . ($account['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");
	if ($ulevel->rowCount() > 0) {
		$ulevel = $ulevel->fetch();
		$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($ulevel['id'] + 1) . "'");
		if (!$next) {
			$width = 100;
			$exp = 'Maxed out';
		} else {
			$exp = $account['experience'] . '/' . $next['experience'];
			$width = round(($account['experience'] / $next['experience']) * 100);
		}
		$ulevel = $ulevel['id'];
		$ulevel = $user->level($account['experience']);
	} else {
		$ulevel = $db->fetch("SELECT * FROM levels WHERE id = '1'");
		$exp = '0/' . $ulevel['experience'];
		$ulevel = 1;
	}
	$clan = $db->fetch("SELECT * FROM `clan-members` WHERE `account_id` ='" . $account['id'] . "'");
	$menu = '';
	$menu .= (($clan) ? '<a href="' . $siteaddress . 'clan/profile/' . urlencode($db->fieldFetch('clans', $clan['clan_id'], 'name')) . '">Clan Panel</a>' : '<a href="' . $siteaddress . 'clans">Join a clan</a>');
	$menu .= '<a href="' . $siteaddress . 'control-panel">Account Settings</a>';
	$menu .= '<a href="' . $siteaddress . 'control-panel?mode=avatar">Change Avatar</a>';
	$menu .= '<a href="' . $siteaddress . 'control-panel?mode=signature">Change Signature</a>';
	if ($system->group_permission($group_id, 'acp') == '1') {
		$menu .= '<a href="' . $siteaddress . 'acp/">Admin Panel</a>';
	}
	if ($system->group_permission($group_id, 'staff') == '1') {
		$menu .= '<a href="' . $siteaddress . 'staff/">Staff Panel</a>';
	}
	/* if ($system->group_permission($user->group($account['id']), 'rc') == '1') {
		$menu .= '<a href="' . $siteaddress . 'rc/" class="normfont">' . L_RESOLUTION_CENTRE . '</a>';
	} */

	if ($account['experience'] == 0)
		$account['experience'] = $account['experience'] + 1;
	$me = $db->fetch("SELECT * FROM levels WHERE experience < '" . $account['experience'] . "' ORDER BY experience DESC LIMIT 1");
	$links = '<a href="' . $siteaddress . 'report" class="normfont">' . L_REPORT . '</a> | <a href="' . $siteaddress . 'terms-of-service" class="normfont">' . L_TERMS_OF_SERVICE . '</a> | <a href="' . $siteaddress . 'the-team" class="normfont">' . L_GROUPS . '</a>';
	$discount = '';
	if (!empty($system->data('discount')))
		$discount = $system->data('discount') . ' OFF!';
	else
		$tpl = preg_replace('/\<!-- BEGIN discount -->(.*?)\<!-- END discount -->/is', '', $tpl);
	if ($clan) {
		$clan = '<a href="./clan/profile/' . urlencode($db->fieldFetch('clans', $clan['clan_id'], 'name')) . '" class="clanlink"><span>' . $db->fieldFetch('clans', $clan['clan_id'], 'abbreviation') . '</span>' . $user->image($clan['clan_id'], 'clans', './', 'clansneak') . '</a>';
	} else {
		$clan = '';
	}
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
						$activate .= $user->image($exclusive, 'characters', './', 'ucp__exclusives--img');
				}
				$activate .= '</a>';
			}
		}
	}
	// Missions code
	/*$missions = $db->query("SELECT * FROM `missions` ORDER BY `level`+0 ASC");
	$todo = '';
	$available = '';
	$inprogress = '';
	while($mission = $missions->fetch()){
		if($mission['hidden'] == 1) continue;
		$finished = $db->query("SELECT * FROM complete WHERE account = '".$account['id']."' AND mission = '".$mission['id']."'");
		if($finished->rowCount() > 0) continue;
		$level = $db->fetch("SELECT * FROM levels WHERE id = '".$mission['level']."'");
		
		if($account['experience'] > $level['experience']){
			$requirements = $db->query("SELECT * FROM requires WHERE mid = '".$mission['id']."' ORDER BY id");
			$ramount = $requirements->rowCount();
			$check = $db->query("SELECT * FROM progress WHERE mission = '".$mission['id']."' AND account = '".$account['id']."' ORDER BY id")->rowCount();
			if(abs($ramount-$check) != 0){
				if(empty($available))
					$available = '<h1 class="button">Available Missions</h1>';
				if(strpos($mission['oncomplete'], '|') !== false){
					$rewards = explode('|', $mission['oncomplete']);
				}else{
					$rewards = array($mission['oncomplete']);
				}
				$r = '';
				foreach($rewards as $reward){
					$it = explode(':',$reward);
					switch($it[0]){
						case 'C':
							$r .= 'Unlocks <br/>'.$user->image($it[1],'characters','./').'<br/>' ;
							break;
						case 'G':
							$r .= '<p style="color: gold;">Gives you '.$it[1].' BC</p><br/>';
							break;
					}
				}
				$accept = '';
				$we = '';
				if(!empty($mission['missions'])){
					$check = explode(',',$mission['missions']);
					
					foreach($check as $c){
						$finished = $db->query("SELECT * FROM complete WHERE account = '".$account['id']."' AND mission = '".$c."'");
						if($finished->rowCount() == 0)
							$we .= '<em>Mission Required: '.$db->fieldFetch('missions',$c,'name').'</em><br/>';
					}
				}
				if(!empty($we))
					$accept = $we;
				else
					$accept = '<span data-mission="'.$mission['id'].'" style="color: green;margin-right: 25px;">Accept</span>';
				$available .= '<div class="mission">'.$user->image($mission['id'],'missions','./').'
			<p class="title">'.$mission['name'].'</p>
			<div class="requirements">
				Rewards: <br/><br/>
				'.$r.'
			</div>
			<p class="option">'.$accept.'<a href="/missions#mission'.$mission['id'].'">View</a></p>
			</div>';
			}else{
				$complete = 0;
				$tasks = '';
				while($requirement = $requirements->fetch()){
					$check = $db->fetch("SELECT * FROM progress WHERE mission = '".$mission['id']."' AND account = '".$account['id']."' AND requirement = '".$requirement['id']."'");
					if(!isset($check)) continue;
					$tasks .= '<li>';
					if(abs($check['count']-$requirement['count']) == 0){
						$complete++;
						$tasks .= '<b>';
					}
					if(!empty($requirement['description'])){
						$tasks .= $requirement['description'];
						goto thend;
					}
					if($requirement['streak'] == 1 && !empty($requirement['winwith']))
						$tasks .= 'Win '.$requirement['count'].' battles in a row with '.$db->fieldFetch('characters', $requirement['winwith'], 'name');
					if($requirement['streak'] == 0 && !empty($requirement['winwith']) && empty($requirement['beatacharacter']))
						$tasks .= 'Win with '.$db->fieldFetch('characters', $requirement['winwith'], 'name').' '.$requirement['count'].' time(s)';
					if($requirement['streak'] == 0 && !empty($requirement['winwith']) && !empty($requirement['beatacharacter']))
						$tasks .= 'Beat '.$db->fieldFetch('characters', $requirement['beatacharacter'], 'name').' with '.$db->fieldFetch('characters', $requirement['winwith'], 'name').' '.$requirement['count'].' time(s)';
					if($requirement['streak'] == 0 && empty($requirement['winwith']) && !empty($requirement['beatacharacter']))
						$tasks .= 'Beat '.$db->fieldFetch('characters', $requirement['beatacharacter'], 'name').' '.$requirement['count'].' time(s)';
					
					thend:
					$tasks .= ' '.(float)$check['count'].' / '.$requirement['count'];
					if(abs($check['count']-$requirement['count']) == 0)
						$tasks .= '</b>';
					$tasks .= '</li>';
				}
				if(empty($tasks))
					continue;
				if(empty($inprogress))
					$inprogress = '<h1 class="button">Mission Progress</h1>';
				if(strpos($mission['oncomplete'], '|') !== false){
					$rewards = explode('|', $mission['oncomplete']);
				}else{
					$rewards = array($mission['oncomplete']);
				}
				$r = '';
				foreach($rewards as $reward){
					$it = explode(':',$reward);
					switch($it[0]){
						case 'C':
							$r .= 'Unlocks <br/>'.$user->image($it[1],'characters','./').'<br/>' ;
							break;
						case 'G':
							$r .= '<p style="color: gold;">Gives you '.$it[1].' BC</p><br/>';
							break;
					}
				}
				$inprogress .= '<div class="mission">'.$user->image($mission['id'],'missions','./').'
			<p class="title">'.$mission['name'].'</p>
			<ul class="requirements">
					'.$tasks.'
			</ul>
			<div class="requirements">
				Rewards: <br/><br/>
				'.$r.'
			</div>
			</div>';
			}
		}else{
				if(empty($todo))
					$todo = '<h1 class="button" style="background: #ff0000;text-shadow: 0px 0px 2px #000000;width: 85%;">
				Next rank -> '.$level['level'].' ( '.(($level['experience']+1)-$account['experience']).' EXP Needed )</h1>';
				if(strpos($mission['oncomplete'], '|') !== false){
					$rewards = explode('|', $mission['oncomplete']);
				}else{
					$rewards = array($mission['oncomplete']);
				}
				$r = '';
				foreach($rewards as $reward){
					$it = explode(':',$reward);
					switch($it[0]){
						case 'C':
							$r .= 'Unlocks <br/>'.$user->image($it[1],'characters','./').'<br/>' ;
							break;
						case 'G':
							$r .= '<p style="color: gold;">Gives you '.$it[1].' BC</p><br/>';
							break;
					}
				}
				$todo .= '<div class="mission">'.$user->image($mission['id'],'missions','./').'
			<p class="title">'.$mission['name'].'</p>
			<div class="requirements">
				Rewards: <br/><br/>
				'.$r.'
			</div>
			<p class="option"><a href="/missions#mission'.$mission['id'].'">View</a></p>
			</div>';
		}
		
	}
	if(empty($available) && empty($inprogress) && empty($todo))
		$available = '<h1 class="button">Congratulations! You have finished all your tasks</h1>';
	*/
	$tpl = $STYLE->tags($tpl, array("CLAN" => $clan, "DISCOUNT" => $discount, /* "AVAILABLE" => $available, "MISSIONS" => $inprogress,"NEXT" => $todo,*/ "EXCLUSIVE" => $activate, "LINKS" => $links, "TIME" => $time, "NOTIFICATION" => $notification, "AVATAR" => (strpos($_SERVER['REQUEST_URI'], 'acp', 'staff') !== false) ? $user->avatar($account['id'], './../') : $user->avatar($account['id']), "LEVEL" => $ulevel, "EXPERIENCE" => $exp, "WIDTH" => $width, "USERNAME" => $user->name($account['id']), "MENU" => $menu));
}

$description = $system->data('meta_info');
$metatags =  $system->data('meta_keywords');
$base = (strpos($current_url, 'acp') !== false) ? '' : '<base href="/" />';
$output .= $STYLE->tags($tpl, array("FAV" => '/favicon.ico?v=' . filemtime($root . '/favicon.ico'), "BASE" => $base, /* "NEWS" => $view, */ "L_HOME" => L_HOME, "L_REGISTER" => L_REGISTER, "L_MAIL" => L_MAIL, "L_LOGIN" => L_LOGIN, "L_LOGOUT" => L_LOGOUT, "L_ACCOUNT" => L_ACCOUNT));

// Check Banlist
$banned = false;
$user_ip = $secure->clean($_SERVER['REMOTE_ADDR']);
$ban_sql = $db->fetch("SELECT * FROM " . $prefix . "_banlist WHERE value = '$user_ip'");
if ($ban_sql) {
	session_destroy();
	if (isset($_COOKIE[$system->data('Universal_Session') . '_email'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_email']);
		setcookie($system->data('Universal_Session') . '_email', null, time() - (30 * 24 * 60 * 60));
	}
	if (isset($_COOKIE[$system->data('Universal_Session') . '_lpip'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_lpip']);
		setcookie($system->data('Universal_Session') . '_lpip', null, time() - (30 * 24 * 60 * 60));
	}
	$system->page(L_BANNED, L_BANNED_IP);
}
if ($account['bantime'] > time()) {
	session_destroy();
	if (isset($_COOKIE[$system->data('Universal_Session') . '_email'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_email']);
		setcookie($system->data('Universal_Session') . '_email', null, time() - (30 * 24 * 60 * 60));
	}
	if (isset($_COOKIE[$system->data('Universal_Session') . '_lpip'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_lpip']);
		setcookie($system->data('Universal_Session') . '_lpip', null, time() - (30 * 24 * 60 * 60));
	}
	$system->page(L_TEMPORARY_BAN, str_replace('[TIME]', $system->time($account['bantime']), L_TEMPORARY_BAN_MSG));
}

if ($account['frozen'] == '1') {
	session_destroy();
	if (isset($_COOKIE[$system->data('Universal_Session') . '_email'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_email']);
		setcookie($system->data('Universal_Session') . '_email', null, time() - (30 * 24 * 60 * 60));
	}
	if (isset($_COOKIE[$system->data('Universal_Session') . '_lpip'])) {
		unset($_COOKIE[$system->data('Universal_Session') . '_lpip']);
		setcookie($system->data('Universal_Session') . '_lpip', null, time() - (30 * 24 * 60 * 60));
	}
	$system->page('Account banned', 'This account has been frozen for being misused.');
}

if ($system->data('siteclosed') !== '0' && $system->group_permission($account['group'], 'ma') != '1') {
	$output = $STYLE->open('maintenance.tpl');
	include("footer.php");
	exit();
}

// Ajax request check ...
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$output = '';
}