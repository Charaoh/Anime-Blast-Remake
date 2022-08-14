<?php

/*
 * Project Name: KaiBB - http://www.kaibb.co.uk
 * Author: Christopher Shaw
 * This file belongs to KaiBB, it may be freely modified but this notice, and all copyright marks must be left
 * intact. See COPYING.txt
 */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	echo $output;
	die();
}

if (!isset($page_title)) {
	$page_title = L_HOME;
}

if (!isset($metatags)) {
	$metatags = '';
}

if (!isset($global_menu)) {
	$global_menu = '';
}

$system = new system();
$version = $system->data('version');
$sitename = $system->data('sitename');
$url = $system->data('url') . '/' . $system->data('path') . '/';

// Check if activated
$url = $system->data('url');
$path = $system->data('path');
if ($path) {
	$siteaddress = "$url/$path/";
} else {
	$siteaddress = "$url/";
}

$ladders = '';
$gold = '';
$clanrank = "";
$streaks = '';
$activate = '';

if (isset($section) && $section === 'game') {
	$output = preg_replace('/\<!-- BEGIN content -->(.*?)\<!-- END content -->/is', '', $output);
	$output = preg_replace('/\<!-- BEGIN ads -->(.*?)\<!-- END ads -->/is', '', $output);
	goto output;
}

if ($system->data('siteclosed') !== '0' && $system->group_permission($account['group'], 'ma') !== '1') {
	$bg = rand(1, 9);
	$shadow = '';
	switch ($bg) {
		case '1':
			$bg = ' style="background: url(./tpl/default/img/m-1.png) 500px 0 no-repeat;
    background-size: 700px;
    background-color: #fbf5f4;"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px red;"';
			break;
		case '2':
			$bg = ' style="background: url(./tpl/default/img/m-2.png) 750px -80px no-repeat;
    background-size: 500px;
    background-color: #e5821c;}"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px #d4c4ad;"';
			break;
		case '3':
			$bg = ' style="background: url(./tpl/default/img/m-3.png) 700px 0 no-repeat;
    background-size: 500px;
    background-color: #f9caa8;"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px #fcaa42;"';
			break;
		case '4':
			$bg = ' style="background: url(./tpl/default/img/m-4.png) 700px 0 no-repeat;
    background-size: 500px;
    background-color: #ebecee;"';
			$shadow = ' style="
    box-shadow: 0px 0px 10px 0px #3c6eff;"';
			break;
		case '5':
			$bg = ' style="    background: url(https://geekandsundry.com/wp-content/uploads/2016/03/JPEG-Promo-15.jpg) 500px 0 no-repeat;
    background-size: 600px;
    background-color: #ffffff;"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px #ffffff;"';
			break;
		case '6':
			$bg = ' style="    background: url(./tpl/default/img/m-6.png) 700px 0 no-repeat;
    background-size: 515px;
    background-color: #03A9F4;"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px #ffffff;"';
			break;
		case '7':
			$bg = ' style="    background: url(https://geekandsundry.com/wp-content/uploads/2016/03/JPEG-Promo-15.jpg) 500px 0 no-repeat;
    background-size: 600px;
    background-color: #ffffff;"';
			$shadow = ' style="box-shadow: 0px 0px 10px 0px #ffffff;"';
			break;
		case '8':
			$bg = ' style="background: url(./tpl/default/img/m-8.png) 700px 0 no-repeat;
    background-size: 400px;
    background-color: #f6f6f6;"';
			$shadow = ' style="    box-shadow: 0px 0px 10px 0px #434548;"';
			break;
		case '9':
			$bg = ' style="    background: url(./tpl/default/img/m-9.png) 700px 0 no-repeat;
    background-size: 350px;
    background-color: #d3d4d7;"';
			$shadow = ' style="    box-shadow: 0px 0px 10px 0px #df97a6;"';
			break;
		default:
			break;
	}
	$output = $STYLE->tags($output, array("BASE" => $base, "TPL" => $template, "METAINFO" => $description, "METAKEYWORDS" => $metatags, "URL" => $url, "SITENAME" => $sitename, "PAGETITLE" => strip_tags($page_title), "ELAPSED" => $system->humanTiming($system->data('siteclosed')), "BG" => $bg, "SHADOW" => $shadow));
	print $output;
	$STYLE->close();
	$db->close();
	die();
}

$tpl = $STYLE->open('footer.tpl');
$output .= $STYLE->tags($tpl, array("TPL" => $template));


$players = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 5;");

$count = 1;
while ($player = $players->fetch()) {

	$style = '';
	$width = 0;
	$level = $db->fetch("SELECT * FROM levels WHERE experience < '" . $player['experience'] . "' ORDER BY experience+0 DESC LIMIT 1");
	if ($level) {
		if ($player['experience'] !== 0) {
			$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($level['id'] + 1) . "'");
			if (!empty($next))
				$width = round(($player['experience'] / $next['experience']) * 100);
			else
				$width = 100;
		}
		$level = $user->level($player['experience']);
	} else {
		$level = 1;
	}
	if ($count === 1) {
		$style = 'style="font-size: 15px;"';
	} elseif ($count === 2) {
		$style = 'style="font-size: 14px;"';
	} elseif ($count === 3) {
		$style = 'style="font-size: 13px;"';
	}


	$ladders .= '
		<li ' . $style . '>
				<span>' . $count . '</span>' . $user->name($player['id']) . '
				<div class="levelBackground">
				<div class="levelFill" style="width: ' . $width . '%;"></div>
				<div class="levelNumber">' . $level . '</div>
				</div>
			</li>';
	$count++;
}
$clans = $db->query("SELECT * FROM clans ORDER BY experience+0 DESC LIMIT 5;");

