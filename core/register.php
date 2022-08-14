<?php

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
	$name = '';
	$email = '';
	$password = ''; 
	$password_confirm = '';
	if (isset($_POST['username'])) {
        $name = $secure->clean($_POST['username']);
    } 
    if (isset($_POST['email'])) {
        $email = $secure->clean($_POST['email']);
    } 
    if (isset($_POST['pass'])) {
        $password = $secure->clean($_POST['pass']);
    }
	if (isset($_POST['confpass'])) {
        $password_confirm = $secure->clean($_POST['confpass']);
    }
    // Ensure New Password is Confirmed
    if ($password != $password_confirm) {
        echo 1;
		return;
    }
    // Prevent Nullifying of Password
    if ($password == '') {
       echo 2;
	   return;
    }

    // Generate ACTIVATION_CODE
    //$activation_code = $secure->password();
	$activation_code = '123';
	
    // Check if fields are blank
    if (!isset($name)) {
        echo 3;
	   return;
    }
    if (!isset($email)) {
        echo 4;
	   return;
    }
    // Check if black listed or false
    if ($secure->verify_name($name) == 'exist') {
        echo 5;
		return;
    }
    // Ensure Name is not Banned
    if ($secure->verify_name($name) == 'banned') {
        echo 6;
		return;
    }
    if ($secure->verify_email($email) == 'exist') {
        echo 7;
		return;
    }
    if ($secure->verify_email($email) == 'banned') {
        echo 8;
		return;
    }
    // Create This Account
    $password = md5($password);
    $ip = $secure->clean($_SERVER['REMOTE_ADDR']);

	if ($system->data('activation') == '1') {
        $email_message = $system->data('activation-email').'<br/> Activation code: '.$activation_code.' <a href=\"' . $siteaddress . '?s=activate\">Click here to activate your account and claim your reward!</a>';
        //$system->email($email, 'Welcome to the aBlast community!', $email_message);
        $activated = '0';
    } else {
        $activated = '1';
    }

    $insert_user = $db->query("INSERT INTO accounts (name, email, password, ip , joined, lastlogin, activation_code, activated, characters, `group`, `tpl`) VALUES ('$name', '$email' , '$password', '$ip' , UNIX_TIMESTAMP(), UNIX_TIMESTAMP(),'$activation_code','1','".$system->data('starters')."','".$system->data('newby')."','christmas')");
    $get_user = $db->fetch("SELECT * FROM accounts WHERE ip = '$ip' ORDER BY id DESC LIMIT 1");
    $permission_group = $db->query("INSERT INTO " . $prefix . "_groups_members (account_id,group_id) VALUES ('" . $get_user['id'] . "','2')");
    $_SESSION[$system->data('Universal_Session').'_id'] = $get_user['id'];
    $lpip = substr(hash('sha256', uniqid(rand(), true)),0,8);
    $_SESSION[$system->data('Universal_Session').'_lpip'] = "$lpip";
    $update = $db->query("UPDATE accounts SET lpip = '$lpip', lastlogin = UNIX_TIMESTAMP() WHERE `id` = '".$get_user['id']."' AND `password` = '".$get_user['password']."'");
	echo 'logged';
	return;

?>