<?php

$newsTemplate = $STYLE->open('homepage.tpl');
$newsContent = $system->data('sliders');
$newsContent = explode(',', $newsContent);
$items = '';
foreach($newsContent as $news){
	
	$topic_data = $db->query("SELECT * FROM " . $prefix . "_topics WHERE forum_id = '$news' ORDER BY date DESC LIMIT 5");
	if($topic_data->rowCount() > 0){
		while($topic = $topic_data->fetch()){
            $newTemplate = $newsTemplate;
            $items .= $STYLE->tags($newTemplate, array(
                "TITLE" => $topic['title'],
                "TIME" => '> '.$system->time($topic['date']).' - '.$user->name($topic['author_id']),
                "AVATAR" => $user->image($topic['author_id'], 'avatars'),
                "LINK" => $siteaddress.'topic/'.$topic['id']
            ));
		}
	}

}

if(empty($items))
    $items = 'No new updates...';

$output .= $items;
?>