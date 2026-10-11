<?php
/**
 * Customizer: CTA, top bar, footer, colors. Company information and social links are
 * managed by the Play2Grow Core plugin (stored as options, independent of the theme).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize a multi-line plain text field.
 *
 * @param string $value Value.
 * @return string
 */
function p2g_sanitize_textarea( $value ) {
	return sanitize_textarea_field( $value );
}

/**
 * Sanitize a page ID (must be a published page or 0).
 *
 * @param mixed $value Value.
 * @return int
 */
function p2g_sanitize_page( $value ) {
	$id = absint( $value );

	return ( $id && 'page' === get_post_type( $id ) ) ? $id : 0;
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Value.
 * @return bool
 */
function p2g_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function p2g_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'p2g_panel',
		array(
			'title'       => __( 'Play2Grow', 'play2grow' ),
			'description' => __( 'Nút đăng ký, thanh trên cùng và chân trang. Thông tin doanh nghiệp và mạng xã hội nằm ở mục "Play2Grow – Thông tin doanh nghiệp" (plugin Play2Grow Core).', 'play2grow' ),
			'priority'    => 30,
		)
	);

	// Header & CTA.
	$wp_customize->add_section(
		'p2g_header',
		array(
			'title' => __( 'Đầu trang & nút đăng ký', 'play2grow' ),
			'panel' => 'p2g_panel',
		)
	);
	$wp_customize->add_setting(
		'p2g_cta_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'p2g_cta_label',
		array(
			'label'       => __( 'Chữ trên nút chính', 'play2grow' ),
			'description' => __( 'Mặc định: "Đăng ký nhận thông tin".', 'play2grow' ),
			'section'     => 'p2g_header',
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting(
		'p2g_cta_page',
		array(
			'default'           => 0,
			'sanitize_callback' => 'p2g_sanitize_page',
		)
	);
	$wp_customize->add_control(
		'p2g_cta_page',
		array(
			'label'       => __( 'Trang đích của nút đăng ký', 'play2grow' ),
			'description' => __( 'Nếu để trống, theme dùng trang có đường dẫn /lien-he/ hoặc trang dùng mẫu "Liên hệ". Nếu không tìm thấy trang đã xuất bản, nút sẽ tự ẩn để tránh dẫn tới trang lỗi.', 'play2grow' ),
			'section'     => 'p2g_header',
			'type'        => 'dropdown-pages',
		)
	);
	$wp_customize->add_setting(
		'p2g_topbar_text',
		array(
			'default'           => 'Play. Explore. Learn. Grow.',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'p2g_topbar_text',
		array(
			'label'       => __( 'Dòng chữ bên trái thanh trên cùng', 'play2grow' ),
			'description' => __( 'Để trống để ẩn.', 'play2grow' ),
			'section'     => 'p2g_header',
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting(
		'p2g_show_floating',
		array(
			'default'           => true,
			'sanitize_callback' => 'p2g_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'p2g_show_floating',
		array(
			'label'       => __( 'Hiện nút liên hệ nổi và thanh đăng ký cố định trên điện thoại', 'play2grow' ),
			'description' => __( 'Nút gọi/Zalo chỉ xuất hiện khi đã nhập hotline hoặc đường dẫn Zalo.', 'play2grow' ),
			'section'     => 'p2g_header',
			'type'        => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'p2g_show_topbar',
		array(
			'default'           => true,
			'sanitize_callback' => 'p2g_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'p2g_show_topbar',
		array(
			'label'       => __( 'Hiện thanh trên cùng', 'play2grow' ),
			'description' => __( 'Hiển thị dòng chữ, menu "Thanh trên cùng", hotline/email (nếu đã nhập) và mạng xã hội.', 'play2grow' ),
			'section'     => 'p2g_header',
			'type'        => 'checkbox',
		)
	);

	// Footer.
	$wp_customize->add_section(
		'p2g_footer',
		array(
			'title' => __( 'Chân trang', 'play2grow' ),
			'panel' => 'p2g_panel',
		)
	);
	$wp_customize->add_setting(
		'p2g_footer_about',
		array(
			'default'           => '',
			'sanitize_callback' => 'p2g_sanitize_textarea',
		)
	);
	$wp_customize->add_control(
		'p2g_footer_about',
		array(
			'label'       => __( 'Đoạn giới thiệu ngắn', 'play2grow' ),
			'description' => __( 'Hiển thị dưới logo ở chân trang. Nếu để trống, theme dùng Khẩu hiệu (Tagline) trong Cài đặt → Tổng quan.', 'play2grow' ),
			'section'     => 'p2g_footer',
			'type'        => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'p2g_copyright',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'p2g_copyright',
		array(
			'label'       => __( 'Dòng bản quyền', 'play2grow' ),
			'description' => __( 'Mặc định: "© [năm] [Tên website]".', 'play2grow' ),
			'section'     => 'p2g_footer',
			'type'        => 'text',
		)
	);

	// Colors (core section).
	$colors = array(
		'p2g_color_primary' => array( __( 'Màu chủ đạo', 'play2grow' ), '#0E6B5C', __( 'Dùng cho nút, liên kết, tiêu đề nhấn. Nên giữ độ tương phản tối thiểu 4.5:1 với nền trắng.', 'play2grow' ) ),
		'p2g_color_accent'  => array( __( 'Màu nhấn', 'play2grow' ), '#FFB938', __( 'Dùng cho điểm nhấn trang trí và nút phụ (chữ màu tối trên nền màu nhấn).', 'play2grow' ) ),
	);
	foreach ( $colors as $key => $c ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $c[1],
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'       => $c[0],
					'description' => $c[2],
					'section'     => 'colors',
				)
			)
		);
	}
}
add_action( 'customize_register', 'p2g_customize_register' );
