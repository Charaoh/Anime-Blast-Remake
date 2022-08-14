<?php
if (!defined('SITE')) {
    require('index.php');
    exit;
}/*
if($account['group'] == 5){
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}*/
// Check if match is there
$check = $db->query("SELECT * FROM `matches` WHERE `check` = '1' AND `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') ORDER BY id DESC LIMIT 1");
if ($check->rowCount()  > 0) {
    require("./" . $version . "/inc/gameHandler.php");
    $match = $game->array_int($check->fetch());
	$status = $match['status'];
    $calculating = ($status == 'calculating') ? true : false;
    $player = $account['id'];
    $opponent = ($match['id-1'] == $account['id']) ? $match['id-0'] : $match['id-1'];
    $first = ($match['id-0'] === $account['id']) ? true : false;
	if($first == false && $match['status'] == 'winner' || $first == false && $match['status'] == 'loser')
		$match['status'] = ($match['status'] == 'winner') ? 'loser': 'winner';
    $turn = $match['time'];
    $turns = explode('/', $turn);
    $turn = end($turns);
    $turn = explode('=', $turn);
    $time = $turn[1];
    $turn = $turn[0];
    $me = false;
	if ($first == true && $turn % 2 != 0) $me = true;
	if ($first != true && $turn % 2 == 0) $me = true;
    $ttime = $system->data('Turn_Time');
	
    if ($status === 'playerTurn' && $me === true) {
        if (isset($_POST['end'])) {
            $status = 'calculating';
        }
    }
//	AI you calculate
	if($status === 'checking' && !$me && $match['type'] === 'ai'){
    	$status = 'calculating';
    }
    if ($status === 'checking' && $ttime <= (time() - $time)) {
        $status = 'calculating';
    }
	if ($status === 'playerTurn' && $ttime <= (time() - $time)) {
        $status = 'calculating';
    }

    if ($status === 'checking' && $me == true /*&& $ctime <= (time() - $time)*/) {
        array_pop($turns);
        $turns = implode('/', $turns);
        $newtime = $turns . '/' . $turn . '=' . time();
        $db->query("UPDATE matches SET time = '" . $newtime . "', status = 'playerTurn' WHERE id = '" . $match['id'] . "'");
		$system->redirect('./battle');       
	   /*if ($first === true) {
            $status = ($me === true) ? 'loser' : 'winner';
        } else {
            $status = ($me === true) ? 'winner' : 'loser';
        }
        $db->query("UPDATE matches SET status = '" . $status . "' WHERE id = '" . $match['id'] . "'");*/
    }

    /*if (($status == 'winner' || $status == 'loser') && empty($match['timeend']) && $rtime < (time() - $time)) {

        $db->query("UPDATE matches SET timeend = unix_timestamp() WHERE id = '" . $match['id'] . "'");
        $db->query("UPDATE matches SET reward = 'ND' WHERE id = '" . $match['id'] . "'");
    }*/

    $arguments = array(
        $status,
        $turn,
        $time,
        $first,
        $me,
        $match
    );

    if ($status === 'playerTurn' && $me === true) {

        new Ingame('Wait', $arguments);
    } else if ($status === 'calculating') {

        new Ingame ('Calculate', $arguments);
    }else {
        new Ingame ('Standby', $arguments);
    }
} else {

    $system->redirect('./ingame');
}
?>