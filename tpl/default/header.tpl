<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {BASE}
    <link rel=”canonical” href=”{URL}” />
    {LANDSCAPE}
    <meta name="resource-type" content="document">
    <meta name="distribution" content="Global">
    <meta name="copyright" content="anime-blast.com">
    <meta name="robots" content="Index,Follow">
    <meta name="rating" content="General">
    <meta name="revisit-after" content="1 day">
    <meta name="description" http-equiv="description" content="{METAINFO}" />
    <meta name="keywords" http-equiv="keywords" content="{METAKEYWORDS}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-152357673-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-152357673-1');
    </script>
    <title>{PAGETITLE}</title>

    <!-- BEGIN content -->
    <script type="text/javascript" src="{URL}tpl/beta/java/jquery.js"></script>
    <script type="text/javascript" src="{URL}tpl/beta/java/bxslider.js"></script>
    <script type="text/javascript" src="{URL}tpl/default/java/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{URL}tpl/default/css/bxslider.css">
    <link rel="stylesheet" type="text/css" href="{URL}tpl/default/css/bootstrap.css">
    <!-- END content -->
    <link rel="icon" type="image/png" href="{FAV}">
    {SCRIPTS}
</head>

<body>
    <!-- BEGIN content -->
    <div class="page-wrapper">
        <a id="toggle-sidebar" style="width:5em; border-right:2px solid #131314;" class="btn btn-sm btn-dark">
            <img class="logo" src="{URL}/tpl/default/img/minlogo.png">
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-brand">
                    <div id="close-sidebar">
                        <i class="fas fa-times"></i>
                    </div>
                </div>

                <!-- BEGIN logged_in -->
                <div class="sidebar-header">
                    <div class="sub-head">
                        <span class="dropdown">
                            Welcome
                            <span class="name" style="color: #FFEB3B;">{USERNAME}</span>
                            <img src="{URL}/tpl/default/img/drop.png" style="width: 10px;margin-left: 5px;">
                            <div class="dropdown-content">
                                {MENU}
                            </div>
                        </span>

                    </div>
                    <div class="user-pic">
                        {AVATAR}
                        <span class="level">Level {LEVEL}</span>
                        <div class="clearfix"></div>
                        <div class="progress md-progress" style="height: 20px">
                            <div class="progress-bar" style="width: {WIDTH}%; height: 20px">
                                <p class="expNumber">{EXPERIENCE}</p>
                            </div>
                        </div>
                    </div>
                    <div class="user-info my-1">

                        <a class="btn btn-custom3 mb-1" style="background-color: #c31a35;" href="{URL}#"
                            onclick="testp('{URL}ingame','','1037','581')" role="button">Play Now
                        </a>
                        <a class="btn btn-custom3 mb-1" style="background-color: #c31a35;" href="{URL}mail"
                            role="button">Messages

                            <span> {NOTIFICATION}</span>
                        </a>

                        <a class="btn btn-custom3 mb-1" style="background-color: #c31a35;" href="{URL}"
                            role="button">Refresh
                            Content
                        </a>

                    </div>
                </div>
                <!-- END logged_in -->

                <div class="sidebar-menu">
                    <ul>
                        <!-- BEGIN logged_in -->
                        <li class="header-menu">
                            <span>Account</span>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}missions" class="btn btn-custom2">
                                <i class="fa fa-tasks"></i>
                                <span class="ml-2">Player Missions</span><br>
                                <small>Unlock characters and more!</small>
                            </a>

                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}characters-and-skills" class="btn btn-custom2">
                                <i class="fa fa-theater-masks"></i>
                                <span class="ml-2">Characters</span><br>
                                <small>View the roster and build a team!</small>
                            </a>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}logout" class="btn btn-custom2">
                                <i class="fa fa-sign-out"></i>
                                <span class="ml-2">Logout</span><br>
                                <small>Go have a drink bud. See ya soon!</small>
                            </a>
                        </li>

                        <li class="header-menu">
                            <span>General</span>
                        </li>

                        <li class=" nav-item button my-1 mx-1">
                            <a href="https://www.youtube.com/watch?v=1M_bM-hzTjU" class="btn btn-custom2">
                                <i class="fa fa-school"></i>
                                <span class="ml-2">Game Mechanics</span><br>
                                <small>Learn how to play Anime-Blast!</small>

                            </a>
                        </li>

                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}the-team" class="btn btn-custom2">
                                <i class="fa fa-user-friends"></i>
                                <span class="ml-2">Memberlist</span><br>
                                <small>Browse players and staff here!</small></a>
                        </li>
                        <!-- END logged_in -->


                        <!-- BEGIN logged_out -->
                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}" class="btn btn-custom2">
                                <i class="fa fa-home"></i>
                                <span class="ml-2">Homepage</span>

                            </a>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="https://www.youtube.com/watch?v=1M_bM-hzTjU" class="btn btn-custom2">
                                <i class="fa fa-ellipsis-h"></i>
                                <span class="ml-2">Game Manual</span>

                            </a>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}characters-and-skills" class="btn btn-custom2">
                                <i class="fa fa-theater-masks"></i>
                                <span class="ml-2">Characters</span>
                            </a>
                        </li>
                        <li class=" nav-item button my-1 mx-1">
                            <a href="{URL}terms-of-service" class="btn btn-custom2"><i class="fa fa-info"></i><span
                                    class="ml-2">Terms of Service</span></a>
                        </li>
                        <!-- END logged_out -->

                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>

            <!-- sidebar-content  -->
            <div class="sidebar-footer">
                <div class="d-flex" style="justify-content:center;">
                    <a class="mx-2 btn btn-custom" style="background-color: ##9c1120;"
                        href="https://discord.gg/52uVv5WS" role="button">
                        <i class="fab fa-discord"></i></a>


                    <a class="mx-2 btn btn-custom" style="background-color: #9c1120;"
                        href="https://twitter.com/AnimeBlast3" role="button">
                        <i class="fab fa-twitter"></i></a>


                    <a class="mx-2 btn btn-custom" style="background-color: #9c1120;"
                        href="https://www.paypal.me/WShall" role="button"><i class="fab fa-paypal"></i></a>

                    <a class="mx-2 btn btn-custom2">{VERSION}</a>

                </div>
            </div>
        </nav>
        <!-- sidebar-wrapper  -->

        <main class="page-content">
            <div class="container-fluid">
                <div id="regModal" class="rModal">

                    <!-- Modal content -->
                    <div class="regModalC">
                        <span class="regClose">&times;</span>
                        <h1 style="text-align:center;"> Welcome to Anime-Blast!</h1>
                        <p style="text-align:center;">If you haven't already, join our <a
                                href="https://discord.gg/CP8pZSA6" target="_blank">Discord community</a> to talk
                            directly with other players and staff here!</p>

                        <h2 class="button"
                            style="background: rgba(255, 255, 255, 0.699) url(https://www.anime-blast.com/tpl/default/img/banner2.png) no-repeat 0px -11px;height: 395px;">
                    </div>
                </div>
                <a class="logo" href="{URL}"><img src="{URL}tpl/default/img/banner.png" class="banner"></a>
                <div class="main-bg my-2">

                    <section class="cell cell-1">
                        <!-- BEGIN UCP -->
                        <!-- BEGIN logged_in -->
                        <div class="ucp">
                            <div class="ucp-header">
                                <div class="clan-pic">
                                    {CLAN}
                                </div>
                            </div>

                            <div class="ucp-body">
                                <div class="user-info">
                                    <div class="btn-bg">
                                        <div class="button mt-1">
                                            <a href="{URL}clans" class="btn btn-custom2">Visit Clans</a>
                                        </div>
                                        <div class="button">
                                            <a href="{URL}" class="btn btn-custom2">Refresh Content
                                                <span> {NOTIFICATION}</span></a>

                                        </div>
                                        <div class="button">
                                            <a href="{URL}logout" class="btn btn-custom2">Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END UCP -->
                        </div>
                        <!-- END logged_in -->
                        <!-- END UCPNEW -->

                        <!-- BEGIN logged_out -->
                        <div class="barrier">
                            <section class=" container-options">
                                <div class="login">
                                    <p class="error false">Username/Password match incorrect!</p>
                                    <p class="error empty">Please fill out the form!</p>
                                    <p class="error username">Please enter a valid username!</p>
                                    <p class="message Username">Username</p>

                                    <input id="uname" type="input" data-placeholder="Username" placeholder="Username"
                                        class="input2" value=""><br />
                                    <p class="error password">Please enter a password!</p>
                                    <p class="message Password">Password</p>
                                    <input id="pass" type="password" data-placeholder="Password" placeholder="Password"
                                        class="input2" value="">
                                    <input id="submit-login" type="submit" value="Login" class="submit">
                                    <br>
                                    <input type="checkbox"
                                        style="margin-bottom: 0px;margin-top: 0p;top: 2px;position: relative;" value="1"
                                        name="remember" id="remember">
                                    <label for="remember" style="font-size: 10px;margin-right: 20px;">Remember
                                        me?</label>
                                    <a href="{URL}password-recovery" target="_blank"><span
                                            style="font-size: 10px;">Password
                                            Recovery</span></a>

                                    <!-- BEGIN registration -->
                                    <div class="regissty">Dont have an account? <span class="reg"> Register Now!</span>
                                    </div>
                                    <!-- END registration -->
                                </div>
                                <!-- BEGIN registration -->
                                <div class="registration">
                                    <p class="error false">User information taken or information invalid!</p>
                                    <p class="error empty">Please fill out the form!</p>
                                    <p class="error email">Please enter an email!</p>
                                    <p class="message Email">Email</p>
                                    <label for="regemail"></label>
                                    <input id="regemail" type="input" data-placeholder="Email" placeholder="Email"
                                        class="input" value=""><br />
                                    <p class="error username">Please enter a username!</p>
                                    <p class="message Username">Username</p>
                                    <label for="reguname"></label>
                                    <input id="reguname" type="input" data-placeholder="Username" placeholder="Username"
                                        class="input" value=""><br />
                                    <p class="error password">Please enter a password!</p>
                                    <p class="message Password">Password</p>
                                    <label for="regpass"></label>
                                    <input id="regpass" type="password" data-placeholder="Password"
                                        placeholder="Password" class="input" value=""><br />
                                    <p class="error conf">Passwords enter the password you entered above!</p>
                                    <p class="error match">Passwords don't match!</p>
                                    <p class="message Confirm">Confirm Password</p>
                                    <label for="confpass"></label>
                                    <input id="confpass" type="password" data-placeholder="Confirm"
                                        placeholder="Confirm" class="input" value="">
                                    <input id="submit-register" type="submit" value="Register!" class="submit">
                                    <br>

                                    <div class="regissty">Already registered? <br /><span class="log">Login now!</span>
                                    </div>
                                </div>
                            </section>
                            <!-- END registration -->
                        </div>
                        <!-- END logged_out -->
                    </section>

                    <section class="cell cell-2">
                        <div class="ranking">
                            <p class="title">Top 5 Ladder <span class="what"
                                    data-descr="This is ranked based on the players with most experience ingame. The top 3 will get a special benifit when season ends.">?</span>
                            </p>
                            <ul class="list">
                                {RANKED}
                            </ul>
                        </div>
                    </section>
                    <section class="cell cell-3">
                        <div class="ranking">
                            <p class="title">Top 5 Highest Streak<span class="what"
                                    data-descr="This is ranked based on the players with the current highest streak in the game. The top 3 will get a special benifit when season ends.">?</span>
                            </p>
                            <ul class="list">
                                {RANKED-STREAK}
                            </ul>
                        </div>
                    </section>
                    <section class="cell cell-4">
                        <div class="ranking">
                            <p class="title">Top 5 Highest Clans<span class="what"
                                    data-descr="This is ranked based on the clans with the most experience in the game. The top 3 will get a special benifit when season ends.">?</span>
                            </p>
                            <ul class="list">
                                {RANKED-CLAN}
                            </ul>
                        </div>
                    </section>
                    <section class="cell cell-5">
                        <!-- BEGIN linktree -->
                        <nav class="linktree">
                            <p>
                                <img src="{URL}favicon.ico" style="padding-right: 5px;float: left;">
                                {AREA}
                            </p>
                        </nav>
                        <!-- END linktree -->
                        <div class="global-menu">{GLOBAL_MENU}</div>


<!-- END content -->