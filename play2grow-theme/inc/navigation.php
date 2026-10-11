<?php
/**
 * Navigation helpers: accessible sub-menu toggles and menu fallbacks.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add a toggle button after parent items in the primary menu.
 *
 * @param string   $item_output Item HTML.
 * @param WP_Post  $item        Menu item.
 * @param int      $depth       Depth.
 * @param stdClass $args        Menu args.
 * @return string
 */
function p2g_submenu_toggle( $item_output, $item, $depth, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$item_output .= sprintf(
			'<button class="submenu-toggle" type="button" aria-expanded="false" aria-label="%1$s">%2$s</button>',
			/* translators: %s: menu item title. */
			esc_attr( sprintf( __( 'Mở menu con của %s', 'play2grow' ), wp_strip_all_tags( $item->title ) ) ),
			p2g_icon( 'chevron-down' )
		);
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'p2g_submenu_toggle', 10, 4 );

/**
 * Fallback for the primary menu when no menu has been assigned yet:
 * lists published top-level pages (max 6) so the site is still navigable.
 */
function p2g_primary_menu_fallback() {
	$front = (int) get_option( 'page_on_front' );
	$pages = get_pages(
		array(
			'parent'      => 0,
			'sort_column' => 'menu_order,post_title',
			'exclude'     => $front ? array( $front ) : array(),
			'number'      => 6,
		)
	);

	echo '<ul id="primary-menu" class="menu">';
	foreach ( $pages as $page ) {
		printf(
			'<li class="menu-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			is_page( $page->ID ) ? ' current-menu-item' : '',
			esc_url( get_permalink( $page ) ),
			is_page( $page->ID ) ? ' aria-current="page"' : '',
			esc_html( get_the_title( $page ) )
		);
	}
	$posts_page = (int) get_option( 'page_for_posts' );
	if ( ! $posts_page && ! $pages ) {
		printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Trang chủ', 'play2grow' ) );
	}
	echo '</ul>';

	if ( current_user_can( 'edit_theme_options' ) ) {
		printf(
			'<p class="menu-admin-hint"><a href="%1$s">%2$s</a></p>',
			esc_url( admin_url( 'nav-menus.php' ) ),
			esc_html__( 'Gán menu cho vị trí "Menu chính"', 'play2grow' )
		);
	}
}

/**
 * Print a footer menu column only when a menu is assigned.
 *
 * @param string $location Menu location.
 * @param string $fallback_title Title when the menu has no name.
 */
function p2g_footer_menu( $location, $fallback_title ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	$locations = get_nav_menu_locations();
	$menu      = isset( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : null;
	$title     = $menu ? $menu->name : $fallback_title;
	?>
	<nav class="site-footer__col site-footer__nav" aria-label="<?php echo esc_attr( $title ); ?>">
		<h2 class="site-footer__heading"><?php echo esc_html( $title ); ?></h2>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
	<?php
}
