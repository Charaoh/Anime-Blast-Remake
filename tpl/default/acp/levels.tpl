<div class="regular">
	<div class="transparent">Create a new level</div>
	<div class="content">
		<form action="" method="post">
			<label>
				<p class="navfont">Level Number</p>
			</label>
			<input type="text" class="globaltab" name="number" value="" placeholder="Level Number" />
			<br class="clearfix" />
			<label>
				<p class="navfont">Level Name</p>
			</label>
			<input type="text" class="globaltab" name="name" value="" placeholder="Level Name" />
			<br class="clearfix" />
			<label>
				<p class="navfont">Experience Needed</p>
			</label>
			<input type="text" class="globaltab" name="experience" value="" placeholder="Experience" />
			<br class="clearfix" />
			<label>
				<p class="navfont">Level Image</p>
			</label>
			{IMAGES}
			<div align="center"><input type="submit" name="submit" value="Add Level" class="globaltab" /><input
					type="hidden" name="addlevel" value="1" /></div>
		</form>
	</div>
	<br>
	<div class="transparent">Current Levels and Ranges</div>
	<div class="content">
		{LEVELS}
	</div>

	<!-- BEGIN level -->
	<form action="" method="post" enctype="multipart/form-data">
		<label>
			<p class="navfont">Level Number</p>
		</label>
		<input type="text" class="globaltab" name="number" value="{NUMBER}" placeholder="Level number" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Level Name</p>
		</label>
		<input type="text" class="globaltab" name="name" value="{NAME}" placeholder="Level name" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Experience Needed</p>
		</label>
		<input type="text" class="globaltab" name="xp" value="{XP}" placeholder="Experience needed" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Level Rank Image</p>
		</label>
		<input type="text" class="globaltab" name="image" value="{IMG}" placeholder="Image of the level" /><input
			type="hidden" name="id" value="{NUMBER}" class="globaltab" />
		<div align="center"><input type="submit" name="submit-level" value="update" class="globaltab" /><input
				type="submit" name="delete-level" value="Delete" class="globaltab" /></div>
	</form>
	<!-- END level -->
</div>