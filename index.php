<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);  
error_reporting(E_ALL);

*/

require("./inc/header.php");
$page_title = '<a href="' . $siteaddress . '" class="normfont">' . $system->data('sitename') . '</a> > ';
require("$root/lang/$language/forum.php");
if (isset($_GET['s'])) {
    $section = $secure->clean($_GET['s']);
} else {
    $section = '';
}

if ($section != 'game') {
    $STYLE->__add('files', 'CSS', '', '/css/index.css');
    $STYLE->__add('files', 'JAVA', '', '/inc/1.js');
}

// Topic Short Url
if (isset($_GET['topic'])) {
    $tid = $secure->clean($_GET['topic']);
    $description .= '- Viewing a site topic > ' . $db->fieldFetch('topics', $tid, 'title');
    $metatags .= ', topic, ' . $db->fieldFetch('topics', $tid, 'title');
    include("./core/topic.php");
} else if ($section == 'login' && !$account['id']) {
    $page_title = '<a href="' . $siteaddress . '?s=login" class="normfont">' . L_LOGIN . '</a>';
    include("./core/login-page.php");
} else if ($section == 'logout' && $account['id']) {
    $page_title = '<a href="' . $siteaddress . 'logout" class="normfont">' . L_LOGOUT . '</a>';
    include("./core/logout.php");
}/* else if ($section == 'register' && !$account['id']) {
    $page_title = '<a href="' . $siteaddress . '?s=register" class="normfont">' . L_REGISTER . '</a>';
    include("./core/register.php");
    }*/ else if ($section == 'exclusive' && $account['id'] && $system->data('Hide-Exclusives') !== '1') {
    $description .= '- Claim your exclusive character!';
    $metatags .= ', account, exclusive, prize';
    $page_title = '<a href="' . $siteaddress . 'exclusive" class="normfont">Exclusive Panel</a>';
    include("./core/activate.php");
} else if ($section == 'ucp' && $account['id']) {
    $description .= '- User Control Panel';
    $metatags .= ', user, control, panel';
    $page_title = '<a href="' . $siteaddress . 'control-panel" class="normfont">' . L_USER_CONTROL_PANEL . '</a>';
    include("./core/ucp.php");
} else if ($section == 'lostpassword' && !$account['id']) {
    $description .= '- Recovery your account here';
    $metatags .= ', new, password, recovery';
    $page_title = '<a href="' . $siteaddress . 'password-recovery" class="normfont">' . L_LOST_PASSWORD . '</a>';
    include("./core/lostpassword.php");
} else if ($section == 'profile') {
    $description .= '- Browsing an anime-blast profile';
    $metatags .= ', browsing, profile, on, anime-blast, aBlast, anime, blast';
    include("./core/profile.php");
} else if ($section == 'groups') {
    $description .= '- In this page you will see the anime-blast staff! At your orders good Sir.';
    $metatags .= ', browsing, the, team, on, anime-blast, aBlast, anime, blast';
    $page_title = '<a href="' . $siteaddress . 'the-team" class="normfont">' . L_GROUPS . '</a>';
    include("./core/groups.php");
} else if ($section == 'viewtopic') {
    $page_title .= '<a href="' . $siteaddress . 'forum" class="normfont">Forum ></a></a>';
    include("./core/topic.php");
} else if ($section == 'viewforum') {
    $page_title .= '<a href="' . $siteaddress . 'forum" class="normfont">Forum ></a></a>';
    include("./core/forum.php");
} else if ($section == 'report') {
    $description .= '- Report to our team an issue.';
    $page_title = '<a href="' . $siteaddress . 'report" class="normfont">' . L_REPORT . '</a>';
    include("./core/report.php");
} else if ($section == 'search') {
    $description .= '- Search the site.';
    $page_title = '<a href="' . $siteaddress . 'search" class="normfont">' . L_SEARCH . '</a>';
    include("./core/search.php");
} else if ($section == 'mail' && $account) {
    $description .= '- maaaailbox.';
    $page_title = '<a href="' . $siteaddress . 'mail" class="normfont">' . L_MAIL . '</a>';
    include("./core/mail.php");
} else if ($section == 'tos') {
    $description .= '- The terms of our service, please read throughoutly.';
    $page_title = '<a href="' . $siteaddress . 'terms-of-service" class="normfont">' . L_TERMS_OF_SERVICE . '</a>';
    $system->page(L_TERMS_OF_SERVICE, $system->data('tos'));
} /*else if ($section == 'shop' && $account) {
	$page_title = '<a href="' . $siteaddress . 'shop" class="normfont">Coin Shop</a>';
    include("./core/shop.php");
}*/ elseif ($section == 'game') {
    if (!$account)
        $system->redirect('./');
    define('SITE', 'www.anime-blast.com');
    $description .= '- Ingame of ' . $account['name'];
    $page_title = '<a href="' . $siteaddress . 'ingame" class="normfont">Main Menu</a>';
    include('./' . $version . '/core/game.php');
} else if ($section == 'clan') {
    $description .= '- Browsing clans.';
    $metatags .= ', browsing, profile, clan, on, anime-blast, aBlast, anime, blast';
    $page_title .= '<a href="' . $siteaddress . 'clans" class="normfont">Clans</a>';
    include("./core/clan.php");
} else if ($section == 'missions' && $account) {
    $description .= '- aBlast Missions';
    $page_title = '<a href="' . $siteaddress . 'missions" class="normfont">Missions Main</a>';
    include("./core/missions.php");
} else if ($section == 'forum') {
    $page_title .= '<a href="' . $siteaddress . 'forum" class="normfont">Forum Main</a></a>';
    include('./core/home.php');
} else {
    $page_title .= '<a href="' . $siteaddress . 'forum" class="normfont">Viewing Anime-Blast forums!</a>';
    include('./core/main.php');
}
require("./inc/footer.php");