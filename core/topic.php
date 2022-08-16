<?php

/*
 * Project Name: KaiBB - http://www.kaibb.co.uk
 * Author: Christopher Shaw
 * This file belongs to KaiBB, it may be freely modified but this notice, and all copyright marks must be left
 * intact. See COPYING.txt
 */

$forum = new forum();
$tpl = $STYLE->open('topic.tpl');

// Generate Global Menu
$global_menu = $STYLE->getcode('menu', $tpl);
$tpl = str_replace($global_menu, '', $tpl);
// Find Your Group
$group_id = (float)$account['group'];
if (empty($account['group']))
    $group_id = '1';
// Find Mode
if (isset($_GET['mode'])) {
    $mode = $secure->clean($_GET['mode']);
} else {
    $mode = '';
}

// Is there a topic id?
// Character page ?
$me = 't';
$topic_path = 'forum';
if (isset($_GET['c'])) {
    $tid = $secure->clean($_GET['c']);
    $me = 'c';
    $topic_path = 'characters-and-skills';
}

if (!isset($_GET['t']) && !isset($tid)) {
    $system->message(L_ERROR, L_TOPIC_ERROR_ID, './', L_CONTINUE);
}

// Get Topic and Forum data
if (isset($tid)) {
    $topic_id = $tid;
} else {
    $topic_id = $secure->clean($_GET['t']);
}

if (!isset($_GET['c'])) {
    $topic_data = $db->fetch("SELECT * FROM " . $prefix . "_topics WHERE id = '$topic_id'");
    $forum_data = $db->fetch("SELECT * FROM " . $prefix . "_forums WHERE id = '" . $topic_data['forum_id'] . "'");
} else {
    $topic_data = $db->fetch("SELECT * FROM characters WHERE name LIKE '$topic_id'");
    $topic_data['title'] = $topic_data['name'];
    $forum_data = $db->fetch("SELECT * FROM " . $prefix . "_forums WHERE type ='1'");
    $topic_id = 'c' . $topic_id;
    // Security check
    $who = $db->fetch("SELECT * FROM animes WHERE id='" . $topic_data['who'] . "'");
    $who = explode(',', $who['who']);
    $google = false;
    if (!$account) {
        $account['group'] = 2;
        $google = true;
    }
    $key = array_search($account['group'], $who);
    if (isset($key) && $who[$key] != $account['group']) {
        $system->redirect('./characters-and-skills');
    }
    // Not unlocked?
    $ucharacters = array();
    if (isset($account['characters']))
        $ucharacters = explode(',', $account['characters']);
    if (empty($ucharacters)) {
        if ($system->data('Only', 1) == $topic_data['who'])
            $system->redirect('./characters-and-skills');
    } else {
        $key = array_search($topic_data['id'], $ucharacters);
        if (isset($key) && $key !== false) {
            // Check if exclusive
            if ($system->data('Only', 1) == $topic_data['who'])
                $system->redirect('./characters-and-skills');
        }
    }
    if ($google)
        unset($account);
}
// Generate Page Title
if ($topic_path = 'characters-and-skills') {
    $page_title = '<a href="' . $siteaddress . '" class="normfont">' . $system->data('sitename') . '</a> > ';
}
if (!empty($forum_data['parent_id']))
    $page_title .= ' <a href="' . $siteaddress . 'forum/' . $forum_data['parent_id'] . '" class="normfont">' . $system->bbcode($system->present($db->fieldFetch('forum_forums', $forum_data['parent_id'], 'name'))) . '</a> >';
$page_title .= ' <a href="./' . ($topic_path !== 'forum' ? $topic_path : $topic_path . '/' . $forum_data['id']) . '" class="normfont">' . $system->bbcode($system->present($forum_data['name'])) . '</a> > <a href="./' . $topic_path . '/' . ($topic_path !== 'forum' ? $topic_data['title'] : $topic_data['id']) . '" class="normfont">' . $system->bbcode($system->present($topic_data['title'])) . '</a>';

$metatags .= 'browsing, topic, on, anime-blast, aBlast, anime, blast, ' . $forum_data['name'] . ', ' . $topic_data['title'];
// Is User Watching Topic
$watching = $db->fetch("SELECT * FROM " . $prefix . "_watching WHERE account_id = '" . $account['id'] . "' AND topic_id = '$topic_id'");
if ($watching) {
    $button = L_UNWATCH;
    $post = 'unwatch';
} else {
    $button = L_WATCH;
    $post = 'watch';
}


// Parse Global Menu
$global_menu = $STYLE->tags($global_menu, array("TOPIC_ID" => $topic_id, "L_REPLY" => L_REPLY, "L_WATCHING" => $button, "WATCHING" => $post));
if ($me == 'c') {
    $global_menu = '';
}
// Does the topic exist?
if (!$topic_data) {
    $system->message(L_ERROR, L_TOPIC_NOT_FOUND, './', L_CONTINUE);
}

