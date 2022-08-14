<?php

if (!defined('SITE')) {
    require('index.php');
    exit;
}

class Ingame extends Functions {

// System game variables
    public $effects = array(
        "STATUS" => array('transform','set-skill','visibility','no-ignore','fear','no-resurrect','increase-mana','targetme', 'renew', 'destroy-dd', 'increase-duration', 'unpiercable', 'disable','show-mana','increase-heal','increase-affliction','dd','if','ignore','no-death','increase-cost','increase-cooldown', 'also', 'invul', 'stun', 'counter', 'reflect', 'dr','destroy-dr', 'increase','deal','increase-manaRem','reverseTargetToCaster','TargetDead'),
        "NORMAL" => array('switch','reset','convertM', 'convertH', 'following', 'damage', 'piercing', 'heal', 'affliction', 'drainM', 'drainH', 'removeM', 'replace','manaGain', 'remove', 'externalManaGain')
    );
    public $match = array();

    public function __construct($phase, $match) {

        global $system, $user, $secure, $game, $db, $STYLE, $siteaddress, $template, $page_title, $ttime, $sitename, $_POST, $version;
		
/*         if (empty($phase)) {

            return $fnc->message('Game Error #1', 'Error occured while processing phase... Refreshing in 5 seconds!', './', 'Refresh', true);
        }
 */
		
		// Set variables
        $this->match = array(
            "MATCH" => $match[5],
            "TURN" => $match[1],
            "TIME" => $match[2],
            "FIRST" => $match[3],
            "ME" => $match[4]
        );

        $this->first = array(
            "ACCOUNT" => $game->whois($match[5]['id-0']),
            "TEAM" => array(
                "ID" => $this->_get( 0 , 't'),
                "HEALTH" => $this->_get( 0 , 'h'),
                "MANA" => $this->_get( 0 , 'm'),
                "SKILL" => $this->_get( 0 , 't', 1),
				"COOLDOWN" => $this->_get( 0, 'c', 2),
				"STATUS" => array('0'=>array(),'1'=>array(),'2'=>array())
			)
        );

        $this->second = array(
            "ACCOUNT" => $game->whois($match[5]['id-1']),
            "TEAM" => array(                
				"ID" => $this->_get( 1 , 't'),
                "HEALTH" => $this->_get( 1 , 'h'),
                "MANA" => $this->_get( 1 , 'm'),
                "SKILL" => $this->_get( 1 , 't', 1),
				"COOLDOWN" => $this->_get( 1, 'c', 2),
				"STATUS" => array('0'=>array(),'1'=>array(),'2'=>array())
			)
		);
        switch ($phase) {

            case 'Wait':
                $this->Waiting(1);
                break;

            case 'Calculate':
                $this->Calculate();
                break;

            /* Ajax Cases */
            case 'verifySkill':
                return $this->verifySkill();
                break;
            case 'getTargets':
                return $this->getTargets();
                break;
            case 'checkTarget':
                return $this->checkTarget();
                break;
			
			case 'getUI':
				return $this->getUI();
				break;
			
            default:
                $this->Waiting();
                break;
        }
    }

