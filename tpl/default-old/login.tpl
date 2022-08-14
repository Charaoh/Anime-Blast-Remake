<div class="transparent"><font class="navfont">{L_LOGIN}</font></div>
<div class="content">
<!-- BEGIN error -->
<p class="error" style="display:block;">{ERROR}</p>
<!-- END error -->
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<form action="" method="post" name="login" target="_self">
<tr>
	<td width="11%">
	<div align="left">
	<font class="normfont">{L_NAME}:</font>
	</div>
	</td>
	<td width="89%">
	<div align="left">
	<input name="username" type="text" class="formcss" size="40" maxlength="40" />
	</div>
	</td>
	</tr>
	<tr>
	<td>
	<div align="left">
	<font class="normfont">{L_PASSWORD}:</font>
	</div>
	</td>
	<td><div align="left">
	<input name="pass" type="password" class="formcss" size="40" maxlength="100" /></div></td></tr>
<tr><td colspan="2"><div align="left"><a href="./?s=lostpassword" class="normfont">Click here for password recovery!</a></div></td></tr>
<tr><td colspan="2"><div align="center"><input name="submit" type="submit" class="formcss" value="{L_LOGIN}"/></div></td></tr>
</table></div>
