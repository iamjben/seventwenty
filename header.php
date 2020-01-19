<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="site">
	<nav class="navbar navbar-expand-md navbar-dark bg-primary py-3" role="navigation">
		<div class="container">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-controls="bs-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="{{ home_url('/') }}">Seventwenty</a>
			<?php
				wp_nav_menu([
					'theme_location'    => 'main_navigation',
					'depth'             => 2,
					'container'         => 'div',
					'container_class'   => 'collapse navbar-collapse',
					'container_id'      => 'bs-navbar-collapse',
					'menu_class'        => 'nav navbar-nav ml-auto',
					'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
					'walker'            => new WP_Bootstrap_Navwalker(),
				]);
			?>
		</div>
	</nav>
	<div class="site__content container">
