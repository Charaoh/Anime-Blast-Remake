<?php


include("../inc/header.php");
if ($account['group'] == 5 && $account['name'] == 'Metraletta') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
require("" . $root . "/lang/$language/admin.php");
$page_title = '<a href="' . $siteaddress . 'acp/" class="normfont">' . L_ADMIN_PANEL . '</a>';
if (!$account) {
    $system->redirect('../');
}

$limitedAccess = false;
if ($account['rank'] == 14) {
    if ($system->group_permission($group_id, 'acp') !== '1')
        $limitedAccess = true;
}

/* $staffAccess = false;
if ($account['group'] == 6 || $account['rank'] == 14) {
    if ($system->group_permission($group_id, 'acp') !== '1')
        $staffAccess = true;
} */

$where = (!isset($where) ? '' : $where);
if ($system->group_permission($group_id, 'acp') == '1' || $limitedAccess === true) {
    $STYLE->__add('files', 'CSS', '', '/css/index.css');
    $STYLE->__add('files', 'CSS', '', '/inc/1.js');

    // Global Menu
    if ($limitedAccess) {
        if (!isset($where)) {
            $system->redirect($siteaddress . 'acp/');
        } else {
            if (isset($where) && !empty($where) && $where['s'] !== 'game')
                $system->redirect($siteaddress . 'acp/');
        }
    }

    /* if ($staffAccess) {
        if (!isset($where)) {
            $system->redirect($siteaddress . 'acp/');
        } else {
            if (isset($where) && !empty($where) && $where['s'] !== 'website')
                $system->redirect($siteaddress . 'acp/');
        }
    } */


    $tpl = $STYLE->open('./acp/menu.tpl');
    $global_menu = $STYLE->getcode('menu', $tpl);
    $tpl = str_replace($global_menu, '', $tpl);
    $global_menu = $STYLE->tags($global_menu, array("L_HOME" => L_HOME, "L_SYSTEM" => L_SYSTEM, "L_COMMUNITY" => L_COMMUNITY, "L_FORUMS" => L_FORUMS, "L_EXIT" => L_EXIT));
    unset($tpl);

    if (!isset($_GET['s'])) {
        $s = '';
    } else {
        $s = $secure->clean($_GET['s']);
    }

    if (isset($_GET['module'])) {
        $mode = $secure->clean($_GET['module']);
    } else {
        $mode = '';
    }

    if ($s == 'exit') {
        include("./core/exit.php");
    } else if ($s == 'forums' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=forums" class="normfont">' . L_FORUMS . '</a>';
        include("./core/forums.php");
    } else if ($s == 'groups' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=groups" class="normfont">' . L_GROUPS . '</a>';
        include("./core/groups.php");
    } else if ($s == 'ranks' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=ranks" class="normfont">' . L_RANKS . '</a>';
        include("./core/ranks.php");
    } else if ($s == 'bans' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=bans" class="normfont">' . L_BANS . '</a>';
        include("./core/bans.php");
    } else if ($s == 'bans-email' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=bans" class="normfont">' . L_BANS . '</a>';
        include("./core/bans-email.php");
    } else if ($s == 'bans-name' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=bans" class="normfont">' . L_BANS . '</a>';
        include("./core/bans-name.php");
    } else if ($s == 'bans-ip' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=bans" class="normfont">' . L_BANS . '</a>';
        include("./core/bans-ip.php");
    } else if ($s == 'community' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=community" class="normfont">' . L_COMMUNITY . '</a>';
        include("./core/community.php");
    } else if ($s == 'system' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=system" class="normfont">' . L_SYSTEM . '</a>';
        include("./core/system.php");
    } else if ($s == 'account-settings' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=system" class="normfont">' . L_SYSTEM . '</a> / ' . L_SETTINGS;
        include("./core/account-settings.php");
    } else if ($s == 'website' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=website" class="normfont"> Website Editor</a>';
        include("./core/website.php");
    } else if ($s == 'website' && !$limitedAccess) {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=website" class="normfont"> Website Editor</a>';
        include("./core/website.php");
    } else if ($s == 'game') {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/?s=game" class="normfont"> Game Editor</a>';
        include("./core/game.php");
    } else {
        $page_title .= ' / <a href="' . $siteaddress . 'acp/" class="normfont">' . L_HOME . '</a>';
        include("./core/home.php");
    }
} else {
    $system->redirect("../");
}
include("../inc/footer.php");