if (strpos($topic_id, 'c') == false) {
    // Mark As Read
    $is_read = $db->fetch("SELECT id FROM " . $prefix . "_forums_read WHERE account_id = '" . $account['id'] . "' AND topic_id ='" . $topic_id . "'");
    if (!$is_read) {
        $db->query("INSERT INTO " . $prefix . "_forums_read (account_id,topic_id,date) VALUES ('" . $account['id'] . "','$topic_id', UNIX_TIMESTAMP())");
    } else {
        $db->query("UPDATE " . $prefix . "_forums_read SET date = UNIX_TIMESTAMP() WHERE account_id = '" . $account['id'] . "' AND topic_id = '" . $topic_id . "'");
    }
}
// Watch and Unwatch
if (isset($_POST['watch'])) {
    $db->query("INSERT INTO " . $prefix . "_watching (account_id,topic_id) VALUE ('" . $account['id'] . "','" . $topic_id . "')");
    $system->message(L_WATCH, L_WATCH_MESSAGE, './topic/' . $topic_data['id'] . '', L_CONTINUE);
}
if (isset($_POST['unwatch'])) {
    $db->query("DELETE FROM " . $prefix . "_watching WHERE account_id = '" . $account['id'] . "' AND topic_id = '" . $topic_id . "'");
    $system->message(L_UNWATCH, L_UNWATCH_MESSAGE, './topic/' . $topic_data['id']  . '', L_CONTINUE);
}


// Are attachments allowed?
if ($forum->forum_permission($forum_data['id'], $group_id, 'upload') == '1') {
    $attachment = 'block';
} else {
    $attachment = 'none';
}

