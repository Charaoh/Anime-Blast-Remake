<?php


header("Location: ../");

/*
$version = '1.0';

require('./' . $version . '/inc/header.php');

$pg = '';

if (!$account) {
    if ($login) {
        include('./' . $version . '/core/login.php');
        require('./' . $version . '/inc/footer.php');
        die;
    }
}
if ($herr) {
    $query = $db->query("SELECT * FROM matchup WHERE uid = '" . $account['id'] . "' AND type = 'private'");
    if ($query->rowCount() > 0) {
        $db->query("DELETE FROM matchup WHERE uid = '" . $account['id'] . "' AND type ='private'");
    }
    $ucontrol->sessionDestroy();
    $tpl .= 'You have been logged out by the server...';
    require('./' . $version . '/inc/footer.php');
        die;
}*/
/*
if (isset($_GET['page'])) {
    $pg = $fnc->clean($_GET['page']);
}*/

// Search for activation! 
/*if ($account['activated'] != '1' && $pg != 'activate') {
    $fnc->redirect($siteaddress . "?page=activate");
}

// Notification for accounts activated! 
if ($account['activated'] == '1' && $account['password'] == md5('123') && $pg != 'activate') {
    if ($pg != 'game') {
        $fnc->message('Account Activated', 'Your account is active, but as a security measurement we would like you to change your password to anything that isnt the default ( <b>123</b> )', './?page=activate', 'Change Password');
    }
}$match = $db->query("SELECT * FROM `matches` WHERE `id-0` ='" . $account['id'] . "' AND `check` != '' AND `timeend` = ''");

if ($match->rowCount() > 0) {

    if ($fnc->group_permission($account['group'], 'ga') != '1') {
        $tpl .= 'No game access.';
        require('./' . $version . '/inc/footer.php');
        die;
    }else{
		$fnc->redirect('./?page=game&mode=battle');
	}
} */
/*
if ($pg == 'game' && $fnc->group_permission($account['group'], 'ga') == '1') {
    include('./' . $version . '/core/game.php');
} else if ($pg == 'activate') {

    include('./' . $version . '/core/activate.php');
} else if ($pg == 'logout') {

    include('./' . $version . '/core/logout.php');
} else if ($pg === 'room' || $account['room'] !== ''  && $account['skip'] == 0) {
	
    include('./' . $version . '/core/room.php');
} else {
    include('./' . $version . '/core/main.php');
}

require('./' . $version . '/inc/footer.php');*/
//$fnc->redirect('./?page=game&mode=battle');
?>
