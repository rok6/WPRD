<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?=Helper::description()?>
<?php wp_head(); ?>
<?=Helper::robots()?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel='stylesheet' media='all' href='<?=get_template_directory_uri()?>/assets/css/style.css' />
</head>
<body>

<div id="wrapper">



<header id="header">

	<div id="blanding">

		<div class="title">
			<?=Helper::logo(false)?>
		</div>

		<section class="web-service">
			<h2><a href="#">
				WEBサイト<br />
				制作・相談
			</a></h2>
		</section>

		<section class="web-recruit">
			<h2><a href="#">
				リクルート<br />
				サイトの<br />
				可能性。
			</a></h2>
		</section>
	</div>

	<div class="container">
		<nav class="nav-container">
			<?=Helper::navgation_menu(['location' => 'primary'])?>
		</nav>
		<div class="search">
			<?php get_search_form(); ?>
		</div>
	</div>
	<div class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
		<?=Helper::breadcrumb()?>
	</div>
</header>
