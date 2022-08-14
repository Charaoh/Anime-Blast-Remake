<!-- BEGIN menu_manage -->
<input type="button" value="Clan Settings" class="globaltab my-1"
    onclick="parent.location='./clan/profile/{NAME}?action=settings'" placeholder="" style="
    float: right;">
<!-- END menu_manage -->
<!-- BEGIN menu_goback -->
<input type="button" value="Back to Clans" class="globaltab my-1" onclick="parent.location='./clans'" placeholder=""
    style="
    float: right;">
<!-- END menu_goback -->
<!-- BEGIN menu_gobackclan -->
<input type="button" value="Clan Panel" class="globaltab my-1" onclick="parent.location='./clan/profile/{NAME}'"
    placeholder="" style="
    float: right;">
<!-- END menu_gobackclan -->
<!-- BEGIN menu_create -->
<input type="button" value="Create a Clan" class="globaltab my-1" onclick="parent.location='./clans?action=create'"
    placeholder="" style="
    float: right;">
<!-- END menu_create -->
<!-- BEGIN menu_leave -->
<input type="button" value="Leave Clan" class="globaltab my-1" onclick="parent.location='./clans?action=leave'"
    placeholder="" style="
    float: right;">
<!-- END menu_leave -->
<!-- BEGIN menu_app -->
<input type="button" value="Manage Application" class="globaltab my-1"
    onclick="parent.location='./clan/profile/{NAME}?action=edit-application'" placeholder="" style="
    float: right;">
<!-- END menu_app -->
<!-- BEGIN menu_invit -->
<input type="button" value="Manage Invitations" class="globaltab my-1"
    onclick="parent.location='./clan/profile/{NAME}?action=invitations'" placeholder="" style="
float: right;">{ITOTAL}
<!-- END menu_invit -->




<!-- BEGIN catalog -->
<br class="clearfix" />
<h2 class="header" style="text-align: center;">Anime Blast Clans</h2>
<p class="wordbreak px-1">Here you can find current clans on Anime Blast, or create your own!
    <span data-tooltip="Be wary, some clans have applications, so read carefully!" data-tooltip-persistent=""><i
            class="fas fa-search-plus"></i></span>
</p>

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
<div id="clanmain" class="shadow-lg rounded my-1 {MYCLAN}">
    <img src="{BANNER}" class="img-fluid card-img" alt="">
    <hr>
    {AVATAR}
    <span class="clanspot">{SPOT}</span>
    <div class="row align-items-center">
        <div class="col">
            <h2 style="font-size:13px; font-weight:bold; color:#9c1120;" title="{NAME}"
                class="h2 text-center text-light"><a href="{LINK}">{NAME}</a></h2>
            <hr>
            <div class="container">
                <div class="row-md-6 text-center flex-fill">
                    <span class="badge bg-custom text-light">
                        Creator
                        <span class="badge bg-secondary">
                            {LEADERS}
                        </span>
                    </span>
                </div>
                <div class="row-md-6 text-center flex-fill">
                    <span class="badge bg-custom text-light">
                        Symbol
                        <span class="badge bg-secondary">
                            {ABR}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid align-items-center">
        <div class="col align-items-center">
            <hr>
            <ul class="list-group list-group-vertical-lg">
                <li class="badge bg-custom text-center flex-fill my-1">
                    Ratio: {RATIO}</li>
                <li class="badge bg-custom text-center flex-fill my-1">
                    {WR}% KDR</li>
            </ul>
            <ul class="list-group list-group-horizontal-lg">
                <li style="line-height:2px" class="badge bg-custom text-center flex-fill my-1">
                    <img src="https://www.anime-blast.com/tpl/default/img/gold.png" style="width: 30px;">{BC}
                </li>
            </ul>


            <!-- BEGIN application -->
            <span class="clanview"><a href="{LINK}?action=application">Apply for {NAME}</a></span>
            <!-- END application -->
        </div>
    </div>
