<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

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
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-152357673-1');
    </script>
    <title>{PAGETITLE}</title>
    <!-- BEGIN content -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="{URL}tpl/default2/css/mdb.dark.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js">
    </script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js">
    </script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js">
    </script>
    <script type="text/javascript" src="{URL}tpl/beta/java/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="{URL}tpl/default/css/bxslider.css">
    <script type="text/javascript" src="{URL}tpl/beta/java/bxslider.js"></script>
    <!-- END content -->
    <link rel="icon" type="image/png" href="{FAV}">
    {SCRIPTS}
</head>

<body>
    <!-- BEGIN content -->
    <div class="page-wrapper">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i class="fas fa-bars"></i>
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
                    <div class="user-pic">
                        {AVATAR}{CLAN}
                        <div class="clearfix"></div>
                        <div class="progress md-progress" style="height: 20px">
                            <div class="progress-bar" style="width: {WIDTH}%; height: 20px">
                                <p class="expNumber">{EXPERIENCE}</p>
                            </div>
                        </div>
                    </div>
                    <div class="user-info">
                        <span class="user-name">Welcome
                            <small>{USERNAME}</small>
                        </span>
                        <a class="btn btn-custom2" style="background-color: #c31a35;" href="{URL}mail"
                            role="button">Messages
                        </a>
                        {NOTIFICATION}

                    </div>
                </div>
                <!-- END logged_in -->

                <div class="sidebar-menu">
                    <ul>
                        <!-- BEGIN logged_out -->
                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        <li>
                            <a href="{URL}">
                                <i class="fa fa-home"></i>
                                <span class="ml-2">Homepage</span>

                            </a>
                        </li>
                        <li>
                            <a href="{URL}">
                                <i class="fa fa-ellipsis-h"></i>
                                <span class="ml-2">Game Manual</span>

                            </a>
                        </li>
                        <!-- END logged_out -->
                        <!-- BEGIN logged_in -->
                        <!-- BEGIN UCP -->
                        <li class="header-menu">
                            <span>Account</span>
                        </li>
                        <li>
                            <a href="{URL}#" onclick="popup_page('{URL}ingame','','1200','900')">
                                <i class="fa fa-balance-scale-right"></i>
                                <span class="ml-2">Start Playing</span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-cogs"></i>
                                <span class="ml-2">Control Panel</span>
                            </a>
                        </li>
                        <li>
                            <a href="{URL}missions">
                                <i class="fa fa-tasks"></i>
                                <span class="ml-2">Missions</span>
                            </a>
                        </li>
                        <li>
                            <a href="{URL}characters-and-skills">
                                <i class="fa fa-theater-masks"></i>
                                <span class="ml-2">Characters</span>
                            </a>
                        </li>
                        <li>
                            <a href="{URL}clans">
                                <i class="fa fa-users"></i>
                                <span class="ml-2">Clans</span>
                            </a>
                        </li>
                        <li>
                            <a href="{URL}logout">
                                <span class="ml-4">Sign Out</span>
                            </a>
                        </li>

                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        <li>
                            <a href="{URL}">
                                <i class="fa fa-home"></i>
                                <span class="ml-2">Homepage</span>

                            </a>
                        </li>
                        <li>
                            <a href="{URL}">
                                <i class="fa fa-ellipsis-h"></i>
                                <span class="ml-2">Game Manual</span>

                            </a>
                        </li>
                        <!-- END UCP -->
                        <!-- END logged_in -->
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>

            <!-- sidebar-content  -->
            <div class="sidebar-footer">
                <div class="container d-flex justify-content-center py-2">
                    <a class="mx-2 btn btn-custom" style="background-color: #c31a35;"
                        href="https://discord.com/invite/UJCRTd2" role="button">
                        <i class="fab fa-discord"></i></a>


                    <a class="mx-2 btn btn-custom" style="background-color: #c31a35;"
                        href="https://www.youtube.com/channel/UCIUv_V2nKY2L45kWRcpUieQ?sub_confirmation=1"
                        role="button">
                        <i class="fab fa-youtube"></i></a>

                    <a class="mx-2 btn btn-custom" style="background-color: #c31a35;"
                        href="https://twitter.com/AnimeBlast3" role="button">
                        <i class="fab fa-twitter"></i></a>


                    <a class="mx-2 btn btn-custom" style="background-color: #c31a35;"
                        href="https://www.paypal.me/WShall" role="button"><i class="fab fa-paypal"></i></a>
                </div>
            </div>
        </nav>
        <!-- sidebar-wrapper  -->

        <main class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <a class="logo" href="{URL}"><img src="{URL}tpl/default/img/banner.png" class="banner"></a>
                </div>
                <div class="col row-cols-lg-auto g-3">
                    <div class="first">
                        <!-- BEGIN logged_out -->
                        <section class="container-options mt-5">
                            <!--<img src="{URL}/tpl/default/img/banner2.png" class="container-options--login-avatar avatar">-->
                            <div class="login">
                                <p class="error false">Username/Password match incorrect!</p>
                                <p class="error empty">Please fill out the form!</p>
                                <p class="error username">Please enter a valid username!</p>
                                <p class="message Username">Username...</p>
                                <div class="form-outline mb-1">
                                    <input id="uname" type="input" data-placeholder="Username..."
                                        placeholder="Username..." class="input textset" value="">
                                </div>
                                <p class="error password">Please enter a password!</p>
                                <p class="message Password">Password</p>
                                <div class="form-outline mb-1">
                                    <input id="pass" type="password" data-placeholder="Password..."
                                        placeholder="Password..." class="input textset" value="">
                                </div>
                                <input id="submit-login" type="submit" value="Sign In" class="submit">
                                <br>
                                <div class="form-outline mb-1">
                                    <input type="checkbox" style="margin: 0px 5px; top: 2px; position: relative;"
                                        class="form-check-input" value="1" name="remember" id="remember" checked><label
                                        for="remember" style="font-size: 10px;margin-right: 55px;">Remember
                                        me?</label><a href="{URL}password-recovery" target="_blank"><span
                                            style="font-size: 10px;">Forgot Password?</span></a>
                                </div>
                            </div>
                            <!-- BEGIN registration -->
                            <div class="regissty">
                                Dont have an account? <span class="reg"> Click here to register!</span>
                            </div>
                            <!-- END registration -->

                            <!-- BEGIN registration -->
                            <div class="registration">
                                <p class="error false">User information taken or information invalid!</p>
                                <p class="error empty">Please fill out the form!</p>
                                <p class="error email">Please enter an email!</p>
                                <p class="message Email">Email</p>
                                <div class="form-outline mb-1">
                                    <input id="regemail" type="input" data-placeholder="Email..." placeholder="Email..."
                                        class="input" value="">
                                </div>
                                <p class="error username">Please enter a username!</p>
                                <p class="message Username">Username</p>
                                <div class="form-outline mb-1">
                                    <input id="reguname" type="input" data-placeholder="Username..."
                                        placeholder="Username..." class="input" value="">
                                </div>
                                <p class="error password">Please enter a password!</p>
                                <p class="message Password">Password</p>
                                <div class="form-outline mb-1">
                                    <input id="regpass" type="password" data-placeholder="Password..."
                                        placeholder="Password..." class="input" value="">
                                </div>
                                <p class="error conf">Passwords enter the password you entered above!</p>
                                <p class="error match">Passwords don't match!</p>
                                <p class="message Confirm">Confirm Password</p>
                                <div class="form-outline mb-1">
                                    <input id="confpass" type="password" data-placeholder="Confirm Password..."
                                        placeholder="Confirm Password..." class="input" value="">
                                </div>
                                <div class="form-outline mb-1">
                                    <input id="submit-register" type="submit" value="Register!" class="submit">
                                </div>

                                <div class="regissty">
                                    Already registered? <br /><span class="log"> Login Now!</span>
                                </div>
                            </div>
                        </section>
                        <!-- END registration -->
                        <!-- END logged_out -->
                    </div>

                </div>
                <!-- BEGIN linktree -->
                <nav class="linktree">
                    <p>
                        <img src="{URL}/favicon.ico" style="width: 16px; float: left;">
                        {AREA}
                    </p>
                </nav>
                <!-- END linktree -->
                <div class="global-menu">{GLOBAL_MENU}</div>
            </div>


<!-- END content -->