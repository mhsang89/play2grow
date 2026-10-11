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
$p2g_contact = p2g_page_link( 'lien-he' );
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
<li><strong>Tìm hiểu cách tiếp cận</strong>Đọc <?php echo $p2g_method ? '<a href="' . esc_url( $p2g_method ) . '">trang Phương pháp</a>' : 'trang Phương pháp'; ?> để biết Play2Grow quan niệm thế nào về việc học tiếng Anh qua trải nghiệm.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Thử một hoạt động nhỏ</strong>Chọn một gợi ý trong <?php echo $p2g_blog ? '<a href="' . esc_url( $p2g_blog ) . '">Góc học tập</a>' : 'Góc học tập'; ?> và thử cùng con trong 10–15 phút. Quan sát xem con hứng thú với điều gì.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Để lại thông tin</strong><?php echo $p2g_contact ? '<a href="' . esc_url( $p2g_contact ) . '">Gửi câu hỏi hoặc đăng ký nhận cập nhật</a>' : 'Gửi câu hỏi hoặc đăng ký nhận cập nhật'; ?> về sản phẩm Play2Grow.</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></div>
<!-- /wp:group -->
