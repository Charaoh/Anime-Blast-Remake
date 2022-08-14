<!-- BEGIN menu_manage -->
<input type="button" value="Settings" class="globaltab"
	onclick="parent.location='./clan/profile/{NAME}?action=settings'" placeholder="" style="
    float: right;
">
<!-- END menu_manage -->
<!-- BEGIN menu_goback -->
<input type="button" value="Go back to clans" class="globaltab" onclick="parent.location='./clans'" placeholder=""
	style="
    float: right;
">
<!-- END menu_goback -->
<!-- BEGIN menu_gobackclan -->
<input type="button" value="Clan Panel" class="globaltab" onclick="parent.location='./clan/profile/{NAME}'"
	placeholder="" style="
    float: right;
">
<!-- END menu_gobackclan -->
<!-- BEGIN menu_create -->
<input type="button" value="Create a clan!" class="globaltab" onclick="parent.location='./clans?action=create'"
	placeholder="" style="
    float: right;
">
<!-- END menu_create -->
<!-- BEGIN menu_leave -->
<input type="button" value="Leave Clan" class="globaltab" onclick="parent.location='./clans?action=leave'"
	placeholder="" style="
    float: right;
">
<!-- END menu_leave -->
<!-- BEGIN menu_app -->
<input type="button" value="Manage Application" class="globaltab"
	onclick="parent.location='./clan/profile/{NAME}?action=edit-application'" placeholder="" style="
    float: right;
">
<!-- END menu_app -->
<!-- BEGIN menu_invit -->
<p style="float: right;"><input type="button" value="Manage Invitations" class="globaltab"
		onclick="parent.location='./clan/profile/{NAME}?action=invitations'" placeholder="">{ITOTAL}</p>
<!-- END menu_invit -->
<!-- BEGIN catalog -->
<br class="clearfix" />
<h2 class="header" style="text-align: left;">Clans
	<span class="fl-r"><input type="text" id="myClan" placeholder="Search for a clan" style="margin-top: 2px;"></span>
</h2>
<div class="content">
	<!-- BEGIN sponsor -->
	<div class="sponsor-container">
		<h2>Sponsored Clans</h2>
		<div class="prevClan">⥢</div>
		<div class="nextClan">⥤</div>
		<div class="clan-container">{SPONSORED}</div>
	</div><br>
	<!-- END sponsor -->
	{CLANS}
</div>
<!-- BEGIN clantemplate -->
<div>
	<div class="clan-container{MYCLAN}">
		<div class="clanbanner" {BANNER}></div>
		{AVATAR}
		<span class="clanabv">{ABR}</span>
		<span class="clanspot">{SPOT}</span>
		<h2 title="{NAME}"><a href="{LINK}">{NAME}</a></h2>
		<br class="clearfix">
		<p>Ratio: {RATIO} ( {WR}% winrate )<br> XP: {XP} xp <span
				style="position: absolute; top:0px;left: 200px;">{BC}<img
					src="https://www.anime-blast.com/tpl/default/img/gold.png" style="width: 30px;"></span> <span
				class="fl-r" style="position:absolute;right:0px;top:0px;">Clan leader(s): {LEADERS}</span></p>
		<!-- BEGIN application -->
		<span class="clanview"><a href="{LINK}?action=application">Apply for {NAME}</a></span>
		<!-- END application -->
		<br class="clearfix">
	</div><br class="clearfix">
