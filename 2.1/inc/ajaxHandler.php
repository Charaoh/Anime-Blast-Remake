<?php
if (!defined('SITE')) {
    require('index.php');
    exit;
}
class Ajax {
	
	function change($which = false, $value){
		global $db, $json, $account, $system, $user, $STYLE;
		// check if exists
		if(!$which)
			return $json['error'] = 'Not determined function';
		if($which == 'selection' || $which == 'ingame')
			goto process;
		if($which == 'sfx' && $value == $system->data('default-sfx'))
			goto process;
		if($which == 'gui' && $value == $system->data('template'))
			goto process;
		$it = $db->fetch("SELECT * FROM items WHERE value='".$value."'");
		if($it){
			$item = $db->query("SELECT * FROM inventory WHERE account='".$account['id']."' AND item='".$it['id']."'");
			if($item->rowCount() > 0){
				$value = $it['value'];
			}else
				return  $json['error'] = 'You do not have this item';
		}else
			return $json['error'] = 'Does not exist';
		process:
		// Update setting
		if($which == 'gui')
			$db->query("UPDATE accounts SET tpl = '".$value."' WHERE id = '".$account['id']."'");
		elseif($which == 'sfx')
			$db->query("UPDATE accounts SET sfx = '".$value."' WHERE id = '".$account['id']."'");
		else{
			if($which == 'selection')
				$value = '('.$value.')';
			if(!empty($account['bg'])){
				if($which == 'selection'){
					$sub = substr($account['bg'], strpos($account['bg'], ')')+1);
					$value = $value.$sub;
				}else{
					$sub = substr($account['bg'], 0, strpos($account['bg'], ')')+1);
					$value = $sub.$value;
				}
			}
			$db->query("UPDATE accounts SET bg = '".$value."' WHERE id = '".$account['id']."'");
		}
		return $json['success'] = true;
	}

