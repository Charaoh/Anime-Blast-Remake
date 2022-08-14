<!-- BEGIN menu -->
<FORM action="" method="post" target="_self" style="padding: 0px; margin: 0px">
    <INPUT TYPE="submit" value="{L_WATCHING}" class="globaltab" name="{WATCHING}">
</FORM>
<!-- END menu -->

<!-- BEGIN normal -->
<!-- BEGIN poll -->
<div class="content my-1">
    <form action="" method="post" target="_self" style="padding: 0px; margin-bottom: 1em;"> {L_POLL}: {QUESTION}
        <div style="justify-content:center;>
            <div class=" normfont">
            <table>{OPTIONS}</table>
        </div>
    </form>
</div>

<!-- END poll -->

<!-- BEGIN moderator -->
<div class="content-m my-1">
    <form action="" method="post" target="_self" style="padding: 0px; margin: 0px">

        <table cellspacing="0" cellpadding="2">
            <tr>
                <!-- BEGIN moderator_lock -->
                <td>
                    <INPUT TYPE="submit" name="{LOCKED}" value="{L_LOCKED}" class="globaltab" style="width: 50px">
                </td>
                <!-- END moderator_lock -->
                <!-- BEGIN moderator_pool -->
                <td style="display: {POLL}">
                    <INPUT TYPE="submit" value="{L_DELETE_POLL}" class="globaltab" name="delete_poll"
                        style="width: 90px">
                </td>
                <!-- END moderator_lock -->
                <!-- BEGIN moderator_topic -->
                <td>
                    <INPUT TYPE="submit" value="{L_DELETE_TOPIC}" class="globaltab" name="delete_topic"
                        style="width: 90px">
                </td>
                <!-- END moderator_topic -->
                <td>
                    <input name="delete_posts" type="submit" value="{L_DELETE_SELECTED}" class="globaltab"
                        style="width: 100px">
                </td>
                <!-- BEGIN moderator_settings -->
                <td>
                    <div align="left">
                        <select size="1" name="sticky" class="globaltab" style="width: 100px">
                            <option value="0" {NORMAL}>{L_NORMAL}</option>
                            <option value="1" {STICKY}>{L_STICKY}</option>
                            <option value="2" {ANNOUNCEMENT}>{L_ANNOUNCEMENT}</option>
                        </select>
                    </div>
                </td>

                <td>
                    <div align="left">
                        <input type="submit" name="sticky_submit" value="{L_SET}" class="globaltab"
                            style="width: 50px" />
                    </div>
                </td>
                <td>
                    <div align="left">
                        <select size="1" name="move" class="globaltab" style="width: 100px">{MOVE}
                        </select>
                    </div>
                </td>
                <td>
                    <div align="left">
                        <input type="submit" name="move_submit" value="{L_MOVE}" class="globaltab"
                            style="width: 50px" />
                    </div>
                </td>
                <!-- END moderator_settings -->
            </tr>
        </table>
</div>
<!-- END moderator -->

