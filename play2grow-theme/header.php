<?php
/**
 * Site header.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_phone       = p2g_company( 'phone' );
$p2g_email       = p2g_company( 'email' );
$p2g_topbar_text = trim( (string) get_theme_mod( 'p2g_topbar_text', 'Explore the world. Play with English. Grow every day.' ) );
$p2g_topbar      = get_theme_mod( 'p2g_show_topbar', true ) && ( $p2g_phone || ( $p2g_email && is_email( $p2g_email ) ) || has_nav_menu( 'topbar' ) || '' !== $p2g_topbar_text || p2g_languages() );
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Bỏ qua và tới nội dung chính', 'play2grow-theme' ); ?></a>

<?php if ( $p2g_topbar ) : ?>
	<div class="topbar">
		<div class="container topbar__inner">
			<?php if ( '' !== $p2g_topbar_text ) : ?>
				<p class="topbar__text"><?php echo esc_html( $p2g_topbar_text ); ?></p>
			<?php endif; ?>
			<div class="topbar__right">
				<?php p2g_language_switcher( 'topbar' ); ?>
				<?php
				if ( has_nav_menu( 'topbar' ) ) {
					wp_nav_menu(
						array(
							'theme_location'       => 'topbar',
							'container'            => 'nav',
							'container_class'      => 'topbar__nav',
							'container_aria_label' => __( 'Liên kết nhanh', 'play2grow-theme' ),
							'depth'                => 1,
							'fallback_cb'          => false,
						)
					);
				}
				?>
				<?php if ( $p2g_phone || ( $p2g_email && is_email( $p2g_email ) ) ) : ?>
					<ul class="topbar__list">
						<?php if ( $p2g_phone ) : ?>
							<li><a href="<?php echo esc_url( p2g_tel( $p2g_phone ), array( 'tel' ) ); ?>"><?php p2g_the_icon( 'phone' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Hotline:', 'play2grow-theme' ); ?> </span><?php echo esc_html( $p2g_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $p2g_email && is_email( $p2g_email ) ) : ?>
							<li><a href="<?php echo esc_url( 'mailto:' . antispambot( $p2g_email ) ); ?>"><?php p2g_the_icon( 'mail' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Email:', 'play2grow-theme' ); ?> </span><?php echo esc_html( antispambot( $p2g_email ) ); ?></a></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
				<?php p2g_social_list(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php p2g_site_branding( 'header' ); ?>
		</div>

		<button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false">
			<span class="nav-toggle__icon nav-toggle__icon--open"><?php p2g_the_icon( 'menu' ); ?></span>
			<span class="nav-toggle__icon nav-toggle__icon--close"><?php p2g_the_icon( 'close' ); ?></span>
			<span class="nav-toggle__label"><?php esc_html_e( 'Menu', 'play2grow-theme' ); ?></span>
		</button>

		<div class="site-nav" id="site-nav">
			<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Menu chính', 'play2grow-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => 'p2g_primary_menu_fallback',
					)
				);
				?>
			</nav>
			<div class="site-nav__actions">
				<?php p2g_language_switcher( 'nav' ); ?>
				<a class="icon-button" href="<?php echo esc_url( p2g_home_url( '?s=' ) ); ?>" data-search-toggle aria-controls="header-search" aria-expanded="false">
					<?php p2g_the_icon( 'search' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Tìm kiếm', 'play2grow-theme' ); ?></span>
				</a>
				<?php p2g_cta_button( 'btn btn--primary btn--sm' ); ?>
			</div>
		</div>
	</div>
	<div class="header-search" id="header-search" hidden>
		<div class="container">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
