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
	$email = '';
	$pass = '';
	$cookie = false;
		if ( isset($_POST['username']) ){
             $email = $secure->clean($_POST['username']);
        }

        if ( isset($_POST['pass']) ){
            $pass = $secure->clean(md5($_POST['pass']));
        }
		
		if( isset($_POST['remember'])){
			if($_POST['remember'] == '1')
				$cookie = true;
		}

        if ( !empty($email) && !empty($pass) )
        {
            // CHECK EMAIL AND DEFINE LOGIN SQL
            $check_email = $secure->verify_email($email);
            if ($check_email == 'exist')
            {
                $logged = $db->fetch("SELECT * FROM accounts WHERE `email` = '$email' AND `password` = '$pass'");
            } else {
                $logged = $db->fetch("SELECT * FROM accounts WHERE `name` LIKE '$email' AND `password` = '$pass'");
            }

            if ( !$logged )
            {
                echo 1;
            } else {
            	$lID = $logged['id'];
                $_SESSION[$system->data('Universal_Session').'_id'] = $lID;
                $lpip = substr(hash('sha256', uniqid(rand(), true)),0,8);
                $_SESSION[$system->data('Universal_Session').'_lpip'] = "$lpip";
				if($cookie == true){
					setcookie($system->data('Universal_Session').'_id', $logged['id'], time()+(30*24*60*60), '/'); 
					setcookie($system->data('Universal_Session').'_lpip', $lpip, time()+(30*24*60*60), '/'); 
				}
                $update = $db->query("UPDATE accounts SET lpip = '$lpip', lastlogin = UNIX_TIMESTAMP() WHERE `id` = '$lID'");
                echo 'logged';
            }

        } else {
			echo 0;
        }
die();

?>
