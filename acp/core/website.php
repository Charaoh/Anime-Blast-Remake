<?php
$tpl = $STYLE->open('acp/website.tpl');
if (isset($_GET['module'])) {
    $mode = $secure->clean($_GET['module']);
} else {
    $mode = '';
}

if ($mode == 'settings') {
    $tpl = $STYLE->getcode('edit', $tpl);
    if (isset($_POST['submit'])) {
        $db->query("UPDATE settings SET `value`='" . $_POST['turn'] . "' WHERE `name`='Turn_Time'");
        $db->query("UPDATE settings SET `value`='" . $_POST['range'] . "' WHERE `name`='Experience_Range'");
        $db->query("UPDATE settings SET `value`='" . $_POST['mana'] . "' WHERE `name`='Mana_Increase'");
        $db->query("UPDATE settings SET `value`='" . $_POST['gold_win'] . "' WHERE `name`='Gold_Earn'");
        $db->query("UPDATE settings SET `value`='" . $_POST['gold_lose'] . "' WHERE `name`='Gold_Lose'");
        $db->query("UPDATE settings SET `value`='" . $_POST['gold_winQ'] . "' WHERE `name`='Gold_EarnQ'");
        $db->query("UPDATE settings SET `value`='" . $_POST['gold_loseQ'] . "' WHERE `name`='Gold_LoseQ'");
        $db->query("UPDATE settings SET `value`='" . $_POST['max'] . "' WHERE `name`='Max_Earn'");
        $db->query("UPDATE settings SET `value`='" . $_POST['min'] . "' WHERE `name`='Min_Earn'");
        $db->query("UPDATE settings SET `value`='" . $_POST['starters'] . "' WHERE `name`='starters'");
        $db->query("UPDATE settings SET `value`='" . $_POST['admin'] . "' WHERE `name`='admin_group'");
        $db->query("UPDATE settings SET `value`='" . $_POST['mc1'] . "' WHERE `name`='First_Mana'");
        $db->query("UPDATE settings SET `value`='" . $_POST['mc2'] . "' WHERE `name`='Second_Mana'");
        if (isset($_POST['ai_account']) && !empty($_POST['ai_account']))
            $db->query("UPDATE accounts SET `group`='-1' WHERE `id`='" . $_POST['ai_account'] . "'");
        $db->query("UPDATE settings SET `value`='" . $_POST['ai_account'] . "' WHERE `name`='AI_Account'");
        $db->query("UPDATE settings SET `value`='" . $_POST['ai_reward'] . "' WHERE `name`='AI_Reward'");
        $hideExclusive = '';
        if (isset($_POST['Hide_Exclusives'])) $hideExclusive = '1';
        $db->query("UPDATE settings SET `value`='" . $hideExclusive . "' WHERE `name`='Hide-Exclusives'");
        $db->query("UPDATE settings SET `value`='" . $_POST['ai_reward'] . "' WHERE `name`='AI_Reward'");
        $mortals = '';
        if (isset($_POST['mortalMenCanSEE'])) $mortals = '1';
        $db->query("UPDATE settings SET `value`='" . $mortals . "' WHERE `name`='AI_Battles'");
        if (isset($_POST['cleanUPExclusives']) && !empty($_POST['cleanUPExclusives']))
            $db->query("UPDATE accounts SET `activated`='0'");
        $db->query("UPDATE settings SET `value`='" . $_POST['exclusives'] . "' WHERE `name`='exclusives'");
    } else {
        $tpl = str_replace(array($STYLE->getcode('success', $tpl)), '', $tpl);
    }

    $ai = '<select id="ai_account" name="ai_account">';
    $ai .= '<option value="" disabled>Select an account to set as the current bot</option>';
    $registered_accounts = $db->query("SELECT * FROM accounts");
    $current_ai = $system->data('AI_Account');
    while ($a = $registered_accounts->fetch()) {
        $checked = "";
        if ($a['id'] == $current_ai) $checked = " selected=true";
        $ai .= '<option value="' . $a['id'] . '"' . $checked . '>' . $a['name'] . '</option>';
    }
    $ai .= '</select>';

    $tpl = $STYLE->tags($tpl, array(
        "HIDEEXCLUSIVE" => ($system->data('Hide-Exclusives') === '1' ? ' checked=true' : ''),
        "BU" => $system->data('Batch', 1),
        "STARTERS" =>  $system->data('starters'),
        "TURN" => $system->data('Turn_Time', 1),
        "AFK" => $system->data('Connect_Time', 1),
        "RANGE" => $system->data('Experience_Range', 1),
        "MANA" => $system->data('Mana_Increase', 1),
        "GW" => $system->data('Gold_Earn', 1),
        "GL" => $system->data('Gold_Lose', 1),
        "GWQ" => $system->data('Gold_EarnQ', 1),
        "GLQ" => $system->data('Gold_LoseQ', 1),
        "MAX" => $system->data('Max_Earn', 1),
        "MIN" => $system->data('Min_Earn', 1),
        "ADMIN" => $system->data('admin_group'),
        "MC1" => $system->data('First_Mana'),
        "MC2" => $system->data('Second_Mana'),
        "AI" => $ai,
        "MORTALMEN" => ($system->data('AI_Battles') == 1 ? "checked=checked" : ""),
        "AIREWARD" => $system->data('AI_Reward'),
        "EXCLUSIVES" => $system->data('exclusives'),
        "L_SUBMIT" => L_SUBMIT
    ));
} else {
    $tpl = str_replace(array($STYLE->getcode('edit', $tpl)), '', $tpl);
}