</div>
<!-- END clantemplate -->
<!-- END catalog -->
<!-- BEGIN information -->
<div class="content">
	<h2 class="header">{NAME}</h2>
	<div class="clan-banner" {BANNER}></div>
	<div class="transparent">
		<div class="fl-l">
			{AVATAR}
		</div>
		<p class="navfont" style="text-align: right;">Clan Information</p>
		<p class="color normal">Clan Name: <span class="edit name">{NAME}</span></p>
		<p class="color alternate">Abbreviation: <span class="edit abv">{AB}</span></p>
		<p class="color normal">Creator of the clan: {CREATOR}</p>
		<p class="color alternate">Registered: {REGISTER}</p>
	</div>
	<br class="clearfix">
	<h2 class="header" style="text-align: left;margin-bottom: 0;">BIOGRAPHY</h2>
	<p class="description">
		{DESCRIPTION}
	</p>
	<br class="clearfix">
	<p class="dots4u">
		<img src="./tpl/default/img/read.png" class="me" style="width:25px;">Clan Ladder Statistics
	</p>
	<br>
	<p class="color normal" style="
	padding-top: 5px;
	padding-bottom: 5px;
    ">Level:</p>{LEVEL}
	<p class="color alternate">Experience Points: {EXPERIENCE}</p>
	<p class="color normal">Ladder Rank: {LADDER}</p>
	<p class="color alternate">Wins: {WINS}</p>
	<p class="color normal">Loses: {LOSES}</p>
	<p class="color alternate">Win Percentage: {WR}</p>

	<h2 class="header" style="text-align: left;">MEMBERS</h2>
	{MEMBERS}
	<!-- BEGIN rank -->
	<div>
		<p class="clan-rank">{CLANRANK}
			<span class="role">Based on {ROLE}</span>
		</p>
		<p class="join-date">Join Date</p>
		<!-- BEGIN member -->
		<p id="{ID}">{RANK} {NAME}<span class="join-member">{DATE}</span></p>
		<!-- END member -->
	</div>
	<!-- END rank -->
</div>
<!-- END information -->
<!-- BEGIN edit-app -->
<div class="content">
	<h2 class="header">{ICON} {NAME} &gt; EDIT Application</h2>
	<div class="transparent"></div>
	<h2 class="header resize">Application Settings</h2>
	<div class="description">
		<form method="post" enctype="multipart/form-data">
			<p>Enable application: <select name="application">{APPLICATION}</select><br>
				Require application: <select name="requires">{REQUIRED}</select></p>
			<input type="submit" name="save-options" value="Save Changes">
		</form>
	</div><br class="clearfix">
	<h2 class="header resize">Below you can manage the application to your clan</h2>
	<div class="description{DISABLED}" style="text-align: center;">
		<form method="post" enctype="multipart/form-data">
			<textarea name="brief" style="margin: 0px; width: 629px; height: 127px;"
				placeholder="Here you can include a message for the applicant">{BRIEFING}</textarea>
			<br class="clearfix"><br class="clearfix">
			<input type="submit" name="save" value="Save Changes">
		</form>
		<hr>

		<input type="button" value="Add Question" class="globaltab"
			onclick="parent.location='./clan/profile/{NAME}?action=add-question'" style="
    float: right;
    background: #5195be;
    box-shadow: 0px 1px 4px 0px black;
    /* padding-bottom: 0; */
    margin-bottom: 0;
    border-bottom: none;
    border: 1px solid grey;">
		<br class="clearfix">

		{QUESTIONS}
		<!-- BEGIN question -->
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="key" value="{KEY}">
			<input type="text" name="question" style="margin-left: 5px;" value="{VALUE}">
			Textarea <input type="checkbox" name="textarea" style="margin-left: 5px;" value="true" {TEXT}>
			Input <input type="checkbox" name="input" style="margin-left: 5px;" value="true" {INPUT}>
			<input type="submit" name="edit-question" value="Save">
			<input type="submit" name="edit-question" value="Delete" style="margin-left: 5px;">
		</form>
		<!-- END question -->

		<br class="clearfix">
	</div>
</div>
<!-- END edit-app -->

