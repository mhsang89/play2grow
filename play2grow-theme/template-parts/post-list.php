<?php
/**
 * Post grid + pagination + optional sidebar. Shared by index, archive and search.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_has_sidebar = is_active_sidebar( 'sidebar-blog' );
?>
<div class="section section--tight">
	<div class="container<?php echo $p2g_has_sidebar ? ' layout-sidebar' : ''; ?>">
		<div class="layout-sidebar__main">
			<?php
			if ( ! is_search() && ( is_home() || is_category() ) ) {
				get_template_part( 'template-parts/category-filter' );
			}

			if ( have_posts() ) :
				?>
				<div class="card-grid <?php echo $p2g_has_sidebar ? 'card-grid--2' : 'card-grid--3'; ?>">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/card', 'post' );
					endwhile;
					?>
				</div>
				<?php
				p2g_pagination();
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
		</div>
		<?php if ( $p2g_has_sidebar ) : ?>
			<aside class="layout-sidebar__aside" aria-label="<?php esc_attr_e( 'Thanh bên', 'play2grow' ); ?>">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>
