<?php
/**
 * Title: Truy cập nhanh (dưới phần mở đầu)
 * Slug: play2grow/quicklinks
 * Categories: play2grow, featured
 * Keywords: truy cập nhanh, liên kết, điều hướng
 * Viewport Width: 1400
 *
 * Cards linking to existing pages only; a card is left out if its page is not published.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_links = array(
	array( 'spot-play.svg', __( 'Chương trình học', 'play2grow-theme' ), __( 'STEAM English, mỹ thuật, âm nhạc, nhảy', 'play2grow-theme' ), p2g_page_link( 'chuong-trinh' ) ),
	array( 'spot-explore.svg', __( 'Phương pháp', 'play2grow-theme' ), __( 'Học tiếng Anh qua trải nghiệm', 'play2grow-theme' ), p2g_page_link( 'phuong-phap-hoc' ) ),
	array( 'spot-home.svg', __( 'Phụ huynh', 'play2grow-theme' ), __( 'Đồng hành cùng con tại nhà', 'play2grow-theme' ), p2g_page_link( 'phu-huynh' ) ),
	array( 'spot-create.svg', __( 'Góc học tập', 'play2grow-theme' ), __( 'Ý tưởng hoạt động và trò chơi', 'play2grow-theme' ), p2g_posts_page_link() ),
);
$p2g_links = array_filter(
	$p2g_links,
	static function ( $l ) {
		return '' !== $l[3];
	}
);
if ( ! $p2g_links ) {
	return;
}
?>
<!-- wp:group {"align":"full","className":"p2g-quicklinks","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-quicklinks">
<?php foreach ( $p2g_links as $p2g_link ) : ?>
<!-- wp:group {"className":"p2g-quicklink","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-quicklink"><!-- wp:image {"width":"56px","height":"56px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( p2g_image_url( $p2g_link[0] ) ); ?>" alt="" style="width:56px;height:56px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"className":"p2g-quicklink__title"} -->
<h2 class="wp-block-heading p2g-quicklink__title"><a href="<?php echo esc_url( $p2g_link[3] ); ?>"><?php echo esc_html( $p2g_link[1] ); ?></a></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $p2g_link[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
