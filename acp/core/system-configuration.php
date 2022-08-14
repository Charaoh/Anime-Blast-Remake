<?php

$tpl = $STYLE->open('./acp/system-configuration.tpl');
if (isset($_POST['submit'])) {
    if (!isset($_POST['metainfo'])) {
        $metainfo = '';
    } else {
        $metainfo = $secure->clean($_POST['metainfo']);
    }
    if (!isset($_POST['newsContainer'])) {
        $newsContainer = '';
    } else {
        $newsContainer = $secure->clean($_POST['newsContainer']);
    }
    if (!isset($_POST['metakeywords'])) {
        $metakeywords = '';
    } else {
        $metakeywords = $secure->clean($_POST['metakeywords']);
    }
    if (!isset($_POST['session_name'])) {
        $session_name = '';
    } else {
        $session_name = $secure->clean($_POST['session_name']);
    }
    if (!isset($_POST['admin_email'])) {
        $admin_email = '';
    } else {
        $admin_email = $secure->clean($_POST['admin_email']);
    }
    if (!isset($_POST['sitestatus'])) {
        $sitestatus = '';
    } else {
        $sitestatus = $secure->clean($_POST['sitestatus']);
    }
    if (!isset($_POST['name'])) {
        $name = '';
    } else {
        $name = $secure->clean($_POST['name']);
    }
    if (!isset($_POST['url'])) {
        $url = '';
    } else {
        $url = $secure->clean($_POST['url']);
    }
    if (!isset($_POST['path'])) {
        $path = '';
    } else {
        $path = $secure->clean($_POST['path']);
    }
    if (!isset($_POST['template'])) {
        $template = '';
    } else {
        $template = $secure->clean($_POST['template']);
    }
    if (!isset($_POST['tos'])) {
        $tos = '';
    } else {
        $tos = $secure->clean($_POST['tos']);
    }
    if (!isset($_POST['facebook_like'])) {
        $facebook_like = '';
    } else {
        $facebook_like = $secure->clean($_POST['facebook_like']);
    }
    if (!isset($_POST['rss'])) {
        $rss = '';
    } else {
        $rss = $secure->clean($_POST['rss']);
    }
    if (preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $admin_email)) {
        $db->query("UPDATE `settings` SET value='$admin_email' WHERE name = 'adminemail'");
    }
    $db->query("UPDATE `settings` SET value='$name' WHERE name = 'sitename'");
    $db->query("UPDATE `settings` SET value='$url' WHERE name = 'url'");
    $db->query("UPDATE `settings` SET value='$path' WHERE name = 'path'");
    $db->query("UPDATE `settings` SET value='$template' WHERE name = 'template'");
    $db->query("UPDATE `settings` SET value='".($sitestatus != '0'?time():'0')."' WHERE name = 'siteclosed'");
    $db->query("UPDATE `settings` SET value='$session_name' WHERE name = 'session'");
    $db->query("UPDATE `settings` SET value='$metainfo' WHERE name = 'meta_info'");
    $db->query("UPDATE `settings` SET value='$metakeywords' WHERE name = 'meta_keywords'");
    $db->query("UPDATE `settings` SET value='$facebook_like' WHERE name = 'facebook_like'");
    $db->query("UPDATE `settings` SET value='$tos' WHERE name = 'tos'");
    $db->query("UPDATE `settings` SET value='$rss' WHERE name = 'rss'");
    $db->query("UPDATE `settings` SET value='$newsContainer' WHERE name = 'sliders'");
    $system->redirect("./?s=system&module=configuration", true);
}

if ($system->data('userreg') == '1') {
    $ryes = 'selected';
    $rno = '';
} else {
    $rno = 'selected';
    $ryes = '';
}
if ($system->data('siteclosed') != '0') {
    $syes = 'selected';
    $sno = '';
} else {
    $sno = 'selected';
    $syes = '';
}
if ($system->data('iplock') == '1') {
    $ipyes = 'selected';
    $ipno = '';
} else {
    $ipno = 'selected';
    $ipyes = '';
}
if ($system->data('showpass') == '1') {
    $spyes = 'selected';
    $spno = '';
} else {
    $spno = 'selected';
    $spyes = '';
}
if ($system->data('usertemplate') == '1') {
    $utyes = 'selected';
    $utno = '';
} else {
    $utno = 'selected';
    $utyes = '';
}
if ($system->data('avatar') == '1') {
    $avyes = 'selected';
    $avno = '';
} else {
    $avno = 'selected';
    $avyes = '';
}
if ($system->data('facebook_like') == '1') {
    $fbyes = 'selected';
    $fbno = '';
} else {
    $fbno = 'selected';
    $fbyes = '';
}

if ($system->data('rss') == '1') {
    $rssyes = 'selected';
    $rssno = '';
} else {
    $rssno = 'selected';
    $rssyes = '';
}
$directory = @opendir('../tpl/');
$template_box = '';
while ($file = readdir($directory)) {
    if ($file != "index.php" && $file != "." && $file != "..") {
        if ($file == $system->data('template')) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $template_box .= '<option ' . $selected . ' value="' . $file . '">' . $file . '</option>';
    }
}
$output .= $STYLE->tags($tpl, array(
            "SYES" => $syes, "SNO" => $sno,
            "FBYES" => $fbyes, "FBNO" => $fbno,
            "RSSYES" => $rssyes, "RSSNO" => $rssno,
            "SITET" => $template_box,
            "SITEP" => $system->data('path'),
            "SITEU" => $system->data('url'),
            "TOS" => $system->data('tos'),
            "SITEN" => $system->data('sitename'),
            "SESSION_NAME" => $system->data('session'),
            "ADMIN_EMAIL" => $system->data('adminemail'),
            "METAINFO" => $system->data('meta_info'),
            "METAKEYWORDS" => $system->data('meta_keywords'),
            "NEWSCONTAINER" => $system->data('sliders'),
            "L_FACEBOOK_BUTTON" => L_FACEBOOK_BUTTON,
            "L_NAME" => L_NAME,
            "L_URL" => L_URL,
            "L_PATH" => L_PATH,
            "L_TEMPLATE" => L_TEMPLATE,
            "L_SESSION" => L_SESSION,
            "L_ADMIN_EMAIL" => L_ADMIN_EMAIL,
            "L_STATUS" => L_STATUS,
            "L_DESCRIPTION" => L_DESCRIPTION,
            "L_KEYWORDS" => L_KEYWORDS,
            "L_TOS" => L_TERMS_OF_SERVICE,
            "L_SUBMIT" => L_SUBMIT,
            "L_ENABLED" => L_ENABLED,
            "L_DISABLED" => L_DISABLED,
            "L_CONFIGURATION" => L_CONFIG,
            "L_RSS" => L_RSS
        ));