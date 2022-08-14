<div class="regular">
  <div class="transparent">Game Editor | {BALANCE}</div>
  <div class="content">
    <table width="100%" cellspacing="2px">
      <tr>
        <td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td>
        <td> <a href="./?s=game&module=balance-update" class="headerfont">Balance Update</a><br />
          <p class="acpfont">Publish a new balance update.</p>
        </td>
        <td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td>
        <td> <a href="./?s=game&module=levels" class="headerfont">Level Management</a><br />
          <p class="acpfont">Manage ingame levels and ranges.</p>
        </td>
      </tr>
    </table>
  </div>
  <div class="content">
    <table width="100%" cellspacing="2px">
      <tr>
        <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
        <td> <a href="./?s=game&module=character" class="headerfont">Character Database</a><br />
          <p class="acpfont">Manage characters and check stats.</p>
        </td>
        <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
        <td> <a href="./?s=game&module=keywords" class="headerfont">Keyword Database</a><br />
          <p class="acpfont">Manage keywords for the character pages.</p>
        </td>

      </tr>
    </table>
  </div>
</div>

<!-- BEGIN balance -->
<div class="regular">
  <div class="transparent">
    <p class="navfont">Balance Manager</p>
  </div>

  <!-- BEGIN error -->
  <div class="regular">
    <div class="transparent">Error!</div>
    <div class="content"> Please specify a forum you wish to publish this to. </div><br style="clear:both;" />
  </div>
  <!-- END error -->

  <!-- BEGIN success -->
  <div class="regular">
    <div class="transparent">Success!</div>
    <div class="content"> Updates to the game are now live. </div><br style="clear:both;" />
  </div>
  <!-- END success -->

  <div class="content">
    <div style="text-align:center;">
      <form action="" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td colspan="3">
              <div style="text-align:left;">
                Here you will prune all character statistics to 0, and the system will automatically publish a topic
                with
                the updated data.
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div style="text-align:left;">
                <p class="normfont">Current balance version:</p>
              </div>
            </td>
            <td colspan="3">
              <div style="text-align:left;"><input name="version" type="text" class="globaltab" id="version"
                  value="{BU}" size="40" maxlength="40" readonly="readonly" /></div>
            </td>
          </tr>
          <tr>
            <td>
              <div style="text-align:left;">
                <p class="normfont">New batch name:</p>
              </div>
            </td>
            <td colspan="3">
              <div style="text-align:left;"><input name="new" type="text" class="globaltab" id="new" value="{BU}"
                  size="40" maxlength="40" /></div>
            </td>
          </tr>

          <tr>
            <td>
              <div style="text-align:left;">
                <p class="normfont">Select the forum to publishs to:</p>
              </div>
            </td>
            <td>
              <div style="text-align:left;">
                <p class="normfont">
                  <select name="forum" class="globaltab">{FORUMS}
                  </select>
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <div style="text-align:center;"><input type="submit" name="submit" value="Publish" class="globaltab" />
              </div>
            </td>
          </tr>
        </table>
      </form>

    </div>
  </div>
<!-- END balance -->