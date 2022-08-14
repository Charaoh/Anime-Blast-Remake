<?php
$tpl = $STYLE->open('acp/characterlist.tpl');
$page_title .=  ' > Character Overview';

if (isset($_GET['module'])) {
    $mode = $secure->clean($_GET['module']);
} else {
    $mode = '';
}

if (isset($_GET['id'])) {
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
                        <td colspan="2" class="chardiv-name"<p style="font-weight:bolder;color:white;" class="cardiv-name">' . $character['name'] . '</p></td>
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
    $tpl = $STYLE->open('acp/characterlist.tpl');
    // $tpl = str_replace(array($STYLE->getcode('new-character', $tpl), $STYLE->getcode('edit-character', $tpl), $STYLE->getcode('change-avatar', $tpl), $STYLE->getcode('skill', $tpl)), '', $tpl);
    $tpl = $STYLE->tags($tpl, array("CHARACTERS" => $ingame));
}