<?php
/**
 * Title: Bố cục trang chi tiết chương trình
 * Slug: play2grow/product-detail
 * Categories: play2grow
 * Keywords: chương trình, lớp học, chi tiết, bố cục
 * Block Types: core/post-content
 * Post Types: page
 *
 * Skeleton with headings that every class/program page should answer. Each section carries
 * an editor-only note; delete the notes after writing verified content.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_sections = array(
	array( __( 'Lớp học này dành cho ai?', 'play2grow-theme' ), 'Độ tuổi, trình độ ban đầu (nếu có yêu cầu), sở thích phù hợp.' ),
	array( __( 'Con sẽ học gì?', 'play2grow-theme' ), 'Mục tiêu và nội dung chính của khóa; giáo trình hoặc dự án tiêu biểu. Không hứa hẹn kết quả học tập.' ),
	array( __( 'Một buổi học diễn ra thế nào?', 'play2grow-theme' ), 'Mô tả trình tự một buổi học thực tế: khởi động, hoạt động chính, sản phẩm/phần trình bày cuối buổi.' ),
	array( __( 'Giáo viên', 'play2grow-theme' ), 'Chỉ ghi thông tin đã được giáo viên đồng ý công bố (tên, chuyên môn, kinh nghiệm có căn cứ).' ),
	array( __( 'Lịch học, sĩ số và học phí', 'play2grow-theme' ), 'Điền trong hộp "Thông tin chương trình / lớp học"; ghi rõ học phí đã gồm/chưa gồm học liệu.' ),
	array( __( 'Đăng ký và học thử', 'play2grow-theme' ), 'Quy trình đăng ký thực tế; chỉ nhắc tới học thử nếu trung tâm thật sự có.' ),
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
<h2 class="wp-block-heading"><?php esc_html_e( 'Câu hỏi thường gặp', 'play2grow-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Câu hỏi', 'play2grow-theme' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Câu trả lời đã được xác nhận.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
