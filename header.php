<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

    <!-- Custom address bar color for mobile browsers -->
    <meta name="theme-color" content="#010051">

		<?php wp_head(); ?>

	</head>

  <body <?php body_class(); ?>>

  <?php
		wp_body_open();
		?>

		<header id="site-header" class="header-footer-group">
			<div class="header-inner section-inner">
				<div class="header-titles-wrapper">
					<div class="header-titles">
						<?php
							twentytwenty_site_logo();
						?>
					</div><
				</div>
			</div>
    </header>

			<?php
