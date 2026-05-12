<?php
/**
 * Site header.
 *
 * Adds:
 *  - skip-link to main content (WCAG 2.4.1)
 *  - role="banner" landmark via <header>
 *  - role="navigation" landmark via <nav>
 *  - aria-label on each nav so multiple nav landmarks are distinguishable
 *
 * @package WCAG_WP
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta name="color-scheme" content="light dark" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main">
		<?php echo esc_html( get_theme_mod( 'wcag_wp_skip_link_text', __( 'Skip to main content', 'wcag-wp' ) ) ); ?>
	</a>

	<div id="page" class="site">

		<header id="masthead" class="site-header" role="banner">
			<div class="site-header__inner">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					}

					$site_title = get_bloginfo( 'name' );
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $site_title ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $site_title ); ?></a></p>
					<?php endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
				</div>

				<nav id="site-navigation"
					class="main-navigation"
					role="navigation"
					aria-label="<?php esc_attr_e( 'Primary', 'wcag-wp' ); ?>">

					<button class="menu-toggle"
						type="button"
						aria-controls="primary-menu"
						aria-expanded="false">
						<span class="menu-toggle__icon" aria-hidden="true">
							<span></span><span></span><span></span>
						</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Primary menu', 'wcag-wp' ); ?></span>
						<span class="menu-toggle__label"><?php esc_html_e( 'Menu', 'wcag-wp' ); ?></span>
					</button>

					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'container'      => false,
								'depth'          => 3,
								'walker'         => new WCAG_WP_Nav_Walker(),
							)
						);
					} else {
						echo '<ul id="primary-menu" class="menu menu--fallback">';
						echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a primary menu', 'wcag-wp' ) . '</a></li>';
						echo '</ul>';
					}
					?>
				</nav>

				<form role="search" method="get" class="site-header__search" action="<?php echo esc_url( home_url( '/' ) ); ?>"
					aria-label="<?php esc_attr_e( 'Search', 'wcag-wp' ); ?>">
					<label for="header-search" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'wcag-wp' ); ?></label>
					<input type="search"
						id="header-search"
						class="search-field"
						name="s"
						value="<?php echo esc_attr( get_search_query() ); ?>"
						placeholder="<?php esc_attr_e( 'Search…', 'wcag-wp' ); ?>" />
					<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'wcag-wp' ); ?></button>
				</form>
			</div>
		</header>
