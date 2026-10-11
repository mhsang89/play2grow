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
	array( 'spot-explore.svg', 'Phương pháp', 'Học tiếng Anh qua trải nghiệm là gì', p2g_page_link( 'phuong-phap-hoc' ) ),
	array( 'spot-play.svg', 'Sản phẩm', 'Trải nghiệm học tập của Play2Grow', p2g_page_link( 'san-pham' ) ),
	array( 'spot-home.svg', 'Phụ huynh', 'Đồng hành cùng con tại nhà', p2g_page_link( 'phu-huynh' ) ),
	array( 'spot-create.svg', 'Góc học tập', 'Ý tưởng hoạt động và trò chơi', p2g_posts_page_link() ),
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
