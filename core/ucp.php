<?php
$tpl = $STYLE->open('ucp.tpl');
// Generate Global Menu
$global_menu = $STYLE->getcode('menu',$tpl);
$tpl = str_replace ($global_menu,'',$tpl);
$global_menu = $STYLE->tags($global_menu,array("L_ACCOUNT" => L_ACCOUNT, "L_SETTINGS" => L_SETTINGS, "L_SIGNATURE" => L_SIGNATURE, "L_AVATAR" => L_AVATAR));
$content = '';
// Define Mode
if ( isset($_GET['mode']))
{
	$mode = $secure->clean($_GET['mode']);
} else {
	$mode = '';
}
if ( $mode == 'avatar')
{
	$tpl = str_replace(array($STYLE->getcode('account',$tpl),$STYLE->getcode('settings',$tpl),$STYLE->getcode('signature',$tpl)),'',$tpl);
	$page_title = $page_title.' / <a href="./control-panel?mode=settings" class="normfont">'.L_AVATAR.'</a>';
	if(isset($_POST['Delete']))
	{
		$user->deletethisfile("../images/avatars/".$account['id']);
		$system->message(L_DELETE,L_AVATAR_DELETE,'./control-panel?mode=avatar',L_CONTINUE);
	} else if ( isset($_POST['Avi'])) {
		if(isset($_POST['Avi'])) {
			$image=$_FILES['image']['name'];
			if ($image){
				$filename = stripslashes($_FILES['image']['name']);
				$extension = $user->getExtension($filename);
				$extension = strtolower($extension);                    
				// Make sure it is an image
				if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")))
				{
					$system->message(L_ERROR,L_AVATAR_UPLOAD_FORMAT,'./control-panel?mode=avatar',L_CONTINUE);
				}
				// Delete possible existing avatar
				$user->deletethisfile("./images/avatars/".$account['id']);
				$image_name=time().'.'.$extension;
				$newname="./images/avatars/".$account['id'].".$extension";
				$copied = copy($_FILES['image']['tmp_name'], $newname);
				$size=filesize("$newname");
				list($width, $height) = getimagesize("$newname");
				unlink($_FILES['image']['tmp_name']);
			}
			if (! isset($copied)) {
				$system->message(L_ERROR,L_AVATAR_UPLOAD_ERROR,'./control-panel?mode=avatar',L_CONTINUE);
			} else
			if( $height > $system->data('avatar_height') || $width > $system->data('avatar_width') ){
				// Prevent Avatar over Dimension size
				unlink("$newname");
				$error_message = str_replace(array("[HEIGHT]","[WIDTH]"),array($system->data('avatar_height'),$system->data('avatar_width')),L_AVATAR_UPLOAD_DIMENSION);
				$system->message(L_ERROR,$error_message,'./control-panel?mode=avatar',L_CONTINUE);
			} else
			if ($size > $system->data('avatar_filesize')) {
				// Prevent Avatar over File size
				unlink("$newname");
				$error_message = str_replace("[SIZE]",$system->data('avatar_filesize'),L_AVATAR_UPLOAD_SIZE);
				$system->message(L_ERROR,$error_message,'./control-panel?mode=avatar',L_CONTINUE);
			} else{
				$system->message(L_UPDATED,L_AVATAR_UPDATE,'./control-panel?mode=avatar',L_CONTINUE);
			}

		}
	} else {
		$tpl = $STYLE->tags($tpl,array("AVATAR" => $user->avatar($account['id']),"L_DELETE" => L_DELETE));
	}
} else if ( $mode == 'signature')
{
	$tpl = str_replace(array($STYLE->getcode('account',$tpl),$STYLE->getcode('avatar',$tpl),$STYLE->getcode('settings',$tpl)),'',$tpl);
	$page_title = $page_title.' / <a href="../control-panel?mode=signature" class="normfont">'.L_SIGNATURE.'</a>';
	if ( isset($_POST['Submit']))
	{
		if ( isset($_POST['signature']))
		{
			$signature = $secure->clean($_POST['signature']);
		} else {
			$signature = '';
		}
		$id = $account['id'];
		$result = $db->query("UPDATE accounts SET signature = '$signature' WHERE id = '$id'");
		if ( $result )
		{
			$system->message(L_UPDATED,L_SIGNATURE_UPDATE,'./control-panel?mode=signature',L_CONTINUE);
		} else {
			$system->message(L_ERROR,L_SIGNATURE_ERROR,'./control-panel?mode=signature',L_CONTINUE);
		}
	} else {
		$tpl = $STYLE->tags($tpl,array("L_PREVIEW" => L_PREVIEW,"PREVIEW" => $system->bbcode($account['signature']), "SIGNATURE" => stripslashes($account['signature'])));
	}
} else if ( $mode == 'settings')
{
	// Account Settings
	$tpl = str_replace(array($STYLE->getcode('account',$tpl),$STYLE->getcode('avatar',$tpl),$STYLE->getcode('signature',$tpl)),'',$tpl);
	$page_title = $page_title.' / <a href="./control-panel?mode=settings" class="normfont">'.L_SETTINGS.'</a>';   
	if (isset($_POST['Submit']) || isset($_POST['UpdatePC'])){		
		$reset = false;
		if(isset($_POST['UpdatePC'])){
			$team = (!empty($account['team'])?explode(',',$account['team']):0);
			$which = (isset($_POST['bg']))?$_POST['bg']:1;
			$pctemplate = imagecreatefrompng('./images/playercards/default-'.$which.'.png');
			if(!empty($team) && count($team) == 3 && !isset($_POST['hideteam'])){
				imagedestroy($pctemplate);
				$pctemplate = imagecreatefrompng('./images/playercards/default-c-'.$which.'.png');
				$pos_x = 115;
				$pos_y = 17;
				foreach($team as $_ => $character){
					$frame = './images/characters/'.$character;
					if (file_exists("$frame.png")) {
						$frame .= '.png';
						$path = $frame;
						$frame = imagecreatefrompng($frame);
					} else
					if (file_exists("$frame.gif")) {
						$frame .= '.gif';
						$path = $frame;
						$frame = imagecreatefromgif($frame);
					} else
					if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
						$frame .= '.jpg';
						$path = $frame;
						$frame = imagecreatefromjpeg($frame);
					} 
					list($orig_width, $orig_height) = getimagesize($path);		
					imagecopyresampled($pctemplate, $frame, $pos_x, $pos_y, 0, 0,
					51, 51, $orig_width, $orig_height);
					$pos_x += 61;
					if($_ == 1)
					$pos_x += 1;
				}
			}	
			$frame = './images/avatars/'.$account['id'];
			if (file_exists("$frame.png")) {
				$frame .= '.png';
				$path = $frame;
				$frame = imagecreatefrompng($frame);
			} else
			if (file_exists("$frame.gif")) {
				$frame .= '.gif';
				$path = $frame;
				$frame = imagecreatefromgif($frame);
			} else
			if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
				$frame .= '.jpg';
				$path = $frame;
				$frame = imagecreatefromjpeg($frame);
			} 
			list($orig_width, $orig_height) = getimagesize($path);		
			imagecopyresampled($pctemplate, $frame, 28, 28, 0, 0,
			75, 75, $orig_width, $orig_height);
			$font = './tpl/default/css/fonts/BebasKai.ttf';
			$ulevel = $db->fetch("SELECT * FROM levels WHERE experience < '".($account['experience']+1)."' ORDER BY experience DESC LIMIT 1");
			$players = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
			$count = 1;
			$ladderank = 'Unranked';
			while($player = $players->fetch()){
				if($player['id'] == $account['id'])
					$ladderank = '#'.$count;
				$count++;
			}
			$team = (!empty($account['team'])?explode(',',$account['team']):0);
			if(!empty($team) && count($team) == 3 && !isset($_POST['hideteam'])){
				$pos_x = 115;
				$pos_y = 17;
				foreach($team as $_ => $character){
					$frame = './images/characters/'.$character;
					if (file_exists("$frame.png")) {
						$frame .= '.png';
						$path = $frame;
						$frame = imagecreatefrompng($frame);
					} else
					if (file_exists("$frame.gif")) {
						$frame .= '.gif';
						$path = $frame;
						$frame = imagecreatefromgif($frame);
					} else
					if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
						$frame .= '.jpg';
						$path = $frame;
						$frame = imagecreatefromjpeg($frame);
					} 
					list($orig_width, $orig_height) = getimagesize($path);		
					imagecopyresampled($pctemplate, $frame, $pos_x, $pos_y, 0, 0,
					51, 51, $orig_width, $orig_height);
					$pos_x += 61;
					if($_ == 1)
					$pos_x += 1;
				}
			}	
			
			$group = $db->fetch("SELECT * FROM usergroups WHERE id = '".$account['group']."'");
			/*$pos_y = 35;
			foreach($group as $char){
				imagettftext($pctemplate, 8, 0, 15, $pos_y, imagecolorallocate($pctemplate, 255, 255, 255), $font, $char);
				$pos_y += 15;
			}*/
			$clan =  $db->fetch("SELECT * FROM `clan-members` WHERE `account_id` = '".$account['id']."'");
			if($clan)
				$clan = $db->fieldFetch('clans',$clan['clan_id'], 'name');
			else
				$clan = 'Clanless';
			// Get level
			imagettftext($pctemplate, 22, 0, 115, 110, imagecolorallocate($pctemplate, 255, 255, 255), $font, $account['name']);
			imagettftext($pctemplate, 10, 0, 135, 130, imagecolorallocate($pctemplate, 0, 0, 0), $font, $group['title']);
			imagettftext($pctemplate, 16, 0, 300, 106, imagecolorallocate($pctemplate, 255, 255, 255), $font, $clan);
			imagettftext($pctemplate, 12, 0, 320, 25, imagecolorallocate($pctemplate, 0, 0, 0), $font, '+ '.$account['highest_streak']);
			imagettftext($pctemplate, 12, 0, 300, 55, imagecolorallocate($pctemplate, 0, 0, 0), $font, $ladderank);
			imagettftext($pctemplate, 12, 0, 390, 60, imagecolorallocate($pctemplate, 0, 0, 0), $font, $ulevel['level']);
			imagettftext($pctemplate, 12, 0, 390, 95, imagecolorallocate($pctemplate, 0, 0, 0), $font, $account['gold']);
			imagettftext($pctemplate, 12, 0, 400, 25, imagecolorallocate($pctemplate, 0, 0, 0), $font, $account['wins'].' - '.$account['loses']);
			imagepng($pctemplate, './images/playercards/'.$account['id'].'.png');
			imagedestroy($pctemplate);
			$system->message(L_UPDATED, 'Your playercard has been successfully updated!', './control-panel?mode=settings',L_CONTINUE);
		}
		
		if(isset($_POST['reset']) && $_POST['reset'] == '1'){
			$db->query("UPDATE accounts SET wins = '0' , streak = '0' , experience='0', loses='0' WHERE id='".$account['id']."'");
			$reset = true;
		}
		/* if ( !isset($_POST['template']) ){
			$user_template = $system->data('template');
		} else{
			$user_template = $secure->clean($_POST['template']);
			if($user_template !== $system->data('template')) {
				$notTemp = false;
				$check = $db->query("SELECT * FROM `items` WHERE `name` = 'template' AND `value` = '".$user_template."'");
				if($check->rowCount() > 0){
					$check = $check->fetch();
					$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '".$account['id']."' AND `item` = '".$check['id']."'");
					if($checkInventory->rowCount() === 0)
						$notTemp = true;
				}else{
					$notTemp = true;
				}
				if($notTemp===true)
					$user_template = $system->data('template');
			}
		}        */
		if ( !isset($_POST['sfx']) ){
			$sfx = $system->data('default-sfx');
		} else{
			$sfx= $secure->clean($_POST['sfx']);
			if($sfx !== $system->data('default-sfx')) {
				$notTemp = false;
				$check = $db->query("SELECT * FROM `items` WHERE `name` = 'sfx' AND `value` = '".$sfx."'");
				if($check->rowCount() > 0){
					$check = $check->fetch();
					$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '".$account['id']."' AND `item` = '".$check['id']."'");
					if($checkInventory->rowCount() === 0)
						$notTemp = true;
				}else{
					$notTemp = true;
				}
				if($notTemp===true)
					$sfx = $system->data('default-sfx');
			}
		}
		if ( !isset($_POST['timezone']) ){
			$timezone = '';
		} else{
			$timezone = $secure->clean($_POST['timezone']);
		}       
		if ( !isset($_POST['gender']) ){
			$gender = '';
		} else{
			$gender = $secure->clean($_POST['gender']);
		}
		if ( !isset($_POST['location']) ){
			$location = '';
		} else{
			$location = $secure->clean($_POST['location']);
		}
		if ( !isset($_POST['emailme']) ){
			$emailme = '';
		} else{
			$emailme = $secure->clean($_POST['emailme']);
		}
		$id = $account['id'];
		$result = $db->query("UPDATE accounts SET sfx = '$sfx' , timezone = '$timezone' , location='$location', gender='$gender', emailme='$emailme' WHERE id='$id'");
		if ( $result )
		{	
			if($reset == true)
			$reset = '<b>Game statistics were reset to 0</b><br/>';
			$system->message(L_UPDATED, $reset.L_ACCOUNT_SETTINGS_UPDATE,'./control-panel?mode=settings',L_CONTINUE);
		} else {
			$system->message(L_ERROR,L_ACCOUNT_SETTINGS_ERROR,'./control-panel?mode=settings',L_CONTINUE);
		}      
		
	} else {
		
		//template
		$pctemplate = $user->image($account['id'],'playercards','./');
		if(!file_exists('./images/playercards/default-'.rand(1,3).'.png')){
			$team = (!empty($account['team'])?explode(',',$account['team']):0);
			$pctemplate = imagecreatefrompng('./images/playercards/default-'.rand(1,3).'.png');
			if(!empty($team) && count($team) == 3){
				imagedestroy($pctemplate);
				$pctemplate = imagecreatefrompng('./images/playercards/default-c-'.rand(1,3).'.png');
				$pos_x = 115;
				$pos_y = 17;
				foreach($team as $_ => $character){
					$frame = './images/characters/'.$character;
					if (file_exists("$frame.png")) {
						$frame .= '.png';
						$path = $frame;
						$frame = imagecreatefrompng($frame);
					} else
					if (file_exists("$frame.gif")) {
						$frame .= '.gif';
						$path = $frame;
						$frame = imagecreatefromgif($frame);
					} else
					if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
						$frame .= '.jpg';
						$path = $frame;
						$frame = imagecreatefromjpeg($frame);
					} 
					list($orig_width, $orig_height) = getimagesize($path);		
					imagecopyresampled($pctemplate, $frame, $pos_x, $pos_y, 0, 0,
					51, 51, $orig_width, $orig_height);
					$pos_x += 61;
					if($_ == 1)
					$pos_x += 1;
				}
			}	
			$frame = './images/avatars/'.$account['id'];
			if (file_exists("$frame.png")) {
				$frame .= '.png';
				$path = $frame;
				$frame = imagecreatefrompng($frame);
			} else
			if (file_exists("$frame.gif")) {
				$frame .= '.gif';
				$path = $frame;
				$frame = imagecreatefromgif($frame);
			} else
			if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
				$frame .= '.jpg';
				$path = $frame;
				$frame = imagecreatefromjpeg($frame);
			} 
			list($orig_width, $orig_height) = getimagesize($path);		
			imagecopyresampled($pctemplate, $frame, 28, 28, 0, 0,
			75, 75, $orig_width, $orig_height);
			$font = './tpl/default/css/fonts/BebasKai.ttf';
			$ulevel = $db->fetch("SELECT * FROM levels WHERE experience < '".($account['experience']+1)."' ORDER BY experience DESC LIMIT 1");
			$players = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
			$count = 1;
			$ladderank = 'Unranked';
			while($player = $players->fetch()){
				if($player['id'] == $account['id'])
					$ladderank = '#'.$count;
				$count++;
			}
			$team = (!empty($account['team'])?explode(',',$account['team']):0);
			if(!empty($team) && count($team) == 3){
				$pos_x = 115;
				$pos_y = 17;
				foreach($team as $_ => $character){
					$frame = './images/characters/'.$character;
					if (file_exists("$frame.png")) {
						$frame .= '.png';
						$path = $frame;
						$frame = imagecreatefrompng($frame);
					} else
					if (file_exists("$frame.gif")) {
						$frame .= '.gif';
						$path = $frame;
						$frame = imagecreatefromgif($frame);
					} else
					if (file_exists("$frame.jpg")||file_exists("$frame.jpeg")) {
						$frame .= '.jpg';
						$path = $frame;
						$frame = imagecreatefromjpeg($frame);
					} 
					list($orig_width, $orig_height) = getimagesize($path);		
					imagecopyresampled($pctemplate, $frame, $pos_x, $pos_y, 0, 0,
					51, 51, $orig_width, $orig_height);
					$pos_x += 61;
					if($_ == 1)
					$pos_x += 1;
				}
			}	
			
			$group = $db->fetch("SELECT * FROM usergroups WHERE id = '".$account['group']."'");
			/*$pos_y = 35;
			foreach($group as $char){
				imagettftext($pctemplate, 8, 0, 15, $pos_y, imagecolorallocate($pctemplate, 255, 255, 255), $font, $char);
				$pos_y += 15;
			}*/
			$clan =  $db->fetch("SELECT * FROM `clan-members` WHERE `account_id` = '".$account['id']."'");
			if($clan)
				$clan = $db->fieldFetch('clans',$clan['clan_id'], 'name');
			else
				$clan = 'Clanless';
			// Get level
			imagettftext($pctemplate, 22, 0, 115, 110, imagecolorallocate($pctemplate, 255, 255, 255), $font, $account['name']);
			imagettftext($pctemplate, 10, 0, 135, 130, imagecolorallocate($pctemplate, 0, 0, 0), $font, $group['title']);
			imagettftext($pctemplate, 16, 0, 300, 106, imagecolorallocate($pctemplate, 255, 255, 255), $font, $clan);
			imagettftext($pctemplate, 12, 0, 320, 25, imagecolorallocate($pctemplate, 0, 0, 0), $font, '+ '.$account['highest_streak']);
			imagettftext($pctemplate, 12, 0, 300, 55, imagecolorallocate($pctemplate, 0, 0, 0), $font, $ladderank);
			imagettftext($pctemplate, 12, 0, 390, 60, imagecolorallocate($pctemplate, 0, 0, 0), $font, $ulevel['level']);
			imagettftext($pctemplate, 12, 0, 390, 95, imagecolorallocate($pctemplate, 0, 0, 0), $font, $account['gold']);
			imagettftext($pctemplate, 12, 0, 400, 25, imagecolorallocate($pctemplate, 0, 0, 0), $font, $account['wins'].' - '.$account['loses']);
			imagepng($pctemplate, './images/playercards/'.$account['id'].'.png');
			imagedestroy($pctemplate);
			$pctemplate = $user->image($account['id'],'playercards','./');
		}
		
		// List Templates
		if(empty($account['tpl']))
			$account['tpl'] = $system->data('template');
		$user_template = $account['tpl'];
		
		$skipT = true;
		$skipA = true;
		if (!empty($account['tpl']) && $system->data('usertemplate') == '1')
			$skipT = false;
		elseif(!empty($account['tpl']) && $system->group_permission($account['group'], 'templates') !== '0')
			$skipT = false;
		$template_box = '';
		$directory = @opendir('./tpl/');
		while($file = readdir($directory))
		{
			if($skipT === true) break;
			
			if($file!="index.php" && $file!="." && $file!="..")
			{
				if($file !==  $system->data('template')) {
					// Check if bought
					$check = $db->query("SELECT * FROM `items` WHERE `name` = 'template' AND `value` = '".$file."'");
					if($check->rowCount() > 0){
						$check = $check->fetch();
						$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '".$account['id']."' AND `item` = '".$check['id']."'");
						if($checkInventory->rowCount() === 0){
							continue;
						}
					}
				}
				if ( $file == $user_template )
				{
					$selected='selected';
				} else {
					$selected = '';
				}
				$template_box .= '<option '.$selected.' value="' . $file . '">' . $file . '</option>';
			}
		}
		if($system->group_permission($account['group'], 'sfx') !== '0')
			$skipA = false;
		$sfxs = '';
		$directory = @opendir('./sound/');
		while($file = readdir($directory))
		{
			if($skipA === true) break;
			if($file!="index.php" && $file!="." && $file!="..")
			{
				if($file !==  $system->data('default-sfx')) {
					// Check if bought
					
					$check = $db->query("SELECT * FROM `items` WHERE `name` = 'sfx' AND `value` = '".$file."'");
					if($check->rowCount() > 0){
						$check = $check->fetch();
						$checkInventory = $db->query("SELECT * FROM `inventory` WHERE `account` = '".$account['id']."' AND `item` = '".$check['id']."'");
						if($checkInventory->rowCount() === 0){
							continue;
						}
					}
				}
				if ( $file == $account['sfx'] )
				{
					$selected='selected';
				} else {
					$selected = '';
				}
				$sfxs .= '<option '.$selected.' value="' . $file . '">' . $file . '</option>';
			}
		}
		// Current Notification Setting
		if ( $account['emailme'] == '1')
		{
			$notify_no = '';
			$notify_yes = 'selected';
		} else {
			$notify_no = 'selected';
			$notify_yes = '';
		}
		if ($account['gender'] == '1')
		{
			$male='selected';
			$female='';
			$hidden='';
		} else if ($account['gender'] == '2')
		{
			$male='';
			$female='selected';
			$hidden='';
		} else {
			$male='';
			$female='';
			$hidden='selected';
		}
		// Timezone Options
		$a='';$b='';$c='';$d='';$e='';$f='';$g='';$h='';$i='';
		$j='';$k='';$l='';$m='';$n='';$o='';$p='';$q='';$r='';
		$s='';$t='';$u='';$v='';$w='';$x='';$y='';$bb='';$rr='';
		$ss='';$sss='';$ww='';$www='';
		if ( $account['timezone'] == '-43200' )
		{
			$a = 'selected';
		} else if ( $account['timezone'] == '-39600' )
		{
			$b = 'selected';
		} else if ( $account['timezone'] == '-36000' ){
			$bb = 'selected';
		} else
		if ( $account['timezone'] == '-32400' ){
			$c = 'selected';
		} else
		if ( $account['timezone'] == '-28800' ){
			$d = 'selected';
		} else
		if ( $account['timezone'] == '-25200' ){
			$e = 'selected';
		} else
		if ( $account['timezone'] == '-21600' ){
			$f = 'selected';
		} else
		if ( $account['timezone'] == '-18000' ){
			$g = 'selected';
		} else
		if ( $account['timezone'] == '-14000' ){
			$h = 'selected';
		} else
		if ( $account['timezone'] == '-12200' ){
			$i = 'selected';
		} else
		if ( $account['timezone'] == '-10400' ){
			$j = 'selected';
		} else
		if ( $account['timezone'] == '-7200' ){
			$k = 'selected';
		} else
		if ( $account['timezone'] == '-3600' ){
			$l = 'selected';
		} else
		if ( $account['timezone'] == '0' ){
			$m = 'selected';
		} else
		if ( $account['timezone'] == '3600' ){
			$n = 'selected';
		} else
		if ( $account['timezone'] == '7200' ){
			$o = 'selected';
		} else
		if ( $account['timezone'] == '10400' ){
			$p = 'selected';
		} else
		if ( $account['timezone'] == '12200' ){
			$q = 'selected';
		} else
		if ( $account['timezone'] == '14000' ){
			$r = 'selected';
		} else
		if ( $account['timezone'] == '16200' ){
			$rr = 'selected';
		} else
		if ( $account['timezone'] == '18000' ){
			$s = 'selected';
		} else
		if ( $account['timezone'] == '19800' ){
			$ss = 'selected';
		} else
		if ( $account['timezone'] == '20700' ){
			$sss = 'selected';
		} else
		if ( $account['timezone'] == '21600' ){
			$t = 'selected';
		} else
		if ( $account['timezone'] == '25200' ){
			$u = 'selected';
		} else
		if ( $account['timezone'] == '28800' ){
			$v = 'selected';
		} else
		if ( $account['timezone'] == '32400' ){
			$w = 'selected';
		} else
		if ( $account['timezone'] == '34200' ){
			$ww = 'selected';
		} else
		if ( $account['timezone'] == '36000' ){
			$www = 'selected';
		} else
		if ( $account['timezone'] == '39600' ){
			$x = 'selected';
		} else
		if ( $account['timezone'] == '43200' ){
			$y = 'selected';
		}
		$inventory = '';
		$item = '';
		$characters = explode(',',$account['characters']);
		foreach($characters as $key => $character){
			// Have to check characters who are able to be sold
			$item = $db->query("SELECT * FROM items WHERE name='character' AND value='".$character."'");
			if($item->rowCount() > 0){
				$chara = $db->fetch("SELECT * FROM characters WHERE id='".$character."'");
				$inventory .= '<li>
							'.$user->image($chara['id'],'characters','./').'<br>
							'.$chara['name'].' <input type="checkbox" name="item_list[]" value="'.$chara['id'].'">
						</li>';
			}
		}
		
		if(empty($inventory))
		$inventory = '<li>Your inventory is empty!</li>';
		
		$tpl = $STYLE->tags($tpl,array("PLAYERCARD" => $pctemplate, "BBCODE" => '[url=https://www.anime-blast.com/profile/'.urlencode($account['name']).'][img]https://www.anime-blast.com//images/playercards/'.$account['id'].'.png[/img][/url]', "INVENTORY" => $inventory, "MALE" => $male, "FEMALE" => $female, "HIDDEN" => $hidden, "NOTIFY_YES" => $notify_yes, "NOTIFY_NO" => $notify_no,
		"L_NOTIFY" => L_NOTIFY,"TEMPLATES" => ($skipT)?'':'  <tr>
	
    <td width="15%"><div align="left"><font class="normfont">'.L_TEMPLATE.':</font></div></td>
    <td width="85%">
	<div align="left">
     <select name="template" class="formcss">


        '.$template_box.'


      </select>
    </div>
	</td>
  </tr>',"SOUND" => ($skipA)?'':'  <tr>
	
    <td width="15%"><div align="left"><font class="normfont">SFX Package:</font></div></td>
    <td width="85%">
	<div align="left">
     <select name="sfx" class="formcss">


        '.$sfxs.'


      </select>
    </div>
	</td>
  </tr>', "L_LANGUAGE" => L_LANGUAGE, "L_TIMEZONE" => L_TIMEZONE, "L_LOCATION" => L_LOCATION, "L_GENDER" => L_GENDER, "L_MALE" => L_MALE, "L_FEMALE" => L_FEMALE, "L_HIDDEN" => L_HIDDEN, "L_ENABLED" => L_ENABLED, "L_DISABLED" => L_DISABLED,
		"LOCATION" => stripslashes($account['location']),
		"a"=>$a,"b"=>$b,"c"=>$c,"d"=>$d,"e"=>$e,"f"=>$f,"g"=>$g,"h"=>$h,"i"=>$i,"j"=>$j,"k"=>$k,"l"=>$l,"m"=>$m,"n"=>$n,"o"=>$o,"p"=>$p,"q"=>$q,"r"=>$r,"s"=>$s,"t"=>$t,"u"=>$u,"v"=>$v,"w"=>$w,"x"=>$x,"y"=>$y,"bb"=>$bb,"rr"=>$rr,"ss"=>$ss,"sss"=>$sss,"ww"=>$ww,"www"=>$www));
	}
} else {
	// Account Options
	$tpl = str_replace(array($STYLE->getcode('signature',$tpl),$STYLE->getcode('avatar',$tpl),$STYLE->getcode('settings',$tpl)),'',$tpl);
	$page_title = $page_title.' / <a href="./control-panel" class="normfont">'.L_ACCOUNT.'</a>';
	if (isset($_POST['Submit']))
	{
		// Sanitise Input
		if ( isset($_POST['name']))
		{
			$name = $secure->clean($_POST['name']);
		} else {
			$name = '';
		}
		if ( isset($_POST['email']))
		{
			$email = $secure->clean($_POST['email']);
		} else {
			$email = '';
		}
		if ( isset($_POST['pass']))
		{
			$password = md5($secure->clean($_POST['pass']));
		} else {
			$password = '';
		}
		if ( isset($_POST['newpass']))
		{
			$new_password = $secure->clean($_POST['newpass']);
		} else {
			$new_password = '';
		}
		if ( isset($_POST['confirmnewpass']))
		{
			$new_password_confirm = $secure->clean($_POST['confirmnewpass']);
		} else {
			$new_password_confirm = '';
		}
		// Ensure New Password is Confirmed
		if($new_password != $new_password_confirm)
		{
			$system->message(L_ERROR,L_CONFIRM_PASSWORD_ERROR,'./control-panel',L_CONTINUE);
		}
		// Prevent Nullifying of Password
		if ( empty($new_password))
		{
			$new_password = $account['password'];
		}
		// Ensure Correct Password
		if($password != $account['password'])
		{
			$system->message(L_ERROR,L_PASSWORD_ERROR,'./control-panel',L_CONTINUE);
		}
		// Ensure Name is not Banned
		if($secure->verify_name($name) == 'banned')
		{
			$system->message(L_ERROR,L_NAME_BANNED,'./control-panel',L_CONTINUE);
		}
		// Ensure name does not already exist
		if($secure->verify_name($name) == 'exist' && $name != $account['name'])
		{
			$system->message(L_ERROR,L_NAME_EXIST,'./control-panel',L_CONTINUE);
		}
		// Only allow if fields are present
		if ( isset($name) && isset($password))
		{
			$user_id = $account['id'];
			$insert = '';
			if($new_password != $password)
			$insert = ", password = '".md5($new_password)."'";
			
			$result = $db->query("UPDATE accounts SET name='$name'$insert WHERE id='$user_id'");
			$system->message(L_UPDATED,L_ACCOUNT_UPDATED,'./control-panel',L_CONTINUE);
		}

	}
	$tpl = $STYLE->tags($tpl,array("NAME"     => $account['name'], "EMAIL" => $account['email'], "L_NAME" => L_NAME, "L_EMAIL" => L_EMAIL, "L_PASSWORD" => L_PASSWORD, "L_NEW_PASSWORD" => L_NEW_PASSWORD, "L_NEW_PASSWORD_CONFIRM" => L_NEW_PASSWORD_CONFIRM ));
}
$output .= $STYLE->tags($tpl,array("L_ACCOUNT" => L_ACCOUNT, "L_SETTINGS" => L_SETTINGS, "L_SIGNATURE" => L_SIGNATURE, "L_AVATAR" => L_AVATAR,"L_SUBMIT" => L_SUBMIT))
?>