// Process Reputation Action
if ($mode == 'reputation-add') {
    if (isset($_GET['post_id'])) {
        $post_id = $secure->clean($_GET['post_id']);
        $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '" . $post_id . "'");
        $reputation = $db->fetch("SELECT id FROM " . $prefix . "_reputation WHERE account_id = '" . $account['id'] . "' AND post_id = '" . $post_id . "'");
        if ($post && !$reputation) {
            $db->query("UPDATE accounts SET reputation = reputation + 1 WHERE id = '" . $post['author_id'] . "'");
            $db->query("INSERT INTO " . $prefix . "_reputation (account_id,post_id) VALUES (" . $account['id'] . ",$post_id)");
            $system->message(L_REPUTATION, L_REPUTATION_MESSAGE, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        } else {
            $system->redirect('./?s=viewtopic&' . $me . '=' . $topic_data['id'] . '');
        }
    }
}
if ($mode == 'reputation-remove') {
    if (isset($_GET['post_id'])) {
        $post_id = $secure->clean($_GET['post_id']);
        $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '" . $post_id . "'");
        $reputation = $db->fetch("SELECT id FROM " . $prefix . "_reputation WHERE account_id = '" . $account['id'] . "' AND post_id = '" . $post_id . "'");
        if ($post && !$reputation) {
            $db->query("UPDATE accounts SET reputation = reputation - 1 WHERE id = '" . $post['author_id'] . "'");
            $db->query("INSERT INTO " . $prefix . "_reputation (account_id,post_id) VALUES (" . $account['id'] . ",$post_id)");
            $system->message(L_REPUTATION, L_REPUTATION_MESSAGE, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        } else {
            $system->redirect('./?s=viewtopic&' . $me . '=' . $topic_data['id'] . '');
        }
    }
}

// Delete Attachment
if ($mode == 'delete_attachment') {
    if (isset($_GET['post_id'])) {
        $post_id = $secure->clean($_GET['post_id']);
        $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '" . $post_id . "'");
        $attachment = $db->fetch("SELECT * FROM " . $prefix . "_attachments WHERE id = '" . $post['attachment'] . "'");
        /*if ($forum->forum_permission($forum_data['id'], $group_id, 'moderator') != '1' && $account['id'] != $post['author_id']) {
            $system->message(L_ERROR, L_PERMISSION_ERROR_ACTION, './?s=viewtopic&amp;'.$me.'=' . $topic_data['id'] . '', L_CONTINUE);
        }*/
        if ($post && $attachment) {
            unlink($attachment['file']);
            $db->query("UPDATE " . $prefix . "_posts SET attachment = '0' WHERE id = '" . $post['id'] . "'");
            $db->query("DELETE FROM " . $prefix . "_attachments WHERE id = '" . $attachment['id'] . "'");
            $system->message(L_DELETED, L_ATTACHMENT_DELETED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        } else {
            $system->redirect('./?s=viewtopic&' . $me . '=' . $topic_data['id'] . '');
        }
    }
}

// Is user a moderator?
if ($forum->forum_permission($forum_data['id'], $group_id, 'moderator') == '1') {
    if (isset($_POST['sticky_submit']) && isset($_POST['sticky']) && $me == 't') {
        // Change topic sticky status
        $sticky = $secure->clean($_POST['sticky']);
        $db->query("UPDATE " . $prefix . "_topics SET sticky = '$sticky' WHERE id = '$topic_id'");
        $system->message(L_UPDATED, L_TOPIC_STICKIED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else if (isset($_POST['move_submit']) && isset($_POST['move']) && $me == 't') {
        // Move Topic
        $move = $secure->clean($_POST['move']);
        $db->query("UPDATE " . $prefix . "_topics SET forum_id = '$move' WHERE id = '$topic_id'");
        $system->message(L_UPDATED, L_TOPIC_MOVED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else if (isset($_POST['delete_topic']) && $me == 't') {

        if (!isset($_POST['confirmed'])) {
            $system->confirm(L_CONFIRM_DELETE_TOPIC, L_CONFIRM_DELETE_TOPIC_MSG, './topic/' . $topic_data['id'] . '', 'delete_topic');
        }

        // Delete topic data
        $db->query("DELETE FROM " . $prefix . "_posts WHERE topic_id = '$topic_id'");
        $db->query("DELETE FROM " . $prefix . "_forums_read WHERE topic_id = '$topic_id'");
        $db->query("DELETE FROM " . $prefix . "_topics WHERE id = '$topic_id'");
        $system->message(L_DELETED, L_TOPIC_DELETED, './forum/' . $forum_data['id'] . '', L_CONTINUE);
    } else if (isset($_POST['delete_poll']) && $me == 't') {
        if (!isset($_POST['confirmed'])) {
            $system->confirm(L_CONFIRM_DELETE_POLL, L_CONFIRM_DELETE_POLL_MSG, './topic/' . $topic_data['id'] . '', 'delete_topic');
        }
        // Delete topic data
        $poll = $db->fetch("SELECT * FROM " . $prefix . "_polls WHERE topic_id = '$topic_id'");
        if (!$poll) {
            $system->message(L_ERROR, L_POLL_DELETED_ERROR, './topic/' . $topic_id . '', L_CONTINUE);
        }
        $db->query("DELETE FROM " . $prefix . "_polls WHERE id = '" . $poll['id'] . "'");
        $db->query("DELETE FROM " . $prefix . "_polls_vote WHERE id = '" . $poll['id'] . "'");
        $db->query("DELETE FROM " . $prefix . "_polls_option WHERE id = '" . $poll['id'] . "'");
        $system->message(L_DELETED, L_POLL_DELETED, './topic/' . $topic_id . '', L_CONTINUE);
    } else if (isset($_POST['lock']) && $me == 't') {
        // Lock Topic
        $db->query("UPDATE " . $prefix . "_topics SET locked = '1' WHERE id = '$topic_id'");
        $system->message(L_UPDATED, L_TOPIC_LOCKED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else if (isset($_POST['unlock']) && $me == 't') {
        // Unlock Topic
        $db->query("UPDATE " . $prefix . "_topics SET locked = '0' WHERE id = '$topic_id'");
        $system->message(L_UPDATED, L_TOPIC_UNLOCKED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else if (isset($_POST['delete_posts'])) {
        // Delete posts data
        if (isset($_POST['checkbox'])) {
            $topic_check = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE topic_id = '$topic_id' ORDER BY id LIMIT 1;");
            $checkbox = $_POST['checkbox'];
            $topic_delete = '0';
            for ($i = 0; $i < count($checkbox); $i++) {
                $delete_id = $checkbox[$i];
                if ($topic_check['id'] == $delete_id && $me == 't') {
                    $db->query("DELETE FROM " . $prefix . "_topics WHERE id = '$topic_id';");
                    $db->query("DELETE FROM " . $prefix . "_forums_read WHERE topic_id = '$topic_id';");
                    $db->query("DELETE FROM " . $prefix . "_posts WHERE topic_id = '$topic_id';");
                    $topic_delete = '1';
                } else {
                    $db->query("DELETE FROM " . $prefix . "_posts WHERE id = '$delete_id';");
                }
            }
            if ($topic_delete == '1') {
                $system->message(L_DELETED, L_POSTS_DELETED_TOPIC, './forum/' . $forum_data['id'] . '', L_CONTINUE);
            } else {
                $system->message(L_DELETED, L_POSTS_DELETED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
            }
        } else {
            $system->message(L_ERROR, L_POSTS_DELETED_ERROR, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
    } else {
        // Show the Moderation Panel
        $moderation_status = 'true';
        $forum_list = $db->query("SELECT * FROM " . $prefix . "_forums");
        $forum_list_row = '';
        while ($row = $forum_list->fetch()) {
            if ($row['id'] == $forum_data['id']) {
                $select = 'selected';
            } else {
                $select = '';
            }
            $forum_list_row .= '<option value="' . $row['id'] . '" ' . $select . '>' . $system->present($row['name']) . '</option>';
        }
        // Find current topic status
        $announcement = '';
        $sticky = '';
        $normal = '';
        $locked = L_LOCK;
        $locked_name = 'lock';
        if (isset($topic_data['sticky']) && $topic_data['sticky'] == '2' && $me == 't') {
            $announcement = 'selected';
        } else if (isset($topic_data['sticky']) && $topic_data['sticky'] == '1' && $me == 't') {
            $sticky = 'selected';
        } else {
            $normal = 'selected';
        }
        if (isset($topic_data['locked']) && $topic_data['locked'] == '1' && $me == 't') {
            $locked = L_UNLOCK;
            $locked_name = 'unlock';
        }
        $poll = $db->fetch("SELECT * FROM " . $prefix . "_polls WHERE topic_id = '$topic_id'");
        if ($poll) {
            $poll = 'block';
        } else {
            $poll = 'none';
        }
        // Parse Moderation Panel
        $tpl = $STYLE->tags($tpl, array(
            "MOVE" => $forum_list_row,
            "L_DELETE_TOPIC" => L_DELETE_TOPIC,
            "L_DELETE_SELECTED" => L_DELETE_SELECTED,
            "L_SET" => L_SET,
            "L_MOVE" => L_MOVE,
            "L_NORMAL" => L_NORMAL,
            "L_STICKY" => L_STICKY,
            "L_ANNOUNCEMENT" => L_ANNOUNCEMENT,
            "NORMAL" => $normal,
            "STICKY" => $sticky,
            "ANNOUNCEMENT" => $announcement,
            "L_LOCKED" => $locked,
            "LOCKED" => $locked_name,
            "POLL" => $poll,
            "L_DELETE_POLL" => L_DELETE_POLL
        ));
        if (strpos($topic_id, 'c') !== false)
            $tpl = str_replace(array($STYLE->getcode('moderator_lock', $tpl), $STYLE->getcode('moderator_topic', $tpl), $STYLE->getcode('moderator_settings', $tpl)), '', $tpl);
    }
} else {
    // Hide Moderation Panel
    $moderation_status = 'false';
    $tpl = str_replace($STYLE->getcode('moderator', $tpl), '', $tpl);
}
// Is user allowed access?
if ($forum->category_permission($forum_data['cat_id'], $group_id, 'view') != '1' || $forum->forum_permission($forum_data['id'], $group_id, 'view') != '1' && $forum_data['id'] !== '20') {
    $output = str_replace($STYLE->getcode('linktree', $output), '', $output);
    $global_menu = '';
    $system->message(L_ERROR, L_PERMISSION_ERROR_AREA, './', L_CONTINUE);
}
$post_row_tpl = $STYLE->getcode('row', $tpl);
if ($mode == 'edit' && isset($_GET['post_id'])) {
    $tpl = str_replace(array($STYLE->getcode('reply', $tpl), $STYLE->getcode('normal', $tpl), $STYLE->getcode('quote', $tpl), $STYLE->getcode('report', $tpl)), '', $tpl);
    $id = $secure->clean($_GET['post_id']);
    $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '$id'");
    $first_post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE topic_id = '" . $topic_data['id'] . "' ORDER BY id");
    // Is user allowed to edit?
    /*if ($account['id'] != $post['author_id'] && $forum->forum_permission($forum_data['id'], $group_id, 'moderator') != '1') {
        $system->message(L_ERROR, L_PERMISSION_ERROR_ACTION, './?s=viewtopic&amp;'.$me.'=' . $topic_data['id'] . '', L_CONTINUE);
    }*/

    // Does the post exist?
    if (!$post) {
        $system->message(L_ERROR, L_POST_ERROR_MISSING, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    }

    if (isset($_POST['Submit'])) {
        if (isset($_POST['title']) && $me == 't') {
            if ($id = $first_post['id']) {
                $title = $secure->clean($_POST['title']);
            } else {
                $title = $topic_data['title'];
            }
        } else {
            $title = $topic_data['title'];
        }

        if (isset($_POST['message'])) {
            $message = $secure->clean($_POST['message']);
        } else {
            $message = $post['message'];
        }
        $db->query("UPDATE " . $prefix . "_posts SET text = '$message' WHERE id = '$id'");
        if ($id == $first_post['id'] && $me == 't') {
            $db->query("UPDATE " . $prefix . "_topics SET title = '$title' WHERE id = '$topic_id'");
        }
        $system->message(L_UPDATED, L_POST_EDIT, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else {
        if ($id != $first_post['id']) {
            $tpl = str_replace($STYLE->getcode('title', $tpl), '', $tpl);
        }

        $tpl = $STYLE->tags($tpl, array(
            "TITLE" => $system->present($topic_data['title']),
            "MESSAGE" => $system->present($post['text']),
            "L_EDIT" => L_EDIT,
            "L_SUBMIT" => L_SUBMIT
        ));
    }
} else if ($mode == 'report' && isset($_GET['post_id'])) {
    $tpl = str_replace(array($STYLE->getcode('reply', $tpl), $STYLE->getcode('normal', $tpl), $STYLE->getcode('edit', $tpl), $STYLE->getcode('quote', $tpl)), '', $tpl);
    if (isset($_POST['Submit']) && isset($_GET['post_id'])) {
        $post_id = $secure->clean($_GET['post_id']);
        $reason = $secure->clean($_POST['reason']);
        if (!$reason) {
            $system->message(L_ERROR, L_INFORMATION_MISSING, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
        $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '$post_id'");
        $text = $secure->clean($post['text']);
        $db->query("INSERT INTO " . $prefix . "_reports (account_id,post_id,reporter_id,reason,content,date) VALUE ('" . $post['author_id'] . "','" . $post['id'] . "','" . $account['id'] . "','" . $reason . "','" . $text . "',UNIX_TIMESTAMP())");
        $system->message(L_SUBMITTED, L_REPORT_POST_SUBMITTED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    }
    $tpl = $STYLE->tags($tpl, array(
        "L_SUBMIT" => L_SUBMIT,
        "L_REPORT" => L_REPORT
    ));
} else if ($mode == 'quote' && isset($_GET['post_id'])) {
    $tpl = str_replace(array($STYLE->getcode('reply', $tpl), $STYLE->getcode('normal', $tpl), $STYLE->getcode('edit', $tpl), $STYLE->getcode('report', $tpl)), '', $tpl);
    $id = $secure->clean($_GET['post_id']);
    $post = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE id = '$id'");
    // Is user allowed to edit?
    /*if ($forum->forum_permission($forum_data['id'], $group_id, 'reply') != '1') {
        $system->message(L_ERROR, L_PERMISSION_ERROR_ACTION, './?s=viewtopic&amp;'.$me.'=' . $topic_data['id'] . '', L_CONTINUE);
    }*/
    // Does the post exist?
    if (!$post) {
        $system->message(L_ERROR, L_POST_ERROR_MISSING, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    }
    if (isset($_POST['Submit'])) {
        $message = $secure->clean($_POST['message']);
        if (!$message) {
            $system->message(L_ERROR, L_INFORMATION_MISSING, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
        if ($_FILES['attachment']['name']) {
            $attachment_id = $forum->attachment($_FILES['attachment']['name'], 'topic', $topic_id);
        } else {
            $attachment_id = '0';
        }
        $result = $db->query("INSERT INTO " . $prefix . "_posts (author_id,topic_id,text,date,attachment)VALUES ('" . $account['id'] . "','$topic_id','$message',UNIX_TIMESTAMP(),'$attachment_id')");
        $db->query("UPDATE " . $prefix . "_topics SET date = UNIX_TIMESTAMP() WHERE id = '$topic_id'");
        // Run Event
        $forum->event('quote');
        $system->message(L_SUBMITTED, L_POST_SUBMITTED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
    } else {
        $tpl = $STYLE->tags($tpl, array(
            "TITLE" => $system->present($topic_data['title']),
            "MESSAGE" => $system->present('[quote]' . $post['text'] . '[/quote]'),
            "L_EDIT" => L_EDIT,
            "L_SUBMIT" => L_SUBMIT,
            "L_QUOTE" => L_QUOTE
        ));
    }
} else {

    // Remove new topic block from display
    $tpl = str_replace(array($STYLE->getcode('edit', $tpl), $STYLE->getcode('reply', $tpl), $STYLE->getcode('quote', $tpl), $STYLE->getcode('report', $tpl)), '', $tpl);

    // Poll

    $poll = $db->fetch("SELECT * FROM " . $prefix . "_polls WHERE topic_id = '" . $topic_id . "'");
    if ($poll) {
        if (isset($_POST['vote']) && isset($_POST['option'])) {
            $option_id = $secure->clean($_POST['option']);
            $db->query("UPDATE " . $prefix . "_polls_option SET total = total + 1 WHERE id = '$option_id'");
            $db->query("INSERT INTO " . $prefix . "_polls_vote (account_id,poll_id,option_id) VALUE ('" . $account['id'] . "','" . $poll['id'] . "','$option_id')");
            $system->message(L_SUBMITTED, L_VOTE_SUBMITTED, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
        $options = '';
        $poll_vote = $db->fetch("SELECT * FROM " . $prefix . "_polls_vote WHERE poll_id = '" . $poll['id'] . "' AND account_id = '" . $account['id'] . "'");
        $option_sql = $db->query("SELECT * FROM " . $prefix . "_polls_option WHERE poll_id = '" . $poll['id'] . "'");
        if ($poll_vote || !$account) {
            while ($row = $option_sql->fetch()) {
                if ($row['total'] > 200) {
                    $pixels = 200;
                } else {
                    $pixels = $row['total'];
                }
                $options .= '<tr><td>' . $system->present($row['value']) . '</td><td><div class="globaltab" style=" width: ' . $pixels . 'px"></div></td><td> ( ' . $system->present($row['total']) . ' )</td></tr>';
            }
        } else {
            while ($row = $option_sql->fetch()) {
                $options .= '<tr><td>' . $system->present($row['value']) . '</td><td><input type="radio" name="option" value="' . $row['id'] . '"></td></tr>';
            }

            $options .= '<tr><td colspan="2"><INPUT TYPE="submit" value ="' . L_SUBMIT . '" class="formcss" name="vote"  style="width: 80px"></td></tr>';
        }
        $poll_style = $STYLE->tags($STYLE->getcode('poll', $tpl), array(
            "L_POLL" => L_POLL, "QUESTION" => $system->present($poll['question']), "OPTIONS" => $options
        ));
        $tpl = str_replace($STYLE->getcode('poll', $tpl), $poll_style, $tpl);
    } else {
        $tpl = str_replace(array($STYLE->getcode('poll', $tpl), $STYLE->getcode('poll_result', $tpl)), '', $tpl);
    }

    // Paginate
    $limiter = $system->data('postlimit');

    $sql = "SELECT * FROM " . $prefix . "_posts WHERE topic_id = '$topic_id' ";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    if ($page != 1) {
        $start = ($page - 1) * $limiter;
    } else {
        $start = 0;
    }

    $relay = "topic/$topic_id?";
    $paginate = $system->paginate("$sql", "$limiter", "$relay");
    // Generate Post's
    $post_sql = $db->query("" . $sql . " ORDER BY id LIMIT $start, $limiter;");
    $realpage =  $db->query("" . $sql . " ORDER BY id")->rowCount();
    if ($realpage / $limiter > $page)
        $realpage = round($realpage / $limiter) + 1;
    else
        $realpage = $page;
    $post_style = '';
    $class = '0';
    $key = $start + 1;
    while ($post_row = $post_sql->fetch()) {
        $post_style_tpl = $post_row_tpl;
        if ($moderation_status == 'true') {
            $check_box = '<input name="checkbox[]" type="checkbox" id="checkbox[]" value="' . $post_row['id'] . '">';
        } else {
            $check_box = '';
        }
        // Change Post Buttons
        $reputation = $db->fetch("SELECT id FROM " . $prefix . "_reputation WHERE account_id = '" . $account['id'] . "' AND post_id = '" . $post_row['id'] . "'");
        if ($account['id'] == $post_row['author_id'] || !$account || $reputation) {
            $post_style_tpl = str_replace($STYLE->getcode('rep', $post_style_tpl), '', $post_style_tpl);
        }
        if ($account['id'] != $post_row['author_id'] && $forum->forum_permission($forum_data['id'], $group_id, 'moderator') != '1') {
            $post_style_tpl = str_replace($STYLE->getcode('authorbutton', $post_style_tpl), '', $post_style_tpl);
        }
        if ($account['id'] != $post_row['author_id'] && $forum->forum_permission($forum_data['id'], $group_id, 'moderator') != '1' || !$post_row['attachment']) {
            $post_style_tpl = str_replace($STYLE->getcode('attachbutton', $post_style_tpl), '', $post_style_tpl);
        }
        if ($post_row['attachment'] != '0') {
            $attachment = $db->fetch("SELECT * FROM " . $prefix . "_attachments WHERE id = '" . $post_row['attachment'] . "'");
            $message = $post_row['text'] . '<br /><div class="attachment"><font class="normfont">' . L_ATTACHMENT . '</font><br /><a href="' . $attachment['file'] . '" class="normfont">' . str_replace('uploads/', '', $attachment['file']) . '</a></div>';
        } else {
            $message = $post_row['text'];
        }
        // Parse the post
        $user_data = $db->fetch("SELECT * FROM accounts WHERE id = '" . $post_row['author_id'] . "'");
        $post_style .= $STYLE->tags($post_style_tpl, array(
            "KEY" => $key,
            "CLASS" => $class,
            "BOX" => $check_box,
            "AUTHOR" => $user->name($post_row['author_id']),
            "AUTHOR_NAME" => $db->fieldFetch('accounts', $post_row['author_id'], 'name'),
            "AUTHOR_ID" => $db->fieldFetch('accounts', $post_row['author_id'], 'name'),
            "AVATAR" => $user->avatar($post_row['author_id']),
            "RANK" => $user->rank($post_row['author_id']),
            "ID" => $system->present($post_row['id']),
            "DATE" => $system->time($post_row['date']),
            "TEXT" => $system->bbcode($message),
            "SIGNATURE" => $system->bbcode($user_data['signature']),
            "POSTCOUNT" => $system->present($user_data['postcount']),
            "STATUS" => $user->status($user_data['id']),
            "REPUTATION" => $system->present($user_data['reputation'])
        ));
        $class = 1 - $class;
        $key++;
    }
    if (empty($post_style))
        $post_style = '<div style="font-weight:bolder; text-align:center;" class="whiter">No comments found</div>';

    // Character?
    $character = '';
    if (strpos($topic_id, 'c') !== false) {
        $requirement = 'Not Unlockable';
        // Check if starter
        $starters = explode(',', $system->data('starters'));
        foreach ($starters as $starter) {
            if ($starter != $topic_data['id']) continue;
            $requirement = 'Starter character';
        }
        $search = $db->query("SELECT * FROM `items` WHERE `value` = '" . $topic_data['id'] . "' AND `name` = 'character'");
        if ($search->rowCount() > 0) {
            $search = $search->fetch();
            $sales = $db->query("SELECT * FROM `sales` WHERE `items` LIKE '" . $search['id'] . "' AND `seller` = -1 ");
            if ($sales) {
                $selling = false;
                while ($sale = $sales->fetch()) {
                    $items = explode(',', $sale['items']);
                    foreach ($items as $item) {
                        if ($item != $search['id']) continue;
                        $selling = true;
                    }
                }
                if ($selling = true) {
                    $requirement = 'Can be bought with Blast Coins';
                }
            }
        }
        // Check missions
        $search = $db->query("SELECT * FROM `missions` WHERE `oncomplete` <> ''");
        while ($searching = $search->fetch()) {
            $unlocks = explode('|', $searching['oncomplete']);
            foreach ($unlocks as $unlock) {
                if (strpos($unlock, 'G') !== false) continue;
                $unlock = substr($unlock, 2);
                if ($unlock == $topic_data['id']) {
                    if (!empty($requirement)) {
                        $requirement .= ' OR ';
                    }
                    $requirement .= 'Completing the mission <a href="' . $siteaddress . 'missions#mission' . $searching['id'] . '" style="color:red;">"' . $searching['name'] . '"</a>';
                }
            }
        }
        $character .= '
            <div class="flex mx-auto character-profile">
            <div class="coverlay">
            <h2>' . $user->image($topic_data['who'], 'animes', './', 'filter') . $topic_data['name'] . '</h2>
            <p class="viewc mx-1">Requirements to unlock: ' . $requirement . '</p>
            <p>' . $user->image($topic_data['id'], 'characters/slanted', './', 'default') . '</p>
            <div class="information">
            <span class="p-1 mx-1 mt-1 hp" style="background: #51d964;">' . $topic_data['health'] . ' Health</span>
            <span class="p-1 mx-1 mt-1 mana" style="background: #34d0f1;">' . $topic_data['mana'] . ' Mana</span>

            <br class="clearfix">
            <p class="cdescription">' . $topic_data['desc'] . '</p>

            </div></div></div>';
        // Here you edit the display for the characters passive skill.
        if (!empty($topic_data['passive'])) {
            $passives = explode(',', $topic_data['passive']);
            $add = '';
            foreach ($passives as $passive) {
                $passive = $db->fetch("SELECT * FROM `skills` WHERE `id` = '" . $passive . "'");
                if ($passive) {
                    $add .= '<div class="coverlay">
                    <h2 style="text-align:left;border-bottom-width: 1px;font-size: 12px;"><span style="color: white;">Passive: </span>
					' . $passive['name'] . '</h2>
					<p>' . $user->image($passive['id'], 'skills', './', 'skill" style="height:35px;width:35px;" title="Skill ' . $passive['name'] . '"') . '
					' . $passive['desc'] . '</p>
                    <br class="clearfix">';
                }
            }
            if (!empty($add)) {
                $character .= '<br class="clearfix">
				<div class="passive-layout">

				' . $add . '
				<p style="color: #fff;text-align: center;font-size: 11px;">*Passives trigger automatically when the match starts*</p>
                <br class="clearfix">
                </div></div></div></div>';
            }
        }
        /* // Here you edit the display for the characters skill effects.
        if (!empty($topic_data['skilleffects'])) {
            $skilleffects = explode(',', $topic_data['skilleffects']);
            $add = '';
            foreach ($skilleffects as $skilleffect) {
                $skilleffect = $db->fetch("SELECT * FROM `skills` WHERE `id` = '" . $skilleffect . "'");
                if ($skilleffect) {
                    $add .= '<div class="coverlay">
                    <h2 style="border-bottom-width: 1px;font-size: 12px;"><span style="color: white;">Character/Skill Effects: </span>
					' . $skilleffect['name'] . '</h2>
					<p>' . $user->image($skilleffect['id'], 'skills', './', 'skill" style="height:35px;width:35px;" title="Skill ' . $skilleffect['name'] . '"') . '
					' . $skilleffect['desc'] . '</p>
                    <br class="clearfix">';
                }
            }
            if (!empty($add)) {
                $character .= '<br class="clearfix">
				<div class="passive-layout">

				' . $add . '
				<p style="color: #d71613;text-align: center;font-size: 11px;">*Effects vary per character.*</p>
                <br class="clearfix">
                </div></div></div></div>';
            }
        } */

        $skillset = explode(',', $topic_data['skills']);
        // $skilleffects = explode(',', $topic_data['skilleffects']);
        $passives = explode(',', $topic_data['passive']);
        $skills = array_merge($skillset, $passives);
        $alts = '';
        $keys = array();
        foreach ($skills as $_ => $skill) {
            $keys[] = $skill;
        }
        $character .= '<div class="skill-container">
                        <div class="soverlay">';
        foreach ($skills as $_ => $skill) {
            $s = $db->fetch("SELECT * FROM skills WHERE id = '" . $skill . "'");
            $effects = $s['effects'];
            $effects = explode(',', $effects);

            foreach ($effects as $effect) {

                if ($db->fieldFetch('effects', $effect, 'replace') !== 'undefined' && $db->fieldFetch('effects', $effect, 'replace') !== '') {
                    $replacements = true;
                    $ez = explode('|', $db->fieldFetch('effects', $effect, 'replace'));

                    do {
                        if (!empty($salt)) {
                            $ez = $salt;
                        }

                        $salt = array();
                        foreach ($ez as $looks) {
                            if (in_array($looks, $keys, true)) {
                                continue;
                            }

                            $keys[] = $looks;
                            $alt = $db->query("SELECT * FROM skills WHERE id = '" . $looks . "'");
                            if ($alt->rowCount() > 0) {
                                $alt = $alt->fetch();
                                $classes = $alt['classes'];
                                $archive = "";
                                if (!empty($classes)) {
                                    $classes = explode(',', $alt['classes']);
                                    foreach ($classes as $class) {
                                        if ($db->fieldFetch('classes', $class, 'name') === 'undefined') {
                                            continue;
                                        }

                                        $archive .= $user->image($db->fieldFetch('classes', $class, 'name'), 'classes', './', 'skill-class" title="Skill Class ' . $db->fieldFetch('classes', $class, 'name') . '"');
                                    }
                                }
                                $alts .= '
                                <div class="skill-layout alt" style="display:none;">
                                <div class="soverlay">
									<h2>' . $alt['name'] . '</h2>
									' . $user->image($alt['id'], 'skills', './', 'skill') . '
									<p>' . $system->keywordReplacements($alt['desc']) . '<br class="clearfix">
									</p>
										<div class="information">
											<p class="viewc">Cooldown: ' . $alt['cooldown'] . '</p>
											<span class="hp_mana">' . $alt['cost'] . ' Mana</span>
											<br class="clearfix">
											' . $archive . '
										</div>
									</div></div>';
                                $aeffects = explode(',', $alt['effects']);
                                foreach ($aeffects as $ae) {
                                    if ($db->fieldFetch('effects', $ae, 'replace') !== 'undefined' && $db->fieldFetch('effects', $ae, 'replace') !== '') {
                                        $as = explode('|', $db->fieldFetch('effects', $ae, 'replace'));
                                        foreach ($as as $askill) {
                                            if (in_array($askill, $keys, true)) {
                                                continue;
                                            }

                                            $salt[] = $askill;
                                        }
                                    }
                                }
                            }
                        }
                        if (empty($salt)) {
                            $replacements = false;
                        }
                    } while ($replacements === true);
                }
            }
            $classes = $s['classes'];
            $archive = "";
            if (!empty($classes)) {
                $classes = explode(',', $s['classes']);
                foreach ($classes as $class) {
                    if ($db->fieldFetch('classes', $class, 'name') === 'undefined') {
                        continue;
                    }

                    $archive .= $user->image($db->fieldFetch('classes', $class, 'name'), 'classes', './', 'skill-class" title="Skill Class ' . $db->fieldFetch('classes', $class, 'name') . '"');
                }
            }
            if (array_search($skill, $passives) === false) {
                $character .= '<!-- BEGIN skill_' . $_ . ' -->
				<div class="skill-layout" data-key="' . $_ . '">
							<h2>' . $s['name'] . '</h2>
				' . $user->image($s['id'], 'skills', './', 'skill', $s['id']) . '
				<p>' . $system->keywordReplacements($s['desc']) . '<br class="clearfix">
				</p><div class="information">
				<p class="viewc">Cooldown: ' . $s['cooldown'] . '</p>
				<span class="hp_mana">' . $s['cost'] . ' Mana</span>
				<br class="clearfix">
				' . $archive . '
				</div>
                </div><!-- END skill_' . $_ . ' -->';
            }
        }
        $character .= '</div></div>';
        if (!empty($alts)) {
            $character .= '<h4 class="my-2 alternative" style="color:#ffffff; text-align:center; font-weight:bolder;">Alternative Skills</h1></div>' . $alts;
        }
    }

    if (isset($_POST['Submit'])) {

        // Prevent Flooding
        if (!empty($account['lastpost']) && time() < $account['lastpost'] + $system->data('anti_flood')) {
            $system->message(L_ERROR, L_FLOOD_ERROR, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
        if (isset($topic_data['locked']) && $topic_data['locked'] == '1') { // Is it locked?
            $system->message(L_ERROR, L_TOPIC_LOCKED_ERROR, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }
        if ($forum->forum_permission($forum_data['id'], $group_id, 'reply') == '0') { // Allowed?
            $system->message(L_ERROR, L_PERMISSION_ERROR_ACTION, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }


        $message = $secure->clean($_POST['message']);
        if (!$message) {
            $system->message(L_ERROR, L_INFORMATION_MISSING, './topic/' . $topic_data['id'] . '', L_CONTINUE);
        }/*print_r(123123);
        if (isset($_FILES['attachment']['name'])) {
            $attachment_id = $forum->attachment($_FILES['attachment']['name'], 'topic', $topic_id);
        } else {*/
        $attachment_id = '0';

        $result = $db->query("INSERT INTO " . $prefix . "_posts (author_id,topic_id,text,date,attachment)VALUES ('" . $account['id'] . "','$topic_id','$message',UNIX_TIMESTAMP(),'$attachment_id')");
        $db->query("UPDATE " . $prefix . "_topics SET date = UNIX_TIMESTAMP() WHERE id = '$topic_id'");
        // Inform Watching
        $watching = $db->query("SELECT * FROM " . $prefix . "_watching WHERE topic_id = '" . $topic_id . "'");
        while ($watch_row = $watching->fetch()) {
            $system->mail($watch_row['account_id'], 1, L_WATCH_TOPIC_TITLE, str_replace('[LINK]', "[url=" . $siteaddress . "topic/" . $topic_data['id'] . "]" . $siteaddress . "topic/" . $topic_data['id'] . "[/url]", L_WATCH_TOPIC_MESSAGE));
        }
        // Run Event
        $forum->event('reply');
        if ($me == 'c')
            $system->redirect($siteaddress . 'characters-and-skills/' . $db->fieldFetch('characters', $topic_data['id'], 'name'));
        else
            $system->redirect($siteaddress . 'topic/' . $topic_data['id'] . '?page=' . $realpage . '#' . $key);
    } else {
        if (isset($topic_data['locked']) && $topic_data['locked'] == '1') goto cantpost;
        $post = $STYLE->getcode('reply', $STYLE->open('topic.tpl'));
        $brief = $db->query("SELECT * FROM " . $prefix . "_posts WHERE topic_id = '" . $topic_id . "' ORDER BY id LIMIT 10");
        $brief_style = '';
        $class = '0';
        while ($post_row = $brief->fetch()) {

            $brief_style .= $STYLE->tags($STYLE->getcode('reply_row', $post), array(
                "CLASS" => $class,
                "AUTHOR" => $user->name($post_row['author_id']),
                "DATE" => $system->time($post_row['date']),
                "TEXT" => $system->bbcode($post_row['text'])

            ));
            $class = 1 - $class;
        }
        $post = str_replace(array($STYLE->getcode('reply_row', $post)), $brief_style, $post);
        // Parse the template
        $post = $STYLE->tags($post, array("L_REPLY" => L_REPLY, "L_TOPIC" => L_TOPIC, "L_SUBMIT" => L_SUBMIT, "ATTACHMENT" => $attachment));
    }
    $tpl .= $post;
    cantpost:


    // Parse the template
    $tpl = str_replace($post_row_tpl, $post_style, $tpl);
    $tpl = $STYLE->tags($tpl, array(
        "PAGINATE" => $paginate,
        "T" => $topic_id,
        "L_FORUM" => L_FORUM,
        "FORUM_NAME" => $system->present($forum_data['name']),
        "TOPIC_NAME" => (strpos($topic_id, 'c') !== false) ? $character : $system->bbcode($system->present($topic_data['title'])),
        "L_POSTS" => L_POSTS,
        "L_REPUTATION" => L_REPUTATION,
        "L_STATUS" => L_STATUS,
        "L_REP" => L_REP,
        "L_PROFILE" => L_PROFILE,
        "L_MAIL" => L_SEND_MAIL,
        "L_QUOTE" => L_QUOTE,
        "L_REPORT" => L_REPORT,
        "L_EDIT" => L_EDIT,
        "L_VIEWING" => L_VIEWING,
        "L_DELETE_ATTACHMENT" => L_DELETE_ATTACHMENT,
        "USERS" => $system->viewing($session_location)
    ));
}

$tpl = $STYLE->tags($tpl, array(
    "BBCODES" => $system->returnBBcodes()
));

$output .= $tpl;