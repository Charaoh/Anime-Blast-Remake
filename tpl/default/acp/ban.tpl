
<div class="transparent"><font class="navfont">{L_ADD_BAN}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    
    <form action="" method="post" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left">
      <input name="value" type="text" class="formcss" size="40" maxlength="40" id="name" />
     </div></td><td><div align="left">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="formcss"/>
    </div></td>
  </tr>

</table>
</form>
    
    
    </td>
  </tr>
</table></div>

<!-- BEGIN list -->
<div class="pages">{PAGES}</div>


<div class="content">
<table width="100%" cellspacing="2" cellpadding="2">
<tr><td colspan="3"   style="padding: 4px">{L_BANS}</td></tr>
<tr><td width="10px"><font class="normfont"><center>{L_ID}</center></font></td><td width="75%"><font class="normfont"><center>{L_INFO}</center></font></td><td width="25%"><font class="normfont"><center>{L_OPTIONS}</center></font></td>

<!-- BEGIN row -->
<tr class="color {CLASS}"><form action="" method="post" name="row" target="_self"><td><font class="normfont">{ID}</font></td><td><font class="normfont">{TITLE}</font></td><td><input type="hidden" name="id" value="{ID}"><input name="delete" type="submit" class="formcss" value="{L_DELETE}"/></font></td></form>
</tr><!-- END row -->
</table>
</div>
<div class="pages">{PAGES}</div>
<!-- END list -->



