<!-- BEGIN menu -->
<FORM style="padding: 0px; margin: 0px">
<INPUT TYPE="button" value ="{L_NEW}" class="globaltab" onClick="parent.location='./forum/{FORUM_ID}?mode=new'">
</FORM>
<!-- END menu -->
<!-- BEGIN subforum -->
<div class="content">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr class="boxone"><td colspan="4">{L_SUBFORUM}</td></tr>
<tr class="boxtwo"><td width="1%">&nbsp; </td><td width = "45%"><div align="left" class="navfont">{L_FORUM}</div></td>
<td width="10%"><div align="center" class="navfont">{L_TOPICS}</div></td><td width="25%"><div align="center" class="navfont">{L_LATEST}</div></td></tr>
<!-- BEGIN subrow -->
<tr class="row{CLASS}">
<td></td>
<td><div align="left"><img src="./tpl/default/img/{ICON}.png" class="me" alt=""><font class="normfont">{FORUM}<br />{INFO}<br />{MODERATORS}</font></div></td>
<td><div align="center"><font class="normfont">{TOPIC_COUNT}</font></div></td>
<td><div align="center"><font class="normfont">{TOPIC}</font></div></td>
</tr>
<!-- END subrow -->
</table></div>
<!-- END subforum -->
<!-- BEGIN normal -->
<div class="content"><div class="pages">{PAGINATE}</div></div>
<div class="content">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr class="boxone"><td colspan="5">{FORUM_NAME}</td></tr><tr class="boxtwo">
<td width="1%"></td>
<td width="49%"><div align="center" class="navfont"><b>{L_TOPIC}</b></div></td>
<td width="20%"><div align="center" class="navfont"><b>{L_AUTHOR}</b></div></td>
<td width="5%"><div align="center" class="navfont"><b>{L_REPLIES}</b></div></td>
<td width="40%"><div align="center" class="navfont"><b>{L_LATEST}</b></div></td></tr>
<!-- BEGIN row -->
<tr class="row{CLASS}">
<td></td>
<td><div align="left" class="normfont"><img src="./tpl/default/img/{ICON}.png" class="me" alt=""/>{TOPIC} {PAGES} </div></td>
<td><div align="center" class="normfont">{AUTHOR}</div></td>
<td><div align="center" class="normfont">{REPLIES}</div></td>
<td><div align="center" class="normfont">{USER}<br /> {DATE}</div></td>
</tr>
<!-- END row -->
</table>
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

<!-- BEGIN newtopic -->

<div class="content">
<font class="navfont">{L_NEW_TOPIC}</font>
</div>
<div class="content">
<form action="" method="post" name="post" target="_self" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
<td width="11%">
<div align="left"><font class="normfont">{L_TITLE}:</font></div>
</td>
<td width="89%">
<div align="left">
<input name="title" type="text" class="formcss" size="40" maxlength="40" />
</div>
</td>
</tr>
<tr>
<td colspan="2">
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
<input type="button" value="Skill" onclick="wrapText('message','[skill=Default Skill][image]https://www.anime-blast.com/images/characters/default.png[/image][description]Default description[/description][cost]None[/cost][cooldown]100[/cooldown][classes][/classes]','[/skill]')" class="formcss" />
</div>
</td>
</tr>
<tr><td colspan="2">
<div align="center">
{BBCODES}
</div></td></tr>
<tr><td colspan="2">
<div align="center">
<textarea name="message" id="message" rows="10" cols="40" style="width: 98%; height: 50" class="formcss"></textarea>
</div></td></tr>
</table></div>

<div class="content" style="display: {ATTACHMENT}">
<div class="boxone">
<font class="navfont">{L_ATTACHMENT}</font>
</div>
<div style="padding:6px">
<input name="attachment" type="file" class="globaltab"  size="50" maxlength="50">
</div>
</div>
<div class="content" style="display: {POLL}">
<div class="boxone">
<font class="navfont">{L_POLL}</font>
</div>
<font class="normfont">
<table>
<tr><td>{L_QUESTION}:</td><td><input name="question" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
<tr><td>{L_OPTION} 1 :</td><td><input name="option1" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
<tr><td>{L_OPTION} 2 :</td><td><input name="option2" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
<tr><td>{L_OPTION} 3 :</td><td><input name="option3" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
<tr><td>{L_OPTION} 4 :</td><td><input name="option4" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
<tr><td>{L_OPTION} 5 :</td><td><input name="option5" type="text" class="formcss" size="40" maxlength="40" /></td></tr>
</table>
</font>
</div>

<div class="content">
<div align="center"><input name="Submit" type="submit" value="{L_SUBMIT}" class="globaltab" /></div>
</div>


</form>


<!-- END newtopic -->
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

   