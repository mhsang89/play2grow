<?php
/**
 * Title: Bắt đầu từ đâu – 3 bước
 * Slug: play2grow/steps
 * Categories: play2grow, call-to-action
 * Keywords: bước, bắt đầu, quy trình
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_method  = p2g_page_link( 'phuong-phap-hoc' );
$p2g_blog    = p2g_posts_page_link();
$p2g_contact = p2g_contact_url_any();
$p2g_programs = p2g_page_link( 'chuong-trinh' );
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--dark","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--dark"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Cách bắt đầu</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ba bước nhỏ để cùng con khám phá tiếng Anh</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:list {"ordered":true,"className":"p2g-steps"} -->
<ol class="wp-block-list p2g-steps"><!-- wp:list-item -->
<li><strong>Chọn chương trình con yêu thích</strong>Xem <?php echo $p2g_programs ? '<a href="' . esc_url( $p2g_programs ) . '">các chương trình học</a>' : 'các chương trình học'; ?> và đọc <?php echo $p2g_method ? '<a href="' . esc_url( $p2g_method ) . '">phương pháp</a>' : 'phương pháp'; ?> của Play2Grow.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Để lại thông tin tư vấn</strong><?php echo $p2g_contact ? '<a href="' . esc_url( $p2g_contact ) . '">Gửi thông tin</a>' : 'Gửi thông tin'; ?> về độ tuổi và sở thích của con; Play2Grow sẽ liên hệ để tư vấn lớp, lịch học và học phí.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Đồng hành cùng con tại nhà</strong>Chọn một gợi ý trong <?php echo $p2g_blog ? '<a href="' . esc_url( $p2g_blog ) . '">Góc học tập</a>' : 'Góc học tập'; ?> để cùng con tiếp tục dùng tiếng Anh trong sinh hoạt hằng ngày.</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></div>
<!-- /wp:group -->
