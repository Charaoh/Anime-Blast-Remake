<?php
if(!$account)
	$system->redirect('./');
$tpl = $STYLE->open('shop.tpl');
// Generate Global Menu
$global_menu = '';
$tpl = str_replace($global_menu, '', $tpl);

if(isset($_GET['buy'])){
	$item = $secure->clean($_GET['buy']);
	$item = $db->query("SELECT * FROM sales WHERE id='".$item."'");
	if($item->rowCount() > 0){
		
		$item = $item->fetch();
		// Check quantity, value
		if($item['seller'] == '-1'){
			// Check discount 
			$discount = $system->data('discount');
			if($discount !== '0'){
				// There is a discount happening!!! 
				$percent = str_replace('%', '', $discount);
				$item['value'] = $item['value']-($item['value']*($percent/100));
			}
		}
		if(($account['gold'] - $item['value']) < 0){
			$system->message(L_ERROR, 'Not enough gold!', './?s=shop', 'Back to shop');
		}
		if($item['quantity'] > 0){
			// Check for account limitation
			if($item['limit'] > 0){
				$count = 0;
				//Search for the user's purchases
				if(!empty($item['sold'])){
					$selled = explode(',',$item['sold']);
					foreach($selled as $s){
						$buy = substr($s, 0, strpos($s,'='));
						if($account['id'] == $buy)
							$count++;
					}
				}
				if(abs($item['limit'] - $count) == 0)
					$system->message('Error on purchase', 'You have already purchased this the ammount allowed!', './?s=shop', 'Back to shop');
				
			}
				
					// Update set the new quantity of the item and the new seller
					$new = $account['id'].'='.time();
					if(!empty($item['sold']))
						$new = $item['sold'].','.$new;
					$quantity = $item['quantity']-1;
					$db->query("UPDATE sales SET quantity = '".$quantity."', sold = '".$new."' WHERE id = '".$item['id']."'");
					// Now give the item to the user, if characters give, if gold give, if experience...
					$items = explode(',', $item['items']);
					foreach($items as $me){
						$it = $db->fetch("SELECT * FROM items WHERE id='".$me."'");
						switch($it['name']){
							case 'character':
								$c = $db->fetch("SELECT * FROM characters WHERE id='".$it['value']."'");
								if($c){
									// Check if I dont have this character
									$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
									$characters = explode(',',$account['characters']);
									if(array_search($c['id'],$characters) == false){
										$db->query("UPDATE accounts SET characters = '".$account['characters'].','.$c['id']."' WHERE id = '".$account['id']."'");
										$db->query("INSERT INTO inventory (account,item,post) VALUES ('".$account['id']."', '".$it['id']."','".$item['id']."')");
									}
								}
							break;
							case 'gold':
								$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
								$account['gold'] = $account['gold']+$it['value'];
								$db->query("UPDATE accounts SET gold = '".$account['gold']."' WHERE id = '".$account['id']."'");
								$db->query("INSERT INTO inventory (account,item,post) VALUES ('".$account['id']."', '".$it['id']."','".$item['id']."')");
							break;
							case 'experience':
								$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
								$account['experience'] = $account['experience']+$it['value'];
								$db->query("UPDATE accounts SET experience = '".$account['experience']."' WHERE id = '".$account['id']."'");
								$db->query("INSERT INTO inventory (account,item,post) VALUES ('".$account['id']."', '".$it['id']."','".$item['id']."')");
							break;
						}
					}
					$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
					$db->query("UPDATE accounts SET gold = '".($account['gold']-$item['value'])."' WHERE id = '".$account['id']."'");
					$seller = $db->fetch("SELECT * FROM accounts WHERE id='".$item['seller']."'");
					if($seller){
						$db->query("UPDATE accounts SET gold = '".($seller['gold']+$item['value'])."' WHERE id = '".$seller['id']."'");
						//Notify the user!
						$system->mail($seller['id'], $account['id'], 'I bought your item!', 'Hi mate! I have just purchased the item <b>"'.$item['title'].'"</b> from you! You have earned '.$item['value'].' BC. Thanks!');
					}
					$system->message('Successful purchase!', 'You have successfully purchased this item!', './?s=shop', 'Back to shop');
				
			}
		
	}
}
/*if(isset($_GET['mode']) && $_GET['mode'] == 'sell'){
	if(($account['gold'] - $system->data('shop_cost')) < 0)
		$system->message(L_ERROR, 'Not enough gold to post items, the requirement is '.$system->data('shop_cost'), './?s=shop', 'Back');
	if(isset($_POST['Submit'])){
		// Check for items selected
		if(empty($_POST['item_list']))
			$system->message(L_ERROR, 'Please select an item..', './?s=shop&mode=sell', 'Back');
		// Check for the date
		/* if(empty($_POST['limit']) || $_POST['limit'] == $system->time(time(),'Y-m-d'))
			$system->message(L_ERROR, 'Please choose a valid date, this item should be posted 1 more day', './?s=shop&mode=sell', 'Back');
		 // Check for the cost
		if(empty($_POST['cost']))
			$system->message(L_ERROR, 'Please define a cost for these items..', './?s=shop&mode=sell', 'Back');
		$cost = $secure->clean($_POST['cost']);
		/*$limit = $secure->clean($_POST['limit']);
		//$description = (empty($_POST['message'])) ? $secure->clean($_POST['message']) : $system->data('default_message');
		$title = '';
		$count = 0;
		$list = '';
		foreach($_POST['item_list'] as $i){
			$item = $db->query("SELECT * FROM items WHERE name='character' AND value='".$i."'");
			if($item->rowCount() > 0){
				$item = $item->fetch();
				$account = $db->fetch("SELECT * FROM accounts WHERE id='".$account['id']."'");
				$characters = explode(',', $account['characters']);
				$key = array_search($item['value'], $characters);
				unset($characters[$key]);
				$characters = implode(',',$characters);
				$db->query("UPDATE accounts SET characters = '".$characters."' WHERE id='".$account['id']."'");
				if(!empty($list))
					$list .= ',';
				$list .= $item['id'];
				if(!empty($title))
					$title .= ',';
				$title .= $db->fieldFetch('characters', $i, 'name');
				$count++;
			}
		}
		if(empty($list))
			$system->message(L_ERROR, 'Please select an item..', './?s=shop&mode=sell', 'Back');
		$db->query("UPDATE accounts SET gold = '".($account['gold']-$system->data('shop_cost'))."' WHERE id='".$account['id']."'");
		$db->query("INSERT INTO `sales`
				(`items`, `quantity`, `limit`, `seller`, `timelimit`, `date`, `value`, `description`, `title`) 
		VALUES ('".$list."','1','1','".$account['id']."','".(time()+604800)."','".time()."','".$cost."','".(($count > 1) ? $system->data('bulk_sale'):$system->data('one_sale'))."','".$title."')");
		$system->message('Successful submit', 'Congratulations! You have successfully posted your item(s)', './?s=shop', 'Continue');
	}
	$tpl = $STYLE->getcode('sell',$tpl);
	$page_title .= ' > Sell';
	
	$items = '';
	$characters = explode(',',$account['characters']);
	foreach($characters as $key => $character){
		// Have to check characters who are able to be sold
		$item = $db->query("SELECT * FROM items WHERE name='character' AND value='".$character."'");
		if($item->rowCount() > 0){
			$c = $db->fetch("SELECT * FROM characters WHERE id='".$character."'");
			$items .= '<li>
							'.$user->image($c['id'],'characters','./').'<br>
							'.$c['name'].' <input type="checkbox" name="item_list[]" value="'.$c['id'].'">
						</li>';
		}
	}
	
	if(empty($items))
		$items = '<li>Your inventory is empty!</li>';
	
	$tpl = $STYLE->tags($tpl, array(
					"INVENTORY" => $items,
					"COST" => $system->data('shop_cost'),
					"DATE" => $system->time(time(),'Y-m-d')
					));
	
}*/else{
// Check for old items and return them to there owners
$all = $db->query("SELECT * FROM sales WHERE seller != '-1' AND sold = ''");
while($i = $all->fetch()){
	if(!empty($i['timelimit']) && ($i['timelimit'] - time()) < 0){
		// Expired delete and give back to the user
		$seller = $db->fetch("SELECT * FROM accounts WHERE id ='".$i['seller']."'");
		$items = explode(',',$i['items']);
		foreach($items as $item){
			$check = $db->query("SELECT * FROM items WHERE name='character' AND id='".$item."'");
			if($check->rowCount() > 0){
				$check = $check->fetch();
				$db->query("UPDATE accounts SET characters='".$seller['characters'].','.$check['value']."' WHERE id ='".$i['seller']."'");
			}
			$seller = $db->fetch("SELECT * FROM accounts WHERE id ='".$i['seller']."'");
		}
		$system->mail($seller['id'], '-1', 'Item sale expired', 'Your items sale has expired, everthing was returned to your inventory.');		
		$db->query("DELETE FROM sales WHERE id='".$i['id']."'");
	}
}

	
	
$sold = $STYLE->getcode('sold', $tpl);
$bought = $STYLE->getcode('bought', $tpl);
$timeout = $STYLE->getcode('timeout', $tpl);
$item_tpl = $STYLE->getcode('item', $tpl);
$tpl = str_replace(array($STYLE->getcode('sell', $tpl),$STYLE->getcode('item', $tpl),$STYLE->getcode('timeout', $tpl),$STYLE->getcode('sold', $tpl), $STYLE->getcode('bought', $tpl)), '', $tpl);
$shop = $db->query("SELECT * FROM sales WHERE seller = '-1' ORDER BY id DESC");
$content = '';
$public = '';
if($shop->rowCount() > 0){
	while($i = $shop->fetch()){
		$item = $item_tpl;
		// Title with experation data
		$timeleft = 'No timelimit';
		if(!empty($i['timelimit'])){
		$timeleft = $i['timelimit']-$i['date'];
		if($timeleft <= 0){
			$timeleft = 'Item expired';
			$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
			$content .= $timeout;
		}elseif(round($timeleft/3600) < 1)
			$timeleft = 'A few minutes are left!';
		else
			$timeleft = round($timeleft/3600).' hrs left';
		}
		$title = $i['title'].' <span style="
    color: #e63d3d;
    font-size: 10px;
">'.$timeleft.'</span>';
		// Who is selling
		$who = 'Server <br/>'.$system->time($i['date']);
		// Characters ? show pictures of them
		$images = '';
		$t = explode(',', $i['items']);
		foreach($t as $it){
			$character = $db->query("SELECT * FROM items WHERE id = '".$it."' AND `name` = 'character'");
			if($character->rowCount() > 0){
				$character = $character->fetch();
				$images .= $user->image($character['value'], 'characters', './', '" style="border:1px solid black;width:35px;margin-right:5px;    margin-bottom: 5px;"');
			}
		}
		
		// Has the user bought this?
		if($i['limit'] > '0' && $i['quantity'] !== '0'){
			$count = 0;
				//Search for the user's purchases
				if(!empty($i['sold'])){
					$selled = explode(',',$i['sold']);
					foreach($selled as $s){
						$buy = substr($s, 0, strpos($s,'='));
						if($account['id'] == $buy)
							$count++;
					}
				}
				if(abs($i['limit'] - $count) == 0){
					$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
					$content .= $bought;
				}
			
		}
		// Is it sold out? 
		if($i['quantity'] == '0'){
			$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
			$content .= $sold;
		}
		// Check for discount!
		$discount = $system->data('discount');
		if(!empty($discount)/* || $discount !== '0'*/){
			// There is a discount happening!!! 
			$percent = str_replace('%', '', $discount);
			$new = $i['value']-($i['value']*($percent/100));
			$item = $STYLE->tags($item, array(
					"NEW" => $new,
					"DISCOUNT" => $discount.' OFF!'
					));
		}else{
			// Remove the discount code 
			$item = preg_replace('/\<!-- BEGIN discount -->(.*?)\<!-- END discount -->/is', '', $item);
		}
		$content .= $STYLE->tags($item, array(
					"ID" => $i['id'],
					"AVATAR" => '',
                    "TITLE" => $title,
					"DESCRIPTION" => $i['description'],
					"IMAGES" => $images,
					"VALUE" => $i['value'],
					"QUANTITY" => $i['quantity'],
					"WHO" => $who
					));
	}
}else{
	$content = '<p style="
    text-align: center;
    clear: both;
">No shop items available</p>';
}

$community = $db->query("SELECT * FROM sales WHERE seller != '-1' ORDER BY id DESC");
if($community->rowCount() > 0){
	while($i = $community->fetch()){
		// Is it sold out? 
		if($i['quantity'] == '0'){
			continue;
			$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
			$public .= $sold;
		}
		$item = $item_tpl;
		// Remove discounts
		$item = preg_replace('/\<!-- BEGIN discount -->(.*?)\<!-- END discount -->/is', '', $item);
		// Title with experation data
		$timeleft = 'No timelimit';
		if(!empty($i['timelimit'])){
		$timeleft = $i['timelimit']-time();
		if($timeleft <= 0){
			$timeleft = 'Item expired';
			$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
			$public .= $timeout;
		}elseif(round($timeleft/3600) < 1)
			$timeleft = 'A few minutes are left!';
		else
			$timeleft = round($timeleft/3600).' hrs left';
		}
		$title = $i['title'].' <span style="
    color: #e63d3d;
    font-size: 10px;
">'.$timeleft.'</span>';
		// Who is selling
		$who = $user->name($i['seller']).' <br/>'.$system->time($i['date']);
		// Characters ? show pictures of them
		$images = '';
		$t = explode(',', $i['items']);
		foreach($t as $it){
			$character = $db->query("SELECT * FROM items WHERE id = '".$it."' AND `name` = 'character'");
			if($character->rowCount() > 0){
				$character = $character->fetch();
				$images .= $user->image($character['value'], 'characters', './', '" style="border:1px solid black;width:35px;margin-right:5px;    margin-bottom: 5px;"');
			}
		}
		// Has the user bought this?
		if($i['limit'] > '0' && $i['quantity'] !== '0'){
			$count = 0;
				//Search for the user's purchases
				if(!empty($i['sold'])){
					$selled = explode(',',$i['sold']);
					foreach($selled as $s){
						$buy = substr($s, 0, strpos($s,'='));
						if($account['id'] == $buy)
							$count++;
					}
				}
				if(abs($i['limit'] - $count) == 0){
					$item = str_replace(array($STYLE->getcode('buy', $item)), '', $item);
					$public .= $bought;
				}
			
		}
		
		$public .= $STYLE->tags($item, array(
					"ID" => $i['id'],
					"AVATAR" => $user->image($i['seller'], 'avatars', './', '" style="border:1px solid black;float:left;width:54px;margin-right:5px;margin-bottom: 5px;"'),
                    "TITLE" => $title,
					"DESCRIPTION" => $i['description'],
					"IMAGES" => $images,
					"VALUE" => $i['value'],
					"QUANTITY" => $i['quantity'],
					"WHO" => $who
					));
	}
}else{
	$public = '<p style="
    text-align: center;
    clear: both;
">No community items available</p>';
}

$tpl = $STYLE->tags($tpl, array(
					"COINS" => $account['gold'],
                    "COMMUNITY" => $public,
					"SHOP" => $content
					));
}
$output .= $tpl;

?>