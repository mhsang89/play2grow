<?php
/**
 * Customizer: company information, CTA, social links, footer, colors.
 *
 * Every company field is empty by default. Empty fields are never shown to
 * visitors – the theme does not invent business data.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Company information fields: key => [label, type, description].
 *
 * @return array
 */
function p2g_company_fields() {
	return array(
		'p2g_legal_name'        => array( __( 'Tên pháp nhân đầy đủ', 'play2grow' ), 'text', __( 'Ghi đúng như trên Giấy chứng nhận đăng ký doanh nghiệp.', 'play2grow' ) ),
		'p2g_tax_code'          => array( __( 'Mã số doanh nghiệp / Mã số thuế', 'play2grow' ), 'text', '' ),
		'p2g_registration'      => array( __( 'Thông tin cấp đăng ký', 'play2grow' ), 'text', __( 'Ví dụ: "Do Sở Kế hoạch và Đầu tư ... cấp lần đầu ngày ...". Chỉ điền khi đã đối chiếu giấy tờ.', 'play2grow' ) ),
		'p2g_hq_address'        => array( __( 'Địa chỉ trụ sở đăng ký', 'play2grow' ), 'textarea', '' ),
		'p2g_operating_address' => array( __( 'Địa chỉ cơ sở hoạt động', 'play2grow' ), 'textarea', __( 'Mỗi cơ sở một dòng. Để trống nếu trùng trụ sở hoặc chưa có.', 'play2grow' ) ),
		'p2g_representative'    => array( __( 'Người đại diện theo pháp luật', 'play2grow' ), 'text', __( 'Chỉ điền nếu doanh nghiệp đồng ý công bố.', 'play2grow' ) ),
		'p2g_phone'             => array( __( 'Hotline / Số điện thoại', 'play2grow' ), 'text', '' ),
		'p2g_email'             => array( __( 'Email liên hệ', 'play2grow' ), 'email', '' ),
		'p2g_license'           => array( __( 'Giấy phép hoạt động / giấy phép chuyên ngành', 'play2grow' ), 'textarea', __( 'Chỉ điền khi đã được cấp và còn hiệu lực.', 'play2grow' ) ),
		'p2g_hours'             => array( __( 'Thời gian làm việc', 'play2grow' ), 'text', __( 'Ví dụ: Thứ Hai – Thứ Bảy, 8:00 – 17:30', 'play2grow' ) ),
		'p2g_map_url'           => array( __( 'Liên kết bản đồ (Google Maps…)', 'play2grow' ), 'url', __( 'Chỉ là liên kết; theme không nhúng bản đồ để tránh tải script bên thứ ba.', 'play2grow' ) ),
	);
}

/**
 * Social profile fields.
 *
 * @return array key => [label, icon]
 */
function p2g_social_fields() {
	return array(
		'p2g_social_facebook'  => array( 'Facebook', 'facebook' ),
		'p2g_social_zalo'      => array( 'Zalo', 'chat' ),
		'p2g_social_youtube'   => array( 'YouTube', 'youtube' ),
		'p2g_social_tiktok'    => array( 'TikTok', 'tiktok' ),
		'p2g_social_instagram' => array( 'Instagram', 'instagram' ),
		'p2g_social_linkedin'  => array( 'LinkedIn', 'linkedin' ),
	);
}

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
			'description' => __( 'Thông tin doanh nghiệp, nút đăng ký, mạng xã hội và chân trang.', 'play2grow' ),
			'priority'    => 30,
		)
	);

	// Company info.
	$wp_customize->add_section(
		'p2g_company',
		array(
			'title'       => __( 'Thông tin doanh nghiệp', 'play2grow' ),
			'description' => __( 'Chỉ điền thông tin đã được xác minh bằng giấy tờ chính thức. Trường để trống sẽ được ẩn khỏi website. Xem thêm file COMPANY_INFO_REQUIRED.md đi kèm theme.', 'play2grow' ),
			'panel'       => 'p2g_panel',
		)
	);

	foreach ( p2g_company_fields() as $key => $field ) {
		list( $label, $type, $desc ) = $field;

		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'p2g_sanitize_textarea';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		}

		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => $sanitize,
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $label,
				'description' => $desc,
				'section'     => 'p2g_company',
				'type'        => $type,
			)
		);
	}

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

	// Social.
	$wp_customize->add_section(
		'p2g_social',
		array(
			'title'       => __( 'Mạng xã hội', 'play2grow' ),
			'description' => __( 'Dán đường dẫn đầy đủ (https://…). Trường để trống sẽ không hiển thị.', 'play2grow' ),
			'panel'       => 'p2g_panel',
		)
	);
	foreach ( p2g_social_fields() as $key => $field ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $field[0],
				'section' => 'p2g_social',
				'type'    => 'url',
			)
		);
	}

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

/**
 * Get one company field value (trimmed).
 *
 * @param string $key Field key without the "p2g_" prefix, e.g. "phone".
 * @return string
 */
function p2g_company( $key ) {
	return trim( (string) get_theme_mod( 'p2g_' . $key, '' ) );
}

/**
 * Configured social links.
 *
 * @return array[] Each: [url, label, icon].
 */
function p2g_social_links() {
	$links = array();
	foreach ( p2g_social_fields() as $key => $field ) {
		$url = trim( (string) get_theme_mod( $key, '' ) );
		if ( '' !== $url ) {
			$links[] = array( $url, $field[0], $field[1] );
		}
	}

	return $links;
}