</div>
<!-- END clantemplate -->
<!-- END catalog -->
<!-- BEGIN information -->
<div class="container">
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="cover-image" {BANNER}>
                        </div>
                        <div class="user-image">
                            {AVATAR}
                        </div>
                    </div>

                    <div class="profile-content">
                        <div class="profile-name">{NAME}</div>
                        <div class="profile-designation">{AB}</div>
                        <p class="profile-description">{DESCRIPTION}</p>
                        <div class="profile-info-list">
                            <span class="profile-info-list-item">Level<span class="ml-2">{LEVEL}</span></span>
                            <span class="profile-info-list-item">Experience<span class="ml-2">{EXPERIENCE}</span></span>
                            <span class="profile-info-list-item">Ladder Rank<span class="ml-2">{LADDER}</span></span>
                            <span class="profile-info-list-item">Ratio<span class="ml-2">{WR}</span></span>
                            <span class="profile-info-list-item">Wins<span class="ml-2">{WINS}</span></span>
                            <span class="profile-info-list-item">Loses<span class="ml-2">{LOSES}</span></span>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-description">
                        <div class="title">Clan Information</div>
                        <div class="about-items">
                            <span class="about-item-name ">Clan Creator:</span> <span
                                class="about-item-detail">{CREATOR}</span><br>
                            <span class="about-item-name">Clan Name:</span> <span
                                class="about-item-detail">{NAME}</span><br>
                            <span class="about-item-name ">Clan Symbol:</span> <span
                                class="about-item-detail">{AB}</span><br>
                            <span class="about-item-name">Date Created:</span> <span
                                class="about-item-detail">{REGISTER}</span><br>
                        </div>
                    </div>

                    <div class="card-description">
                        <div class="title">Clan Roster</div>
                        <div class="row-fluid align-items-center">
                            <div class="col align-items-center about-items">
                                {MEMBERS}
                                <!-- BEGIN rank -->
                                <hr>
                                <ul class="list-group list-group-horizontal-lg">
                                    <li class="badge bg-custom text-center flex-fill my-1">
                                        {CLANRANK}<span class="about-item-rankfo">Based on {ROLE}</span>
                                    </li>

                                    <br>
                                    <!-- BEGIN member -->
                                    <li class="badge bg-custom text-center flex-fill my-1">Joined On<span
                                            class="about-item-rankfo">{DATE}</span>
                                    </li>
                                    <br>
                                    <li class="badge bg-custom text-center flex-fill my-1">
                                        <span id="{ID}" class="about-item-rankmem"> {RANK}
                                            {NAME}</span>
                                    </li>
                                    <!-- END member -->
                                </ul>
                                <!-- END rank -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
                <input type="submit" class="globaltab" name="save-options" value="Save Changes">
            </form>
        </div><br class="clearfix">
        <h2 class="header resize">Below you can manage the application for your clan.</h2>
        <div class="description{DISABLED}" style="text-align: center;">
            <form method="post" enctype="multipart/form-data">
                <textarea name="brief" style="color: white; margin: 0px; width: 100%; height: 125px;"
                    placeholder="Here you can include a message for the applicant">{BRIEFING}</textarea>
                <br class="clearfix">
                <input type="submit" class="globaltab" name="save" value="Save Changes">
            </form>
            <br class="clearfix">
            <hr class="my-2" style="height:100%;">

            <input type="button" value="Add Question" class="globaltab"
                onclick="parent.location='./clan/profile/{NAME}?action=add-question'"
                style="float: right; white;margin-bottom: 0;">
            <br class="clearfix">

            {QUESTIONS}
            <!-- BEGIN question -->
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="key" value="{KEY}">
                <input type="text" name="question" style="margin-left: 5px;" value="{VALUE}">
                Textarea <input type="checkbox" name="textarea" style="margin-left: 5px;" value="true" {TEXT}>
                Input <input type="checkbox" name="input" style="margin-left: 5px;" value="true" {INPUT}>
                <input type="submit" class="globaltab mx-1" name="edit-question" value="Save">
                <input type="submit" class="globaltab" name="edit-question" value="Delete">
            </form>
            <!-- END question -->

            <br class="clearfix">
        </div>
    </div>
    <!-- END edit-app -->

    <!-- BEGIN invitation -->
    <div class="content">
        <h2 class="header">{ICON} {NAME} &gt; Invitations</h2>
        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">
            Applications
        </h2>
        <div class="description my-1">
            <p class="userleft">Username</p>
            <p class="userright">Created On</p><br>
            {APPLICATIONS}
            <!-- BEGIN applicated -->
            <p class="color normal clearfix"><img
                    src="https://upload.wikimedia.org/wikipedia/commons/9/9d/Arrow-down.svg"
                    style="width: 15px;">{MEMBER}<span class="join-member">{DATE}</span></p>
            <div class="app-options">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{ID}">
                    <input type="submit" class="globaltab" name="submit" value="Close">
                    <input type="submit" class="globaltab" name="submit" value="Accept">
                </form>
            </div>
            </p>
            <div class="app-container">
                <p style="width: 15%;float: left;">{ICON}</p>
                <p>{APP}</p>
            </div>
            <!-- END applicated -->
        </div>

        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">
            Pending Invitations
        </h2>
        <div class="description my-1">
            <p class="userleft">Username</p>
            <p class="userright">Created On</p><br>
            {INVITES}
            <!-- BEGIN invited -->
            <form method="post" enctype="multipart/form-data">
                <p class="color normal clearfix"> {MEMBER} <span class="join-member">{DATE}</span></p>
                <input type="hidden" class="globaltab" name="invite" value="{ID}">
                <input type="submit" class="globaltab" name="delete-invite" value="Delete Invite"
                    style="float: right;display: inline-block;margin-top: -16px;padding: 0;font-size: 11px;">
            </form>
            <!-- END invited -->
            <br class="clearfix">
            <div style="text-align:center;">
                {ERROR}
                <form method="post" enctype="multipart/form-data">
                    Invite: <input type="text" name="member">
                    <br><br>
                    <input type="submit" class="globaltab" name="invite" value="Submit invitation">
                </form>
            </div>
        </div>
    </div>
    <!-- END invitation -->

    <!-- BEGIN applicant -->

    <div class="content">
        <h2 class="header">{AVATAR}{NAME} &gt; Application</h2>
        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Please
            fill
            out
            the bottom application</h2>
        <div class="description">
            {MESSAGE}
            <br class="clearfix">
            <hr class="my-2">
            <br class="clearfix">
            <div class="justify-content-center">
                <form method="post" enctype="multipart/form-data">
                    {QUESTIONS}<br class="clearfix"><br class="clearfix">
                    <input type="submit" class="globaltab" name="Submit" value="Submit Application">
                </form>
            </div>
        </div>
    </div>
    <!-- END applicant -->
    <div class="content">
        <h2 class="header">Ladders</h2>
        <p>Here you can find separate statistic categories of your personal performance in completed ladder matches
            in
            the
            &lt;website name&gt; game. Every new &lt;website name&gt; season these ladder statistics are reset!</p>
        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">General
            Ladder
        </h2>
        <div class="description">
            <p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;">Ladder Statistics</p>

            <div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;">
                <p style="
    width: 22%;
    float: left;"><img src="https://i.ytimg.com/vi/yBoKZXxTVM4/hqdefault.jpg" alt="User Avatar" class="avatar" style="
    border: 1px solid black;
    width: 130px;"></p>
                <p> Compare your ladder match skill to other individual players here. Players who have played at
                    least
                    one
                    ladder game can be found here within this ladder. At the end of each season, the top players
                    within
                    this
                    ladder will be rewarded.</p>
            </div>
        </div>
        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Clan
            Ladder
        </h2>
        <div class="description">
            <p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;">Clan Ladder Statistics</p>

            <div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;">
                <p style="
    width: 22%;
    float: left;"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTqelICCA-8g8ZP7wYNvs5vZtWVhnLp35mLFxfTzdutcx2B6_Bt"
                        alt="User Avatar" class="avatar" style="
    border: 1px solid black;
    width: 130px;"></p>
                <p>Compare your clan and it's ladder match skill to other clans here.</p>
            </div>
        </div>
        <h2 class="header" style="text-align: left;margin-bottom: 0;text-transform: none;font-size: 14px;">Country
            Ladder
        </h2>
        <div class="description">
            <p style="
    width: 99%;
    float: left;
    background: #497a99bf;
    padding-left: 5px;
    border-bottom: 1px dotted black;"><img src="https://image.flaticon.com/icons/svg/32/32213.svg" style="
    width: 9px;">Country Ladder Statistics</p>

            <div style="
    padding: 5px;
    color: black;
    clear: both;
    height: 100%;
    background: #ffffffe8;
    height: 100px;
    border-bottom: 1px dotted black;">
                <p style="
    width: 22%;
    float: left;"><img src="https://images-na.ssl-images-amazon.com/images/I/71Mz4LfO2BL._AC_SY450_.jpg"
                        alt="User Avatar" class="avatar" style="
    border: 1px solid black;
    width: 130px;
    height: 95px;"></p>
                <p>Compare your country and it's ladder match skill to other countries around the world here.</p>
            </div>
        </div>
    </div>

    <!-- BEGIN create -->
    <div class="regular">
        <div class="title">Creating New Clan</div>
        <div class="content">
            <p>Below you can complete the form to register your clan on anime blast. There are exclusive benefits you
                can
                earn from being in a clan.</p>
            <div class="content">
                {ERROR}
                <form method="post" enctype="multipart/form-data">
                    Clan Icon <input type="file" class="normaltab" name="avatar"><br><br>
                    Clan Name <input type="text" class="normaltab" name="name" placeholder="Clan Name"><br><br>
                    Clan Biography <textarea name="bio" style="width:100%;"
                        placeholder="Clan Biography or description"></textarea><br><br>
                    Leader Rank Name <input type="text" class="normaltab" name="default"
                        placeholder="Clan Leader"><br><br>
                    Clan Abbreviation <input type="text" name="abv" class="normaltab"
                        placeholder="Clan Abbreviation"><br><br>
                    <input type="submit" class="globaltab p-1" name="create" value="Create Clan">
                </form>
            </div>
        </div>
    </div>

    <!-- END create -->

    <!-- BEGIN settings -->
    <div class="content">
        <h2 class="header">{NAME} > Settings</h2>
        <div class="transparent">
            <p style="font-size:12px">Here you can update clan settings. If a setting has <img
                    src="https://www.anime-blast.com/tpl/default/img/gold.png" style="width: 30px;"> next to it, it
                requires
                BC. Clans have a common pool of BC that is earned by the members of the clan.</p>
            {ERROR}
            {AVATAR}
            <div class="fl-l">
                <form method="post" enctype="multipart/form-data">
                    <img class="change-avatar" src="./tpl/default/img/change.png">
                    <input name="avatar" class="upload-avatar" style="display:none;" type="file">
                    <img class="preview">
                    <input type="submit" name="save-avatar" value="Save" placeholder=""
                        style="position: absolute;z-index: 2;bottom: 15px;left: 20px;">
                </form>
            </div>
            <form method="post" enctype="multipart/form-data">
                <p class="navfont" style="text-align: right;">Clan Information</p>
                <p class="color normal">Clan Name: <input type="text" name="name" class="clanname" value="{NAME}">
                    <input type="submit" class="clanname" value="Save" /> <span> 10000 <img
                            src="https://www.anime-blast.com/tpl/default/img/gold.png"
                            style="width: 30px;vertical-align: middle;"></span>
                <p class="color alternate">Abbreviation: <input type="text" name="abv" value="{AB}"> <input
                        type="submit" class="clanname" value="Save" /> <span> 5000 <img
                            src="https://www.anime-blast.com/tpl/default/img/gold.png"
                            style="width: 30px;vertical-align: middle;"></span>
                <p class="color normal">Clan banner: <input type="text" name="banner" value="{BANNER}"
                        placeholder="Clan banner to show on clan listing" /> <input type="submit" name="action"
                        class="clanname" value="Remove" /> <input type="submit" class="clanname" value="Save" /><span>
                        1000
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
                <div class="justify-content-center">
                    <textarea name="biography" style="width:90%;" placeholder="Clan Biography">{DESCRIPTION}</textarea>
                    <br class="clearfix"><br>
                    <input type="submit" value="Save" />
                </div>
            </form>
        </div>
        <br class="clearfix">
        <h2 class="header" style="text-align: left;">MEMBERS<input class="globaltab" type="button" value="Add Rank"
                onclick="parent.location='./clan/profile/{NAME}?action=add-rank'" style="float: right;"></h2>
        <div class="clan-sortable">
            {MEMBERS}
            <!-- BEGIN rank -->
            <div id="{ID}" class="clan-order">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="rank" value="{ID}">
                    <p class="clan-rank"><input type="text" name="clanrank" value="{CLANRANK}"><span class="role">Based
                            on
                            {ROLE}</span> <input type="submit" name="save" value="Save Changes" /></p>
                </form>
                <div id="{ID}" {SORTABLE}>
                    {MEMBER}
                    <!-- BEGIN member -->
                    <div class="ui-state-default">
                        <form method="post" enctype="multipart/form-data"><input type="hidden" name="member"
                                value="{ID}">
                            <p>{RANK} {NAME}
                                <!-- BEGIN kick --><input type="submit" name="option" value="Kick">
                                <!-- END kick --><span class="join-member">{DATE}</span>
                            </p>
                        </form>
                    </div><!-- END member -->

                </div>
            </div>
            <!-- END rank -->
        </div>
    </div>
</div>
<!-- END settings -->