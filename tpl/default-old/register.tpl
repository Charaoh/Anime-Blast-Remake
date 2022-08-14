
<form action="" method="post" name="register" target="_self">
<div class="boxone"><font class="navfont">{L_ACC_DETAILS}</font></div>
<div class="boxtwo"></div>
<div class="boxthree">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="regname" type="text" class="formcss" size="40" maxlength="40" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">{L_EMAIL}:</font></div></td>
    <td><div align="left">
      <input name="regemail" type="text" class="formcss" size="40" maxlength="100" />
    </div></td>
  </tr>
  <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_PASSWORD}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="password" type="password" class="formcss" size="40" maxlength="40" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_CONFIRM_PASSWORD}:</font></div></td>
    <td width="85%"><div align="left">
     <input name="confirm_password" type="password" class="formcss" size="40" maxlength="40" /></div></td>
  </tr>


</table>



</div>



<div class="boxone"><font class="navfont">{L_PROFILE_DETAILS}</font></div>
<div class="boxtwo"></div>
<div class="boxthree">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
 <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_TIMEZONE}:</font></div></td>
    <td width="85%"><div align="left">

<select id="timezone" name="timezone" class="formcss"> 
<option value="-43200">(GMT -12:00) Eniwetok, Kwajalein</option> 
<option value="-39600">(GMT -11:00) Midway Island, Samoa</option> 
<option value="-36000">(GMT -10:00) Hawaii</option> 
<option value="-32400">(GMT -9:00) Alaska</option> 
<option value="-28800">(GMT -8:00) Pacific Time (US & Canada)</option> 
<option value="-25200">(GMT -7:00) Mountain Time (US & Canada)</option> 
<option value="-21600">(GMT -6:00) Central Time (US & Canada), Mexico City</option> 
<option value="-18000">(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima</option> 
<option value="-14000">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option> 
<option value="-12200">(GMT -3:30) Newfoundland</option> 
<option value="-10400">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option> 
<option value="-7200">(GMT -2:00) Mid-Atlantic</option> 
<option value="-3600">(GMT -1:00 hour) Azores, Cape Verde Islands</option> 
<option value="0" selected>(GMT) Western Europe Time, London, Lisbon, Casablanca</option> 
<option value="3600">(GMT +1:00 hour) Berlin, Copenhagen, Madrid, Paris</option> 
<option value="7200">(GMT +2:00) Kaliningrad, South Africa</option> 
<option value="10400">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option> 
<option value="12200">(GMT +3:30) Tehran</option> 
<option value="14000">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option> 
<option value="16200">(GMT +4:30) Kabul</option>
<option value="18000">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="19800">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="20700">(GMT +5:45) Kathmandu</option>
<option value="21600">(GMT +6:00) Almaty, Dhaka, Colombo</option> 
<option value="25200">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
<option value="28800">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
<option value="32400">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="34200">(GMT +9:30) Adelaide, Darwin</option> 
<option value="36000">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option> 
<option value="39600">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="43200">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option></select> 




    </div></td>
  </tr>
<tr>
    <td width="15%"><div align="left"><font class="normfont">{L_LOCATION}:</font></div></td>
    <td width="85%"><div align="left">
      <input name="location" type="text" class="formcss" id="location" value="" size="40" maxlength="80" />
    </div></td>
</tr>
   <tr>
    <td width="15%"><div align="left"><font class="normfont">{L_GENDER}:</font></div></td>
    <td width="85%"><div align="left">

<select id="gender" name="gender" class="formcss"> 
<option value="1">{L_MALE}</option> 
<option value="2">{L_FEMALE}</option> 
<option value="3">{L_HIDDEN}</option> 





    </div></td>
  </tr>
</table>

</div>
<div class="boxtwo"></div>
<div align="center">
<div class="boxtwo" style="width: 300px; padding: 4px; margin: 4px;">
<font class="navfont">{L_CAPTCHA}</font><div class="boxthree">
<table>
<tr>
<td>
<img src="{URL}inc/captcha.php" border="0"></td><td><input name="captcha" type="text" class="formcss" size="25" maxlength="25" /></td>
</tr>
</table>
</div>



</div></div>

<div class="boxone"><font class="navfont">{L_AGREEMENT}</font></div>
<div class="boxtwo"></div>
<div class="boxthree">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
    <td colspan="2"><div align="center"> <textarea disabled rows="5" name="tos" id="message" cols="20" style="width: 90%; height: 100" class="formcss" >{TOS}</textarea></div></td>
  </tr> 

<tr>
    <td colspan="2"><div class="boxthree"><font class="normfont">{L_AGREEMENT_STATEMENT}</font></div></td>
    </tr> 
<tr>
    <td colspan="2"><div align="center"><input name="Submit" type="submit" class="formcss" value="{L_SUBMIT}"/></div></td>
    </tr>   
</table>

</div>



</form>
    

