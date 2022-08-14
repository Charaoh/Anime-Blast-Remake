<div class="transparent">Game Editor {BALANCE}</div>
<div class="content">
<table width = "100%" cellspacing="2px">
<tr>
<td width="50px"><img src="{URL}tpl/default/img/clipboard.png" /></td><td> <a href="./?s=game&module=settings" class="normfont">Game Configuration</a><br /><font class="normfont">Here you can edit the game settings</font></td>
<td width="50px"><img src="{URL}tpl/default/img/database.png" /></td><td> <a href="./?s=game&module=balance-update" class="normfont">Balance Update</a><br /><font class="normfont">Here you can publish the balance update.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/user.png" /></td><td> <a href="./?s=game&module=character" class="normfont">Character database</a><br /><font class="normfont">Here you can view/edit/insert characters from the game database.</font></td>
<td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td><td> <a href="./?s=game&module=skill" class="normfont">Skill database</a><br /><font class="normfont">Here you can view/edit/insert skills from the game database.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/database.png" /></td><td> <a href="./?s=game&module=keywords" class="normfont">Keyword database</a><br /><font class="normfont">Here you can view/edit/insert keywords for the character pages.</font></td>
</tr>
</table>
</div>
<div class="content">
<table width = "100%" cellspacing="2px">
<tr>
<td width="50px"><img src="{URL}tpl/default/img/clipboard.png" /></td><td> <a href="./?s=game&module=missions" class="normfont">Mission management</a><br /><font class="normfont">Here you can add/edit ingame missions</font></td>
<td width="50px"><img src="{URL}tpl/default/img/database.png" /></td><td> <a href="./?s=game&module=store" class="normfont">Store management</a><br /><font class="normfont">Here you can manage the store items.</font></td>
</tr>
<tr>
<td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td><td> <a href="./?s=game&module=season" class="normfont">Season end</a><br /><font class="normfont">Here you can end the current season.</font></td>
<td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td><td> <a href="./?s=game&module=levels" class="normfont">Level management</a><br /><font class="normfont">Here you can add/edit/delete levels ingame.</font></td>
</tr>
</table>
</div>
<!-- BEGIN balance -->

<div class="transparent"><font class="navfont">Game settings</font></div>
<!-- BEGIN error -->
<div class="transparent">Error!</div>
<div class="content"> Please specify a forum you wish to publish this to. </div><br style="clear:both;"/>
<!-- END error -->
<!-- BEGIN success -->
<div class="transparent">Success!</div>
<div class="content"> Updates to the game are now live. </div><br style="clear:both;"/>
<!-- END success -->
<div class="content">
    
    <form action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td colspan="3"><div align="left">
	Here you will prune all character statistics to 0, and the system will automatically publish a topic with the updated data.
	</div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Current balance version:</font></div></td>
    <td colspan="3"><div align="left"><input name="version" type="text" class="globaltab" id="version" value="{BU}" size="40" maxlength="40" readonly="readonly"/></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">New batch name:</font></div></td>
    <td colspan="3"><div align="left"><input name="new" type="text" class="globaltab" id="new" value="{BU}" size="40" maxlength="40"  /></div></td>
  </tr>
  
    <tr>
	<td><div align="left"><font class="normfont">Select the forum to publishs to:</font></div></td>
    <td><div align="left"><font class="normfont">
      <select name="forum" class="globaltab" >{FORUMS}
      </select>
    </font></div></td> 
	</tr>
  <tr>
    <td colspan="4"><div align="center"><input type="submit" name="submit" value="Publish" class="globaltab"/></div></td>
  </tr>
</table>
</form>
    
    </center>
</div>

<!-- END balance -->

