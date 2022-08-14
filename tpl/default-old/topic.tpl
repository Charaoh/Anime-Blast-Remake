<!-- BEGIN menu -->
<FORM action="" method="post" target="_self" style="padding: 0px; margin: 0px">
<INPUT TYPE="submit" value ="{L_WATCHING}" class="globaltab" name="{WATCHING}">
</FORM>

<!-- END menu -->
<!-- BEGIN normal -->
<!-- BEGIN poll -->

<div class="content"><form action="" method="post" target="_self" style="padding: 0px; margin: 0px"> {L_POLL}: {QUESTION}
<center><font class="normfont"><table>{OPTIONS}</table></font></center></form>
</div>

<!-- END poll -->
<!-- BEGIN moderator -->
<div class="content">
<form action="" method="post" target="_self" style="padding: 0px; margin: 0px">

<table border="0" cellspacing="0" cellpadding="2">
<tr>
<!-- BEGIN moderator_lock -->
		<td>
			<INPUT TYPE="submit" name = "{LOCKED}" value ="{L_LOCKED}" class="globaltab" style="width: 50px">
		</td>
<!-- END moderator_lock -->
<!-- BEGIN moderator_pool -->
	<td style="display: {POLL}">
		<INPUT TYPE="submit" value ="{L_DELETE_POLL}" class="globaltab" name="delete_poll"  style="width: 90px">
	</td>
<!-- END moderator_lock -->
<!-- BEGIN moderator_topic -->
	<td>
		<INPUT TYPE="submit" value ="{L_DELETE_TOPIC}" class="globaltab" name="delete_topic"  style="width: 90px">
	</td>
<!-- END moderator_topic -->
	<td>
		<input name="delete_posts" type="submit" value="{L_DELETE_SELECTED}" class="globaltab"style="width: 100px">
	</td>
	<!-- BEGIN moderator_settings -->
	<td>
		<div align="left">
			<select size="1" name="sticky" class="globaltab"  style="width: 100px">
				<option value="0" {NORMAL}>{L_NORMAL}</option> 
				<option value="1" {STICKY}>{L_STICKY}</option> 
				<option value="2" {ANNOUNCEMENT}>{L_ANNOUNCEMENT}</option>           
			</select>
		</div>
	</td>
	
	<td>
		<div align="left">
			<input type="submit" name="sticky_submit" value="{L_SET}" class="globaltab"  style="width: 50px"/>
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
			<input type="submit" name="move_submit" value="{L_MOVE}" class="globaltab"  style="width: 50px"/>
		</div>
	</td>
	<!-- END moderator_settings -->
</tr>
</table>
</div>


<!-- END moderator -->
<div class="content">
<div class="pages">{PAGINATE}</div>
</div>
<div class="content">
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="boxone">
	<td colspan="5">{TOPIC_NAME}</td>
</tr>
<!-- BEGIN row -->
<tr id="{KEY}" class="content">
<td width="20%">
	<div align="left" class="navfont">{BOX}<b> {KEY}</b>
	</div>
</td>
<td width="80%">
	<div align="left" class="navfont" style="text-align: right;border: none;"><b>{DATE}</b>
	</div>
</td>
</tr>
<tr class="cell0">
	<td valign="top" style="border:1px solid grey;">
		<div align="center" class="normfont">{AUTHOR}<br />{RANK}<br />{AVATAR}{STATUS}<br />
			<strong>{L_POSTS}:</strong> {POSTCOUNT}<br />
			<strong>{L_REPUTATION}:</strong> {REPUTATION}
		</div>
	</td>
	<td valign="top" style="border:1px solid grey;">
	<font class="normfont">
		{TEXT}
	</font><br><br>
		<div class="forum-signature">
		{SIGNATURE}
		</div>
	</td>
