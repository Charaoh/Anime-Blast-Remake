<div class="boxone">IN-GAME EDITOR {BALANCE}</div><table width = "100%" cellspacing="2px">
<tr>
<td width="50px"><img src="{URL}tpl/default/img/clipboard/img/clipboard.png" /></td><td> <a href="./?s=game&module=settings" class="normfont">Game Configuration</a><br /><font class="normfont">Here you can edit the game settings</font></td>
<td width="50px"><img src="{URL}tpl/default/img/database.png" /></td><td> <a href="./?s=game&module=balance-update" class="normfont">Balance Update</a><br /><font class="normfont">Here you can publish the balance update.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/user.png" /></td><td> <a href="./?s=game&module=character" class="normfont">Character database</a><br /><font class="normfont">Here you can view/edit/insert characters from the game database.</font></td>
<td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td><td> <a href="./?s=game&module=skill" class="normfont">Skill database</a><br /><font class="normfont">Here you can view/edit/insert skills from the game database.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/user.png" /></td><td> <a href="./?s=game&module=character" class="normfont">Character database</a><br /><font class="normfont">Here you can view/edit/insert characters from the game database.</font></td>
<td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td><td> <a href="./?s=game&module=skill" class="normfont">Skill database</a><br /><font class="normfont">Here you can view/edit/insert skills from the game database.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/database.png" /></td><td> <a href="./?s=game&module=keywords" class="normfont">Keywords database</a><br /><font class="normfont">Here you can view/edit/insert keywords from the game database.</font></td>
</tr>
</table>
<!-- BEGIN balance -->

<div class="boxone"><font class="navfont">Game settings</font></div>
<!-- BEGIN success -->
<div class="boxtwo">Success!</div>
<div class="boxthree"> Updates to the game are now live. </div><br style="clear:both;"/>
<!-- END success -->
<div class="boxtwo"></div>
<div class="boxthree">
    
    <form action="" method="post"><center>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td colspan="3"><div align="left">
	Here you will prune all character statistics to 0, and the system will automatically publish a topic with the updated data.
	</div></td>
  </tr>
  <tr>
    <td width="10px"><div align="left"><font class="normfont">Current balance version:</font></div></td>
    <td colspan="3"><div align="left"><input name="version" type="text" class="formcss" id="version" value="{BU}" size="40" maxlength="40" readonly="readonly"/></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">New batch name:</font></div></td>
    <td colspan="3"><div align="left"><input name="new" type="text" class="formcss" id="new" value="{BU}" size="40" maxlength="40"  /></div></td>
  </tr>
  
    <tr>
	<td><div align="left"><font class="normfont">Select the forum to publishs to:</font></div></td>
    <td><div align="left"><font class="normfont">
      <select name="forum" class="formcss" >{FORUMS}
      </select>
    </font></div></td> 
	</tr>
  <tr>
    <td colspan="4"><div align="center"><input type="submit" name="submit" value="Publish" class="formcss"/></div></td>
  </tr>
</table>
</form>
    
    </center>
</div>

<!-- END balance -->

<!-- BEGIN edit -->
<div class="boxone"><font class="navfont">Game settings</font></div>
<!-- BEGIN success -->
<div class="boxtwo">Success!</div>
<div class="boxthree"> Updates to the game are now live. </div><br style="clear:both;"/>
<!-- END success -->
<div class="boxtwo"></div>
<div class="boxthree">
    
    <form action="" method="post"><center>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="10px"><div align="left"><font class="normfont">Balance version:</font></div></td>
    <td colspan="3"><div align="left"><input name="version" type="text" class="formcss" id="version" value="{BU}" size="40" maxlength="40" readonly="readonly"/></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Turn time (Default 60 seconds):</font></div></td>
    <td colspan="3"><div align="left"><input name="turn" type="text" class="formcss" id="turn" value="{TURN}" size="40" maxlength="40"  /></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Reconnection time (AFK check, to give result to the match if a player doesn't respond):</font></div></td>
    <td colspan="3"><div align="left"><input name="afk" type="text" class="formcss" id="afk" value="{AFK}" size="40" maxlength="40" /></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Experience difference in ladder mode ( Difference between 2 players ):</font></div></td>
    <td colspan="3"><div align="left"><input name="range" type="text" class="formcss" id="range" value="{RANGE}" size="40" maxlength="40" />
    </div></td>
  </tr>
 
  <tr>
    <td width="11%"><div align="left"><font class="normfont">Mana gained per turn ( This is divided by the characters alive ):</font></div></td>
    <td colspan="3"><div align="left">
      <input name="mana" type="text" class="formcss" id="mana" value="{MANA}" size="40" maxlength="40" />
    </div></td>
  </tr>
<tr>
    <td width="11%"><div align="left"><font class="normfont">Gold earned if you win:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="gold_win" type="text" class="formcss" id="gold_win" value="{GW}" size="40" maxlength="40" />
    </div></td>
  </tr> 

   
<tr>
    <td width="11%"><div align="left"><font class="normfont">Gold earned if you lose:</font></div></td>
    <td colspan="2"><div align="left"><font class="normfont">
		<input name="gold_lose" type="text" class="formcss" id="gold_lose" value="{GL}" size="40" maxlength="40" />
    </font></div></td>

      </tr>

   <tr>
    <td width="11%"><div align="left"><font class="normfont">Max experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="max" type="text" class="formcss" id="max" value="{MAX}" size="40" maxlength="40" />
    </div></td>
  </tr>
  <tr>
    <td width="11%"><div align="left"><font class="normfont">Minimum experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
    </div></td>
  </tr>
  <tr>
    <td width="11%"><div align="left"><font class="normfont">Minimum experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
    </div></td>
    <td width="11%"><div align="left"><font class="normfont">Minimum experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
    </div></td>
  </tr>
  
      
  <tr>
    <td colspan="4"><div align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="formcss"/></div></td>
  </tr>
</table>
</form>
    
    </center>
</div>

<!-- END edit -->