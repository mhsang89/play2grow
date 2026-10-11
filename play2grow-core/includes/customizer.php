<?php
/**
 * Customizer section for company information and social links. Values are stored as
 * options (p2gc_company, p2gc_social), not theme mods, so they survive a theme change.
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function p2gc_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'p2gc_company',
		array(
			'title'       => __( 'Play2Grow – Thông tin doanh nghiệp', 'play2grow-core' ),
			'description' => __( 'Chỉ điền thông tin đã được xác minh bằng giấy tờ chính thức. Trường để trống sẽ được ẩn khỏi website. Xem COMPANY_INFO_REQUIRED.md.', 'play2grow-core' ),
			'priority'    => 31,
		)
	);

	foreach ( p2gc_company_fields() as $key => $field ) {
		list( $label, $type, $desc ) = $field;

		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		}

		$id = 'p2gc_company[' . $key . ']';
		$wp_customize->add_setting(
			$id,
			array(
				'type'              => 'option',
				'default'           => '',
				'sanitize_callback' => $sanitize,
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $label,
				'description' => $desc,
				'section'     => 'p2gc_company',
				'type'        => $type,
			)
		);
	}

	$wp_customize->add_section(
		'p2gc_social',
		array(
			'title'       => __( 'Play2Grow – Mạng xã hội', 'play2grow-core' ),
			'description' => __( 'Dán đường dẫn đầy đủ (https://…) của tài khoản chính thức. Trường để trống sẽ không hiển thị.', 'play2grow-core' ),
			'priority'    => 32,
		)
	);
	foreach ( p2gc_social_fields() as $key => $field ) {
		$id = 'p2gc_social[' . $key . ']';
		$wp_customize->add_setting(
			$id,
			array(
				'type'              => 'option',
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field[0],
				'section' => 'p2gc_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'p2gc_customize_register' );