	function buyThis($id) {

        global $db, $json, $account, $system, $user, $STYLE;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
		
		$item = $db->query("SELECT * FROM sales WHERE id='".$id."'");
		if($item->rowCount() > 0){
			$item = $item->fetch();
			$discount = $system->data('discount');
			if($discount !== '0'){
				// There is a discount happening!!! 
				$percent = str_replace('%', '', $discount);
				$item['value'] = $item['value']-($item['value']*($percent/100));
			}
			if(($account['gold'] - $item['value']) < 0)
				return $json['error'] = 'Not enough BC. Win some games pleb';
			$items = explode(',', $item['items']);
			$update = array();
			foreach($items as $me){
				$it = $db->fetch("SELECT * FROM items WHERE id='".$me."'");
				if(!$it) continue;
				switch($it['name']){
					case 'character':
						$c = $db->fetch("SELECT * FROM characters WHERE id='".$it['value']."'");
						if($c){
							$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
							$characters = explode(',',$account['characters']);
							if(array_search($c['id'],$characters) == false){
								$db->query("UPDATE accounts SET characters = '".$account['characters'].','.$c['id']."' WHERE id = '".$account['id']."'");
								$update['character'] = true;
							}
						}
					break;
					case 'sfx':
						$update['sfx'] = $it['value'];
					break;
				}
				$db->query("INSERT INTO inventory (account,item,post) VALUES ('".$account['id']."', '".$it['id']."','".$item['id']."')");
			}
			$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
			$db->query("UPDATE accounts SET gold = '".($account['gold']-$item['value'])."' WHERE id = '".$account['id']."'");
			if($update['character'] == true){
				$update['character'] = array();
				$item_tpl = '<div id="{ID}" class="i">
						<span class="ititle"><span class="discount">{DISCOUNT}</span>{TITLE}</span>
						<p class="price"><span>{PRICE}</span></p>
						<div>{PREV}</div>
					</div>';
				$shop = $db->query("SELECT * FROM sales WHERE seller = '-1' ORDER BY id DESC");
				$consale = '';
				if($shop->rowCount() > 0){
					while($i = $shop->fetch()){
						$item = $item_tpl;
						// Title with experation data
						$title = $i['title'];
						// Characters ? show pictures of them
						$images = '';
						$t = explode(',', $i['items']);
						foreach($t as $k=>$it){
							$character = $db->query("SELECT * FROM items WHERE id = '".$it."' AND `name` = 'character'");
							if($character->rowCount() > 0){
								$character = $character->fetch();
								$character = $db->fetch("SELECT * FROM characters WHERE id='" . $character['value'] . "'");
								if($character){
									$mycharacters = explode(',',$account['characters']);
									$unlocked = '';
									if(array_search($character['id'],$mycharacters) !== false){
										unset($t[$k]);
										$unlocked = 'locked';
									}
									$images .= $user->image($character['id'], 'characters', './../../', $unlocked);
									$images .= $user->image($character['id'], 'characters/slanted', './../../', $unlocked.' alts');
								}
							}
						}
						if(empty($t))
							continue;
				
						// Check for discount!
						$discount = $system->data('discount');
						if(!empty($discount)){
							// There is a discount happening!!! 
							$percent = str_replace('%', '', $discount);
							$new = $i['value']-($i['value']*($percent/100));
							$i['value'] = $new;
						}
						$consale .= $STYLE->tags($item, array(
							"ID" => $i['id'],
							"TITLE" => $i['description'],
							"DISCOUNT" => ((!empty($discount))?$discount.' OFF!':''),
							"PREV" => $images,
						"PRICE" => $i['value']
						));
					}
					if(empty($consale))
						$consale = '<p style="transform: skewX(30deg);">No items available to buy.</p>';
			
				}else{
					$consale = '<p style="transform: skewX(30deg);">No items available to buy.</p>';
				}
				$update['character'][] = $consale;
				// Now get the ingame characters and refresh the list
				$characters = $db->query("SELECT * FROM `characters` ORDER BY who ASC, sort DESC");
				$c = '';
				if ($characters->rowCount() > 0) {

					while ($character = $characters->fetch()) {
			
					// Using account var update every loop since we update fields
					$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");

					// Check if a certain group can't see the character
					if (!empty($character['who'])) {
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						$who = explode(',', $who['who']);
						$key = array_search($account['group'], $who);
						if (isset($key) && $who[$key] != $account['group']) 
							continue;
					}
			
					$skills = explode(',', $character['skills']);
					$f2 = array(preg_replace('/\s+/', '_', $db->fieldFetch('animes',$character['who'],'name')));
					foreach($skills as &$skill){
						$classes = explode(',',$db->fieldFetch('skills',$skill,'classes'));
						foreach($classes as $class){
							if(array_search($db->fieldFetch('classes', $class,'name'), $f2) !== false) continue;
							$f2[] = $db->fieldFetch('classes', $class,'name');
						}
						$effects = explode(',',$db->fieldFetch('skills',$skill,'effects'));
						$replacements = array();
						foreach($effects as $effect){
							$effect = $db->fetch("SELECT * FROM `effects` WHERE `id` ='".$effect."'");
							if($effect){
								foreach($effect as $item => $value){
									if(empty($item) || is_numeric($item))
										continue;
									if(empty($value))
										continue;
									if($item == 'id' || $item == 'duration' || $item == 'target' || $item == 'description')
										continue;
									if($item == 'replace' || $item == 'if'){
										$replaced = explode('|', $value);
										foreach($replaced as $replacing){
											$replacements[] = $replacing;
										}
										continue;
									}
									if(array_search($item, $f2) !== false) 
										continue;
									$f2[] = $item;
								}
							}
						}
						if(!empty($replacements)){
							array_push($replacements, $skills);
						}
					}
					$f2 = implode(' ', $f2);
					// Start decompiling the characters and checking if there unlocked or in the team
					$ucharacters = explode(',', $account['characters']);
					$key = array_search($character['id'], $ucharacters);
					if (isset($key) && $ucharacters[$key] == $character['id']) {
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						$c .= '<div class="'.$f2.'">' . $user->image($character['id'], 'characters', './../../', 'character', '', $who['name'])  . '</div>';
					} else {
					
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						// Check if group of only can be seen if unlocked
						if($system->data('Only') == $character['who'])
							continue;
						$c .= '<div class="'.$f2.'"><p class="locked">' . $user->image($character['id'], 'characters', './../../', 'character', '', $who['name']) . '</p><span class="lock"></span></div>';
					}
					}
				}
				$update['character'][] = $c;
			}elseif(isset($update['sfx'])){
				$val = $update['sfx'];
				$update['sfx'] = array($val);
				$item_tpl = '<div id="{ID}" class="i">
						<span class="ititle"><span class="discount">{DISCOUNT}</span>{TITLE}</span>
						<p class="price"><span>{PRICE}</span></p>
						<div>{PREV}</div>
					</div>';
				$shop = $db->query("SELECT * FROM sales WHERE seller = '-1' ORDER BY id DESC");
				$consale = '';
				if($shop->rowCount() > 0){
					while($i = $shop->fetch()){
						$item = $item_tpl;
						// Title with experation data
						$title = $i['title'];
						// Characters ? show pictures of them
						$images = '';
						$t = explode(',', $i['items']);
						foreach($t as $k=>$it){
							$me = $db->query("SELECT * FROM items WHERE id = '".$it."' AND `name` = 'sfx'");
							if($me->rowCount() > 0){
								// Item exists, check my inventory if I have it
								$ihave = $db->query("SELECT * FROM `inventory` WHERE `account` = '".$account['id']."' AND `item` = '".$it."'");
								if($ihave->rowCount() > 0)
									unset($t[$k]);
							}else
								unset($t[$k]);
						}
						if(empty($t))
							continue;
				
						// Check for discount!
						$discount = $system->data('discount');
						if(!empty($discount)){
							// There is a discount happening!!! 
							$percent = str_replace('%', '', $discount);
							$new = $i['value']-($i['value']*($percent/100));
							$i['value'] = $new;
						}
						$consale .= $STYLE->tags($item, array(
							"ID" => $i['id'],
							"TITLE" => $i['description'],
							"DISCOUNT" => ((!empty($discount))?$discount.' OFF!':''),
							"PREV" => (!empty($i['thumbnail'])?'<img class="thumbnail" src="'.$i['thumbnail'].'"/>':$images),
						"PRICE" => $i['value']
						));
					}
					if(empty($consale))
						$consale = '<p style="transform: skewX(30deg);">No items available to buy.</p>';
			
				}else{
					$consale = '<p style="transform: skewX(30deg);">No items available to buy.</p>';
				}
				$update['sfx'][] = $consale;
			}
			$update['gold'] = $account['gold'];
			return $json = $update;
		}else{
			return $json['error'] = 'notfound';
		}
    }
    

// EVENT
	function buyBox($id) {

        global $db, $json, $account, $system, $user, $STYLE, $game;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
    	$santaItem = 'santa';
    	if($id == '1' || $id == '2')
        	$santaItem = 'bcConvert';
    	else
        	$id = $id-2;
		
		$item = $db->query("SELECT * FROM `items` WHERE `name` = '$santaItem' AND `value` ='".$id."'");
		if($item->rowCount() > 0){
			$item = $item->fetch();
        	$what = ($santaItem == 'santa' ? 'cookies' : 'gold');
			if(($account[$what] - $item['sub-value']) < 0)
				return $json['error'] = 'Not enough '.$what.'.';
			$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '".$item['id']."')");
			$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
			$db->query("UPDATE accounts SET $what = '".($account[$what]-$item['sub-value'])."' WHERE id = '".$account['id']."'");
			$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'"); 
			$update['gold'] = $account['gold'];
        	$update['cookies'] = $account['cookies'];
        	// Update inventory
        	$inventory = $game->getInventory();
        	$update['inventory'] = $inventory;
			return $json = $update;
		}else{
			return $json['error'] = 'notfound';
		}
    }
	function openBox($id) {

        global $db, $json, $account, $system, $user, $STYLE, $game;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
		
		$item = $db->query("SELECT * FROM `inventory` WHERE `id` ='".$id."'");
		if($item->rowCount() > 0){
			$item = $item->fetch();
        	// Check if its me
        	if($item['account'] !== $account['id'])
            	return $json['error'] = 'Invalid box';
        	$itemX = $db->query("SELECT * FROM `items` WHERE `id` ='".$item['item']."'");
        	if($itemX->rowCount() === 0)
            	return $json['error'] = 'Item not found';
        	$itemX = $itemX->fetch();
        	if($itemX['name'] !== 'santa' && $itemX['name'] !== 'bcConvert')
            	return $json['error'] = 'Invalid inventory item';
        	$reward = '';
        	$value = $itemX['value'];
        	if($itemX['name'] == 'santa'){
            	
            	switch($value){
                case '1':
                	$rand = rand(1, 1000);
                	$bc = $rand;
					$db->query("UPDATE accounts SET gold = '".($account['gold']+$bc)."' WHERE id = '".$account['id']."'");
               		$reward = "You have earned $bc BC from the random box! Congratulations";
                	if($rand == 1000)
                    	$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the random bc box!");
                break;
                case '2':
                	$rand = rand(1, 500);
                	$xp = $rand;
                	$db->query("UPDATE accounts SET experience = '".($account['experience']+$xp)."' WHERE id = '".$account['id']."'");
               		$reward = "You have earned $xp XP from the random box! Congratulations";
                	if($rand == 500)
                    	$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the random xp box!");
                break;
                case '3':
                	$rand = rand(1000, 2500);
                	$bc = $rand;
                	$db->query("UPDATE accounts SET gold = '".($account['gold']+$bc)."' WHERE id = '".$account['id']."'");
               		$reward = "You have earned $bc BC from the medium box! Congratulations";
                	if($rand == 2500)
                    	$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the medium bc box!");
                break;
                case '4':
                	$rand = rand(500, 1500);
                	$xp = $rand;
                	$db->query("UPDATE accounts SET experience = '".($account['experience']+$xp)."' WHERE id = '".$account['id']."'");
               		$reward = "You have earned $xp XP from the medium box! Congratulations";
                	if($rand == 1500)
                    	$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the medium XP box!");
                break;
                case '5':
                	$reward = "You have earned a custom rank! We have contacted a discord staff member and they will message you. Congratulations";
                $system->sendDiscordMsg("Everyone congratulate ".$account['name']." for earning a custom discord rank!");
                	$system->sendDiscordMsg("Everyone ".$account['name']." has earned a custom discord rank, please message him. @everyone","https://discord.com/api/webhooks/793660045962379314/wDCJMLB4Ch1ZSY0YSIkp9FIwNjGWB-wkKF5ZfkhDpxmqyhGrlfPzZW6S-iw2f6MdRZ1c");
                break;
                case '6':
                	$reward = "You have earned Light Yagami! Congratulations";
					$characters = explode(',',$account['characters']);
					if(array_search('612',$characters) === false){
                    	$characters = $account['characters'].',612';
						$db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                    	//$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Light Yagami!");
					}else
                    	$reward = "error";
                break;
                case '7':
                	$rate = (double) '0.50';
					$max = 1 / $rate; // 100
					if (mt_rand(0, $max) === 0) {
                    	$rand = rand(2,3);
                    	$rand = '20'.$rand;
                    	$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '".$rand."')");
                    	$reward = "Congratulations! you earned a random box";
                    	$rate = (double) '0.35';
						$max = 1 / $rate; // 100
						if (mt_rand(0, $max) === 0) {
                        	$rand = rand(4,5);
                        	$rand = '20'.$rand;
                    		$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '".$rand."')");
                        	$reward .= "\n On a streak!, You have earned a medium box!";
                        	$rate = (double) '0.10';
							$max = 1 / $rate; // 100
							if (mt_rand(0, $max) === 0) {
                            	$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '206')");
                            	$reward .= "\n Stop lad, breaking the system... You have earned the discord box!";
                        		$rate = (double) '0.05';
								$max = 1 / $rate; // 100
								if (mt_rand(0, $max) === 0) {
                                	$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '210')");
                                	$reward .= "\n fml... You have earned another chance at the gacha box!";
                               	 	/*$rate = (double) '0.001';
									$max = 1 / $rate; // 100
									if (mt_rand(0, $max) === 0) {
                                    	$db->query("INSERT INTO inventory (account,item) VALUES ('".$account['id']."', '207')");
                                    	$reward .= "\n The gods are with you, you have earned the gif character!";  
                    					$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the gacha box!");*/
                                    
                                }
                            }
                        }
                    }else
                    {
                    	$cookies = rand(10,30);
                		$db->query("UPDATE accounts SET cookies = '".($account['cookies']+$cookies)."' WHERE id = '".$account['id']."'");
               			$reward = "You have earned $cookies cookies from the gacha box! Congratulations";}
                    	
                break;
                case '8':
                    $reward = "You have earned Tanjiro! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('613',$characters) === false){
                        $characters = $account['characters'].',613';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Hiei!");
                    }else
                        $reward = "error";
                break;
                case '9':
                    $reward = "You have earned Broly! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('642',$characters) === false){
                        $characters = $account['characters'].',642';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '10':
                    $reward = "You have earned Gintoki! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('607',$characters) === false){
                        $characters = $account['characters'].',607';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '11':
                    $reward = "You have earned Kakashi! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('625',$characters) === false){
                        $characters = $account['characters'].',625';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '12':
                    $reward = "You have earned Frieza! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('638',$characters) === false){
                        $characters = $account['characters'].',638';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '13':
                    $reward = "You have earned Mayuri! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('617',$characters) === false){
                        $characters = $account['characters'].',617';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '14':
                    $reward = "You have earned Gojo! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('609',$characters) === false){
                        $characters = $account['characters'].',609';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '15':
                    $reward = "You have earned Ichigo! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('624',$characters) === false){
                        $characters = $account['characters'].',624';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '16':
                    $reward = "You have earned Dabi! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('633',$characters) === false){
                        $characters = $account['characters'].',633';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '17':
                    $reward = "You have earned Garou! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('629',$characters) === false){
                        $characters = $account['characters'].',629';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '18':
                    $reward = "You have earned Kurapika! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('628',$characters) === false){
                        $characters = $account['characters'].',628';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '19':
                    $reward = "You have earned Lucy! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('627',$characters) === false){
                        $characters = $account['characters'].',627';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '20':
                    $reward = "You have earned Alice! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('634',$characters) === false){
                        $characters = $account['characters'].',634';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '21':
                    $reward = "You have earned Kaiba! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('631',$characters) === false){
                        $characters = $account['characters'].',631';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '22':
                    $reward = "You have earned Doflamingo! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('641',$characters) === false){
                        $characters = $account['characters'].',641';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '23':
                    $reward = "You have earned Hiei! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('602',$characters) === false){
                        $characters = $account['characters'].',602';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '24':
                    $reward = "You have earned Akame! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('608',$characters) === false){
                        $characters = $account['characters'].',608';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '25':
                    $reward = "You have earned Hawks! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('640',$characters) === false){
                        $characters = $account['characters'].',640';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '26':
                    $reward = "You have earned Shoto! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('639',$characters) === false){
                        $characters = $account['characters'].',639';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '27':
                    $reward = "You have earned Mai! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('643',$characters) === false){
                        $characters = $account['characters'].',643';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '28':
                    $reward = "You have earned Amon! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('635',$characters) === false){
                        $characters = $account['characters'].',635';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '29':
                    $reward = "You have earned Goku Rose! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('645',$characters) === false){
                        $characters = $account['characters'].',645';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '30':
                    $reward = "You have earned Kuzan! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('636',$characters) === false){
                        $characters = $account['characters'].',636';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '31':
                    $reward = "You have earned Katakuri! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('637',$characters) === false){
                        $characters = $account['characters'].',637';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '32':
                    $reward = "You have earned Pegasus! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('632',$characters) === false){
                        $characters = $account['characters'].',632';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '33':
                    $reward = "You have earned Zoro! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('106',$characters) === false){
                        $characters = $account['characters'].',106';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '34':
                    $reward = "You have earned Minato! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('630',$characters) === false){
                        $characters = $account['characters'].',630';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '35':
                    $reward = "You have earned Usopp! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('646',$characters) === false){
                        $characters = $account['characters'].',646';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '36':
                    $reward = "You have earned Natsu! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('648',$characters) === false){
                        $characters = $account['characters'].',648';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '37':
                    $reward = "You have earned Genos! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('649',$characters) === false){
                        $characters = $account['characters'].',649';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '38':
                    $reward = "You have earned Trunks! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('644',$characters) === false){
                        $characters = $account['characters'].',644';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '39':
                    $reward = "You have earned Byakuya! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('647',$characters) === false){
                        $characters = $account['characters'].',647';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                case '40':
                    $reward = "You have earned All Might! Congratulations";
                    $characters = explode(',',$account['characters']);
                    if(array_search('622',$characters) === false){
                        $characters = $account['characters'].',622';
                        $db->query("UPDATE accounts SET characters = '".$characters."' WHERE id = '".$account['id']."'");
                        //$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the event; Gojo!");
                    }else
                        $reward = "error";
                break;
                }
            }else{
            	
            	switch($value){
                case '1':
                	$rate = (double) '0.25';
					$max = 1 / $rate; // 100
					if (mt_rand(0, $max) === 0) {
                    	$cookies = rand(1,3);
						$db->query("UPDATE accounts SET cookies = '".($account['cookies']+$cookies)."' WHERE id = '".$account['id']."'");
               			$reward = "You have earned $cookies cookies from the bc conversion box! Congratulations";
                    	if($cookies == 3)
                    		$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the bc conversion box!");
                    }else
                    	$reward = "Sorry you didn't get lucky! Better luck on the next box!";
                break;
                case '2':
                	$cookies = rand(1,3);
                	$db->query("UPDATE accounts SET cookies = '".($account['cookies']+$cookies)."' WHERE id = '".$account['id']."'");
               		$reward = "You have earned $cookies cookies from the bc conversion box! Congratulations";
                	if($cookies == 3)
                    		$system->sendDiscordMsg("Everyone congratulate ".$account['name']." for getting the max reward from the bc conversion box!");
                break;
                }
            }
        	if($reward == 'error'){
            	return $json['error'] = "You have already earned this reward!";
            }else{
            	if($value == '6' || '8' || '9' || '10' || '11' || '12' || '13' || '14' || '15' || '16' || '17' || '18' || '19' || '20' || '21' || '22' || '23' 
                  || '24' || '25' || '26' || '27' || '28' || '29' || '30' || '31' || '32' || '33' || '34'|| '35' || '36' || '37' || '38' || '39' || '40'){
                // Now get the ingame characters and refresh the list
				$characters = $db->query("SELECT * FROM `characters` ORDER BY who ASC, sort DESC");
				$c = '';
				if ($characters->rowCount() > 0) {

					while ($character = $characters->fetch()) {
			
					// Using account var update every loop since we update fields
					$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");

					// Check if a certain group can't see the character
					if (!empty($character['who'])) {
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						$who = explode(',', $who['who']);
						$key = array_search($account['group'], $who);
						if (isset($key) && $who[$key] != $account['group']) 
							continue;
					}
			
					$skills = explode(',', $character['skills']);
					$f2 = array(preg_replace('/\s+/', '_', $db->fieldFetch('animes',$character['who'],'name')));
					foreach($skills as &$skill){
						$classes = explode(',',$db->fieldFetch('skills',$skill,'classes'));
						foreach($classes as $class){
							if(array_search($db->fieldFetch('classes', $class,'name'), $f2) !== false) continue;
							$f2[] = $db->fieldFetch('classes', $class,'name');
						}
						$effects = explode(',',$db->fieldFetch('skills',$skill,'effects'));
						$replacements = array();
						foreach($effects as $effect){
							$effect = $db->fetch("SELECT * FROM `effects` WHERE `id` ='".$effect."'");
							if($effect){
								foreach($effect as $item => $value){
									if(empty($item) || is_numeric($item))
										continue;
									if(empty($value))
										continue;
									if($item == 'id' || $item == 'duration' || $item == 'target' || $item == 'description')
										continue;
									if($item == 'replace' || $item == 'if'){
										$replaced = explode('|', $value);
										foreach($replaced as $replacing){
											$replacements[] = $replacing;
										}
										continue;
									}
									if(array_search($item, $f2) !== false) 
										continue;
									$f2[] = $item;
								}
							}
						}
						if(!empty($replacements)){
							array_push($replacements, $skills);
						}
					}
					$f2 = implode(' ', $f2);
					// Start decompiling the characters and checking if there unlocked or in the team
					$ucharacters = explode(',', $account['characters']);
					$key = array_search($character['id'], $ucharacters);
					if (isset($key) && $ucharacters[$key] == $character['id']) {
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						$c .= '<div class="'.$f2.'">' . $user->image($character['id'], 'characters', './../../', 'character', '', $who['name'])  . '</div>';
					} else {
					
						$who = $db->fetch("SELECT * FROM animes WHERE id='".$character['who']."'");
						// Check if group of only can be seen if unlocked
						if($system->data('Only') == $character['who'])
							continue;
						$c .= '<div class="'.$f2.'"><p class="locked">' . $user->image($character['id'], 'characters', './../../', 'character', '', $who['name']) . '</p><span class="lock"></span></div>';
					}
					}
				}
				$update['character'][] = $c;
                }
            }
        	$db->query("DELETE FROM `inventory` WHERE `inventory`.`id` = '".$id."'");
			$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'"); 
        	
			$update['gold'] = $account['gold'];
        	$update['cookies'] = $account['cookies'];
        	// Update inventory
        	$inventory = $game->getInventory();
        	$update['inventory'] = $inventory;
        	$update['reward'] = $reward;
			return $json = $update;
		}else{
			return $json['error'] = 'notfound';
		}
    }
