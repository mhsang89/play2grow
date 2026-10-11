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
<p class="eyebrow"><?php esc_html_e( 'Cách bắt đầu', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Ba bước nhỏ để cùng con khám phá tiếng Anh', 'play2grow-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:list {"ordered":true,"className":"p2g-steps"} -->
<ol class="wp-block-list p2g-steps"><!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Chọn chương trình con yêu thích', 'play2grow-theme' ); ?></strong><?php
/* translators: 1: link to programs page, 2: link to method page. */
echo wp_kses_post( sprintf( __( 'Xem %1$s và đọc %2$s của Play2Grow.', 'play2grow-theme' ), p2g_maybe_link( $p2g_programs, __( 'các chương trình học', 'play2grow-theme' ) ), p2g_maybe_link( $p2g_method, __( 'phương pháp', 'play2grow-theme' ) ) ) );
?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Để lại thông tin tư vấn', 'play2grow-theme' ); ?></strong><?php
/* translators: %s: link to the contact page. */
echo wp_kses_post( sprintf( __( '%s về độ tuổi và sở thích của con; Play2Grow sẽ liên hệ để tư vấn lớp, lịch học và học phí.', 'play2grow-theme' ), p2g_maybe_link( $p2g_contact, __( 'Gửi thông tin', 'play2grow-theme' ) ) ) );
?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Đồng hành cùng con tại nhà', 'play2grow-theme' ); ?></strong><?php
/* translators: %s: link to the learning corner (blog). */
echo wp_kses_post( sprintf( __( 'Chọn một gợi ý trong %s để cùng con tiếp tục dùng tiếng Anh trong sinh hoạt hằng ngày.', 'play2grow-theme' ), p2g_maybe_link( $p2g_blog, __( 'Góc học tập', 'play2grow-theme' ) ) ) );
?></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></div>
<!-- /wp:group -->
