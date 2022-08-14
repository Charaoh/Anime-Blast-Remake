
<div class="transparent"><font class="navfont">{L_BACKUP}</font></div>
<div class="content">{L_BACKUP_MSG}<br /><center><form action="" method="post" name="row" target="_self"><input name="generate" type="submit" class="globaltab" value="{L_GENERATE_BACKUP}"/></form></center></div>

<div class="content">
<table width="100%" cellspacing="2" cellpadding="2">
<tr class="boxone"><td colspan="3"   style="padding: 4px">{L_BACKUP}</td></tr>
<tr  class="boxtwo"><td width="75%"><font class="normfont"><center>{L_BACKUP}</center></font></td><td width="25%"><font class="normfont"><center>{L_OPTIONS}</center></font></td>

<!-- BEGIN row -->
<tr  class="color {CLASS}"><form action="" method="post" name="row" target="_self"><td><font class="normfont"> {TITLE}</font></td><td><input type="hidden" name="id" value="{TITLE}"><input name="download" type="button" class="globaltab" onClick="parent.location='./backups/{TITLE}'" value="{L_DOWNLOAD}"/> <input name="delete" type="submit" class="globaltab" value="{L_DELETE}"/></font></td></form>
</tr><!-- END row -->
</table>
</div>
