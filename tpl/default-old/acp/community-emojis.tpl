<!-- BEGIN main -->

<div class="transparent"><font class="navfont">Forum Emojis :D</font></div>
<div class="content">
{MESSAGE}
<h2 class="header"> Create a new Emoticon</h2>
<form action="" method="post" target="_self">
	<label name="name">Emoji Name: <input type="input" name="name" class="globaltab"/></label>
	<label name="code">Emoji Code: <input type="input" name="code" class="globaltab"/></label>
	<label name="replacement">Emoji Replacement: <input  type="input" name="replacement" class="globaltab"/></label>
	<input type="submit" name="new" value="New Emoji" class="globaltab"/>
</form>
<hr>
<h2 class="header"> Database of Emojis</h2>
{LIST}
<!-- BEGIN option -->
<div>
<form action="" method="post" target="_self">
	{REPLACEMENT}
	<label name="name">Emoji Name: <input type="input" name="name" value="{NAME}" class="globaltab"/></label>
	<label name="code">Emoji Code: <input type="input" name="code" value="{CODE}" class="globaltab"/></label>
	<br>
		<center>
	<label name="replacement">Emoji Replacement: <br><textarea name="replacement" value="{VALUE}" class="globaltab" style="margin: 5px 5px 0px 0px;
    width: 674px;
    height: 30px;">{VALUE}</textarea></label>	</center>
	<br class="clearfix">
	<center>
	<input type="hidden" name="emoji" value="{ID}" class="globaltab"/>
	<input type="submit" name="edit" value="Save Changes" class="globaltab"/>
	<input type="submit" name="delete" value="Delete" class="globaltab"/>
	</center>
</form>
</div><br>
<!-- END option -->
</div>
<!-- END main -->