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
$p2g_contact  = p2g_page_link( 'lien-he' );
$p2g_parent   = p2g_post_link( 'cha-me-khong-gioi-tieng-anh-giup-con-hoc' );
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--sky","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--sky"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Hỏi đáp</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Câu hỏi thường gặp</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"p2g-faq","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-faq"><!-- wp:details -->
<details class="wp-block-details"><summary>Play2Grow là gì?</summary><!-- wp:paragraph -->
<p>Play2Grow là thương hiệu giáo dục tập trung vào việc giúp trẻ học tiếng Anh thông qua hoạt động thực tế, vui chơi, khám phá và những trải nghiệm gắn với đời sống hằng ngày.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Học qua trải nghiệm có thay thế việc học từ vựng, ngữ pháp không?</summary><!-- wp:paragraph -->
<p>Không. Từ vựng và ngữ pháp vẫn là nền tảng. Học qua trải nghiệm bổ sung thêm cơ hội để trẻ dùng những gì đã học trong tình huống có ý nghĩa, nhờ đó tiếng Anh trở nên gần gũi và dễ vận dụng hơn.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Phụ huynh không giỏi tiếng Anh có đồng hành cùng con được không?</summary><!-- wp:paragraph -->
<p>Có. Điều trẻ cần nhiều nhất ở cha mẹ là thời gian, sự quan tâm và tinh thần cùng khám phá. <?php echo $p2g_parent ? '<a href="' . esc_url( $p2g_parent ) . '">Bài viết này</a> có những gợi ý cụ thể dành cho phụ huynh.' : 'Góc học tập có những gợi ý cụ thể dành cho phụ huynh.'; ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Sản phẩm dành cho độ tuổi nào và có mất phí không?</summary><!-- wp:paragraph -->
<p>Độ tuổi phù hợp, hình thức sử dụng và chi phí sẽ được công bố cùng từng sản phẩm trên trang Sản phẩm. <?php echo $p2g_contact ? 'Anh/chị có thể <a href="' . esc_url( $p2g_contact ) . '">để lại thông tin liên hệ</a> để nhận cập nhật.' : ''; ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Play2Grow có sử dụng AI không?</summary><!-- wp:paragraph -->
<p>Hiện website chưa giới thiệu tính năng AI nào. Play2Grow xem AI là một công cụ có thể hỗ trợ việc học – chẳng hạn gợi ý hoạt động hay mẫu câu theo ngữ cảnh – chứ không phải giá trị cốt lõi. Mọi tính năng như vậy, nếu có, sẽ được giới thiệu rõ ràng khi thực sự sẵn sàng.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Xem tất cả câu hỏi', $p2g_faq_page, 'outline' ),
	),
	'p2g-center'
);
?></div>
<!-- /wp:group -->
