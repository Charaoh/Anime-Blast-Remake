<div class="transparent">Levels</div>
<div class="content">
{LEVELS}
</div>
<div class="transparent">Create new level</div>
<div class="content">
 <form action="" method="post">
	<label><font class="navfont">Level Number</font></label>
    <input type="text" class="globaltab" name="number" value="" placeholder="Level Number" />
	<br class="clearfix"/>
	<label><font class="navfont">Level Name</font></label>
	<input type="text" class="globaltab" name="name" value="" placeholder="Level Name" />
	<br class="clearfix"/>
	<label><font class="navfont">Experience needed</font></label>
	<input type="text" class="globaltab" name="experience" value="" placeholder="Experience" />
	<br class="clearfix"/>
	<label><font class="navfont">Level Image</font></label>
	{IMAGES}
	<div align="center"><input type="submit" name="submit" value="Add Level" class="globaltab"/><input type="hidden" name="addlevel" value="1"/></div>
 </form>
</div>
<!-- BEGIN level -->
 <form action="" method="post" enctype="multipart/form-data">
	<label><font class="navfont">Level Number</font></label>
	<input type="text" class="globaltab" name="number" value="{NUMBER}" placeholder="Level number" />
	<br class="clearfix"/>
	<label><font class="navfont">Level Name</font></label>
	<input type="text" class="globaltab" name="name" value="{NAME}" placeholder="Level name" />
	<br class="clearfix"/>
	<label><font class="navfont">Experience needed</font></label>
	<input type="text" class="globaltab" name="xp" value="{XP}" placeholder="Experience needed" />
	<br class="clearfix"/>
	<label><font class="navfont">Level Rank Image</font></label>
	<input type="text" class="globaltab" name="image" value="{IMG}" placeholder="Image of the level" /><input type="hidden" name="id" value="{NUMBER}" class="globaltab"/>
	<div align="center"><input type="submit" name="submit-level" value="update" class="globaltab"/><input type="submit" name="delete-level" value="Delete" class="globaltab"/></div>
 </form>
<!-- END level -->