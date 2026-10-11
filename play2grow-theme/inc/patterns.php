<?php
/**
 * Block pattern category. Pattern files in /patterns are registered automatically
 * by WordPress (6.0+) from their header comments.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the "Play2Grow" pattern category.
 */
function p2g_register_pattern_category() {
	register_block_pattern_category(
		'play2grow',
		array(
			'label'       => __( 'Play2Grow', 'play2grow' ),
			'description' => __( 'Các khối nội dung dựng sẵn theo nhận diện Play2Grow.', 'play2grow' ),
		)
	);
}
add_action( 'init', 'p2g_register_pattern_category', 9 );
