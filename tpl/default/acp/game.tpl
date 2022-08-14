<div class="boxone">IN-GAME EDITOR {BALANCE}</div>
<table width="100%" cellspacing="2px">
  <tr>
    <td width="50px"><img src="{URL}tpl/default/img/clipboard/img/clipboard.png" /></td>
    <td> <a href="./?s=game&module=settings" class="normfont">Game Configuration</a><br />
      <p class="normfont">Here you can edit the game settings</p>
    </td>
    <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
    <td> <a href="./?s=game&module=balance-update" class="normfont">Balance Update</a><br />
      <p class="normfont">Here you can publish the balance update.</p>
    </td>
  </tr>
  <tr>
    <td width="50px"><img src="{URL}tpl/default/img/user.png" /></td>
    <td> <a href="./?s=game&module=character" class="normfont">Character database</a><br />
      <p class="normfont">Here you can view/edit/insert characters from the game database.</p>
    </td>
    <td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td>
    <td> <a href="./?s=game&module=skill" class="normfont">Skill database</a><br />
      <p class="normfont">Here you can view/edit/insert skills from the game database.</p>
    </td>
  </tr>
  <tr>
    <td width="50px"><img src="{URL}tpl/default/img/user.png" /></td>
    <td> <a href="./?s=game&module=character" class="normfont">Character database</a><br />
      <p class="normfont">Here you can view/edit/insert characters from the game database.</p>
    </td>
    <td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td>
    <td> <a href="./?s=game&module=skill" class="normfont">Skill database</a><br />
      <p class="normfont">Here you can view/edit/insert skills from the game database.</p>
    </td>
  </tr>
  <tr>
    <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
    <td> <a href="./?s=game&module=keywords" class="normfont">Keywords database</a><br />
      <p class="normfont">Here you can view/edit/insert keywords from the game database.</p>
    </td>
  </tr>
</table>
<!-- BEGIN balance -->

<div class="boxone">
  <p class="navfont">Game settings</p>
</div>
<!-- BEGIN success -->
<div class="boxtwo">Success!</div>
<div class="boxthree"> Updates to the game are now live. </div><br style="clear:both;" />
<!-- END success -->
<div class="boxtwo"></div>
<div class="boxthree">

  <form action="" method="post">
    <center>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td colspan="3">
            <div align="left">
              Here you will prune all character statistics to 0, and the system will automatically publish a topic with
              the updated data.
            </div>
          </td>
        </tr>
        <tr>
          <td width="10px">
            <div align="left">
              <p class="normfont">Current balance version:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="version" type="text" class="formcss" id="version" value="{BU}" size="40"
                maxlength="40" readonly="readonly" /></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="left">
              <p class="normfont">New batch name:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="new" type="text" class="formcss" id="new" value="{BU}" size="40"
                maxlength="40" /></div>
          </td>
        </tr>

        <tr>
          <td>
            <div align="left">
              <p class="normfont">Select the forum to publishs to:</p>
            </div>
          </td>
          <td>
            <div align="left">
              <p class="normfont">
                <select name="forum" class="formcss">{FORUMS}
                </select>
              </p>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <div align="center"><input type="submit" name="submit" value="Publish" class="formcss" /></div>
          </td>
        </tr>
      </table>
  </form>

  </center>
</div>

<!-- END balance -->

<!-- BEGIN edit -->
<div class="boxone">
  <p class="navfont">Game settings</p>
</div>
<!-- BEGIN success -->
<div class="boxtwo">Success!</div>
<div class="boxthree"> Updates to the game are now live. </div><br style="clear:both;" />
<!-- END success -->
<div class="boxtwo"></div>
<div class="boxthree">

  <form action="" method="post">
    <center>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="10px">
            <div align="left">
              <p class="normfont">Balance version:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="version" type="text" class="formcss" id="version" value="{BU}" size="40"
                maxlength="40" readonly="readonly" /></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="left">
              <p class="normfont">Turn time (Default 60 seconds):</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="turn" type="text" class="formcss" id="turn" value="{TURN}" size="40"
                maxlength="40" /></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="left">
              <p class="normfont">Reconnection time (AFK check, to give result to the match if a player doesn't
                respond):</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="afk" type="text" class="formcss" id="afk" value="{AFK}" size="40"
                maxlength="40" /></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="left">
              <p class="normfont">Experience difference in ladder mode ( Difference between 2 players ):</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left"><input name="range" type="text" class="formcss" id="range" value="{RANGE}" size="40"
                maxlength="40" />
            </div>
          </td>
        </tr>

        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Mana gained per turn ( This is divided by the characters alive ):</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="mana" type="text" class="formcss" id="mana" value="{MANA}" size="40" maxlength="40" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Gold earned if you win:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="gold_win" type="text" class="formcss" id="gold_win" value="{GW}" size="40" maxlength="40" />
            </div>
          </td>
        </tr>


        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Gold earned if you lose:</p>
            </div>
          </td>
          <td colspan="2">
            <div align="left">
              <p class="normfont">
                <input name="gold_lose" type="text" class="formcss" id="gold_lose" value="{GL}" size="40"
                  maxlength="40" />
              </p>
            </div>
          </td>

        </tr>

        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Max experience earned in ladder match:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="max" type="text" class="formcss" id="max" value="{MAX}" size="40" maxlength="40" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Minimum experience earned in ladder match:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="11%">
            <div align="left">
              <p class="normfont">Minimum experience earned in ladder match:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
            </div>
          </td>
          <td width="11%">
            <div align="left">
              <p class="normfont">Minimum experience earned in ladder match:</p>
            </div>
          </td>
          <td colspan="3">
            <div align="left">
              <input name="min" type="text" class="formcss" id="min" value="{MIN}" size="40" maxlength="40" />
            </div>
          </td>
        </tr>


        <tr>
          <td colspan="4">
            <div align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="formcss" /></div>
          </td>
        </tr>
      </table>
  </form>

  </center>
</div>

<!-- END edit -->