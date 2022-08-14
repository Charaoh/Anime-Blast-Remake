<?php

if (!isset($account) || $account['activated'] == '1') {
	$system->redirect($siteaddress);
}

$tpl = $STYLE->open('activate.tpl');

$exclusives = explode(',', $system->data('exclusives'));
$tpl = preg_replace('/\<!-- BEGIN error_1 -->(.*?)\<!-- END error_1 -->/is', '', $tpl);
if (isset($_POST['Submit'])) {
	if (isset($_POST['bonus'])) {
		$bonus = $secure->clean($_POST['bonus']);
		$character = $db->fetch("SELECT * FROM characters WHERE id='" . $bonus . "'");
		if ($character && array_search($bonus, $exclusives) !== false) {
			if ($account['activated'] == '0') {
				$characters = $account['characters'] . ',' . $bonus;
				$db->query("UPDATE accounts SET activated = '1',	characters = '" . $characters . "' WHERE id = '" . $account['id'] . "'");
				$system->message('Successful reclaim!', 'You have been rewarded, check your ingame for an update.', './', 'Continue');
			} else {
				$tpl = $STYLE->open('activate.tpl');
				$tpl = preg_replace('/\<!-- BEGIN error_1 -->(.*?)\<!-- END error_1 -->/is', '', $tpl);
			}
		} else {
			$tpl = $STYLE->open('activate.tpl');
			$tpl = preg_replace('/\<!-- BEGIN error_2 -->(.*?)\<!-- END error_1 -->/is', '', $tpl);
		}
	} else {
		if (!isset($_POST['bonus'])) {
			$tpl = $STYLE->open('activate.tpl');
			$tpl = preg_replace('/\<!-- BEGIN error_2 -->(.*?)\<!-- END error_2 -->/is', '', $tpl);
		}
		if (!isset($_POST['bonus']) && !isset($_POST['code'])) {
			$tpl = $STYLE->open('activate.tpl');
		}
	}
}
$freeby = '';
foreach ($exclusives as $character) {
	$freeby .= '<p>' . $user->image($character, 'characters', './') . '<br/>' . $db->fieldFetch('characters', $character, 'name') . '<br/><input type="radio" name="bonus" value="' . $character . '"></p>';
}

$output .= $STYLE->tags($tpl, array("BONUS" => $freeby, "L_EMAIL" => L_EMAIL, "L_CODE" => L_CODE, "L_SUBMIT" => L_SUBMIT, "L_ACTIVATION" => L_ACTIVATION));
