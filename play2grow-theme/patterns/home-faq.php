<?php
/**
 * Title: Câu hỏi thường gặp (rút gọn)
 * Slug: play2grow/faq
 * Categories: play2grow, text
 * Keywords: faq, câu hỏi, hỏi đáp
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_faq_page = p2g_page_link( 'cau-hoi-thuong-gap' );
$p2g_contact  = p2g_contact_url_any();
$p2g_parent   = p2g_post_link( 'cha-me-khong-gioi-tieng-anh-giup-con-hoc' );

if ( $p2g_parent ) {
	/* translators: %s: link to an article for parents. */
	$p2g_faq_parent = sprintf( __( 'Có. Điều trẻ cần nhiều nhất ở cha mẹ là thời gian, sự quan tâm và tinh thần cùng khám phá. %s có những gợi ý cụ thể dành cho phụ huynh.', 'play2grow-theme' ), p2g_maybe_link( $p2g_parent, __( 'Bài viết này', 'play2grow-theme' ) ) );
} else {
	$p2g_faq_parent = __( 'Có. Điều trẻ cần nhiều nhất ở cha mẹ là thời gian, sự quan tâm và tinh thần cùng khám phá. Góc học tập có những gợi ý cụ thể dành cho phụ huynh.', 'play2grow-theme' );
}

$p2g_faq_fees = __( 'Mỗi lớp có độ tuổi, lịch học và học phí riêng, được ghi trên trang của từng chương trình.', 'play2grow-theme' );
if ( $p2g_contact ) {
	/* translators: %s: link to the contact page. */
	$p2g_faq_fees .= ' ' . sprintf( __( 'Anh/chị có thể %s để được tư vấn lớp phù hợp với con.', 'play2grow-theme' ), p2g_maybe_link( $p2g_contact, __( 'để lại thông tin', 'play2grow-theme' ) ) );
}
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--sky","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--sky"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'Hỏi đáp', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Câu hỏi thường gặp', 'play2grow-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"p2g-faq","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-faq"><!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Play2Grow là gì?', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Play2Grow là trung tâm dành cho trẻ với các lớp STEAM English, mỹ thuật, âm nhạc và nhảy. Chúng tôi tập trung vào việc giúp trẻ học qua hoạt động thực tế, vui chơi, khám phá và những trải nghiệm gắn với đời sống hằng ngày.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Học qua trải nghiệm có thay thế việc học từ vựng, ngữ pháp không?', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Không. Từ vựng và ngữ pháp vẫn là nền tảng. Học qua trải nghiệm bổ sung thêm cơ hội để trẻ dùng những gì đã học trong tình huống có ý nghĩa, nhờ đó tiếng Anh trở nên gần gũi và dễ vận dụng hơn.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Phụ huynh không giỏi tiếng Anh có đồng hành cùng con được không?', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p>
<?php
echo wp_kses_post(
	$p2g_parent
		/* translators: %s: link to an article for parents. */
		? sprintf( __( 'Có. Điều trẻ cần nhiều nhất ở cha mẹ là thời gian, sự quan tâm và tinh thần cùng khám phá. %s có những gợi ý cụ thể dành cho phụ huynh.', 'play2grow-theme' ), p2g_maybe_link( $p2g_parent, __( 'Bài viết này', 'play2grow-theme' ) ) )
		: __( 'Có. Điều trẻ cần nhiều nhất ở cha mẹ là thời gian, sự quan tâm và tinh thần cùng khám phá. Góc học tập có những gợi ý cụ thể dành cho phụ huynh.', 'play2grow-theme' )
);
?>
</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Các lớp dành cho độ tuổi nào, học phí ra sao?', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p><?php echo wp_kses_post( $p2g_faq_fees ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Play2Grow có sử dụng AI không?', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Hiện website chưa giới thiệu tính năng AI nào. Play2Grow xem AI là một công cụ có thể hỗ trợ việc học – chẳng hạn gợi ý hoạt động hay mẫu câu theo ngữ cảnh – chứ không phải giá trị cốt lõi. Mọi tính năng như vậy, nếu có, sẽ được giới thiệu rõ ràng khi thực sự sẵn sàng.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( __( 'Xem tất cả câu hỏi', 'play2grow-theme' ), $p2g_faq_page, 'outline' ),
	),
	'p2g-center'
);
?>
</div>
<!-- /wp:group -->