</tr>
<tr class="cell1">
	<td colspan="2">
	<div style="height: 25px; text-align:right;">
	<!-- BEGIN rep -->
	<a href="{URL}?s=viewtopic&amp;t={T}&amp;mode=reputation-add&amp;post_id={ID}" class="formcss">+ {L_REP}</a> 
	<a href="{URL}?s=viewtopic&amp;t={T}&amp;mode=reputation-remove&amp;post_id={ID}" class="formcss">- {L_REP}</a> 
	<!-- END rep -->
	<!-- BEGIN logged_in -->
	<a href="{URL}profile/{AUTHOR_NAME}" class="formcss">{L_PROFILE}</a> 
	<a href="{URL}mail?mode=new&amp;user={AUTHOR_ID}" class="formcss">{L_MAIL}</a> 
	<a href="{URL}topic/{T}?mode=quote&amp;post_id={ID}" class="formcss">{L_QUOTE}</a> 
	<a href="{URL}topic/{T}?mode=report&amp;post_id={ID}" class="formcss">{L_REPORT}</a> 
	<!-- END logged_in -->
	<!-- BEGIN authorbutton -->
	<a href="{URL}topic/{T}?mode=edit&amp;post_id={ID}" class="formcss">{L_EDIT}</a> 
	<!-- END authorbutton -->
	<!-- BEGIN attachbutton -->
	<a href="{URL}./topic/{T}?mode=delete_attachment&amp;post_id={ID}" class="formcss">{L_DELETE_ATTACHMENT}</a> 
	<!-- END attachbutton -->
	</div>
	</td>
</tr>
<!-- END row -->
</table>
<!-- BEGIN moderator -->
</form>
<!-- END moderator -->
</div>
<div class="content">
<div class="pages">{PAGINATE}</div>
</div>
<div class="content">
	<div class="boxone">{L_VIEWING}</div>
	<div class="boxtwo"></div>
	<div class="boxthree">{USERS}</div>
</div>
<!-- END normal -->
<!-- BEGIN reply -->


<div class="transparent"><font class="navfont">{L_REPLY}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<form action="" method="post" name="postreply" enctype="multipart/form-data">
<tr><td colspan="2">
<div align="left">
<input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')" class="formcss" />
<input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')" class="formcss" />
<input type="button" value="Underline" onclick="wrapText('message','[u]','[/u]')"  class="formcss"/>
<input type="button" value="Size" onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
<input type="button" value="Color" onclick="wrapText('message','[color=000000]','[/color]')" class="formcss" />
<input type="button" value="Align" onclick="wrapText('message','[align=center]','[/align]')" class="formcss" />
<input type="button" value="WWW" onclick="wrapText('message','[url=http://link here]','[/url]')" class="formcss" />
<input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')" class="formcss" />
<input type="button" value="Youtube" onclick="wrapText('message','[youtube]','[/youtube]')" class="formcss" />
<input type="button" value="Spoiler" onclick="wrapText('message','[spoiler= button text]','[/spoiler]')" class="formcss" />
<input type="button" value="Character" onclick="wrapText('message','[character=Default Name][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][hp]100[/hp][mana]100[/mana]','[/character]')" class="formcss" />
<input type="button" value="Skill" onclick="wrapText('message','[skill=Default Skill][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][cost]None[/cost][cooldown]100[/cooldown][classes][/classes]','[/skill]')" class="formcss" /></div></td></tr>
</div>
</td>
</tr>
<tr><td colspan="2"><div align="center">
{BBCODES}
</div></td></tr>
<tr><td width="100%"><div align="center">
<textarea id="message" name="message" rows="4" cols="40" style="width: 98%; height: 50" class="formcss"></textarea>
</div></td>
</tr><tr><td colspan="2">
<div align="center" style="display: {ATTACHMENT}">
<input name="attachment" type="file" class="formcss"  size="50" maxlength="50">
</div>
</td>
</tr>
<tr>
	<td>
	<div align="center">
	<input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss" />
	</div>
	</td>
</tr>
</form> 
</table>
</div>
<div class="content">
<div class="boxone"><font class="navfont">{L_TOPIC}</font></div>
<div class="boxtwo"></div>
<div style="height: 100px;
width: 100%;
overflow: auto;
border: 0px;">
<!-- BEGIN reply_row -->
<div class="cell{CLASS}">
<font class="normfont">{AUTHOR} - {DATE}
<br />{TEXT}
</font>
</div>
<!-- END reply_row -->

</div>
</div>
<!-- END reply -->
<!-- BEGIN edit -->
<div class="content"><font class="navfont">{L_EDIT}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>
<form action="" method="post" name="postreply"><table width="100%" border="0" cellspacing="0" cellpadding="4">

