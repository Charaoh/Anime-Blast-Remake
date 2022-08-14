<?php


$forum = new forum();
$tpl = $STYLE->open('home.tpl');
// Generate Global Menu
$global_menu = $STYLE->getcode('menu', $tpl);
$tpl = str_replace($global_menu, '', $tpl);
$global_menu = $STYLE->tags($global_menu, array("L_SEARCH" => L_SEARCH, "L_NEW_POSTS" => L_NEW_POSTS));

// Sort Style Data
$cat = $STYLE->getcode('category', $tpl);
$for = $STYLE->getcode('row', $tpl);
$break = $STYLE->getcode('break', $tpl);
$category_end = $STYLE->getcode('category_end', $tpl);
$tpl = str_replace(array($break, $for, $category_end), '', $tpl);
$content = '';
$forum_style = '';
$class = '0';
$category_sql = $db->query("SELECT * FROM " . $prefix . "_categories ORDER by sort ASC");
while ($category_row = $category_sql->fetch()) {
    // Check if Category allowed?
    if ($forum->category_permission($category_row['id'], $group_id, 'view') == '1') {
        $forum_sql = $db->query("SELECT * FROM " . $prefix . "_forums WHERE cat_id = " . $category_row['id'] . " AND parent_id = '0' ORDER by sort ASC");
        while ($forum_row = $forum_sql->fetch()) {
            // Check if forum allowed?
            if ($forum->forum_permission($forum_row['id'], $group_id, 'view') == '1') {
                // Check for Sub Forums
                $subforum = '';
                $sub_forum_sql = $db->query("SELECT * FROM " . $prefix . "_forums WHERE parent_id = '" . $forum_row['id'] . "'");
                $topic_number = '';
                $topic_number = $db->query("SELECT * FROM " . $prefix . "_topics WHERE forum_id='" . $forum_row['id'] . "' ORDER BY date DESC;")->rowCount();

                while ($sub_forum_row = $sub_forum_sql->fetch()) {
                    // Check if sub forum allowed?
                    if ($forum->forum_permission($sub_forum_row['id'], $group_id, 'view') == '1') {
                        $subforum_topic_number = $db->query("SELECT * FROM " . $prefix . "_topics WHERE forum_id='" . $sub_forum_row['id'] . "' ORDER BY date DESC;")->rowCount();
                        $topic_number = $topic_number + $subforum_topic_number;
                        $subforum .= '<a href="./forum/' . $sub_forum_row['id'] . '" class="normfont" style="font-style: italic">' .  $system->bbcode($system->present($sub_forum_row['name'])) . '</a> ';
                    }
                }
                if ($subforum) {
                    $subforum = L_SUBFORUM . ': ' . $subforum . '<br />';
                }
                // Find Moderators
                $moderator_sql = $db->query("SELECT * FROM " . $prefix . "_forums_permission WHERE forum_id = '" . $forum_row['id'] . "' AND moderator = '1'");
                $moderators = '';
                while ($moderator_row = $moderator_sql->fetch()) {
                    $moderators .= ' ' . $system->group($moderator_row['group_id']);
                }

                if ($moderators) {
                    $moderators = L_MODERATORS . ': ' . $moderators;
                }
                $topic_sql = $db->query("SELECT * FROM " . $prefix . "_topics WHERE forum_id='" . $forum_row['id'] . "' ORDER BY date DESC;");

                if ($topic_row = $topic_sql->fetch()) {
                    $post_sql = $db->fetch("SELECT * FROM " . $prefix . "_posts WHERE topic_id = '" . $topic_row['id'] . "' ORDER BY id DESC LIMIT 1;");
                    $author = $user->name($post_sql['author_id']);
                    $topic = '<a href="./topic/' . $topic_row['id'] . '" class="normfont">' .  $system->bbcode($system->present($topic_row['title'])) . '</a><br />' . $author . '<br /> ' . $system->time($topic_row['date']) . '';
                } else {
                    $topic = L_EMPTY;
                }
				$read = '';
                if (!$account) {
                    $read = 'read';
                } else {
                    $row = $db->fetch("SELECT * FROM " . $prefix . "_topics WHERE forum_id = '" . $forum_row['id'] . "' ORDER BY date DESC;");
                    $timeout = time() - 43200;
                    if ($row['date'] < $timeout) {
                        $read = 'read';
                    } else {
                        ;
                        $read_row = $db->fetch("SELECT * FROM " . $prefix . "_forums_read WHERE account_id ='" . $account['id'] . "' AND topic_id ='" . $row['id'] . "' AND date > '" . $row['date'] . "' ");

                        if ($read_row) {
                            $read = 'read';
                        } else {
                            $read = 'unread';
                        }
                    }
                }
				if($forum_row['type'] == '1'){
					$read = 'characters';
					$topic = '';
					$topic_number = '';
				}
                $forum_style .= $STYLE->tags($for, array("MODERATORS" => $moderators, "ICON" => $read, "TOPIC" => $topic, "TOPIC_COUNT" => $topic_number, "SUB" => $subforum, "INFO" => $system->bbcode($forum_row['info']), "CLASS" => $class, "FORUM" => '<a href="./forum/' . $forum_row['id'] . '" class="normfont">' . stripslashes($forum_row['name']) . '</a>'));
            }
            $class = 1 - $class;
        }
        $category_style = $STYLE->tags($cat, array("NAME" => $category_row['name']));
        $content .= $category_style . $forum_style . $category_end . $break;
    }
    $forum_style = '';
}

$user_count = $db->query("SELECT id FROM accounts")->rowCount();
$topic_count = $db->query("SELECT id FROM " . $prefix . "_topics")->rowCount();
$post_count = $db->query("SELECT id FROM " . $prefix . "_posts")->rowCount();
$stats = str_replace(array('[USERS]', '[TOPICS]', '[POSTS]'), array($user_count, $topic_count, $post_count), L_FORUM_STATS);
$tpl = str_replace($cat, $content, $tpl);
$user_online_sql = $db->query("SELECT * FROM online WHERE account_id != '-1';");
$users = '';
while ($user_online_row = $user_online_sql->fetch()) {
    $users .= $user->name($user_online_row['account_id']) . ' ';
}
if (!$users) {
    $users = L_NONE;
}
$output .= $STYLE->tags($tpl, array("L_STATS" => $stats, "L_FORUM" => L_FORUM, "L_TOPICS" => L_TOPICS, "L_LATEST" => L_LATEST, "L_SUMMARY" => L_SUMMARY, "L_ONLINE" => L_ONLINE, "ONLINE_STATS" => $users));
?>