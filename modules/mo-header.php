<?php if(!defined('EMLOG_ROOT')) {exit('error!');}?><!--em-compress-html--><!--em-compress-html no compression-->



























<!--
　　　　　　　　 ／ ¯)
　　　　　　　 ／　／
　　　　　　 ／　／
　　　_／¯ ／　／'¯ )
　　／／ ／　／　／ ('＼
　（（ （　（　（　 ） )
　　＼　　　　　 ＼／ ／
　　　＼　　　　　　／
　　　　＼　　　　／
　　　　　＼　　　＼
-->




























<!--em-compress-html no compression--><!--em-compress-html-->
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="shortcut icon" type="images/x-icon" href="<?php echo BLOG_URL; ?>favicon.ico" />
<meta charset="UTF-8" />
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
	
	
<link rel='stylesheet' id='wp-player-css'  href='<?php echo TEMPLATE_URL; ?>css/wp-player.css?ver=2.5.1' type='text/css' media='screen' />
<link rel='stylesheet' id='wp-syntax-css-css'  href='<?php echo TEMPLATE_URL; ?>css/wp-syntax.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='Sealey-style-css'  href='<?php echo TEMPLATE_URL; ?>css/style.css?ver=1.2' type='text/css' media='screen' />

<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/jquery.js?ver=1.11.1'></script>

<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/jquery-migrate.min.js?ver=1.2.1'></script>

<meta name="generator" content="WordPress 4.1.10" />
<style type="text/css">
.orange li:hover>a,.orange li.active a{background:#084dd1;color:#fff!important}
.flexy-menu>li>a{/*padding:20px 22px*/padding:13px 15px;color:#ccc;text-decoration:none;display:block;text-transform:uppercase;-webkit-transition:color .2s linear,background .2s linear;-moz-transition:color .2s linear,background .2s linear;-o-transition:color .2s linear,background .2s linear;transition:color .2s linear,background .2s linear}
</style>
<script type="text/javascript" src="<?php echo TEMPLATE_URL; ?>js/shCore.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>css/shCoreDefault.css" />	

</head>
<body>
<div id="wrapper">
	<header id="main-header">
		<div id="header-wrap">
			<div id="logo">
				<h1><a href="<?php echo BLOG_URL; ?>" title="<?php echo option('blogname');?>"><?php echo option('blogname');?></a></h1>
				<h2><?php echo option('bloginfo');?></h2>
				<div class="logo-img" ><img class="avatar" src="<?php echo TEMPLATE_URL; ?>img/logo.png" title="<?php echo option('blogname');?>"/></div>
				<div id="logo-music">
					<div id="logo-music-name"></div>
					<div id="logo-music-prev"></div>
					<div id="logo-music-play"></div>
					<div id="logo-music-pause"></div>
					<div id="logo-music-next"></div>
					<div class="loading">
						<div class="loading-bar">
							<div class="bar1"></div>
							<div class="bar2"></div>
							<div class="bar3"></div>
							<div class="bar4"></div>
						</div>
					</div>
				</div>
				<div id="logo_jplayer" class="jp-jplayer"></div>
			</div>
			
			<button id="openlist" class="open">playlist</button>
			<button id="openmenu" class="open">menu</button>

			<nav id="main-nav"><?php blog_navi(); ?></nav>

			<form role="search" name="keyform" method="get" id="search-form" action="<?php echo BLOG_URL; ?>index.php">
				<div>
					<input type="text" value="Search" name="keyword" id="keyword" onblur="if ( this.value == '' ){this.value='Search';}" onfocus = "if ( this.value == 'Search' ){this.value = '';}" />
				</div>
			</form>
			<div class="clear"></div>
		</div>
	</header>
	<!--header-->
	<div id="main">
