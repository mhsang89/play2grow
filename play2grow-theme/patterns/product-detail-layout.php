<?php
/**
 * Title: Bố cục trang chi tiết sản phẩm
 * Slug: play2grow/product-detail
 * Categories: play2grow
 * Keywords: sản phẩm, chi tiết, bố cục
 * Block Types: core/post-content
 * Post Types: page
 *
 * Skeleton with headings that every product page should answer. Each section carries
 * an editor-only note; delete the notes after writing verified content.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_sections = array(
	array( 'Sản phẩm này dành cho ai?', 'Độ tuổi, trình độ tiếng Anh ban đầu, người sử dụng chính (trẻ, phụ huynh hay cả hai).' ),
	array( 'Nhu cầu học tập được giải quyết', 'Mô tả vấn đề thực tế của phụ huynh và trẻ mà sản phẩm hướng tới. Không hứa hẹn kết quả học tập.' ),
	array( 'Cách sản phẩm hoạt động', 'Mô tả một lượt sử dụng điển hình từ đầu đến cuối. Chỉ mô tả những gì đã chạy thật.' ),
	array( 'Tính năng đã sẵn sàng', 'Chỉ liệt kê tính năng có trạng thái LIVE_VERIFIED trong PRODUCT_AUDIT.md. Tính năng đang thử nghiệm hoặc dự kiến phải ghi rõ trạng thái.' ),
	array( 'Yêu cầu sử dụng', 'Thiết bị, kết nối Internet, tài khoản, thời lượng khuyến nghị, có cần người lớn hỗ trợ không.' ),
	array( 'Dữ liệu và quyền riêng tư', 'Sản phẩm thu thập dữ liệu gì của trẻ và phụ huynh, lưu ở đâu, ai truy cập được. Liên kết tới chính sách liên quan.' ),
);
foreach ( $p2g_sections as $p2g_section ) :
	?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html( $p2g_section[0] ); ?></h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[p2g_note]<?php echo esc_html( $p2g_section[1] ); ?>[/p2g_note]
<!-- /wp:shortcode -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->

<?php endforeach; ?>
<!-- wp:heading -->
<h2 class="wp-block-heading">Câu hỏi thường gặp</h2>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Câu hỏi</summary><!-- wp:paragraph -->
<p>Câu trả lời đã được xác nhận.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