<!-- BEGIN edit -->
<div class="transparent"><font class="navfont">Game settings</font></div>
<!-- BEGIN success -->
<div class="transparent">Success!</div>
<div class="content"> Updates to the game are now live. </div><br style="clear:both;"/>
<!-- END success -->
<div class="content">
    
    <form action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <td><div align="left"><font class="normfont">Admin group that the new characters will be published to (glued by a comma if multiple):</font></div></td>
    <td colspan="3"><div align="left"><input name="admin" type="text" class="globaltab" id="version" value="{ADMIN}" /></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Balance version:</font></div></td>
    <td colspan="3"><div align="left"><input name="version" type="text" class="globaltab" id="version" value="{BU}" size="40" maxlength="40" readonly="readonly"/></div></td>
  </tr>
    <tr>
    <td><div align="left"><font class="normfont">Ingame Starters (characters id glued by a comma):</font></div></td>
    <td colspan="3"><div align="left"><input name="starters" type="text" class="globaltab" id="version" value="{STARTERS}" size="40"/></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Turn time (Default 60 seconds):</font></div></td>
    <td colspan="3"><div align="left"><input name="turn" type="text" class="globaltab" id="turn" value="{TURN}" size="40" maxlength="40"  /></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Mana cap turn 1:</font></div></td>
    <td colspan="3"><div align="left"><input name="mc1" type="text" class="globaltab" id="mc1" value="{MC1}" size="40" maxlength="40" /></div></td>
  </tr>
   <tr>
    <td><div align="left"><font class="normfont">Mana cap turn 2:</font></div></td>
    <td colspan="3"><div align="left"><input name="mc2" type="text" class="globaltab" id="mc2" value="{MC2}" size="40" maxlength="40" /></div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Experience difference in ladder mode ( Difference between 2 players ):</font></div></td>
    <td colspan="3"><div align="left"><input name="range" type="text" class="globaltab" id="range" value="{RANGE}" size="40" maxlength="40" />
    </div></td>
  </tr>
 
  <tr>
    <td><div align="left"><font class="normfont">Mana gained per turn ( This is divided by the characters alive ):</font></div></td>
    <td colspan="3"><div align="left">
      <input name="mana" type="text" class="globaltab" id="mana" value="{MANA}" size="40" maxlength="40" />
    </div></td>
  </tr>
<tr>
    <td><div align="left"><font class="normfont">Gold earned if you win a Ladder game:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="gold_win" type="text" class="globaltab" id="gold_win" value="{GW}" size="40" maxlength="40" />
    </div></td>
  </tr> 
  
<tr>
    <td><div align="left"><font class="normfont">Gold earned if you lose a Ladder game:</font></div></td>
    <td colspan="3"><div align="left"><font class="normfont">
		<input name="gold_lose" type="text" class="globaltab" id="gold_lose" value="{GL}" size="40" maxlength="40" />
    </font></div></td>
   
<tr>
    
<td><div align="left"><font class="normfont">Gold earned if you win a Quick game:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="gold_winQ" type="text" class="globaltab" id="gold_winQ" value="{GWQ}" size="40" maxlength="40" />
    </div></td>
  </tr> 
      </tr>
<tr>
    <td><div align="left"><font class="normfont">Gold earned if you lose a Quick game:</font></div></td>
    <td colspan="3"><div align="left"><font class="normfont">
		<input name="gold_loseQ" type="text" class="globaltab" id="gold_loseQ" value="{GLQ}" size="40" maxlength="40" />
    </font></div></td>

      </tr>
      
   <tr>
    <td><div align="left"><font class="normfont">Max experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="max" type="text" class="globaltab" id="max" value="{MAX}" size="40" maxlength="40" />
    </div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Minimum experience earned in ladder match:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="min" type="text" class="globaltab" id="min" value="{MIN}" size="40" maxlength="40" />
    </div></td>
  </tr>
  <tr>
  <td>
    <font class="navfont">AI settings</font>
  <hr></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">AI Bot ACCOUNT:</font></div></td>
    <td colspan="3"><div align="left">
      {AI}
    </div></td>
  </tr>
    <tr>
    <td><div align="left"><font class="normfont">AI Bot Character reward:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="ai_reward" type="text" class="globaltab" id="ai_reward" value="{AIREWARD}" size="40" maxlength="40" />
    </div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Show AI battle to mortals:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="mortalMenCanSEE" type="checkbox" class="globaltab" id="mortalMenCanSEE" {MORTALMEN} />
    </div></td>
  </tr>
  <tr>
  <td>
    <font class="navfont">Exclusive page settings</font>
  <hr></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Hide exlusive page:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="Hide_Exclusives" type="checkbox" class="globaltab" id="Hide_Exclusives" value="1" {HIDEEXCLUSIVE}/>
    </div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Clean the exclusive character selected of all accounts:</font></div></td>
    <td colspan="3"><div align="left">
      <input name="cleanUPExclusives" type="checkbox" class="globaltab" id="cleanUPExclusives" />
    </div></td>
  </tr>
  <tr>
    <td><div align="left"><font class="normfont">Exclusive characters: (by id and glued by commas if multiple options)</font></div></td>
    <td colspan="3"><div align="left">
      <input name="exclusives" type="text" class="globaltab" id="exclusives" value="{EXCLUSIVES}" size="40" maxlength="40" />
    </div></td>
  </tr>
      
  <tr>
    <td colspan="4"><div align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="globaltab"/></div></td>
  </tr>
</table>
</form>
</div>

<!-- END edit -->