<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>TopCreator :: Admin</title>
    <meta name="description" content="" />
    <meta name="keywords"    content="" />

    <link rel="stylesheet" type="text/css" href="/admin/bootstrap/css/bootstrap.min.css" media="screen, projection, print">
    <link rel="stylesheet" type="text/css" href="/admin/bootstrap/css/style.css" media="screen, projection, print">
    <!--[if lt IE 9]>
    <script src="/js/html5.js" type="text/javascript"></script>
    <![endif]-->

    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
    <script>
        !window.XMLHttpRequest && window.location.reload("/ie6/ie6.html");
        !window.jQuery && document.write('<script src="/js/jquery-1.7.1.min.js"><\/script>');
    </script>

    <link rel="stylesheet" type="text/css" href="/js/selectbox/jquery.selectBox.css" media="screen, projection, print">
    <script type="text/javascript" src="/js/selectbox/jquery.selectBox.patched.min.js"></script>

    <script type="text/javascript" src="/js/checkbox/jquery.checkbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/js/checkbox/jquery.checkbox.css" media="screen, projection, print">

    <link rel="stylesheet" href="/js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>

    <!-- bootstrap js -->
    <script type="text/javascript" src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- core js API -->
    <script type="text/javascript" src="/js/core.js"></script>

    <link rel="stylesheet" href="/css/alertbox.css" type="text/css" media="screen" />
</head>
<body>
    <div class="admin_wrapper">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <!-- Be sure to leave the brand out there if you want it shown -->
                    <a class="brand" href="/admin/">TopCreator</a>

                    <!-- Everything you want hidden at 940px or less, place within here -->
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active">
                                <a href="#">Управление сайтом</a>
                            </li>
                            <li><a href="#">Модерация</a></li>
                            <li><a href="#">Блог</a></li>
                        </ul>
                        <div class="admin_panel_logo pull-right">
                            logo here
                        </div>
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                      Admin<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Настройки</a></li>
                                    <li><a href="#">Выйти</a></li>
                                </ul>
                            </li>
                            <li class="divider-vertical"></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="left_panel">
            <?include(PATH_TEMPLATES.'Common/left_menu.php');?>
        </div>

        <div class="main_panel">
            <!-- all the content output goes here -->
            <?echo $_content;?>
            <!-- and here it all ends =) -->
        </div>

        <div class = "b-wrapper__empty"></div>
    </div>
    <footer class="admin_footer well">
        <div class="copyright">
            <p><b>Vivian</b><sup>&copy;</sup></p>
            <p class="year">2012</p>
        </div>
    </footer>
<!--debugger-->
</body>
</html>