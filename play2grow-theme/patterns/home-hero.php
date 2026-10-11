<?php
/**
 * Title: Trang chủ – Mở đầu (hero)
 * Slug: play2grow/home-hero
 * Categories: play2grow, banner
 * Keywords: hero, mở đầu, trang chủ
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-hero","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-hero"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'Học tiếng Anh qua trải nghiệm', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo wp_kses_post( __( 'Học tiếng Anh từ những điều trẻ <mark>nhìn thấy, chạm vào</mark> và yêu thích', 'play2grow-theme' ) ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"p2g-hero__lead"} -->
<p class="p2g-hero__lead"><?php esc_html_e( 'Play2Grow là trung tâm dành cho trẻ với lớp STEAM English cùng các lớp mỹ thuật, âm nhạc và nhảy. Chúng tôi tập trung vào việc học qua vui chơi, khám phá và thực hành – để tiếng Anh và năng khiếu không chỉ nằm trên trang vở mà xuất hiện trong chính những gì trẻ đang trải nghiệm.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( __( 'Xem các chương trình', 'play2grow-theme' ), p2g_page_link( 'chuong-trinh' ) ),
		array( __( 'Đăng ký tư vấn', 'play2grow-theme' ), p2g_contact_url_any(), 'outline' ),
	)
);
?>

<!-- wp:paragraph {"className":"p2g-hero__tagline"} -->
<p class="p2g-hero__tagline"><?php esc_html_e( 'Explore the world. Play with English. Grow every day.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"p2g-hero__art"} -->
<figure class="wp-block-image size-full p2g-hero__art"><img src="<?php echo esc_url( p2g_image_url( 'hero-explore.svg' ) ); ?>" alt="<?php echo esc_attr__( 'Minh họa: kính lúp soi chiếc lá, mặt trời, cây xanh và các bong bóng hội thoại tiếng Anh', 'play2grow-theme' ); ?>"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
