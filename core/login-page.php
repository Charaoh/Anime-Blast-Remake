<?php

$email = '';
$pass = '';
if ( isset($_POST['username']) ){
    $email = $secure->clean($_POST['username']);
  }

if ( isset($_POST['pass']) ){
    $pass = $secure->clean(md5($_POST['pass']));
}
$error = '';

if( !empty($email) && !empty($pass) )
{
   //CHECK EMAIL AND DEFINE LOGIN SQL
   $check_email = $secure->verify_email($email);
   if ($check_email == 'exist'){
	   $login = $db->fetch("SELECT email FROM accounts WHERE `email` = '$email' AND `password` = '$pass'");
	} else {
		$login = $db->fetch("SELECT email FROM accounts WHERE `name` LIKE '$email' AND `password` = '$pass'");
		$email = $login['email'];
	}
	if ( !$login )
	{
       $error = 'Credentials are wrong!';
    } else {
		$_SESSION[$system->data('Universal_Session').'_email'] = $login['email'];
		$lpip = rand(500,20000);
		$_SESSION[$system->data('Universal_Session').'_lpip'] = "$lpip";
		$db->query("UPDATE accounts SET lpip = '$lpip', lastlogin = UNIX_TIMESTAMP() WHERE `email` = '$email' AND `password` = '$pass'");
        $system->redirect('./');
	}

}else{
	
	if(isset($_POST['submit']))
		$error = 'Please fill out the form!';
}
$global_menu = '';
$tpl = $STYLE->open('login.tpl');
if(empty($error))
	$tpl = str_replace(array($STYLE->getcode('error',$tpl)), '', $tpl);
else
	$tpl = $STYLE->tags($tpl, array("ERROR" => $error));

$tpl = $STYLE->tags($tpl, array("L_LOGIN" => L_LOGIN, "L_NAME" => L_NAME, "L_PASSWORD" => L_PASSWORD, "L_LOSTPASSWORD" => L_LOST_PASSWORD));

$output .= $tpl;
?>