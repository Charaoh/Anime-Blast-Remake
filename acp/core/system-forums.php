<?php

$tpl = $STYLE->open('./acp/system-forums.tpl');
if (isset($_POST['submit'])) {
    if (isset($_POST['topic'])) {
        $topic = $secure->clean($_POST['topic']);
    } else {
        $topic = $system->data('topiclimit');
    }
    if (isset($_POST['post'])) {
        $post = $secure->clean($_POST['post']);
    } else {
        $post = $system->data('postlimit');
    }
    if (isset($_POST['hot'])) {
        $hot = $secure->clean($_POST['hot']);
    } else {
        $hot = $system->data('hottopic');
    }
    if (isset($_POST['anti_flood'])) {
        $anti_flood = $secure->clean($_POST['anti_flood']);
    } else {
        $anti_flood = $system->data('anti_flood');
    }
    if (isset($_POST['attachment_file_size'])) {
        $attachment_file_size = $secure->clean($_POST['attachment_file_size']);
    } else {
        $attachment_file_size = $system->data('attachment_file_size');
    }
    $db->query("UPDATE `settings` SET value = '$topic' WHERE name = 'topiclimit'");
    $db->query("UPDATE `settings` SET value = '$post' WHERE name = 'postlimit'");
    $db->query("UPDATE `settings` SET value = '$hot' WHERE name = 'hottopic'");
    $db->query("UPDATE `settings` SET value = '$anti_flood' WHERE name = 'anti_flood'");
    $db->query("UPDATE `settings` SET value = '$attachment_file_size ' WHERE name = 'attach_filesize'");
    $system->redirect("./?s=system&module=forums", true);
}
$output .= $STYLE->tags($tpl, array(
    "TOPIC" => $system->data('topiclimit'),
    "POST" => $system->data('postlimit'),
    "HOT" => $system->data('hottopic'),
    "ANTIFLOOD" => $system->data('anti_flood'),
    "ATTACHMENT_FILE_SIZE" => $system->data('attach_filesize'),
    "L_SUBMIT" => L_SUBMIT,
    "L_POSTS" => L_POSTS,
    "L_POSTS_MSG" => L_POSTS_MSG,
    "L_TOPICS" => L_TOPICS,
    "L_TOPICS_MSG" => L_TOPICS_MSG,
    "L_HOT" => L_HOT,
    "L_HOT_MSG" => L_HOT_MSG,
    "L_ANTI_FLOOD" => L_ANTI_FLOOD,
    "L_ANTI_FLOOD_MSG" => L_ANTI_FLOOD_MSG,
    "L_ATTACHMENT_FILE_SIZE" => L_ATTACHMENT_FILE_SIZE,
    "L_ATTACHMENT_FILE_SIZE_MSG" => L_ATTACHMENT_FILE_SIZE_MSG,
    "L_FORUM_SETTINGS" => L_FORUM_SETTINGS
));