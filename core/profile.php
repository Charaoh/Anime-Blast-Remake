<?php
if ( isset($_GET['user']))
{

    $user_id = $secure->clean($_GET['user']);
	/*if(strpos('%20', $user_id) !== false){
		$user_id = explode('%20', $user_id);
		$user_id = implode(' ', $user_id);
	}*/
	$result = $db->fetch("SELECT * FROM accounts WHERE name LIKE '". urldecode($user_id)."'");
	if(is_numeric($_GET['user']))
		$result = $db->fetch("SELECT * FROM accounts WHERE id = '$user_id'");
    if ( $result )
    {
		if(preg_match('/^[a-zA-Z_\/\s\d]+$/i',$result['name']) && is_numeric($_GET['user'])){ 
			$system->redirect($siteaddress.'profile/'.$result['name']);
		}
		$description .= '-> '.$result['name'];
		$metatags .= ', '.$result['name'];
		$user_id = $result['id'];
        $tpl = $STYLE->open('profile.tpl');
		
        // Generate Global Menu
        $global_menu = $STYLE->getcode('menu',$tpl);
        $tpl = str_replace ($global_menu,'',$tpl);
        $global_menu = $STYLE->tags($global_menu,array("L_SEND_MAIL" => L_SEND_MAIL, "L_SETTINGS" => L_SETTINGS, "L_SIGNATURE" => L_SIGNATURE, "L_AVATAR" => L_AVATAR));
        $page_title .= 'Profile > '.$result['name'];
		$last = time()-(5 * 24 * 60 * 60);
		$pb = $db->query("SELECT * FROM `matches` WHERE `timeend` != '0' AND (`id-0` ='" . $user_id . "' OR `id-1` ='" . $user_id . "') AND type = 'private' AND `timeend` > '".$last."' ORDER BY `timeend` DESC ");
		$matchs = '';
		$pbs = '';
		if($pb->rowCount() > 0){
			while($t = $pb->fetch()){
				$pbs .= $system->time($t['timeend']).' - '.$user->name($t['id-0']).' vs '.$user->name($t['id-1']).' Winner '.(($t['status'] == 'loser')? $user->name($t['id-1']) : $user->name($t['id-0'])).'<br/>';
			}
		}
		$quicks = $db->query("SELECT * FROM `matches` WHERE `timeend` != '0' AND (`id-0` ='" . $user_id . "' OR `id-1` ='" . $user_id . "') AND type = 'quick' AND `timeend` > '".$last."' ORDER BY `timeend` DESC ");
		$quicky = '';
		if($quicks->rowCount() > 0){
			while($m = $quicks->fetch()){
				$quicky .= $system->time($m['timeend']).' - '.$user->name($m['id-0']).' vs '.$user->name($m['id-1']).' Winner '.(($m['status'] == 'loser')? $user->name($m['id-1']) : $user->name($m['id-0'])).'<br/>';
			}
		}
		
		$ladder = $db->query("SELECT * FROM `matches` WHERE `timeend` != '0' AND (`id-0` ='" . $user_id . "' OR `id-1` ='" . $user_id . "') AND type = 'ladder' AND `timeend` > '".$last."' ORDER BY `timeend` DESC ");
		$ladders = '';
		if($ladder->rowCount() > 0){
			while($m = $ladder->fetch()){
				$ladders .= $system->time($m['timeend']).' - '.$user->name($m['id-0']).' vs '.$user->name($m['id-1']).' Winner '.(($m['status'] == 'loser')? $user->name($m['id-1']) : $user->name($m['id-0'])).'<br/>';
			}
		}

		if(!empty($ladders))
			$matchs .= '<div class="boxone header">Recent Ladder matches</div>'.$ladders;
		if(!empty($quicky))
			$matchs .= '<div class="boxone header">Recent Quick matches</div>'.$quicky;
		if(!empty($pbs))
			$matchs .= '<div class="boxone header">Recent Private matches</div>'.$pbs;
		
		if(empty($matchs)){
			$matchs = 'No match history';
		}
		if($result['experience'] == '0')
			$result['experience'] = 1;
		$level = $db->fetch("SELECT * FROM levels WHERE experience < '".$result['experience']."' ORDER BY experience DESC LIMIT 1");
		
			
		$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
		$rank = 'Not ranked in ladder';
		$key = 1;
		$max = $db->fetch("SELECT * FROM levels ORDER BY experience DESC LIMIT 1");
		while($me = $ranked->fetch()){
			if($me['id'] == $result['id']){
				$rank = '#'.$key;
				if($key == 1){
					if($max['id'] == $level['id']){
						$level['img'] = '1st';
						$rank = 'The Champion!';
					}
				}
			}
			$key++;
		}
		$wr = 0;
		if($result['wins'] != 0 && $result['loses'] != 0)
			$wr = round($result['wins']/($result['loses']+$result['wins'])*100);
		if($result['wins'] > 0 && $result['loses'] == 0)
			$wr = 100;
		if($wr > 100)
			$wr = 100;
		$width = 0;
		if($result['experience'] != 0){
			$next = $db->fetch("SELECT * FROM levels WHERE id = '".($level['id']+1)."'");
			if($next)
				$width = round(($result['experience']/$next['experience'])*100);
			else
				$width = 100;
		}
		$l = '<div class="levelBackground" style="display: inline-block;">
				<div class="levelFill" style="width: '.$width.'%;"></div>
				<div class="levelNumber">'.$user->level($result['experience']).'</div>
				</div>';
		$warnings = '';
		if($system->group_permission($user->group($account['id']), 'rc') == '1'){
			$warnings = 'IP Address: '.$result['ip'].'<br/> Warnings: '.(float)$result['warnings'];
			$warnings .= '<br/> Account frozen: '.(!empty($result['frozen']) ? 'This account is frozen.' : 'This account is active');
			$warnings .= (!empty($result['bantime']) ? '<br/> This account is banned until: '.$system->time($result['bantime']) : '');
			if($system->group_permission($user->group($result['id']), 'acp') == '1' && $system->group_permission($user->group($account['id']), 'acp') == '0')
				$warnings = '';
		}
		$season = $db->fetch("SELECT * FROM `season` WHERE end = '' LIMIT 1;");
		$seasons = '';
		if($season['id'] > 0){
			
			for($i = 1;$i < $season['id']; $i++){
				
				$old = $db->fetch("SELECT * FROM `season` WHERE id='".$i."'");
				$ranked = explode('/',$old['ranked']);
				foreach($ranked as $key => $r){
					if(empty($r)) continue;
					$stats = explode(',',$r);
					if(substr($stats[0],1) == $user_id){
						$y = $db->fetch("SELECT * FROM `levels` WHERE `experience` < '".substr($stats[4],1)."' ORDER BY experience DESC LIMIT 1;");
						if(!empty($seasons)){
							$seasons .= '<br style="clear: both;">';
						}
						$seasons .= '<p style="width: 25%;float: left;">'.$i.'</p>
						<p style="width: 50%;float: left;">'.$user->image(((substr($stats[1],1) == 1)?'1st':$y['img']),'ranks','./', 'profile-level').' Ladderrank #'.substr($stats[1],1).'</p>
						<p style="float: left;">'.substr($stats[5],1).'</p>';
					}
				}
			}
		}
		if(empty($seasons))
			$seasons = 'No statistics available..';
			$clan = $db->query("SELECT * FROM  `clan-members` WHERE `account_id` = '".$result['id']."'");
			if($clan->rowCount()>0){
				$clan = $clan->fetch();
				$clan = $db->fetch("SELECT * FROM  `clans` WHERE `id` = '".$clan['clan_id']."'");
				$clan='<a href="./clan/profile/'.urlencode($clan['name']).'">'.$clan['name'].'</a>';
				}else{
				$clan = 'Clanless';
				}
			
        $output .= $STYLE->tags($tpl,array(
            "AVATAR"=> $user->border($result, $user->image($user_id,'avatars')),
            "NAME" => $user->name($user_id),
            "STATUS" => $user->status($user_id),
            "RANK" => $user->rank($user_id),
			"GROUP" => $system->group($result['group']),
			"LEVEL" => $l,
			"GR" => $level['level'].' '.$user->image($level['img'],'ranks','./','" style="left:-5px;position:relative;margin:0 !important;vertical-align:bottom;width:35px;bottom: -5px;"'),
			"COINS" => $result['gold'].' <img src="'.$siteaddress.'tpl/'.$system->data('template').'/img/gold.png" style="margin:0 !important;vertical-align:bottom;width:35px;"/>',
			"MYRANK" => $rank,
			"WIN" => (float)$result['wins'],
			"LOSE" => (float)$result['loses'],
			"STREAK" => (((float)$result['streak'] >= 0) ? '+'.$result['streak'] : $result['streak']),
			"HSTREAK" => (float)$result['highest_streak'] ,
			"WR" => $wr.' %',
			"EXP" => $result['experience'].' exp',
			"CLAN" => $clan,
            "JOINED" => $system->time($result['joined']),
            "LASTLOGIN" => $system->time($result['lastlogin']),
            "GENDER" => $user->gender($user_id),
            "LOCATION" => $system->present($result['location']),
            "SIGNATURE" => $system->bbcode($result['signature']),
            "L_RANK" => L_RANK,
            "L_JOINED" => L_JOINED,
            "L_LAST_LOGIN" => L_LAST_LOGIN,
            "L_GENDER" => L_GENDER,
            "L_LOCATION" => L_LOCATION,
			"STALKED" => (empty($result['stalkme'])?L_HOME:$result['stalkme']),
            "L_DETAILS" => L_DETAILS,
            "L_SIGNATURE" => L_SIGNATURE,
			"L_SEND_MAIL" => L_SEND_MAIL,
			"L_SEARCH_TOPICS" => L_SEARCH_TOPICS,
			"L_SEARCH_POSTS" => L_SEARCH_POSTS,
			"MATCHHISTORY" => $matchs,
			"WARNINGS" => $warnings,
			"SEASONS" => $seasons
                ));
    } else {
        $system->message(L_ERROR,L_PROFILE_ERROR,'./',L_CONTINUE);
    }

} else {
    $system->message(L_ERROR,L_PROFILE_ERROR,'./',L_CONTINUE);
}
?>