<!-- BEGIN invitation -->
<div class="content">
	<h2 class="header">{ICON} {NAME} &gt; Invitations</h2>
	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Applications</h2>
	<div class="description">
		<p class="userleft">Username</p>
		<p class="userright">Submitted</p>
		{APPLICATIONS}
		<!-- BEGIN applicated -->
		<p class="color normal clearfix"><img src="https://upload.wikimedia.org/wikipedia/commons/9/9d/Arrow-down.svg"
				style="width: 15px;">{MEMBER}<span class="join-member">{DATE}</span></p>
		<div class="app-options">
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{ID}">
				<input type="submit" name="submit" value="Close">
				<input type="submit" name="submit" value="Accept">
			</form>
		</div>
		</p>
		<div class="app-container">
			<p style="width: 15%;float: left;">{ICON}</p>
			<p>{APP}</p>
		</div>
		<!-- END applicated -->
	</div>

	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Invitations</h2>
	<div class="description">
		<p class="userleft">Username</p>
		<p class="userright">Submitted</p>
		{INVITES}
		<!-- BEGIN invited -->
		<form method="post" enctype="multipart/form-data">
			<p class="color normal clearfix"> {MEMBER} <span class="join-member">{DATE}</span></p>
			<input type="hidden" name="invite" value="{ID}">
			<input type="submit" name="delete-invite" value="Delete Invite" style="
    float: right;
    display: inline-block;
    margin-top: -16px;
    padding: 0;
    font-size: 11px;">
		</form>
		<!-- END invited -->
		<br class="clearfix">
		<div style="text-align:center;">
			{ERROR}
			<form method="post" enctype="multipart/form-data">
				Invite: <input type="text" name="member">
				<br><br>
				<input type="submit" name="invite" value="Submit invitation">
			</form>
		</div>
	</div>
</div>
<!-- END invitation -->

<!-- BEGIN applicant -->

<div class="content">
	<h2 class="header">{AVATAR}{NAME} &gt; Application</h2>
	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Please fill out
		the bottom application</h2>
	<div class="description">
		{MESSAGE}
		<hr>
		<br class="clearfix">
		<center>
			<form method="post" enctype="multipart/form-data">
				{QUESTIONS}<br class="clearfix"><br class="clearfix">
				<input type="submit" name="Submit" value="Submit Application">
			</form>
		</center>
	</div>
</div>
<!-- END applicant -->
<div class="content">
	<h2 class="header">Ladders</h2>
	<p>Here you can find separate statistic categories of your personal performance in completed ladder matches in the
		&lt;website name&gt; game. Every new &lt;website name&gt; season these ladder statistics are reset!</p>
	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">General Ladder
	</h2>
	<div class="description">
		<p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;
"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;
">Ladder Statistics</p>

		<div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;
">
			<p style="
    width: 22%;
    float: left;
"><img src="https://i.ytimg.com/vi/yBoKZXxTVM4/hqdefault.jpg" alt="User Avatar" class="avatar" style="
    border: 1px solid black;
    width: 130px;
"></p>
			<p> Compare your ladder match skill to other individual players here. Players who have played at least one
				ladder game can be found here within this ladder. At the end of each season, the top players within this
				ladder will be rewarded.</p>
		</div>
	</div>
	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Clan Ladder</h2>
	<div class="description">
		<p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;
"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;
">Clan Ladder Statistics</p>

		<div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;
">
			<p style="
    width: 22%;
    float: left;
"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTqelICCA-8g8ZP7wYNvs5vZtWVhnLp35mLFxfTzdutcx2B6_Bt"
					alt="User Avatar" class="avatar" style="
    border: 1px solid black;
    width: 130px;
"></p>
			<p>Compare your clan and it's ladder match skill to other clans here.</p>
		</div>
	</div>
	<h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Country Ladder
	</h2>
	<div class="description">
		<p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;
"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;
">Country Ladder Statistics</p>

		<div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;
">
			<p style="
    width: 22%;
    float: left;
"><img src="https://images-na.ssl-images-amazon.com/images/I/71Mz4LfO2BL._AC_SY450_.jpg" alt="User Avatar"
					class="avatar" style="
    border: 1px solid black;
    width: 130px;
    height: 95px;
"></p>
			<p>Compare your country and it's ladder match skill to other countries around the world here.</p>
		</div>
	</div>
</div>

<!-- BEGIN create -->
<div class="content">
	<h2 class="header">Create your clan!</h2>
	<p>Below you can complete the form to register your clan on anime blast. There are exclusive benefits you can earn
		from being in a clan.</p>
	<center>
		{ERROR}
		<form method="post" enctype="multipart/form-data">
			Clan Icon: <input type="file" name="avatar"><br><br>
			Clan Name*: <input type="text" name="name" placeholder="Clan Name"><br><br>
			Clan Abbreviation*: <input type="text" name="abv" placeholder="Clan Abbreviation"><br><br>
			Leader Rank: <input type="text" name="default" placeholder="Clan Leader"><br><br>
			Clan Biography: <textarea name="bio" placeholder="Clan Biography or description"></textarea><br><br>
			<input type="submit" name="create" value="Create Clan">
		</form>
	</center>