$count = 1;

while ($clan = $clans->fetch()) {


	$style = '';
	if ($count === 1) {
		$style = 'style="font-size: 15px;"';
	} elseif ($count === 2) {
		$style = 'style="font-size: 14px;"';
	} elseif ($count === 3) {
		$style = 'style="font-size: 13px;"';
	}
	$width = 0;
	$level = $db->fetch("SELECT * FROM levels WHERE experience < '" . $clan['experience'] . "' ORDER BY experience+0 DESC LIMIT 1");
	if ($level) {
		if ($clan['experience'] !== 0) {
			$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($level['id'] + 1) . "'");
			if (!empty($next))
				$width = round(($clan['experience'] / $next['experience']) * 100);
			else
				$width = 100;
		}
		$level = $user->level($clan['experience']);
	} else {
		$level = 1;
	}
	$clanrank .= '
       <li ' . $style . '>
				<span>' . $count . '</span><a href="./clan/profile/' . urlencode($clan['name']) . '">' . $clan['name'] . '</a>
				<div class="levelBackground">
				<div class="levelFill" style="width: ' . $width . '%;"></div>
				<div class="levelNumber">' . $level . '</div>
			</li>';
	$count++;
}
$streak = $db->query("SELECT * FROM accounts ORDER BY streak+0 DESC LIMIT 5;");

$count = 1;
while ($player = $streak->fetch()) {

	$style = '';
	if ($count === 1) {
		$style = 'style="font-size: 14px;"';
	} elseif ($count === 2) {
		$style = 'style="font-size: 13px;"';
	} elseif ($count === 3) {
		$style = 'style="font-size: 12px;"';
	}
	$streaks .= '
       <li ' . $style . '>
				<span>' . $count . '</span>' . $user->name($player['id']) . '<br/>
				Streak ' . (((float)$player['streak'] >= 0) ? '+' . $player['streak'] : $player['streak']) . '
			</li>';
	$count++;
}

$current_url = preg_replace('/^([^&]*).*$/', '$1', str_replace(array($siteaddress), '', $system->current_url()));

if (!$account) {
	// Check if registration is open
	if ($system->data('userreg') !== '1') {
		$output = preg_replace('/\<!-- BEGIN registration -->(.*?)\<!-- END registration -->/is', '', $output);
	}
	$output = preg_replace('/\<!-- BEGIN logged_in -->(.*?)\<!-- END logged_in -->/is', '', $output);
} else {

	if ($system->data('activation') === '1' && $account['activated'] !== '1' && strpos($current_url, '?s=activate') === false) {

		$activate = '<a href="' . $siteaddress . 'activation"><p style="margin-top: 10px;color: #df193c;font-size: 10px;word-break: break-word;padding-left: 5px;margin-bottom: 21px;">
	<span style="width: 53%;display: block;float: left;text-align: center;">Activate your account for an exclusive character!</span>
	<img src="https://www.anime-blast.com/images/characters/91.jpg" style="width: 30px;border: 1px solid black;margin-right: 2px;">
	<img src="https://www.anime-blast.com/images/characters/93.jpg" style="width: 30px;border: 1px solid black;margin-right: 2px;">
	<img src="https://www.anime-blast.com/images/characters/94.jpg" style="width: 30px;border: 1px solid black;"></p> </a>';
	}
	$output = preg_replace('/\<!-- BEGIN logged_out -->(.*?)\<!-- END logged_out -->/is', '', $output);
}

output:
if ($account)
	$db->query("UPDATE `accounts` SET `stalkme` = '" . $page_title . "' WHERE `id` = '" . $account['id'] . "';");

$javascript = '';
$scripts = '';
foreach ($STYLE->__get('files') as $type => $content) {

	foreach ($content as $file => $java) {

		if ($type === 'CSS')
			$scripts .= '<link rel="stylesheet" type="text/css" href="' . $file . '">';

		if ($type === 'JAVA')
			$javascript .= '<script src="' . $file . '" type="text/javascript">' . $java . '</script>';
	}
}

$output = $STYLE->tags($output, array(
	"LANDSCAPE" => ($landscape) ? '<link rel="manifest" href="./manifest.json">' : '',
	"SCRIPTS" => $scripts,
	"METAINFO" => $description,
	"METAKEYWORDS" => $metatags,
	"ACTIVATE" => $activate,
	"RANKED" => $ladders,
	"RANKED-CLAN" => $clanrank,
	"RANKED-STREAK" => $streaks,
	"URL" => $url,
	"GLOBAL_MENU" => $global_menu,
	"AREA" => $page_title,
	"SITELINK" => '<a href="' . $siteaddress . '" class="normfont">' . $sitename . '</a>',
	"PAGETITLE" => strip_tags($page_title),
	"VERSION" => $version
));
print $output . $javascript;
$STYLE->close();
$db->close();