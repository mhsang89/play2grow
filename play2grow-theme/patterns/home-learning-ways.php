<?php
/**
 * Title: Định hướng – Những cách tiếng Anh bước vào ngày của trẻ
 * Slug: play2grow/learning-ways
 * Categories: play2grow, featured
 * Keywords: tính năng, định hướng, khám phá, vui chơi
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_tiles = array(
	array( 'spot-explore.svg', 'p2g-tile--mint', 'Khám phá', 'Quan sát chiếc lá, con kiến hay đám mây rồi gọi tên, mô tả chúng bằng tiếng Anh. Sự tò mò là động lực tự nhiên để trẻ muốn biết thêm một từ mới.' ),
	array( 'spot-play.svg', 'p2g-tile--sun', 'Vui chơi', 'Trò chơi tạo ra lý do để nói: đến lượt ai, tìm thấy gì, ai về đích trước. Trẻ lặp lại câu chữ nhiều lần mà không cảm thấy đó là bài tập.' ),
	array( 'spot-home.svg', 'p2g-tile--sky', 'Đời sống hằng ngày', 'Ăn sáng, mặc quần áo, dọn đồ chơi… những việc lặp lại mỗi ngày là cơ hội để dùng đi dùng lại cùng một mẫu câu một cách tự nhiên.' ),
	array( 'spot-talk.svg', 'p2g-tile--blush', 'Giao tiếp', 'Hỏi – đáp, kể lại, đóng vai người bán và người mua. Trẻ dùng tiếng Anh để làm một việc cụ thể, không chỉ để trả lời câu hỏi trong bài.' ),
	array( 'spot-create.svg', 'p2g-tile--sun', 'Sáng tạo', 'Vẽ, cắt dán, làm mô hình rồi giới thiệu sản phẩm của mình. Khi tự tay làm ra một thứ, trẻ có rất nhiều điều muốn nói về nó.' ),
	array( 'spot-grow.svg', 'p2g-tile--mint', 'Lớn lên từng ngày', 'Ngôn ngữ cần thời gian để nảy mầm. Những trải nghiệm nhỏ, đều đặn và vui vẻ có giá trị hơn những buổi học dài nhưng căng thẳng.' ),
);
?>
<!-- wp:group {"align":"full","className":"p2g-section p2g-section--cream","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section p2g-section--cream"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Định hướng học tập</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Những cách tiếng Anh bước vào ngày của trẻ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Đây là những hướng tiếp cận định hình cách Play2Grow suy nghĩ về việc học tiếng Anh, trong lớp học cũng như ở nhà.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"p2g-tiles","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-tiles">
<?php foreach ( $p2g_tiles as $p2g_tile ) : ?>
<!-- wp:group {"className":"p2g-tile <?php echo esc_attr( $p2g_tile[1] ); ?>","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-tile <?php echo esc_attr( $p2g_tile[1] ); ?>"><!-- wp:image {"width":"72px","height":"72px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( p2g_image_url( $p2g_tile[0] ) ); ?>" alt="" style="width:72px;height:72px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $p2g_tile[2] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $p2g_tile[3] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
