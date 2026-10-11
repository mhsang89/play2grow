<?php
/**
 * Empty state for archives and search.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="empty-state empty-state--large">
	<?php if ( is_search() ) : ?>
		<p class="empty-state__title"><?php esc_html_e( 'Chưa tìm thấy nội dung phù hợp.', 'play2grow' ); ?></p>
		<p><?php esc_html_e( 'Hãy thử từ khóa ngắn hơn, kiểm tra chính tả hoặc dùng từ đồng nghĩa (ví dụ: "trò chơi" thay vì "game").', 'play2grow' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p class="empty-state__title"><?php esc_html_e( 'Chuyên mục này chưa có bài viết.', 'play2grow' ); ?></p>
		<p><?php esc_html_e( 'Bài viết mới sẽ được cập nhật tại đây. Trong lúc chờ, bạn có thể tìm kiếm nội dung khác.', 'play2grow' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