///----
	function getTeam() {

        global $db, $json, $account;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }

        $id = $account['id'];
        $check = $db->fetch("SELECT * FROM accounts WHERE id = '" . $id . "'");
        if ($check) {
       		if($check['notified'] == '0')
            	$db->query("UPDATE `accounts` SET `notified` = '01' WHERE `accounts`.`id` = '" . $id . "';");
            $team = explode(',', $check['team']);
            $json['team'] = $team;
        } else {
            $json['error'] = 'invalid';
            return false;
        }
    }
	
	function saveTeam($name) {
		global $db, $json, $account;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
		// Check if you have these characters :turkey
		$characters = explode(',',$account['characters']);
		$count = 0;
		$team = explode(',',$account['team']);
		foreach($team as $slot){
			if(array_search($slot, $characters) !== false)
				$count++;
		}
		if($count !== 3)
			return $json['error'] = 'params';
        $check = $db->query("INSERT INTO `teams` (`account`, `name`, `team`) VALUES ('".$account['id']."','".$name."','".$account['team']."')");
        if ($check->rowCount()>0) {
			$check = $db->fetch("SELECT * FROM `teams` WHERE `account` = '".$account['id']."' ORDER BY `teams`.`id` DESC LIMIT 1");
			$db->query("UPDATE `accounts` SET `equiped_team` = '".$check['id']."' WHERE `accounts`.`id` = '".$account['id']."';");
            $json['team'] = '<p id="team" class="'.$check['id'].' selected">'.$check['name'].' <br>'.$check['wins'].' - '.$check['loses'].' ( '.$check['highest_streak'].' )<span class="delete">Remove</span></p>';
        } else {
            $json['error'] = 'invalid';
            return false;
        }
	}
	
	function selectTeam($id) {
		global $db, $json, $account;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
        $check = $db->fetch("SELECT * FROM `teams` WHERE `account` = '".$account['id']."' AND `id` = '".$id."'");
        if ($check) {
			$characters = explode(',',$account['characters']);
			$count = 0;
			$team = explode(',',$check['team']);
			foreach($team as $slot){
				if(array_search($slot, $characters) !== false)
					$count++;
			}
			if($count !== 3)
				return $json['error'] = 'Locked characters';
			$db->query("UPDATE `accounts` SET `team` = '".$check['team']."',`equiped_team` = '".$check['id']."' WHERE `accounts`.`id` = '".$account['id']."';");
            $json['team'] = explode(',',$check['team']);
        } else {
            $json['error'] = 'invalid';
            return false;
        }
	}
	
	function deleteTeam($id) {
		global $db, $json, $account;

        if (!$account) {
            $json['error'] = 'invalid';
            return false;
        }
        $db->query("DELETE FROM `teams` WHERE `account` = '".$account['id']."' AND `id` = '".$id."'");
	}

    function setTeam($team) {

        global $db, $json, $account, $secure;

        if (!$account) {
            return $json['error'] = 'invalid';
        }

        $id = $account['id'];
        $u = $db->fetch("SELECT * FROM accounts WHERE id = '" . $id . "'");
        if ($u) {

            $team = array_unique(explode(',', $team));
            foreach ($team as $key => $value) {

                if ($key > 2) {
                    unset($team[$key]);
                }

                $value = preg_replace("[^0-9\,]", "", $secure->clean($value));
                $check = $db->fetch("SELECT * FROM characters WHERE id = '" . $value . "'");
                if ($check) {

                    $true = false;
                    $characters = explode(',', $u['characters']);
                    foreach ($characters as $i => $c) {
                        if ($c == $value) {
                            $true = true;
                            break;
                        }
                    }

                    if ($true) {

                        // check group...
                        if (!empty($check['who'])) {
                            $who = $db->fetch("SELECT * FROM animes WHERE id='".$check['who']."'");
							$who = explode(',', $who['who']);
							$key = array_search($account['group'], $who);
							if (isset($key) && $who[$key] != $u['group']) {
                                unset($team[$key]);
                            }
                        }
                    } else {
                        unset($team[$key]);
                    }
                } else {
                    unset($team[$key]);
                }
            }
            $team = implode(',', $team);
            $db->query("UPDATE accounts SET team = '" . $team . "', equiped_team = '0' WHERE id = '" . $id . "'");
        } else {
            
            return $json['error'] = 'invalid';
        }
    }

    function cancelMatch() {

        global $db, $account;
	    $db->query("DELETE FROM `matches` WHERE `matches`.`id-0` = '".$account['id']."' AND `matches`.`timeend` = ''");
    }
	function updateVolume($which = 'player', $volume) {

        global $db, $account;
		if($which == 'player')
			$which = '`mvol` = "'.$volume.'"';
		else
			$which = '`vsfx` = "'.$volume.'"';
	    $db->query("UPDATE `accounts` SET ".$which." WHERE `id` = '".$account['id']."'");
    }

    function checkMatch() {

        global $db, $account, $system;
		$match = $db->query("SELECT * FROM matches WHERE (´id-0´ = '" . $account['id'] . "' OR ´id-1´ = '" . $account['id'] . "') AND timeend = '' AND check = '1' ORDER BY `id` DESC LIMIT 1;");

        if ($match->rowCount() > 0)
			$system->redirect('./battle');
    }

    function getCharacter($id) {

        global $db, $json, $secure, $user, $system, $account;

        $u = $db->fetch("SELECT * FROM accounts WHERE id = '" . $account['id'] . "'");
        if ($u) {

            $check = $db->fetch("SELECT * FROM characters WHERE id = '" . $id . "'");
            if ($check) {

                $true = false;
                $id = $secure->clean($id);
                $characters = explode(',', $u['characters']);
                foreach ($characters as $i => $c) {
                    if ($c == $id) {
                        $true = true;
                        break;
                    }
                }

                if ($true) {

                    // check group...
                    if (!empty($check['who'])) {
                        $who = $db->fetch("SELECT * FROM animes WHERE id='".$check['who']."'");
						$who = explode(',', $who['who']);
						$key = array_search($account['group'], $who);
						if (isset($key) && $who[$key] != $u['group']) {
                            $json['error'] = 'group';
                            return false;
                        }
                    }
                    $count = 0;
                    $skills = explode(',', $check['skills']);
					$keys = array();
					foreach($skills as $_ => $skill){
						$keys[] = $skill;
					}
					$transform = 0;
                    foreach ($skills as $key => $skill) {
                        $scheck = $db->fetch("SELECT * FROM skills WHERE id = '" . $skill . "'");
                        if ($scheck) {
							$effects = explode(',',$scheck['effects']);
							foreach($effects as $effect){
								if($db->fieldFetch('effects',$effect,'transform') !== 'undefined' && $db->fieldFetch('effects',$effect,'transform') !== ''){
									$transform++;
									$json['transformations'] = isset($json['transformations']) ? $this->json['transformations'] .= $user->image($db->fieldFetch('effects',$effect,'transform'), 'characters/slanted', '../.././', 'transformation '.$transform) : $this->json['transformations'] = $user->image($db->fieldFetch('effects',$effect,'transform'), 'characters/slanted', '../.././', 'transformation '.$transform);	
									
								}
								if($db->fieldFetch('effects',$effect,'replace') !== 'undefined' && $db->fieldFetch('effects',$effect,'replace') !== ''){
								$replacements = true;
								$ez = explode('|',$db->fieldFetch('effects',$effect,'replace'));
								
								do{
									if(!empty($salt))
										$ez = $salt;
									$salt = array();
									foreach($ez as $looks){
										if(in_array($looks,$keys)) continue;
										$keys[] = $looks;
										$alt = $db->query("SELECT * FROM skills WHERE id = '".$looks."'");
										if($alt->rowCount() > 0){
											$alt = $alt->fetch();
											
											$json['alts'] = isset($json['alts']) ? $this->json['alts'] .= $user->image($alt['id'], 'skills', '../.././', 'skill') : $this->json['alts'] = $user->image($alt['id'], 'skills', '../.././', 'skill');
											$aeffects = explode(',',$alt['effects']);
											foreach($aeffects as $ae){
												if($db->fieldFetch('effects',$ae,'transform') !== 'undefined' && $db->fieldFetch('effects',$ae,'transform') !== ''){
													$transform++;
													$json['transformations'] = isset($json['transformations']) ? $this->json['transformations'] .= $user->image($db->fieldFetch('effects',$ae,'transform'), 'characters/slanted', '../.././', 'transformation '.$transform) : $this->json['transformations'] = $user->image($db->fieldFetch('effects',$ae,'transform'), 'characters/slanted', '../.././', 'transformation '.$transform);
												}
												if($db->fieldFetch('effects',$ae,'replace') !== 'undefined' && $db->fieldFetch('effects',$ae,'replace') !== ''){			
													$as = explode('|',$db->fieldFetch('effects',$ae,'replace'));
													foreach($as as $askill){
														if(in_array($askill,$keys)) continue;
														$salt[] = $askill;
													}
												}
											}
										}
									}
									if(empty($salt))
										$replacements = false;
								}while($replacements === true);
								}
							}
							$json['skills'] = isset($json['skills']) ? $this->json['skills'] .= $user->image($skill, 'skills', '../.././', 'skill') : $this->json['skills'] = $user->image($skill, 'skills', '../.././', 'skill') ;
                        } else {
                            $count++;
                            $json['skills'] .= 'undefined';
                        }
                    }
					if(!empty($check['passive'])){
						$passives = explode(',', $check['passive']);
						foreach($passives as $passive){
							$scheck = $db->fetch("SELECT * FROM skills WHERE id = '" . $passive . "'");
							if ($scheck) {
								$json['passives'] = isset($json['passives']) ? $this->json['passives'] .= $user->image($passive, 'skills', '../.././', 'skill passive') : $this->json['passives'] = $user->image($passive, 'skills', '../.././', 'skill passive') ;
							}
						}
						
					}
                    // Else with error message here, which is not in yet...
                    if ($count > 0) {
                        $json['error'] = 'skills';
                        $json['name'] = $check['name'];
                        return false;
                    }
					if($account['tpl'] !== 'default'){
						$json['slanted'] = $user->image($id, 'characters/slanted', '../.././');
					}
					$json['character'] = $user->image($id, 'characters', '../.././', 'original');
					$json['name'] = $check['name'];
					$json['description'] = $check['desc'];
					$json['stats'] = '<p style="color: #45bc59;">HP: '.$check['health'].'</p>
					<p style="color: #32c9e9;">Mana: '.$check['mana'].'</p>';
				} else {

                    $json['error'] = 'character';
                    return false;
                }
            } else {
                $json['error'] = 'undefined';
                return false;
            }
        } else {
            $json['error'] .= 'invalid';
            return false;
        }
    }

    function getSkill($id) {

        global $db, $json, $secure, $user, $account, $_path;

        if (strstr($id, '/') === false) {
            $json['error'] .= 'params';
            return false;
        }
        $id = explode('/', $id);
        if (empty($id[1])) {
            $json['error'] .= 'undefined';
            return false;
        }
        if (empty($id[0])) {
            $json['error'] .= 'undefined skill';
            return false;
        }

        $u = $db->fetch("SELECT * FROM accounts WHERE id = '" . $account['id'] . "'");
        if ($u) {

            $i = $secure->clean($id[1]);
            $s = $secure->clean($id[0]);
            $check = $db->fetch("SELECT * FROM characters WHERE id = '" . $i . "'");
            if ($check) {

                $true = false;
                $characters = explode(',', $u['characters']);
                foreach ($characters as $key => $c) {
                    if ($c == $i) {
                        $true = true;
                        break;
                    }
                }

                if ($true) {

                    // check group...
                    if (!empty($check['who'])) {
                        $who = $db->fetch("SELECT * FROM animes WHERE id='".$check['who']."'");
						$who = explode(',', $who['who']);
						$key = array_search($account['group'], $who);
						if (isset($key) && $who[$key] != $u['group']) {
							
                            $json['error'] = 'group';
                            return false;
                        }
                    }

                    $true = false;
					if(!empty($check['passive']))
						$check['skills'] = (empty($check['skills']))?$check['passive']:$check['skills'].','.$check['passive'];
                    $skills = explode(',', $check['skills']);
					
                    foreach ($skills as $key => $value) {
						$effects = explode(',',$db->fieldFetch('skills',$value,'effects'));
						foreach($effects as $effect){
							if($db->fieldFetch('effects',$effect,'replace') !== 'undefined' && $db->fieldFetch('effects',$effect,'replace') !== ''){
							$replacements = true;
							$ez = explode('|',$db->fieldFetch('effects',$effect,'replace'));
								
							do{
								if(!empty($salt))
									$ez = $salt;
									$salt = array();
								foreach($ez as $looks){
									if(in_array($looks,$keys)) continue;
									$keys[] = $looks;
									$alt = $db->query("SELECT * FROM skills WHERE id = '".$looks."'");
									if($alt->rowCount() > 0){
										$alt = $alt->fetch();
										if ($alt['id'] == $s) {
											$true = true;
											$replacements = false;
										}
										$aeffects = explode(',',$alt['effects']);
										foreach($aeffects as $ae){
											if($db->fieldFetch('effects',$ae,'replace') !== 'undefined' && $db->fieldFetch('effects',$ae,'replace') !== ''){			
												$as = explode('|',$db->fieldFetch('effects',$ae,'replace'));
												foreach($as as $askill){
													if(in_array($askill,$keys)) continue;
													$salt[] = $askill;
												}
											}
										}
									}
								}
								if(empty($salt))
									$replacements = false;
							}while($replacements === true);
							}
							if($true == true)
								break;
						}
							
                        if ($value == $s) {
                            $true = true;
                            break;
                        }
                    }

                    if ($true) {

                        $check = $db->fetch("SELECT * FROM skills WHERE id = '" . $s . "'");
                        if ($check) {
							$check['classes'] = explode(',',$check['classes']);
							foreach($check['classes'] as $class){
								if($db->fieldFetch('classes',$class,'name') == 'undefined') continue;
								$check['archive'] .= $user->image($db->fieldFetch('classes',$class,'name'), 'classes', '../.././', 'skill-class" title="Skill Class '.$db->fieldFetch('classes',$class,'name').'"');
							}
                            $json['skill'] = $user->image($s, 'skills', '../.././', 'skill');
							$json['name'] = $check['name'];
                            $json['description'] = $check['desc'];
							$json['stats'] = '<p style="color: #b13232;">Cooldown: '.$check['cooldown'].'</p>
							<p style="color: #32c9e9;">Mana Cost: '.$check['cost'].'</p>'.$check['archive'];
                        } else {
                            $json['error'] .= 'undefined skill';
                            return false;
                        }
                    } else {
                        $json['error'] .= 'undefined character skill';
                        $json['name'] = $check['name'];
                        return false;
                    }
                } else {
                    $json['error'] .= 'character';
                    return false;
                }
            } else {
                $json['error'] .= 'undefined';
                return false;
            }
        } else {
            $json['error'] .= 'invalid';
            return false;
        }
    }

    function checkStatus($update = false) {
        global $db, $json, $system, $account, $user, $game, $_POST;
		$rows = $db->query("SELECT * FROM `matches` WHERE `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') ORDER BY `id` DESC LIMIT 1;");
        if ($rows->rowCount() > 0){
			$rows = $rows->fetch();
        	include 'gameHandler.php';
			$first = ($account['id'] == $rows['id-0']) ? true: false;
			if($first == false && $rows['status'] == 'winner')
				$rows['status'] = 'loser';
			if($first == false && $rows['status'] == 'loser')
				$rows['status'] = 'winner';
			$turn = $rows['time'];
			$turns = explode('/', $turn);
			$turn = end($turns);
			$turn = explode('=', $turn);
			$time = $turn[1];
			$turn = $turn[0];
			$json['turn'] = $turn;
			$me = false;
        	$arguments = array($rows['status'],
                $turn,
                $time,
                $first,
				$me,
                $rows);
			if ($first == true && $turn % 2 != 0) $me = true;
			if ($first != true && $turn % 2 == 0) $me = true;
        	if($rows['status'] === 'checking' && !$me && $rows['type'] === 'ai'){
    			$rows['status'] = 'calculating';
            	$rows['me'] = false;
                new Ingame ('Calculate', $arguments);
            	$update = true;
            	$rows = $db->fetch("SELECT * FROM `matches` WHERE `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') ORDER BY `id` DESC LIMIT 1;");
    		}
        	$json['status'] = $rows['status'];
			if($update){
				$arguments = array($rows['status'],
                $turn,
                $time,
                $first,
				$me,
                $rows);
				$json['update'] = new Ingame('getUI', $arguments);
			}
		}else 
			$json['result'] = false;
		return $json;
    }

    function verifySkill() {
        global $db, $json, $system, $account, $user, $game, $_POST;
		$rows = $db->query("SELECT * FROM `matches` WHERE `check` = '1' AND `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') ORDER BY `id` DESC LIMIT 1");

		if ($rows->rowCount() > 0) {
			$match = $rows->fetch();
            include 'gameHandler.php';
            $turn = $match['time'];
            $turns = explode('/', $turn);
            $turn = end($turns);
            $turn = explode('=', $turn);
            $time = $turn[1];
            $turn = $turn[0];
            $arguments = array($match['status'],
                $turn,
                $time,
                (($match['id-0'] == $account['id']) ? true : false), true,
                $match);
            new Ingame('verifySkill', $arguments);
        }
    }

    function getTargets() {
        global $db, $json, $system, $account, $user, $game, $_POST;
		$rows = $db->query("SELECT * FROM `matches` WHERE `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "')");
		if ($rows->rowCount() > 0) {
			$match = $rows->fetch();
            include 'gameHandler.php';
            $turn = $match['time'];
            $turns = explode('/', $turn);
            $turn = end($turns);
            $turn = explode('=', $turn);
            $time = $turn[1];
            $turn = $turn[0];
            $arguments = array($match['status'],
                $turn,
                $time,
                (($match['id-0'] == $account['id']) ? true : false), true,
                $match);
            new Ingame('getTargets', $arguments);
        }
    }

    function checkTarget() {
        global $db, $json, $system, $account, $user, $game, $_POST;
		$rows = $db->query("SELECT * FROM `matches` WHERE `timeend` = '' AND (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "')");
		if ($rows->rowCount() > 0) {
			$match = $rows->fetch();
            include 'gameHandler.php';
            $turn = $match['time'];
            $turns = explode('/', $turn);
            $turn = end($turns);
            $turn = explode('=', $turn);
            $time = $turn[1];
            $turn = $turn[0];
            $arguments = array($match['status'],
                $turn,
                $time,
                (($match['id-0'] == $account['id']) ? true : false), true,
                $match);
            new Ingame('checkTarget', $arguments);
        }
    }
	function return_($what, $value) {
        global $db, $user, $system, $STYLE, $json, $_path, $_POST;
        if ($what == "undefined" || $value == "undefined")
            return $json['result'] = false;
        if($what == 'fin')
			return $this->resultMatch();
		elseif($what == 'surrender')
			return $this->surrender();
		else
            return $json['result'] = false;
        return $json['result'] = $tpl;
    }

	function resultMatch(){
		
		global $db, $account, $STYLE, $system, $json, $user, $system;
		$rows = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND (`status` ='winner' OR `status` ='loser') ORDER BY id DESC LIMIT 1");
		if($rows->rowCount() > 0){
			$rows = $rows->fetch();
			$ihave = $rows['status'];
			$first = false;
			if($rows['id-0'] == $account['id']) 
				$first = true;
			if($first === false)
				$ihave = (($ihave == 'winner') ? 'loser': 'winner');
			$checking = '`check-1` = "1" ';
			if($first)
				$checking = '`check-0` = "1" ';
			$db->query("UPDATE `matches` SET ".$checking."WHERE id = '".$rows['id']."'");
			$players = array();
			$players[] = $rows['id-0'];
			$players[] = $rows['id-1'];
			if($rows['resulted'] == 0 && $rows['check-0'] == '0' && $rows['check-1'] == '0'){
				$r = '';
				foreach($players as $key => $player){
					$a = $db->fetch("SELECT * FROM accounts WHERE id = '".$player."'");
					$result = $rows['status'];
					$whois = false;
					if($player == $rows['id-0']) 
						$whois = true;
					if($whois == false)
						$result = ($result == 'winner') ? 'loser': 'winner';
					if($rows['type'] == 'ladder'||$rows['type'] == 'quick'||$rows['type'] == 'ai'){
						switch($result){
							case 'winner':
								$opponent = $db->query("SELECT * FROM `accounts` WHERE `id` = '".(($key == 0) ? $players[1] : $players[0])."'")->fetch();
								$exp = 0;
								if($rows['type'] == 'ladder'){
									$rank1 = $user->level($a['experience']);
									$rank2 = $user->level($opponent['experience']);
									if($a['experience'] <= $db->fieldFetch('levels', 16, 'experience')){ // for ranks of diamond and below
										if($rank1 == $rank2){
											$exp+=250;  // if both are same level, exp gain starts from 250
											// you want with same level the exp gain to not easily go over 380
											if($a['streak']>0) $exp+= $a['streak']*5;
											if($opponent['streak']>0) $exp+= $opponent['streak']*5;
											// if you or enemy has a streak, then gain 5 exp for each win of the streaks
										}else if($rank1 < $rank2) { // if you are a smaller level, exp gain stats from 250 + 50* level difference
											$exp+=250;
											$levelDif = ($rank2-$rank1);
											$exp+= $levelDif * 50;
											// on average up to here exp gain is 400 if level difference is 5. We want 5 level difference to usually result in 600 exp, so the other factors should average at 200 exp total
											if($a['streak']>0) $exp+= $a['streak']*5;
											if($opponent['streak']>0) $exp+= $opponent['streak']*10;
											// if you or enemy has a streak, then gain 5 exp for each of yours and 10 for each of enemy's
										}else{ // if you are bigger level, exp gain starts from 50 - 10* level difference where if the difference is above 5 it counts as if it's 5
											$exp+=160;
											if($rank1-$rank2>5) $levelDif = 5;
											else $levelDif = ($rank1-$rank2);
											$exp+=  $levelDif * 10;
											// if you are bigger level than your opponent, then to gain better exp you'll need a streak
											if($a['streak']>0) $exp+= $a['streak']*10;
											if($opponent['streak']>0) $exp+= $opponent['streak']*5;
											// if you or enemy has a streak, then gain 10 exp for each win of your streak and 5 for each of enemy's
										}
									}else{ // if you are Crimson and above
										if($rank1 == $rank2){
											$exp+=300;  // if both are same level, exp gain starts from 300
											if($a['streak']>0) $exp+= $a['streak']*10;
											if($opponent['streak']>0) $exp+= $opponent['streak']*10;
										}
										elseif($rank1 < $rank2) { // if you are a smaller level, exp gain stats from 300 + 50* level difference
											$exp+=300;
											$levelDif = ($rank2-$rank1);
											$exp+= $levelDif * 70;
											if($a['streak']>0) $exp+= $a['streak']*10;
											if($opponent['streak']>0) $exp+= $opponent['streak']*15;
										}else{ // if you are bigger level, exp gain starts from 200 - 10* level difference where if the difference is above 10 it counts as if it's 10
											$exp+=200;
											if($rank1-$rank2>10) 
												$levelDif = 10;
											else 
												$levelDif = ($rank1-$rank2);
											$exp+= $levelDif * 10;
											if($a['streak']>0) $exp+= $a['streak']*15;
											if($opponent['streak']>0) $exp+= $opponent['streak']*10;
										}
									}
									$turn = $rows['time'];
									$turns = explode('/', $turn);
									$turn = end($turns);
									$turn = explode('=', $turn);
									$turn = $turn[0];
									if($turn == 1) 
										$exp-=150; // if someone surrenders turn 1, gain 150 less exp
									else if($turn < 10) 
										$exp+=25; //if it ends before 10 turns pass ( 4 from one and 5 from other player) gain only 25 bonus exp
									else 
										$exp+=50; // if game was longer, give 50 more exp
									$champion = $db->query("SELECT * FROM `accounts` ORDER BY `accounts`.`experience` DESC LIMIT 1")->fetch();
									if($opponent['id'] == $champion['id']) $exp += 220; // if facing the champion, bonus 220 exp
									if($exp < $system->data('Min_Earn')) $exp = $system->data('Min_Earn'); // if exp< min exp, exp = min exp
									if($exp > $system->data('Max_Earn')) $exp = $system->data('Max_Earn'); // if exp> max exp, exp = max exp
									if($a['id'] == $champion['id'] && $exp<=190) $exp= 190; // if you are the champion your minimum exp gain is 190 instead of 160
									$extra = 1;
									if(!empty($a['boost-xp']) && $a['boost-xp'] > 1)
										$extra = $a['boost-xp'];
									$exp = ($exp*$extra);
									$a['experience'] += $exp;
									
									$extra = 1;
									if(!empty($a['boost-bc']) && $a['boost-bc'] > 1)
										$extra = $a['boost-bc'];
                                    // BC gain per win system:
                                    $rankMe = $user->level($a['experience']);
									$rankEnemy = $user->level($opponent['experience']);
                                    if($rankMe<$rankEnemy) $levelDifference = ($rankEnemy-$rankMe); // if you are lower level, difference in levels is levelDif
                                    else $levelDifference = 1; // if you are higher level it's always 1
                                    // bellow is BC system formula
                                     if($rankEnemy>=10) $gold = ($system->data('Gold_Earn')*$extra+($levelDifference*rand(4,10))+rand(45+$rankMe,45+($rankEnemy)*5));
                                     else $gold = ($system->data('Gold_Earn')*$extra+($levelDifference*rand(4,10))+rand(45+$rankMe,95));
                                    
									// Check if clan
									$cexp = 0;
									$cbc = 0;
									$clan = $db->query("SELECT * FROM `clan-members` WHERE account_id = '" . $a['id'] . "'");
									if($clan->rowCount()>0){
										$clan = $clan->fetch();
										$cexp = round($exp/10);
										$cbc = round($gold/10);
										//Check if trial
										$trial = $db->fetch("SELECT * FROM `clan-ranks` WHERE id = '" . $clan['rank'] . "'");
										if($trial['privelage'] != '1')
											$db->query("UPDATE `clans` SET `experience` = `experience`+'".$cexp."',`bc` = `bc`+'".$cbc."', `wins` = `wins`+'1'  WHERE `clans`.`id` = '".$clan['clan_id']."';");
										else{
											$cexp = 0;
											$cbc = 0;
										}
										$db->query("UPDATE `clan-members` SET `wins` = `wins`+'1' WHERE `clan-members`.`id` = '".$clan['id']."';");
									}
									if(!empty($r))
										$r .= '/';
									$r .= $key.':G'.$gold.'|E'.$exp.'|BBC'.$extra.'|CLAN'.$cexp.'+'.$cbc;
									if($a['streak'] < 0)
										$a['streak'] = 0;
									$streak = $a['streak']+1;
									$highest_streak = $a['highest_streak'];
									if($streak > $a['highest_streak'])
										$highest_streak = $streak;
									$db->query("UPDATE `accounts` SET `wins` =  `wins`+1,`streak` =  '".$streak."',`highest_streak` =  '".$highest_streak."',`experience` = '".$a['experience']."', `gold` = `gold`+$gold WHERE `id` = '".$a['id']."'");

								}
								// Update team stats
								if($a['equiped_team'] != 0){
									$highest_streak = 0;
									if($db->fieldFetch('teams', $a['equiped_team'], 'streak')+1 > $db->fieldFetch('teams', $a['equiped_team'], 'highest_streak'))
										$highest_streak = $db->fieldFetch('teams', $a['equiped_team'], 'streak')+1;
									$db->query("UPDATE `teams` SET `wins` =  `wins`+1,`streak` = `streak`+1, `highest_streak` =  '".$highest_streak."' WHERE `id` = '".$a['equiped_team']."'");
								}
                        		// Reward for Christmas event
                        		if($rows['type'] == 'ai' && $whois === true){
                                	// Check difficulty
                                	$cookieTotal = 1;
                                	$difficulty = $rows['difficulty'];
                                	if($difficulty == 2)
                                    	$cookieTotal = 2;
                                	if($difficulty == 3){
                                    	$cookieTotal = 3;
                    					$system->sendDiscordMsg("Everyone congratulate ".$account['name'].", he has beaten Santa on Legendary :beer: What a god!");
                   
                                    }
                                	if($cookieTotal < 3){
                                    	$min = 0;
                                    	$max = 25;
                                    	if($difficulty == 2){
                                        	$min = 25;
                                        	$max = 50;
                                        }
                                    	$numChance = rand($min, 100);
                                    	if($numChance < $max){
                                        	$cookieTotal = 0;
                                        }
                                    }
                                	$cookies = $a['cookie'];
                                	$db->query("UPDATE `accounts` SET `cookies` = `cookies`+$cookieTotal WHERE `id` = '".$a['id']."'");
                                	$r .= $key.':COOKIES'.$cookieTotal;
                                }
						
								break;
					
							case 'loser':
								$opponent = $db->query("SELECT * FROM `accounts` WHERE `id` = '".(($key == 0) ? $players[1] : $players[0])."'")->fetch();
								$exp = 0;
								if($rows['type'] == 'ladder'){
									// 60 min 300 max
									$rank1 = $user->level($a['experience']);
									$rank2 = $user->level($opponent['experience']);
									if($a['experience'] <= $db->fieldFetch('levels', 16, 'experience')){ // for ranks of diamond and bellow
										if($rank1 == $rank2){
											$exp+=150;  // if both are same level, exp loss starts from 150
											if($a['streak']>0) $exp-= $a['streak']*2;
											if($opponent['streak']>0) $exp-= $opponent['streak']*2;
											// lose less exp based on streaks
										}elseif($rank1 < $rank2) { // if you are a smaller level, exp loss stats from 100 - 10* level difference
											$exp+=100;
											$levelDif = ($rank2-$rank1);
											$exp-= $levelDif * 10;
											// on average up to here exp gain is 400 if level difference is 5. We want 5 level difference to usually result in 600 exp, so the other factors should average at 200 exp total
											if($a['streak']>0) $exp-= $a['streak']*2;
											if($opponent['streak']>0) $exp-= $opponent['streak']*4;
											// lose less exp based on streaks
										}else{ // if you are bigger level, exp loss starts from 300 + 10* level difference where if the difference is above 5 it counts as if it's 5
											$exp+=300;
											if($rank1-$rank2>5) 
												$levelDif = 5;
											else 
												$levelDif = ($rank1-$rank2);
											$exp+= $levelDif * 10;
											if($a['streak']>0) $exp-= $a['streak']*4;
											if($opponent['streak']>0) $exp-= $opponent['streak']*2;
											// lose less exp based on streaks
										}
									}else{ // if you are Crimson and above
										if($rank1 == $rank2){
											$exp+=200;  // if both are same level, exp loss starts from 200
											if($a['streak']>0) $exp-= $a['streak']*4;
											if($opponent['streak']>0) $exp-= $opponent['streak']*4;
											// lose less exp based on streaks
										}elseif($rank1 < $rank2) { // if you are a smaller level, exp loss stats from 100 - 30* level difference
											$exp+=100;
											$levelDif = ($rank2-$rank1);
											$exp-= $levelDif * 30;
											if($a['streak']>0) $exp-= $a['streak']*2;
											if($opponent['streak']>0) $exp-= $opponent['streak']*4;
											// lose less exp based on streaks
										}else{ // if you are bigger level, exp loss starts from 300 + 5* level difference where if the difference is above 10 it counts as if it's 10
											$exp+=300;
											if($rank1-$rank2>10) 
												$levelDif = 10;
											else 
												$levelDif = ($rank1-$rank2);
											$exp+= $levelDif * 5;
											if($a['streak']>0) $exp-= $a['streak']*4;
											if($opponent['streak']>0) $exp-= $opponent['streak']*4;
											// lose less exp based on streaks
										}
									}
									$turn = $rows['time'];
									$turns = explode('/', $turn);
									$turn = end($turns);
									$turn = explode('=', $turn);
									$turn = $turn[0];
									if($turn == 1) 
										$exp+=500; // if someone surrenders turn 1, they lose 500 more exp (meaning they will always end up losing the max loss exp
									elseif($turn < 10) 
										$exp-=15; //if it ends before 10 turns pass ( 4 from one and 5 from other player) lose 15 less exp
									else 
										$exp-=25; // if game was longer, lose 25 less exp
									$champion = $db->query("SELECT * FROM `accounts` ORDER BY `accounts`.`experience` DESC LIMIT 1")->fetch();
									if($opponent['id'] == $champion['id']) $exp = 60; // if facing the champion, you lose the minimum amount of exp always
									if($exp < 60) $exp = 60; // if exp< min exp, exp = min exp
									if($exp > 300) $exp = 300; // if exp> max exp, exp = max exp
									if($a['id'] == $champion['id'] && $exp<=320) $exp= 320; // if you are the champion your maximum exp loss is 320 instead of 300
									$a['experience'] -= $exp;
									$gold = $system->data('Gold_Lose');
									// Check if clan
									$cexp = 0;
									$clan = $db->query("SELECT * FROM `clan-members` WHERE account_id = '" . $a['id'] . "'");
									if($clan->rowCount()>0){
										$clan = $clan->fetch();
										$cexp = round($exp/15);
										//Check if trial
										$trial = $db->fetch("SELECT * FROM `clan-ranks` WHERE id = '" . $clan['rank'] . "'");
										if($trial['privelage'] != '1')
											$db->query("UPDATE `clans` SET `experience` = `experience`-'".$cexp."', `loses` = `loses`+1 WHERE `clans`.`id` = '".$clan['clan_id']."';");
										else{
											$cexp = 0;
										}
										if(($db->fieldFetch('clans', $clan['clan_id'],'experience')-$cexp) < 0)
											$db->query("UPDATE `clans` SET `experience` = '0' WHERE `clans`.`id` = '".$clan['clan_id']."';");
										$db->query("UPDATE `clan-members` SET `loses` = `loses`+'1' WHERE `clan-members`.`id` = '".$clan['id']."';");
									}
									if($a['experience'] <0)
										$a['experience'] = 0;
									if(!empty($r))
										$r .= '/';
									$r .= $key.':G'.$gold.'|E'.$exp.'|CLAN'.$cexp;
									if($a['streak'] > 0)
										$a['streak'] = 0;
									$db->query("UPDATE `accounts` SET `loses` = `loses`+1, `streak` = '".$a['streak']."'-1,`experience` = '".$a['experience']."', `gold` = `gold`+$gold WHERE `id` = '".$a['id']."'");						
								}
								// Update team stats
								if($a['equiped_team'] != 0){
									$streak = 0;
									if($db->fieldFetch('teams', $a['equiped_team'], 'streak')-1 < 0)
										$streak = $db->fieldFetch('teams', $a['equiped_team'], 'streak')-1;
									$db->query("UPDATE `teams` SET `loses` =  `loses`+1,`streak` = '".$streak."' WHERE `id` = '".$a['equiped_team']."'");

								}
								
								break;
						}
						if($rows['type'] == 'ladder'||$rows['type'] == 'ai'){
							$characters = explode(',',$a['team']);
							foreach($characters as $character){
								if($result == 'winner')
									$db->query("UPDATE characters SET `wins` =  `wins`+1 WHERE `id` = '".$character."'");
								else
									$db->query("UPDATE characters SET `loses` =  `loses`+1 WHERE `id` = '".$character."'");
							}
						}
					}
				}	
            	if($rows['type'] == 'ai')
                	$endThis = ", `timeend` = '".time()."', `check-1` = '1'";
				$db->query("UPDATE `matches` SET `resulted` = '1', `reward` = '".$r."' $endThis WHERE id = '".$rows['id']."'");
			}else{
				//Update and check rows
				$rows = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `check` = '1' AND (`status` ='winner' OR `status` ='loser') ORDER BY id DESC LIMIT 1");
				$rows = $rows->fetch();
				if($rows['check-0'] == 1 && $rows['check-1'] == 1)
					$db->query("UPDATE `matches` SET `timeend` = '".time()."' WHERE id = '".$rows['id']."'");
			}
			$rows = $db->fetch("SELECT * FROM `matches` WHERE id = '".$rows['id']."' LIMIT 1");
			$rewarded = '';
			if(!empty($rows['reward'])){
				$rewards = explode('/', $rows['reward']);
				foreach($rewards as $reward){
					$k = substr($reward, 0, 1);
					if($rows['id-'.$k] !== $account['id']) continue;
					$r = substr($reward, strpos($reward,':'));
					$r = explode('|', $r);
					foreach($r as $m){
                    	if(strpos($m, 'COOKIES') !== false){
                        	$difficulty = $rows['difficulty'];
                        	if($difficulty == 1)
                            	$difficulty = "Easy";
                        	if($difficulty == 2)
                            	$difficulty = "Medium";
                        	if($difficulty == 3)
                            	$difficulty = "Legendary";
                        	$cookieTotal = substr($m, 8);
                        	if($cookieTotal != '0')
                        		$rewarded .='<p>You have earned '.$cookieTotal.' cookies <img src="https://www.anime-blast.com/tpl/christmas/css/images/cookie.png" />! <br/>Congratulations for beating Santa on '.$difficulty.' mode</p>';
                        	continue;
                        }
						if(strpos($m, 'E') !== false){
							if(substr($m, strpos($m, 'E')+1) <= 0)
								continue;
							$rewarded .= '<p>You have '.(($ihave == 'loser')?'lost':'won').' '.substr($m, strpos($m, 'E')+1).' XP points!</p>';
						}elseif(strpos($m, 'G') !== false){
							if(substr($m, strpos($m, 'G')+1) <= 0)
								continue;
							$rewarded .='<p>You have earned '.substr($m, strpos($m, 'G')+1).' Blast Coins!</p>';
						}elseif(strpos($m, 'CLAN') !== false){
							$exp = substr($m, 4);
							$gold = 0;
							if(strpos($exp, '+') !== false){
								$gold = substr($exp, strpos($exp, '+')+1);
								$exp = substr($exp, 0, strpos($exp, '+'));
							}
							if($exp > 0){
								$rewarded .='<p>You have '.(($ihave == 'loser')?'lost':'won').' '.$exp.' XP points for your clan!</p>';
							}
							if($gold > 0){
								$rewarded .='<p>You have earned '.$gold.' Blast Coins for your clan!</p>';
							}
						}elseif(strpos($m, 'BBC') !== false){
							if(substr($m, strpos($m, 'BBC')+1) <= 0)
								continue;
							if(substr($m, strpos('BBC')+3) != 1)
								$rewarded .='<p>Blast Coin Boost x'.substr($m, strpos('BBC')+3).'!</p>';
						}
					}
				}
			}
			if(empty($rewarded))
				$rewarded = 'You have not earned a reward...';
			$json['title'] = (($ihave == 'winner')?'You have won!':'You have lost!');
			$json['result'] = $rewarded;
			$json['status'] = $ihave;
			if($account['tpl'] === "default"){
				$json['title'] = (($ihave == 'winner')?'You have won!':'You have lost!');
				$tpl = $STYLE->open($ihave.'.tpl');
					$tpl = $STYLE->tags($tpl, array(
						"TYPE" => $rows['type'],
					"OPPONENT" => $db->fieldFetch('accounts', ($first == false) ? $rows['id-0'] : $rows['id-1'], 'name') ,
					"REWARDS" => $rewarded));
				$json['result'] = $tpl;
				$json['status'] = $ihave;
			}
			return $json;
		
		}else
			return false;
	}
	function surrender(){
		
		global $db, $account, $STYLE, $system, $json, $_POST;
		$rows = $db->query("SELECT * FROM `matches` WHERE (`id-0` ='" . $account['id'] . "' OR `id-1` ='" . $account['id'] . "') AND `timeend` = '' AND `check` = '1' ORDER BY id DESC LIMIT 1");
		if($rows->rowCount() > 0){
			$rows = $rows->fetch();
			if($rows['status'] == 'loser' || $rows['status'] == 'winner')	
				return false;
			$first = false;
			if($rows['id-0'] == $account['id']) 
				$first = true;
			$resulting = (($first===true)?'loser':'winner');
			if(isset($_POST['i']) && $_POST['i'] == 'win'){
				if($resulting == 'loser')
					$resulting = 'winner';
				else 
					$resulting = 'loser';
			}
			$db->query("UPDATE `matches` SET `status` = '".$resulting."' WHERE `id` = '".$rows['id']."'");
			return $json['result'] = true;
		}else
			return false;
	}
}
