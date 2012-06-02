<!DOCTYPE html> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>TopCreator :: <?=$obj->page_title;?></title>
		<meta name="description" content="" />
		<meta name="keywords"    content="" />
		
        <!--<link rel="stylesheet" type="text/css" href="/css/style.css" media="screen, projection, print">-->
		
        <link rel="stylesheet/less" type="text/css" href="/css/styless.css" media="screen, projection, print">
		<script src="/js/less.js" type="text/javascript"></script>
		
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

        <script type="text/javascript" src="/js/placeholder.min.js"></script>

        <script type="text/javascript" src="/js/main.js"></script>

        <script type="text/javascript" src="/js/jquery.dotdotdot-1.4.0-packed.js"></script>

        <script type="text/javascript" src="/js/maxim.js"></script>

        <!-- core js API -->
        <script type="text/javascript" src="/js/core.js"></script>

        <!-- plupload (make this loading only when it's needed!!!) -->
        <script type="text/javascript" src="/js/plupload/plupload.full.js"></script>
        <!--<script type="text/javascript" src="/js/plupload/plupload.gears.js"></script>
        <script type="text/javascript" src="/js/plupload/plupload.silverlight.js"></script>
        <script type="text/javascript" src="/js/plupload/plupload.flash.js"></script>
        <script type="text/javascript" src="/js/plupload/plupload.browserplus.js"></script>
        <script type="text/javascript" src="/js/plupload/plupload.html4.js"></script>
        <script type="text/javascript" src="/js/plupload/plupload.html5.js"></script>-->
        <!--<script type="text/javascript" src="/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js"></script>-->
        <script type="text/javascript" src="/js/uploader.init.js"></script>

        <link rel="stylesheet" href="/css/alertbox.css" type="text/css" media="screen" />
	</head> 
	<body>

        <!-- all the content output goes here -->
        <?echo $_content;?>
        <!-- and here it all ends =) -->

    <!--debugger-->
    </body>
</html>