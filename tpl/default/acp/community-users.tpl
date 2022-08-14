<!-- BEGIN edit -->

<div class="transparent"><font class="navfont">{L_EDIT_ACCOUNT}</font></div>
<div class="content">
<form action="" method="post" target="_self">
<table width="100%">
<tr>
<td>
<center>
{AVI}<br /><input name="avatar-delete" type="submit" class="globaltab" value="{L_DELETE}"/>
</center>
</td>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
    <td width="10%"><div align="left"><font class="normfont">{L_EMAIL}:</font></div></td>
    <td width="40%"><div align="left">
      <input name="email" type="text" class="globaltab" value="{EMAIL}" size="30" maxlength="100" />
    </div>
	</td>
	<td width="10%"><div align="left"><font class="normfont">{L_PASSWORD}:</font></div></td>
    <td width="40%">
	<div align="left">
      <input name="password" type="password" class="globaltab" size="30" maxlength="40" />
	</div>
	</td>
  </tr>
  <tr>
  <td><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td><div align="left">
      <input name="name" type="text" class="globaltab" value="{NAME}" size="30" maxlength="40" />
    </div>
	</td>
    <td><div align="left"><font class="normfont">{L_IP}:</font></div></td>
    <td><div align="left">
  <input name="ip" type="text" class="globaltab" value="{IP}" size="30" maxlength="40" readonly="readonly" />
    </div>
	</td>
	</tr>
    <tr>
	<td><div align="left"><font class="normfont">{L_STATUS}:</font></div></td>
    <td><div align="left"><font class="normfont">
      <select size="1" name="status" class="globaltab" >
        <option {ACTIVE} value="0">{L_ENABLED}</option>
        <option {FROZEN} value="1">{L_DISABLED}</option>
      </select>
    </font></div>
	</td> 

	<td><div align="left"><font class="normfont">{L_RANK}:</font></div></td>
    <td><div align="left"><font class="normfont">
       <select name="rank" class="globaltab">
        
        {RANKS}
      
      </select>
    </font></div></td>  
	</tr>
	</table>
   </td>
  </tr>
</table>

 </td>
  </tr>
</table>
</div>
<div class="transparent"><font class="navfont">Game Settings</font></div>
<div class="content">
<h3 class="header">Game Statistics</h3>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
<td><div align="left" class="navfont">Blast Coins</div></td>
<td><div align="left" class="navfont">Experience</div></td>
<td><div align="left" class="navfont">Wins</div></td>
<td><div align="left" class="navfont">Loses</div></td>
<td><div align="left" class="navfont">Streak</div></td>
<td><div align="left" class="navfont">Reset?</div></td>
</tr>
<tr>
<td><input type="input" name="bc" value="{BC}" class="globaltab"/></td>
<td><input type="input" name="xp" value="{XP}" class="globaltab"/></td>
<td><input type="input" name="wins" value="{WINS}" class="globaltab"/></td>
<td><input type="input" name="loses" value="{LOSES}" class="globaltab"/></td>
<td><input type="input" name="streak" value="{STREAK}" class="globaltab"/></td>
<td><input type="checkbox" name="reset-account" class="globaltab" value="1"/></td>
</tr>
</table>
<h3 class="header">Game Characters</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
<td width="20%"><div align="left" class="navfont">Characters In-game</div></td>
<td width="80%"><div align="left" class="navfont">Characters Unlocked</div></td>
</tr>
<tr class="row0"><td valign="top">
<select class="globaltab" style="width: 100%; margin:1;"name="all-characters[]" multiple="multiple" size="5">
{CHARACTERS}
</select>
<center><input type="submit" name="add" value="{L_ADD}" class="globaltab"/></center>
</td>
<td valign="top">
<select class="globaltab" style="width: 100%;" name="current-characters[]" multiple="multiple" size="5">
{CURRENT_CHARACTERS}
</select>
<center><input type="submit" name="remove" value="{L_REMOVE}" class="globaltab"/></center>
</td>
</tr>
</table>
</div>

<div class="transparent"><font class="navfont">{L_SIGNATURE}</font></div>
<div class="content">
<center> <textarea rows="2" name="signature" cols="20" style="width: 97%; height: 100" class="globaltab" >{SIGNATURE}</textarea>
   </center>
</div>
<div class="transparent"><font class="navfont">{L_NOTES}</font></div>
<div class="content">
<center><textarea rows="2" name="notes" cols="20" style="width: 97%; height: 100" class="globaltab" >{NOTES}</textarea></center>
</div>
<center>
<input name="Submit" type="submit" class="globaltab" value="{L_SUBMIT}"/>
</center>

</form>
</div>
<!-- END edit -->




<!-- BEGIN select -->

<div class="transparent"><font class="navfont">{L_SELECT_USER}</font></div>
<div class="content">
<form action="" method="post" target="_self">
<center>
<table width="400">
<tr>
    <td><div align="left">
      <select name="account_select" class="globaltab" style="width:100%">
        
        {ACCOUNT_SELECT}
      
      </select></div></td>
<td><input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/></td>
  </tr>
</table>
</center>
</form>
</div>
<div class="transparent"><font class="navfont">{L_SEARCH_USERS}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <form action="" method="post" name="email" target="_self">
	<table width="100%" border="0" cellspacing="0" cellpadding="4">
	<tr>
    <td width="11%">
	<div align="left"><font class="normfont">{L_EMAIL}:</font></div>
	</td>
    <td width="49%">
	<div align="left"><font class="normfont">
		<input name="email" type="text" class="globaltab" id="email" size="30" maxlength="100" />
    </font>&nbsp;
	</div>
	</td>
	<td><div align="left">
      <input name="searchemail" type="submit" class="globaltab" value="{L_SUBMIT}"/>
    </div></td>
	</tr>
	</table>
    </form>
    </td>
  </tr>
  <tr>
    <td>
    
    <form action="" method="post" name="name" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td width="49%"><div align="left"><font class="normfont">
	<input name="name" type="text" class="globaltab" id="name" size="30" maxlength="100" />
    </font>&nbsp;</div></td><td><div align="left">
      <input name="searchname" type="submit" class="globaltab" value="{L_SUBMIT}"/>
    </div></td>
  </tr>
</form>
</table>

    
    
    </td>
  </tr>
    <tr>
    <td>
    
    <form action="" method="post" name="name" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">IP Address:</font></div></td>
    <td width="49%"><div align="left"><font class="normfont">
	<input name="ip" type="text" class="globaltab" id="ip" />
    </font>&nbsp;</div></td><td><div align="left">
      <input name="searchip" type="submit" class="globaltab" value="{L_SUBMIT}"/>
    </div></td>
  </tr>
</form>
</table>

    
    
    </td>
  </tr>
</table></div>

<!-- END select -->

