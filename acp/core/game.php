<?php
$tpl = $STYLE->open('acp/ingame.tpl');
$page_title .=  ' > Game Management';
$description = '';
$metatags = '';
$message = '';

if (isset($_GET['module'])) {
    $mode = $secure->clean($_GET['module']);
} else {
    $mode = '';
}

if ($mode == 'balance-update') {
    $tpl = $STYLE->getcode('balance', $tpl);
    $page_title .=  ' > Balance Management';
    if (isset($_POST['submit'])) {
        if (isset($_POST['forum'])) {
            // Find batch notes
            $notes = $db->query("SELECT * FROM balance WHERE `batch`='" . $_POST['version'] . "' ORDER BY `cid`");
            $topic = '';
            if ($notes->rowCount() > 0) {
                $characters = array();
                while ($note = $notes->fetch()) {
                    $characters[$note['cid']][] = $note['detail'];
                }
                foreach ($characters as $id => $detail) {
                    $character = $db->query("SELECT * FROM characters WHERE `id`='" . $id . "'");
                    $character = $character->fetch();
                    if ($character['wins'] == 0)
                        $percentage = 0;
                    else
                        $percentage = round(($character['wins'] / ($character['wins'] + $character['loses'])) * 100);
                    $topic .= '<div class="quote">' . $user->image($id, 'characters', './../') . '<br/>[b]' . $character['name'] . '[/b]
						<br /><font class="normfont">Games Won: ' . $character['wins'] . ' ( ' . $percentage . ' %) </font>
						<br /><font class="normfont">Games Played: ' . ($character['wins'] + $character['loses']) . ' </font><ul>';
                    foreach ($detail as $it) {
                        $topic .= '<li>' . $it . '</li><br/><br/>';
                    }
                    $topic .= '</ul></div><br/>';
                }
            }
            if (!empty($topic)) {
                $insert_topic = $db->query("INSERT INTO " . $prefix . "_topics (author_id,title,forum_id,date) VALUES ('" . $account['id'] . "',
				'Balance Update " . $_POST['version'] . "','" . $_POST['forum'] . "',UNIX_TIMESTAMP())");
                $select_topic = $db->fetch("SELECT * FROM " . $prefix . "_topics WHERE author_id = '" . $account['id'] . "' ORDER BY id DESC");
                $topic_id = $select_topic['id'];
                $insert_post = $db->query("INSERT INTO " . $prefix . "_posts (author_id,text,topic_id,date,attachment) VALUES 
				('" . $account['id'] . "', '" . $topic . "', '$topic_id',UNIX_TIMESTAMP(),'')");
            }
            $db->query("UPDATE characters SET `wins`='0',`loses`='0'");
            $db->query("UPDATE settings SET `value`='" . $_POST['new'] . "' WHERE `name`='Batch'");
            $tpl = str_replace(array($STYLE->getcode('error', $tpl)), '', $tpl);
        } else {
            $tpl = $STYLE->getcode('error', $tpl);
        }
    } else {
        $tpl = str_replace(array($STYLE->getcode('success', $tpl), $STYLE->getcode('error', $tpl)), '', $tpl);
    }
    $forum = $db->query("SELECT * FROM " . $prefix . "_forums ORDER BY sort");
    $forums = '';
    while ($row = $forum->fetch()) {
        $forums .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    $tpl = $STYLE->tags($tpl, array(
        "BU" => $system->data('Batch', 1),
        "FORUMS" => $forums
    ));
} else {
    $tpl = str_replace(array($STYLE->getcode('balance', $tpl)), '', $tpl);
}

/* if ($mode == 'character') {
    include('./core/characterlist.php');
} */

if ($mode == 'character') {
    if (isset($_GET['id'])) {
        $character = $db->query("SELECT * FROM characters WHERE id='" . $_GET['id'] . "'");
        if ($character->rowCount() > 0) {
            $character = $character->fetch();
            if (isset($_POST['Delete'])) {
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
				('" . $character['id'] . "', 'There has been an update to this characters face picture','" . $system->data('Batch', 1) . "')");
                $user->deletethisfile("../../images/characters/" . $character['id']);
                $system->message(L_DELETE, 'Character face picture was deleted', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
            }
            if (isset($_POST['Delete-Slanted'])) {
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
				('" . $character['id'] . "', 'There has been an update to this characters face picture','" . $system->data('Batch', 1) . "')");
                $user->deletethisfile("../../images/characters/slanted/" . $character['id']);
                $system->message(L_DELETE, 'Character slanted face picture was deleted', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
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
                $system->redirect('./?s=game&module=character&id=' . $character['id']);
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
                    //$system->redirect('./?s=game&module=character&id='.$character['id']);
                }
            }
            if (isset($_POST['change']) && isset($_POST['description-' . $_POST['skill-id']])) {
                $s = $db->fetch("SELECT * FROM skills WHERE id='" . $_POST['skill-id'] . "'");
                if (isset($_POST['sname-' . $s['id']])) {
                    $name = '';
                    if ($s['name'] !== $_POST['sname-' . $s['id']])
                        $name = ",name='" . $secure->clean($_POST['sname-' . $s['id']]) . "'";
                    $db->query("UPDATE skills SET `desc`='" . $secure->clean($_POST['description-' . $s['id']]) . "'$name WHERE id='" . $s['id'] . "'");
                    $system->redirect('./?s=game&module=character&id=' . $character['id']);
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
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
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
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'New face picture has been added','" . $system->data('Batch', 1) . "')");
                    $system->message(L_UPDATED, 'Character face picture update', './?s=game&module=character&id=' . $character['id'], L_CONTINUE);
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
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
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
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                } else {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'New slanted face picture has been added','" . $system->data('Batch', 1) . "')");
                    $system->message(L_UPDATED, 'Character slanted face picture update', './?s=game&module=character&id=' . $character['id'], L_CONTINUE);
                }
            }
            if (isset($_POST['delete'])) {
                $db->query("DELETE FROM characters WHERE id='" . $character['id'] . "'");
                $system->redirect('./?s=game&module=character');
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
                $system->redirect('./?s=game&module=character&id=' . $character['id']);
            }
            if (isset($_POST['change-stat']) && isset($_POST['mana']) || isset($_POST['health'])) {
                if (is_numeric($_POST['mana']) || is_numeric($_POST['health'])) {
                    $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', 'This characters health is now " . $_POST['health'] . " from " . $character['health'] . "','" . $system->data('Batch', 1) . "')");
                    $db->query("UPDATE characters SET `mana`='" . $_POST['mana'] . "', `health`='" . $_POST['health'] . "' WHERE id='" . $character['id'] . "'");
                    $system->redirect('./?s=game&module=character&id=' . $character['id']);
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
                $system->redirect('./?s=game&module=character&id=' . $character['id']);
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
                $system->redirect('./?s=game&module=character&id=' . $character['id'] . '', true);
            }

            // Remove
            if (
                isset($_POST['remove']) && isset($_POST['current-skills'])
            ) {
                $skills = explode(',', $character['skills']);
                unset($skills[array_search($_POST['current-skills'], $skills)]);
                $skills = implode(',', $skills);
                $skill = $db->query("SELECT * FROM skills WHERE id='" . $_POST['current-skills'] . "'");
                $skill = $skill->fetch();
                $db->query("INSERT INTO balance (cid,detail,batch) VALUES 
					('" . $character['id'] . "', '[b][u]" . $skill['name'] . "[/u][/b] has been removed.','" . $system->data('Batch', 1) . "')");
                $db->query("UPDATE characters SET skills ='" . $skills . "' WHERE id = '" . $character['id'] . "'");
                $system->redirect('./?s=game&module=character&id=' . $character['id'] . '', true);
            }

            $tpl = $STYLE->open('acp/staffpanel.tpl');
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
            $system->redirect('./?s=game&module=character');
        }
    } elseif (isset($_GET['new'])) {
        $tpl = $STYLE->open('acp/staffpanel.tpl');
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
                        $system->message(L_ERROR, 'The format uploaded is invalid', './?s=game&module=character&new=true', L_CONTINUE);
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
                    $system->message(L_ERROR, 'An error occured on upload, please try again.', './?s=game&module=character&new=true', L_CONTINUE);
                } else
                if ($height > $system->data('avatar_height') || $width > $system->data('avatar_width')) {
                    // Prevent Avatar over Dimension size
                    unlink("$newname");
                    $error_message = str_replace(array("[HEIGHT]", "[WIDTH]"), array($system->data('avatar_height'), $system->data('avatar_width')), L_AVATAR_UPLOAD_DIMENSION);
                    $system->message(L_ERROR, $error_message, './?s=game&module=character&new=true', L_CONTINUE);
                } else
                if ($size > $system->data('avatar_filesize')) {
                    // Prevent Avatar over File size
                    unlink("$newname");
                    $error_message = str_replace("[SIZE]", $system->data('avatar_filesize'), L_AVATAR_UPLOAD_SIZE);
                    $system->message(L_ERROR, $error_message, './?s=game&module=character&id=' . $character['id'] . '&change-avatar=true', L_CONTINUE);
                }
            } else {
                $system->message(L_ERROR, 'Please supply a face picture.', './?s=game&module=character&new=true', L_CONTINUE);
            }
            $last = $db->query("INSERT INTO characters (name,`desc`,health,mana,who) VALUES 
			('" . $secure->clean($_POST['name']) . "',
			'" . $secure->clean($_POST['description']) . "',
			'" . $secure->clean($_POST['health']) . "',
			'" . $secure->clean($_POST['mana']) . "',
            '" . $system->data('admin_group') . "')");

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
                $system->message($_POST['name'], 'Was inserted to the database!', './?s=game&module=character&id=' . $db->fetch("SELECT id FROM characters ORDER BY id DESC LIMIT 1")['id'], L_CONTINUE);
            } else {
                $system->message('An error occured', 'Insertion to the database was not possible.', './?s=game&module=character&new=true', L_CONTINUE);
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
                        <td colspan="2" class="chardiv-name"><p style="font-weight:bolder;color:white;" class="cardiv-name">' . $character['name'] . '</p></td>
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
        $tpl = $STYLE->open('acp/staffpanel.tpl');
        $tpl = str_replace(array($STYLE->getcode('new-character', $tpl), $STYLE->getcode('edit-character', $tpl), $STYLE->getcode('change-avatar', $tpl), $STYLE->getcode('skill', $tpl)), '', $tpl);
        $tpl = $STYLE->tags($tpl, array("CHARACTERS" => $ingame));
    }
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