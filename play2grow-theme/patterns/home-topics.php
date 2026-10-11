<?php
/**
 * Title: Khám phá theo chủ đề
 * Slug: play2grow/topics
 * Categories: play2grow, featured
 * Keywords: chủ đề, thiên nhiên, con vật, đồ ăn, địa điểm
 * Viewport Width: 1400
 *
 * Cards come from post categories with published articles (empty topics are hidden).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--cream","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--cream"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'Khám phá theo chủ đề', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Thế giới quanh con là một cuốn sách tiếng Anh', 'play2grow-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Chọn một chủ đề con đang yêu thích – thiên nhiên, con vật, đồ ăn, sinh hoạt hằng ngày hay những nơi con thường đến – để tìm hoạt động và mẫu câu phù hợp.', 'play2grow-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[p2g_topics slugs="<?php echo esc_attr( p2g_topic_slugs() ); ?>"]
<!-- /wp:shortcode --></div>
<!-- /wp:group -->
