<!-- BEGIN menu -->
<FORM style="padding: 0px; margin: 0px">
<INPUT TYPE="button" value ="{L_ACCOUNT}" class="globaltab" onClick="parent.location='./control-panel'">
<INPUT TYPE="button" value ="{L_SETTINGS}" class="globaltab" onClick="parent.location='./control-panel?mode=settings'">
<INPUT TYPE="button" value ="{L_AVATAR}" class="globaltab" onClick="parent.location='./control-panel?mode=avatar'">
<INPUT TYPE="button" value ="{L_SIGNATURE}" class="globaltab" onClick="parent.location='./control-panel?mode=signature'">
</FORM>
<!-- END menu -->
<!-- BEGIN account -->
<div class="transparent"><font class="navfont">{L_ACCOUNT}</font></div>
 <div class="content">
 <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <form action="" method="post" name="register" target="_self"><tr>
    <td width="15%"><div align="left"><font class="normfont">{L_EMAIL}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="email" type="text" class="formcss" value="{EMAIL}" size="40" maxlength="100" readonly="true" />
    </div></td>
  </tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="name" type="text" class="formcss" id="name" value="{NAME}" size="40" maxlength="40" />
    </div></td>
  </tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_PASSWORD}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="pass" type="password" class="formcss" size="40" maxlength="40" id="pass" autocomplete="off"/>
    </div></td>
  </tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_NEW_PASSWORD}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="newpass" type="password" class="formcss" size="40" maxlength="40" id="newpass" autocomplete="off"/>
    </div></td>
  </tr>
  <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_NEW_PASSWORD_CONFIRM}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="confirmnewpass" type="password" class="formcss" size="40" maxlength="40" id="confirmnewpass" autocomplete="off"/>
    </div></td>
  </tr>

  <tr>
    <td colspan="2"><div align="center"><input name="Submit" type="submit" class="formcss" value="{L_SUBMIT}"/></div></td>
    </tr></form>
</table></div>
<!-- END account -->

<!-- BEGIN settings -->

<div class="transparent"><font class="navfont">Game Settings</font></div>
<div class="content">
<h2 class="header"> AnimeBlast Player Card </h2>
<ul>
<li>Down below, click "UPDATE" to update your current playercard shown.
<ul>
<li>A full team must be equipped to display your characters.</li>
<li>If you wish to hide your equipped team, check the "HIDE TEAM" checkbox down below.</li>
</ul>
</li>
<li>
Down below is also a drop down menu in where you can select your preferred background. After selection, you can change to this background by clicking "UPDATE".</li>
<li>A bbcode is supplied to you so you are allowed to show off your achievements to friends here or in another game's forums.</li>
</p>
<br/>
{PLAYERCARD}
<br class="clearfix" />
<br/>
<center>
<form action="" method="post" name="playercard" target="_self">
Change Background: <select name="bg">
  <option value="1">Ink</option> 
  <option value="2">Space</option>
  <option value="3">Lightning</option>
  <option value="4">Grape</option> 
  <option value="5">Lime</option>
  <option value="6">Chaos</option>
</select>
<br/>
Hide team: <input style="text-transform:none;" name="hideteam" type="checkbox" class="globaltab" id="hideteam" />
<br/>
BBCode: <input style="text-transform:none;" name="playercard" type="input" class="globaltab" id="pc" value="{BBCODE}"/>
<button type="button" onclick="copyToClip()">Copy BBCode</button>
<br/>
<input name="UpdatePC" type="submit" class="globaltab" value="Update"/>
</center>

</form>
<script>
function copyToClip() {
  var copyText = document.getElementById("pc");
  copyText.select();
  copyText.setSelectionRange(0, 99999); 
  document.execCommand("copy");
}
</script>
</div><!--
<div class="content">
<h2 class="header"> Inventory<input type="button" value="Sell?" class="globaltab" onclick="parent.location='./?s=shop&mode=sell'" placeholder="" style="
    float: right;
"><input type="button" value="Buy" class="globaltab" onclick="parent.location='./?s=shop'" placeholder="" style="
    float: right;
    margin-right: 5px;
"></h2>

<ul class="inventory">	   
{INVENTORY}
</ul>
</div>-->
<div class="content">

<form action="" method="post" name="register" target="_self">
<p style="
    float: left;
    margin-right: 5px;
"> Reset account </p><span class="what" data-descr="By checking this setting you will reset your game wins, loses, experience, streak and perserve your blast coins, characters, mission progress and forum statistics.">?</span> <input name="reset" type="checkbox" class="formcss" id="reset" value="1"/>
</div>
<div class="transparent"><font class="navfont">Forum Settings</font></div>

 <div class="content">
 <table width="100%" border="0" cellspacing="0" cellpadding="4">
      
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_NOTIFY}:</font></div></td>
    <td width="85%"><div align="left">

