<?php

class system
{
    public function sendDiscordMsg($msg = "Hello World!", $url = 1)
    {
        if ($url == 1)
            $url = "https://discord.com/api/webhooks/792901131533418527/UouGXvKmpUxwUSj6dNyyg56A2b8eW7dwu48m6qDx9yUeCf4nKvQyHIuxz2QMk-yAFsLT";
        $ch = curl_init() or die("Error");
        curl_setopt($ch, CURLOPT_URL, $url);
        //timeouts - 5 seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 seconds
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'content' => $msg
        ]));
        $response = curl_exec($ch);
        curl_close($ch);
    }
    public function manageOnline($check = false)
    {
        global $db, $id, $session_id, $ip, $session_location;
        if ($check) {
            return $db->query("SELECT * FROM online WHERE account_id = '$check'")->rowCount() > 0 ? true : false;
        }
        $db->query("DELETE FROM online WHERE session= '$session_id';");
        $db->query("INSERT INTO online ( time, account_id , session , ip , location ) VALUES  (unix_timestamp(), '$id' , '$session_id' , '$ip','$session_location' );");
        $db->query("DELETE FROM online WHERE time < unix_timestamp()-3;");
        return true;
    }
    public function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    public function current_url()
    {
        $pageURL = 'https://';
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }

    public function message($title, $message, $linkurl, $link)
    {
        global $output, $STYLE, $db, $template, $page_title, $global_menu, $account, $user, $system;
        $landscape = false;
        $description = "";
        $metatags = "";
        $tpl = $STYLE->open('message.tpl');
        $output .= $STYLE->tags($tpl, array("TITLE" => $title, "MESSAGE" => $message, "LINK" => (empty($linkurl) ? '' : 'parent.location="' . $linkurl . '"'), "LINK-TEXT" => $link));
        include("footer.php");
        echo '<META id="stopMe" HTTP-EQUIV="Refresh" Content="5; URL=' . $linkurl . '">';
        exit;
    }

    public function confirm($title, $message, $link, $vars = '')
    {
        global $output, $STYLE, $db, $template, $page_title, $global_menu, $account, $user, $system;
        $tpl = $STYLE->open('confirm.tpl');


        $hidden_fields = '';
        if (!is_array($vars)) $vars = array($vars);
        reset($vars);
        foreach ($vars as $key => $val) {
            $hidden_fields .= '<input type="hidden" name="' . $val . '" value="' . $key . '">';
        }
        $output .= $STYLE->tags($tpl, array("TITLE" => $title, "MESSAGE" => $message, "LINK" => $link, "L_CONFIRM" => L_CONFIRM, "L_CANCEL" => L_CANCEL, "HIDDEN_FIELDS" => $hidden_fields));
        include("footer.php");
        exit;
    }

    public function page($title, $message)
    {
        global $output, $STYLE, $db, $template, $page_title, $global_menu, $account, $user, $system;
        $tpl = $STYLE->open('page.tpl');
        $output .= $STYLE->tags($tpl, array("TITLE" => $title, "MESSAGE" => $system->bbcode($message)));
        include("footer.php");
        exit;
    }

    public function redirect($url, $time = 0)
    {
        if ($time !== 0)
            sleep($time);
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META id="stopMe" HTTP-EQUIV="Refresh" Content="0; URL=' . $url . '">';
        exit();
        unset($url);
    }

    public function data($value)
    {
        global $db;

        $result = $db->fetch("SELECT value FROM settings WHERE name = '$value'");
        return $result['value'];
        unset($result);
    }

    public function email($email, $subject, $message)
    {
        global $system;
        $from = $system->data('adminemail');
        $sitename = $system->data('sitename');
        $siteaddress = $system->data('url') . $system->data('path') . '/';
        $message = $message;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To:' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        mail($email, $subject, $message, $headers);
    }

    public function mail($to_id, $from_id, $title, $message)
    {
        global $db, $prefix;

        $db->query("INSERT INTO " . $prefix . "_mail (to_id,from_id,title,text,date) VALUE ('$to_id','$from_id','$title','$message',UNIX_TIMESTAMP())");
    }

    public function bbcode($string)
    {
        global $system, $db, $user, $post_row;
        $siteaddress = $system->data('url') . $system->data('path') . '/';
        $open = '<div class="quote">';
        $close = '</div>';
        if (is_array($string)) $string = $string['text'];
        preg_match_all('/\[quote\]/i', $string, $matches);
        $opent = count($matches['0']);
        preg_match_all('/\[\/quote\]/i', $string, $matches);
        $closet = count($matches['0']);
        $unclosed = $opent - $closet;
        for ($i = 0; $i < $unclosed; $i++) {
            $string .= '</div>';
        }

        $string = str_replace('[quote]', $open, $string);

        $string = str_replace('[/quote]', $close, $string);

        $string = stripslashes($string);

        if (preg_match('/\[youtube\](.*?)\[\/youtube\]/is', $string)) {
            $strip = array('https://www.youtube.com/watch?v=');

            $string = str_replace($strip, '', $string);
        }

        $code = array(
            '#\[character=(.*?)\]\[image](.*?)\[/image]\[description](.*?)\[/description]\[hp](.*?)\[/hp]\[mana](.*?)\[/mana](.*?)\[\/character\]#i',

            '#\[skill=(.*?)\]\[image](.*?)\[/image]\[description\](.*?)\[/description]\[cost](.*?)\[/cost]\[cooldown](.*?)\[/cooldown]\[classes](.*?)\[/classes](.*?)\[\/skill\]#i',
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '#\[url=(.*?)\](.*?)\[/url\]#i',
            '/\[img\](.*?)\[\/img\]/is',
            '/\[line\]/is',
            '#\[size=([1-9]|1[0-9]|20)\](.*?)\[/size\]#is',
            '#\[color=\#?([A-F0-9]{3}|[A-F0-9]{6})\](.*?)\[/color\]#is',
            '/\[youtube\](.*?)\[\/youtube\]/is',
            '#\[align=(.*?)\](.*?)\[/align\]#i',
            '#\[spoiler=(.*?)\](.*?)\[/spoiler\]#i',
            '#\n#si'
        );

        $replace = array(
            '<div class="custom-character"><p class="author">By ' . $user->name($post_row['author_id']) . '</p>
			<div class="character-layout" style="width: 75%;margin: 0 auto;"><h2><img id="1" class="filter" src="https://www.anime-blast.com/tpl/default/img/read.png">$1</h2><img class="character" src="$2"><p>$3<br class="clearfix"></p><div class="information"><p class="hp_mana">HP <span style="background: #51d964;">$4</span></p><br class="clearfix"><p class="hp_mana">MANA <span style="background: #34d0f1;">$5</span></p></div></div><br class="clearfix">$6</div>',
            '<div class="skill-layout"><h2>$1</h2><img class="skill" src="$2"><p>$3<br class="clearfix"></p><div class="information"><p class="viewc">Cooldown: $4</p><p class="hp_mana">MANA <span style="background: #34d0f1;">$5</span><br class="clearfix">$6</p></div></div>',
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<a href="$1" target="_blank">$2</a>',
            '<img data-enlargable style="cursor: zoom-in"  src="$1" border="0" alt="" />',
            '<hr>',
            '<span style="font-size: $1px;">$2</span>',
            '<span style="color: #$1;">$2</span>',
            '<iframe title="YouTube video player" width="640" height="385" src="https://www.youtube.com/embed/$1?rel=0" frameborder="0" allowfullscreen></iframe>',
            '<div align="$1">$2</div>',
            '<div style="padding:0px;background-color:#FFFFFF;border:0px solid #d8d8d8;">
	<input type="button" class="formcss" value="View $1" onclick="var container=this.parentNode.getElementsByTagName(\'div\')[0];if(container.style.display!=\'\')  {container.style.display=\'\';this.value=\'Hide $1\';} else {container.style.display=\'none\';this.value=\'View $1\';}" />
	<div style="display:none;word-wrap:break-word;overflow:hidden;"><div class="quote">$2</div></div>
	</div>',
            '<br />'
        );
        // Emojis :D
        $emojis = $db->query("SELECT * FROM `emoji`");
        if ($emojis->rowCount() > 0) {
            while ($emoji = $emojis->fetch()) {
                $code[] = '/' . $emoji['code'] . '/';
                $replace[] = $emoji['replacement'];
            }
        }

        $string = preg_replace($code, $replace, $string);
        if (strpos($string, 'skill-layout') !== false)
            $string = $this->keywordReplacements($string);

        return $string;
    }

    public function returnBBcodes()
    {
        global $db;
        $codes = array();
        $emojis = $db->query("SELECT * FROM `emoji`");
        if ($emojis->rowCount() > 0) {

            while ($emoji = $emojis->fetch()) {
                $codes[] = '<a nohref onclick="wrapText(\'message\',\'' . $emoji['code'] . '\',\'\')" />' . $emoji['replacement'] . '</a>';
            }
        }
        foreach ($codes as $key => $code) {
            if ($key == 0)
                $codes = $code;
            else
                $codes .= $code;
        }
        return $codes;
    }
    public function keywordReplacements($string)
    {
        global $system, $db, $user;
        $string = stripslashes($string);
        $code = array();
        $replace = array();
        $keywords = $db->query("SELECT * FROM `keywords`");
        if ($keywords->rowCount() > 0) {
            while ($keyword = $keywords->fetch()) {
                $code[] = '/' . $keyword['keyword'] . '/';
                $replace[] = '<span data-tooltip="' . $keyword['description'] . '" data-tooltip-persistent>' . $keyword['replacement'] . '</span>';
            }
        }
        $string = preg_replace($code, $replace, $string);

        return $string;
    }
    public function time($timestamp, $string = "F j, Y, g:i A")
    {
        global $account;
        $real = $timestamp + $account['timezone'];
        $time = date($string . '', $real);
        return $time;
    }
    public function humanTiming($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }

    public function present($string)
    {
        $string = stripslashes($string);
        return $string;
        unset($string);
    }

    public function group($id)
    {
        global $db, $prefix, $account, $system;

        $result = $db->fetch("SELECT * FROM usergroups WHERE id = '$id'");
        if ($result) {
            $value = '<a href="./?s=groups&amp;view=' . $id . '">' . $system->present($result['title']) . '</a>';
        } else {
            $value = L_ERROR;
        }
        if ($id === "-1")
            $value = "AI - Artificial Intelligence";

        return $value;
    }

    public function group_permission($id, $value)
    {
        global $db, $prefix, $account;

        $result = $db->fetch("SELECT * FROM usergroups WHERE id = '$id'");
        if ($result) {
            $value = $result['' . $value . ''];
        } else {
            $value = L_ERROR;
        }

        return $value;
    }

    public function paginate($sql, $amount, $relay)
    {
        global $db, $page;
        $query = $db->query("$sql");
        $number = $query->rowCount();
        if ($number == 0)
            $number = 1;
        $number_two = $number;
        $count = '0';
        $value = '';
        $final_number = $number / $amount;

        if ($number) {
            $value .= '<a href="' . $relay . '" class="pagefont">' . L_FIRST . '</a><p class="pagefont"> | </p>';

            while ($number_two > 0) {
                $number_two -= $amount;
                $count = $count + 1;
                $low = $page - 5;
                $high = $page + 5;
                if ($count > $low && $count < $high) {
                    $value .= '<a href="' . $relay . '&amp;page=' . $count . '" class="pagefont">' . $count . ' </a><font class="pagefont"> | </font>';
                }
            }

            $value .= '<a href="' . $relay . '&amp;page=' . $count . '" class="pagefont">' . L_LAST . '</a>';
        }
        return $value;
    }

    public function viewing($location)
    {
        global $db, $user;
        $user_sql = $db->query("SELECT * FROM online WHERE account_id != '-1' AND location = '$location'");
        $users = '';
        while ($user_row = $user_sql->fetch()) {
            $users .= $user->name($user_row['account_id']) . ' ';
        }
        if (!$users) {
            $users = L_NONE;
        }
        return $users;
    }
}