if ($mode == 'character') {
    if (isset($_GET['id'])) {
        $character = $db->query("SELECT * FROM characters WHERE id='" . $_GET['id'] . "'");
        if ($character->rowCount() > 0) {
            $character = $character->fetch();
            if (isset($_POST['Delete'])) {
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
				('" . $character['id'] . "', 'There has been an update to this characters face picture','" . $system->data('Batch', 1) . "')");
                $user->deletethisfile("../../images/characters/" . $character['id']);
                $system->message(L_DELETE, 'Character face picture was deleted', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
            }
            if (isset($_POST['Delete-Slanted'])) {
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
				('" . $character['id'] . "', 'There has been an update to this characters face picture','" . $system->data('Batch', 1) . "')");
                $user->deletethisfile("../../images/characters/slanted/" . $character['id']);
                $system->message(L_DELETE, 'Character slanted face picture was deleted', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
            }
            if (isset($_POST['skill-delete']) && isset($_POST['skill-id'])) {
                $skills = explode(',', $character['skills']);
                if (array_search($_POST['skill-id'], $skills) !== false) {
                    unset($skills[array_search($_POST['skill-id'], $skills)]);
                    $skills = implode(',', $skills);
                    $db->query("UPDATE characters SET `skills`='" . $skills . "' WHERE id='" . $character['id'] . "'");
                }
                $skill = $db->query("SELECT * FROM skills WHERE id='" . $_POST['skill-id'] . "'");
                $skill = $skill->fetch();
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
				('" . $character['id'] . "', '[b][u]" . $skill['name'] . "[/u][/b] has been removed.','" . $system->data('Batch', 1) . "')");
                $system->redirect('./?s=website&module=character&id=' . $character['id']);
            }
            if (isset($_POST['change-stats']) && isset($_POST['mc']) || isset($_POST['change-stats']) && isset($_POST['cd'])) {
                if (is_numeric($_POST['mc']) && is_numeric($_POST['cd']) || $_POST['cd'] == 'None' || $_POST['cd'] == 'Infinite') {
                    $skill = $db->query("SELECT * FROM skills WHERE id='" . $_POST['skill-id'] . "'");
                    $skill = $skill->fetch();
                    if ($skill['cooldown'] !== $_POST['cd'])
                        $db->query("INSERT INTO balance (cid,detail,batch) VALUES ('" . $character['id'] . "', '[b][u]" . $skill['name'] . "[/u][/b]: <br/> The cooldown of this skill has changed to " . $_POST['cd'] . "','" . $system->data('Batch', 1) . "')");
                    if ($skill['cost'] !== $_POST['mc'])
                        $db->query("INSERT INTO balance (cid,detail,batch) VALUES ('" . $character['id'] . "', '[b][u]" . $skill['name'] . "[/u][/b]: <br/> The mana cost of this skill has changed to " . $_POST['mc'] . "','" . $system->data('Batch', 1) . "')");
                    $db->query("UPDATE skills SET `cost`='" . $_POST['mc'] . "', `cooldown`='" . $_POST['cd'] . "' WHERE id='" . $_POST['skill-id'] . "'");
                    //$system->redirect('./?s=website&module=character&id='.$character['id']);
                }
            }
            if (isset($_POST['change']) && isset($_POST['description-' . $_POST['skill-id']])) {
                $s = $db->fetch("SELECT * FROM skills WHERE id='" . $_POST['skill-id'] . "'");
                if (isset($_POST['sname-' . $s['id']])) {
                    $name = '';
                    if ($s['name'] !== $_POST['sname-' . $s['id']])
                        $name = ",name='" . $secure->clean($_POST['sname-' . $s['id']]) . "'";
                    $db->query("UPDATE skills SET `desc`='" . $secure->clean($_POST['description-' . $s['id']]) . "'$name WHERE id='" . $s['id'] . "'");
                    $system->redirect('./?s=website&module=character&id=' . $character['id']);
                }
            }
            if (isset($_POST['Avi'])) {
                $image = $_FILES['image']['name'];
                if ($image) {
                    $filename = stripslashes($_FILES['image']['name']);
                    $extension = $user->getExtension($filename);
                    $extension = strtolower($extension);
                    // Make sure it is an image
                    if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                    }
                    // Delete possible existing avatar
                    $user->deletethisfile("./../images/characters/" . $character['id']);
                    $image_name = time() . '.' . $extension;
                    $newname = "./../images/characters/" . $character['id'] . ".$extension";
                    $copied = copy($_FILES['image']['tmp_name'], $newname);
                    $size = filesize("$newname");
                    list($width, $height) = getimagesize("$newname");
                    unlink($_FILES['image']['tmp_name']);
                }
                if (!isset($copied)) {
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'New face picture has been added','" . $system->data('Batch', 1) . "')");
                    $system->message(L_UPDATED, 'Character face picture update', './?s=website&module=character&id=' . $character['id'], L_CONTINUE);
                }
            }
            if (isset($_POST['Avi-Slanted'])) {
                $image = $_FILES['image-slanted']['name'];
                if ($image) {
                    $filename = stripslashes($_FILES['image-slanted']['name']);
                    $extension = $user->getExtension($filename);
                    $extension = strtolower($extension);
                    // Make sure it is an image
                    if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                    }
                    // Delete possible existing avatar
                    $user->deletethisfile("./../images/characters/slanted/" . $character['id']);
                    $image_name = time() . '.' . $extension;
                    $newname = "./../images/characters/slanted/" . $character['id'] . ".$extension";
                    $copied = copy($_FILES['image-slanted']['tmp_name'], $newname);
                    $size = filesize("$newname");
                    list($width, $height) = getimagesize("$newname");
                    unlink($_FILES['image-slanted']['tmp_name']);
                }
                if (!isset($copied)) {
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'New slanted face picture has been added','" . $system->data('Batch', 1) . "')");
                    $system->message(L_UPDATED, 'Character slanted face picture update', './?s=website&module=character&id=' . $character['id'], L_CONTINUE);
                }
            }
            if (isset($_POST['delete'])) {
                $db->query("DELETE FROM characters WHERE id='" . $character['id'] . "'");
                $system->redirect('./?s=website&module=character');
            }
            if (isset($_POST['change-description'])) {
                $name = '';
                if (isset($_POST['name'])) {
                    if ($character['name'] !== $_POST['name'])
                        $name = ",name='" . $_POST['name'] . "'";
                }
                if (isset($_POST['who'])) {
                    $who = ",`who`='" . $_POST['who'] . "'";
                }
                if (isset($_POST['passive-skills'])) {
                    $passive = ",`passive`='" . $_POST['passive-skills'] . "'";
                }
                $db->query("UPDATE characters SET `desc`='" . $_POST['description'] . "'$name$who$passive WHERE id='" . $character['id'] . "'");
                $system->redirect('./?s=website&module=character&id=' . $character['id']);
            }
            if (isset($_POST['change-stat']) && isset($_POST['mana']) || isset($_POST['health'])) {
                if (is_numeric($_POST['mana']) || is_numeric($_POST['health'])) {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'This characters health is now " . $_POST['health'] . " from " . $character['health'] . "','" . $system->data('Batch', 1) . "')");
                    $db->query("UPDATE characters SET `mana`='" . $_POST['mana'] . "', `health`='" . $_POST['health'] . "' WHERE id='" . $character['id'] . "'");
                    $system->redirect('./?s=website&module=character&id=' . $character['id']);
                }
            }

            if (isset($_POST['update-classes'])) {
                $s = $db->fetch("SELECT * FROM skills WHERE id='" . $_POST['skill-id'] . "'");
                $current = explode(',', $s['classes']);
                $current['names'] = '';
                $current['new'] = '';
                foreach ($current as $class) {
                    if ($db->fieldFetch('classes', $class, 'name') == 'undefined') continue;
                    if (!empty($current['names']))
                        $current['names'] .= ',';

                    $current['names'] .= $db->fieldFetch('classes', $class, 'name');
                }
                foreach ($_POST['classes'] as $class) {
                    if ($db->fieldFetch('classes', $class, 'name') == 'undefined') continue;
                    if (!empty($current['new']))
                        $current['new'] .= ',';

                    $current['new'] .= $db->fieldFetch('classes', $class, 'name');
                }
                $classes = implode(',', $_POST['classes']);
                $db->query("UPDATE skills SET `classes`='" . $classes . "' WHERE id='" . $s['id'] . "'");
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', '" . $s['name'] . " classes have been changed from " . (empty($current['names']) ? 'Nothing' : $current['names']) . " to " . $current['new'] . "','" . $system->data('Batch', 1) . "')");
                $system->redirect('./?s=website&module=character&id=' . $character['id']);
            }

            if (isset($_POST['add']) && isset($_POST['all-skills'])) {
                if (!empty($character['skills'])) {
                    $skills = explode(',', $character['skills']);
                } else
                    $skills = $_POST['all-skills'];
                if (is_array($skills)) {
                    $skills[] = $_POST['all-skills'];
                    $skills = implode(',', $skills);
                }
                $skill = $db->query("SELECT * FROM skills WHERE id='" . $_POST['all-skills'] . "'");
                $skill = $skill->fetch();
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'This character has a new skill: <br/> 
					" . $user->image($skill['id'], 'skills', './../') . "
					[b][u]" . $skill['name'] . "[/u][/b]:<br/> " . $skill['desc'] . " ','" . $system->data('Batch', 1) . "')");
                $db->query("UPDATE characters SET skills ='" . $skills . "' WHERE id = '" . $character['id'] . "'");
                $system->redirect('./?s=website&module=character&id=' . $character['id'] . '', true);
            }

            // Remove
            if (isset($_POST['remove']) && isset($_POST['current-skills'])) {
                $skills = explode(',', $character['skills']);
                unset($skills[array_search($_POST['current-skills'], $skills)]);
                $skills = implode(',', $skills);
                $skill = $db->query("SELECT * FROM skills WHERE id='" . $_POST['current-skills'] . "'");
                $skill = $skill->fetch();
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', '[b][u]" . $skill['name'] . "[/u][/b] has been removed.','" . $system->data('Batch', 1) . "')");
                $db->query("UPDATE characters SET skills ='" . $skills . "' WHERE id = '" . $character['id'] . "'");
                $system->redirect('./?s=website&module=character&id=' . $character['id'] . '', true);
            }

            $tpl = $STYLE->open('acp/characters.tpl');
            if (isset($_GET['change-picture'])) {
                $tpl = $STYLE->getcode('change-avatar', $tpl);
                $tpl = $STYLE->tags(
                    $tpl,
                    array(
                        "ID" => $character['id'],
                        "NAME" => $character['name'],
                        "AVATAR" => $user->image($character['id'], 'characters', './../'),
                        "SLANTED" => $user->image($character['id'], 'characters/slanted', './../'),
                        "L_SUBMIT" => L_SUBMIT,
                        "L_DELETE" => L_DELETE
                    )
                );
            } else {
                $return = '';
                $unlocked = '';
                if (!empty($character['skills'])) {
                    if (strpos($character['skills'], ',') !== false)
                        $skills = explode(',', $character['skills']);
                    else
                        $skills = array($character['skills']);
                    $count = 0;
                    $add = '';
                    $keys = array();
                    foreach ($skills as $skill) {
                        $keys[] = $skill;
                    }
                    foreach ($skills as $skill) {
                        $s = $db->query("SELECT * FROM skills WHERE id = '" . $skill . "'");
                        if ($s->rowCount() > 0) {
                            $s = $s->fetch();
                            $unlocked .= '<option value="' . $s['id'] . '">' . $system->present($s['name']) . '</option>';
                            //Search for alternate skills 

                            $effects = explode(',', $s['effects']);
                            foreach ($effects as $effect) {
                                if ($db->fieldFetch('effects', $effect, 'replace') !== 'undefined' && $db->fieldFetch('effects', $effect, 'replace') !== '') {
                                    $replacements = true;
                                    $ez = explode('|', $db->fieldFetch('effects', $effect, 'replace'));
                                    do {
                                        if (!empty($salt))
                                            $ez = $salt;
                                        $salt = array();
                                        foreach ($ez as $looks) {
                                            if (in_array($looks, $keys)) continue;
                                            $keys[] = $looks;
                                            $alt = $db->query("SELECT * FROM skills WHERE id = '" . $looks . "'");
                                            if ($alt->rowCount() > 0) {
                                                $alt = $alt->fetch();
                                                $classes = '';
                                                $alt['classes'] = explode(',', $alt['classes']);
                                                $iclass = $db->query("SELECT * FROM `classes`");
                                                while ($class = $iclass->fetch()) {
                                                    if (empty($class['name'])) continue;
                                                    $checked = '';
                                                    if (array_search($class['id'], $alt['classes']) !== false)
                                                        $checked = ' checked="checked"';
                                                    $classes .= $class['name'] . '<input name="classes[]" type="checkbox" class="globaltab" id="classes" value="' . $class['id'] . '" ' . $checked . '/>';
                                                }
                                                $temp = $STYLE->getcode('skill', $tpl);
                                                $temp = $STYLE->tags(
                                                    $temp,
                                                    array(
                                                        "SID" => $alt['id'],
                                                        "SNAME" => $alt['name'],
                                                        "PICTURE" => $user->image($alt['id'], 'skills', './../', 'skill'),
                                                        "SD" => $alt['desc'],
                                                        "SC" => $alt['cost'],
                                                        "CD" => $alt['cooldown'],
                                                        "CLASSES" => $classes
                                                    )
                                                );
                                                $add .= $temp;
                                                $aeffects = explode(',', $alt['effects']);
                                                foreach ($aeffects as $ae) {
                                                    if ($db->fieldFetch('effects', $ae, 'replace') !== 'undefined' && $db->fieldFetch('effects', $ae, 'replace') !== '') {
                                                        $as = explode('|', $db->fieldFetch('effects', $ae, 'replace'));
                                                        foreach ($as as $askill) {
                                                            if (in_array($askill, $keys)) continue;
                                                            $salt[] = $askill;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if (empty($salt))
                                            $replacements = false;
                                    } while ($replacements === true);
                                }
                            }
                            $classes = '';
                            $s['classes'] = explode(',', $s['classes']);
                            $iclass = $db->query("SELECT * FROM `classes`");
                            while ($class = $iclass->fetch()) {
                                if (empty($class['name'])) continue;
                                $checked = '';
                                if (array_search($class['id'], $s['classes']) !== false)
                                    $checked = ' checked="checked"';
                                $classes .= $class['name'] . '<input name="classes[]" type="checkbox" class="globaltab" id="classes" value="' . $class['id'] . '" ' . $checked . '/>';
                            }

                            $temp = $STYLE->getcode('skill', $tpl);
                            $temp = $STYLE->tags(
                                $temp,
                                array(
                                    "SID" => $s['id'],
                                    "SNAME" => $s['name'],
                                    "PICTURE" => $user->image($s['id'], 'skills', './../', 'skill'),
                                    "SD" => $s['desc'],
                                    "SC" => $s['cost'],
                                    "CD" => $s['cooldown'],
                                    "CLASSES" => $classes
                                )
                            );
                            $return .= $temp;
                            $count++;
                        }
                    }
                    if (!empty($add)) {
                        $return = $return . '<div style="clear: both;"><center><b>Alternate Skills</b></center></div>' . $add;
                    }
                } else {
                    $return = 'This character has yet to be asigned skills!';
                }
                $all = $db->query("SELECT * FROM `skills` ORDER BY `id` DESC");
                $database = '';
                while ($it = $all->fetch()) {
                    $database .= '<option value="' . $it['id'] . '">' . $system->present($it['name']) . '</option>';
                }
                $tpl = $STYLE->getcode('edit-character', $tpl);
                $tpl = $STYLE->tags(
                    $tpl,
                    array(
                        "ID" => $character['id'],
                        "NAME" => $character['name'],
                        "AVATAR" => $user->image($character['id'], 'characters', './../', 'character') . $user->image($character['id'], 'characters/slanted', './../'),
                        "DESCRIPTION" => $character['desc'],
                        "HEALTH" => $character['health'],
                        "MANA" => $character['mana'],
                        "SKILLS" => $return,
                        "CURRENT_SKILLS" => $unlocked,
                        "DATABASE" => $database,
                        "L_ADD" => L_ADD,
                        "WHO" => $character['who'],
                        "PASSIVES" => $character['passive'],
                        "L_REMOVE" => L_REMOVE
                    )
                );
            }
            //$tpl = $STYLE->tags($tpl, array("CHARACTERS" => $ingame));
        } else {
            $system->redirect('./?s=website&module=character');
        }
    } elseif (isset($_GET['new'])) {
        $tpl = $STYLE->open('acp/characters.tpl');
        if (isset($_POST['submit'])) {
            //Just process character image, all else have a default value...
            if (isset($_FILES)) {
                $image = $_FILES['image']['name'];
                if ($image) {
                    $filename = stripslashes($_FILES['image']['name']);
                    $extension = $user->getExtension($filename);
                    $extension = strtolower($extension);
                    // Make sure it is an image
                    if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=character&new=true', L_CONTINUE);
                    }
                    // Delete possible existing avatar
                    //$user->deletethisfile("./../images/characters/temp");
                    $image_name = time() . '.' . $extension;
                    $newname = "./../images/characters/temp.$extension";
                    $copied = copy($_FILES['image']['tmp_name'], $newname);
                    $size = filesize("$newname");
                    list($width, $height) = getimagesize("$newname");
                    unlink($_FILES['image']['tmp_name']);
                }
                if (!isset($copied)) {
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=character&new=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=website&module=character&new=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=website&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                }
            } else {
                $system->message(L_ERROR, 'Please supply a face picture.', './?s=website&module=character&new=true', L_CONTINUE);
            }
            $last = $db->query("INSERT INTO characters (name,`desc`,health,mana,who) VALUES 
			('" . $secure->clean($_POST['name']) . "',
			'" . $secure->clean($_POST['description']) . "',
			'" . $secure->clean($_POST['health']) . "',
			'" . $secure->clean($_POST['mana']) . "','" . $system->data('admin_group') . "')");

            if ($last == true) {
                rename("./../images/characters/temp.$extension", './../images/characters/' . $db->link_id->lastInsertId() . '.' . $extension);
                $a = $db->fetch("SELECT * FROM `animes` WHERE id= '" . $system->data('admin_group') . "'");
                $admins = explode(',', $a['who']);
                foreach ($admins as $admin) {
                    $webs = $db->query("SELECT * FROM `accounts` WHERE `group` = '" . $admin . "'");
                    while ($web = $webs->fetch()) {
                        $db->query("UPDATE accounts SET `characters` = '" . $web['characters'] . "," . $db->fetch("SELECT id FROM characters ORDER BY id DESC LIMIT 1")['id'] . "' WHERE `id` = '" . $web['id'] . "'");
                    }
                }
                $system->message($_POST['name'], 'Was inserted to the database!', './?s=website&module=character&id=' . $db->fetch("SELECT id FROM characters ORDER BY id DESC LIMIT 1")['id'], L_CONTINUE);
            } else {
                $system->message('An error occured', 'Insertion to the database was not possible.', './?s=website&module=character&new=true', L_CONTINUE);
            }
        } else
            $tpl = $STYLE->getcode('new-character', $tpl);
    } else {

        $characters = $db->query("SELECT * FROM characters ORDER BY sort ASC");
        $ingame = '';
        if ($characters->rowCount() > 0) {
            $count = 0;
            while ($character = $characters->fetch()) {
                if ($character['wins'] == 0)
                    $percentage = 0;
                else
                    $percentage = round(($character['wins'] / ($character['wins'] + $character['loses'])) * 100);

                if ($count == 0)
                    $ingame .= '<tr>';
                $ingame .= '<tr>
                        <td class="chardiv-sprite" colspan="2" align="center">' . $user->image($character['id'], 'characters', './../') . '</td>
                        <td colspan="2" class="chardiv-name"><a style="font-weight:bolder;color:white;" href="./?s=website&module=character&id=' . $character['id'] . '" class="cardiv-name">' . $character['name'] . '</a></td>
                    </tr>

					 <tr>
                        <td colspan="4" class="chardiv-name">' . $character['desc'] . '</td>
					 </tr>
					 <tr>
                        <td class="chardiv-attribute">Games Won:</td>
                        <td class="chardiv-value">' . $character['wins'] . '</td>
                     </tr>
					 <tr>
                        <td class="chardiv-attribute">Games Played:</td>
                        <td class="chardiv-value">' . ($character['wins'] + $character['loses']) . '</td>
                     </tr>
                     <tr>
                        <td class="chardiv-attribute">Win Rate:</td>
                        <td class="chardiv-value">( ' . $percentage . ' %)</td>
                     </tr>
                     <tr>';
                if ($count == 1) {
                    $ingame .= '</tr>';
                    $count = 0;
                } else
                    $count++;
            }
        } else {
            $ingame = 'No characters were found in the database.';
        }
        $tpl = $STYLE->open('acp/characters.tpl');
        $tpl = str_replace(array($STYLE->getcode('new-character', $tpl), $STYLE->getcode('edit-character', $tpl), $STYLE->getcode('change-avatar', $tpl), $STYLE->getcode('skill', $tpl)), '', $tpl);
        $tpl = $STYLE->tags($tpl, array("CHARACTERS" => $ingame));
    }
}
if ($mode == 'skill') {
    if (isset($_GET['id'])) {
        $skill = $db->query("SELECT * FROM `skills` WHERE `id`='" . $_GET['id'] . "'");
        if ($skill->rowCount() > 0) {
            $skill = $skill->fetch();
            if (isset($_POST['delete'])) {
                $db->query("DELETE FROM skills WHERE id='" . $skill['id'] . "'");
                $system->redirect('./?s=website&module=skill');
            }
            if (isset($_POST['add-effect'])) {
                $db->query("INSERT INTO `effects`( `duration`) VALUES ('0')");
                $effects = $skill['effects'];
                if (empty($effects)) {
                    $effects = $db->link_id->lastInsertId();
                } else {
                    if (strpos($effects, ',') !== false) {
                        $effects = explode(',', $effects);
                    } else {
                        $effects = array($effects);
                    }
                    $effects[] = $db->link_id->lastInsertId();
                    $effects = implode(',', $effects);
                }
                $db->query("UPDATE skills SET `effects`='" . $effects . "' WHERE id='" . $skill['id'] . "'");
                $system->redirect('./?s=website&module=skill&id=' . $skill['id']);
            }
            if (isset($_POST['submit'])) {
                if (!isset($_POST['status'])) {
                    $_POST['status'] = '0';
                }
                if (!isset($_POST['bypass'])) {
                    $_POST['bypass'] = '0';
                }
                if (!isset($_POST['invisible'])) {
                    $_POST['invisible'] = '0';
                }
                if (!isset($_POST['sharing'])) {
                    $_POST['sharing'] = '0';
                }
                if (!isset($_POST['starting'])) {
                    $_POST['starting'] = '0';
                }
                if (!isset($_POST['requires'])) {
                    $_POST['requires'] = '0';
                }
                if (!isset($_POST['targets'])) {
                    $_POST['targets'] = '';
                }
                if (!isset($_POST['uncounterable'])) {
                    $_POST['uncounterable'] = '0';
                }
                if (!isset($_POST['unreflectable'])) {
                    $_POST['unreflectable'] = '0';
                }
                // crimson added
                if (!isset($_POST['dead'])) {
                    $_POST['dead'] = '0';
                }
                //end of added
                $effects = (isset($_POST['id'])) ? $_POST['id'] : null;
                if (isset($effects)) {
                    foreach ($effects as $key => $effect) {

                        $insert = '';
                        if (!isset($_POST['invul'][$key])) {
                            $_POST['invul'][$key] = '0';
                        }
                        if (!isset($_POST['stun'][$key])) {
                            $_POST['stun'][$key] = '0';
                        }
                        if (!isset($_POST['reflect'][$key])) {
                            $_POST['reflect'][$key] = '0';
                        }
                        if (!isset($_POST['no-death'][$key])) {
                            $_POST['no-death'][$key] = '0';
                        }
                        if (!isset($_POST['following'][$key])) {
                            $_POST['following'][$key] = '0';
                        }
                        foreach ($_POST as $it => $value) {
                            if ($it == 'uncounterable' || $it == 'unreflectable' || $it == 'starting' || $it == 'sharing' || $it == 'id' || $it == 'submit' || $it == 'targets' || $it == 'invisible' || $it == 'bypass' || $it == 'status' || $it == 'requires' || $it == 'dead') continue;

                            if (isset($value[$key])) {
                                if (!empty($insert))
                                    $insert .= ',';
                                if ($it !== 'condition')
                                    $value[$key] = $secure->clean($value[$key]);
                                $insert .= "`" . $it . "` = '" . $value[$key] . "'";
                            }
                        }
                        if (!empty($insert))
                            $db->query("UPDATE `effects` SET $insert WHERE `id`='" . $effect . "'");
                    }
                }
                $db->query("UPDATE skills SET `uncounterable`='" . $_POST['uncounterable'] . "',`unreflectable`='" . $_POST['unreflectable'] . "',`targets`='" . $_POST['targets'] . "',`requires`='" . $_POST['requires'] . "', `status`='" . $_POST['status'] . "', `iinvul`='" . $_POST['bypass'] . "', `invisible`='" . $_POST['invisible'] . "', `starting_cooldown`='" . $_POST['starting'] . "', `shared_cooldown`='" . $_POST['sharing'] . "', `dead`='" . $_POST['dead'] . "'  WHERE `id`='" . $skill['id'] . "'");
                $system->redirect('./?s=website&module=skill&id=' . $skill['id']);
            }
            if (isset($_POST['delete-effect'])) {
                if (isset($_POST['id'][$_POST['delete-effect']])) {
                    $db->query("DELETE FROM `effects` WHERE `id`='" . $_POST['id'][$_POST['delete-effect']] . "'");
                    $system->redirect('./?s=website&module=skill&id=' . $skill['id']);
                }
            }
            if (isset($_POST['Avi'])) {
                $image = $_FILES['image']['name'];
                if ($image) {
                    $filename = stripslashes($_FILES['image']['name']);
                    $extension = $user->getExtension($filename);
                    $extension = strtolower($extension);
                    // Make sure it is an image
                    if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=skill&id=' . $skill['id'] . '&change-avatar=true', L_CONTINUE);
                    }
                    // Delete possible existing avatar
                    $user->deletethisfile("./../images/skills/" . $skill['id']);
                    $image_name = time() . '.' . $extension;
                    $newname = "./../images/skills/" . $skill['id'] . ".$extension";
                    $copied = copy($_FILES['image']['tmp_name'], $newname);
                    $size = filesize("$newname");
                    list($width, $height) = getimagesize("$newname");
                    unlink($_FILES['image']['tmp_name']);
                }
                if (!isset($copied)) {
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=skill&id=' . $skill['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=website&module=skill&id=' . $skill['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=website&module=skill&id=' . $skill['id'] . '&change-avatar=true', L_CONTINUE);
                } else {
                    $system->message(L_UPDATED, 'Skill picture update', './?s=website&module=skill&id=' . $skill['id'], L_CONTINUE);
                }
            }
            $tpl = $STYLE->open('acp/skills.tpl');
            if (isset($_GET['change-picture'])) {
                $tpl = $STYLE->getcode('change-avatar', $tpl);
                $tpl = $STYLE->tags(
                    $tpl,
                    array(
                        "ID" => $skill['id'],
                        "NAME" => $skill['name'],
                        "AVATAR" => $user->image($skill['id'], 'skills', './../', 'skill'),
                        "L_SUBMIT" => L_SUBMIT
                    )
                );
            } else {
                $tpl = $STYLE->getcode('edit-skill', $tpl);
                $targets = $skill['targets'];
                $ii = $skill['iinvul'];
                $invisible = $skill['invisible'];
                $status = $skill['status'];
                $starting = $skill['starting_cooldown'];
                $share = $skill['shared_cooldown'];
                $effects = $skill['effects'];
                $uncounterable = $skill['uncounterable'];
                $unreflectable = $skill['unreflectable'];
                $dead = $skill['dead'];
                $done = '';
                if (empty($effects)) {
                    $done = 'This skill has no effects assigned!';
                } else {
                    if (strpos($effects, ',') !== false) {
                        $effects = explode(',', $effects);
                    } else {
                        $effects = array($effects);
                    }
                    $input = array('set-skill', 'fear', 'externalManaGain', 'visibility', 'no-ignore', 'specify', 'targetme', 'invul', 'stun', 'also', 'increase-mana', 'increase-duration', 'disable', 'condition', 'switch', 'following', 'ally', 'if', 'not', 'self', 'ignore', 'reset', 'increase-heal', 'increase-cooldown', 'increase-cost', 'count', 'remove', 'damage', 'piercing', 'affliction', 'manaGain', 'drainM', 'removeM', 'drainH', 'convertM', 'convertH', 'dd', 'dr', 'heal', 'counter', 'replace', 'increase', 'transform', 'deal', 'increaseby', 'duration', 'increase-manaRem', 'setCd');
                    $checkbox = array('no-resurrect', 'unpiercable', 'renew', 'destroy-dd', 'show-mana', 'increase-affliction', 'no-death', 'reflect', 'destroy-dr', 'reverseTargetToCaster');
                    $radio = array('key', 'target');
                    $textarea = array('description');
                    foreach ($effects as $key => $effect) {
                        $e = $db->query("SELECT * FROM effects WHERE id='" . $effect . "'");
                        if ($e->rowCount() > 0) {
                            $e = $e->fetch();
                            $done .= $effect . ') <center>';
                            foreach ($e as $id => $value) {
                                if (is_numeric($id)) continue;
                                if ($id == 'id') continue;
                                if (array_search($id, $input) !== false) {
                                    $done .= ucfirst($id) . ' <input name="' . $id . '[' . $key . ']" type="text" class="formcss" id="' . $id . '" value="' . $value . '"><br/>';
                                } elseif (array_search($id, $checkbox) !== false) {
                                    $checked = '';
                                    if ($value == '1')
                                        $checked = 'checked';
                                    $done .= $id . '<input type="checkbox" name="' . $id . '[' . $key . ']" value="1" ' . $checked . '><br/>';
                                } elseif (array_search($id, $radio) !== false) {
                                    $k = array_search($id, $radio);
                                    if ($radio[$k] == 'key') {
                                        $done .= '<br/>Replace Skill 1: <input type="radio" name="' . $id . '[' . $key . ']" value="0" ' . (($value == '0') ? 'checked' : '') . '>';
                                        $done .= 'Replace Skill 2: <input type="radio" name="' . $id . '[' . $key . ']" value="1" ' . (($value == '1') ? 'checked' : '') . '>';
                                        $done .= 'Replace Skill 3: <input type="radio" name="' . $id . '[' . $key . ']" value="2" ' . (($value == '2') ? 'checked' : '') . '>';
                                        $done .= 'Replace Skill 4: <input type="radio" name="' . $id . '[' . $key . ']" value="3" ' . (($value == '3') ? 'checked' : '') . '>
									Remove <input type="radio" name="' . $id . '[' . $key . ']" value="Null"><br/>';
                                    } else {
                                        $done .= 'Target: ' .
                                            'Self <input type="checkbox" name="' . $id . '[' . $key . ']" value="S" ' . (($value == 'S') ? 'checked' : '') . '>' .
                                            'Enemy <input type="checkbox" name="' . $id . '[' . $key . ']" value="E" ' . (($value == 'E') ? 'checked' : '') . '>' .
                                            'Ally <input type="checkbox" name="' . $id . '[' . $key . ']" value="A" ' . (($value == 'A') ? 'checked' : '') . '>';
                                    }
                                } else {
                                    //textarea
                                    $done .= 'Description <textarea rows="2" name="' . $id . '[' . $key . ']" cols="20" style="width: 97%; height: 100" class="formcss">' . $value . '</textarea>';
                                }
                            }
                            $done .= '<input type="hidden" name="id[' . $key . ']" value="' . $effect . '"><br/>
						<button type="submit" name="delete-effect" value="' . $key . '" style="float:right;" class="formcss">Delete Effect</button>
						</center><br/>';
                        } else {
                            // Unset effect
                            if (strpos($skill['effects'], ',') !== false) {
                                $new = explode(',', $skill['effects']);
                            } else {
                                $new = array($skill['effects']);
                            }
                            if (array_search($effect, $new) !== false) {
                                unset($new[array_search($effect, $new)]);
                                $new = implode(',', $new);
                                $db->query("UPDATE `skills` SET `effects`='" . $new . "' WHERE `id`='" . $skill['id'] . "'");
                            }
                        }
                    }
                }
                $tpl = $STYLE->tags(
                    $tpl,
                    array(
                        "ID" => $skill['id'],
                        "NAME" => $skill['name'],
                        "PICTURE" => $user->image($skill['id'], 'skills', './../', 'skill'),
                        "TARGETS" => $targets,
                        "STARTING" => $starting,
                        "SHARED" => $share,
                        "INVUL" => ($ii == '1') ? 'checked' : '',
                        "INVISIBLE" => ($invisible == '1') ? 'checked' : '',
                        "STATUS" => ($status == '1') ? 'checked' : '',
                        "REQUIRES" => $skill['requires'],
                        "UNCOUNTERABLE" => ($uncounterable == '1') ? 'checked' : '',
                        "UNREFLECTABLE" => ($unreflectable == '1') ? 'checked' : '',
                        "DEAD" => ($dead == '1') ? 'checked' : '',
                        "EFFECTS" => $done
                    )
                );
            }
        } else {
            $system->redirect('./?s=website&module=skill');
        }
    } elseif (isset($_GET['new'])) {
        $tpl = $STYLE->open('acp/skills.tpl');
        if (isset($_POST['submit'])) {
            //Just process skill image, all else have a default value...
            if (isset($_FILES)) {
                $image = $_FILES['image']['name'];
                if ($image) {
                    $filename = stripslashes($_FILES['image']['name']);
                    $extension = $user->getExtension($filename);
                    $extension = strtolower($extension);
                    // Make sure it is an image
                    if ((($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))) {
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=website&module=skill&new=true', L_CONTINUE);
                    }
                    // Delete possible existing avatar
                    $user->deletethisfile("./../images/skills/temp");
                    $image_name = time() . '.' . $extension;
                    $newname = "./../images/skills/temp.$extension";
                    $copied = copy($_FILES['image']['tmp_name'], $newname);
                    $size = filesize("$newname");
                    list($width, $height) = getimagesize("$newname");
                    unlink($_FILES['image']['tmp_name']);
                }
                if (!isset($copied)) {
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=website&module=skill&new=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=website&module=skill&new=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=website&module=skill&new=true', L_CONTINUE);
                }
            } else {
                $system->message(L_ERROR, 'Please supply a skill picture.', './?s=website&module=skill&new=true', L_CONTINUE);
            }
            $last = $db->query("INSERT INTO skills (name,`desc`,cost,cooldown) VALUES 
			('" . $secure->clean($_POST['name']) . "',
			'" . $secure->clean($_POST['description']) . "',
			'" . $secure->clean($_POST['cost']) . "',
			'" . $secure->clean($_POST['cooldown']) . "')");

            if ($last == true) {
                rename("./../images/skills/temp.$extension", './../images/skills/' . $db->link_id->lastInsertId() . '.' . $extension);
                $system->message($_POST['name'], 'Was inserted to the database!', './?s=website&module=skill&id=' . $db->link_id->lastInsertId(), L_CONTINUE);
            } else {
                $system->message('An error occured', 'Insertion to the database was not possible.', './?s=website&module=skill&new=true', L_CONTINUE);
            }
        } else
            $tpl = $STYLE->getcode('new-skill', $tpl);
    } else {
        $skills = $db->query("SELECT * FROM `skills` ORDER BY `skills`.`id` DESC");
        $ingame = '';
        if ($skills->rowCount() > 0) {
            $count = 0;
            while ($skill = $skills->fetch()) {
                if ($count == 0)
                    $ingame .= '<tr>';
                $ingame .= '<td class="skilldiv-sprite" colspan="2" align="center">' . $user->image($skill['id'], 'skills', './../', 'skill') . '</td>
                <td class="skilldiv-name" colspan="2" align="left"><a href="./?s=website&module=skill&id=' . $skill['id'] . '" class="skilldiv-name">' . $skill['name'] . '</a><br>
                <p class="skilldiv-desc">' . $skill['desc'] . '</p></td>';
                if ($count == 1) {
                    $ingame .= '</tr>';
                    $count = 0;
                } else
                    $count++;
            }
        } else {
            $ingame = 'No skills were found in the database.';
        }
        $tpl = $STYLE->open('acp/skills.tpl');
        $tpl = str_replace(array($STYLE->getcode('edit-skill', $tpl), $STYLE->getcode('change-avatar', $tpl), $STYLE->getcode('new-skill', $tpl)), '', $tpl);
        $tpl = $STYLE->tags($tpl, array("SKILLS" => $ingame));
    }
}

/* if ($mode == 'character') {
    include('./core/characterlist.php');
} */

if ($mode == 'store') {
    include('./core/store.php');
}

if ($mode == 'missions') {
    include('./core/missions.php');
}
if ($mode == 'season') {
    include('./core/seasons.php');
}
if ($mode == 'levels') {
    include('./core/levels.php');
}
if ($mode == 'keywords') {
    include('./core/game-keywords.php');
}

$tpl = $STYLE->tags($tpl, array(
    "BALANCE" => '<div style="float:right;font-style:italic;">Balance Update: ' . $system->data('Batch', 1) . '</div>'
));
$output .= $tpl;