<div id="characters">
    <div class="paginate my-1">{PAGINATE}</div>

    <div class="navigate title">
        <span>{TOPIC_NAME}</span>
    </div>
    <!-- BEGIN row -->
    <div id="{KEY}" class="topic-container">
        <div>
            <div class="posthead" class="title">{BOX}<b> {KEY}</b>
            </div>
        </div>
        <div>
            <div style="justify-content:left" class="title">
                <b>{DATE}</b>
            </div>
        </div>
    </div>
    <div class="postbody">
        <div class="authors">
            <div class="posters">{AUTHOR}</div>
            {AVATAR}
            <div>{RANK}</div>
            <div><strong>{L_POSTS}:</strong> <u>{POSTCOUNT}</u></div>
            <div><strong>{L_REPUTATION}:</strong> <u>{REPUTATION}</u></div>
        </div>

        <div class="postcontent">
            {TEXT}
            <br>
            <div class="forum-signature">
                {SIGNATURE}
            </div>
        </div>
    </div>
    <div class="cell1 my-1">
        <div style="height: auto; text-align:right;">
            <!-- BEGIN rep -->
            <a href="{URL}?s=viewtopic&amp;t={T}&amp;mode=reputation-add&amp;post_id={ID}" class="formcss2">+
                {L_REP}</a>
            <a href="{URL}?s=viewtopic&amp;t={T}&amp;mode=reputation-remove&amp;post_id={ID}" class="formcss2">-
                {L_REP}</a>
            <!-- END rep -->
            <!-- BEGIN logged_in -->
            <a href="{URL}profile/{AUTHOR_NAME}" class="formcss2">{L_PROFILE}</a>
            <a href="{URL}mail?mode=new&amp;user={AUTHOR_ID}" class="formcss2">{L_MAIL}</a>
            <a href="{URL}topic/{T}?mode=quote&amp;post_id={ID}" class="formcss2">{L_QUOTE}</a>
            <a href="{URL}topic/{T}?mode=report&amp;post_id={ID}" class="formcss2">{L_REPORT}</a>
            <!-- END logged_in -->
            <!-- BEGIN authorbutton -->
            <a href="{URL}topic/{T}?mode=edit&amp;post_id={ID}" class="formcss2">{L_EDIT}</a>
            <!-- END authorbutton -->
            <!-- BEGIN attachbutton -->
            <a href="{URL}./topic/{T}?mode=delete_attachment&amp;post_id={ID}"
                class="formcss2">{L_DELETE_ATTACHMENT}</a>
            <!-- END attachbutton -->
        </div>
    </div>
    <!-- END row -->

    <!-- BEGIN moderator -->
    </form>
    <!-- END moderator -->



    <div class="active-content">
        <div class="boxone" style="font-weight:bolder; color: #ffffff;">{L_VIEWING}</div>
        <div class="boxtwo" style="color: #2c2b2c;"></div>
        <div class="boxthree" style="color: #fff;">{USERS}</div>
    </div>
    <!-- END normal -->


    <!-- BEGIN reply -->

    <!-- END reply -->


    <!-- BEGIN edit -->
    <div class="content">
        <div class="navfont">{L_EDIT}</div>
    </div>
    <div class="content">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td>
                    <form action="" method="post" name="postreply">
                        <table width="100%" border="0" cellspacing="0" cellpadding="4">

                            <!-- BEGIN title -->
                            <tr>
                                <td width="100%" colspan="2">
                                    <div class="justify-content-left">
                                        <input name="title" type="text" class="formcss" size="40" maxlength="40"
                                            value="{TITLE}" />
                                    </div>
                                </td>
                            </tr>
                            <!-- END title -->
                            <tr>
                                <td colspan="2">
                                    <div class="justify-content-left">
                                        <input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')"
                                            class="formcss" />
                                        <input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')"
                                            class="formcss" />
                                        <input type="button" value="Underline"
                                            onclick="wrapText('message','[u]','[/u]')" class="formcss" />
                                        <input type="button" value="Size"
                                            onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
                                        <input type="button" value="Color"
                                            onclick="wrapText('message','[color=000000]','[/color]')" class="formcss" />
                                        <input type="button" value="Align"
                                            onclick="wrapText('message','[align=center]','[/align]')" class="formcss" />
                                        <input type="button" value="WWW"
                                            onclick="wrapText('message','[url=http://link here]','[/url]')"
                                            class="formcss" />
                                        <input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')"
                                            class="formcss" />
                                        <input type="button" value="Youtube"
                                            onclick="wrapText('message','[youtube]','[/youtube]')" class="formcss" />
                                        <input type="button" value="Spoiler"
                                            onclick="wrapText('message','[spoiler= button text]','[/spoiler]')"
                                            class="formcss" />
                                        <input type="button" value="Character"
                                            onclick="wrapText('message','[character=Default Name][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][hp]100[/hp][mana]100[/mana]','[/character]')"
                                            class="formcss" />
                                        <input type="button" value="Skill"
                                            onclick="wrapText('message','[skill=Default Skill][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][cost]None[/cost][cooldown]100[/cooldown][classes][/classes]','[/skill]')"
                                            class="formcss" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" colspan="2">
                                    <div class="justify-content-center">
                                        {BBCODES}</div>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="2">
                                    <div class="justify-content-left"><textarea name="message" id="message" rows="4"
                                            cols="40" style="width: 98%; height: 50"
                                            class="formcss">{MESSAGE}</textarea></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="justify-content-left">
                                        <table style="padding: 4px;">
                                            <tr>
                                                <td><input name="Submit" type="submit" value="{L_SUBMIT}"
                                                        class="formcss" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <!-- END edit -->

    <!-- BEGIN quote -->

    <!-- END quote -->

    <!-- BEGIN report -->

    <!-- END report -->
</div>
<script>
    function wrapText(elementID, openTag, closeTag) {
        var textArea = $('#' + elementID);
        var len = textArea.val().length;
        var start = textArea[0].selectionStart;
        var end = textArea[0].selectionEnd;
        var selectedText = textArea.val().substring(start, end);
        var replacement = openTag + selectedText + closeTag;
        textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
    }
</script>