<div class="transparent"><font class="navfont">{TITLE}</font></div>
<div class="content">

<img src="{URL}tpl/default/img/system.png" />
<font class="normfont">{MESSAGE}</font>
<br class="clearfix"/>
<center>
<form action="" method="post" target="_self">
{HIDDEN_FIELDS}
<INPUT TYPE="hidden" name="confirmed" value="true"/>
<INPUT TYPE="submit" value ="{L_CONFIRM}" name="submit" class="globaltab"/>
<INPUT TYPE="button" value ="{L_CANCEL}" class="globaltab" onClick="parent.location='{LINK}'">
</form></center>
</div>