<!-- BEGIN title --><tr>
<td width="100%" colspan="2"><div align="left">
<input name="title" type="text" class="formcss" size="40" maxlength="40" value="{TITLE}"/>
</div></td>
</tr>
<!-- END title -->
<tr><td colspan="2"><div align="left">
<input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')" class="formcss" />
<input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')" class="formcss" />
<input type="button" value="Underline" onclick="wrapText('message','[u]','[/u]')"  class="formcss"/>
<input type="button" value="Size" onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
<input type="button" value="Color" onclick="wrapText('message','[color=000000]','[/color]')" class="formcss" />
<input type="button" value="Align" onclick="wrapText('message','[align=center]','[/align]')" class="formcss" />
<input type="button" value="WWW" onclick="wrapText('message','[url=http://link here]','[/url]')" class="formcss" />
<input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')" class="formcss" />
<input type="button" value="Youtube" onclick="wrapText('message','[youtube]','[/youtube]')" class="formcss" />
<input type="button" value="Spoiler" onclick="wrapText('message','[spoiler= button text]','[/spoiler]')" class="formcss" />
<input type="button" value="Character" onclick="wrapText('message','[character=Default Name][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][hp]100[/hp][mana]100[/mana]','[/character]')" class="formcss" />
<input type="button" value="Skill" onclick="wrapText('message','[skill=Default Skill][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][cost]None[/cost][cooldown]100[/cooldown][classes][/classes]','[/skill]')" class="formcss" /></div></td></tr>
<tr><td colspan="2" colspan="2">
<div align="center">
{BBCODES}</div></td></tr>
<tr><td width="100%" colspan="2"><div align=center"><textarea name="message" id="message" rows="4" cols="40" style="width: 98%; height: 50" class="formcss">{MESSAGE}</textarea></div></td></tr>
<tr><td colspan="2"><div align="center"><table style="padding: 4px;"><tr><td><input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss"/></td></tr></table></div></td>
</tr></table></form></td></tr>
</table></div>
<!-- END edit -->
<!-- BEGIN quote -->
<div class="content"><font class="navfont">{L_QUOTE}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>
<form action="" method="post" name="postreply" enctype="multipart/form-data"><table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr><td colspan="2"><div align="left">
<input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')" class="formcss" />
<input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')" class="formcss" />
<input type="button" value="Underline" onclick="wrapText('message','[u]','[/u]')"  class="formcss"/>
<input type="button" value="Size" onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
<input type="button" value="Color" onclick="wrapText('message','[color=000000]','[/color]')" class="formcss" />
<input type="button" value="Align" onclick="wrapText('message','[align=center]','[/align]')" class="formcss" />
<input type="button" value="WWW" onclick="wrapText('message','[url=http://link here]','[/url]')" class="formcss" />
<input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')" class="formcss" />
<input type="button" value="Youtube" onclick="wrapText('message','[youtube]','[/youtube]')" class="formcss" />
<input type="button" value="Spoiler" onclick="wrapText('message','[spoiler= button text]','[/spoiler]')" class="formcss" />
<input type="button" value="Character" onclick="wrapText('message','[character=Default Name][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][hp]100[/hp][mana]100[/mana]','[/character]')" class="formcss" />
<input type="button" value="Skill" onclick="wrapText('message','[skill=Default Skill][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][cost]None[/cost][cooldown]100[/cooldown][classes][/classes]','[/skill]')" class="formcss" /></div></td></tr>
<tr><td colspan="2"><div align="center">
{BBCODES}
</div></td></tr>
<tr><td width="100%"><div align=center">
<textarea name="message" id="message" rows="4" cols="40" style="width: 98%; height: 50" class="formcss">{MESSAGE}</textarea>
</div></td></tr><tr><td colspan="2"><div align="center"  style="display: {ATTACHMENT}"><input name="attachment" type="file" class="formcss" size="50" maxlength="50"></div></td></tr><tr>
<td><div align="center"><input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss"/></div></td>
</tr></table>
</form></td></tr></table></div>
<!-- END quote -->
<!-- BEGIN report -->
<div class="content"><font class="navfont">{L_REPORT}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="2" cellpadding="4"><form action="" method="post" name="post" target="_self"><tr>
<td colspan="2"><div align="center">
<textarea name="reason" id="reason" rows="10" cols="40" style="width: 98%; height: 50" class="formcss"></textarea>
</div></td></tr><tr>
<td colspan="2"><div align="center"><input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss" /></div></td></tr>
</form></table></div>
<!-- END report -->
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
