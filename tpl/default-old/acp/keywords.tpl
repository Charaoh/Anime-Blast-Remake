<!-- BEGIN main -->

<div class="transparent"><font class="navfont">Character Keywords</font></div>
<div class="content">
{MESSAGE}
<h2 class="header"> Create a new Keyword</h2>
<form action="" method="post" target="_self">
	<label name="name">Keyword: <input type="input" name="name" placeholder="The word you want to search for" class="globaltab"/></label><br>
	<center>
		<label name="description">Description: <br><textarea name="description" placeholder="The word you want to describe"  class="globaltab" style="margin: 5px 5px 0px 0px;width: 674px;height: 30px;"></textarea></label>
		<br>
		<label name="replacement">Keyword Replacement: <br><textarea placeholder="The word you want to search for replaced with this"  name="replacement" class="globaltab" style="margin: 5px 5px 0px 0px;
    width: 674px;
    height: 30px;"></textarea></label>	
	</center>
	<input type="submit" name="new" value="New Keyword" class="globaltab"/>
</form>
<hr>
<h2 class="header"> Database of Keywords</h2>
{LIST}
<!-- BEGIN option -->
<div>
<form action="" method="post" target="_self">
	<div class="quote">
		Word Preview <br> <span data-tooltip="{DESCRIPTION}" data-tooltip-persistent>{REPLACEMENT}</span></div>
	<label name="name">Keyword: <input type="input" name="name" value="{NAME}" class="globaltab"/></label><br>
	<center>
	<label name="description">Description: <br><textarea name="description" value="{DESCRIPTION}" class="globaltab" style="margin: 5px 5px 0px 0px;
    width: 674px;
    height: 30px;">{DESCRIPTION}</textarea></label>
	<br>
		
	<label name="replacement">Keyword Replacement: <br><textarea name="replacement" value="{VALUE}" class="globaltab" style="margin: 5px 5px 0px 0px;
    width: 674px;
    height: 30px;">{VALUE}</textarea></label>	
	</center>
	<br class="clearfix">
	<center>
	<input type="hidden" name="keyword" value="{ID}" class="globaltab"/>
	<input type="submit" name="edit" value="Save Changes" class="globaltab"/>
	<input type="submit" name="delete" value="Delete" class="globaltab"/>
	</center>
</form>
</div><br>
<!-- END option -->
</div>
<!-- END main -->