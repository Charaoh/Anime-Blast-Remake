<?php
$cid = (isset($_GET['id']) ? urldecode($_GET['id']) : '');
$tpl = $STYLE->open('clan.tpl');
$clan = $db->query("SELECT * FROM `clans` WHERE `name` LIKE '" . $cid . "'");
$action = (!empty($_GET['action']) ? $secure->clean($_GET['action']) : '');
$check = $db->fetch("SELECT * FROM `clan-members` WHERE account_id = '" . $account['id'] . "'");
if (!empty($action)) {
	if (!$account)
		$system->redirect($siteaddress . 'clans');
	switch ($action) {
		case 'accept':
			if ($check)
				$system->redirect($siteaddress . 'clans?action=leave');
			$clan = $clan->fetch();
			$invite = $db->query("SELECT * FROM `clan-invitations` WHERE `account_id` = '" . $account['id'] . "' AND `clan_id` = '" . $clan['id'] . "' AND `resolved` = 0");
			if ($invite->rowCount() > 0) {
				// He was invited make him a member and resolve the app
				$invite = $invite->fetch();
				$db->query("UPDATE `clan-invitations` SET `resolved` = '1' WHERE `clan-invitations`.`id` = '" . $invite['id'] . "';");
				$db->query("INSERT INTO `clan-members` (`id`, `clan_id`, `account_id`, `rank`, `joined`, `wins`, `loses`) VALUES (NULL, '" . $clan['id'] . "', '" . $account['id'] . "', '" . $clan['default-rank'] . "', unix_timestamp(), '', '');");
				// Inform all of the members of the new member
				$members = $db->query("SELECT * FROM `clan-members` WHERE `clan_id` = '" . $clan['id'] . "'");
				while ($member = $members->fetch()) {
					if ($member['id'] == $account['id']) continue;
					$system->mail($member['id'], $account['id'], 'A new member has joined ' . $clan['name'], 'Hello fellow clanmate! I have joined aboard. **This message is automatically sent by the server** ');
				}
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			} else {
				$system->redirect($siteaddress . 'clans');
			}
			break;
		case 'leave':
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$members = $db->query("SELECT * FROM `clan-members` WHERE clan_id = '" . $check['clan_id'] . "'")->rowCount();
			if (isset($_POST['confirmed'])) {
				// If he is the last member or the clan leader disband the clan.
				$db->query("DELETE FROM `clan-members` WHERE account_id = '" . $account['id'] . "'");
				if ($members - 1 == 0)
					$db->query("DELETE FROM `clans` WHERE id = '" . $check['clan_id'] . "'");
				$system->redirect($siteaddress . 'clans');
			}
			$system->confirm('Leave your clan', 'Below by confirming you will leave your clan and lose all progress done. ' . (($members - 1 == 0) ? 'This will also disband the clan because you are the only member left. Are your sure?' : ''), './clan/profile/' . urlencode($db->fieldFetch('clans', $check['clan_id'], 'name')), 'confirmed');
			break;
		case 'invitations':
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			// Check if clan is the same as the fetched...
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $check['rank'] . "'")->fetch();
			if ($rank['privelage'] < 3)
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			$page_title .= ' > Profile > ' . $clan['name'] . ' > Manage Applicants';
			$error = '';
			if (isset($_POST['submit'])) {
				$type = $secure->clean($_POST['submit']);
				$app = $db->query("SELECT * FROM `clan-app` WHERE id = '" . $secure->clean($_POST['id']) . "'")->fetch();
				$db->query("UPDATE `clan-app` SET `resolved` = '1' WHERE `clan-app`.`id` = '" . $app['id'] . "';");
				switch ($type) {
					case 'Accept':
						// If accepted message member and everyone. Add him to members as trial.
						$db->query("INSERT INTO `clan-members` (`id`, `clan_id`, `account_id`, `rank`, `joined`, `wins`, `loses`) VALUES (NULL, '" . $clan['id'] . "', '" . $app['account_id'] . "', '" . $clan['default-rank'] . "', unix_timestamp(), '', '');");
						$members = $db->query("SELECT * FROM `clan-members` WHERE `clan_id` = '" . $clan['id'] . "'");
						while ($member = $members->fetch()) {
							if ($member['account_id'] == $app['account_id'])
								$system->mail($member['account_id'], $account['id'], 'You have been accepted by ' . $clan['name'], 'Welcome fellow clanmate! We are happy to have you aboard. **This message is automatically sent by the server** ');
							else
								$system->mail($member['account_id'], $app['account_id'], 'A new member has joined ' . $clan['name'], 'Hello fellow clanmate! I have joined aboard. **This message is automatically sent by the server** ');
						}
						break;
					case 'Close':
						// If rejected message member and close application.
						$system->mail($app['account_id'], $account['id'], 'You have been rejected by ' . $clan['name'], 'Hello aspirant! We thank you for your time and appreciate you for taking us in consideration. We have resolved to postpone for now. **This message is automatically sent by the server** ');
						break;
					default:
						break;
				}
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=invitations');
			}
			if (isset($_POST['delete-invite'])) {
				$it = $secure->clean($_POST['invite']);
				$it = $db->query("SELECT * FROM `clan-invitations` WHERE `id` = '" . $it . "'");
				if ($it->rowCount() > 0) {
					$it = $it->fetch();
					$db->query("DELETE FROM `clan-invitations` WHERE `clan-invitations`.`id` = '" . $it['id'] . "'");
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=invitations');
				} else
					$error = 'Could not locate invitation.';
			}
			if (isset($_POST['invite'])) {
				$member = $secure->clean($_POST['member']);
				if ($member == $account['name'])
					$error = "You can't invite yourself.";
				$ismember = $db->query("SELECT * FROM `accounts` WHERE `name` LIKE '" . $member . "'");
				if ($ismember->rowCount() > 0) {
					// Invite 
					$ismember = $ismember->fetch();
					// Check if invite exists..
					$invited = $db->query("SELECT * FROM `clan-invitations` WHERE `account_id` = '" . $ismember['id'] . "' AND `resolved` = 0");
					if ($invited->rowCount() > 0) {
						$error = 'This member was already invited, please wait for a reply.';
					}
				} else
					$error = 'Member was not found, please supply the exact username of the player you wish to invite.';
				if (empty($error)) {
					$db->query("INSERT INTO `clan-invitations` (`id`, `account_id`, `clan_id`, `resolved`, `date`) VALUES (NULL, '" . $ismember['id'] . "', '" . $clan['id'] . "', '0', UNIX_TIMESTAMP())");
					// Message the member
					$system->mail($ismember['id'], $account['id'], 'Invitation for ' . $clan['name'], 'Hello! I have invited you to ' . $clan['name'] . '. You can accept here: <a href="' . $siteaddress . '/clan/profile/' . urlencode($clan['name']) . '?action=accept">Clan Invite</a> **This message is automatically sent by the server** ');
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=invitations');
				}
			}
			$global_menu = $STYLE->getcode('menu_gobackclan', $tpl);
			$global_menu = $STYLE->tags($global_menu, array(
				"NAME" => $clan['name']
			));

			// Applicants
			$applicated = $STYLE->getcode('applicated', $tpl);
			$apps = '';
			$searches = $db->query("SELECT * FROM `clan-app` WHERE `clan_id` = '" . $clan['id'] . "' AND `resolved` = 0");
			if ($searches->rowCount() > 0) {
				while ($search = $searches->fetch()) {
					$app = '';
					$search['answers'] = explode('/', $search['answers']);
					foreach ($search['answers'] as $answer) {
						if (empty($answer)) continue;
						if (!empty($app))
							$app .= '<br />';
						$app .= $answer;
					}
					if (empty($app))
						$app = 'No app filled out';
					$apps .= $STYLE->tags($applicated, array(
						"ID" => $search['id'],
						"MEMBER" => $db->fieldFetch('accounts', $search['account_id'], 'name'),
						"ICON" => $user->image($search['account_id'], 'avatars', './', 'applicant-avatar'),
						"DATE" => $system->time($search['date'], "F j, Y"),
						"APP" => $app
					));
				}
			}
			if (empty($apps)) {
				$apps = '<p style="text-align:center;">No applications</p>';
			}
			// Invites 
			$invites = $STYLE->getcode('invited', $tpl);
			$stack = '';
			$searches = $db->query("SELECT * FROM `clan-invitations` WHERE `clan_id` = '" . $clan['id'] . "' AND `resolved` = 0");
			if ($searches->rowCount() > 0) {
				while ($search = $searches->fetch()) {
					$stack .= $STYLE->tags($invites, array(
						"ID" => $search['id'],
						"MEMBER" => $db->fieldFetch('accounts', $search['account_id'], 'name'),
						"DATE" => $system->time($search['date'], "F j, Y")
					));
				}
			}
			if (empty($stack)) {
				$stack = '<p style="text-align:center;">No invitations sent</p>';
			}
			$tpl = $STYLE->getcode('invitation', $tpl);
			$tpl = str_replace(array($STYLE->getcode('invited', $tpl), $STYLE->getcode('applicated', $tpl)), '', $tpl);
			$tpl = $STYLE->tags($tpl, array(
				"ICON" => $user->image($clan['id'], 'clans', './', 'clan-icon-header'),
				"NAME" => $clan['name'],
				"INVITES" => $stack,
				"APPLICATIONS" => $apps,
				"ERROR" => $error
			));
			break;
		case 'application':
			if (isset($check) && !empty($check))
				$system->redirect($siteaddress . 'clans');
			if (!isset($clan) || empty($cid))
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$global_menu = $STYLE->getcode('menu_goback', $tpl);
			if (isset($_POST['confirmed'])) {
				// If he is the last member or the clan leader disband the clan.
				//$db->query("INSERT INTO `clan-members` (`id`, `clan_id`, `account_id`, `rank`, `joined`, `wins`, `loses`) VALUES (NULL, '".$clan['id']."', '" . $account['id'] . "', '".$clan['default-rank']."', UNIX_TIMESTAMP(), '', '');");
				$answers = '';
				$db->query("INSERT INTO `clan-app` (`id`, `clan_id`, `account_id`, `date`, `resolved`, `answers`) VALUES (NULL, '" . $clan['id'] . "', '" . $account['id'] . "', UNIX_TIMESTAMP(), '0', '$answers');");
				$appid = $db->fetch("SELECT * FROM `clan-app` WHERE clan_id = '" . $clan['id'] . "' AND account_id = '" . $account['id'] . "'");
				$appid = $appid['id'];
				$members = $db->query("SELECT * FROM `clan-members` WHERE clan_id = '" . $clan['id'] . "'");
				while ($member = $members->fetch()) {
					$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $member['rank'] . "'");
					if ($rank->rowCount() > 0) {
						$rank = $rank->fetch();
						if ($rank['privelage'] >= 3)
							$system->mail($member['account_id'], $account['id'], 'Application for ' . $clan['name'], 'Hello! I have applied for ' . $clan['name'] . '. You can see my application here: <a href="' . $siteaddress . '/clan/profile/' . urlencode($clan['name']) . '?action=invitations">Clan Applications</a> ');
					}
				}
				$system->message('Successful Application', 'We have notified the clan management of your application. Goodluck!', './clan/profile/' . urlencode($clan['name']), L_CONTINUE);
			}
			if (isset($_POST['Submit'])) {
				$answers = $_POST['formula'];
				$app = $db->query("SELECT * FROM `clan-application` WHERE clan_id = '" . $clan['id'] . "'")->fetch();
				$questions = explode('/', $app['questions']);
				$answered = '';
				foreach ($answers as $key => $answer) {
					$question = substr($questions[$key], strpos($questions[$key], '=') + 1);
					$answered .= 'Question; ' . $question . '<br class="clearfix">' . (empty($answer) ? 'Question passed' : 'Answered: ' . $answer) . '<br class="clearfix">';
				}
				$db->query("INSERT INTO `clan-app` (`id`, `clan_id`, `account_id`, `date`, `resolved`, `answers`) VALUES (NULL, '" . $clan['id'] . "', '" . $account['id'] . "', UNIX_TIMESTAMP(), '0', '$answered');");
				$members = $db->query("SELECT * FROM `clan-members` WHERE clan_id = '" . $clan['id'] . "'");
				while ($member = $members->fetch()) {
					$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $member['rank'] . "'");
					if ($rank->rowCount() > 0) {
						$rank = $rank->fetch();
						if ($rank['privelage'] >= 3)
							$system->mail($member['account_id'], $account['id'], 'Application for ' . $clan['name'], 'Hello! I have applied for ' . $clan['name'] . '. You can see my application here: <a href="' . $siteaddress . '/clan/profile/' . urlencode($clan['name']) . '?action=invitations">Clan Applications</a> ');
					}
				}
				$system->message('Successful Application', 'We have notified the clan management of your application. Goodluck!', './clan/profile/' . urlencode($clan['name']), L_CONTINUE);
			}

			// have I applied already?
			$check = $db->query("SELECT * FROM `clan-app` WHERE clan_id = '" . $clan['id'] . "' AND account_id='" . $account['id'] . "'")->rowCount();
			// no application required
			if ($check !== 0)
				$system->message('Application being evaluated', 'We have notified the clan management of your application. You will be notified for any update.', './clan/profile/' . urlencode($clan['name']), L_CONTINUE);

			// check if open or if an application is required

			$check = $db->query("SELECT * FROM `clan-application` WHERE clan_id = '" . $clan['id'] . "'");
			// no application required
			if ($check->rowCount() == 0)
				$system->confirm('Applying for ' . $clan['name'], 'Below by confirming you will apply for this clan.', './clan/profile/' . urlencode($clan['name']), 'confirmed');

			$tpl = $STYLE->getcode('applicant', $tpl);
			$check = $check->fetch();
			if ($check['open'] == '0')
				$system->message('Application Closed', 'We are currently not accepting new applicants.', './clan/profile/' . urlencode($clan['name']), L_CONTINUE);
			$questions = explode('/', $check['questions']);
			$formula = '';
			foreach ($questions as $key => $question) {
				$type = substr($question, 0, strpos($question, '='));
				$question = substr($question, strpos($question, '=') + 1);
				if (!empty($formula))
					$formula .= '<br class="clearfix"/><br class="clearfix">';
				if ($type == 'input')
					$formula .= $question . '<input name="formula[]" type="text" style="margin-left: 5px;">';
				if ($type == 'textarea')
					$formula .= $question . '<br class="clearfix"><textarea name="formula[]"style="margin-left: 5px;"></textarea>';
			}
			$tpl = $STYLE->tags($tpl, array(
				"AVATAR" => $user->image($clan['id'], 'clans', './', "application-ava"),
				"NAME" => $clan['name'],
				"MESSAGE" => $check['message'],
				"QUESTIONS" => $formula
			));
			break;
		case 'add-question':
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $check['rank'] . "'")->fetch();
			if ($rank['privelage'] < 3)
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			$app = $db->query("SELECT * FROM `clan-application` WHERE `clan_id` = '" . $clan['id'] . "'")->fetch();
			if (!empty($app['questions']))
				$app['questions'] .= '/';
			$app['questions'] .= 'input=Default Question';
			$db->query("UPDATE `clan-application` SET `questions` = '" . $app['questions'] . "' WHERE `clan-application`.`id` = '" . $app['id'] . "';");
			$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=edit-application');
			break;
		case 'add-rank':
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $check['rank'] . "'")->fetch();
			if ($rank['privelage'] < 3)
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			$db->query("INSERT INTO `clan-ranks` (`id`, `clan_id`, `account_id`, `name`, `privelage`, `sort`) VALUES (NULL, '" . $clan['id'] . "', '', 'New Rank', '1', '');");
			$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			break;
		case 'edit-application':
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $check['rank'] . "'")->fetch();
			if ($rank['privelage'] < 3)
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			$app = $db->query("SELECT * FROM `clan-application` WHERE `clan_id` = '" . $clan['id'] . "'");
			if ($app->rowCount() === 0) {
				$db->query("INSERT INTO `clan-application` (`id`, `clan_id`, `questions`, `message`, `open`) VALUES (NULL, '" . $clan['id'] . "', 'input=Default testing input field', 'Default', '1');");
				$app = $db->query("SELECT * FROM `clan-application` WHERE `clan_id` = '" . $clan['id'] . "'");
			}
			$app = $app->fetch();
			if (isset($_POST['edit-question'])) {
				switch ($_POST['edit-question']) {
					case 'Save':
						$type = '';
						if (isset($_POST['textarea']) && $_POST['textarea'] == 'true')
							$type = 'textarea=';
						if (isset($_POST['input']) && $_POST['input'] == 'true')
							$type = 'input=';
						$questions = explode('/', $app['questions']);
						$questions[$_POST['key']] = $type . $_POST['question'];
						$questions = implode('/', $questions);
						$db->query("UPDATE `clan-application` SET `questions` = '" . $questions . "' WHERE `clan-application`.`id` = '" . $app['id'] . "';");
						break;
					case 'Delete':
						$questions = explode('/', $app['questions']);
						unset($questions[$_POST['key']]);
						$questions = implode('/', $questions);
						$db->query("UPDATE `clan-application` SET `questions` = '" . $questions . "' WHERE `clan-application`.`id` = '" . $app['id'] . "';");
						break;
					default:
						break;
				}
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=edit-application');
			}
			if (isset($_POST['brief'])) {
				$brief = $secure->clean($_POST['brief']);
				$db->query("UPDATE `clan-application` SET `message` = '" . $brief . "' WHERE `clan_id` = '" . $clan['id'] . "';");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=edit-application');
			}
			if (isset($_POST['save-options'])) {
				$required = ($_POST['requires'] == "true" ? 1 : 0);
				$db->query("UPDATE `clans` SET `require-app` = '" . $required . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
				$application = ($_POST['application'] == "true" ? 1 : 0);
				$db->query("UPDATE `clan-application` SET `open` = '" . $application . "' WHERE `clan-application`.`id` = '" . $app['id'] . "';");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=edit-application');
			}
			$enable = false;
			$require = false;
			$disabled = '';
			if ($app['open'] == '1')
				$enable = true;
			else
				$disabled = ' disabled';
			if ($clan['require-app'] == '1')
				$require = true;
			else
				$disabled = ' disabled';
			$global_menu = $STYLE->getcode('menu_gobackclan', $tpl);
			$global_menu = $STYLE->tags($global_menu, array("NAME" => $db->fieldFetch('clans', $clan['id'], 'name')));
			$tpl = $STYLE->getcode('edit-app', $tpl);
			$questionar = $STYLE->getcode('question', $tpl);
			$questions = '';
			$questioning = explode('/', $app['questions']);
			foreach ($questioning as $key => $question) {
				$type = substr($question, 0, strpos($question, '='));
				$question = substr($question, strpos($question, '=') + 1);
				$questions .= $STYLE->tags($questionar, array(
					"KEY" => $key,
					"VALUE" => $question,
					"INPUT" => ($type == 'input' ? 'checked=true' : ''),
					"TEXT" => ($type == 'textarea' ? 'checked=true' : ''),
				)) . '<br/>';
			}
			if (empty($questions))
				$questions = 'No questions found';
			$tpl = str_replace($STYLE->getcode('question', $tpl), '', $tpl);
			$page_title .= ' > Manage Clan Application';
			$tpl = $STYLE->tags($tpl, array(
				"ICON" => $user->image($clan['id'], 'clans', './', 'application-ava'),
				"NAME" => $clan['name'],
				"BRIEFING" => $app['message'],
				"QUESTIONS" => $questions,
				"DISABLED" => $disabled,
				"REQUIRED" => ($require ? '<option value="true" selected>Required</option><option value="false">Unnecessary</option>' : '<option value="true">Required</option><option value="false" selected>Unnecessary</option>'),
				"APPLICATION" => ($enable ? '<option value="true" selected>Open</option><option value="false">Disable</option>' : '<option value="true">Open</option><option value="false" selected>Disable</option>')
			));
			break;
		case 'create':
			$error = '';
			if (isset($_POST['create'])) {
				// Validation
				// Name only characters and numbers, max 20 characters
				$name = $secure->clean($_POST['name']);
				if (strlen($name) > 20)
					$error = 'Clan name must be less than 20 characters';
				if (preg_match('/[^A-Za-z0-9 ]/', $name)) {
					$error = 'Clan name must only contain numbers and or letters';
				}
				// Check if name exists
				$checkname = $db->query("SELECT * FROM `clans` WHERE `name` LIKE '" . $name . "'");
				if ($checkname->rowCount() > 0)
					$error = 'Clan name already exists';
				// Clan abbreviation max 5 characters
				$abr = $secure->clean($_POST['abv']);
				if (strlen($abr) > 5)
					$error = 'Clan abv must be less than 5 characters';
				if (empty($name) || empty($abr)) {
					$error = 'Please fill out the required fields';
				}
				// Clan avatar dimensions 75 x 75
				$last = $db->query("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'clans'")->fetch();
				$last = $last['auto_increment'];
				error:
				$leaderrank = $secure->clean($_POST['default']);
				$bio = $secure->clean($_POST['bio']);
				if (!empty($_FILES['avatar']['name'])) {
					$image = $_FILES['avatar']['name'];
					if ($image) {
						$filename = stripslashes($_FILES['avatar']['name']);
						$extension = $user->getExtension($filename);
						$extension = strtolower($extension);
						// Make sure it is an image
						if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
							$error = 'Unpermited extension of the icon';
							goto error;
						}

						$newname = "./images/clans/" . $last . ".$extension";
						$copied = copy($_FILES['avatar']['tmp_name'], $newname);
						$size = filesize("$newname");
						list($width, $height) = getimagesize("$newname");
						unlink($_FILES['avatar']['tmp_name']);
					}
					if (!isset($copied)) {
						$error = 'Failed to upload avatar';
					} elseif ($height > 75 || $width > 75) {
						// Prevent Avatar over Dimension size
						$error = 'The icon dimensions permited are 75 px x 75 px';
						unlink("$newname");
					}
				}
				if (empty($error)) {
					if (empty($leaderrank))
						$leaderrank = 'Clan Leader';
					$db->query("INSERT INTO `clan-ranks` (`id`, `clan_id`, `account_id`, `name`, `privelage`, `sort`) VALUES (NULL, '" . $last . "', '', '" . $leaderrank . "', '4', '');");
					$leaderrank = $db->query("SELECT * FROM `clan-ranks` WHERE `clan_id` = '" . $last . "' AND privelage = '4' LIMIT 1")->fetch();
					$leaderrank = $leaderrank['id'];
					// Make new default rank
					$db->query("INSERT INTO `clan-ranks` (`id`, `clan_id`, `account_id`, `name`, `privelage`, `sort`) VALUES (NULL, '" . $last . "', '', 'Member', '1', '');");
					$default = $db->query("SELECT * FROM `clan-ranks` WHERE `clan_id` = '" . $last . "' AND privelage = '1' LIMIT 1")->fetch();
					$default = $default['id'];
					$db->query("INSERT INTO `clans` (`id`, `name`, `abbreviation`, `description`, `creator`, `registered`, `wins`, `loses`, `experience`, `bc`, `sponsored`, `default-rank`, `banner`)
					VALUES (NULL, '" . $name . "', '" . $abr . "', '" . $bio . "', '" . $account['id'] . "', unix_timestamp(), '', '', '', '', '', '" . $default . "', '');");
					$db->query("INSERT INTO `clan-members` (`id`, `clan_id`, `account_id`, `rank`, `joined`, `wins`, `loses`) VALUES (NULL, '" . $last . "', '" . $account['id'] . "', '" . $leaderrank . "', unix_timestamp(), '', '');");
					//Insert, make member and redirect to clan panel
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($name));
				}
			}
			if (isset($check) && !empty($check))
				$system->redirect($siteaddress . 'clans');
			$global_menu = $STYLE->getcode('menu_goback', $tpl);
			$tpl = $STYLE->getcode('create', $tpl);
			$page_title .= ' > Create a clan';
			$tpl = $STYLE->tags($tpl, array(
				"ERROR" => $error
			));
			break;
		case 'settings':
			//add rank, change privelage, change rank name, change member rank, kick member
			if (!$check)
				$system->redirect($siteaddress . 'clans');
			$clan = $clan->fetch();
			if ($clan['name'] !== $cid)
				$system->redirect($siteaddress . 'clans');
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $check['rank'] . "'")->fetch();
			if ($rank['privelage'] < 3)
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']));
			$error = '';
			$STYLE->__add('files', 'JAVA', '', '/inc/2.js');
			$STYLE->__add('files', 'JAVA', '', '/java/jquery-ui.js');
			if (isset($_POST['option'])) {
				$db->query("DELETE FROM `clan-members` WHERE `clan-members`.`account_id` = '" . $secure->clean($_POST['member']) . "'");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			}
			if (isset($_POST['default']) && $_POST['default'] == 'Save') {
				$db->query("UPDATE `clans` SET `default-rank` = '" . $_POST['default-rank'] . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			}
			if (isset($_POST['save-avatar'])) {
				if (!empty($_FILES['avatar']['name'])) {
					$image = $_FILES['avatar']['name'];
					if ($image) {
						$filename = stripslashes($_FILES['avatar']['name']);
						$extension = $user->getExtension($filename);
						$extension = strtolower($extension);
						// Make sure it is an image
						if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
							$error = 'Unpermited extension of the icon';
							goto error;
						}

						$newname = "./images/clans/" . $clan['id'] . ".$extension";
						$copied = copy($_FILES['avatar']['tmp_name'], $newname);
						$size = filesize("$newname");
						list($width, $height) = getimagesize("$newname");
						unlink($_FILES['avatar']['tmp_name']);
					}
					if (!isset($copied)) {
						$error = 'Failed to upload avatar';
					} elseif ($height > 75 || $width > 75) {
						// Prevent Avatar over Dimension size
						$error = 'The icon dimensions permited are 75 px x 75 px';
						unlink("$newname");
					}
				}
				if (empty($error))
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			}
			if (isset($_POST['save'])) {
				$_me = $rank['privelage'];
				$rank = $secure->clean($_POST['rank']);
				$name = $secure->clean($_POST['clanrank']);
				$role = $secure->clean($_POST['role']);
				// Verify my role 
				if ($_me < $role)
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
				$db->query("UPDATE `clan-ranks` SET `name` = '" . $name . "', `privelage` = '" . $role . "' WHERE `clan-ranks`.`id` = '" . $rank . "';");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			}
			if (isset($_POST['name'])) {
				$name = $secure->clean($_POST['name']);
				if ($name !== $clan['name']) {
					if (strlen($name) > 20)
						$error = 'Clan name must be less than 20 characters';
					if (preg_match('/[^A-Za-z0-9 ]/', $name)) {
						$error = 'Clan name must only contain numbers and or letters';
					}
					$checkname = $db->query("SELECT * FROM `clans` WHERE `name` LIKE '" . $name . "'");
					if ($checkname->rowCount() > 0)
						$error = 'Clan name already exists';
					if ($clan['bc'] < 10000)
						$error = 'You do not have enough BC for this; ' . (10000 - $clan['bc']) . '  left to purchase';
					if (empty($error)) {
						$clan['bc'] -= 10000;
						$db->query("UPDATE `clans` SET `name` = '" . $name . "',`bc` = '" . $clan['bc'] . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
						$system->redirect($siteaddress . 'clan/profile/' . urlencode($name) . '?action=settings');
					}
				}
			}
			if (isset($_POST['abv'])) {
				$abv = $secure->clean($_POST['abv']);
				if ($abv !== $clan['abbreviation']) {
					if (strlen($abv) > 5)
						$error = 'Clan abbreviation must be less than 5 characters';
					if ($clan['bc'] < 5000)
						$error = 'You do not have enough BC for this; ' . (5000 - $clan['bc']) . '  left to purchase';
					if (empty($error)) {
						$clan['bc'] -= 5000;
						$db->query("UPDATE `clans` SET `abbreviation` = '" . $abv . "',`bc` = '" . $clan['bc'] . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
						$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
					}
				}
			}
			if (isset($_POST['banner'])) {
				$banner = $secure->clean($_POST['banner']);
				if (isset($_POST['action']) && $_POST['action'] == 'Remove') {
					$db->query("UPDATE `clans` SET banner = '' WHERE `clans`.`id` = '" . $clan['id'] . "';");
					$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
				}
				if ($clan['banner'] !== $banner) {
					if ($clan['bc'] < 1000)
						$error = 'You do not have enough BC for this; ' . (1000 - $clan['bc']) . '  left to purchase';
					if (empty($error)) {
						$clan['bc'] -= 1000;
						$db->query("UPDATE `clans` SET banner = '" . $banner . "',`bc` = '" . $clan['bc'] . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
						$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
					}
				}
			}
			if (isset($_POST['biography'])) {
				$db->query("UPDATE `clans` SET `description` = '" . $secure->clean($_POST['biography']) . "' WHERE `clans`.`id` = '" . $clan['id'] . "';");
				$system->redirect($siteaddress . 'clan/profile/' . urlencode($clan['name']) . '?action=settings');
			}
			$page_title .= ' > Profile > ' . $clan['name'] . ' > Settings';
			$global_menu = $clan['bc'] . ' <img src="https://www.anime-blast.com/tpl/default/img/gold.png" style="width: 30px;"> ' . $STYLE->getcode('menu_gobackclan', $tpl);
			$tpl = $STYLE->getcode('settings', $tpl);
			$global_menu = $STYLE->tags($global_menu, array("NAME" => $clan['name']));
			if ($clan['experience'] == 0)
				$clan['experience'] = 1;
			$level = $db->fetch("SELECT * FROM levels WHERE experience < '" . $clan['experience'] . "' ORDER BY experience DESC LIMIT 1");
			$ranked = $db->query("SELECT * FROM clans ORDER BY experience+0 DESC LIMIT 10;");
			$ladderrank = 'Not ranked in ladder';
			$key = 1;
			while ($me = $ranked->fetch()) {
				if ($me['id'] == $clan['id']) {
					$ladderrank = '#' . $key;
					if ($key == 1) {
						$ladderrank = '"Them" RUN!';
					}
				}
				$key++;
			}
			$wr = 0;
			if ($clan['wins'] != 0 || $clan['loses'] != 0)
				$wr = round($clan['wins'] / ($clan['loses'] + $clan['wins']) * 100);
			if ($wr > 100 && $clan['loses'] == 0)
				$wr = 100;
			if ($wr > 100)
				$wr = 100;
			$width = 0;
			if ($clan['experience'] != 0) {
				$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($level['id'] + 1) . "'");
				if ($next)
					$width = round(($clan['experience'] / $next['experience']) * 100);
				else
					$width = 100;
			}
			$l = '<div class="levelBackground">
				<div class="levelFill" style="width: ' . $width . '%;"></div>
				<div class="levelNumber">' . $user->level($clan['experience']) . '</div>
				</div>';
			$m = $STYLE->getcode('rank', $tpl);
			$mtpl = $STYLE->getcode('member', $m);
			$m = str_replace($STYLE->getcode('member', $m), '', $m);
			$tpl = str_replace($STYLE->getcode('rank', $tpl), '', $tpl);
			$list = '';
			$ranking = $db->query("SELECT * FROM `clan-ranks` WHERE `clan_id` = '" . $clan['id'] . "'  ORDER BY `clan-ranks`.`sort` ASC");
			$ranks = array();
			$ranklist = array();
			$_me = $rank['privelage'];
			while ($rank = $ranking->fetch()) {
				$ranklist[$rank['id']] = $rank['name'];
				$roles = array('1' => 'Trial', '2' => 'Member', '3' => 'Captain');
				if ($_me == '4')
					$roles['4'] = 'Clan Leader';
				$r = '<select name="role">';
				foreach ($roles as $key => $role) {
					if ($key == $rank['privelage'])
						$r .= '<option value="' . $key . '" selected>' . $role . '</option>';
					else
						$r .= '<option value="' . $key . '">' . $role . '</option>';
				}
				$r .= '</select>';
				if (empty($ranks[$rank['id']])) {
					$sortable = '';
					if ($rank['privelage'] !== '4') {
						$sortable = ' class="sortable connectedSortable"';
					}
					if ($_me == '4') {
						$sortable = ' class="sortable connectedSortable"';
					}
					$ranks[$rank['id']] = $STYLE->tags($m, array(
						"ID" => $rank['id'],
						"CLANRANK" => $rank['name'],
						"ROLE" => $r,
						"SORTABLE" => $sortable
					));
				}
				$members = $db->query("SELECT * FROM `clan-members` WHERE `clan_id` = '" . $clan['id'] . "' AND `rank` = '" . $rank['id'] . "' ORDER BY `joined` ASC");
				$listed = '';
				while ($member = $members->fetch()) {
					$who = $db->fetch("SELECT * FROM accounts WHERE id = '" . $member['account_id'] . "'");
					$mlevel = $db->fetch("SELECT * FROM levels WHERE experience < '" . ($who['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");
					$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
					$r = 'Not ranked in ladder';
					$key = 1;
					while ($me = $ranked->fetch()) {
						if ($me['id'] == $who['id']) {
							$r = '#' . $key;
							if ($key == 1) {
								$mlevel['img'] = '1st';
								$r = 'The Champion!';
							}
						}
						$key++;
					}
					$temp = $mtpl;
					if ($who['id'] == $account['id'])
						$temp = str_replace($STYLE->getcode('kick', $temp), '', $temp);
					$listed .= $STYLE->tags($temp, array(
						"ID" => $member['account_id'],
						"RANK" => $mlevel['level'] . ' ' . $user->image($mlevel['img'], 'ranks', './', '" style="left:0px;position:relative;margin:0 !important;vertical-align:bottom;width:20px;bottom: 5px;"'),
						"NAME" => $who['name'],
						"DATE" => $system->time($member['joined'], "F j, Y")
					));
				}
				$ranks[$rank['id']] = $STYLE->tags($ranks[$rank['id']], array("MEMBER" => $listed));
			}
			$defaultrank = '<select name="default-rank">';
			foreach ($ranklist as $key => $name) {
				if ($key == $clan['default-rank'])
					$defaultrank .= '<option value="' . $key . '" selected>' . $name . '</option>';
				else
					$defaultrank .= '<option value="' . $key . '">' . $name . '</option>';
			}
			$defaultrank .= '</select>';
			$list = implode('', $ranks);
			$tpl = $STYLE->tags($tpl, array(
				"ERROR" => $error,
				"ID" => $clan['id'],
				"BANNER" => $clan['banner'],
				"DEFAULT" => $defaultrank,
				"AVATAR" => $user->image($clan['id'], 'clans', './', 'img'),
				"NAME" => $clan['name'],
				"AB" => $clan['abbreviation'],
				"LEVEL" => $l,
				"LADDER" => $ladderrank,
				"REGISTER" => $system->time($clan['registered'], "F j, Y"),
				"CREATOR" => $user->name($clan['creator']),
				"DESCRIPTION" => $clan['description'],
				"WINS" => $clan['wins'],
				"LOSES" => $clan['wins'],
				"WR" => $wr . ' %',
				"EXPERIENCE" => $clan['experience'],
				"MEMBERS" => $list
			));
			break;
		default:
			$system->redirect($siteaddress . 'clans');
			break;
	}
	goto skipintro;
}
//Check if he's in a clan? 

if ($clan->rowCount() > 0) {
	$original = $tpl;
	$tpl = $STYLE->getcode('information', $tpl);
	$clan = $clan->fetch();
	//Check my rank
	$member = $db->query("SELECT * FROM `clan-members` WHERE `account_id` = '" . $account['id'] . "' AND `clan_id` = '" . $clan['id'] . "'");
	//$clan = $db->fetch("SELECT * FROM `clans` WHERE `id` = '".$rank['clan_id']."'");

	// Generate Global Menu
	$page_title .= ' > Profile > ' . $clan['name'];
	if ($member->rowCount() > 0) {
		$member = $member->fetch();
		$global_menu = $STYLE->getcode('menu_leave', $original);
		$rankprivelage = $db->fetch("SELECT * FROM `clan-ranks` WHERE `id` = '" . $member['rank'] . "'");
		if ($rankprivelage['privelage'] >= 3) {
			$STYLE->__add('files', 'JAVA', '', '/inc/2.js');
			$global_menu .= $STYLE->getcode('menu_manage', $original);
			$global_menu .= $STYLE->getcode('menu_app', $original);
			$global_menu .= $STYLE->getcode('menu_invit', $original);
			$global_menu = $STYLE->tags($global_menu, array(
				"NAME" => $clan['name'],
				"ITOTAL" => ($db->query("SELECT * FROM `clan-app` WHERE `clan_id` = '" . $clan['id'] . "' AND `resolved` = '0'")->rowCount() == 0 ? '' : '<p class="clanalert">' . $db->query("SELECT * FROM `clan-app` WHERE `clan_id` = '" . $clan['id'] . "' AND `resolved` = '0'")->rowCount() . '</p>')
			));
		} else {
			$tpl = str_replace($STYLE->getcode('leader', $tpl), '', $tpl);
			$tpl = str_replace($STYLE->getcode('privelage', $tpl), '', $tpl);
		}
	} else {
		$tpl = str_replace($STYLE->getcode('leader', $tpl), '', $tpl);
		$tpl = str_replace($STYLE->getcode('privelage', $tpl), '', $tpl);
	}
	if ($clan['experience'] == 0)
		$clan['experience'] = 1;
	$level = $db->fetch("SELECT * FROM levels WHERE experience < '" . $clan['experience'] . "' ORDER BY experience DESC LIMIT 1");
	$ranked = $db->query("SELECT * FROM clans ORDER BY experience+0 DESC LIMIT 10;");
	$ladderrank = 'Not ranked in ladder';
	$key = 1;
	while ($me = $ranked->fetch()) {
		if ($me['id'] == $clan['id']) {
			$ladderrank = '#' . $key;
			if ($key == 1) {
				$ladderrank = '"Them" RUN!';
			}
		}
		$key++;
	}
	$wr = 0;
	if ($clan['wins'] != 0 || $clan['loses'] != 0)
		$wr = round($clan['wins'] / ($clan['loses'] + $clan['wins']) * 100);
	if ($wr > 100 && $clan['loses'] == 0)
		$wr = 100;
	if ($wr > 100)
		$wr = 100;
	$width = 0;
	if ($clan['experience'] != 0) {
		$next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($level['id'] + 1) . "'");
		if ($next)
			$width = round(($clan['experience'] / $next['experience']) * 100);
		else
			$width = 100;
	}
	$l = '<div class="levelBackground">
				<div class="levelFill" style="width: ' . $width . '%;"></div>
				<div class="levelNumber">' . $user->level($clan['experience']) . '</div>
				</div>';
	$m = $STYLE->getcode('rank', $tpl);
	$mtpl = $STYLE->getcode('member', $m);
	$m = str_replace($STYLE->getcode('member', $m), '', $m);
	$tpl = str_replace($STYLE->getcode('rank', $tpl), '', $tpl);
	$list = '';
	$ranking = $db->query("SELECT * FROM `clan-ranks` WHERE `clan_id` = '" . $clan['id'] . "' ORDER BY `clan-ranks`.`sort` ASC");
	$ranks = array();
	while ($rank = $ranking->fetch()) {
		$role = $rank['privelage'];
		switch ($role) {
			case '1':
				$role = 'Trial';
				break;
			case '2':
				$role = 'Member';
				break;
			case '3':
				$role = 'Captain';
				break;
			case '4':
				$role = 'Clan Leader';
				break;
		}
		if (empty($ranks[$rank['id']])) {
			$ranks[$rank['id']] = $STYLE->tags($m, array(
				"ID" => $rank['id'],
				"CLANRANK" => $rank['name'],
				"ROLE" => $role
			));
		}
		$members = $db->query("SELECT * FROM `clan-members` WHERE `clan_id` = '" . $clan['id'] . "' AND `rank` = '" . $rank['id'] . "' ORDER BY `joined` ASC");
		$listed = 0;
		while ($member = $members->fetch()) {
			$who = $db->fetch("SELECT * FROM accounts WHERE id = '" . $member['account_id'] . "'");
			$mlevel = $db->fetch("SELECT * FROM levels WHERE experience < '" . ($who['experience'] + 1) . "' ORDER BY experience DESC LIMIT 1");
			$ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
			$r = 'Not ranked in ladder';
			$key = 1;
			while ($me = $ranked->fetch()) {
				if ($me['id'] == $who['id']) {
					$r = '#' . $key;
					if ($key == 1) {
						$mlevel['img'] = '1st';
						$r = 'The Champion!';
					}
				}
				$key++;
			}
			$ranks[$rank['id']] .= $STYLE->tags($mtpl, array(
				"RANK" => $mlevel['level'] . ' ' . $user->image($mlevel['img'], 'ranks', './', '" style="left:0px;position:relative;margin:0 !important;vertical-align:bottom;width:20px;bottom: 5px;"'),
				"NAME" => $user->profile($who['id']),
				"DATE" => $system->time($member['joined'], "F j, Y")
			));
			$listed++;
		}
		if ($listed === 0)
			unset($ranks[$rank['id']]);
	}
	$list = implode('', $ranks);
	$tpl = $STYLE->tags($tpl, array(
		"ID" => $clan['id'],
		"BANNER" => (!empty($clan['banner']) ? ' style="background:url(' . $clan['banner'] . ')"' : ''),
		"AVATAR" => $user->image($clan['id'], 'clans', './', 'clan-icon-profile card-img rounded'),
		"NAME" => $clan['name'],
		"AB" => $clan['abbreviation'],
		"LEVEL" => $l,
		"LADDER" => $ladderrank,
		"REGISTER" => $system->time($clan['registered'], "F j, Y"),
		"CREATOR" => $user->name($clan['creator']),
		"DESCRIPTION" => $clan['description'],
		"WINS" => $clan['wins'],
		"LOSES" => $clan['loses'],
		"WR" => $wr . ' %',
		"EXPERIENCE" => $clan['experience'],
		"MEMBERS" => $list
	));
} else {
	if ($account)
		$page_title .= (!$check) ? ' > Search for a clan' : '';
	// Show a catalog of the clans ingame along with searching...

	$cpage = $STYLE->getcode('catalog', $tpl);
	$global_menu = $STYLE->getcode('menu_create', $tpl);
	if ($check) {
		$global_menu = $STYLE->getcode('menu_leave', $tpl);
		$global_menu .= $STYLE->getcode('menu_gobackclan', $tpl);
		$global_menu = $STYLE->tags($global_menu, array(
			"NAME" => $db->fieldFetch('clans', $check['clan_id'], 'name')
		));
	}
	$ct = $STYLE->getcode('clantemplate', $tpl);
	if ($check || !$account)
		$ct = str_replace($STYLE->getcode('application', $ct), '', $ct);
	if (!$account)
		$global_menu = '';
	$tpl = str_replace(array($STYLE->getcode('clantemplate', $tpl)), '', $cpage);
	$sponsored = $db->query("SELECT * FROM `clans` WHERE sponsored > 0");
	$sponsores = '';
	if ($sponsored->rowCount() > 0) {
		$sponsores = 'Hello friend!';
	}
	if (empty($sponsores))
		$tpl = str_replace(array($STYLE->getcode('sponsor', $tpl)), '', $tpl);
	$clans = $db->query("SELECT * FROM `clans` ORDER BY experience DESC");
	$list = '';
	$number = 0;
	while ($clan = $clans->fetch()) {
		$number++;
		// Get leaders
		$leaders = '';
		$captains = '';
		$members = $db->query("SELECT * FROM `clan-members` WHERE `clan_id` = '" . $clan['id'] . "'");
		while ($member = $members->fetch()) {
			$rank = $db->query("SELECT * FROM `clan-ranks` WHERE `id` = '" . $member['rank'] . "'");
			if ($rank->rowCount() > 0) {
				$rank = $rank->fetch();
				if ($rank['privelage'] == 3) {
					if (!empty($leaders))
						$captains .= ', ';
					$captains .= $db->fieldFetch('accounts', $member['account_id'], 'name');
				} elseif ($rank['privelage'] == 4) {
					if (!empty($leaders))
						$leaders .= ', ';
					$leaders .= $db->fieldFetch('accounts', $member['account_id'], 'name');
				}
			}
		}
		if (!empty($captains))
			$leaders .= '<br/>Co-Leader(S): ' . $captains;
		$myclan = '';
		if ($check && $check['clan_id'] == $clan['id'])
			$myclan = ' highlightme';
		$wr = 0;
		if ($clan['wins'] != 0 || $clan['loses'] != 0)
			$wr = round($clan['wins'] / ($clan['loses'] + $clan['wins']) * 100);
		if ($wr > 100 && $clan['loses'] == 0)
			$wr = 100;
		if ($wr > 100)
			$wr = 100;
		$list .= $STYLE->tags($ct, array(
			"MYCLAN" => $myclan,
			"SPOT" => "#" . $number,
			"BANNER" => (!empty($clan['banner']) ? ' style="background:url(' . $clan['banner'] . ')"' : ''),
			"NAME" => $clan['name'],
			"ABR" => '[ ' . $clan['abbreviation'] . ' ]',
			"AVATAR" => $user->image($clan['id'], 'clans', './', 'clan-icon'),
			"BIO" => $clan['description'],
			"RATIO" => $clan['wins'] . ' - ' . $clan['loses'],
			"XP" => $clan['experience'],
			"BC" => (float)$clan['bc'] . ' BC POT',
			"WR" => $wr,
			"LINK" => $siteaddress . 'clan/profile/' . urlencode($clan['name']),
			"LEADERS" => $leaders
		));
	}
	$tpl = $STYLE->tags($tpl, array(
		"CLANS" => $list,
		"SPONSORED" => $sponsores
	));
}

skipintro:
$output .= $tpl;
