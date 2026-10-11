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
	array( 'Lớp học này dành cho ai?', 'Độ tuổi, trình độ ban đầu (nếu có yêu cầu), sở thích phù hợp.' ),
	array( 'Con sẽ học gì?', 'Mục tiêu và nội dung chính của khóa; giáo trình hoặc dự án tiêu biểu. Không hứa hẹn kết quả học tập.' ),
	array( 'Một buổi học diễn ra thế nào?', 'Mô tả trình tự một buổi học thực tế: khởi động, hoạt động chính, sản phẩm/phần trình bày cuối buổi.' ),
	array( 'Giáo viên', 'Chỉ ghi thông tin đã được giáo viên đồng ý công bố (tên, chuyên môn, kinh nghiệm có căn cứ).' ),
	array( 'Lịch học, sĩ số và học phí', 'Điền trong hộp "Thông tin chương trình / lớp học"; ghi rõ học phí đã gồm/chưa gồm học liệu.' ),
	array( 'Đăng ký và học thử', 'Quy trình đăng ký thực tế; chỉ nhắc tới học thử nếu trung tâm thật sự có.' ),
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