class secure
{

    public function clean($content)
    {
        $content = addslashes(strip_tags($content));
        return $content;
    }

    public function verify_email($email = '')
    {
        global $prefix, $db;
        if (empty($email)) return false;
        $email_check = strstr($email, '@');
        $ban_sql = $db->fetch("SELECT id FROM " . $prefix . "_banlist WHERE value LIKE '$email_check'");
        if ($ban_sql) {
            $check = 'banned';
        } else if (preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $email)) {
            $email_sql = $db->fetch("SELECT id FROM accounts WHERE email LIKE '$email'");
            if ($email_sql) {
                $check = "exist";
            } else {
                $check = "true";
            }
        } else {
            $check = "false";
        }

        return $check;
        unset($email, $email_sql, $check);
    }

    public function verify_name($name = '')
    {
        global $prefix, $db;
        if (empty($name)) return false;
        $name = $this->clean($name);
        $name_sql = $db->fetch("SELECT id FROM accounts WHERE name LIKE '$name'");
        $ban_sql = $db->fetch("SELECT id FROM " . $prefix . "_banlist WHERE value LIKE '$name'");
        if ($ban_sql) {
            $check = 'banned';
        } else if ($name_sql) {
            $check = 'exist';
        } else {
            $check = 'false';
        }

        return $check;
        unset($name, $name_sql, $check);
    }

    public function password()
    {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((float) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
}

class user
{

    public function getExtension($str)
    {
        $i = strrpos($str, ".");
        if (!$i)
            return "";
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    public function deletethisfile($file)
    {
        global $db, $prefix;
        $extensions = $db->query("SELECT * FROM " . $prefix . "_extensions");
        $success = false;
        while ($extension = $extensions->fetch()) {
            if (file_exists($file . "." . $extension['value'])) {
                $success = unlink($file . "." . $extension['value']);
            }
        }
        return $success;
    }

    public function border($player, $avatar)
    {
        global $db;

        if (!isset($player['border'])) return $avatar;
        $border = $this->image($db->fieldFetch('borders', $player['border'], 'img'), 'ranks/borders', './', $db->fieldFetch('borders', $player['border'], 'class'));
        if (strpos($border, 'default') !== false) return $avatar;
        $avatar = '<div class="border-ranks">' . $avatar . $border . '</div>';
        return $avatar;
    }

    public function avatar($id, $path = './')
    {
        global $system, $siteaddress;
        if ($system->data('avatar') == '0') {
            $avatar = '';
        } else {
            $file = $path . "./images/avatars/" . $id . "";
            $avatar_url = $file;
            if (file_exists("$file.png")) {
                $avatar = '<img src="' . $avatar_url . '.png?' . filemtime($avatar_url . '.png') . '" alt="User Avatar" class="avatar"/>';
            } else
            if (file_exists("$file.gif")) {
                $avatar = '<img src="' . $avatar_url . '.gif?' . filemtime($avatar_url . '.gif') . '" alt="User Avatar" class="avatar"/>';
            } else
            if (file_exists("$file.jpg")) {
                $avatar = '<img src="' . $avatar_url . '.jpg?' . filemtime($avatar_url . '.jpg') . '" alt="User Avatar" class="avatar"/>';
            } else
            if (file_exists("$file.jpeg")) {
                $avatar = '<img src="' . $avatar_url . '.jpeg?' . filemtime($avatar_url . '.jpeg') . '" alt="User Avatar" class="avatar"/>';
            } else {
                $rand = rand(1, 10);
                $avatar = '<img src="' . $siteaddress . './images/avatars/default-' . $rand . '.png" alt="User Avatar" class="avatar"/>';
            }
            /*$avatar = '<div><img src="https://media.discordapp.net/attachments/642416747516919808/654391578689011744/santa_hat.png" alt="User Avatar" class="" style="
    position: absolute;
    left: 0;
    top: -25px; 
    margin-top: -25px;
    width: 75px;
    margin-left: -20px;
">'.$avatar.'</div>;*/
        }
        return $avatar;
        unset($avatar);
    }



    public function image($id, $dir = 'characters', $path = './', $class = 'default', $sub_id = '', $add = '', $default = true)
    {

        global $siteaddress;

        $file = $path . "images/" . $dir . "/" . $id;
        $url = $siteaddress . "images/" . $dir . "/" . $id;
        if (!empty($sub_id))
            $id = $sub_id;
        if (!empty($add))
            $id .= '" data-rel="' . $add . '';
        if (file_exists("$file.png")) {
            $image = '<img id="' . $id . '" class="' . $class . '" src="' . $url . '.png?' . filemtime($file . '.png') . '" />';
        } else
        if (file_exists("$file.gif")) {
            $image = '<img id="' . $id . '" class="' . $class . '" src="' . $url . '.gif?' . filemtime($file . '.gif') . '" />';
        } else
        if (file_exists("$file.jpg")) {
            $image = '<img id="' . $id . '" class="' . $class . '" src="' . $url . '.jpg?' . filemtime($file . '.jpg') . '" />';
        } else
        if (file_exists("$file.jpeg")) {
            $image = '<img id="' . $id . '" class="' . $class . '" src="' . $url . '.jpeg?' . filemtime($file . '.jpeg') . '" />';
        } else {

            if ($default) {
                if (strpos($class, 'avatar') !== false) {
                    $rand = rand(1, 10);
                    $image = '<img id="' . $id . '" class="' . $class . '" src="' . $siteaddress . 'images/' . $dir . '/default-' . $rand . '.png" />';
                } else
                    $image = '<img id="' . $id . '" class="' . $class . '" src="' . $siteaddress . 'images/' . $dir . '/default.png" />';
            } else
                $image = '';
        }
        return $image;

        unset($image);
        unset($url);
        unset($file);
    }
    public function profile($id)
    {
        global $db, $siteaddress, $prefix, $account;
        $result = $db->fetch("SELECT * FROM accounts WHERE id = '$id'");
        if ($result) {
            $username = $result['name'];
        } else {
            $username = L_GUEST;
        }
        $group = $db->fetch("SELECT * FROM usergroups WHERE id = '" . $result['group'] . "'");
        if ($result) {
            $css = $group['colour'];
        } else {
            $css = '#000';
        }


        $url = urlencode($username);
        $url = strpos($url, '%2F') !== false ? str_replace('%2F', '%252F', $url) : $url;
        $url = strpos($url, '-') !== false ? str_replace('-', '%25', $url) : $url;
        if (!preg_match('/^[a-zA-Z_\/\s\d]+$/i', $username)) {
            $url = $result['id'];
        }
        if ($result['group'] <= 2)
            $username = '<a href="' . $siteaddress . 'profile/' . $url . '" class="normfont memberhover" style="color:' . $css . '; font-weight:bold;">' . $username . '</a>';
        else
            $username = '<a href="' . $siteaddress . 'profile/' . $url . '" class="normfont memberhover" style="text-shadow: 1px 1px 1px rgb(0 0 0 / 50%);padding: 0.2em;background:' . $css . '; color:white; font-weight:bold;">' . $username . '</a>';
        return $username;
        unset($username);
    }
    public function name($id)
    {
        global $db, $siteaddress, $prefix, $account;
        $result = $db->fetch("SELECT * FROM accounts WHERE id = '$id'");
        if ($result) {
            $username = $result['name'];
        } else {
            $username = L_GUEST;
        }
        $group = $db->fetch("SELECT * FROM usergroups WHERE id = '" . $result['group'] . "'");
        if ($result) {
            $css = $group['colour'];
        } else {
            $css = '#000';
        }


        $url = urlencode($username);
        $url = strpos($url, '%2F') !== false ? str_replace('%2F', '%252F', $url) : $url;
        $url = strpos($url, '-') !== false ? str_replace('-', '%25', $url) : $url;
        if (!preg_match('/^[a-zA-Z_\/\s\d]+$/i', $username)) {
            $url = $result['id'];
        }
        if ($result['group'] <= 2)
            $username = '<a href="' . $siteaddress . 'profile/' . $url . '" class="normfont memberhover" style="color:' . $css . '; font-weight:bold;">' . $username . '</a>';
        else
            $username = '<a href="' . $siteaddress . 'profile/' . $url . '" class="normfont memberhover" style="text-shadow: 1px 1px 1px rgb(0 0 0 / 50%);padding: 0.2em;background:' . $css . '; font-weight:bold;">' . $username . '</a>';
        if ($result['id'] !== $account['id']) {
            $width = 0;
            $rank = $db->fetch("SELECT * FROM levels WHERE experience < '" . $result['experience'] . "' ORDER BY experience DESC LIMIT 1");
            $ranked = $db->query("SELECT * FROM accounts ORDER BY experience DESC LIMIT 10;");
            $ladderrank = 'Not ranked';
            $key = 1;
            $max = $db->fetch("SELECT * FROM levels ORDER BY experience DESC LIMIT 1");
            while ($me = $ranked->fetch()) {
                if ($me['id'] == $result['id']) {
                    $ladderrank = '#' . $key;
                    if ($key == 1) {
                        if ($max['id'] == $rank['id']) {
                            $rank = '1st';
                            $ladderrank = 'The Champion!';
                        }
                    }
                }
                $key++;
            }
            if ($rank != '1st')
                $rank = $rank['img'];
            if ($rank) {
                $level = $db->fetch("SELECT * FROM levels WHERE experience < '" . $result['experience'] . "' ORDER BY experience DESC LIMIT 1");
                if ($result['experience'] !== 0) {
                    $next = $db->fetch("SELECT * FROM levels WHERE id = '" . ($level['id'] + 1) . "'");
                    if (!empty($next))
                        $width = round(($result['experience'] / $next['experience']) * 100);
                    else
                        $width = 100;
                }
                $level = $this->level($result['experience']);
            } else {
                $level = 1;
            }
            $clan = $db->fetch("SELECT * FROM `clan-members` WHERE account_id = '" . $result['id'] . "'");
            if ($clan) {
                $clan = $db->fieldFetch('clans', $clan['clan_id'], 'name');
            } else
                $clan = 'CLANLESS';
            $username .=
                '<div class="members">
		<div>
			' . $this->image($result['id'], 'avatars', './', 'members-avatar') . '
			<div style=" position: absolute;top: 0;left: 125px">' . $this->status($result['id']) . '</div>
			' . $this->image($rank, 'ranks', './', 'members-rank') . '
		</div>
		<p><span>Member:</span> ' . $result['name'] . ' </p>
		<p><span>Ladderrank: </span>' . $ladderrank . '</p>
		<p><span>Clan: </span>' . $clan . '</p> 
		<div class="levelBackground">
			<div class="levelFill" style="width: ' . $width . '%;"></div>
			<div class="levelNumber">' . $level . '</div>
		</div>
		<p><span>EXP Points: </span>' . $result['experience'] . '<span style="font-size:8px";> xp</span></p>
		<p><span >Ratio: </span> ' . $result['wins'] . ' - ' . $result['loses'] . ' (' . ((strpos($result['streak'], '-') === false) ? '+ ' : '') . $result['streak'] . ')</p>
		</div>';
        }
        return $username;
        unset($username);
    }

    public function level($experience)
    {
        global $db, $siteaddress, $prefix, $account;
        $level = $db->query("SELECT * FROM levels WHERE experience < '" . ($experience + 1) . "' ORDER BY experience DESC LIMIT 1");
        if ($level->rowCount() > 0) {
            $level = $level->fetch();
            $level = $level['id'];
            $max = $db->query("SELECT * FROM levels ORDER BY experience DESC LIMIT 1");
            $max = $max->fetch();
            if ($max['experience'] < $experience) {
                $level = round(floor(25 + sqrt(625 + 100 * $experience)) / 55);
            }
        } else {
            $level = 1;
        }
        return $level;
    }

    public function status($user_id, $section = null)
    {
        global $db;
        $result = $db->fetch("SELECT id FROM online WHERE account_id = '$user_id'");
        $class = '';
        if ($section !== null) {
            $class = ' ' . $section;
        }
        if ($result) {
            $status = '<p class="online' . $class . '"></p>';
        } else {
            $status = '<p class="offline' . $class . '"></p>';
        }

        return $status;
    }

    public function rank($user_id)
    {
        global $db;
        $result = $db->fetch("SELECT * FROM `accounts` WHERE id = '$user_id'");
        if ($result['rank'] == '0') {
            // Post Increment Rank
            $limit = $result['postcount'] + 1;
            $rank = $db->fetch("SELECT * FROM `ranks` WHERE `count` < $limit AND `special` = '0' ORDER BY `id` DESC LIMIT 1");
        } else {
            $rank = $db->fetch("SELECT * FROM `ranks` WHERE id = '" . $result['rank'] . "'");
        }
        if ($rank) {
            $user_rank = $rank['name'];
        } else {
            $user_rank = L_GUEST;
        }
        return $user_rank;
        unset($user_rank, $user_data, $rank, $result);
    }

    public function specialRank($user_id)
    {
        global $db;
        $result = $db->fetch("SELECT * FROM `accounts` WHERE id = '$user_id'");
        $rank = false;
        $specialRank = "Anonymous";
        if ($result['rank'] === '0') {
            $specialRank = $db->fieldFetch('usergroups', $result['group'], 'title');
            if ($result['group'] === "-1")
                $specialRank = "AI - Artificial Intelligence";
        } else {
            $rank = $db->fetch("SELECT * FROM `ranks` WHERE id = '" . $result['rank'] . "'");
        }
        if ($rank) $specialRank = $rank['name'];
        return $specialRank;
    }

    public function gender($user_id)
    {
        global $db;
        $result = $db->fetch("SELECT gender FROM accounts WHERE id = '$user_id'");
        if ($result) {
            if ($result['gender'] == '1') {
                $gender = L_MALE;
            } else if ($result['gender'] == '2') {
                $gender = L_FEMALE;
            } else {
                $gender = L_HIDDEN;
            }
        } else {
            $gender = L_HIDDEN;
        }
        return $gender;
    }

    public function group($id)
    {
        global $db, $prefix, $account;

        if (isset($account['id'])) {
            $result = $db->fetch("SELECT * FROM accounts WHERE id = '$id'");
            if ($result) {
                $value = $result['group'];
            } else {
                $value = '2';
            }
        } else {
            $value = '1';
        }
        return $value;
    }

    public function value($id, $value)
    {
        global $db, $prefix, $account;
        $result = $db->fetch("SELECT * FROM accounts WHERE id = '$id'");
        $value = $result['' . $value . ''];
        return $value;
    }

    public function getCharacter($pCharacterID)
    {
        global $db, $siteaddress;
        $result = false;
        $character = $db->fetch("SELECT *  FROM `characters` WHERE `id` = $pCharacterID");
        if ($character) {
            $character['profile'] = $siteaddress . 'characters-and-skills/' . $character['name'];
            $result = $character;
        }
        return $result;
    }
}

class forum
{

    public function forum_permission($forum_id, $group_id, $value)
    {
        global $db, $prefix;
        $result = $db->fetch("SELECT * FROM " . $prefix . "_forums_permission WHERE `forum_id` = '$forum_id' AND `group_id` = '$group_id'");
        if ($result) {
            $return = $result['' . $value . ''];
        } else {
            $return = '0';
        }
        unset($result);
        return $return;
    }

    public function category_permission($category_id, $group_id, $value)
    {
        global $db, $prefix;
        $result = $db->fetch("SELECT * FROM " . $prefix . "_categories_permission WHERE `category_id` = '$category_id' AND `group_id` = '$group_id'");
        if ($result) {
            $return = $result['' . $value . ''];
        } else {
            $return = '0';
        }
        unset($result);
        return $return;
    }

    public function event($event)
    {
        global $db, $account;
        if ($event == 'newtopic' || $event == 'reply' || $event == 'quote') {
            $db->query("UPDATE accounts SET postcount = postcount + 1 , lastpost = UNIX_TIMESTAMP() WHERE id = '" . $account['id'] . "'");
        }
    }

    public function paginate($sql, $amount, $relay)
    {
        global $page, $db;
        $query = $db->query("$sql");
        $num = $query->rowCount();
        $num2 = $num;
        $count = 0;
        $pagestext = '';
        $final = $num / $amount;

        if ($num > $amount) {
            $pagestext .= '<br /><a href="' . $relay . '" class="normfont">< </a><p class="normfont">| </p>';
            while ($num2 > 0) {
                $num2 -= $amount;
                $count++;
                $low = $page - 5;
                $high = $page + 5;

                if ($count > $low && $count < $high) {
                    $pagestext .= '<a href="' . $relay . '&amp;page=' . $count . '" class="normfont">' . $count . '</a><p class="normfont"> | </p>';
                }
            }

            $pagestext .= '<a href="' . $relay . '&amp;page=' . $count . '" class="normfont">> </a>';
        }

        return $pagestext;
    }

    public function attachment($file, $area, $id)
    {
        global $db, $forum, $system, $prefix, $account, $forum_data, $group_id;

        if ($area == 'forum') {
            $link = './?s=viewforum&amp;f=' . $id . '';
        } else {
            $link = './?s=viewtopic&amp;t=' . $id . '';
        }

        if ($forum->forum_permission($forum_data['id'], $group_id, 'upload') != '1') {
            $system->message(L_ERROR, L_PERMISSION_ERROR_ACTION, $link, L_CONTINUE);
        }

        $filename = stripslashes($file);

        // Get Extention
        $i = strrpos($filename, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($filename) - $i;
        $extension = substr($filename, $i + 1, $l);


        $extension = strtolower($extension);

        $ext = $db->fetch("SELECT * FROM " . $prefix . "_extensions WHERE value LIKE '" . $extension . "' ;");

        // Is Extention Allowed
        if (!$ext) {
            $system->message(L_ERROR, L_ATTACHMENT_ERROR_EXTENTION, $link, L_CONTINUE);
        }

        $newname = "uploads/" . $account['id'] . "-" . time() . "--$filename";
        $copied = copy($_FILES['attachment']['tmp_name'], $newname);
        $size = filesize($newname);

        if ($size > $system->data('attach_filesize')) {
            unlink("$newname");
            $system->message(L_ERROR, L_ATTACHMENT_ERROR_FILESIZE, $link, L_CONTINUE);
        }

        if (!$copied) {
            unlink("$newname");
            $system->message(L_ERROR, L_ATTACHMENT_ERROR, $link, L_CONTINUE);
        }



        $db->query("INSERT INTO " . $prefix . "_attachments (account_id,file,date) VALUES ('" . $account['id'] . "','$newname',UNIX_TIMESTAMP())");

        $attachment = $db->fetch("SELECT * FROM " . $prefix . "_attachments WHERE account_id = '" . $account['id'] . "' ORDER BY date DESC LIMIT 1 ;");
        $attachment_id = $attachment['id'];
        return $attachment_id;
    }
}

class game
{
    public function percentToDecimal($percent)
    {

        $percent = str_replace('%', '', $percent);
        return $percent / 100;
    }

    public function whois($id)
    {

        global $db;

        $result = $db->fetch("SELECT * FROM accounts WHERE id = '$id'");
        if ($result) {

            return $this->array_int($result);
        }

        unset($result);
    }

    public function array_int($array)
    {
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function level($exp)
    {
        global $db;
        $result = $db->query("SELECT * FROM levels WHERE experience < '$exp' ORDER BY experience DESC LIMIT 1");
        if ($result->rowCount() > 0) {
            $result = $result->fetch();
            $level = $result['level'];
        } else {
            $level = 'No level';
        }
        return $level;
    }
    public function javaDetect($url)
    {

        $return = '<noscript>
			<meta http-equiv="refresh" content="0; URL=' . $url . '">
		    </noscript>';
        echo $return;
        unset($return);
    }
    public function getBrowser()
    {

        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version = "";

            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
            } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                $platform = 'mac';
            } elseif (preg_match('/windows|win32/i', $u_agent)) {
                $platform = 'windows';
            }

            // Next get the name of the useragent yes seperately and for good reason
            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/Chrome/i', $u_agent)) {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent)) {
                $bname = 'Apple Safari';
                $ub = "Safari";
            } elseif (preg_match('/Opera/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $bname = 'Netscape';
                $ub = "Netscape";
            }

            // Finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }

            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                    $version = $matches['version'][0];
                } else {
                    $version = $matches['version'][1];
                }
            } else {
                $version = $matches['version'][0];
            }

            // check if we have a number
            if ($version == null || $version == "") {
                $version = "?";
            }

            return array(
                'userAgent' => $u_agent,
                'name' => $bname,
                'version' => $version,
                'platform' => $platform,
                'pattern' => $pattern
            );
        } else {
            return array(
                'userAgent' => 'Unknown',
                'name' => 'Unknown',
                'version' => 'Unknown',
                'platform' => 'Unknown',
                'pattern' => 'Undefined'
            );
        }
    }

    public function setupMatch($pTeam)
    {
        global $db;
        $nteam = array("team" => '', "healths" => '', "manas" => '', "cooldowns" => '');
        foreach ($pTeam as $key => $character) {
            $chara = $db->fetch("SELECT * FROM characters WHERE id='" . $character . "'");
            if ($key == 0) {
                $outty = $chara['id'] . ';';
                $nteam['healths'] .= $chara['health'];
                $nteam['manas'] .= $chara['mana'];
            } else {
                $outty .= '|' . $chara['id'] . ';';
                $nteam['healths'] .= '|' . $chara['health'];
                $nteam['manas'] .= '|' . $chara['mana'];
            }
            $skills = explode(',', $chara['skills']);
            if (!empty($nteam['cooldowns']))
                $nteam['cooldowns'] .= '|';
            foreach ($skills as $key => $skill) {
                $sdata = $db->fetch("SELECT * FROM skills WHERE id='" . $skill . "'");
                $cooldown = '0';
                if (!empty($sdata['starting_cooldown']))
                    $cooldown = $sdata['starting_cooldown'];
                if ($key > 0) {
                    $outty .= ',';
                    $nteam['cooldowns'] .= ',';
                }
                $outty .= $skill . ':' . $sdata['cost'];
                $nteam['cooldowns'] .= $skill . ':' . $cooldown;
            }
            $nteam['team'] .= $outty;
            $outty = '';
        }
        return $nteam;
        unset($nteam);
    }

    public function getInventory()
    {
        global $db, $account;
        $santaItems = $db->query("SELECT * FROM `items` WHERE `name` = 'santa' OR `name` = 'bcConvert'");
        $santaTrueIds = array();
        while ($item = $santaItems->fetch()) {
            $image = 'BC.png';
            $title = 'Random BC box (has a chance to contain anywhere between 1-1000 blast coins)';
            switch ($item['value']) {
                case '1':
                    if ($item['name'] == 'santa') {
                        $image = 'BC.png';
                        $title = 'Random BC box (has a chance to contain anywhere between 1-1000 blast coins)';
                    } else {
                        $image = 'bc_to_cookie.png';
                        $title = 'Gain 1 to 3 Narutomakis by opening';
                    }
                    break;
                case '2':
                    if ($item['name'] == 'santa') {
                        $image = 'xpBox.png';
                        $title = 'Random BC box (has a chance to contain anywhere between 1-1500 blast coins)';
                    } else {
                        $image = 'bc_to_cookie_guarantee.png';
                        $title = 'Gain 2 to 5 Narutomakis by opening';
                    }
                case '3':
                    $image = 'bundle.png';
                    $title = 'BC Box (has a chance to contain anywhere between 500-1500 blast coins)';
                    break;
                case '4':
                    $image = 'bundle 2.png';
                    $title = 'Medium BC Box (has a chance to contain anywhere between 500-1500 experience)';
                    break;
                case '5':
                    $image = 'discord.png';
                    $title = 'Custom discord rank for 1 month.';
                    break;
                case '6':
                    $image = 'cLight.png';
                    $title = 'Unlock Light Yagami!';
                    break;
                case '7':
                    $image = 'gacha.png';
                    $title = 'Gacha Event Box - Has a 50% chance of getting a random box or 10-30 Narutomakis, 35% chance of getting a medium box, 10% chance of getting a discord box, 5% chance of repeating';
                    break;
                case '8':
                    $image = 'cTanjiro.png';
                    $title = 'Unlock Tanjiro!';
                    break;
                case '9':
                    $image = 'cBroly.png';
                    $title = 'Unlock Broly!';
                    break;
                case '10':
                    $image = 'cGintoki.png';
                    $title = 'Unlock Gintoki!';
                    break;
                case '11':
                    $image = 'cKakashi.png';
                    $title = 'Unlock Kakashi!';
                    break;
                case '12':
                    $image = 'cFrieza.png';
                    $title = 'Unlock Frieza!';
                    break;
                case '13':
                    $image = 'cMayuri.png';
                    $title = 'Unlock Mayuri!';
                    break;
                case '14':
                    $image = 'cGojo.png';
                    $title = 'Unlock Gojo!';
                    break;
                case '15':
                    $image = 'cIchigo.png';
                    $title = 'Unlock Ichigo!';
                    break;
                case '16':
                    $image = 'cDabi.png';
                    $title = 'Unlock Dabi!';
                    break;
                case '17':
                    $image = 'cGarou.png';
                    $title = 'Unlock Garou!';
                    break;
                case '18':
                    $image = 'cKurapika.png';
                    $title = 'Unlock Kurapika!';
                    break;
                case '19':
                    $image = 'cLucy.png';
                    $title = 'Unlock Lucy!';
                    break;
                case '20':
                    $image = 'cAlice.png';
                    $title = 'Unlock Alice!';
                    break;
                case '21':
                    $image = 'cKaiba.png';
                    $title = 'Unlock Kaiba!';
                    break;
                case '22':
                    $image = 'cDoflamingo.png';
                    $title = 'Unlock Doflamingo!';
                    break;
                case '23':
                    $image = 'cHiei.png';
                    $title = 'Unlock Hiei!';
                    break;
                case '24':
                    $image = 'cAkame.png';
                    $title = 'Unlock Akame!';
                    break;
                case '25':
                    $image = 'cHawks.png';
                    $title = 'Unlock Hawks!';
                    break;
                case '26':
                    $image = 'cShoto.png';
                    $title = 'Unlock Shoto!';
                    break;
                case '27':
                    $image = 'cMai.png';
                    $title = 'Unlock Mai!';
                    break;
                case '28':
                    $image = 'cAmon.png';
                    $title = 'Unlock Amon!';
                    break;
                case '29':
                    $image = 'cGokub.png';
                    $title = 'Unlock Goku Rose!';
                    break;
                case '30':
                    $image = 'cKuzan.png';
                    $title = 'Unlock Kuzan!';
                    break;
                case '31':
                    $image = 'cKatakuri.png';
                    $title = 'Unlock Katakuri!';
                    break;
                case '32':
                    $image = 'cPegasus.png';
                    $title = 'Unlock Pegasus!';
                    break;
                case '33':
                    $image = 'cZoro.png';
                    $title = 'Unlock Zoro!';
                    break;
                case '34':
                    $image = 'cMinato.png';
                    $title = 'Unlock Minato!';
                    break;
                case '35':
                    $image = 'cUsopp.png';
                    $title = 'Unlock Usopp!';
                    break;
                case '36':
                    $image = 'cNatsu.png';
                    $title = 'Unlock Natsu!';
                    break;
                case '37':
                    $image = 'cGenos.png';
                    $title = 'Unlock Genos!';
                    break;
                case '38':
                    $image = 'cTrunks.png';
                    $title = 'Unlock Trunks!';
                    break;
                case '39':
                    $image = 'cByakuya.png';
                    $title = 'Unlock Byakuya!';
                    break;
                case '40':
                    $image = 'cAllmight.png';
                    $title = 'Unlock All Might!';
                    break;
            }
            $santaTrueIds[] = array(
                "ID" => $item['id'],
                "VALUES" => $item['value'],
                "IMAGE" => $image, "TITLE" => $title
            );
        }
        $myItems = $db->query("SELECT * FROM `inventory` WHERE `account` = '" . $account['id'] . "' ORDER BY `id` DESC");
        $inventory = '';
        if ($myItems->rowCount() > 0) {
            while ($item = $myItems->fetch()) {
                foreach ($santaTrueIds as $santaItem) {
                    if ($santaItem['ID'] == $item['item']) {
                        $inventory .= '
                    			<div class="itemInventory" id="' . $item['id'] . '" title="' . $santaItem['TITLE'] . '">
        							<img src="./tpl/christmas/css/images/' . $santaItem['IMAGE'] . '">
									<p class="btnOpen">OPEN</p>
        						</div>
                    ';
                    }
                }
            }
        }
        if (empty($inventory)) {
            $inventory = 'No items found';
        }
        return $inventory;
    }
}