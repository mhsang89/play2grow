<?php
/**
 * Search form.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_search_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( p2g_home_url() ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $p2g_search_id ); ?>"><?php esc_html_e( 'Tìm kiếm', 'play2grow-theme' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $p2g_search_id ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Tìm bài viết, hoạt động, chủ đề…', 'play2grow-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="btn btn--primary search-form__submit"><?php p2g_the_icon( 'search' ); ?><span class="search-form__label"><?php esc_html_e( 'Tìm', 'play2grow-theme' ); ?></span></button>
</form>
