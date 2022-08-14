<div id="selection" {BG}>
	<div class="landscape"><img src="./tpl/beta/css/images/mobile.png"></div>
	{POPUP}
	<div class="tips">{TIPS}</div>
	<div class="inventoryContainer alts" tabindex="-1">
		<div class="containerInventory" role="dialog" aria-modal="true">
			<div class="contentTitle">
				<img src="https://www.anime-blast.com/tpl/christmas/css/images/NK8Vnlb.png" class="headerInventory">
				<span class="pool">
					<img src="https://www.anime-blast.com/tpl/christmas/css/images/cookie.png"> {COOKIES}
				</span>
				<span class="close-inventory">Close</span>
			</div>
			<div class="inventoryContent">
				{INVENTORY}
			</div>
		</div>
	</div>

	<!-- BEGIN event -->
	<!--<div class="christmasImage alts" tabindex="-1">
    <div class="containerImage" role="dialog" aria-model="true">  
    <div class="contentImage">
    	<p> "Welcome to Anime-Blast's [belated] Christmas Event! Assemble a team of your strongest characters and face off against the mighty Saint Nicholas and his Happy Helpers, 
        dodge his axes, and avoid getting covered in snow! Successfully defeating Saint Nicholas will yield Christmas Cookies which can be redeemed for special prizes in Santa's Shop, 
        found in the 'Shop' tab. Test your luck against Saint Nicholas, or the random loot boxes!"
        </p>
        </div>
    	<span class="close-image">Close</span>
	</div>
    </div>-->
	<!-- END event -->
	<div class="settings alts" tabindex="-1">
		<div class="container" role="dialog" aria-modal="true">
			<div class="content-title">MENU<span class="close-menu">Close</span></div>
			<div class="setup">
				<div class="skip">
					<img src="./tpl/beta/css/images/skipTrack.png" class="prevTrack">SKIP BGM
					<img src="./tpl/beta/css/images/skipTrack.png" class="nextTrack">
				</div>
				<p>Music Volume</p>
				<p class="musicControls buffering">Buffering sound</p>
				<div class="musicControls overlay player">
					<span class="minus"><img style="padding-top:4px" src="./tpl/beta/css/images/volumeDOWN.png"></span>
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.2" class="flip ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.4" class="ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.6" class="flip ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.8" class="ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="1" class="flip mute ratio">
					<img src="./tpl/beta/css/images/volumeUP.png" class="plus">
				</div>
				<br class="clearfix">
				<p>SFX Volume</p>
				<div class="musicControls Sounds">
					<span class="minus"><img style="padding-top:4px" src="./tpl/beta/css/images/volumeDOWN.png"></span>
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.2" class="flip ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.4" class="ratio">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.6" class="flip ratio mute">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="0.8" class="ratio mute">
					<img src="./tpl/beta/css/images/triangle.png" data-volume="1" class="flip mute ratio">
					<img src="./tpl/beta/css/images/volumeUP.png" class="plus">
				</div>
				<br class="clearfix">
				<!--<select class="select gui"><option value="0">Change GUI</option>{TEMPLATES}</select>
				<img src="./tpl/beta/css/images/downtriangle.png" class="down">
				<br class="clearfix">-->
				<select class="select sfx">
					<option value="0">Change SFX</option>{SFX}
				</select>
				<img src="./tpl/beta/css/images/downtriangle.png" class="down">
				<br class="clearfix">
				<p>Change Selection BG</p>
				<input type="text" class="input selection" value="{CSBG}" placeholder="URL of your desired BG">
				<p>Change INGAME BG</p>
				<input type="text" class="input ingame" value="{CIBG}" placeholder="URL of your desired BG">
			</div>
		</div>
	</div>
	<span class="menu"><span></span>MENU</span>
	<span class="inventory"><img src="./tpl/christmas/css/images/Inventory.png" title="Your Inventory"></span>
	<div id="menu">
		<div class="item management">
			<a href="https://www.anime-blast.com/selection#">Teams</a>
		</div>
		<div class="item play active">
			<a href="https://www.anime-blast.com/selection#"
				style="transform: perspective(2px) rotateX(0.9deg);">Play</a>
		</div>
		<div class="item shop">
			<a href="https://www.anime-blast.com/selection#">Shop</a>
		</div>
	</div>
	<div id="left">
		<div class="member">
			<div class="avatar_container">
				{AVATAR}
			</div>
			<div class="details">
				<div class="username">
					<a href="./profile/{USERNAME}" target="_blank" title="{USERNAME}">{SHORTY}</a>
				</div>
				<br class="clearfix">
				<div class="group">
					<a href="./the-team" target="_blank">{GROUP}</a>

				</div>
			</div>
		</div>
	</div>
	<div id="middle">
		<div class="play-2" style="display: block;">
			<div class="option private">
				<span class="hover"></span>
				<div>
					<img class="oimage one" src="./tpl/beta/css/images/b-1.png?99999999999999999999999">
					<img class="gradient" src="./tpl/beta/css/images/gradient2.png">
				</div>
				<a href="#">Private</a>
			</div>
			<div class="option ladder">
				<span class="hover"></span>
				<div>
					<img class="oimage two" src="./tpl/beta/css/images/b-2.png?99999999999999999999999">
					<img class="gradient" src="./tpl/beta/css/images/gradient2.png">
				</div>
				<a href="#">Ranked</a>
			</div>
			<div class="option quick">
				<span class="hover"></span>
				<div>
					<img class="oimage three" src="./tpl/beta/css/images/b-3.png?99999999999999999999999">
					<img class="gradient" src="./tpl/beta/css/images/gradient2.png">
				</div>
				<a href="#">Quick</a>
			</div>
			<!-- BEGIN AI -->
			<div class="option ai" style="opacity: 1;">
				<span class="hover" style="display: inline-block;"></span>
				<div>
					<img class="oimage" src="./tpl/christmas/css/images/event.png?99999999999999999999999"
						style="display: block;">
					<img class="gradient" src="./tpl/beta/css/images/gradient2.png">
				</div>
				<a class="modeText" href="#">BOSS</a>
				<div class="levels">
					<h4 class="difficultyText"
						title="Click a difficulty below to assign the AI. Each mode has a difference, be sure to highlight to see what changes.">
						<span>AI Difficulty</span>
					</h4>
					<div class="difficulty one"
						title="Can you beat Pain before he beats you, give it a try! Earn a chance to obtain 1 narutomaki to use at Pains Domain shop.">
						<p>EASY</p>
					</div>
					<div class="difficulty two"
						title="The same benefit applies here. Except you are certified to get 1 to 2 narutomakis, however the AI's will have infinite mana and no cooldowns.">
						<p>MEDIUM</p>
					</div>
					<div class="difficulty three"
						title="You will gain 3 narutomakis upon victory.. however the AI's have the same benefits as the above, but can use up to 3 skills per turn.">
						<p>LEGEND</p>
					</div>
				</div>
			</div>
			<!-- END AI -->
		</div>
		<div class="shop-2">
			<div class="title" style="transform: skewx(0deg);margin-left: -41px;">
				<span style="transform: skewX(30deg);left: 50%;">SHOP</span>
			</div>
			<img src="./tpl/christmas/css/images/pain3.png" class="render-shop">
			<span class="pool"><img src="https://www.anime-blast.com/tpl/default/img/gold.png">{GOLD}</span>
			<p class="backbtn">Back</p>
			<div class="sales">
				<div class="sale" data-rel="characters">
					<div class="categorybtn"></div>
					<p>Characters</p><img src="./tpl/beta/css/images/chars.png" class="fl-l">
				</div>
				<div class="sale" data-rel="gui">
					<div class="categorybtn"></div>
					<p>GFX</p><img src="./tpl/beta/css/images/gfx.png" class="fl-l">
				</div>
				<div class="sale" data-rel="sfx">
					<div class="categorybtn"></div>
					<p>SFX</p><img src="./tpl/beta/css/images/sfx.png" class="fl-l">
				</div>
				<div class="sale" data-rel="misc">
					<div class="categorybtn"></div>
					<p>Pain's Domain</p><img src="./tpl/christmas/css/images/painShop2.png" class="fl-l">
				</div>
				<div id="characters" class="characters" style="display:none;">
					<p class="ehead"><img
							src="https://emojis.slackmojis.com/emojis/images/1579216111/7550/pikachu_wave.gif?1579216111"
							class="emoji">Characters</p>
					{CSALES}
				</div>
				<br class="clearfix">
				<div id="gui" style="display:none;">
					<p class="ehead"><img
							src="https://emojis.slackmojis.com/emojis/images/1580448086/7667/think-about-it.png?1580448086"
							class="emoji">GFX Packs</p>
					{GSALES}
				</div>
				<br class="clearfix" style="display:none;">
				<div id="sfx" style="display:none;">
					<p class="ehead"><img
							src="https://emojis.slackmojis.com/emojis/images/1586361156/8558/coffin_dance.gif?1586361156"
							class="emoji">SFX Packs</p>
					{SSALES}
				</div>
				<div id="misc" style="display:none;transform: skewX(30deg);">
					<h2 class="headerSantaShop">Pain's Domain</h2>
					<p class="pSantaShop">Defeat The Six Paths of Pain and win Narutomaki or a character</p>
					<span class="pool"><img src="https://www.anime-blast.com/tpl/christmas/css/images/cookie.png"
							style="vertical-align: bottom;"> {COOKIES}</span>
					<span class="eventPack" style="margin-left: 35%;display: block;">
						<h2>Cookie Conversion</h2>
						<span class="eventItem" id="2" title="This box gives 10000 BC for 20 Narutomaki">
							<span class="itemHover cookies"></span>
							<span><img
									src="https://www.anime-blast.com/tpl/christmas/css/images/bc_to_cookie_guarantee.png"></span>
							<span class="cost">
								<img src="https://www.anime-blast.com/tpl/christmas/css/images/cookie.png"
									style="width: 30px;vertical-align: bottom;"> 20
							</span>
						</span>
					</span>
					</span>
				</div>

			</div>
			<div class="buy-out">
				<span></span><img src="./images/characters/slanted/default.png">
				<p>BUY</p>
			</div>
		</div>
		<div class="management-2">
			<div class="title">
				<span>TEAM MANAGEMENT</span>
			</div>
			<div class="character_list">{CHARACTERS}</div>
			<div class="filters" style="display: block;">
				<img class="close" src="./tpl/beta/css/images/close.png">
				<h2 style="margin-left: 50%;margin-top: 10px;">Filters</h2>
				<h2 class="sub-header">Animes</h2>
				<div>{ANIMES}</div>
				<h2 class="sub-header" style="margin-left: 35%;">Classes</h2>
				<div style="margin-left: 25%;width: 210px;">{CLASSES}</div>
				<br class="clearfix">
				<h2 class="sub-header" style="margin-left: 18%;">Effects</h2>
				<div class="effects-list" style="margin-left: 15%;width: 311px;">{EFFECTS}</div>
			</div>
			<img class="open" src="./tpl/beta/css/images/open.png">
		</div>
		<div class="character-page">
			<div class="character-show">
				<p class="character-avatar">
					<span></span>
				</p>
				<div class="character-options">
				</div>
			</div>
			<h2 class="header">Natsu Dragneel</h2>
			<span class="close-character">Close</span>
			<div class="skill-list">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
			</div>
			<div class="alts skill-list">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
				<img src="https://www.anime-blast.com//images/skills/128.jpg" class="skill">
			</div>
			<p class="description">A mage of Fairy Tail Guild and the protagonist of the show, Natsu is a Dragon Slayer
				with the ability to use fire-based magic to scorch his enemies, while also being able to consume their
				own fire magic, as he is immune to flames.</p>
			<div class="character-page-bottom">
				<p class="cooldown">Cooldown: 1</p>
				<p class="costs">Mana: 1000 </p>
				<img src="https://www.anime-blast.com//images/classes/Energy.png" class="skill-class">
			</div>
		</div>
	</div>
	<div id="bottom">
		<div class="stats active stay" style="left: 127px;">
			<div class="content">
				<p>{RNAME}</p>
				<p>{RATIO}</p>
				<p class="pool"><img src="https://www.anime-blast.com/tpl/default/img/gold.png"
						style="width: 20px;">{GOLD}</p>
				<br><br>
			</div>
		</div>
		<div class="team">
			<hr class="hr">
			<hr class="hr two">
			<p class="quicky">
				<span class="inside shadow">
					<img src="./tpl/beta/css/images/team.png" style="width: 40px;transform: skewX(30deg);">
				</span>
			</p>

			<p class="char_list_text">Awaiting overwrite...</p>
			<div id="droppable_slots" class="current_team">
				<div class="slot 1">1</div>
				<input type="hidden" name="s0" value="">
				<div class="slot 2">2</div>
				<input type="hidden" name="s1" value="">
				<div class="slot 3">3</div>
				<input type="hidden" name="s2" value="">
			</div>
			<div class="statistics">
				<p class="overlay" style="display: block;"></p>
				<img src="./tpl/beta/css/images/statistics3.png">
			</div>
		</div>

		<div class="teams active stay" style="right: 146px;">
			<div class="content two">
				<img class="shuffle" src="./tpl/beta/css/images/shuffle.png">
				<div class="current_equiped" style="margin-top: -20px;">
					{EQUIPED}
				</div>
				<div class="team_list">
					{TEAMS}
				</div>
			</div>
		</div>
	</div>
</div>