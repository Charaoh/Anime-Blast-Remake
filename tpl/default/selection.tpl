		
		<div id="main_container">
		{POPUP}
			<div id="ingame">
			<p class="event"> EVENT 5x <img src="./tpl/{TPL}/css/images/gold.png" style="width: 30px;"></p>
			<div class="musicControls">
				<img class="minus" src="./tpl/{TPL}/css/images/minus.png" style="width: 10px;">
				<img class="toggleSound" src="./tpl/{TPL}/css/images/mute.png" style="width: 10px;">
				<img class="plus" src="./tpl/{TPL}/css/images/plus.png" style="width: 10px;">
			</div>
				<div id="character_selected">
				<div class="sel_info_top">
					<div class="skill_tree">
					</div>
				</div>
				<br>
				<div class="character_information">
					<h2>Deidara (S)</h2>
					<p>Character description</p>
				</div>
				<div class="character_stats">
					<p style="color: #45bc59;">HP: 100</p>
					<p style="color: #32c9e9;">Mana: 100</p>
				</div>
				</div>
                <div id="sel_buttons">
				<div id="btn"  class="private" style="opacity: 0.6; url(images/cursor.png), auto/* margin-left: 105px; */">
					<img src="./tpl/{TPL}/css/images/private.png">
					<a href="{URL}?s=game&amp;mode=selection#">Private Match</a>
				</div>
				<div id="btn"  class="ladder" style="opacity: 0.6; cursor: url(images/cursor.png), auto">
					<img src="./tpl/{TPL}/css/images/ladder.png">
					<a href="{URL}?s=game&amp;mode=selection#">Ladder Match</a>
				</div>
				<div id="btn" class="quick" style="opacity:0.6; cursor: url(images/cursor.png), auto;">
					<img src="./tpl/{TPL}/css/images/quick.png">
					<a href="{URL}?s=game&amp;mode=selection#">Quick Match</a>
				</div>
				<div id="btn" style="opacity: 1;">
					<img src="./tpl/{TPL}/css/images/logout.png">
					<a href="{URL}?s=logout">Log out</a>
				</div>
				</div>
				<br class="clearfix">
				<div class="sel_left">
					<div id="user_information_wrapper">
					<div id="user_information_left">
						{AVATAR}
					</div>
					<div id="user_information_right">
						<h2 title="{USERNAME}">{SHORTY}</h2>{RANK}
						<br><p>{GROUP}</p>
					</div>
					</div>
					<div id="user_statistics">
					<p>{LR}</p>
					<br class="clearfix"/>
					<p>
						<img src="./tpl/{TPL}/css/images/ratio.png" style="width: 18px;float: left;margin-right: 5px;">
						{RATIO}
					</p>
					<br class="clearfix"/>
					<p>
						<img src="./tpl/{TPL}/css/images/gold.png" style="width: 30px;float: left;    margin-left: -3px;">
						{GOLD}
					</p>
					</div>

					<p class="char_list_text clearfix">Awaiting overwrite...</p>
					<div id="droppable_slots" class="current_team">
						<div class="slot 1">1</div>
						<input type="hidden" name="s0" value="">
						<div class="slot 2">2</div>
						<input type="hidden" name="s1" value="">
						<div class="slot 3">3</div>
						<input type="hidden" name="s2" value="">
					</div>
				</div>
				<div class="sel_right">
				<div class="filter_container">
					{FILTERS}
					</div><br/>
				<div id="draggable" class="character_list">
				{CHARACTERS}
					
				</div>

			</div>
			
			
                                        </div>
			
		</div>