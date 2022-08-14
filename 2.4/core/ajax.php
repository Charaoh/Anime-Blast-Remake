<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    require('index.php');
    exit;
}
define('SITE', 'www.adumbralartz.com');
$json = array();
require("../../inc/db.php");
require("../../inc/function.php");
require("../../inc/config.php");
$system = new system();
$secure = new secure();
$user = new user();
$game = new game();
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
$domain = $system->data('url');
$path = $system->data('path');
if ($path) {
    $siteaddress = "$domain/$path/";
} else {
    $siteaddress = "$domain/";
}
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
$template = $system->data('template');
if (!empty($account['tpl']) && $system->data('usertemplate') == '1')
    $template = $account['tpl'];

if (!empty($account['tpl']) && $system->group_permission($account['group'], 'templates') !== '0')
    $template = $account['tpl'];
require("../../inc/parser.php");
$STYLE = new style();
$language = $system->data('language');
$version = $system->data('version');
//Delete if necessary.
if ($account['group'] == '3' && $system->group_permission($account['group'], 'version') != $system->data('version'))
    $version = $system->group_permission($account['group'], 'version');
if ($account['group'] == '4' && $system->group_permission($account['group'], 'version') != $system->data('version'))
    $version = $system->group_permission($account['group'], 'version');
if ($account['group'] == '5' && $system->group_permission($account['group'], 'version') != $system->data('version'))
    $version = $system->group_permission($account['group'], 'version');
$_path = './' . $version . '/tpl/' . $template . '/';
require("" . $root . "/lang/$language/index.php");
require("../inc/ajaxHandler.php");
$ajax = new Ajax;
$f = (isset($_POST['f'])) ? $secure->clean($_POST['f']) : 'error';
switch ($f) {

    case 'getCharacter':
        if (!isset($_POST['i'])) {
            $json['error'] = 'params';
            break;
        } else {
            $id = $secure->clean($_POST['i']);
            if ($id == 0) {
                $json['error'] = 'undefined';
                break;
            }
        }
        $ajax->getCharacter($id);
        break;
    case 'getSkill':
        if (!isset($_POST['i'])) {
            $json['error'] = 'params';
            break;
        } else {
            $id = $secure->clean($_POST['i']);
            if ($id == 0) {
                $json['error'] = 'params';
                break;
            }
        }
        $ajax->getSkill($id);
        break;
    case 'updateVolume':
        $v = $secure->clean($_POST['v']);
        $w = $secure->clean($_POST['w']);
        $ajax->updateVolume($w, $v);
        break;
    case 'getTeam':
        $ajax->getTeam();
        break;
    case 'cancelMatch':
        $ajax->cancelMatch();
        break;
    case 'checkMatch':
        $ajax->checkMatch();
        break;
    case 'setTeam':
        $id = $secure->clean($_POST['i']);
        $ajax->setTeam($id);
        break;
    case 'checkStatus':
        $argument = false;
        if (isset($_POST['i']))
            $argument = $secure->clean($_POST['i']);
        $ajax->checkStatus($argument);
        break;
    case 'return_':
        $what = isset($_POST['w']) ? $secure->clean($_POST['w']) : false;
        $value = isset($_POST['v']) ? $secure->clean($_POST['v']) : false;
        $ajax->return_($what, $value);
        break;
    case 'verifySkill':
        $ajax->verifySkill();
        break;
    case 'getTargets':
        $ajax->getTargets();
        break;
    case 'checkTarget':
        $ajax->checkTarget();
        break;
    case 'setTimeout':
        $ajax->setTimeout();
        break;
    case 'manaCap':
        $ajax->manaCap();
        break;
    case 'saveTeam':
        $name = $secure->clean($_POST['i']);
        $ajax->saveTeam($name);
        break;
    case 'selectTeam':
        $id = $secure->clean($_POST['i']);
        $ajax->selectTeam($id);
        break;
    case 'deleteTeam':
        $id = $secure->clean($_POST['i']);
        $ajax->deleteTeam($id);
        break;
    case 'buyThis':
        $id = $secure->clean($_POST['i']);
        $ajax->buyThis($id);
        break;
    case 'buyBox':
        $id = $secure->clean($_POST['i']);
        $ajax->buyBox($id);
        break;
    case 'openBox':
        $id = $secure->clean($_POST['i']);
        $ajax->openBox($id);
        break;
    case 'changesfx':
        $id = $secure->clean($_POST['i']);
        $ajax->change('sfx', $id);
        break;
    case 'changegui':
        $id = $secure->clean($_POST['i']);
        $ajax->change('gui', $id);
        break;
    case 'changeselection':
        $id = $secure->clean($_POST['i']);
        $ajax->change('selection', $id);
        break;
    case 'changeingame':
        $id = $secure->clean($_POST['i']);
        $ajax->change('ingame', $id);
        break;
    default:
        $json['error'] = 'function';
        break;
}

if ($json)
    echo json_encode($json);

die();