<select id="emailme" name="emailme" class="formcss">
<option value="0"{NOTIFY_NO}>{L_DISABLED}</option>
<option value="1"{NOTIFY_YES}>{L_ENABLED}</option>






    </div></td>
  </tr>
  {SOUND}
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_TIMEZONE}:</font></div></td>
    <td width="85%"><div align="left">

<select id="timezone" name="timezone" class="formcss">
<option value="-43200"{a}>(GMT -12:00) Eniwetok, Kwajalein</option>
<option value="-39600"{b}>(GMT -11:00) Midway Island, Samoa</option>
<option value="-36000"{bb}>(GMT -10:00) Hawaii</option>
<option value="-32400"{c}>(GMT -9:00) Alaska</option>
<option value="-28800"{d}>(GMT -8:00) Pacific Time (US & Canada)</option>
<option value="-25200"{e}>(GMT -7:00) Mountain Time (US & Canada)</option>
<option value="-21600"{f}>(GMT -6:00) Central Time (US & Canada), Mexico City</option>
<option value="-18000"{g}>(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima</option>
<option value="-14000"{h}>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-12200"{i}>(GMT -3:30) Newfoundland</option>
<option value="-10400"{j}>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-7200"{k}>(GMT -2:00) Mid-Atlantic</option>
<option value="-3600"{l}>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
<option value="0"{m}>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
<option value="3600"{n}>(GMT +1:00 hour) Berlin, Copenhagen, Madrid, Paris</option>
<option value="7200"{o}>(GMT +2:00) Kaliningrad, South Africa</option>
<option value="10400"{p}>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
<option value="12200"{q}>(GMT +3:30) Tehran</option>
<option value="14000"{r}>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="16200"{rr}>(GMT +4:30) Kabul</option>
<option value="18000"{s}>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="19800"{ss}>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="20700"{sss}>(GMT +5:45) Kathmandu</option>
<option value="21600"{t}>(GMT +6:00) Almaty, Dhaka, Colombo</option>
<option value="25200"{u}>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
<option value="28800"{v}>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
<option value="32400"{w}>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="34200"{ww}>(GMT +9:30) Adelaide, Darwin</option>
<option value="36000"{www}>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
<option value="39600"{x}>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="43200"{y}>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option></select>




    </div></td>
  </tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_LOCATION}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="location" type="text" class="formcss" id="location" value="{LOCATION}" size="40" maxlength="80" />
    </div></td>
</tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_GENDER}:</font></div></td>
    <td width="85%"><div align="left">

<select id="gender" name="gender" class="formcss">
<option value="1"{MALE}>{L_MALE}</option>
<option value="2"{FEMALE}>{L_FEMALE}</option>
<option value="3"{HIDDEN}>{L_HIDDEN}</option>





    </div></td>
  </tr>

  <tr>
    <td colspan="2"><div align="center"><input name="Submit" type="submit" class="formcss" value="{L_SUBMIT}"/></div></td>
    </tr></form>
</table></div>


<!-- END settings -->

<!-- BEGIN avatar -->


<div class="transparent"><font class="navfont">{L_AVATAR}</font></div>
<div class="content">
<h2 class="header">Change your avatar</h2>
<p>Below you can change your avatar, the permited size at the moment is up to 100 x 100</p><br class="clearfix"/>
<div align="center">{AVATAR}</div>
<div align="center"><form name="newad" method="post" enctype="multipart/form-data"  action="">
 <table width="100%">
 	<tr>
 	  <td><div align="center">
 	  <input name="image" type="file" class="formcss"  size="50" maxlength="50" class="formcss">
 	  </div><div align="center">
 	  <input name="Avi" type="submit" class="formcss" value="{L_SUBMIT}"> <input name="Delete" type="submit" class="formcss" value="{L_DELETE}">
 	  </div></td></tr>

 </table>
 </form></div>

</div>
<!-- END avatar -->

<!-- BEGIN signature -->
<div class="transparent"><font class="navfont">{L_PREVIEW}</font></div>
<div class="content"><center><font class="normfont">{PREVIEW}</font></center></div>

<div class="transparent"><font class="navfont">{L_SIGNATURE}</font></div>
<div class="content"><font class="navfont"> <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <form action="" method="post" name="post" target="_self">
 <tr>

    <td colspan="2"><div align="left">

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

    </div></td>

    </tr>

   <tr>
    <td><div align="center"> <textarea rows="2" name="signature" id="message" cols="20" style="width: 98%; height: 100" class="formcss" >{SIGNATURE}</textarea></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><input name="Submit" type="submit" class="formcss" value="{L_SUBMIT}"/></div></td>
    </tr></form>
</table></font></div>
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


<!-- END signature -->