</div>
<!-- END create -->

<!-- BEGIN settings -->
<div class="content">
	<h2 class="header">{NAME} > Settings</h2>
	<div class="transparent">
		<p>Here you can update clan settings. If a setting has <img
				src="https://www.anime-blast.com/tpl/default/img/gold.png" style="width: 30px;"> next to it, it requires
			BC. Clans have a common pool of BC that is earned by the members of the clan.</p>
		{ERROR}
		<div class="fl-l">
			<form method="post" enctype="multipart/form-data">
				<img class="change-avatar" src="./tpl/default/img/change.png">
				<input name="avatar" class="upload-avatar" style="display:none;" type="file">
				<img class="preview">
				<input type="submit" name="save-avatar" value="Save" placeholder=""
					style="position: absolute;z-index: 2;bottom: 15px;left: 20px;">
			</form>
			{AVATAR}
		</div>
		<form method="post" enctype="multipart/form-data">
			<p class="navfont" style="text-align: right;">Clan Information</p>
			<p class="color normal">Clan Name: <input type="text" name="name" class="clanname" value="{NAME}"> <input
					type="submit" class="clanname" value="Save" /> <span> 10000 <img
						src="https://www.anime-blast.com/tpl/default/img/gold.png"
						style="width: 30px;vertical-align: middle;"></span>
			<p class="color alternate">Abbreviation: <input type="text" name="abv" value="{AB}"> <input type="submit"
					class="clanname" value="Save" /> <span> 5000 <img
						src="https://www.anime-blast.com/tpl/default/img/gold.png"
						style="width: 30px;vertical-align: middle;"></span>
			<p class="color normal">Clan banner: <input type="text" name="banner" value="{BANNER}"
					placeholder="Clan banner to show on clan listing" /> <input type="submit" name="action"
					class="clanname" value="Remove" /> <input type="submit" class="clanname" value="Save" /><span> 1000
					<img src="https://www.anime-blast.com/tpl/default/img/gold.png"
						style="width: 30px;vertical-align: middle;"></span></p>
			<p class="color alternate" style="padding-left: 87px;">New Member Rank: {DEFAULT} <input type="submit"
					name="default" class="clanname" value="Save" /></p>


		</form>
	</div>
	<br class="clearfix">
	<h2 class="header" style="text-align: left;margin-bottom: 0;">BIOGRAPHY</h2>
	<div class="description">
		<form method="post" enctype="multipart/form-data">
			<center>
				<textarea name="biography" style="width:90%;" placeholder="Clan Biography">{DESCRIPTION}</textarea>
				<br class="clearfix"><br>
				<input type="submit" value="Save" />
			</center>
		</form>
	</div>
	<br class="clearfix">
	<h2 class="header" style="text-align: left;">MEMBERS<input type="button" value="Add Rank"
			onclick="parent.location='./clan/profile/{NAME}?action=add-rank'" style="
    float: right;
	color: white;
    background: #5195be;
    box-shadow: 0px 1px 4px 0px black;
    /* padding-bottom: 0; */
    margin-bottom: 0;
    border-bottom: none;
    border: 1px solid grey;"></h2>
	<div class="clan-sortable">
		{MEMBERS}
		<!-- BEGIN rank -->
		<div id="{ID}" class="clan-order">
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="rank" value="{ID}">
				<p class="clan-rank"><input type="text" name="clanrank" value="{CLANRANK}"><span class="role">Based on
						{ROLE}</span> <input type="submit" name="save" value="Save Changes" /></p>
			</form>
			<div id="{ID}" {SORTABLE}>
				{MEMBER}
				<!-- BEGIN member -->
				<div class="ui-state-default">
					<form method="post" enctype="multipart/form-data"><input type="hidden" name="member" value="{ID}">
						<p>{RANK} {NAME}
							<!-- BEGIN kick --><input type="submit" name="option" value="Kick"><!-- END kick --><span
								class="join-member">{DATE}</span>
						</p>
					</form>
				</div><!-- END member -->

			</div>
		</div>
		<!-- END rank -->
	</div>
</div>
<!-- END settings -->