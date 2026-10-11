<?php
/**
 * Title: Ví dụ hoạt động – Giờ ăn sáng bằng tiếng Anh
 * Slug: play2grow/example-activity
 * Categories: play2grow, text
 * Keywords: hoạt động, ví dụ, phụ huynh
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--mint","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--mint"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'Ví dụ minh họa', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Một bữa sáng cũng có thể là một hoạt động tiếng Anh', 'play2grow-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Không cần bàn học hay sách bài tập: chỉ cần vài phút quanh bàn ăn, trẻ đã có thể nghe, nhìn, chạm và nói về những thứ rất cụ thể trước mặt.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"p2g-hero__note"} -->
<p class="p2g-hero__note"><?php esc_html_e( 'Đây là gợi ý để phụ huynh tham khảo và tự thực hiện tại nhà, không phải mô tả nội dung của một lớp học cụ thể tại Play2Grow.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( __( 'Xem thêm hoạt động tại nhà', 'play2grow-theme' ), p2g_post_link( 'hoat-dong-tieng-anh-tai-nha-cho-tre' ), 'outline' ),
	)
);
?>
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:group {"className":"p2g-activity","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-activity"><!-- wp:paragraph {"className":"p2g-label"} -->
<p class="p2g-label"><?php esc_html_e( 'Gợi ý cho phụ huynh', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Breakfast time – Giờ ăn sáng', 'play2grow-theme' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo wp_kses_post( __( '<strong>Thời gian:</strong> 5–10 phút trong bữa sáng. <strong>Phù hợp:</strong> trẻ đang bắt đầu làm quen với tiếng Anh.', 'play2grow-theme' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"p2g-phrases"} -->
<ul class="wp-block-list p2g-phrases"><!-- wp:list-item -->
<li><?php esc_html_e( 'What’s this?', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'It’s an egg.', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Do you like milk?', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'It’s hot!', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'More, please.', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Chỉ vào 3–4 món trên bàn và gọi tên từng món thật chậm.', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Hỏi “Do you like…?” và chấp nhận mọi cách trả lời: “Yes”, “No”, gật đầu hay lắc đầu.', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo wp_kses_post( __( 'Thêm một tính từ đơn giản khi con chạm vào món ăn: <em>hot, cold, soft, yummy</em>.', 'play2grow-theme' ) ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Kết thúc bằng một câu quen thuộc mà con có thể nói lại vào ngày mai: “Thank you!”.', 'play2grow-theme' ); ?></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
