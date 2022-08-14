<div class="regular">
    <div class="transparent">Website Editor</div>
    <div class="content">
        <table width="100%" cellspacing="2px">
            <tr>
                <td width="50px"><img src="{URL}tpl/default/img/tools.png" /></td>
                <td> <a href="./?s=website&module=settings" class="headerfont">Game Configuration</a><br />
                    <p class="acpfont">Manage game settings.</p>
                </td>
                <td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td>
                <td> <a href="./?s=website&module=season" class="headerfont">Season Management</a><br />
                    <p class="acpfont">Manage the ranked season.</p>
                </td>

            </tr>
            <tr>
                <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
                <td> <a href="./?s=website&module=character" class="headerfont">Character Database</a><br />
                    <p class="acpfont">Manage and create new characters.</p>
                </td>
                <td width="50px"><img src="{URL}tpl/default/img/database.png" /></td>
                <td> <a href="./?s=website&module=skill" class="headerfont">Skill Database</a><br />
                    <p class="acpfont">Manage and create new skills.</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <table width="100%" cellspacing="2px">
            <tr>
                <td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td>
                <td> <a href="./?s=website&module=missions" class="headerfont">Mission Management</a><br />
                    <p class="acpfont">Manage and create new missions</p>
                </td>
                <td width="50px"><img src="{URL}tpl/default/img/settings.png" /></td>
                <td> <a href="./?s=website&module=store" class="headerfont">Store Management</a><br />
                    <p class="acpfont">Manage and create new items.</p>
                </td>
            </tr>

        </table>
    </div>

    <!-- BEGIN edit -->
    <div class="regular">
        <div class="transparent">
            <p class="navfont">Game Settings</p>
        </div>
    </div>

    <!-- BEGIN success -->
    <div class="regular">
        <div class="title">Success!</div>
        <div class="content"> Updates to the game are now live. </div><br style="clear:both;" />
    </div>
    <!-- END success -->
    <div class="regular">
        <div class="content">
            <form action="" method="post">
                <div class="regular">
                    <div class="title">Defaults</div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Admin group that the new characters will be published to (glued
                                        by a
                                        comma if multiple):</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="admin" type="text" class="globaltab"
                                        id="version" value="{ADMIN}" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Balance Version:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="version" type="text" class="globaltab"
                                        id="version" value="{BU}" size="40" maxlength="40" readonly="readonly" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Ingame Starters (characters id glued by a comma):</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="starters" type="text" class="globaltab"
                                        id="version" value="{STARTERS}" size="40" /></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="regular">
                    <div class="title">Battle Settings</div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Turn Time (Default 60 seconds):</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="turn" type="text" class="globaltab" id="turn"
                                        value="{TURN}" size="40" maxlength="40" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Mana cap turn 1:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="mc1" type="text" class="globaltab" id="mc1"
                                        value="{MC1}" size="40" maxlength="40" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Mana cap turn 2:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="mc2" type="text" class="globaltab" id="mc2"
                                        value="{MC2}" size="40" maxlength="40" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Experience difference in ladder mode ( Difference between 2
                                        players
                                        ):
                                    </p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;"><input name="range" type="text" class="globaltab"
                                        id="range" value="{RANGE}" size="40" maxlength="40" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Mana gained per turn ( This is divided by the characters
                                        alive
                                        ):
                                    </p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="mana" type="text" class="globaltab" id="mana" value="{MANA}" size="40"
                                        maxlength="40" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Gold earned if you win a Ladder game:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="gold_win" type="text" class="globaltab" id="gold_win" value="{GW}"
                                        size="40" maxlength="40" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Gold earned if you lose a Ladder game:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <p class="normfont">
                                        <input name="gold_lose" type="text" class="globaltab" id="gold_lose"
                                            value="{GL}" size="40" maxlength="40" />
                                    </p>
                                </div>
                            </td>

                        <tr>

                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Gold earned if you win a Quick game:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="gold_winQ" type="text" class="globaltab" id="gold_winQ" value="{GWQ}"
                                        size="40" maxlength="40" />
                                </div>
                            </td>
                        </tr>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Gold earned if you lose a Quick game:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <p class="normfont">
                                        <input name="gold_loseQ" type="text" class="globaltab" id="gold_loseQ"
                                            value="{GLQ}" size="40" maxlength="40" />
                                    </p>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Max experience earned in ladder match:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="max" type="text" class="globaltab" id="max" value="{MAX}" size="40"
                                        maxlength="40" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Minimum experience earned in ladder match:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="min" type="text" class="globaltab" id="min" value="{MIN}" size="40"
                                        maxlength="40" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <br>
                <div class="regular">
                    <div class="title">Exclusive Settings</div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">AI Bot Account:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    {AI}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">AI Bot Character Reward:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="ai_reward" type="text" class="globaltab" id="ai_reward"
                                        value="{AIREWARD}" size="40" maxlength="40" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Show AI battle to mortals:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="mortalMenCanSEE" type="checkbox" class="globaltab" id="mortalMenCanSEE"
                                        {MORTALMEN} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Hide exlusive page:</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="Hide_Exclusives" type="checkbox" class="globaltab" id="Hide_Exclusives"
                                        value="1" {HIDEEXCLUSIVE} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Clean the exclusive character selected of all accounts:
                                    </p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="cleanUPExclusives" type="checkbox" class="globaltab"
                                        id="cleanUPExclusives" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="text-align:left;">
                                    <p class="normfont">Exclusive characters: (by id and glued by commas if
                                        multiple
                                        options)
                                    </p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div style="text-align:left;">
                                    <input name="exclusives" type="text" class="globaltab" id="exclusives"
                                        value="{EXCLUSIVES}" size="40" maxlength="40" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <div style="text-align:center;"><input type="submit" name="submit" value="{L_SUBMIT}"
                                        class="globaltab" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END edit -->