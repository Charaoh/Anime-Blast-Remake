<?php


include("../inc/header.php");
if ($account['group'] == 5 && $account['name'] == 'Metraletta') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
require("" . $root . "/lang/$language/admin.php");
$page_title = '<a href="' . $siteaddress . 'staff/" class="normfont">' . L_ADMIN_PANEL . '</a>';
if (!$account) {
    $system->redirect('../');
}

$limitedAccess = false;
if ($account['rank'] == 14) {
    if ($system->group_permission($group_id, 'staff') !== '1')
        $limitedAccess = true;
}

/* $staffAccess = false;
if ($account['group'] == 6 || $account['rank'] == 14) {
    if ($system->group_permission($group_id, 'staff') !== '1')
        $staffAccess = true;
} */

$where = (!isset($where) ? '' : $where);
if ($system->group_permission($group_id, 'staff') == '1' || $limitedAccess === true) {
    $STYLE->__add('files', 'CSS', '', '/css/index.css');
    $STYLE->__add('files', 'CSS', '', '/inc/1.js');

    // Global Menu
    if ($limitedAccess) {
        if (!isset($where)) {
            $system->redirect($siteaddress . 'staff/');
        } else {
            if (isset($where) && !empty($where) && $where['s'] !== 'game')
                $system->redirect($siteaddress . 'staff/');
        }
    }

    /* if ($staffAccess) {
        if (!isset($where)) {
            $system->redirect($siteaddress . 'staff/');
        } else {
            if (isset($where) && !empty($where) && $where['s'] !== 'website')
                $system->redirect($siteaddress . 'staff/');
        }
    } */


    $tpl = $STYLE->open('./staff/menu.tpl');
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
    } else if ($s == 'game') {
        $page_title .= ' / <a href="' . $siteaddress . 'staff/?s=game" class="normfont"> Game Editor</a>';
        include("./core/game.php");
    } else {
        $page_title .= ' / <a href="' . $siteaddress . 'staff/" class="normfont">' . L_HOME . '</a>';
        include("./core/home.php");
    }
} else {
    $system->redirect("../");
}
include("../inc/footer.php");