    function Waiting($_ = 0) {
        global $account, $system, $user, $secure, $game, $db, $STYLE, $output, $ttime, $siteaddress, $template, $page_title, $template, $landscape, $version;
		if($this->match['FIRST'] == false){
			$opponent = $this->first;
			$this->first = $this->second;
			$this->second = $opponent;
		}
        $status = $this->prettyStatus($this->match['MATCH']['status']);
		// Global variables
        $slot = array('A', 'B', 'C');
		$form = '';
		$oslots ='';
		$slots ='';
		$__ = '';
		$max = $db->fetch("SELECT * FROM levels ORDER BY experience DESC LIMIT 1");
		// Step One: Setup Player 2 
        //if ($_ === 1)
        $form = '<form action="" method="post" name="turn-' . $this->match['TURN'] . '" target="_self">';
		$orank = $db->fetch("SELECT * FROM levels WHERE experience < '".($this->second['ACCOUNT']['experience']+1)."' ORDER BY experience DESC LIMIT 1");
		$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
		$or = 'Not ranked in ladder';
		$key = 1;
		while($me = $ranked->fetch()){
			if($me['id'] == $this->second['ACCOUNT']['id']){
				$or = 'Ranked #'.$key;
				if($key == 1){
					if($max['id'] == $orank['id']){
					$orank['img'] = '1st';
					$or = 'The Champion!';
					}
				}
			}
			$key++;
		}
		$characterlist = array();
		$mana = array();
		$healths = array();
        foreach ($this->second['TEAM']['ID'] as $key => $character) {
			$characterlist[] = $character;
            $oslots .= '<div class="eslot ' . $slot[$key] . '">';
            $avatar = '<p class="animate right"></p>
			'.$user->image($orank['img'], 'ranks', './','crank').'
			'.$user->image($character, 'characters', './', 'character fl-r flip').'
			<div class="fl-r tooltip c">
					<div>					
						<img class="point" src="./images/arrow_right.png">
						<h1>'.$db->fieldFetch('characters',$character,'name').'</h1>
						<p>'.$db->fieldFetch('characters',$character,'desc').'</p>
					</div>
			</div>';
			if($template == 'beta'){
				$image = (strpos($user->image($character, 'characters/slanted', './', 'character fl-r flip'),'default') !== false?'<p>?</p>':$user->image($character, 'characters/slanted', './', 'character fl-r flip'));
				$avatar = '
				<p class="animate right"></p>
              
				<div id="'.$character.'" class="character-frame r">
				{AVATAR}
                <div><img class="shat r" src="https://cdn.discordapp.com/attachments/713810205862789405/787488168156594196/santa_hat.png" style="z-index:999999;"></div>
					<div class="damaged">{AVATAR}</div>
					<img src="./tpl/beta/css/images/fade.png" class="gradient r">
					{BRIEF}
				</div>
				<span class="hover r"></span>
				<div class="fl-r tooltip c">
					<div>					
						<img class="point" src="./images/arrow_right.png">
						<h1>'.$db->fieldFetch('characters',$character,'name').'</h1>
						<p>'.$db->fieldFetch('characters',$character,'desc').'</p>
					</div>
				</div>'; 
			}
			if ($this->second['TEAM']['HEALTH'][$key] === '0'){
                $avatar = $user->image('dead', 'skills', './', 'fl-r');
				if($template == 'beta')
					$avatar = '<div class="character-frame r"><p>X</p>
					<img src="./tpl/beta/css/images/fade.png" class="gradient r">
				</div>';
			}
            $left = round(($this->second['TEAM']['HEALTH'][$key] / $db->fieldFetch('characters', $character, 'health')) * 100);
            if ($left > 100)
                $left = 100;
            $background = '';
            if ($left <= 75)
				if($template == 'beta')
					$background = ' background: linear-gradient(to right, yellow 60%, transparent 100%);';
				else
					$background = ' background: yellow;';
            if ($left <= 40)
                if($template == 'beta')
					$background = ' background: linear-gradient(to right, red 60%, transparent 100%);';
				else
					$background = ' background: red;';
			$healths['1'.$key]['total'] = $this->second['TEAM']['HEALTH'][$key];
			$healths['1'.$key]['width'] = $left;
			$healths['1'.$key]['background'] = $background;
			$health = '<p>' . $this->second['TEAM']['HEALTH'][$key] . '/' . $db->fieldFetch('characters', $character, 'health') . '</p>';
			
            if($template == 'beta'){
				$health = '';
				$left = 'height: '.$left.'%;';
			}else
				$left = 'width: '.$left.'%;';
			$oslots .= $avatar . '
                                    <div class="healthBar">
                                    <div class="healthBar Left" style="' . $left . $background . '">
                                    '.$health.'
                                    </div></div>';
			if(!empty($background))
				$background = ' style = "'.$background.'"';
			$briefing = '<div class="brief health r"'.$background.'><p>'.$this->second['TEAM']['HEALTH'][$key].'</p></div>';
            if ($this->second['TEAM']['HEALTH'][$key] == 0){
                $oslots .= '</div>'; 
				continue;
			}
			$oslots .= '<div class="le r">';
			// Get opponent active skills
			if(empty($this->match['MATCH']['active'])) goto skipped;
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			$add = '';
				$words = $active;
				$turnstore = '';
				foreach($words as &$value){
					$turn = substr($value, 0, stripos($value, "="));
					$value = substr($value, stripos($value, "=")+1);
					$turnstore[$value] = $turn;
				}
				$words = array_count_values($words);
				foreach ($words as $value => $count) {
					$value = $turnstore[$value].'='.$value;
					$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
					// Check to see if im first or second
					if($what['target'][0] != 'second') continue;
					if($what['target'][1] != $key) continue;
					foreach(reset($what['skill']) as $effect => $turnsleft){
						$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), array_search($value,$active)), $what['turn']);
					}					
				}
				foreach ($words as $value => $count) {
					$value = $turnstore[$value].'='.$value;
					$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
					// Check to see if im first or second
					if($what['target'][0] != 'second') continue;
					if($what['target'][1] != $key) continue;
					$store = $this->doEffect('Description', $what);
					$add .= $store;
					if($count > 1 && !empty($store))
						$add .= '<span class="stack r">'.$count.'</span>';
				}
			
			if(array_search('show_mana', $this->second["TEAM"]["STATUS"][$key], true) !== false){
				$left = round(($this->second['TEAM']['MANA'][$key] / $db->fieldFetch('characters', $character, 'mana')) * 100);
				if ($left > 100)
					$left = 100;
				if($template == 'beta'){
					$mana['1'.$key]['total'] = $this->second['TEAM']['MANA'][$key];
					$mana['1'.$key]['width'] = $left;
					$briefing .= '<div class="brief mana"><p>'.$this->second['TEAM']['MANA'][$key].'</p></div>';
				}else
					$oslots .= '<div class="healthBar">
                                    <div class="manaBar Left" style="width: ' . $left . '%;">
                                    <p>' . $this->second['TEAM']['MANA'][$key] . '/' . $db->fieldFetch('characters', $character, 'mana') . '</p>
                                    </div></div>';
			}
			$oslots .= $add;
			
            skipped:
			if($template == 'beta'){
				if(isset($this->second["TEAM"]["STATUS"][$key]['transform'])){
					$image = $image = (strpos($user->image($this->second["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './', 'character fl-r flip'),'default') !== false?'<p>?</p>':$user->image($this->second["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './', 'character fl-r flip'));
				}
				$oslots = $STYLE->tags($oslots, array(
						"BRIEF" => $briefing,
						"AVATAR" => $image
					));
			}
			$oslots .= '<div class="fl-l new"></div></div>';
            $oslots .= '</div>';
            //if ($_ === 1)
            $form .= '<input id="1' . $key . '-targeted" type="hidden" name="1' . $key . '-targeted" value="X">';
        }
		
        // Step Two: Setup Player 1
		// Checks for stun and invul
		if(empty($this->match['MATCH']['active'])) goto noactive;
		if(strpos($this->match['MATCH']['active'], "/") == true)
			$active = explode('/', $this->match['MATCH']['active']);
		else
			$active = array($this->match['MATCH']['active']);
		foreach ($active as $k => $value) {
			$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $k), $what['turn']);
			}
        } 
		noactive:
		// Step Three: Setup Player 1
		$targets = array("00"=>array(),"01"=>array(),"02"=>array());
		$rank = $db->fetch("SELECT * FROM levels WHERE experience < '".($this->first['ACCOUNT']['experience']+1)."' ORDER BY experience DESC LIMIT 1");
        		$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
		$ra = 'Not ranked in ladder';
		$key = 1;
		while($me = $ranked->fetch()){
			if($me['id'] == $this->first['ACCOUNT']['id']){
				$ra = 'Ranked #'.$key;
				if($key == 1){
					if($max['id'] == $rank['id']){
					$rank['img'] = '1st';
					$ra = 'The Champion!';
					}
				}
			}
			$key++;
		}

		foreach ($this->first['TEAM']['ID'] as $key => $character) {
			$characterlist[] = $character;
            $slots .= '<div class="slot ' . $slot[$key] . '">';
            $avatar = '<p class="animate"></p>
			'.$user->image($rank['img'], 'ranks', './','crank l').'
			' .$user->image($character, 'characters', './', 'character fl-l').'
			<div class="fl-l tooltip c">
				<div>
				<img class="point" src="./images/arrow_left.png" />
					<h1>'.$db->fieldFetch('characters',$character,'name').'</h1>
					<p>'.$db->fieldFetch('characters',$character,'desc').'</p>
				</div>
			</div>
			<div class="status">';
			// Find character status effects
			$seffects = array('stunned' => 's','disable' => 'd','feared' => 'f','invulnerability' => 'i');
			foreach($seffects as $se => $short){
				if(isset($this->first['TEAM']['STATUS'][$key][$se])){
					$disable = $this->first['TEAM']['STATUS'][$key][$se];
					$disable = explode(',',$disable);
					$effecting = array('s'=>'Stunned','d'=>'Disabled','f'=>'Scared','i'=>'Invulnerable');
					$descriptions = array('s'=>' will not be able to use certain skills;','d'=>' will not be able to use certain skill abilities;','f'=>' will miss with certain skills;','i'=>' is invulnerable to certain skills;');
					if(count($disable) > 0){
					if(count($disable) > 1){
						$disabled = explode(',',$disable);
						$counter = count($disabled);
						foreach($disabled as $i => $me){
							if($me == 'all'){
								$disabled = 'all';
								break;
							}
							if($i == 0)
								$disabled = $db->fieldFetch('classes',$me,'name');
							else
								$disabled .= (($i+1==$counter)?' and ':', ').$db->fieldFetch('classes',$me,'name');
						}
						$avatar .= '
						<a class="tooltip r" href="#">
						<img src="'.$siteaddress.'images/status/'.$short.'-a.png" class="fl-l seffect">
							<div>
								<h1>'.$effecting[$short].'</h1><p>- '.$db->fieldFetch('characters',$character,'name').$descriptions[$short].' '.$disabled.' skill classes.</p>
							</div>
						</a>';
					}else{
						$avatar .= '
						<a class="tooltip r" href="#">
						<img src="'.$siteaddress.'images/status/'.$short.'-'.(($disable[0]=='all')?'a':$disable[0]).'.png" class="fl-l seffect">
							<div>
								<h1>'.$effecting[$short].'</h1><p>- '.$db->fieldFetch('characters',$character,'name').$descriptions[$short].' '.(($disable[0]!=='all')?$db->fieldFetch('classes',$disable[0],'name'):$disable[0]).' skill classes.</p>
							</div>
						</a>';
					}
					}
				}
			}
			$avatar .= '</div>';
			if($template == 'beta'){
				$image = (strpos($user->image($character, 'characters/slanted', './', 'character fl-l'),'default') !== false?'<p>?</p>'
					:$user->image($character, 'characters/slanted', './', 'character fl-l'));
				$avatar = '
				<p class="animate"></p>
                
				<div id="'.$character.'" class="character-frame">
				{AVATAR}
                <div><img class="shata" src="https://cdn.discordapp.com/attachments/713810205862789405/787488168156594196/santa_hat.png"></div>
				<div class="damaged">{AVATAR}</div>
					<img src="./tpl/beta/css/images/fade.png" class="gradient flip">
					{BRIEFING}
				</div>
				<span class="hover"></span>
				<div class="fl-l tooltip c">
				<div>
				<img class="point" src="./images/arrow_left.png" />
					<h1>'.$db->fieldFetch('characters',$character,'name').'</h1>
					<p>'.$db->fieldFetch('characters',$character,'desc').'</p>
				</div>
				</div>';
			}
			//if ($_ === 1)
            $form .= '<input id="0' . $key . '-targeted" type="hidden" name="0' . $key . '-targeted" value="X"><input id="0' . $key . '-skill" type="hidden" name="0' . $key . '-skill" value="X">';
			if ($this->first['TEAM']['HEALTH'][$key] == '0') {
				$slots .= '<p class="animate"></p>';
				if($template == 'beta')
					$slots .= '<div class="character-frame"><p>X</p>
								<img src="./tpl/beta/css/images/fade.png" class="gradient flip">
								</div> <div class="healthBar">
                                    <div class="healthBar Left" style="height:0%">
                                    </div></div>';
				else
					$slots .= $user->image('dead', 'skills', './', 'fl-l character');
				$slots .= '<div class="le"><div class="new"></div></div>';
				if($template == 'beta')
					$slots .= '<div class="skills wait">';
				else
					$slots .= '<div class="skills-wait">';
				$slots .= $user->image('dead', 'skills', './', 'fl-l skill opacity');
				$slots .=  $user->image('dead', 'skills', './', 'fl-l skill opacity');
				$slots .=  $user->image('dead', 'skills', './', 'fl-l skill opacity');
				$slots .=  $user->image('dead', 'skills', './', 'fl-l skill opacity');
				$slots .= '</div></div>';
				continue;
            }
			
            $left = round(($this->first['TEAM']['HEALTH'][$key] / $db->fieldFetch('characters', $character, 'health')) * 100);
            if ($left > 100)
                $left = 100;
			
            $background = '';
            if ($left <= 75)
				if($template == 'beta')
					$background = ' background: linear-gradient(to left, yellow 60%, transparent 100%);';
				else
					$background = ' background: yellow;';
            if ($left <= 40)
                if($template == 'beta')
					$background = ' background: linear-gradient(to left, red 60%, transparent 100%);';
				else
					$background = ' background: red;';
			$healths['0'.$key]['total'] = $this->first['TEAM']['HEALTH'][$key];
			$healths['0'.$key]['width'] = $left;
			$healths['0'.$key]['background'] = $background;
			$health = '<p>' . $this->first['TEAM']['HEALTH'][$key] . '/' . $db->fieldFetch('characters', $character, 'health') . '</p>';
			if($template == 'beta'){
				$health = '';
				$left = 'height: '.$left.'%;';
			}else
				$left = 'width: '.$left.'%;';
            $slots .= $avatar . '
                                    <div class="healthBar">
                                    <div class="healthBar Left" style="' . $left . $background . '">
                                    <p>' . $health . '</p>
                                    </div></div>';
			if(!empty($background))
				$background = ' style="'.$background.'"';
            $left = round(($this->first['TEAM']['MANA'][$key] / $db->fieldFetch('characters', $character, 'mana')) * 100);
            if ($left > 100)
                $left = 100;
			$mana['0'.$key]['total'] = $this->first['TEAM']['MANA'][$key];
			$mana['0'.$key]['width'] = $left;
            $slots .= '<div class="le">';
			// Get opponent active skills
			if(empty($this->match['MATCH']['active'])) goto letgo;
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			$add = '';
			$words = $active;
			$turnstore = '';
			foreach($words as &$value){
				$turn = substr($value, 0, stripos($value, "="));
				$value = substr($value, stripos($value, "=")+1);
				$turnstore[$value] = $turn;
			}
			$words = array_count_values($words);
			foreach ($words as $value => $count) {
				$value = $turnstore[$value].'='.$value;
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				//Check to see if im first or second
				if($what['target'][0] != 'first') continue;
				if($what['target'][1] != $key) continue;
				$store = $this->doEffect('Description', $what);
				$add .= $store;
				if($count > 1 && !empty($store))
					$add .= '<span class="stack">'.$count.'</span>';
			}
			$slots .= $add;
            letgo:
			$slots .= '<div class="new"></div></div>';
			if($template == 'beta'){
				$briefing = '
					<div class="brief health"'.$background.'><p>'.$this->first['TEAM']['HEALTH'][$key].'</p></div>
					<div class="brief mana"><p>'.$this->first['TEAM']['MANA'][$key].'</p></div>';
				if(isset($this->first["TEAM"]["STATUS"][$key]['transform'])){
					$image = $image = (strpos($user->image($this->first["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './', 'character fl-l'),'default') !== false?
					'<p>?</p>':$user->image($this->first["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './', 'character fl-l'));
				}
				$slots .= '<div id="'.$left.'" class="manaBar">
                                <p class="error '.$character.'">For the first turn there is a mana cap of '.$system->data('First_Mana').' per character. <br/> For turn 2 there is a mana cap of '.$system->data('Second_Mana').' per character. </p>
                                <div class="manaBar Left" style="width: ' . $left . '%"><p>'.$this->first['TEAM']['MANA'][$key].'</p></div>
							</div>';
				$slots = $STYLE->tags($slots, array(
						"BRIEFING" => $briefing,
						"AVATAR" => $image
					));
			}
			$slots .= '<div class="skills">
                            <img class="selected fl-l'.(($_ == 0)?' opacity':'').'" src="' . $siteaddress .'tpl/default/css/images/select.png"/>';
            $skillkey = 1;
			foreach ($this->first['TEAM']['SKILL'][$key] as $skill => $cost) {
				
                $opacity = '';
                $cooldown = '';
				$_skill = $db->fetch('SELECT * FROM skills WHERE id = "' . $skill . '"');
                if ($_ == 0) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// Cooldown
                
				if ($this->first['TEAM']['COOLDOWN'][$key][$skill] !== '0') {
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] == 'None')
						$this->first['TEAM']['COOLDOWN'][$key][$skill] = 0;
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] == 'Infinite'){
						$cooldown = '<span class="cooldown">∞</span>';
						$opacity = 'opacity';
						goto skill;
					}
					if(empty($this->first['TEAM']['COOLDOWN'][$key][$skill]))
						$this->first['TEAM']['COOLDOWN'][$key][$skill] = 0;
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] != 0){
						$cooldown = '<span class="cooldown">' . $this->first['TEAM']['COOLDOWN'][$key][$skill]. '</span>';
						$opacity = 'opacity';
						goto skill;
					}
				}
				
				$extra = '0';
				if(isset($this->first["TEAM"]["STATUS"][$key]['increase-cost']))
					$extra = $this->first["TEAM"]["STATUS"][$key]['increase-cost'];
                if ($this->first['TEAM']['MANA'][$key] < ($cost+$extra)) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// If stunned
				if ($this->checkStun($this->first['TEAM']['STATUS'][$key]['stunned'], $_skill['classes']) === true) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// Disable skills
				if (isset($this->first['TEAM']['STATUS'][$key]['disable'])) {
					$disable = $this->first['TEAM']['STATUS'][$key]['disable'];
					$disable = explode(',',$disable);
					$disabled = false;
					foreach($disable as $what){
						if(empty($what)) continue;
						if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $what)){
							$what = substr($what, strpos($what,'S')+1);
							if($what != $skillkey) continue;
							$disabled = true;
						}elseif(strpos($what, 'Offensive') !== false){
							if(strpos($_skill['targets'], 'E') !== false)
								$disabled = true;
						}elseif(strpos($what, 'Defensive') !== false){
							if(strpos($_skill['targets'], 'A') !== false)
								$disabled = true;
							if(strpos($_skill['targets'], 'S') !== false)
								$disabled = true;
						}elseif(strpos($what,'c')!== false){
							$classes = explode(',', $_skill['classes']);
							if(array_search(substr($what,1), $classes) !== false)
								$disabled = true;
						}else{
							
							$effects = explode(',',$_skill['effects']);
							foreach($effects as $e){
								$e = $db->fetch("SELECT * FROM effects WHERE id = '".$e."'");
								if(!empty($e[$what])){
									$disabled = true;
								}
							}
						}
					}
					if($disabled === true){
					$opacity = 'opacity';
					goto skill;
					}
                } 
                // Require these skills first...
				if ($_skill['requires'] !== '0') {
                    $requires = explode('|', $_skill['requires']);
                    $done = 0;
					if(empty($this->match['MATCH']['active'])) goto noskill;
					if(strpos($this->match['MATCH']['active'], "/") == true)
						$active = explode('/', $this->match['MATCH']['active']);
					else
						$active = array($this->match['MATCH']['active']);
					foreach ($active as $value) {
						$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
						
						if($this->{$what['caster'][0]}['ACCOUNT']['id'] !== $account['id']) continue;
						
						if($this->{$what['caster'][0]}['TEAM']['ID'][$what['caster'][1]] !== $this->first['TEAM']['ID'][$key]) continue;
						
						if(($llave = array_search(key($what['skill']),$requires)) !== false){
							unset($requires[$llave]);
						}
					}
					noskill:
					if (count($requires) !== 0){
                        $opacity = 'opacity';
						goto skill;
					}
                }
				if($template == 'beta'){
				if($this->match['TURN'] == '1' || $this->match['TURN'] == '2'){
					$manaCap = ($this->match['TURN'] == '1') ? $system->data('First_Mana') : $system->data('Second_Mana');
					if($manaCap < ($cost+$extra)){
						$opacity = 'opacity';
						goto skill;
					}
				}
				}
				$targets['0'.$key][$_skill['id']]['targets'] = $this->availableTargets($key,$_skill['id']);
				$targets['0'.$key][$_skill['id']]['available'] = $_skill['targets'];
				$targets['0'.$key][$_skill['id']]['costs'] = (($cost+$extra)<0?0:($cost+$extra));
				skill:
				
				$classes = $_skill['classes'];
				if(!empty($classes)){
					$classes = explode(',',$_skill['classes']);
					$classes['archive'] = '<br/>';
					foreach($classes as $class){
						if($db->fieldFetch('classes',$class,'name') == 'undefined') continue;
						$classes['archive'] .= $user->image($db->fieldFetch('classes',$class,'name'), 'classes', './', 'skill-class" title="Skill Class '.$db->fieldFetch('classes',$class,'name').'"');
					}
				}
                $slots .= 
			$user->image($skill, 'skills', './', "skill fl-l $opacity") .'
			<div class="fl-l tooltip s">
					<div><img class="point" src="./images/arrow_left.png" />
                        
					<h1>'.$_skill['name'].'</h1>
						<p>'.$_skill['desc'].'</p><p style="color:#32c9e9;">Mana Cost: '.(($cost+((isset($extra))?$extra:'0')< 0)?'0':$cost+((isset($extra))?$extra:'0')).'<br/> <span  style="color: #e63d3d;">Cooldown: '.$_skill['cooldown'].'</span>'.$classes['archive'].'</p>
					</div></div>'. $cooldown;
					$skillkey++;
            } /* <div class="manaBar Preview" style="width: ' . $left . '%">
                                    </div>*/
			if($template !== 'beta'){
				$slots .=
                        '<div id="'.$left.'" class="manaBar">
                                 <p class="error '.$character.'">For the first turn there is a mana cap of '.$system->data('First_Mana').' per character. <br/> For turn 2 there is a mana cap of '.$system->data('Second_Mana').' per character. </p>
                                    <div class="manaBar Left" style="width: ' . $left . '%"><p>'.$this->first['TEAM']['MANA'][$key].'</p></div></div>';
				
            }
			$slots .= '</div></div>';
        }
        //if ($_ === 1)
        $form .= $__ . '<input id="order" type="hidden" name="order" value="X"><input id="current" type="hidden" name="current" value="X"><input id="end" type="hidden" name="end" value="false"></form>';
		// *Final Step* Step Five: Process the page for Player 
		if($this->first['ACCOUNT']['experience'] == 0)
			$this->first['ACCOUNT']['experience'] = 1;
		$level = $db->fetch("SELECT * FROM levels WHERE experience < '".$this->first['ACCOUNT']['experience']."' ORDER BY experience DESC LIMIT 1");
        if($this->second['ACCOUNT']['experience'] == 0)
			$this->second['ACCOUNT']['experience'] = 1;
		$ulevel = $db->fetch("SELECT * FROM levels WHERE experience < '".$this->second['ACCOUNT']['experience']."' ORDER BY experience DESC LIMIT 1");
		$clan = $db->query("SELECT * FROM `clan-members` WHERE account_id = '" . $this->first['ACCOUNT']['id'] . "'");
		if($clan->rowCount() >0){
			$clan = $clan->fetch();
			$clan = $db->fieldFetch('clans', $clan['clan_id'], 'name');
		}else{
			$clan = 'Clanless';
		}
		$eclan = $db->query("SELECT * FROM `clan-members` WHERE account_id = '" . $this->second['ACCOUNT']['id'] . "'");
		if($eclan->rowCount() >0){
			$eclan = $eclan->fetch();
			$eclan = $db->fieldFetch('clans', $eclan['clan_id'], 'name');
		}else{
			$eclan = 'Clanless';
		}
		$player = $user->image($this->first['ACCOUNT']['id'], 'avatars', './', 'fl-r avatar').'
			<div class="fl-r tooltip u">
			 <div>
			 <img class="point" src="./images/arrow_up.png" />
				<h1>'.$this->first['ACCOUNT']['name'].'</h1><br style="clear:both;"/>
				<p>'.$level['level'].' / '.$ra.'<br/>
				Ratio: '.$this->first['ACCOUNT']['wins'].' - '.$this->first['ACCOUNT']['loses'].' ( '.(((float)$this->first['ACCOUNT']['streak'] >= 0) ? '+'.(float)$this->first['ACCOUNT']['streak'] : (float)$this->first['ACCOUNT']['streak']).' )
				<br/>Clan: '.$clan.'
				</p>
				<p>
				'.$user->image($this->first['TEAM']['ID'][0], 'characters', './', 'fl-r character').'
				'.$user->image($this->first['TEAM']['ID'][1], 'characters', './', 'fl-r character').'
				'.$user->image($this->first['TEAM']['ID'][2], 'characters', './', 'fl-r character').'
                </p>
			</div></div>'.$user->image($rank['img'], 'ranks', './','rank fl-r').'
			<p>'.$this->first['ACCOUNT']['name'].'</p>
			<p>'.$db->fieldFetch('usergroups', $this->first['ACCOUNT']['group'],'title').'</p>';
		$opponent = $user->image($this->second['ACCOUNT']['id'], 'avatars', './', 'fl-l avatar').'
			 <div class="fl-l tooltip u"><div>
			 <img class="point" src="./images/arrow_up.png" />
				<h1>'.$this->second['ACCOUNT']['name'].'</h1><br style="clear:both;"/>
				<p>'.$ulevel['level'].' / '.$or.'<br/>
				Ratio: '.$this->second['ACCOUNT']['wins'].' - '.$this->second['ACCOUNT']['loses'].' ( '.(((float)$this->second['ACCOUNT']['streak'] >= 0) ? '+'.(float)$this->second['ACCOUNT']['streak'] : (float)$this->second['ACCOUNT']['streak']).' )
				<br/>Clan: '.$eclan.'
				</p>
				<p>
				'.$user->image($this->second['TEAM']['ID'][0], 'characters', './', 'fl-r character').'
				'.$user->image($this->second['TEAM']['ID'][1], 'characters', './', 'fl-r character').'
				'.$user->image($this->second['TEAM']['ID'][2], 'characters', './', 'fl-r character').'
                </p>
			</div></div>'.$user->image($orank['img'], 'ranks', './','rank fl-l').'
			<p>'.$this->second['ACCOUNT']['name'].'</p>
			<p>'.$db->fieldFetch('usergroups', $this->second['ACCOUNT']['group'],'title').'</p>';
		if($template == 'beta'){
			$rankimg = $user->image($rank['img'].'-3', 'ranks/new', './','rankbottom');
			if($rank['img'] > 3 || $rank['img'] == '1st'){
				$rankimg .= $user->image($rank['img'].'-1', 'ranks/new', './','rankwing');
				if($rank['img'] == '1st'){
					$rankimg = $user->image($rank['img'].'-3', 'ranks/new', './','rankbottom"');
					$rankimg .= $user->image($rank['img'].'-1', 'ranks/new', './','rankwing');
					$rankimg .= $user->image($rank['img'].'-1', 'ranks/new', './','rankwing2');
				}
			}
			$player = '<div class="playerData">
				'.$user->image($this->first['ACCOUNT']['id'], 'avatars', './', 'fl-r avatar').'
				'.$rankimg.'
				</div>
			<div class="fl-r tooltip u">
			 <div>
			 <img class="point" src="./images/arrow_down.png" />
				<h1>'.$this->first['ACCOUNT']['name'].'</h1><br style="clear:both;"/>
				<p>'.$level['level'].' / '.$ra.'<br/>
				Ratio: '.$this->first['ACCOUNT']['wins'].' - '.$this->first['ACCOUNT']['loses'].' ( '.(((float)$this->first['ACCOUNT']['streak'] >= 0) ? '+'.$this->first['ACCOUNT']['streak'] : $this->first['ACCOUNT']['streak']).' )
				<br/>Clan: '.$clan.'
				</p>
				<p>
				'.$user->image($this->first['TEAM']['ID'][0], 'characters', './', 'fl-r character').'
				'.$user->image($this->first['TEAM']['ID'][1], 'characters', './', 'fl-r character').'
				'.$user->image($this->first['TEAM']['ID'][2], 'characters', './', 'fl-r character').'
                </p>
			</div></div>
			<p>'.$this->first['ACCOUNT']['name'].'</p>
			<p>'.$db->fieldFetch('usergroups', $this->first['ACCOUNT']['group'],'title').'</p>';
			$orankimg = $user->image($orank['img'].'-3', 'ranks/new', './','rankbottom');
			if($orank['img'] > 3 || $orank['img'] == '1st'){
				$orankimg .= $user->image($orank['img'].'-1', 'ranks/new', './','rankwing');
				if($orank['img'] == '1st'){
					$orankimg = $user->image($orank['img'].'-3', 'ranks/new', './','rankbottom"');
					$orankimg .= $user->image($orank['img'].'-1', 'ranks/new', './','rankwing');
					$orankimg .= $user->image($orank['img'].'-1', 'ranks/new', './','rankwing2');
				}
			}
			$opponent = '<div class="playerData">
				'.$user->image($this->second['ACCOUNT']['id'], 'avatars', './', 'fl-l avatar').$orankimg.'
				
				</div>
			<div class="fl-l tooltip u"><div>
			 <img class="point" src="./images/arrow_down.png" />
				<h1>'.$this->second['ACCOUNT']['name'].'</h1><br style="clear:both;"/>
				<p>'.$ulevel['level'].' / '.$or.'<br/>
				Ratio: '.$this->second['ACCOUNT']['wins'].' - '.$this->second['ACCOUNT']['loses'].' ( '.(((float)$this->second['ACCOUNT']['streak'] >= 0) ? '+'.$this->second['ACCOUNT']['streak'] : $this->second['ACCOUNT']['streak']).' )
				<br/>Clan: '.$eclan.'
				</p>
				<p>
				'.$user->image($this->second['TEAM']['ID'][0], 'characters', './', 'fl-r character').'
				'.$user->image($this->second['TEAM']['ID'][1], 'characters', './', 'fl-r character').'
				'.$user->image($this->second['TEAM']['ID'][2], 'characters', './', 'fl-r character').'
                </p>
			</div></div>
			<p>'.$this->second['ACCOUNT']['name'].'</p>
			<p>'.$db->fieldFetch('usergroups', $this->second['ACCOUNT']['group'],'title').'</p>';
		}
		$mybg = '';
		if(!empty($account['bg'])){
			if(strpos($account['bg'], ')') !== false && strlen($account['bg']) > 2)
				$mybg = substr($account['bg'],strpos($account['bg'],')')+1);
			if(strlen($mybg) > 0)
				$mybg = ' style="background: url('.$mybg.') 0 0 no-repeat; background-size:cover;"';
		}
        $s = $STYLE->open('ingame.tpl');
		$mmenu = '';
		if($system->isMobile() && $template == 'beta'){
			$landscape = true;
			$mmenu = $STYLE->open('mobile.tpl');
			$STYLE->__add('files', 'CSS', '', '/css/mobile.css');
		}
        $s = $STYLE->tags($s, array(
			"MOBILE" => $mmenu,
			"TYPE"=> $this->match['MATCH']['type'],
			"USER" => $account['name'],
			"RANK" => $rank['level'],
			"AVATAR"=>$user->image($this->first['ACCOUNT']['id'], 'avatars', './', 'fl-l avatar').$rankimg,
			"EUSER" => $this->second['ACCOUNT']['name'],
			"EAVATAR" => $user->image($this->second['ACCOUNT']['id'], 'avatars', './', 'fl-l avatar').$orankimg,
			"ERANK" => $orank['level'],
			"BG" => $mybg,
            "STATUS" => $status,
            "PLAYER" => $player,
			"OPPONENT" => $opponent,
            "OSLOTS" => $oslots,
            "SLOTS" => $slots,
            "FORMAT" => $form,
			"TURN" => $this->match['TURN']
        ));
        $output .= $s;
        $output = preg_replace(
                ($_ == 0) ?
                        '/\<!-- BEGIN Render 1 -->(.*?)\<!-- END Render 1 -->/is' :
                        '/\<!-- BEGIN Render 2 -->(.*?)\<!-- END Render 2 -->/is'
                , '', $output);
        $maxtime = ($ttime * 1000);
        $output .= '<script> let gVersion = "'.$version.'", mvol="'.(isset($account['mvol'])&&$account['mvol'] !== ""?$account['mvol']:$system->data('default-volume-music')).'", vsfx="'.(isset($account['vsfx'])&&$account['vsfx'] !== ""?$account['vsfx']:$system->data('default-volume-sfx')).'", mana = '.json_encode($mana).', health = '.json_encode($healths).', available = '.json_encode($targets).', characters = "'.implode(',',$characterlist).'", match_status = "' . $this->match['MATCH']['status'] . '", me = "' . $this->match['ME'] . '", turn = ' . $this->match['TURN'] . ', startTime = ' . (($ttime - (time() - $this->match['TIME'])) * 1000) . ', maxTime=' . $maxtime . ';</script>';
		if($template !== 'beta')
			$output .= $this->getMusic();
		$STYLE->__add('files', 'JAVA', '', '/java/jquery.js');
		$STYLE->__add('files', 'JAVA', '', '/java/jquery-ui.js');
		$STYLE->__add('files', 'JAVA', '', '/java/jquery.ui.touch-punch.min.js');
		$STYLE->__add('files', 'JAVA', '', '/java/sweetalert.js');
    	$STYLE->__add('files', 'JAVA', '', '/java/player.js');
		$STYLE->__add('files', 'JAVA', '', '/java/howler.js');
		$STYLE->__add('files', 'JAVA', '', '/java/spin.min.js');
        $STYLE->__add('files', 'JAVA', '', '/java/3.js');
		$STYLE->__add('files', 'CSS', '', '/css/ingame.css');
		
		$page_title = 'Ingame vs ' . $this->second['ACCOUNT']['name'];
		
    }
	

    function Calculate() {
        global $system, $db, $account, $STYLE, $ttime;

        $db->query("UPDATE matches SET status = 'calculating' WHERE id = '" . $this->match['MATCH']['id'] . "'");

		//---------------------------------
		// FIRST PROCESS STATUS EFFECTS
		if(!empty($this->match['MATCH']['active'])){
		if(strpos($this->match['MATCH']['active'], "/") == true)
			$active = explode('/', $this->match['MATCH']['active']);
		else
			$active = array($this->match['MATCH']['active']);
		$last = 0;
		foreach ($active as $key => $value) {
			$what = $this->makemeunderstand($value);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $key), $what['turn']);
			}
			$last=$key;
		}
		}	
		$record = array();
		$who = $account['id'];
		if($this->match['ME'] === false)
			$who = ($account['id'] == $this->match['MATCH']['id-0']) ? $this->match['MATCH']['id-1'] : $this->match['MATCH']['id-0'];
		// Manage cooldowns
		$turnof = ($who == $this->match['MATCH']['id-0']) ? 'first' : 'second';
		foreach($this->{$turnof}['TEAM']['ID'] as $key => $character){
			if(isset($this->{$turnof}["TEAM"]["STATUS"][$key]['if'])){
				$all = explode(',',$this->{$turnof}["TEAM"]["STATUS"][$key]['if'][0]);
				$amounts = explode('/',$this->{$turnof}["TEAM"]["STATUS"][$key]['if'][2]);
				foreach($all as $k => $hehe){
					if(strpos($hehe,'n') !== false && strpos($hehe,'ally') == false && !is_numeric($_POST['0' . $key . '-skill'])){
						//$hehe = substr($hehe, 0, strpos($hehe,'n'));
						$s = $db->fetch("SELECT * FROM skills WHERE id = '".substr($hehe, 0, strpos($hehe,'n'))."'");
						$add = explode(',',$s['effects']);
						$aid = '';
						foreach($add as $a){
							$i = $db->fetch("SELECT * FROM effects WHERE id = '".$a."'");
							if(!empty($aid))
								$aid .= ',';
							$custom = '';
							if(!empty($i['dd']))
								$custom = '*'.$i['dd'];
							$aid .= $a.';'.$i['duration'].$custom;
						}
						$c = substr($hehe, strpos($hehe,'n')+1);
						$t = (($this->match['FIRST'] == false)?'1':'0').$key;
						if(strpos($hehe,'s') !== false){
							$c= substr($hehe, strpos($hehe,'n')+1, strpos($hehe,'s')-1);
							$t = substr($hehe, strpos($hehe,'n')+1, strpos($hehe,'s')-1);
						}
						if(!empty($aid)){
							
								$active[] = $this->match['TURN'].'='.$c.':' . $s['id'] . '[' . $aid . ']'.$t;
							
						}
							//unset($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if']);
					}elseif(strpos($hehe,'n') == false && strpos($hehe,'ally') == false && is_numeric($_POST['0' . $key . '-skill'])){
						$specific = explode('/', $this->{$turnof}["TEAM"]["STATUS"][$key]['if'][1]);
						$specific = explode(',', $specific[$k]);
						$found = false;
						$classes = explode(',', $db->fieldFetch('skills', $_POST['0' . $key . '-skill'], 'classes'));
						foreach($specific as $s){
							if($s == 'all')
								$found = true;
							if(array_search($s, $classes) !== false)
								$found = true;
							if($found === true)
								break;
						}
						if($found === false)
							continue;
						//$hehe = substr($hehe, 0, strpos($hehe,'e'));
						$s = $db->fetch("SELECT * FROM skills WHERE id = '".substr($hehe, 0, strpos($hehe,'e'))."'");
						$add = explode(',',$s['effects']);
						$aid = '';
						foreach($add as $a){
							$i = $db->fetch("SELECT * FROM effects WHERE id = '".$a."'");
							if(!empty($aid))
								$aid .= ',';
							$custom = '';
							if(!empty($i['dd']))
								$custom = '*'.$i['dd'];
							$aid .= $a.';'.$i['duration'].$custom;
						}
						$c = substr($hehe, strpos($hehe,'e')+1);
						$t = (($this->match['FIRST'] == false)?'1':'0').$key;
						if(strpos($hehe,'s') !== false){
							$c = substr($hehe, strpos($hehe,'e')+1, -1);
							$t = substr($hehe, strpos($hehe,'e')+1, -1);
						}
						if(!empty($aid)){
						
								$active[] = $this->match['TURN'].'='.$c.':'. $s['id'] . '[' . $aid . ']'.$t;
							
						}
							//unset($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if']);
					}
				}
			}
			foreach ($this->{$turnof}['TEAM']['COOLDOWN'][$key] as $skill => $cost) {
				if($this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] == '0') continue;
				if($this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] == 'Infinite') continue;
				if($this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] == 'None') continue;
				if(isset($this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill]))
					$this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] = $this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill]-1;
				else
					$this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] = 0;
				if($this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] < 0)
					$this->{$turnof}['TEAM']['COOLDOWN'][$key][$skill] = 0;
			}
		}
		// Check player submit skill queue
		if (isset($_POST['end']) && $_POST['end'] == 'true') {
			if($this->match['FIRST'] == false){
				$opponent = $this->first;
				$this->first = $this->second;
				$this->second = $opponent;
			}
			//Find if targets exist
            $targets = array(
                '00' => isset($_POST['00-targeted']) ? $_POST['00-targeted'] : 'X',
                '01' => isset($_POST['01-targeted']) ? $_POST['01-targeted'] : 'X',
                '02' => isset($_POST['02-targeted']) ? $_POST['02-targeted'] : 'X',
                '10' => isset($_POST['10-targeted']) ? $_POST['10-targeted'] : 'X',
                '11' => isset($_POST['11-targeted']) ? $_POST['11-targeted'] : 'X',
                '12' => isset($_POST['12-targeted']) ? $_POST['12-targeted'] : 'X'
            );
            $sort = false;
            $new = false;
            if ($_POST['order'] !== 'X' && stripos($_POST['order'], ',') !== false) {
                $sort = explode(',', $_POST['order']);
                $new = array();
                foreach ($sort as $it) {
                    foreach ($this->first['TEAM']['ID'] as $key => $character) {
                        if (isset($this->first['TEAM']['SKILL'][$key][$it])) {
                            array_push($new, $key);
                            break;
                        }
                    }
                }
            }
			
			foreach ($this->first['TEAM']['ID'] as $key => $character) {
                if ($new !== false) {
                    $sort = $new;
                    break;
                }
				if(!isset($_POST['0' . $key . '-skill']))
					continue;
                if (!is_numeric($_POST['0' . $key . '-skill'])){
					continue;
                }
				$sort[] = $key;
            }
            
			if($sort == false) goto skippy;
			$l = 0;
			foreach ($sort as $key) {
				if ($this->first['TEAM']['HEALTH'][$key] == '0')
                    continue;
				
                if (!is_numeric($_POST['0' . $key . '-skill'])){
                    continue;
				}
                $character = $this->first['TEAM']['ID'][$key];
                $skill = $db->query("SELECT * FROM skills WHERE id = '" . $_POST['0' . $key . '-skill'] . "'");
                if ($skill->rowCount() == 0)
                    continue;

                $skill = $db->fetch("SELECT * FROM skills WHERE id = '" . $_POST['0' . $key . '-skill'] . "'");
				$extra = 0;
				if($l == $skill['id'] && strpos($skill['targets'],'r') !== false/* && $this->{($_ == 0)?'first' : 'second'}['TEAM']['ID'][$__] !== $this->first['TEAM']['ID'][$key] && $this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] !== $account['id']*/)
					continue;
				if(isset($this->first['TEAM']['STATUS'][$key]['increase-cost']))
					$extra = $this->first['TEAM']['STATUS'][$key]['increase-cost'];
			    if (($this->first['TEAM']['SKILL'][$key][$skill['id']]+$extra) > $this->first['TEAM']['MANA'][$key])
                    continue;	
				if(isset($this->first["TEAM"]["STATUS"][$key]['set-skill']) && strpos($this->first["TEAM"]["STATUS"][$key]['set-skill'], 'uncounterable') !== false){
					$skill['uncounterable'] = 1;
				}
				if(isset($this->first["TEAM"]["STATUS"][$key]['set-skill']) && strpos($this->first["TEAM"]["STATUS"][$key]['set-skill'], 'unreflectable') !== false){
					$skill['unreflectable'] = 1;
				}
				if(isset($this->first['TEAM']['STATUS'][$key]['feared'])){
					$fears = explode(',',$this->first['TEAM']['STATUS'][$key]['feared']);
					$continue = false;
					$classes = explode(',',$skill['classes']);
					foreach($fears as $fear){
						if($fear == 'all')
							$continue = true;
						elseif(array_search($fear, $classes) !== false)
							$continue = true;
						if($continue === true)
							break;
					}
					if($continue === true){
						$rand = rand(0,100);
						if($rand < 50)
							continue;
					}
				}
				if(isset($this->first['TEAM']['STATUS'][$key]['targetme']))
					$skill['targets'] = $this->first['TEAM']['STATUS'][$key]['targetme'][2];
				
                foreach ($targets as $id =>  &$target){
					// TEAM TARGET
                    $_ = substr($id, 0, 1);
					// CHARACTER
                    $__ = substr($id, 1, 1);
                    $targeted = explode(',', $target);
                    foreach ($targeted as $w =>  &$me) {
                        if ($me == 'X')
                            continue;
                        if ($me !== $skill['id'])
                            continue;
						if($l == $skill['id'])
							continue;
						if(strpos($skill['targets'],'r') !== false/* && $this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] !== $account['id']*/){
							$blank = false;
							do {
								$__ = rand(0,2);
								if($this->{($_ == 0)?'first' : 'second'}['TEAM']['HEALTH'][$__] !== '0')
									if($this->checkInvulnerability($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['invulnerability'], $skill) === false)
										$blank = true;
							} while ($blank === false);
							$l = $skill['id'];
						}
						//Countered
						$counter = false;
						if(isset($this->first['TEAM']['ID'][$key]['ignore']) && strpos($this->first['TEAM']['ID'][$key]['ignore'], 'counter') !== false)
							$counter = true;
						if($skill['uncounterable'] == '1')
							$counter = true;
						if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter']) && strpos($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter'], 's') !== false)
							$counter = true;
						if(isset($this->first['TEAM']['ID'][$key]['no-ignore']) && strpos($this->first['TEAM']['ID'][$key]['no-ignore'], 'counter') !== false)
							$counter = false;
						if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter']) && $this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] != $account['id'] && $counter === false){
							$record[] = $this->match['TURN'].'='.(($this->match['FIRST'] == true) ? 0:1). $key .':' . $skill['id'] . '[1;e]'. (($this->match['FIRST'] == false) ? (($_==0) ? 1 : 0) : $_ ) . $__;
							$lookingfor = $_POST['0' . $key . '-skill'];
							$checkme = $active;
							foreach($checkme as $k => $v){
								$t = $this->makemeunderstand($v);
								if($t['turn'] == $this->match['TURN'] && key($t['skill']) == $lookingfor)
								{	unset($checkme[$k]);
									continue;
								}
								if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter']) && $this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter'] != key($t['skill']))
									continue;
								foreach(reset($t['skill']) as $effect => $turnsleft){
									$e = $db->fetch("SELECT * FROM effects WHERE id = '".$effect."'");
									if(!empty($e['counter'])){
										if($e['count'] !== '1') 
											unset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['counter']);
										if($e['counter'] !== 'Nothing'){
											$q = $db->fetch("SELECT * FROM skills WHERE id = '".$e['counter']."'");
											$i = explode(',',$q['effects']);
											$w = '';
											foreach($i as $m){
												$j = $db->fetch("SELECT * FROM effects WHERE id = '".$m."'");
												if(!empty($w))
													$w .= ',';
												$w .= $m.';'.$j['duration'];
											}
											$return = $this->match['TURN'].'='.(($this->match['FIRST'] == false) ? (($_==0) ? 0 : 1) : $_ ) . $__ .':' . $e['counter'] . '[' . $w . ']'.(($this->match['FIRST'] == true) ?0:1). $key ;
										}
										$t['skill'][key($t['skill'])][$effect] = 'e';
									}
								}
								$t = $this->makemeunderstand($t);
								$checkme[$k] = $t;
							}
							if(!empty($return))
								$checkme[] = $return;
							$active = $checkme;
							
							// Find all skills used by this character and remove
							$are = $targets;
							foreach($are as $we => $tar){
								$tar = explode(',',$tar);
								foreach($tar as $a => $move){
									if($move == $lookingfor){
										unset($tar[$a]);
									}
									$targeted[$a] = $tar;
								}
								if(empty($tar))
									$targets[$we] = 'X';
								else
									$targets[$we] = implode(',',$tar);
							}
							continue;
							
						}
						
						//Counter on enemy
						$counter = false;
						if(isset($this->first['TEAM']['STATUS'][$key]['ignore']) && strpos($this->first['TEAM']['STATUS'][$key]['ignore'], 'counter') !== false)
							$counter = true;
						if($this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] == $account['id'])
							$counter = true;
						if($skill['uncounterable'] == '1')
							$counter = true;
						if(isset($this->first['TEAM']['ID'][$key]['no-ignore']) && strpos($this->first['TEAM']['ID'][$key]['no-ignore'], 'counter') !== false)
							$counter = false;
						if(isset($this->first['TEAM']['STATUS'][$key]['counter']) && strpos($this->first['TEAM']['STATUS'][$key]['counter'], 's') !== false && $counter === false){
							$castedby = substr($this->first['TEAM']['STATUS'][$key]['counter'],strpos($this->first['TEAM']['STATUS'][$key]['counter'],'s')+1);
							$this->first['TEAM']['STATUS'][$key]['counter'] = substr($this->first['TEAM']['STATUS'][$key]['counter'],0, strpos($this->first['TEAM']['STATUS'][$key]['counter'], 's'));
							$record[] = $this->match['TURN'].'='.(($this->match['FIRST'] == true) ? 0:1). $key .':' . $skill['id'] . '[1;e]'. (($this->match['FIRST'] == false) ? (($_==0) ? 1 : 0) : $_ ) . $__;
							$lookingfor = $_POST['0' . $key . '-skill'];
							$checkme = $active;
							foreach($checkme as $k => $v){
								$t = $this->makemeunderstand($v);
								if($t['turn'] == $this->match['TURN'] && array_keys($t['skill'])[0] == $lookingfor)
								{	unset($checkme[$k]);
									continue;
								}
								if($this->first['TEAM']['STATUS'][$key]['counter'] != array_keys($t['skill'])[0])
									continue;
								foreach(reset($t['skill']) as $effect => $turnsleft){
									$e = $db->fetch("SELECT * FROM effects WHERE id = '".$effect."'");
									if(!empty($e['counter'])){
										if($e['count'] !== '1') 
											unset($this->first['TEAM']['STATUS'][$key]['counter']);
										if($e['counter'] !== 'Nothing'){
											$q = $db->fetch("SELECT * FROM skills WHERE id = '".$e['counter']."'");
											$i = explode(',',$q['effects']);
											$w = '';
											foreach($i as $m){
												$j = $db->fetch("SELECT * FROM effects WHERE id = '".$m."'");
												if(!empty($w))
													$w .= ',';
												$w .= $m.';'.$j['duration'];
											}
										}
										$return =$this->match['TURN'].'='.$castedby.':' . $e['counter'] . '[' . $w . ']'.(($this->match['FIRST'] == true) ? 0:1). $key ;
	
										$t['skill'][key($t['skill'])][$effect] = 'e';
									}
								}
								$t = $this->makemeunderstand($t);
								$checkme[$k] = $t;
							}
							if(!empty($return))
								$checkme[] = $return;
							$active = $checkme;
							
							// Find all skills used by this character and remove
							$are = $targets;
							foreach($are as $we => $tar){
								$tar = explode(',',$tar);
								foreach($tar as $a => $move){
									if($move == $lookingfor){
										unset($tar[$a]);
									}
									$targeted[$a] = $tar;
								}
								if(empty($tar))
									$targets[$we] = 'X';
								else
									$targets[$we] = implode(',',$tar);
							}
							continue;
							
						}
						
						$effects = explode(',', $skill['effects']);
                        $_add = '';
						
                        foreach ($effects as $effect) {
                            $data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
                            if ($data->rowCount() == 0)
                                continue;
                            $data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
							$continue = false;
							$self = false;
							switch($data['target']){
								case 'S':
									if($this->{($_ == 0)?'first' : 'second'}['TEAM']['ID'][$__] !== $this->first['TEAM']['ID'][$key] || $this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] !== $account['id']) 
										$continue = true;	
									$self = true;
								break;
								case 'A':
									if(!in_array($this->{(($_ == 0)?'first' : 'second')}['TEAM']['ID'][$__],$this->first['TEAM']['ID']) || (($_ == 0)?'first' : 'second') == 'second'|| $this->{($_ == 0)?'first' : 'second'}['TEAM']['ID'][$__] == $this->first['TEAM']['ID'][$key]) 
										$continue = true;
								break;
								case 'E':
									if(!in_array($this->{($_ == 0)?'first' : 'second'}['TEAM']['ID'][$__], $this->second['TEAM']['ID']) || (($_ == 0)?'first' : 'second') == 'first') 
										$continue = true;
								break;
								default:
								
								break;
							}
							if(strpos($skill['targets'], 'O/r') !== false && $self === false)
								$continue = false;
							
							if($continue === true) 
								continue;
							
							if(!empty($_add)) 
								$_add .= ',';
							$custom = '';
							if(!empty($data['dd']))
								$custom = '*'.$data['dd'];
							$_add .= $data['id']. ';' . $data['duration'].$custom;
                        }
                        if (!empty($_add)){
							$ignore = false;
							if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['ignore']) && strpos($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['ignore'], 'reflect') !== false)
								$ignore = true;
							if($skill['unreflectable'] == '1')
								$ignore = true;
							if($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['reflect'][1] !== 'all'){
								$classes = explode(',', $skill['classes']);
								$specifics = explode(',', $this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['reflect'][1]);
								$found = false;
								foreach($specific as $s){
									if(array_search($s,$classes) !== false)
										$found = true;
								}
								if($found === false)
									$ignore = true;
							}
							if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['no-ignore']) && strpos($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['no-ignore'], 'reflect') !== false)
								$ignore = false;
							if(isset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['reflect']) && $this->{($_ == 0)?'first' : 'second'}['ACCOUNT']['id'] !== $account['id'] && $ignore === false){
								$checkme = $active;
								foreach($checkme as $k => $v){
									$t = $this->makemeunderstand($v);
									if($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['reflect'][0] != key($t['skill']))
										continue;
									foreach(reset($t['skill']) as $effect => $turnsleft){
										$e = $db->fetch("SELECT * FROM effects WHERE id = '".$effect."'");
										if($e['count'] !== '1') 
											unset($this->{($_ == 0)?'first' : 'second'}['TEAM']['STATUS'][$__]['reflect']);
									}
									$t['skill'][key($t['skill'])][$effect] = 'e';
									$t = $this->makemeunderstand($t);
									$checkme[$k] = $t;
								}
								
								$active = $checkme;
								$active[] = $this->match['TURN'].'='. (($this->match['FIRST'] == false) ? (($_==0) ? 1 : 0) : $_ ) . $__. ':' . $skill['id'] . '[' . $_add . ']'. (($this->match['FIRST'] == true) ? 0:1). $key;

							}else{
								$active[] = $this->match['TURN'].'=' . (($this->match['FIRST'] == true) ? 0:1). $key . ':' . $skill['id'] . '[' . $_add . ']'.(($this->match['FIRST'] == false) ? (($_==0) ? 1 : 0) : $_ ) . $__;

							}
						}
					}
                }
				$extra = 0;
				if(isset($this->first['TEAM']['STATUS'][$key]['increase-cost']))
					$extra = $this->first['TEAM']['STATUS'][$key]['increase-cost'];
				$result = $this->first['TEAM']['SKILL'][$key][$skill['id']]+$extra;
				if($result < 0)
					$result = 0;
                $this->first['TEAM']['MANA'][$key] -= $result;
				if($skill['cooldown'] == 'None')
					$skill['cooldown'] = 0;
				$extra = 0;
				if(isset($this->first["TEAM"]["STATUS"][$key]['increase-cooldown'][$skill['id']]))
					$extra = (float)$this->first["TEAM"]["STATUS"][$key]['increase-cooldown'][$skill['id']];
				if(isset($this->first["TEAM"]["STATUS"][$key]['increase-cooldown']['all']))
					$extra += (float)$this->first["TEAM"]["STATUS"][$key]['increase-cooldown']['all'];
				
				$this->first['TEAM']['COOLDOWN'][$key][$skill['id']] = ($skill['cooldown'] !== 'Infinite')?$skill['cooldown']+$extra:$skill['cooldown'];
				if($this->first['TEAM']['COOLDOWN'][$key][$skill['id']] < 0)
					$this->first['TEAM']['COOLDOWN'][$key][$skill['id']] = 0;
				if(!empty($skill['shared_cooldown']))
					$this->first['TEAM']['COOLDOWN'][$key][$skill['shared_cooldown']] = $skill['cooldown']+$extra;
				if(isset($this->first['TEAM']['COOLDOWN'][$key][$skill['shared_cooldown']]) && $this->first['TEAM']['COOLDOWN'][$key][$skill['shared_cooldown']] < 0)
					$this->first['TEAM']['COOLDOWN'][$key][$skill['shared_cooldown']] = 0;
				
					
            }
			skippy:
			if($this->match['FIRST'] == false){
				$opponent = $this->first;
				$this->first = $this->second;
				$this->second = $opponent;
			}
        }
		$this->match['MATCH']['active'] = implode('/',$active);
		if(!empty($active)){
		foreach ($active as $key => $value) {
			if($key <= $last) continue;
			$what = $this->makemeunderstand($value);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $key), $what['turn']);
			}
		}}	
		
		
        if(empty($active)) goto endturn;
		
		/* Process everything */
		$removed = array();
		$backed = $active;
		
        foreach ($active as $key => &$value) {
			$what = $this->makemeunderstand($value);
			if(!empty($removed) && array_search($key,$removed) !== false){
				if($db->fieldFetch('skills', key($what['skill']), 'no-remove') !== '0')
					unset($removed[$key]);
				else
					continue;
			}
			// Check to see if its the casters turn ...
			if($this->{$what['caster'][0]}['ACCOUNT']['id'] != $who) continue;
			$ignored = false;
			$executed = false;
			foreach(reset($what['skill']) as $effect => $turnsleft){
				
				$original = $turnsleft;
				$_value = '';
				if(strpos($turnsleft,'*') !== false){
					$_value = substr($turnsleft, strpos($turnsleft,'*')+1);
					$turnsleft = substr($turnsleft,0,strpos($turnsleft,'*'));
				}
				if(!is_numeric($turnsleft)){
					if(strpos($turnsleft, 'e') !== false){
						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
					if($this->{$what['caster'][0]}['TEAM']['HEALTH'][$what['caster'][1]] == '0' && strpos($turnsleft, 'c') !== false){
						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
					if($this->{$what['target'][0]}['TEAM']['HEALTH'][$what['target'][1]] == '0' && strpos($turnsleft, 't') !== false){
						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
					
					if($this->{$what['target'][0]}['TEAM']['MANA'][$what['target'][1]] == '0' && strpos($turnsleft, 'm') !== false){
						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
					if($this->checkStun($this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['stunned'], $db->fieldFetch('skills',key($what['skill']),'classes')) !== false && strpos($turnsleft, 's') !== false){

						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
					if($this->checkInvulnerability($this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['invulnerability'], $db->fieldFetch('skills',key($what['skill']),'classes')) !== false && strpos($turnsleft, 'i') !== false){

						unset($what['skill'][key($what['skill'])][$effect]);
						continue;
					}
				}
				
				if(isset($this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['increase-duration']) && $what['turn'] == $this->match['TURN']){
					$increase = $this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['increase-duration']['all'];
					if(isset($this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['increase-duration'][$skill['id']]))
						$increase += $this->{$what['caster'][0]}['TEAM']['STATUS'][$what['caster'][1]]['increase-duration'][$skill['id']];
					$turnsleft += $increase;
				}
							
				
				if(isset($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore'])){
					
					if(strpos($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore'], 'allexcept') !== false){

						if($db->fieldFetch('effects', $effect, 'ignore') !== 'allexcept'){
							$returned = true;
							$ignored = true;
						}
					}elseif(strpos($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore'], 'allenemy') !== false){
						if($what['target'][0] !== $what['caster'][0]){
							$returned = true;
							$ignored = true;
						}
					}elseif(strpos($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore'], 'allally') !== false){
						if($what['target'][0] === $what['caster'][0] && $what['target'][1] !== $what['caster'][1]){
							$returned = true;
							$ignored = true;
						}
					}elseif(strpos($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore'], 'all') !== false && empty($db->fieldFetch('effects', $effect, 'replace'))){
						//$check = $db->fetch("SELECT * FROM effects WHERE id = '".$effect."'");
						//if(empty($check['replace'])){
							$returned = true;
							$ignored = true;
						//}
					}
				}
				if($ignored === false)
					$returned = $this->doEffect('Process', array($what['target'], $what['caster'], $effect, $original, key($what['skill']), $key), $what['turn']);
				else
					$returned = true;
				
				if($returned === true){
					$executed = true;
				}
				
				if($returned === false){
					$executed = false;
					unset($what['skill'][key($what['skill'])]);
					continue;
				}elseif(strpos($returned, 'remove') !== false){
					$executed = true;
					$returned = substr($returned, strpos($returned, '-')+1);
					$s = false;
					if(strpos($returned, 'status') !== false && strpos($returned,'caster') === false && strpos($returned,'target') === false){
						$returned = substr($returned, 0, -7);
						$s = true;
						
					}else{
						if(strpos($returned,'caster') !== false){
							$s = 'caster';
							$returned = substr($returned, 0, -7);
						}
						if(strpos($returned,'target') !== false){
							$s = 'target';
							$returned = substr($returned, 0, -7);
						}
						
					}
						
					foreach($backed as $k => $v){
						$t = $this->makemeunderstand($v);
						if($what['target'][0] == $t['target'][0] || $what['target'][0] == $t['caster'][0]){
							// This is a skill active on me?
							// Are we removing beneficial or harmful
						//var_dump(array_keys($t['skill'])[0]);
				
							if($s === 'target'){
								
								$target = false;
								if($what['target'][1] == $t['target'][1] && $what['target'][0] == $t['target'][0])
									$target = true;
								else
									continue;
								
								if(array_keys($t['skill'])[0] == $returned){
									// check if looking for effect specific
									
									$removed[] = $k;
								}else{
									
									if(strpos($returned, '+') !== false){
										$returned = substr($returned, 1);
										$check = false;
										foreach(reset($t['skill']) as $ef => $tl){
											$check = $db->fieldFetch('effects', $ef, $returned);
											if($check == 'undefined')
												$check = false;
											elseif(!empty($check))
												$check = true;
										}
										if($check === true)
											$removed[] = $k;
									}
								}
								continue;
							}
							if($s === 'caster'){
								$caster = false;
								if($what['target'][1] == $t['caster'][1] && $what['target'][0] == $t['caster'][0])
									$caster = true;
								else
									continue;
								
								if(array_keys($t['skill'])[0] == $returned){
									$removed[] = $k;
								}else{
									
									if(strpos($returned, '+') !== false){
										$returned = substr($returned, 1);
										$check = false;
										foreach(reset($t['skill']) as $ef => $tl){
											$check = $db->fieldFetch('effects', $ef, $returned);
											if($check == 'undefined')
												$check = false;
											elseif(!empty($check))
												$check = true;
										}
										if($check === true)
											$removed[] = $k;
									}
								}
								continue;
							}
							$me = false;
							if($what['target'][1] == $t['target'][1] && $what['target'][0] == $t['target'][0])
								$me = true;
							if($what['target'][1] == $t['caster'][1] && $what['target'][0] == $t['caster'][0])
								$me = true;
							if($what['target'][0] !== $t['target'][0] && $what['target'][0] == $t['caster'][0] && $what['target'][1] == $t['caster'][1])
								$me = false;
							if($what['target'][1] !== $t['target'][1] && $what['target'][0] == $t['caster'][0] && $what['target'][1] == $t['caster'][1])
								$me = false;
							if($me === false)
								continue;

							if(array_keys($t['skill'])[0] == array_keys($what['skill'])[0]) continue;
							$me = $db->query("SELECT * FROM skills WHERE id = '".array_keys($t['skill'])[0]."'");
							$me = $me->fetch();
							
							if($returned === 'all'){
								if($me['status'] == '1' && $s === false) continue;
								$removed[] = $k;
								continue;
							}
							
							// Skills I use on myself should be continued...
							//if($t['target'][0] === $t['caster'][0] && $t['target'][1] === $t['caster'][1]) continue;
							if($t['target'][0] == $t['caster'][0] && $returned !== 'beneficial') continue;
							if($t['target'][0] != $t['caster'][0] && $returned !== 'harmful') continue;
							
							if($me['status'] == '1' && $s === false) continue;
							$removed[] = $k;
						}
					}
					$returned = true;
				}
				
				
				$record[] =  $value;
				$originated = '';
				if(strpos($turnsleft, '+') !== false){
					$originated = explode('+',$turnsleft);
					foreach($originated as $origin){
						if(preg_match('~[0-9]+~', $origin))
							$turnsleft = $origin;
					}
				}
				if(is_numeric($turnsleft) && $turnsleft >= '0'){
					$turnsleft--;
				}
				if($turnsleft < '0'){
					unset($what['skill'][key($what['skill'])][$effect]);
					continue;
				}
				if(!empty($originated)){
					foreach($originated as &$origin){
						if(preg_match('~[0-9]+~', $origin))
							$origin = $turnsleft;
					}
					$turnsleft = implode('+',$originated);
				}
				$what['skill'][key($what['skill'])][$effect] = $turnsleft;
				if(isset($_value)&&$_value !== ''){
					$what['skill'][key($what['skill'])][$effect] = $turnsleft.'*'.$_value;
				}
			}
			// Check for characters that use skills 
			if($executed == true){
				if($what['turn'] !== $this->match['TURN'])
					goto skipif;
				if($what['target'][0] == $what['caster'][0])
					goto skipif;
				if(isset($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if'])){
					$all = explode(',',$this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if'][0]);
					$amounts = explode(',',$this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if'][2]);
					foreach($all as $k=>$hehe){
						if(strpos($hehe,'n') == false && strpos($hehe,'ally') !== false){
							$specific = explode('/', $this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['if'][1]);
							$specific = explode(',', $specific[$k]);
							$classes = explode(',',$db->fieldFetch('skills', array_keys($what['skill'])[0], 'classes'));
							$found = false;
							foreach($specific as $s){
								if($s == 'all')
									$found = true;
								if(array_search($s, $classes) !== false)
									$found = true;
								if($found === true)
									break;
							}
							if($found === false)
								continue;
							$target = $what['caster'];
							$caster = substr($hehe, strpos($hehe,'e')+1,-4);
							if(strpos($hehe,'s') !== false){
								$target = $what['target'];
								$caster = substr($hehe, strpos($hehe,'e')+1,-5);
								if(strpos($hehe,'sc') !== false){
									$target[1] = substr($hehe, strpos($hehe,'e')+2,-6);
									$caster = substr($hehe, strpos($hehe,'e')+1,-6);
								}
							}
							$hehe = substr($hehe, 0, strpos($hehe,'e'));
							$hehe = $db->fetch("SELECT * FROM skills WHERE id = '".$hehe."'");
							$add = explode(',',$hehe['effects']);
							$aid = '';
							foreach($add as $a){
								$i = $db->fetch("SELECT * FROM effects WHERE id = '".$a."'");
								if(!empty($aid))
									$aid .= ',';
								
								$custom = '';
								if(!empty($i['dd']))
									$custom = '*'.$i['dd'];
								$aid .= $a.';'.$i['duration'].$custom;
							}
							if(!empty($aid)){
								
									$active[] = $this->match['TURN'].'='.$caster.':' . $hehe['id'] . '[' . $aid . ']'.(($target[0] == 'first')? '0':'1').$target[1];
								
							}
						}
					}
				}
				$executed = false;
			}
			skipif:
			if(empty($what['skill'][key($what['skill'])])){
				$act = $db->fetch('SELECT * FROM skills WHERE id = "' . key($what['skill']) . '"');
				if(!empty($act['whenend']) && isset($act['activate']) && !empty($act['activate'])){
					$act['activate'] = explode(',',$act['activate']);
					foreach($act['activate'] as $act){
					$activate = substr($act, 1);
					$twho = '';
					if(strpos($act, 'a')!==false){
						$activate = substr($act,1,strpos($act,'a')-1);
						$twho = 'all';
					}
					$w = (substr($act,0,1)=='c')?$what['caster']:$what['target'];
					$add = explode(',',$db->fieldFetch('skills', $activate, 'effects'));
					$aid = '';
					foreach($add as $a){
						$i = $db->fetch("SELECT * FROM effects WHERE id = '".$a."'");
						if(!empty($aid))
							$aid .= ',';
						$custom = '';
						if(!empty($i['dd']))
							$custom = '*'.$i['dd'];
						$aid .= $a.';'.$i['duration'].$custom;
					}
					if(!empty($aid)){
						if(strpos($db->fieldFetch('skills', $activate, 'targets'), 'a') !== false || $twho == 'all'){
							foreach($this->{$w[0]}['TEAM']['ID'] as $k => $character){
								$active[] = $this->match['TURN'].'='.(($what['caster'][0]=='first')?'0':'1').$what['caster'][1].':' . $activate . '[' . $aid . ']'.(($w[0]=='first')?'0':'1').$k;
							}
						}else{
							$active[] = $this->match['TURN'].'='.(($what['caster'][0]=='first')?'0':'1').$what['caster'][1].':' . $activate . '[' . $aid . ']'.(($w[0]=='first')?'0':'1').$w[1];
						}
					}
					}
				}
				unset($active[$key]);
			}else {
				$act = $db->fetch('SELECT * FROM skills WHERE id = "' . key($what['skill']) . '"');
				
				if(empty($act['whenend']) && isset($act['activate']) && $act['activate'] !== '0' && $this->match['TURN'] >= $what['turn']){
					$act['activate'] = explode(',',$act['activate']);
					foreach($act['activate'] as $act){
					$activate = substr($act, 1);
					$times = ((strpos($act,'+')!==false)?substr($act,strpos($act,'+')+1):'');
					$counter = 0;
					foreach($backed as $k => $v){
						$t = $this->makemeunderstand($v);
						if($what['caster'][0] == $t['caster'][0]){
							if(key($t['skill']) == $activate)
								$counter++;
						}
					}
					if(!empty($times) && $counter == $times)
						goto skipactivate;
					$w = (substr($act,0,1)=='c')?$what['caster']:$what['target'];
					$add = explode(',',$db->fieldFetch('skills', $activate, 'effects'));
					$aid = '';
					foreach($add as $a){
						$i = $db->fetch("SELECT * FROM effects WHERE id = '".$a."'");
						if(!empty($aid))
							$aid .= ',';
						$custom = '';
						if(!empty($i['dd']))
							$custom = '*'.$i['dd'];
						$aid .= $a.';'.$i['duration'].$custom;
					}
					if(!empty($aid))
						$active[] = $this->match['TURN'].'='.(($what['caster'][0]=='first')?'0':'1').$what['caster'][1].':' . $activate . '[' . $aid . ']'.(($w[0]=='first')?'0':'1').$w[1];
					}
				}
				$active[$key] = $this->makemeunderstand($what);
			}
			skipactivate:
			if(!empty($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['count']) && $this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['count'] == 1)
			{	if($ignored == true)
					unset($this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['ignore']);
			}
        }
		
		// Update custom values
		foreach ($active as $key => &$value) {
			$what = $this->makemeunderstand($value);
			// Check to see if its the casters turn ...
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$original = $turnsleft;
				$_value = '';
				if(strpos($turnsleft,'*') !== false){
					$_value = substr($turnsleft, strpos($turnsleft,'*')+1);
					$turnsleft = substr($turnsleft,0,strpos($turnsleft,'*'));
				}
				if(isset($_value)&&$_value !== ''){
					if(isset($this->{$what['target'][0]}['TEAM']['STATUS'][$what['target'][1]]['dd'][$key])){
						if($this->{$what['target'][0]}['TEAM']['STATUS'][$what['target'][1]]['dd'][$key][1] !== $effect) continue;
						$_value = $this->{$what['target'][0]}["TEAM"]["STATUS"][$what['target'][1]]['dd'][$key][0];
					}
					if(isset($this->{$what['target'][0]}['TEAM']['STATUS'][$what['target'][1]]['destroy-dd']))
						$_value = 0;
					$what['skill'][key($what['skill'])][$effect] = $turnsleft.'*'.$_value;
					if(!isset($this->{$what['target'][0]}['TEAM']['STATUS'][$what['target'][1]]['renew']) && $_value == 0)
						unset($what['skill'][key($what['skill'])][$effect]);
				}
			}
			
			if(empty($what['skill'][key($what['skill'])])){
				unset($active[$key]);
			}else{
				$active[$key] = $this->makemeunderstand($what);
			}
		}
		
		if(!empty($removed)){
			foreach($removed as $remove){
				if($db->fieldFetch('skills', key($this->makemeunderstand($active[$remove])['skill']), 'no-remove') == '0')
					unset($active[$remove]);
			}
		}
		// *Final Step* Step Five: implode everything and update the data!
		// Check your health and the Opponents
        endturn:
		// Prepare everything for insertion
		//$newtime = $this->match['MATCH']['time'].'/'.($this->match["TURN"] + 1) . '=' . time();
		$death_0 = 0;
        $death_1 = 0;
        foreach ($this->second['TEAM']['ID'] as $key => $character) {
            if ($this->second['TEAM']['HEALTH'][$key] == '0') {
                $death_1++;
            }else{
				foreach($this->second['TEAM']['SKILL'][$key] as $skill => $cost) {
					$_skill = $db->fetch('SELECT * FROM skills WHERE id = "' . $skill . '"');
					$this->second['TEAM']['SKILL'][$key][$skill] = $_skill['cost'];
				}
			}
        }
        foreach ($this->first['TEAM']['ID'] as $key => $character) {
            if ($this->first['TEAM']['HEALTH'][$key] == '0') {
                $death_0++;
            }else{
				foreach($this->first['TEAM']['SKILL'][$key] as $skill => $cost) {
					$_skill = $db->fetch('SELECT * FROM skills WHERE id = "' . $skill . '"');
					$this->first['TEAM']['SKILL'][$key][$skill] = $_skill['cost'];
				}
			}
        }
		
		$this->manaGain($death_0, $death_1);
		$this->_implode();
        $this->match['MATCH']['status'] = 'checking';
        if ($death_1 === 3)
            $this->match['MATCH']['status'] = 'winner';
		elseif ($death_0 == 3)
            $this->match['MATCH']['status'] = 'loser';
		
		
		if(!empty($active)){
			
			if(count($active) > 1)
				$active = implode('/', $active);
			else{
				$active = reset($active);
			}
		}else
			$active = '';
		
		
		if(!empty($record)){
			if(count($record) > 1)
				$record = implode('/', $record);
			else{
				$record = reset($record);
			}
		}else
			$record = '';
		$record = $this->match['TURN'].'-'.$record;
			
		$db->query("UPDATE matches SET status = '" . $this->match['MATCH']['status'] . "' ,
			time = '" . $this->match['MATCH']['time'].'/'.($this->match["TURN"] + 1) . '=' . time() . "',
			`t-0` = '" . $this->first['TEAM']['SKILL'] . "',
			`t-1` = '" . $this->second['TEAM']['SKILL'] . "',
			`h-0` = '" . $this->first['TEAM']['HEALTH'] . "',
			`m-0` = '" . $this->first['TEAM']['MANA'] . "',
			`c-0` = '" . $this->first['TEAM']['COOLDOWN'] . "',
			`h-1` = '" . $this->second['TEAM']['HEALTH'] . "',
            `m-1` = '" . $this->second['TEAM']['MANA'] . "',
			`c-1` = '" . $this->second['TEAM']['COOLDOWN'] . "',
			`turns` = '" .(!empty($this->match['MATCH']['turns']) ? $this->match['MATCH']['turns'].'|':'').$record. "',
            `active` = '" . $active . "'
            WHERE id = '" . $this->match['MATCH']['id'] . "'");
		$system->redirect('./ingame');
		
    }


	function availableTargets($character, $skill) {
        global $system, $db;
        $skill = $db->query('SELECT * FROM skills WHERE id = "' . $skill . '"')->fetch();
        $cost = $this->first['TEAM']['SKILL'][$character][$skill['id']];
		$extra = 0;
		if(isset($this->first["TEAM"]["STATUS"][$character]['increase-cost']))
			$extra = $this->first["TEAM"]["STATUS"][$character]['increase-cost'];
		$cost = $cost+$extra;
        $targets = explode('|', $skill['targets']);
		foreach($this->first['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $character){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '0'.$m;
				}
			}
		}
		foreach($this->second['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $character){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '1'.$m;
				}
			}
		}
        
		if(isset($this->first["TEAM"]["STATUS"][$character]['targetme']) && strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][2],'not') === false)
			$targets = array($this->first["TEAM"]["STATUS"][$character]['targetme'][2]);
		$targeting = array();
		
		if(isset($this->first["TEAM"]["STATUS"][$character]['set-skill']) && strpos($this->first["TEAM"]["STATUS"][$character]['set-skill'], 'bypass') !== false)
			$skill['iinvul'] = '1';
		if(isset($this->first["TEAM"]["STATUS"][$character]['no-ignore']) && strpos($this->first["TEAM"]["STATUS"][$character]['no-ignore'], 'iinvul') !== false)
			$skill['iinvul'] = '0';
		foreach ($targets as $target) {
            switch ($target) {
				case "00";
				case "10";
				case "01";
				case "11";
				case "02";
				case "12";
					$m = substr($target,0,1);
					$k = substr($target,1);
					if ($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && empty($skill['dead']))
						break;
					if ($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && isset($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['no-resurrect']))
						break;
                    if ($this->checkInvulnerability($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
						break;
					$targeting[$m.$k] = true;
					break;
                case "E/1";
                case "E/r";
                case "E/a";
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
                            continue;
						if(isset($this->first["TEAM"]["STATUS"][$character]['targetme']) && strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][2],'not') !== false){
							if(strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][3], 'all') === false){
								$specifics = explode(',',$this->first["TEAM"]["STATUS"][$character]['targetme'][3]);
								$true = false;
								$classes = explode(',',$skill['classes']);
								foreach($specifics as $s){
									if(array_search($s,$classes) !== false)
										$true = true;
								}
								if($true === false)
									goto skipnotclass;
							}
							if(substr($this->first["TEAM"]["STATUS"][$character]['targetme'][0], -1) == $key && $this->first["TEAM"]["STATUS"][$character]['targetme'][2] == 'notcaster')
								continue;
							elseif(substr($this->first["TEAM"]["STATUS"][$character]['targetme'][0], -1) != $key && $this->first["TEAM"]["STATUS"][$character]['targetme'][2] == 'notallys')
								continue;
							
							
						}
						skipnotclass:
                        $targeting['1' . $key] = true;
                    }
                    break;
                case "A/1";
                case "A/r";
                case "A/a";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($key == $character)
                            continue;
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    break;
                case "S":
                    $targeting['0' . $character] = true;
                    break;
                case "O/r";
                case "O";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
                            continue;
                        $targeting['1' . $key] = true;
                    }
                    break;
            }
        }
        return ($this->match['ME'] === true)?$targeting:false;
    }
}

class GAjax {
	function getUI(){
		global $system, $db, $user, $account, $_POST, $json, $ttime;
		if($this->match['FIRST'] == false){
			$opponent = $this->first;
			$this->first = $this->second;
			$this->second = $opponent;
		}
		
		// Global variables
        $healths = array();
		$mana = array();
		$team = array();
        foreach ($this->second['TEAM']['ID'] as $key => $character) {
			$image =  (strpos($user->image($character, 'characters/slanted', './../../', 'character fl-r flip'),'default') !== false?'<p>?</p>':$user->image($character, 'characters/slanted', './../../', 'character fl-r flip'));
			$team['1'.$key]['original'] = true;
			if ($this->second['TEAM']['HEALTH'][$key] === '0'){
				$image = '<p>X</p>';
			}
            $left = round(($this->second['TEAM']['HEALTH'][$key] / $db->fieldFetch('characters', $character, 'health')) * 100);
            if ($left > 100)
                $left = 100;
            $background = '';
            if ($left <= 75)
				$background = 'linear-gradient(to right, yellow 60%, transparent 100%)';
            if ($left <= 40)
                $background = 'linear-gradient(to right, red 60%, transparent 100%)';
			$healths['1'.$key]['total'] = $this->second['TEAM']['HEALTH'][$key];
			$healths['1'.$key]['width'] = $left;
			$healths['1'.$key]['background'] = $background;
			$left = 'height: '.$left.'%;';
			if(!empty($background))
				$background = ' style = "'.$background.'"';
			// Get opponent active skills
			if(empty($this->match['MATCH']['active'])) goto skipped;
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			$add = '';
			$words = $active;
			$turnstore = '';
			foreach($words as &$value){
				$turn = substr($value, 0, stripos($value, "="));
				$value = substr($value, stripos($value, "=")+1);
				$turnstore[$value] = $turn;
			}
			$words = array_count_values($words);
			foreach ($words as $value => $count) {
				$value = $turnstore[$value].'='.$value;
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				// Check to see if im first or second
				if($what['target'][0] != 'second') continue;
				if($what['target'][1] != $key) continue;
				foreach(reset($what['skill']) as $effect => $turnsleft){
					$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), array_search($value,$active)), $what['turn']);
				}					
			}
			foreach ($words as $value => $count) {
				$value = $turnstore[$value].'='.$value;
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				// Check to see if im first or second
				if($what['target'][0] != 'second') continue;
				if($what['target'][1] != $key) continue;
				$store = $this->doEffect('Description', $what, './../../');
				$add .= $store;
				if($count > 1 && !empty($store))
					$add .= '<span class="stack r">'.$count.'</span>';
			}
			if(!empty($add))
				$team['1'.$key]['active'] = $add;
			skipped:
			if(isset($this->second["TEAM"]["STATUS"][$key]['transform'])){
				$image = (strpos($user->image($this->second["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './../../', 'character fl-r flip'),'default') !== false?'<p>?</p>':$user->image($this->second["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './../../', 'character fl-r flip'));
				$team['1'.$key]['original'] = false;
			}
			$team['1'.$key]['image'] = $image;
        }
		
        // Step Two: Setup Player 1
		// Checks for stun and invul
		if(empty($this->match['MATCH']['active'])) goto noactive;
		if(strpos($this->match['MATCH']['active'], "/") == true)
			$active = explode('/', $this->match['MATCH']['active']);
		else
			$active = array($this->match['MATCH']['active']);
		foreach ($active as $k => $value) {
			$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $k), $what['turn']);
			}
        } 
		noactive:
		$skillkey = 1;
		foreach ($this->first['TEAM']['ID'] as $key => $character) {
			$image = (strpos($user->image($character, 'characters/slanted', './../../', 'character fl-l'),'default') !== false?'<p>?</p>'
					:$user->image($character, 'characters/slanted', './../../', 'character fl-l'));
			$team['0'.$key]['original'] = true;
			if ($this->first['TEAM']['HEALTH'][$key] == '0') {
				$image = '<p>X</p>';
				$team['0'.$key]['skills'][] .= $user->image('dead', 'skills', './../../', 'fl-l skill opacity');
				$team['0'.$key]['skills'][] .=  $user->image('dead', 'skills', './../../', 'fl-l skill opacity');
				$team['0'.$key]['skills'][] .=  $user->image('dead', 'skills', './../../', 'fl-l skill opacity');
				$team['0'.$key]['skills'][] .=  $user->image('dead', 'skills', './../../', 'fl-l skill opacity');
            }
            $left = round(($this->first['TEAM']['HEALTH'][$key] / $db->fieldFetch('characters', $character, 'health')) * 100);
            if ($left > 100)
                $left = 100;
            $background = '';
            if ($left <= 75)
				$background = 'linear-gradient(to left, yellow 60%, transparent 100%)';
            if ($left <= 40)
				$background = 'linear-gradient(to left, red 60%, transparent 100%)';
			$healths['0'.$key]['total'] = $this->first['TEAM']['HEALTH'][$key];
			$healths['0'.$key]['width'] = $left;
			$healths['0'.$key]['background'] = $background;
			$left = round(($this->first['TEAM']['MANA'][$key] / $db->fieldFetch('characters', $character, 'mana')) * 100);
            if ($left > 100)
                $left = 100;
			$mana['0'.$key]['total'] = $this->first['TEAM']['MANA'][$key];
			$mana['0'.$key]['width'] = $left;
           
			if(empty($this->match['MATCH']['active'])) goto letgo;
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			$add = '';
			$words = $active;
			$turnstore = '';
			foreach($words as &$value){
				$turn = substr($value, 0, stripos($value, "="));
				$value = substr($value, stripos($value, "=")+1);
				$turnstore[$value] = $turn;
			}
			$words = array_count_values($words);
			foreach ($words as $value => $count) {
				$value = $turnstore[$value].'='.$value;
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				//Check to see if im first or second
				if($what['target'][0] != 'first') continue;
				if($what['target'][1] != $key) continue;
				$store = $this->doEffect('Description', $what, './../../');
				$add .= $store;
				if($count > 1 && !empty($store))
					$add .= '<span class="stack">'.$count.'</span>';
			}
			if(!empty($add))
				$team['0'.$key]['active'] = $add;
			letgo:
            
			if(isset($this->first["TEAM"]["STATUS"][$key]['transform'])){
				$image = $image = (strpos($user->image($this->first["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './../../', 'character fl-l'),'default') !== false?
					'<p>?</p>':$user->image($this->first["TEAM"]["STATUS"][$key]['transform'], 'characters/slanted', './../../', 'character fl-l'));
				$team['0'.$key]['original'] = false;
			}
			$team['0'.$key]['image'] = $image;
			$skillkey = 1;
			foreach ($this->first['TEAM']['SKILL'][$key] as $skill => $cost) {
				if ($this->first['TEAM']['HEALTH'][$key] == '0') 
					break;
                $opacity = '';
                $cooldown = '';
				$_skill = $db->fetch('SELECT * FROM skills WHERE id = "' . $skill . '"');
                if ($this->match['ME'] == false) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// Cooldown
                
				if ($this->first['TEAM']['COOLDOWN'][$key][$skill] !== '0') {
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] == 'None')
						$this->first['TEAM']['COOLDOWN'][$key][$skill] = 0;
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] == 'Infinite'){
						$cooldown = '<span class="cooldown">∞</span>';
						$opacity = 'opacity';
						goto skill;
					}
					if(empty($this->first['TEAM']['COOLDOWN'][$key][$skill]))
						$this->first['TEAM']['COOLDOWN'][$key][$skill] = 0;
					if($this->first['TEAM']['COOLDOWN'][$key][$skill] != 0){
						$cooldown = '<span class="cooldown">' . $this->first['TEAM']['COOLDOWN'][$key][$skill]. '</span>';
						$opacity = 'opacity';
						goto skill;
					}
				}
				
				$extra = '0';
				if(isset($this->first["TEAM"]["STATUS"][$key]['increase-cost']))
					$extra = $this->first["TEAM"]["STATUS"][$key]['increase-cost'];
                if ($this->first['TEAM']['MANA'][$key] < ($cost+$extra)) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// If stunned
				if ($this->checkStun($this->first['TEAM']['STATUS'][$key]['stunned'], $_skill['classes']) === true) {
                    $opacity = 'opacity';
					goto skill;
                } 
				// Disable skills
				if (isset($this->first['TEAM']['STATUS'][$key]['disable'])) {
					$disable = $this->first['TEAM']['STATUS'][$key]['disable'];
					$disable = explode(',',$disable);
					$disabled = false;
					foreach($disable as $what){
						if(empty($what)) continue;
						if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $what)){
							$what = substr($what, strpos($what,'S')+1);
							if($what != $skillkey) continue;
							$disabled = true;
						}elseif(strpos($what, 'Offensive') !== false){
							if(strpos($_skill['targets'], 'E') !== false)
								$disabled = true;
						}elseif(strpos($what, 'Defensive') !== false){
							if(strpos($_skill['targets'], 'A') !== false)
								$disabled = true;
							if(strpos($_skill['targets'], 'S') !== false)
								$disabled = true;
						}elseif(strpos($what,'c')!== false){
							$classes = explode(',', $_skill['classes']);
							if(array_search(substr($what,1), $classes) !== false)
								$disabled = true;
						}else{
							
							$effects = explode(',',$_skill['effects']);
							foreach($effects as $e){
								$e = $db->fetch("SELECT * FROM effects WHERE id = '".$e."'");
								if(!empty($e[$what])){
									$disabled = true;
								}
							}
						}
					}
					if($disabled === true){
						$opacity = 'opacity';
						goto skill;
					}
                } 
                // Require these skills first...
				if ($_skill['requires'] !== '0') {
                    $requires = explode('|', $_skill['requires']);
                    $done = 0;
					if(empty($this->match['MATCH']['active'])) goto noskill;
					if(strpos($this->match['MATCH']['active'], "/") == true)
						$active = explode('/', $this->match['MATCH']['active']);
					else
						$active = array($this->match['MATCH']['active']);
					foreach ($active as $value) {
						$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
						
						if($this->{$what['caster'][0]}['ACCOUNT']['id'] !== $account['id']) continue;
						
						if($this->{$what['caster'][0]}['TEAM']['ID'][$what['caster'][1]] !== $this->first['TEAM']['ID'][$key]) continue;
						
						if(($llave = array_search(key($what['skill']),$requires)) !== false){
							unset($requires[$llave]);
						}
					}
					noskill:
					if (count($requires) !== 0){
                        $opacity = 'opacity';
						goto skill;
					}
                }
				if($this->match['TURN'] == '1' || $this->match['TURN'] == '2'){
					$manaCap = ($this->match['TURN'] == '1') ? $system->data('First_Mana') : $system->data('Second_Mana');
					if($manaCap < ($cost+$extra)){
						$opacity = 'opacity';
						goto skill;
					}
				}
				$team['targets']['0'.$key][$_skill['id']]['targets'] = $this->availableTargets($key,$_skill['id']);
				$team['targets']['0'.$key][$_skill['id']]['available'] = $_skill['targets'];
				$team['targets']['0'.$key][$_skill['id']]['costs'] = (($cost+$extra)<0?0:($cost+$extra));
				skill:
				
				$classes = $_skill['classes'];
				if(!empty($classes)){
					$classes = explode(',',$_skill['classes']);
					$classes['archive'] = '<br/>';
					foreach($classes as $class){
						if($db->fieldFetch('classes',$class,'name') == 'undefined') continue;
						$classes['archive'] .= $user->image($db->fieldFetch('classes',$class,'name'), 'classes', './../../', 'skill-class" title="Skill Class '.$db->fieldFetch('classes',$class,'name').'"');
					}
				}
				
                $team['0'.$key]['skills'][] = 
			$user->image($skill, 'skills', './../../', "skill fl-l $opacity") .'
			<div class="fl-l tooltip s">
					<div><img class="point" src="./images/arrow_left.png" />
                        
					<h1>'.$_skill['name'].'</h1>
						<p>'.$_skill['desc'].'</p><p style="color:#32c9e9;">Mana Cost: '.(($cost+((isset($extra))?$extra:'0')< 0)?'0':$cost+((isset($extra))?$extra:'0')).'<br/> <span  style="color: #e63d3d;">Cooldown: '.$_skill['cooldown'].'</span>'.$classes['archive'].'</p>
					</div></div>'. $cooldown;
				$skillkey++;
            } 
        }
		$update = true;
		$json['prettyStatus'] = $this->prettyStatus($this->match['MATCH']['status']);
		$json['maxTime'] = ($system->data('Turn_Time') * 1000);
		$json['startTime'] = (($system->data('Turn_Time') - (time() - $this->match['TIME'])) * 1000);
		$json['healths'] = $healths;
		$json['manas'] = $mana;
		$json['team'] = $team;
	}
	
    function verifySkill() {
        global $system, $db, $account, $_POST, $json;
		$json['manacap'] = false;
		if($this->match['FIRST'] == false){
			$opponent = $this->first;
			$this->first = $this->second;
			$this->second = $opponent;
		}
        if (!isset($_POST['c']))
            return $json['result'] = false;
        $character = array_search($_POST['c'], $this->first['TEAM']['ID']);
        if ($this->first['TEAM']['HEALTH'][$character] === '0')
            return $json['result'] = false;
        $s = $db->query('SELECT * FROM skills WHERE id = "' . $_POST['s'] . '"');
        if ($s->rowCount() == 0)
            return $json['result'] = false;
        $s = $db->fetch('SELECT * FROM skills WHERE id = "' . $_POST['s'] . '"');
        
        // Find active and stun/invul
		if(!empty($this->match['MATCH']['active'])){
		if(strpos($this->match['MATCH']['active'], "/") == true)
			$active = explode('/', $this->match['MATCH']['active']);
		else
			$active = array($this->match['MATCH']['active']);
		foreach ($active as $key => $value) {
			$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $key), $what['turn']);
			}
        }}
		$cost = $this->first['TEAM']['SKILL'][$character][$_POST['s']];
		$extra = 0;
		if(isset($this->first["TEAM"]["STATUS"][$character]['increase-cost']))
			$extra = $this->first["TEAM"]["STATUS"][$character]['increase-cost'];
        if ($this->first['TEAM']['MANA'][$character] < ($cost+$extra))
            return $json['result'] = false;
		if ($s['requires'] != '0') {
            $requires = explode('|', $s['requires']);
            $done = 0;
			if(empty($this->match['MATCH']['active'])) goto noskill;
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			foreach ($active as $value) {
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				if($this->{$what['caster'][0]}['ACCOUNT']['id'] !== $account['id']) continue;
				if($this->{$what['caster'][0]}['TEAM']['ID'][$what['caster'][1]] !== $this->first['TEAM']['ID'][$what['caster'][1]]) continue;
				if(($llave = array_search(key($what['skill']),$requires)) !== false){
					unset($requires[$llave]);
				}
			}
			
			noskill:
			if (count($requires) !== 0)
                return $json['result'] = false;
        }
		if ($this->first['TEAM']['COOLDOWN'][$character][$character] !== '0') {
			if($this->first['TEAM']['COOLDOWN'][$character][$_POST['s']] == 'None' || empty($this->first['TEAM']['COOLDOWN'][$character][$_POST['s']]))
				$this->first['TEAM']['COOLDOWN'][$character][$_POST['s']] = '0';
			if($this->first['TEAM']['COOLDOWN'][$character][$_POST['s']] == 'Infinite')
				return $json['result'] = false;
			if($this->first['TEAM']['COOLDOWN'][$character][$_POST['s']] !== '0')
				return $json['result'] = false;
			
		}
		
		if ($this->checkStun($this->first['TEAM']['STATUS'][$character]['stunned'], $s['classes']) !== false)
			return $json['result'] = false;
		if (isset($this->first['TEAM']['STATUS'][$character]['disable'])) {
			$disable = $this->first['TEAM']['STATUS'][$character]['disable'];
			$disable = explode(',',$disable);
			$disabled = false;
			foreach($disable as $what){
				if(empty($what)) continue;
				if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $what)){
					$what = substr($what, strpos($what,'S')+1);
					if($what-1 != array_search($_POST['s'], array_keys($this->first['TEAM']['SKILL'][$character]))) continue;
					$disabled = true;
				}elseif(strpos($what, 'Offensive') !== false){
					if(strpos($s['targets'], 'E') !== false)
						$disabled = true;
					}elseif(strpos($what, 'Defensive') !== false){
						if(strpos($s['targets'], 'A') !== false)
							$disabled = true;
						if(strpos($s['targets'], 'S') !== false)
							$disabled = true;
					}elseif(strpos($what,'c')!== false){
						$classes = explode(',', $s['classes']);
						if(array_search(substr($what,1), $classes) !== false)
							$disabled = true;
					}else{
						$effects = explode(',',$s['effects']);
						foreach($effects as $e){
							$e = $db->fetch("SELECT * FROM effects WHERE id = '".$e."'");
							if(!empty($e[$what])){
								$disabled = true;
							}
						}
					}
			}
			if($disabled === true)
				return $json['result'] = false;
        } 
		
		$json['result'] = true;
		if($this->match['TURN'] == '1' || $this->match['TURN'] == '2'){
			$manaCap = ($this->match['TURN'] == '1') ? $system->data('First_Mana') : $system->data('Second_Mana');
			$json['manacap'] = false;
			if($manaCap < ($cost+$extra)){
				$json['manacap'] = true;
				$json['result'] = false;
			}
		}
        return $json;
    }

    function getTargets() {
        global $system, $db, $_POST, $json;
		if($this->match['FIRST'] == false){
			$opponent = $this->first;
			$this->first = $this->second;
			$this->second = $opponent;
		}
		if(!empty($this->match['MATCH']['active'])) {
		if(strpos($this->match['MATCH']['active'], "/") !== false)
			$active = explode('/', $this->match['MATCH']['active']);
		else
			$active = array($this->match['MATCH']['active']);
		foreach ($active as $key => $value) {
			$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
			foreach(reset($what['skill']) as $effect => $turnsleft){
				$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $key), $what['turn']);
			}
        }       }
        $character = array_search($_POST['c'], $this->first['TEAM']['ID']);
        $skill = $db->fetch('SELECT * FROM skills WHERE id = "' . $_POST['s'] . '"');
        $cost = $this->first['TEAM']['SKILL'][$character][$_POST['s']];
		$extra = 0;
		if(isset($this->first["TEAM"]["STATUS"][$character]['increase-cost']))
			$extra = $this->first["TEAM"]["STATUS"][$character]['increase-cost'];
		$cost = $cost+$extra;
        $targets = explode('|', $skill['targets']);
		foreach($this->first['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $character){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '0'.$m;
				}
			}
		}
		foreach($this->second['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $character){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '1'.$m;
				}
			}
		}
        
		if(isset($this->first["TEAM"]["STATUS"][$character]['targetme']) && strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][2],'not') === false)
			$targets = array($this->first["TEAM"]["STATUS"][$character]['targetme'][2]);
		$targeting = array();
		
		if(isset($this->first["TEAM"]["STATUS"][$character]['set-skill']) && strpos($this->first["TEAM"]["STATUS"][$character]['set-skill'], 'bypass') !== false)
			$skill['iinvul'] = '1';
		if(isset($this->first["TEAM"]["STATUS"][$character]['no-ignore']) && strpos($this->first["TEAM"]["STATUS"][$character]['no-ignore'], 'iinvul') !== false)
			$skill['iinvul'] = '0';
		foreach ($targets as $target) {
            switch ($target) {
				case "00";
				case "10";
				case "01";
				case "11";
				case "02";
				case "12";
					$m = substr($target,0,1);
					$k = substr($target,1);
					if ($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && empty($skill['dead']))
						break;
					if ($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && isset($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['no-resurrect']))
						break;
                    if ($this->checkInvulnerability($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
						break;
					$targeting[$m.$k] = true;
					break;
                case "E/1";
                case "E/r";
                case "E/a";
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
                            continue;
						if(isset($this->first["TEAM"]["STATUS"][$character]['targetme']) && strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][2],'not') !== false){
							if(strpos($this->first["TEAM"]["STATUS"][$character]['targetme'][3], 'all') === false){
								$specifics = explode(',',$this->first["TEAM"]["STATUS"][$character]['targetme'][3]);
								$true = false;
								$classes = explode(',',$skill['classes']);
								foreach($specifics as $s){
									if(array_search($s,$classes) !== false)
										$true = true;
								}
								if($true === false)
									goto skipnotclass;
							}
							if(substr($this->first["TEAM"]["STATUS"][$character]['targetme'][0], -1) == $key && $this->first["TEAM"]["STATUS"][$character]['targetme'][2] == 'notcaster')
								continue;
							elseif(substr($this->first["TEAM"]["STATUS"][$character]['targetme'][0], -1) != $key && $this->first["TEAM"]["STATUS"][$character]['targetme'][2] == 'notallys')
								continue;
							
							
						}
						skipnotclass:
                        $targeting['1' . $key] = true;
                    }
                    break;
                case "A/1";
                case "A/r";
                case "A/a";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($key == $character)
                            continue;
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    break;
                case "S":
                    $targeting['0' . $character] = true;
                    break;
                case "O/r";
                case "O";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
                            continue;
                        $targeting['1' . $key] = true;
                    }
                    break;
            }
        }
        return $json['result'] = $targeting;
    }

    function checkTarget() {
        global $system, $db, $_POST, $json;
		if($this->match['FIRST'] == false){
			$opponent = $this->first;
			$this->first = $this->second;
			$this->second = $opponent;
		}
		if(!empty($this->match['MATCH']['active'])) {
			if(strpos($this->match['MATCH']['active'], "/") == true)
				$active = explode('/', $this->match['MATCH']['active']);
			else
				$active = array($this->match['MATCH']['active']);
			foreach ($active as $key=>$value) {
				$what = ($this->match['FIRST'] == true) ? $this->makemeunderstand($value) : $this->makemeunderstand($value,1);
				foreach(reset($what['skill']) as $effect => $turnsleft){
					$this->doEffect('Status', array($what['target'], $what['caster'], $effect, $turnsleft, key($what['skill']), $key), $what['turn']);
				}
			}       
		}
        $caster = array_search($_POST['c'], $this->first['TEAM']['ID']);
        $skill = $db->fetch('SELECT * FROM skills WHERE id = ' . $_POST['s'] . '');
        $cost = $this->first['TEAM']['SKILL'][$caster][$_POST['s']];
		$extra = 0;
		if(isset($this->first["TEAM"]["STATUS"][$caster]['increase-cost']))
			$extra = $this->first["TEAM"]["STATUS"][$caster]['increase-cost'];
		$cost = $cost+$extra;
        $defined = $_POST['d'];
        $posible = array(
            'slot A' => '00',
            'slot B' => '01',
            'slot C' => '02',
            'eslot A' => 10,
            'eslot B' => 11,
            'eslot C' => 12,
            10 => 0,
            11 => 1,
            12 => 2,
        );
        $defined = $posible[$defined];
        $targets = explode('|', $skill['targets']);
		foreach($this->first['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $caster){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '0'.$m;
				}
			}
		}
		foreach($this->second['TEAM']['STATUS'] as $m => $t){
			if(isset($t['target'])){
				$k = substr($t['target'][0], strlen($t['target'][0])-1);
				$it = substr($t['target'][0], 0, strlen($t['target'][0])-1);
				if($this->{$it} === $this->first && $k == $caster){
					if(strpos($t['target'][2], $skill['id']) === false && strpos($t['target'][2],'all') === false) continue;
					if($skill['id'] != $t['target'][1])
						$targets[] = '1'.$m;
				}
			}
		}
		if(isset($this->first["TEAM"]["STATUS"][$caster]['targetme']) && strpos($this->first["TEAM"]["STATUS"][$caster]['targetme'][2],'not') === false)
			$targets = array($this->first["TEAM"]["STATUS"][$caster]['targetme'][2]);
		if(isset($this->first["TEAM"]["STATUS"][$caster]['set-skill']) && strpos($this->first["TEAM"]["STATUS"][$caster]['set-skill'], 'bypass') !== false)
			$skill['iinvul'] = '1';
		if(isset($this->first["TEAM"]["STATUS"][$caster]['no-ignore']) && strpos($this->first["TEAM"]["STATUS"][$caster]['no-ignore'], 'iinvul') !== false)
			$skill['iinvul'] = '0';
        $targeting = array();
		
		foreach ($targets as $target) {
            switch ($target) {
				case "00";
				case "10";
				case "01";
				case "11";
				case "02";
				case "12";
					$m = substr($target,0,1);
					$k = substr($target,1);
					if ($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && empty($skill['dead']))
						break;
					if($this->{(($m == '1') ? 'second':'first')}['TEAM']['HEALTH'][$k] == 0 && isset($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['no-resurrect']))
						break;
					if ($this->checkInvulnerability($this->{(($m == '1') ? 'second':'first')}['TEAM']['STATUS'][$k]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
						break;
					$targeting[$m.$k] = true;
					break;
                case "E/1":
                    if($this->checkInvulnerability($this->second['TEAM']['STATUS'][$posible[$defined]]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
						break;
					if(strpos($this->first["TEAM"]["STATUS"][$caster]['targetme'][3], 'all') === false){
						$specifics = explode(',',$this->first["TEAM"]["STATUS"][$caster]['targetme'][3]);
						$true = false;
						$classes = explode(',',$skill['classes']);
						foreach($specifics as $s){
							if(array_search($s,$classes) !== false)
								$true = true;
						}
						if($true === false)
							goto skipnotclass;
					}
					if(isset($this->first["TEAM"]["STATUS"][$caster]['targetme']) && $this->first["TEAM"]["STATUS"][$caster]['targetme'][2] == 'notcaster'){
						if($posible[$defined] == substr($this->first["TEAM"]["STATUS"][$caster]['targetme'][0],-1))
							break;
					}elseif(isset($this->first["TEAM"]["STATUS"][$caster]['targetme']) && $this->first["TEAM"]["STATUS"][$caster]['targetme'][2] == 'notallys'){
						if($posible[$defined] != substr($this->first["TEAM"]["STATUS"][$caster]['targetme'][0],-1))
							break;
					}
					skipnotclass:
					if ($defined >= 10) 
                        $targeting[$defined] = true;
                    break;
                case "E/r";
                case "E/a";
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes']) !== false && $skill['iinvul'] !== '1')
                            continue;
						if(isset($this->first["TEAM"]["STATUS"][$caster]['targetme']) && $this->first["TEAM"]["STATUS"][$caster]['targetme'][2] = 'notcaster'){
							if($posible[$defined] == substr($this->first["TEAM"]["STATUS"][$caster]['targetme'][0],-1))
								break;
						}elseif(isset($this->first["TEAM"]["STATUS"][$caster]['targetme']) && $this->first["TEAM"]["STATUS"][$caster]['targetme'][2] = 'notallys'){
							if($posible[$defined] != substr($this->first["TEAM"]["STATUS"][$caster]['targetme'][0],-1))
								break;
						}
                        $targeting['1' . $key] = true;
                    }
                    break;
                case "A/1";
                    if ($defined <= 02)
                        $targeting[$defined] = true;
                    break;
                case "A/r";
                case "A/a";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($key == $caster)
                            continue;
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    break;
                case "S":
                    $targeting['0' . $caster] = true;
                    break;
                case "O/r";
                case "O";
                    foreach ($this->first['TEAM']['ID'] as $key => $person) {
                        if ($this->first['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->first['TEAM']['HEALTH'][$key] == 0 && isset($this->first['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        $targeting['0' . $key] = true;
                    }
                    foreach ($this->second['TEAM']['ID'] as $key => $person) {
                        if ($this->second['TEAM']['HEALTH'][$key] == 0 && empty($skill['dead']))
                            continue;
						if($this->second['TEAM']['HEALTH'][$key] == 0 && isset($this->second['TEAM']['STATUS'][$key]['no-resurrect']))
							continue;
                        if ($this->checkInvulnerability($this->second['TEAM']['STATUS'][$key]['invulnerability'], $skill['classes'])  !== false && $skill['iinvul'] !== '1')
                            continue;
                        $targeting['1' . $key] = true;
                    }
                    break;
            }
        }
		
        $json['width'] = round((($this->first['TEAM']['MANA'][$caster] - $cost) / $db->fieldFetch('characters', $this->first['TEAM']['ID'][$caster], 'mana')) * 100);
		if(0 > $cost) 
			$json['width'] = $this->first['TEAM']['MANA'][$caster];
		$json['mana'] = $this->first['TEAM']['MANA'][$caster] - $cost;
        $json['character'] = $this->first['TEAM']['MANA'][$caster];
        if ($json['mana'] < 0)
            $json['mana'] = 0;
        $json['result'] = $targeting;
        return $json;
    }

}

class Functions extends GAjax {
	
	function checkStun($status, $_skill){
		if(empty($status)) return false;
		$disable = $status;
		$disable = explode(',',$disable);
		$disabled = false;
		foreach($disable as $what){
			if(empty($what)) continue;
			if($what == 'all'){
				$disabled = true;
				break;
			}
			if(strpos($_skill, $what) !== false){
				$disabled = true;
				break;
			}
		}
		return $disabled;
	}
	function checkInvulnerability($status, $_skill){
		if(empty($status)) return false;
		if(empty($_skill)) return true;
		$disable = $status;
		$disable = explode(',',$disable);
		$disabled = false;
		foreach($disable as $what){
			if(empty($what)) continue;
			if($what == 'all'){
				$disabled = true;
				break;
			}
			if(strpos($_skill, $what) !== false){
				$disabled = true;
				break;
			}
		}
		return $disabled;
	}
	
	function getMusic(){
		$return = '<audio id="background" loop preload="auto" style="display:none;">
    <source src="./tpl/default/sound/background.mp3" />
</audio>';
	$return .= '<audio id="start" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/start.mp3" />
</audio>';
		$return .= '<audio id="click" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/click.mp3" />
</audio>';
		$return .= '<audio id="choosen" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/choosen.mp3" />
</audio>';
		$return .= '<audio id="end-turn" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/end.mp3" />
</audio>';
		$return .= '<audio id="lose" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/lose.mp3" />
</audio>';
		$return .= '<audio id="win" preload="auto" style="display:none;">
    <source src="./tpl/default/sound/win.mp3" />
</audio>';
		return $return;
	}

    function _get($who, $_, $__ = false) {
        $field = $this->match["MATCH"][$_ . '-' . $who];
        $x = explode('|', $field);
        foreach ($x as $key => $value) {
            if (stripos($value, ';') !== false && $_ == 't' && $__ === false) {
                $pos = stripos($value, ';');
                $return[] = substr($value, 0, $pos);
                continue;
            }
            if (stripos($value, ';') !== false && $_ == 't') {
                $pos = stripos($value, ';');
                $skills = substr($value, $pos + 1);
                $skills = explode(',', $skills);
                foreach ($skills as $w => $skill) {
					if($__ == '1')
						$return[$key][substr($skill, 0, strpos($skill, ':'))] = substr($skill, strpos($skill, ':') + 1);
					else
						$return[$key][substr($skill, 0, strpos($skill, ':'))] = substr($skill, strpos($skill, ':') + 1);
                }
                continue;
            }
			if($_ == 'c'){
                $skills = explode(',', $value);
                foreach ($skills as $w => $skill) {
					$return[$key][substr($skill, 0, strpos($skill, ':'))] = substr($skill, strpos($skill, ':') + 1);
                }
                continue;
			}
            $return[] = $value;
        }
        return $return;
    }

    function _implode() {

        $this->first['TEAM']['HEALTH'] = implode('|', $this->first['TEAM']['HEALTH']);
        $this->first['TEAM']['MANA'] = implode('|', $this->first['TEAM']['MANA']);
        $this->second['TEAM']['HEALTH'] = implode('|', $this->second['TEAM']['HEALTH']);
        $this->second['TEAM']['MANA'] = implode('|', $this->second['TEAM']['MANA']);
		$return = '';
		$cooldowns = '';
		foreach($this->first['TEAM']['ID'] as $key => $character){ 
			if(!empty($return))
				$return .= '|';
			$return .= $character.';';
			$add = '';
			foreach($this->first['TEAM']['SKILL'][$key] as $skill => $cost){
				if(!empty($add)){
				$return .= ',';}
				$return .= $skill.':'.$cost;
				$add = '1';
			}
			if(!empty($cooldowns))
				$cooldowns .= '|';
			foreach($this->first['TEAM']['COOLDOWN'][$key] as $skill => $cooldown){
				if(empty($skill)) continue;
				if(substr($cooldowns, -1) !== '|' && !empty($cooldowns)){
				$cooldowns .= ',';}
				$cooldowns .= $skill.':'.$cooldown;
				$add = '1';
			}
		}
		$this->first['TEAM']['SKILL'] = $return;
		$this->first['TEAM']['COOLDOWN'] = $cooldowns;
		$return = '';
		$cooldowns = '';
		foreach($this->second['TEAM']['ID'] as $key => $character){ 
			if(!empty($return))
				$return .= '|';
			$return .= $character.';';
			$add = '';
			foreach($this->second['TEAM']['SKILL'][$key] as $skill => $cost){
				if(!empty($add)){
				$return .= ',';}
				$return .= $skill.':'.$cost;
				$add = '1';
			}
			if(!empty($cooldowns))
				$cooldowns .= '|';
			foreach($this->second['TEAM']['COOLDOWN'][$key] as $skill => $cooldown){
				if(empty($skill)) continue;
				if(substr($cooldowns, -1) !== '|' && !empty($cooldowns)){
					$cooldowns .= ',';
				}
				$cooldowns .= $skill.':'.$cooldown;
			}
		}
		$this->second['TEAM']['SKILL'] = $return;
		$this->second['TEAM']['COOLDOWN'] = $cooldowns;
		
	}

    function prettyStatus($status) {
        global $db, $system;
		$status = ($status === 'playerTurn' && $this->match['ME'] !== true) ? 'opponentTurn' : $this->match['MATCH']['status'];
        switch ($status) {
            case 'playerTurn' :
                return ($system->data('Turn_Time') - (time() - $this->match['TIME'])) . ' seconds left';
                break;
            case 'opponentTurn' :
                return 'Waiting for ' . $db->fieldFetch('accounts', $this->second['ACCOUNT']['id'], 'name');
                break;
            case 'calculating' :
                return 'Calculating....';
                break;
            case 'checking' :
                return 'Checking for opponent....';
                break;
			case 'loser' :
				return (($this->match['FIRST']===false) ? 'You have won!' : 'You have lost!');
				break;
			case 'winner' :
				return (($this->match['FIRST']===false) ? 'You have lost!' : 'You have won!');
				break;
            default:
                return 'Undefined';
        }
    }

// ADVANCED FUNCTIONS //
    function doEffect($function, $arguments, $casted = 0) {

        switch ($function) {
            case 'Status':
                $success = $this->ProcessEffect($arguments, 1, $casted);
                return $success;
                break;
            case 'Process':
                $success = $this->ProcessEffect($arguments, 0, $casted);
                return $success;
                break;
            case 'Description':
                $success = $this->ReturnDescription($arguments, $casted);
                return $success;
                break;
            default:
                break;
        }
    }

    function ProcessEffect(&$arguments, $_ = 0, $casted = 0) {
        
		global $db, $system, $game, $account;
        
        $success = false;
		// Only status effects
		// Check if the effect has a value casted
	
		$_value = '';
		if(strpos($arguments[3],'*') !== false){
			$_value = substr($arguments[3],strpos($arguments[3],'*')+1);
			$arguments[3] = substr($arguments[3],0,strpos($arguments[3],'*'));
		}
		
		$data = $db->query("SELECT * FROM effects WHERE id = '" . $arguments[2] . "'");
        if ($data->rowCount() === 0)
            return;
		$data = $data->fetch();
		
		if(!empty($data['following'])){
			if($data['following'] !== 0){
			if((($this->match['TURN']-$casted)/2) < $data['following'])
				return true;
			}
		}
		
		if(!empty($data['condition'])){
			$who = $arguments[0];
			if(!empty($data['self']))
				$who = $arguments[1];
			$conditions = explode(',',$data['condition']);
			$conditioned = 0;
			foreach($conditions as $condition){
				if(empty($condition)) continue;
				if(strpos($condition,'H') !== false){
					if(strpos($condition,'>') !== false){
						if($this->{$who[0]}["TEAM"]["HEALTH"][$who[1]] >= (substr($condition,strpos($condition,'>')+1)))
							$conditioned++;
					}elseif(strpos($condition,'<') !== false){
						if($this->{$who[0]}["TEAM"]["HEALTH"][$who[1]] <= (substr($condition,strpos($condition,'<')+1)))
							$conditioned++;
					}else{
						if($this->{$who[0]}["TEAM"]["HEALTH"][$who[1]] == (substr($condition,strpos($condition,'H')+1)))
							$conditioned++;
					}
				}
				if(strpos($condition,'M') !== false){
					if(strpos($condition,'>') !== false){
						if($this->{$who[0]}["TEAM"]["MANA"][$who[1]] >= (substr($condition,strpos($condition,'>')+1)))
							$conditioned++;
					}elseif(strpos($condition,'<') !== false){
						if($this->{$who[0]}["TEAM"]["MANA"][$who[1]] <= (substr($condition,strpos($condition,'<')+1)))
							$conditioned++;
					}else{
						if($this->{$who[0]}["TEAM"]["MANA"][$who[1]] == (substr($condition,strpos($condition,'M')+1)))
							$conditioned++;
					}
				}
				/*if(strpos($condition,'C') !== false){
					$needthesec = substr($condition,1);
					$if($this->{$who[0]}["TEAM"]["ID"][$who[1]] == $needthesec){
						$conditioned++;
					}
				}*/
				if(strpos($condition,'S') !== false){
					$needtheses = substr($condition,1);
					$truethat = false;
					$counter = 0;
					$times = 0;
					if(strpos($needtheses,'*')!==false){
						$times = substr($needtheses,strpos($needtheses,'*')+1);
						$needtheses = substr($needtheses,0,strpos($needtheses,'*'));
					}
					$active = explode('/', $this->match['MATCH']['active']);
					foreach ($active as $value){
						if($truethat === true)
							break;
						$what =  $this->makemeunderstand($value);
						$me = false;
						if($what['target'][1] == $who[1] && $what['target'][0] == $who[0])
							$me = true;
						if($what['caster'][1] == $who[1] && $what['caster'][0] == $who[0])
							$me = true;
						if($me === false)
							continue;
						if($needtheses != array_keys($what['skill'])[0]) 
							continue;
						if($what['turn'] == $this->match['TURN'])
							continue;
						if($times == 0){
							$conditioned++;
							$truethat = true;
						}else{
							$counter++;
						}
					}
					if($times > 0){
						if($counter >= $times)
							$conditioned++;
					}
					
				}
				if(strpos($condition, 'A') !== false){
					$what = (float)substr($condition, 1);
					$counter = 3;
					foreach($this->{$who[0]}['TEAM']['ID'] as $key => $character){
						if($this->{$who[0]}['TEAM']['HEALTH'][$key] == 0)
							--$counter;
					}
					if($what == $counter)
						$conditioned++;
				}
				if(strpos($condition, 'E') !== false){
					$what = (float)substr($condition, 1);
					$counter = 3;
					if($who[0] == 'first')
						$who[0] = 'second';
					foreach($this->{$who[0]}['TEAM']['ID'] as $key => $character){
						if($this->{$who[0]}['TEAM']['HEALTH'][$key] == 0)
							--$counter;
					}
					if($what == $counter)
						$conditioned++;
				}
				if($condition == 'Stunned'){
					
					if(isset($this->{$who[0]}['TEAM']['STATUS'][$who[1]]['stunned'])){
						$conditioned++;
					}
				}
                if($condition == 'Killed'){
                  if($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] == 0){
						$conditioned++;
                  }
					
				}
                if($condition == 'Disabled'){ // does it even work ?
					
					if(isset($this->{$who[0]}['TEAM']['STATUS'][$who[1]]['disable'])){
						$conditioned++;
					}
				}
				if($condition == 'Invulnerable'){
					if(isset($this->{$who[0]}['TEAM']['STATUS'][$who[1]]['invulnerability'])){
						$conditioned++;
					}
				}
			}
			if(abs(count($conditions)-$conditioned) !== 0)
				return true;
		}
		
		if(!empty($data['remove']) && $_ == 0){
			if($data['remove'] == 'beneficial')
				return 'remove-beneficial';
			elseif($data['remove'] == 'beneficial-status')
				return 'remove-beneficial-status';
			elseif($data['remove'] == 'harmful')
				return 'remove-harmful';
			elseif($data['remove'] == 'harmful-status')
				return 'remove-harmful-status';
			
			elseif(strpos($data['remove'], 'c+')!==false)
				return 'remove-'.substr($data['remove'],1).'-caster';
			elseif(strpos($data['remove'], 't+')!==false)
				return 'remove-'.substr($data['remove'],1).'-target';
			elseif(strpos($data['remove'], 'c')!==false)
				return 'remove-'.substr($data['remove'],1).'-caster';
			elseif(strpos($data['remove'], 't')!==false)
				return 'remove-'.substr($data['remove'],1).'-target';
			else
				return 'remove-all';
		}
		
		foreach($data as $effect=>$value){
			
			if(empty($value)) continue;
			
			if($_ == 1 && array_search($effect, $this->effects['STATUS']) === false) continue;
			
			if(is_int($effect) || $effect == 'duration' || $effect == 'description' || $effect == 'target') continue;
			//echo 'I make it here';
			if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'])){
				if(strpos($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'], 'allenemy') !== false){
					if($arguments[0][0] !== $arguments[1][0])
						if($effect !== 'replace')
							return $success = true;
				}
				if(strpos($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'], 'allally') !== false){
					if($arguments[0][0] == $arguments[1][0])
						if($effect !== 'replace')
							return $success = true;
				}
				if(strpos($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'],$effect) !== false){
					if($effect !== 'replace')
						return $success = true;
				}
				
			}
			//echo 'I do too it here';
			switch ($effect) {
                case 'reverseTargetToCaster':
                    $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reverseTargetToCaster'] = true;
                    $success = true;
                break;
				case 'reset':
					if($value !== 'all')
						$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$value] = 0;
					else{
						foreach($this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]] as $it => $willbe){
							$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$it] = 0;
						}
					}
					$success = true;
				break;
				case 'if':
					if($_ == 0) break;
					$not = 'e'.(($arguments[1][0] == 'first')?0:1).$arguments[1][1];
					if(!empty($data['not']) && $data['not'] == '1')
						$not='n'.(($arguments[1][0] == 'first')?0:1).$arguments[1][1];
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][0] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][0] .= $value.$not;
					if($data['ally'] == '1' && $arguments[1][0] == $arguments[0][0])
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][0] .= 'ally';
					$who = (!empty($data['self'])?(($data['self'] == '1')?'s':'sc'):'');
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][0] .= $who;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][1]))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][1] .= '/';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][1] .= (empty($data['specify'])?'all':$data['specify']);
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][2]))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][2] .= '/';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['if'][2] .= (empty($data['count'])?'1':$data['count']);
					$success = true;
				break;
				case 'dd':
					if($_ == 0) break;
					if(empty($_value) && empty($data['renew'])){
						$success = false;
						break;
					}
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['destroy-dd'])){
						$success = true;
						break;
					}
					if(!empty($data['renew']))
						$_value = $value;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]]))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]][0] += $_value;
					else{
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]][0] = $_value;
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]][1] = $arguments[2];
						if(!empty($data['renew']))
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]]['renew'] = true;
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$arguments[5]]['renew'] = false;
					}
					$success = true;
				break;
				case 'destroy-dd':
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['destroy-dd'] = true;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd']))
						unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd']);
					$success = true;
				case 'dr':
					if($_ == 0) break;
					if(strpos($value, 'f') !== false){
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']))
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] += substr($value, 0, strpos($value, 'f'));
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] = substr($value, 0, strpos($value, 'f'));
					}else{
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] += $value;
					else
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] = $value;
					}
					if(!empty($data['unpiercable']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']['unpiercable'] = 'true';
					$success = true;
				break;
				case 'increase':
					if($_ == 0) break;
					if(!isset($data['increaseby'])) break;
					if($value == 'all'){
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase']['all']))
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase']['all'] += $data['increaseby'];
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase']['all'] = $data['increaseby'];
/* 					foreach($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] as $skill => $cost){
						
						
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$skill])){
							if(strpos($data['increaseby'],'*') !== false)
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$skill] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$skill]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
							else
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$skill] += $data['increaseby'];
						}else{
							if(strpos($data['increaseby'],'*') !== false)
								$data['increaseby'] = 0;
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$skill] = $data['increaseby'];
						}
					} */
						
					}else{
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$value])){
							if(strpos($data['increaseby'],'*') !== false)
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$value] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$value]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
							else
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$value] += $data['increaseby'];
						}else{
							if(strpos($data['increaseby'],'*') !== false)
								$data['increaseby'] = 0;
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase'][$value] = $data['increaseby'];
						}
					}
					$success = true;
				break;
				case 'increase-duration':
					if(!isset($data['increaseby'])) continue;
					if($value == 'all'){
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration']['all'])){
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration']['all'] += $data['increaseby'];
						}else{
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration']['all'] = $data['increaseby'];
						}
					}else{
						if(strpos($value,'S')){
							$value = substr($value,1);
							if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration'][$value])){
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration'][$value] += $data['increaseby'];
							}else{
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-duration'][$value] = $data['increaseby'];
							}
						}
					}
					$success = true;
				break;
				case 'increase-heal':
					if($_ == 0) break;
					if(!isset($data['increaseby'])) continue;
					$skill = $value; 
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-heal'][$skill])){
							if(strpos($data['increaseby'],'*') !== false)
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-heal'][$skill] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-heal'][$skill]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
							else
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-heal'][$skill] += $data['increaseby'];
					}else{
							if(strpos($data['increaseby'],'*') !== false)
								$data['increaseby'] = 0;
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-heal'][$skill] = $data['increaseby'];
						}
					$success = true;
				break;
				case 'increase-mana':
					if($_ == 0) break;
					if(!isset($data['increaseby'])) continue;
					$skill = $value; 
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$skill])){
							if(strpos($data['increaseby'],'*') !== false)
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$skill] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$skill]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
							else
								$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$skill] += $data['increaseby'];
					}else{
							if(strpos($data['increaseby'],'*') !== false)
								$data['increaseby'] = 0;
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$skill] = $data['increaseby'];
						}
					$success = true;
				break;
            
                case 'increase-manaRem':
					if($_ == 0) break;
					if(!isset($data['increaseby'])) continue;
                    $skill = $value; 
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-manaRem'][$skill])){
						if(strpos($data['increaseby'],'*') !== false)
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-manaRem'][$skill] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-manaRem'][$skill]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-manaRem'][$skill] += $data['increaseby'];
					}else{
						if(strpos($data['increaseby'],'*') !== false)
							$data['increaseby'] = 0;
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-manaRem'][$skill] += $data['increaseby'];
					}
					$success = true;
				break;
            
            
				case 'deal':
					if($_ == 0) break;
					if(!isset($data['increaseby'])) continue;
					$skill = $value; 
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$skill])){
						if(strpos($data['increaseby'],'*') !== false)
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$skill] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$skill]*(substr($data['increaseby'],strpos($data['increaseby'],'*')+1));
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$skill] += $data['increaseby'];
					}else{
						if(strpos($data['increaseby'],'*') !== false)
							$data['increaseby'] = 0;
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$skill] += $data['increaseby'];
					}
					$success = true;
				break;
				case 'no-death':
					 if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reverseTargetToCaster']){
                    $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]][] = 'no-death';
                    $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reverseTargetToCaster'] = false;
                    $success = true;
                    }
                    else{
                    $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]][] = 'no-death';
                    $success = true;
                    }
				break;
				case 'no-resurrect':
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['no-resurrect'] = true;
					$success = true;
				break;
				case 'ignore':
					if(!empty($data['count']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['count'] = $data['count'];
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'] .= $value;
					if($value == 'dr'){
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']))
							unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']);
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']))
							unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']);
					}
					if($value == 'stun'){
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['stunned']))
							unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['stunned']);
					}
					$success = true;
				break;
				case 'no-ignore':
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['no-ignore']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['no-ignore'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['no-ignore'] .= $value;
					$success = true;
				break;
				case 'set-skill':
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['set-skill']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['set-skill'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['set-skill'] .= $value;
					$success = true;
				break;
				case 'increase-cooldown':
					
					if(strpos($value, 'S')!== false){
						if($_ == 1) break;
						$specific = substr($value,0, strpos($value,'S'));
						$value = substr($value,strpos($value,'S')+1);
							foreach ($this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]] as $skill => $cost) {
								if($specific != $skill) continue;
                            
								if($cost == 'None')
									$cost = 0;
								if(strpos($value, '+') !== false)
									$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$skill] = $cost+$value;
								elseif(strpos($value, '-') !== false){
									$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$skill] = $cost-(substr($value,1));
								}else
									$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$skill] = $value;
								if((float)$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$skill] <= 0){
									$this->{$arguments[0][0]}["TEAM"]["COOLDOWN"][$arguments[0][1]][$skill] = '0';
								}
								
							}
					}else{
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cooldown']['all']))
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cooldown']['all'] += $value;
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cooldown']['all'] = $value;
						
					}
					$success = true;
				break;
				
				case 'increase-cost':
				
					if(strpos($value, 'S') !== false){
						$specific = substr($value,0, strpos($value,'S'));
						$value = substr($value,strpos($value,'S')+1);
						foreach ($this->{$arguments[0][0]}['TEAM']['SKILL'][$arguments[0][1]] as $skill => $cost) {
							if($specific !=	$skill&&$specific != 'a') continue;
							$this->{$arguments[0][0]}["TEAM"]['SKILL'][$arguments[0][1]][$skill] = $value;
						}
					}else{
						if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cost']))
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cost'] += $value;
						else
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-cost'] = $value;
					}
					$success = true;
				break;
				
				case 'increase-affliction':
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]][] = 'increase-affliction';
					$success = true;
				break;
				
				case 'stun':
					if(empty($value)) break;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['stunned']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['stunned'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['stunned'] .= $value;
					$success = true;
                break;
				case 'transform':
					if(empty($value)) break;
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['transform'] = $value;
					$success = true;
                break;
				case 'fear':
					if(empty($value)) break;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['feared']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['feared'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['feared'] .= $value;
					$success = true;
                break;
				case 'disable':
					$disable = $value;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['disable']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['disable'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['disable'] .= $disable;
					$success = true;
                break;
				
				case 'invul':
					if(empty($value)) break;
					//echoecho $data['duration'].$arguments[3];
					if(is_numeric($data['duration']) && $arguments[3] < 0) 
						if($_ == 1)
							break;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability']))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'] .= $value;
					$success = true;
                break;
				
				case 'manaGain':
                if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
                    $success = true;
					break;
                }
                $manaGain = $value;
				if (stripos($manaGain, '%') !== false) {
					$totalmana = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]];
					if(strpos($manaGain, 'b') !== false){
						$totalmana = $db->fieldFetch('characters',$this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]],'mana');
						$manaGain = substr($manaGain,0,strpos($manaGain,'b'));
					}
                    $manaGain = round($totalmana * $game->percentToDecimal($manaGain));
                }
                if (stripos($manaGain, 'i') !== false) {
					$manaGain = 100;
                }
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana']['all']))
					$manaGain += $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana']['all'];
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$arguments[4]]))
					$manaGain += $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['increase-mana'][$arguments[4]];
				$who = $arguments[0];
				if(!empty($data['ally']))
					$who = $arguments[1];
				$this->{$who[0]}["TEAM"]["MANA"][$who[1]] += $manaGain;
				if($this->{$who[0]}["TEAM"]["MANA"][$who[1]] > $db->fieldFetch('characters', $this->{$who[0]}["TEAM"]["ID"][$who[1]], 'mana')) 
					$this->{$who[0]}["TEAM"]["MANA"][$who[1]] = $db->fieldFetch('characters', $this->{$who[0]}["TEAM"]["ID"][$who[1]], 'mana');
                $success = true;
                break;
               /* case 'externalManaGain':
                for(id=1;id<=400;id++)
                $db->query("UPDATE characters SET 'externalMana' = 'externalMana' + $value WHERE id='".$character['id']."'");
                break;*/
       /*     case 'externalManaGain':    // not made by marcos
                if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
                    $success = true;
					break;
                }
                $externalManaGain = $value;
				if (stripos($externalManaGain, '%') !== false) {
					$totalmana = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]];
					if(strpos($externalManaGain, 'b') !== false){
						$totalexternalManaGain = $db->fieldFetch('characters',$this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]],'externalMana');
						$externalManaGain = substr($externalManaGain,0,strpos($externalManaGain,'b'));
					}
                    $externalManaGain = round($totalexternalManaGain * $game->percentToDecimal($externalManaGain));
                }
                if (stripos($externalManaGain, 'i') !== false) {
					$externalManaGain = 100;
                }
				$who = $arguments[0];
				if(!empty($data['ally']))
					$who = $arguments[1];
				$this->{$who[0]}["TEAM"]["MANA"][$who[1]] += $externalManaGain;
				if($this->{$who[0]}["TEAM"]["MANA"][$who[1]] > $db->fieldFetch('characters', $this->{$who[0]}["TEAM"]["ID"][$who[1]], 'externalMana')) 
					$this->{$who[0]}["TEAM"]["MANA"][$who[1]] = $db->fieldFetch('characters', $this->{$who[0]}["TEAM"]["ID"][$who[1]], 'externalMana');
                $success = true;
                break;
                */
				
				case 'drainM' :
                if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
                    $success = true;
					break;
                }
				if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
                    $success = true;
					break;
                }
                $manaGain = $value;
				if (stripos($manaGain, '%') !== false) {
					$totalmana = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]];
					if(strpos($manaGain, 'b') !== false){
						$totalmana = $db->fieldFetch('characters',$this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]],'mana');
						$manaGain = substr($manaGain,0,strpos($manaGain,'b'));
					}
                    $manaGain = round($totalmana * $game->percentToDecimal($manaGain));
                }
                if (stripos($manaGain, 'i') !== false) {
					$manaGain = 100;
                }
				// If the caster gains more mana ->
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-mana']['all']))
					$manaGain += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-mana']['all'];
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-mana'][$arguments[4]]))
					$manaGain += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-mana'][$arguments[4]];
				$total = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]]-($this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]]-$manaGain);
				if(($this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]]-$manaGain) < 0)
					$total = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]];
				$this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]]-$total;
				if($this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] < 0)
					$this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] = 0;
				$this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] = $this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]]+$total;
				if($this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] > $db->fieldFetch('characters', $this->{$arguments[1][0]}["TEAM"]["ID"][$arguments[1][1]], 'mana'))
					$this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] = $db->fieldFetch('characters', $this->{$arguments[1][0]}["TEAM"]["ID"][$arguments[1][1]], 'mana');
                $success = true;
				break;
				
				case 'drainH' :
                if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
                    $success = true;
					break;
                }
				if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
                    $success = true;
					break;
                }
                $manaGain = $value;
				if (stripos($manaGain, '%') !== false) {
                    $manaGain = round($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] * $game->percentToDecimal($manaGain));
                }
                if (stripos($manaGain, 'i') !== false) {
					$manaGain = $db->fieldFetch('characters', $this->{$arguments[1][0]}["TEAM"]["ID"][$arguments[1][1]], 'health');
                }
				$total = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-$manaGain);
				if(($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-$manaGain) < 0)
					$total = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]];
				$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-$total;
				if($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0)
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
				$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = $this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]]+$total;
				if($this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] > $db->fieldFetch('characters', $this->{$arguments[1][0]}["TEAM"]["ID"][$arguments[1][1]], 'health'))
					$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = $db->fieldFetch('characters', $this->{$arguments[1][0]}["TEAM"]["ID"][$arguments[1][1]], 'health');
                $success = true;
				break;
				case 'convertM':
					if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
						$success = true;
						break;
					}
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
					$damage = $value;
					if (stripos($damage, '%') !== false) {
						$damage = $damage-round($this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] * $game->percentToDecimal($damage));
					}
					if (stripos($damage, 'i') !== false) {
						$damage = $this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]];
					}
					// Take away from the caster
					$this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] = $this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]]-$damage;
					if($this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] < 0){
						$damage = $this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]];
						$this->{$arguments[1][0]}["TEAM"]["MANA"][$arguments[1][1]] = 0;
					}
					if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
						$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
					if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all']))
						$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all'];
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'])){
						$damage = $damage-(round($damage * $game->percentToDecimal($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0].'%')));
						if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] == 100)
							$damage = 0;
					}
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'])){
						$damage = $damage-$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0];
					}
					if($damage < 0)
						$damage = 0;
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-$damage;
					if($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0) 
						$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
					$success = true;
                break;
				
				case 'convertH':
					if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
						$success = true;
						break;
					}
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
					$damage = $value;
					if (stripos($damage, '%') !== false) {
						$damage = $damage-round($this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] * $game->percentToDecimal($damage));
					}
					if (stripos($damage, 'i') !== false) {
						$damage = $this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]];
					}
					// Take away from the caster
					$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = $this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]]-$damage;
					if($this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] < 0){
						$damage = $this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]];
						$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = 0;
						
					}
					if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
						$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
					if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all']))
						$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all'];
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'])){
						$damage = $damage-(round($damage * $game->percentToDecimal($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0].'%')));
					if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] == 100)
						$damage = 0;
					}
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'])){
						$damage = $damage-$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0];
					}
					if($damage < 0)
						$damage = 0;
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]-$damage;
					if($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0) 
						$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
					$success = true;
                break;
				
				case 'removeM':
				if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
					$success = true;
					break;
				}
                $manaGain = $value;
				if (stripos($manaGain, '%') !== false) {
                    $manaGain = round($this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] * $game->percentToDecimal($manaGain));
                }
                if (stripos($manaGain, 'i') !== false) {
					$manaGain = $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]], 'mana');
                }
                if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-manaRem'][$arguments[4]])) $manaGain += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-manaRem'][$arguments[4]];
                if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-manaRem']['all'])) $manaGain += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-manaRem']['all'];
				
                $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]]-$manaGain;
				if($this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] < 0) 
					$this->{$arguments[0][0]}["TEAM"]["MANA"][$arguments[0][1]] = 0;
                $success = true;
                break;
				
				case 'damage':
                	if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
						$success = true;
						break;
					}
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
				$damage = $value;
            if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reverseTargetToCaster']){
				if (stripos($damage, '%') !== false) {
					$damage = round($this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] * $game->percentToDecimal($damage));
                }
				if (stripos($damage, 'i') !== false) {
					$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = 0;
					$success = true;
					break;
                }
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all']))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all'];
				if(array_search('increase-affliction', $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]], true) === false){
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal'][$arguments[4]]) || isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal']['all'])){
					$increase = (isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal'][$arguments[4]]))?$this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal'][$arguments[4]]:0;
					if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal']['all']))
						$increase = $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['deal']['all'];
					$damage += $increase;
				}
				}
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dr'])){
					$damage = $damage-(round($damage * $game->percentToDecimal($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dr'][0].'%')));
					if($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dr'][0] == 100)
						$damage = 0;
				}
				
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['fdr'])){
					$newdamage = $damage-$this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['fdr'][0];
					$this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['fdr'][0] -= $damage;
					if($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['fdr'][0] < 0)
						unset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['fdr']);
					$damage = $newdamage;
				}
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dd'])){
					
					foreach($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dd'] as $_e => $dd){
						if($dd[0] == 0) continue;
						if($damage <= 0) continue;
						$result = $dd[0]-$damage;
						$damage -= $dd[0];
						$this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dd'][$_e][0] = $result;
						if($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dd'][$_e][0] < 0)
							$this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['dd'][$_e][0] = 0;
					}
					
					
				}
				if($damage < 0)
					$damage = 0;
				$this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] -= $damage;
				if ($this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] < 0) {
                    $this->{$arguments[1][0]}["TEAM"]["HEALTH"][$arguments[1][1]] = 0;
                }
                $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reverseTargetToCaster'] = false;
				$success = true;
				if($refresh == true)
					$success = 'refresh';
             }
            //
            else{
				if (stripos($damage, '%') !== false) {
					$damage = round($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] * $game->percentToDecimal($damage));
                }
				if (stripos($damage, 'i') !== false) {
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
					$success = true;
					break;
                }
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all']))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all'];
				if(array_search('increase-affliction', $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]], true) === false){
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]) || isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'])){
					$increase = (isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]))?$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]:0;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all']))
						$increase = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'];
					$damage += $increase;
				}
				}
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'])){
					$damage = $damage-(round($damage * $game->percentToDecimal($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0].'%')));
					if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] == 100)
						$damage = 0;
				}
				
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'])){
					$newdamage = $damage-$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] -= $damage;
					if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] < 0)
						unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']);
					$damage = $newdamage;
				}
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'])){
					
					foreach($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'] as $_e => $dd){
						if($dd[0] == 0) continue;
						if($damage <= 0) continue;
						$result = $dd[0]-$damage;
						$damage -= $dd[0];
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] = $result;
						if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] < 0)
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] = 0;
					}
					
					
				}
				if($damage < 0)
					$damage = 0;
				$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] -= $damage;
				if ($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0) {
                    $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
                }
				$success = true;
				if($refresh == true)
					$success = 'refresh';
             }
                break;
				
				case 'piercing':
                	if ($this->checkInvulnerability($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['invulnerability'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false && $db->fieldFetch('skills', $arguments[4], 'iinvul') !== '1') {
						$success = true;
						break;
					}
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
				$damage = $value;
				if (stripos($damage, '%') !== false) {
					$damage = round($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] * $game->percentToDecimal($damage));
                }
				if (stripos($damage, 'i') !== false) {
					$damage = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]];
                }
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all']))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase']['all'];
				if(array_search('increase-affliction', $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]], true) === false){
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]) || isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'])){	
					$increase = (isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]))?$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]:0;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all']))
						$increase = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'];
					$damage = $damage+$increase;
				}
				}
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']) && isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']['unpiercable'])){
					$damage = $damage-round($damage * $game->percentToDecimal($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0].'%'));
					if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr'][0] == 100)
						$damage = 0;
				}
				
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']) && $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dr']['unpiercable'] == 'true'){
					$newdamage = $damage-$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] -= $damage;
					if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr'][0] < 0)
						unset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['fdr']);
					$damage = $newdamage;
				}
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'])){
					foreach($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'] as $_e => $dd){
						if($dd[0] == 0) continue;
						if($damage <= 0) continue;
						$result = $dd[0]-$damage;
						$damage -= $dd[0];
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] = $result;
						if($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] < 0)
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['dd'][$_e][0] = 0;
					}
					
				}
				if($damage < 0)
					$damage = 0;
				$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] -= $damage;
				if ($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0) {
                    $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
                }
				$success = true;
                break;
				
				case 'affliction':
                $damage = $value;
				if (stripos($damage, '%') !== false) {
					$damage = round($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] * $game->percentToDecimal($damage));
                }
                if (stripos($damage, 'i') !== false) {
					$damage = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]];
                }
				if(array_search('increase-affliction', $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]], true) !== false){
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]]))
					$damage += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase'][$arguments[4]];
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]) || isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'])){	
					$increase = (isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]))?$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal'][$arguments[4]]:0;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all']))
						$increase = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['deal']['all'];
					$damage = $damage+$increase;
				}
				}
				$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] -= $damage;
				if ($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] < 0)
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
      
                $success = true;
                break;
				
				case 'heal':
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
				$heal = $value;
				if (stripos($heal, '%') !== false) {
					$totalheal = $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]];
					if(strpos($heal, 'b') !== false){
						$totalheal = $db->fieldFetch('characters',$this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]], 'health');
						$heal = substr($heal,0,strpos($heal,'b'));
					}
					$heal = round($totalheal * $game->percentToDecimal($heal));
                }
				if ($heal === 'f') {
					$heal = $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]], 'health');
                }
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-heal']['all']))
					$heal += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-heal']['all'];
				if(isset($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-heal'][$arguments[4]]))
					$heal += $this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['increase-heal'][$arguments[4]];
				if($heal < 0)
					$heal = 0;
				if($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] <= 0 && empty($db->fieldFetch('skills',$arguments[4],'dead'))){
					$success = true;
					break;
				}else{
					$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]]  += $heal;
				}
				if ($this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] > $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]] , 'health')) {
                    $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]] , 'health');
                }
				$success = true;
                break;
				case 'switch':
					if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) !== false) {
						$success = true;
						break;
					}
				$it = (($value == 'h')?"HEALTH":"MANA");
				$what = $this->{$arguments[0][0]}["TEAM"][$it][$arguments[0][1]];
				$what2 = $this->{$arguments[1][0]}["TEAM"][$it][$arguments[1][1]];
				$this->{$arguments[0][0]}["TEAM"][$it][$arguments[0][1]] = $what2;
				$this->{$arguments[1][0]}["TEAM"][$it][$arguments[1][1]] = $what;
				$success = true;
				break;
				case 'counter':
				if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['counter'])){
					$success = true;
					break;
				}
			    if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) === false) {
                    if($arguments[1][0] == $arguments[0][0])
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['counter'] = $arguments[4];
					if($arguments[1][0] !== $arguments[0][0])
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['counter'] = $arguments[4].'s'.(($arguments[1][0] == 'first')?0:1).$arguments[1][1];
                }
                $success = true;
				break;
				
				case 'reflect':
				if ($this->checkStun($this->{$arguments[1][0]}["TEAM"]["STATUS"][$arguments[1][1]]['stunned'], $db->fieldFetch('skills',$arguments[4],'classes')) === false) {
                    if($arguments[1][0] == $arguments[0][0]){
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reflect'][0] = $arguments[4];
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['reflect'][1] = (empty($data['specify'])?'all':$data['specify']);
					}
				}
                $success = true;
				break;
				
				case 'replace':
					//if($_ == 0) break;
					if($arguments[3] == '0'){
						/*if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['replaced'])){
							$this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] = $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['replaced'];
							$success = true;
							break;
						}*/
						$pos = array_search($value,array_keys($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]));
						$skills = explode(',', $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]], 'skills'));
						$new = array($skills[$data['key']] => $db->fieldFetch('skills', $skills[$data['key']] , 'cost'));
						$this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] = array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], 0,$pos,TRUE)+$new+array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], $pos+1,count($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]),TRUE);
						unset($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]][$pos]);
					}else{
						// Set a backup for replacements ending
						/*if(!isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['replaced']) && !is_numeric($arguments[3])){
							$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['replaced'] = $this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]];
						}*/
						if(strpos($value,'|') !== false){
							$value = explode('|',$value);
							shuffle($value);
							$value = $value[0];
						}
						if(array_search($value,array_keys($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]))=== false){
							$pos = $data['key'];
							$new = array($value => $db->fieldFetch('skills', $value, 'cost'));
							$this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] = array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], 0,$pos,TRUE)+$new+array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], $pos+1,count($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]),TRUE);
							unset($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]][$pos]);
						}
					}
				$success = true;
				break;
				case 'copy':
					if($arguments[3] === '0'){
						
						$pos = array_search($value,array_keys($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]), true);
						$skills = explode(',', $db->fieldFetch('characters', $this->{$arguments[0][0]}["TEAM"]["ID"][$arguments[0][1]], 'skills'));
						$new = array($skills[$data['key']] => $db->fieldFetch('skills', $skills[$data['key']] , 'cost'));
						$this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] = array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], 0,$pos,TRUE)+$new+array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], $pos+1,count($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]),TRUE);
						unset($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]][$pos]);
					}else{
						if(strpos($value,'|') !== false){
							$value = explode('|',$value);
							shuffle($value);
							$value = $value[0];
						}
						if(array_search($value,array_keys($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]), true)=== false){
							$pos = $data['key'];
							$new = array($value => $db->fieldFetch('skills', $value, 'cost'));
							$this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]] = array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], 0,$pos,TRUE)+$new+array_slice($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]], $pos+1,count($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]]),TRUE);
							unset($this->{$arguments[0][0]}["TEAM"]["SKILL"][$arguments[0][1]][$pos]);
						}
					}
				$success = true;
				break;
				case 'show-mana':
					//$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['show_mana'] = true;
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]][] = 'show_mana';
					$success = true;
				break;
				case 'visibility':
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['visibility'][0] = $value;
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['visibility'][1]))
						$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['visibility'][1] .= ',';
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['visibility'][1] .= (empty($data['specify'])?'all':$data['specify']);
					$success = true;
				break;
				
				case 'also':
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['target'][0] = $arguments[1][0].$arguments[1][1];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['target'][1] = $arguments[4];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['target'][2] = $value;
				break;
				
				case 'targetme':
					switch($value){
						case 'Enemy 1';
						case 'Enemy 2';
						case 'Enemy 3';
							$value = (float)substr($value, 6)-1;
							$value = '1'.$value;
							break;
						case 'Ally 1';
						case 'Ally 2';
						case 'Ally 3';
							$value = (float)substr($value, 5)-1;
							$value = '0'.$value;
							$value = '0'.$value;
							break;
						case 'Me':
							$value = ($arguments[1][0]=='first')?'0':'1'.$arguments[1][1];
							break;
						default:
							break;
					}
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['targetme'][0] = $arguments[1][0].$arguments[1][1];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['targetme'][1] = $arguments[4];
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['targetme'][2] = $value;
					$this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['targetme'][3] = (empty($data['specify'])?'all':$data['specify']);
					
				break;
				
				default:
					$success = true;
					break;
			}
            //findmefast
            if(array_search('no-death', $this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]], true) !== false && $this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] == 0){	
				if($_ == 0){
					if(isset($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore']) && strpos($this->{$arguments[0][0]}["TEAM"]["STATUS"][$arguments[0][1]]['ignore'],'no-death') !== false)
						$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 0;
					else
						$this->{$arguments[0][0]}["TEAM"]["HEALTH"][$arguments[0][1]] = 1;
				}
			}
		}
		return $success;

    }
        
    function ReturnDescription($arguments, $directory) {

		global $system, $user, $db, $account, $caster, $target;
		
        $target = $db->query("SELECT * FROM characters WHERE id = '" . $this->{$arguments['target'][0]}['TEAM']['ID'][$arguments['target'][1]] . "'");
        if ($target->rowCount() === 0)
            return false;
        $target = $target->fetch();
        $caster = $db->query("SELECT * FROM characters WHERE id = '" . $this->{$arguments['caster'][0]}['TEAM']['ID'][$arguments['caster'][1]] . "'");
        if ($caster->rowCount() === 0)
            return false;
        $caster = $caster->fetch();
        $skill = $db->query("SELECT * FROM skills WHERE id = '" . key($arguments['skill']) . "'");
        if ($skill->rowCount($skill) === 0)
            return false;
        $skill = $skill->fetch();
        $effects = reset($arguments['skill']);
        $_turns = $arguments['turn'];
		$start = '';
		$expand = '';
        if ($arguments['target'][0] == 'second') {
			if($_turns == ($this->match['TURN']-1))
				$expand = $user->image($skill['id'], 'skills', (($directory===0)?'./':$directory), 'preview fl-r last');
			$start = '<div class = "tooltip r" href="#">
            <p>' . $expand.$user->image($skill['id'], 'skills', (($directory===0)?'./':$directory), 'fl-r last') . '</p>
                    <div>
					<img class="point" src="./images/arrow_right.png" />
                        <h1>' . $skill['name'] . '</h1>';
        } else {
			if($_turns == ($this->match['TURN']-1))
				$expand = $user->image($skill['id'], 'skills', (($directory===0)?'./':$directory), 'preview fl-l last');
            $start = '<div class = "tooltip" href="#">
            <p>' . $expand . $user->image($skill['id'], 'skills', (($directory===0)?'./':$directory), 'fl-l last') . '</p>
                    <div>
					<img class="point" src="./images/arrow_left.png" />
                        <h1>' . $skill['name'] . '</h1>';
        }
		$i = 0;
		
        foreach ($effects as $effect => $turns) {

            $data = $db->query("SELECT * FROM effects WHERE id = '" . $effect . "'");
            if ($data->rowCount($data) === 0)
                continue;
            $data = $db->fetch("SELECT * FROM effects WHERE id = '" . $effect . "'");
			
			

            $tturns = $data['duration'];
            if (empty($tturns)) {
                $tturns = '1';
            }
			$custom = '';
			
			if(strpos($turns, '*') !== false){
				$custom = substr($turns,strpos($turns,'*')+1); 
				$turns = substr($turns,0,strpos($turns,'*'));
			}
			//if(is_numeric($tturns) && round($tturns-(($this->match['TURN']-$_turns)/2)) < 0) continue;
			if(is_numeric($tturns) && $turns < 0) continue;
			$i++;
			if (!empty($data['description'])) {
				$pre .= '<p>- ' . $data['description'] . '</p>' . $this->defineTurns($_turns, $turns);
				continue;
			}
			
			if(!empty($data['increase-by']) && $data['increase-by'] !== '0')
				$data['increase-by'] = abs($data['increase-by']);
			
            foreach ($data as $id => $it) {
                if (is_numeric($id))
                    continue;
                if (empty($it))
                    continue;
                $continue = false;
                $break = false;
                $all = array_merge($this->effects['STATUS'], $this->effects['NORMAL']);
                foreach ($all as $_) {

                    if ($id == $_) {
                        $continue = false;
                        break;
                    }
                    if (/* $id != $___ && */ $id != 'description')
                        $continue = true;
                }
				
                if ($continue == true)
                    continue;
				if($id == 'remove')
					continue;
				
                switch ($id) {
					case 'renew':
							$pre .= '<p style="font-style: italic; color: grey;">This effect will renew.</p>';
						break;
					case 'visibility':
						$classes = 'all';
						if(!empty($data['specify'])){
							$classes = explode(',',$data['specify']);
							$counter = count($classes);
							foreach($classes as $k=>$c){
								if(empty($c))
									continue;
								if($k == 0)
									$classes = $db->fieldFetch('classes', $c, 'name');
								else
									$classes .= (($k+1==$counter)?' and ':', ').$db->fieldFetch('classes', $c, 'name');
							}
						}
						if($classes == 'all')
							$pre .= '<p>- All this characters skills will be '.$it.'.'. $this->defineTurns($_turns, $turns) . '</p>';
						else
							$pre .= '<p>- This characters '.$classes.' skills will be '.$it.'.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'destroy-dd':
							$pre .= '<p>- <b>' . $target['name'] . '</b> will lose all destructible defense.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'increase':
						if(empty($data['increaseby'])){
							$i--;
							break;
						}
						if($it == 'all')
							$pre .= '<p>- <b>' . $target['name'] . '\'s</b> damage will be '.((stripos($data['increaseby'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
                        else 
							$pre .= '<p>- <b>' . $db->fieldFetch('skills', $it, 'name') . '</b> damage will be '.((stripos($data['increaseby'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'increase-heal':
						if(empty($data['increaseby'])){
							$i--;
							break;
						}
						if($it == 'all')
							$pre .= '<p>- <b>' . $target['name'] . '</b> healing skills will be '.((stripos($data['increaseby'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
                        else 
							$pre .= '<p>- <b>' . $db->fieldFetch('skills', $it, 'name') . '</b>  healing skills will be '.((stripos($data['increaseby'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'increase-affliction':
							$pre .= '<p>- This will increase affliction damage. '. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'deal':
						if(empty($data['increaseby'])){
							$i--;
							break;
						}
						if($it == 'all')
							$pre .= '<p>- <b>' . $target['name'] . '</b> will take '.$data['increaseby'].$string.'more damage.'. $this->defineTurns($_turns, $turns) . '</p>';
                        else 
							$pre .= '<p>- <b>' . $db->fieldFetch('skills', $it, 'name') . '</b> damage will be '.((stripos($data['increaseby'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].' damage.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
                    case 'increase-manaRem':
                        if(empty($data['increaseby'])){
							$i--;
							break;
						}
                          if($it == 'all')
							$pre .= '<p>- <b>' . $target['name'] . '</b> will remove '.$data['increaseby'].' more mana with removing skills'. $this->defineTurns($_turns, $turns) . '</p>';
                          else 
							$pre .= '<p>- <b>' . $db->fieldFetch('skills', $it, 'name') . '</b> will remove '.$data['increaseby'].((stripos($data['increaseby'], '-')!== false)?'less':'more').' mana.'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'increase-cost':
						if(strpos($data['increase-cost'], 'S') !== false){
							$specific = substr($it,0, strpos($it,'S'));
							if($specific == 'a')
								$specific = '';
							if(!empty($specific))
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have <b>"'.$db->fieldFetch('skills',$specific,'name').'"</b> cost set to '.(substr($data['increase-cost'],strpos($data['increase-cost'], 'S')+1)).'.'. $this->defineTurns($_turns, $turns) . '</p>';
							else
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have their skill cost set to '.(substr($data['increase-cost'],strpos($data['increase-cost'], 'S')+1)).'.'. $this->defineTurns($_turns, $turns) . '</p>';
						}else{
							$pre .= '<p>- <b>' . $target['name'] . '</b> will have their skill cost '.((stripos($data['increase-cost'], '-')!== false)?'decreased':'increased').' by '.$data['increase-cost'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
						}
						break;
					case 'increase-duration':
						if(strpos($data['increase-duration'], 'S') !== false){
							$specific = substr($it, 1);
							if(!empty($specific))
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have <b>"'.$db->fieldFetch('skills',$specific,'name').'"</b> duration '.((stripos($data['increase-duration'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].' turn(s)'. $this->defineTurns($_turns, $turns) . '</p>';
						}else{
							$pre .= '<p>- <b>' . $target['name'] . '</b> will have their skill durations '.((stripos($data['increase-duration'], '-')!== false)?'decreased':'increased').' by '.$data['increaseby'].'.'. $this->defineTurns($_turns, $turns) . '</p>';
						}
						break;
					case 'increase-cooldown':
						if(strpos($data['increase-cooldown'], 'S') !== false){
							if(strpos($data['increase-cooldown'], '+') !== false)
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have "'.$db->fieldFetch('skills', substr($data['increase-cooldown'],0,strpos($data['increase-cooldown'], 'S')), 'name').'" cooldown increased by '.(substr($data['increase-cooldown'],strpos($data['increase-cooldown'], 'S')+2)).'.'. $this->defineTurns($_turns, $turns) . '</p>';
							elseif(strpos($data['increase-cooldown'], '-') !== false)
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have "'.$db->fieldFetch('skills', substr($data['increase-cooldown'],0,strpos($data['increase-cooldown'], 'S')), 'name').'" cooldown decreased by '.(substr($data['increase-cooldown'],strpos($data['increase-cooldown'], 'S')+2)).'.'. $this->defineTurns($_turns, $turns) . '</p>';
							else
								$pre .= '<p>- <b>' . $target['name'] . '</b> will have "'.$db->fieldFetch('skills', substr($data['increase-cooldown'],0,strpos($data['increase-cooldown'], 'S')), 'name').'" cooldown set to '.(substr($data['increase-cooldown'],strpos($data['increase-cooldown'], 'S')+1)).'.'. $this->defineTurns($_turns, $turns) . '</p>';
						}else{
							$pre .= '<p>- <b>' . $target['name'] . '</b> will have their cooldown '.((stripos($data['increase-cooldown'], '-')!== false)?'decreased':'increased').' by '.$data['increase-cooldown'].' turn(s).'. $this->defineTurns($_turns, $turns) . '</p>';
						}
						break;
					case 'if':
						$specific = '';
						if(!empty($data['specify'])){
							$specific = explode(',',$data['specify']);
							$counter = count($specific);
							foreach($specific as $k => $we){
								if($k == 0)
									$specific = $db->fieldFetch('classes', $we, 'name');
								else
									$specific .= (($k+1 == $counter)?' and ':', ').$db->fieldFetch('classes', $we, 'name');
							}
						}
						if(!empty($data['not']))
							$pre .= '<p>- If <b>' . $target['name'] . '</b> '.((!empty($data['ally']))?'is not targeted by':'does not use').' a skill '.((!empty($data['ally']))?'they':$target['name']).' will be effected by <b>'.$db->fieldFetch('skills', $it,'name'). '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						else
							$pre .= '<p>- If <b>' . $target['name'] . '</b> '.((!empty($data['ally']))?'is targeted by':'uses').' a '.$specific.' skill '.((!empty($data['ally']))?'they': $target['name']).' will be effected by <b>'.$db->fieldFetch('skills', $it,'name'). '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'dd':
						if(is_numeric($tturns) && round($tturns-(($this->match['TURN']-$_turns)/2)) == 0){
							$i--;
						break;}
						$pre .= '<p>- <b>' . $target['name'] . '</b> has '.(((float)$custom == 0)?'no':(float)$custom).' destructible defense. '. $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'dr':
						if(is_numeric($tturns) && round($tturns-(($this->match['TURN']-$_turns)/2)) == 0){
							$i--;
						break;}
						if(strpos($it,'f') !== false){
							$it = substr($it, 0, strpos($it, 'f'));
							$pre .= '<p>- <b>' . $target['name'] . '</b> has '.$it.' damage reduction.'. $this->defineTurns($_turns, $turns) . '</p>';
						}else
							$pre .= '<p>- <b>' . $target['name'] . '</b> has '.$it.'% reduction.'. $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'replace':
						$skillit = explode(',',$target['skills']);
						$pre .= '<p>- <b>'.$db->fieldFetch('skills', $skillit[$data['key']], 'name').'</b> has been replaced. </p>'. $this->defineTurns($_turns, $turns);
						break;
                    case 'invul':
						/*if(is_numeric($tturns) && round($tturns-(($this->match['TURN']-$_turns)/2)) == 0){
							$i--;
						break;}*/
						if($it !== 'all'){
							$it = explode(',', $it);
							$return = '';
							foreach($it as $k => $f){
								if(!empty($return) && $k!=(count($it)-1))
									$return .= ', ';
								if(!empty($return) && $k == (count($it)-1))
									$return .= ' and ';
								$return .= $db->fieldFetch('classes', $f, 'name');
							}
							$it = $return;
						}
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will be invulnerable to '.$it.' skills.' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
                    case 'stun':
						if($it !== 'all'){
							$it = explode(',', $it);
							$return = '';
							foreach($it as $k => $f){
								if(!empty($return) && $k!=(count($it)-1))
									$return .= ', ';
								if(!empty($return) && $k == (count($it)-1))
									$return .= ' and ';
								$return .= $db->fieldFetch('classes', $f, 'name');
							}
							$it = $return;
						}
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will have '.$it.' skills stunned.' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'fear':
						if($it !== 'all'){
							$it = explode(',', $it);
							$return = '';
							foreach($it as $k => $f){
								if(!empty($return) && $k!=(count($it)-1))
									$return .= ', ';
								if(!empty($return) && $k == (count($it)-1))
									$return .= ' and ';
								$return .= $db->fieldFetch('classes', $f, 'name');
							}
							$it = $return;
						}
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will have a fear state for '.$it.' skills. **This character can potentially miss skills**' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'disable':
						if(strpos($it,'S') !== false)
							$it = substr($it, 1);
						else
							$it = $it.' skills';
						if($it == '1')
							$it = 'first skill';
						elseif($it == '2')
							$it = 'second skill';
						elseif($it == '3')
							$it = 'third skill';
						elseif($it == '4')
							$it = 'fourth skill';
						elseif(strpos($it,'c') !== false){
							$it = explode(',',$it);
							$counter = $it;
							foreach($it as $k => $w){
								if($k == 0)
									$it = $db->fieldFetch('classes',substr($w,1),'name').' skills';
								else
									$it .= (($k+1 == $counter)?', ':' and ').$db->fieldFetch('classes',substr($w,1),'name').' skills';
							}
						}
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will have their '.$it.' disabled.' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
                    case 'status':
                        break;
                    case 'manaGain':
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will gain ' . (($data['manaGain'] == 'i') ? 'full mana' : $data['manaGain'] . ' mana points') . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
                    case 'externalManaGain':
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will gain ' . (($data['externalManaGain'] == 'i') ? 'full external mana' : $data['externalManaGain'] . ' external mana points') . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
                    case 'damage':
						if($tturns === 'e')
							$pre .= '<p>- <b>' . $target['name'] . '</b> has taken ' . $data['damage'] . ' damage</p>';
						else
							$pre .= '<p>- <b>' . $target['name'] . '</b> will take ' . $data['damage'] . ' damage' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'piercing':
						if($tturns === 'e')
							$pre .= '<p>- <b>' . $target['name'] . '</b> has taken ' . $data['piercing'] . ' piercing damage</p>';
						else
							$pre .= '<p>- <b>' . $target['name'] . '</b> will take ' . $data['piercing'] . ' piercing damage' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
                    case 'affliction':
						if($data['affliction'] == 'i')
							$pre .= '<p>- <b>' . $target['name'] . '</b> will instantly die' . $this->defineTurns($_turns, $turns) . '</p>';
                        else
							$pre .= '<p>- <b>' . $target['name'] . '</b> will take ' . $data['affliction'] . ' affliction damage' . $this->defineTurns($_turns, $turns) . '</p>';
						break;
                    case 'heal':
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will heal ' . (($data['heal'] == 'f') ? 'to full health' : $data['heal']) . ' ' . $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'switch':
                        $pre .= '<p>- <b>' . $target['name'] . '</b> will switch ' . (($data['switch'] == 'h') ? 'health' : 'mana') . ' with '.$caster['name']. $this->defineTurns($_turns, $turns) . '</p>';
                        break;
					case 'convertM':
						if($tturns === 'e')
							$pre .= '<p>- <b>' . $caster['name'] . '</b> has lost '. (($data['convertM'] == 'i') ? 'full mana' : $data['convertM'] . ' mana points').' and has dealt this damage to <b>' . $target['name'] . '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						else
							$pre .= '<p>- <b>' . $caster['name'] . '</b> will lose '. (($data['convertM'] == 'i') ? 'full mana' : $data['convertM'] . ' mana points').' and will deal this as damage to <b>' . $target['name'] . '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'convertH':
						if($tturns === 'e')
							$pre .= '<p>- <b>' . $caster['name'] . '</b> has lost '. (($data['convertH'] == 'i') ? 'full health' : $data['convertH'] . ' health points').' and has dealt this damage to <b>' . $target['name'] . '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						else
							$pre .= '<p>- <b>' . $caster['name'] . '</b> will lose '. (($data['convertH'] == 'i') ? 'full mana' : $data['convertH'] . ' health points').' and will deal this as damage to <b>' . $target['name'] . '</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'removeM':
						if($tturns === 'e')
							$pre .= '<p>- <b>' . $target['name'] . '</b> has lost '. (($data['removeM'] == 'i') ? 'full mana' : $data['removeM'] . ' mana points');
						else
							$pre .= '<p>- <b>' . $target['name'] . '</b> will lose ' . (($data['removeM'] == 'i') ? 'full mana' : $data['removeM'] . ' mana points') . $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'drainH':
						$pre .= '<p>- <b>' . $target['name'] . '</b> will be drained by ' . $caster['name'] . ' for ' . (($data['drainH'] == 'i') ? ' full health' : $data['drainH'] . ' health points') . $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'drainM':
						$pre .= '<p>- <b>' . $target['name'] . '</b> will be drained by ' . $caster['name'] . ' for ' . (($data['drainM'] == 'i') ? ' full mana' : $data['drainM'] . ' mana points') . $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'counter':
						if($turns === 'e'){
							$skill['invisible'] = '0';
							$pre .= '<p>- This skill has countered successfully</p>';
						}else{
							if(round($tturns-(($this->match['TURN']-$_turns)/2)) == 0){
								$pre .= '<p>- This skill has ended</p>';
								$skill['invisible'] = '0';
							}else{
								if($arguments['target'][0] !== $arguments['caster'][0])
									$pre .= '<p>- If <b>' . $target['name'] . '</b> uses a skill on the enemy team it will be countered '. $this->defineTurns($_turns, $turns) . '</p>';
								else
									$pre .= '<p>- <b>' . $target['name'] . '</b> will counter '.(($data['count'] !== '1') ? 'the first skill' : 'all skills'). $this->defineTurns($_turns, $turns) . '</p>';
							}
						}
						break;
					case 'reflect':
						if($turns === 'e'){
							$skill['invisible'] = '0';
							$pre .= '<p>- This skill has reflected successfully</p>';
						}else{
							if(round($tturns-(($this->match['TURN']-$_turns)/2)) == 0){
								$pre .= '<p>- This skill has ended</p>';
								$skill['invisible'] = '0';
							}else{
								$specific = 'all';
								if(!empty($data['specify'])){
									$specific = explode(',',$data['specify']);
									foreach($specific as $k => $s){
										if($k == 0)
											$specific = $db->fieldFetch('classes', $s, 'name');
										else
											$specific .= (($k+1 == $counter)?' and ': ',').$db->fieldFetch('classes', $s, 'name');
									}
								}
								$pre .= '<p>- <b>' . $target['name'] . '</b> will reflect '.(($data['count'] !== '1') ? 'the first '.$specific.' skill' : 'all '.$specific.' skills'). $this->defineTurns($_turns, $turns) . '</p>';
							}	
						}						
						break;
					case 'ignore':
						if($it == 'invul'){
							$it = 'cannot become invulnerable.';
						}elseif($it == 'dr'){
							$it = 'cannot reduce damage.';
						}elseif($it == 'removeM'){
							$it = 'will ignore mana removal.';
						}elseif($it == 'drainM'){
							$it = 'will ignore mana drain.';
						}elseif($it == 'drainH'){
							$it = 'will ignore health drain.';
						}elseif($it == 'manaGain'){
							$it = 'will ignore mana gain.';
						}elseif($it == 'no-die'){
							$it = 'will ignore no death effects.';
						}elseif($it == 'allenemy'){
							$it = 'will ignore all harmful skills.';
						}elseif($it == 'allally'){
							$it = 'will ignore all helpful skills.';
						}else{
							$it = 'will ignore '.$it.' effects.';
						}
						$pre .= '<p>- <b>' . $target['name'] . '</b> '.$it.$this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'no-ignore':
						if($it == 'iinvul'){
							$it = 'bypass invulnerability.';
						}else{
							$it = 'ignore '.$it.' skills.';
						}
						$pre .= '<p>- <b>' . $target['name'] . '</b> cannot '.$it.$this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'set-skill':
						if($it == 'bypass'){
							$it = 'bypass invulnerability.';
						}
						$pre .= '<p>- <b>' . $target['name'] . '</b> skills will '.$it.$this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'also':
						if($arguments['target'][0] == $arguments['caster'][0] && $arguments['target'][1] == $arguments['caster'][1]) 
							break;
						$pre .= '<p>- <b>' . $caster['name'] . '</b>  will target <b>'.$target['name'].'</b>'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'targetme':
						$specific = ' skills';
						if(!empty($data['specify'])){
							$specific = explode(',',$data['specify']);
							$counter = count($specific);
							foreach($specific as $k=>$s){
								if($k == 0)
									$specific = $db->fieldFetch('classes', $s,'name');
								else
									$specific .= (($k+1==$counter)?' and ':', ').$db->fieldFetch('classes', $s,'name');
							}
							$specific .= ' skills';
						}
						if($it == 'O/r')
							$it = 'randomly';
						if($it == 'notallys')
							$pre .= '<p>- <b>' . $target['name'] . '</b>  will not be able to target <b>'.$caster['name'].'</b> teammates with '.$specific. $this->defineTurns($_turns, $turns) . '</p>';
						elseif($it == 'notcaster')
							$pre .= '<p>- <b>' . $target['name'] . '</b>  will not be able to target <b>'.$caster['name'].'</b> with '.$specific. $this->defineTurns($_turns, $turns) . '</p>';
						else 
							$pre .= '<p>- <b>' . $target['name'] . '</b>  will target <b>'.$it.'</b> with '.$specific. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'no-death':
                        if(!empty($data['reverseTargetToCaster'])){
                        break;
                        }
                        else{
						$pre .= '<p>- <b>' . $target['name'] . '</b> will not die'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
                        }
                    case 'reverseTargetToCaster':
                    break;
					case 'no-resurrect':
						$pre .= '<p>- <b>' . $target['name'] . '</b> cannot be targeted after death'. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'following':
						$pre .= '<p style="font-style: italic; color: grey;"> This effect will trigger '.(($data['following'] ==1)?'next turn':'after '.$data['following'].' turns').'</p>';
						break;
					case 'show-mana':
						$pre .= '<p>- <b>' . $target['name'] . '</b> mana will appear to the enemy team. '. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'reset':
						$pre .= '<p>- <b>' . $target['name'] . '</b> will have <b>"'.$db->fieldFetch('skills', $it,'name').'"</b> cooldown reset to 0. '. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'unpiercable':
						$pre .= '<p>- This damage reduction cannot be pierced. '. $this->defineTurns($_turns, $turns) . '</p>';
						break;
					case 'transform':
						continue;
						break;
                    default:
                        $pre .= '<p>- Something happens</p>';
                }
				
				if(!empty($data['condition'])){
					$who = $target['name'];
					if(!empty($data['self']))
						$who = $caster['name'];
					$conditions = explode(',',$data['condition']);
					foreach($conditions as $condition){
						if(empty($condition)) continue;
						if(strpos($condition,'H') !== false){
							if(strpos($condition,'>') !== false){
								$pre .= '<p style="font-style: italic; color: #e63d3d;">This effect will trigger when <b>' . $who . '</b> is above '.(substr($condition,strpos($condition,'>')+1)).' health</p>';
							}elseif(strpos($condition,'<') !== false){
								$pre .= '<p style="font-style: italic; color: #e63d3d;">This effect will trigger when <b>' . $who . '</b> is below '.(substr($condition,strpos($condition,'<')+1)).' health</p>';
							}else{
								$pre .= '<p style="font-style: italic; color: #e63d3d;">This effect will trigger when <b>' . $who . '</b> is at '.(substr($condition,strpos($condition,'H')+1)).' health</p>';
							}	
						}
						if(strpos($condition,'M') !== false){
							if(strpos($condition,'>') !== false){
								$pre .= '<p style="font-style: italic; color: #32c9e9; #e63d3d;">This effect will trigger when <b>' . $who . '</b> is above '.(substr($condition,strpos($condition,'>')+1)).' mana</p>';
							}elseif(strpos($condition,'<') !== false){
								$pre .= '<p style="font-style: italic; color: #32c9e9;">This effect will trigger when <b>' . $who . '</b> is below '.(substr($condition,strpos($condition,'<')+1)).' mana</p>';
							}else{
								$pre .= '<p style="font-style: italic; color: #32c9e9;">This effect will trigger when <b>' . $who . '</b> is at'.(substr($condition,strpos($condition,'M')+1)).' mana</p>';
							}
						}
					}
				}
			}
        
			
		}
		
		if(isset($this->{$arguments['caster'][0]}['TEAM']['STATUS'][$arguments['caster'][1]]['visibility'])){
			$classes = explode(',',$skill['classes']);
			$specific = explode(',',$this->{$arguments['caster'][0]}["TEAM"]["STATUS"][$arguments['caster'][1]]['visibility'][1]);
			$found = false;
			foreach($specific as $s){
				if($s == 'all')
					$found = true;
				if(array_search($s,$classes) !== false)
					$found = true;
				if($found === true)
					break;
			}
			if($found === true){
				if($this->{$arguments['caster'][0]}['TEAM']['STATUS'][$arguments['caster'][1]]['visibility'][0] == 'visible')
					$skill['invisible'] = '0';
				else
					$skill['invisible'] = '1';
			}
		}
		
		if($skill['invisible'] == '1' && $account['id'] !== $this->{$arguments['caster'][0]}['ACCOUNT']['id']) 
			return false;
		
		if($i > 0){
			if($skill['iinvul'] === '1' && $arguments['caster'][0] !== $arguments['target'][0])
				$pre .= '<p><i>This skill will ignore invulnerability</i> ';
			
			if($this->{$arguments['caster'][0]}['ACCOUNT']['id'] === $this->{$arguments['target'][0]}['ACCOUNT']['id']){
			$skills = $db->query("SELECT id, name, requires FROM skills");
			while ($s = $skills->fetch()) {
			
				if ($s['requires'] == '0')
					continue;
				$_ = explode('|', $s['requires']);
				foreach ($_ as $__) {
					if ($__ != $skill['id'])
						continue;
					$pre .= '<p style="font-style: italic; color: grey;"> This skill enables <b>' . $s['name'] . '</b></p>';
					$i++;
				}
			}
			}
			if($skill['invisible'] == '1')
				$pre .= '<p style="font-style: italic; color: grey;"> This skill is invisible</p>';
			if(!empty($caster['passive'])){
				$passives = explode(',',$caster['passive']);
				if(array_search($skill['id'],$passives) !== false)
					$pre .= '<p style="font-style: italic; color: grey;"> This skill is passive</p>';
			}
			if(!empty($skill['activate'])){
				$activate = $db->fieldFetch('skills', substr($skill['activate'],1),'name');
				$pre .= '<p style="font-style: italic; color: grey;"> This skill will activate <b>'.$activate.'</b> '.(!empty($skill['whenend'])?'when it ends':'next turn').'.</p>';
			}
			$pre .= '</div></div>';
		
			return $start.$pre;
		}
    }

    function defineTurns($turn, $duration) {
        global $caster, $target, $skill, $pre;
        $prefix = '<i style="color:#BA2415;"> ';
        $return = '';
		$duration = explode('+',$duration);
		foreach($duration as $lasts){
        if (!is_numeric($lasts)) {
			if(!empty($return))
				$return .= '<br/>';
            switch ($lasts) {
                case 'c':
                    $return .= 'this skill ends when '.$caster['name'].' dies';
                    break;
                case 't':
                    $return .= 'this skill ends when '.$target['name'].' dies';
                    break;
				case 'e':
					$return .= 'this skill has ended!';
					break;
				case 's':
					$return .= 'this skill will end if '.$caster['name'].' is stunned';
					break;
				case 'i':
					$return .= 'this skill will end if '.$target['name'].' goes invulnerable';
					break;
				case 'm':
					$return .= 'this skill will end if '.$caster['name'].' mana is 0';
					break;
                default:
                    $return .= 'Undefined';
					break;
            }
        } else {
			//$total = round($lasts-(($this->match['TURN']-$turn)/2));
			$total = $lasts;
            if ($total <= 0)
				$return .= 'Ends next turn';
			elseif($total == 1)
                $return .= '1 turn left';
            else
                $return .= 'Left(-) ' . $total . ' turns';
        }
		}
        return $prefix . $return . '</i>';
    }

    function manaGain($death_0,$death_1) {

        global $system, $db;

        $increase = $system->data('Mana_Increase');
		$deaths = $death_0;
		// Whos turn is it next?
		if ($this->match['TURN'] % 2 !== 0){$deaths = $death_1; $opponent = $this->first; $this->first = $this->second;}
		if($deaths == 3) return;
        foreach ($this->first['TEAM']['ID'] as $key => $character) {
			if(isset($this->first['TEAM']['STATUS'][$key]['increase-mana']['all']))
				$increase += $this->first['TEAM']['STATUS'][$key]['increase-mana']['all'];
            $out = $increase / (3 - $deaths);
            $out += $this->first['TEAM']['MANA'][$key];
            $who = $db->fetch("SELECT * FROM characters WHERE id = '" . $character . "'");
            if ($out > $who['mana']) {
                $out = $who['mana'];
            }
			$ignore = false;
			if(isset($this->first['TEAM']['STATUS'][$key]['ignore']) && strpos($this->first['TEAM']['STATUS'][$key]['ignore'], 'manaGain') !== false)
				$ignore = true;
			if($ignore === false)
				$this->first['TEAM']['MANA'][$key] = $out;
        }
		if ($this->match['TURN'] % 2 !== 0){$this->second = $this->first; $this->first = $opponent;}
        unset($increase);
        unset($out);
        unset($who);
    }

/*

    function externalManaGain() { not by marcos


        global $system, $db;
		// Whos turn is it next?
		if ($this->match['TURN'] % 2 !== 0){$opponent = $this->first; $this->first = $this->second;}
        foreach ($this->first['TEAM']['ID'] as $key => $character) {
            $out += $this->first['TEAM']['MANA'][$key];
            $who = $db->fetch("SELECT * FROM characters WHERE id = '" . $character . "'");
            if ($out > $who['externalMana']) {
                $out = $who['externalMana'];
            }
			$ignore = false;
			if(isset($this->first['TEAM']['STATUS'][$key]['ignore']) && strpos($this->first['TEAM']['STATUS'][$key]['ignore'], 'externalManaGain') !== false)
				$ignore = true;
			if($ignore === false)
				$this->first['TEAM']['MANA'][$key] = $out;
        }
		if ($this->match['TURN'] % 2 !== 0){$this->second = $this->first; $this->first = $opponent;}
        unset($out);
        unset($who);
    }



*/
	function makemeunderstand($what, $switch = 0){
		if(!is_array($what)){
		$return = array();
		$return['turn'] = substr($what, 0, stripos($what, "="));
		$what = substr($what, stripos($what, "=")+1);
		// Caster
		if(substr($what, 0, 1) == 1) $return['caster'][] = 'second';
		else $return['caster'][] = 'first';
		$return['caster'][1] = substr($what, 1, 1);
		// Target
		if(substr($what,stripos($what,"]")+1,1) == 1) $return['target'][] = 'second';
		else $return['target'][] = 'first';
		$return['target'][1] = substr($what,stripos($what,"]")+2,1);
		// Skill
		$effects = explode(",", substr(substr(substr($what, 3), 0, strlen(substr($what, 3))-3),strpos(substr(substr($what, 3), 0, strlen(substr($what, 3))-3),"[")+1));
		foreach($effects as $effect){
			$final[substr($effect, 0, strpos($effect,";"))] = substr($effect, strpos($effect,";")+1);
		}
		$return['skill'][substr($what, 3,strpos($what,"[")-3)] = $final;
		if($switch == 1){
			if($return['caster'][0] == 'first') $return['caster'][0] = 'second';
			else $return['caster'][0] = 'first';
			if($return['target'][0] == 'first') $return['target'][0] = 'second';
			else $return['target'][0] = 'first';
		}
		}else{
			$return = $what['turn'].'='.(($what['caster'][0] == 'first')?'0':'1').$what['caster'][1].':'.key($what['skill']).'[';
			foreach(reset($what['skill']) as $effect=>$turnsleft){
				if(strpos($return,';') == true) $return .= ',';
				$return .= $effect.';'.$turnsleft;
			}
			$return .= ']'.(($what['target'][0] == 'first')?'0':'1').$what['target'][1];
		}
		return $return;
		
	}
}

?>