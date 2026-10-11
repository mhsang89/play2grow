<?php
/**
 * Shared helpers: settings, company data, icons, editor notes.
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default settings (Cài đặt → Play2Grow).
 *
 * @return array
 */
function p2gc_settings_defaults() {
	return array(
		'lead_recipient'      => '',
		'lead_store'          => 1,
		'lead_retention_days' => 180,
		'lead_interests'      => '',
		'lead_age_field'      => 1,
		'lead_success_text'   => '',
		'consent_text'        => '',
	);
}

/**
 * Read one setting.
 *
 * @param string $key Key.
 * @return mixed
 */
function p2gc_setting( $key ) {
	$defaults = p2gc_settings_defaults();
	$settings = get_option( 'p2gc_settings', array() );
	$settings = is_array( $settings ) ? wp_parse_args( $settings, $defaults ) : $defaults;

	return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
}

/**
 * Company fields: key => [label, type, description]. All empty by default.
 *
 * @return array
 */
function p2gc_company_fields() {
	return array(
		'legal_name'        => array( __( 'Tên pháp nhân đầy đủ', 'play2grow-core' ), 'text', __( 'Ghi đúng như trên Giấy chứng nhận đăng ký doanh nghiệp. Không điền tên "dự kiến".', 'play2grow-core' ) ),
		'tax_code'          => array( __( 'Mã số doanh nghiệp / Mã số thuế', 'play2grow-core' ), 'text', '' ),
		'registration'      => array( __( 'Thông tin cấp đăng ký', 'play2grow-core' ), 'text', __( 'Ví dụ: "Do Sở … cấp lần đầu ngày …". Chỉ điền khi đã đối chiếu giấy tờ.', 'play2grow-core' ) ),
		'hq_address'        => array( __( 'Địa chỉ trụ sở đăng ký', 'play2grow-core' ), 'textarea', '' ),
		'operating_address' => array( __( 'Địa chỉ cơ sở hoạt động', 'play2grow-core' ), 'textarea', __( 'Mỗi cơ sở một dòng. Để trống nếu chưa có.', 'play2grow-core' ) ),
		'representative'    => array( __( 'Người đại diện theo pháp luật', 'play2grow-core' ), 'text', __( 'Chỉ điền nếu đồng ý công bố.', 'play2grow-core' ) ),
		'phone'             => array( __( 'Hotline / Số điện thoại', 'play2grow-core' ), 'text', '' ),
		'email'             => array( __( 'Email liên hệ', 'play2grow-core' ), 'email', '' ),
		'license'           => array( __( 'Giấy phép hoạt động / giấy phép chuyên ngành', 'play2grow-core' ), 'textarea', __( 'Chỉ điền khi đã được cấp và còn hiệu lực.', 'play2grow-core' ) ),
		'hours'             => array( __( 'Thời gian làm việc', 'play2grow-core' ), 'text', __( 'Ví dụ: Thứ Hai – Thứ Sáu, 8:00 – 17:30', 'play2grow-core' ) ),
		'map_url'           => array( __( 'Liên kết bản đồ', 'play2grow-core' ), 'url', __( 'Chỉ là liên kết; không nhúng bản đồ để tránh tải script bên thứ ba.', 'play2grow-core' ) ),
	);
}

/**
 * Social fields: key => [label, icon].
 *
 * @return array
 */
function p2gc_social_fields() {
	return array(
		'facebook'  => array( 'Facebook', 'facebook' ),
		'zalo'      => array( 'Zalo', 'chat' ),
		'youtube'   => array( 'YouTube', 'youtube' ),
		'tiktok'    => array( 'TikTok', 'tiktok' ),
		'instagram' => array( 'Instagram', 'instagram' ),
		'linkedin'  => array( 'LinkedIn', 'linkedin' ),
	);
}

/**
 * One company value.
 *
 * @param string $key Field key (see p2gc_company_fields()).
 * @return string
 */
function p2gc_company( $key ) {
	$data = get_option( 'p2gc_company', array() );

	return is_array( $data ) && isset( $data[ $key ] ) ? trim( (string) $data[ $key ] ) : '';
}

/**
 * Configured social links.
 *
 * @return array[] [url, label, icon]
 */
function p2gc_social_links() {
	$data  = get_option( 'p2gc_social', array() );
	$links = array();
	foreach ( p2gc_social_fields() as $key => $field ) {
		$url = is_array( $data ) && isset( $data[ $key ] ) ? trim( (string) $data[ $key ] ) : '';
		if ( '' !== $url ) {
			$links[] = array( $url, $field[0], $field[1], $key );
		}
	}

	return $links;
}

/**
 * Whether the visitor may see internal notes.
 *
 * @return bool
 */
function p2gc_can_see_notes() {
	return is_user_logged_in() && current_user_can( 'edit_posts' );
}

/**
 * Editor-only note.
 *
 * @param string $text Text (basic HTML allowed).
 * @return string
 */
function p2gc_editor_note( $text ) {
	if ( ! p2gc_can_see_notes() ) {
		return '';
	}

	return '<div class="p2g-editor-note" role="note" style="margin:16px auto;padding:14px 18px;border:2px dashed #b26200;border-radius:10px;background:#fff7e6;color:#5c3300;font-size:.92rem;line-height:1.55"><strong>' . esc_html__( 'Ghi chú nội bộ (khách truy cập không nhìn thấy):', 'play2grow-core' ) . '</strong> ' . wp_kses_post( $text ) . '</div>';
}

/**
 * Phone number to tel: URL.
 *
 * @param string $phone Phone.
 * @return string
 */
function p2gc_tel( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Inline SVG icon (decorative).
 *
 * @param string $name Icon.
 * @return string
 */
function p2gc_icon( $name ) {
	$paths = array(
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'map-pin'   => '<path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
		'building'  => '<path d="M4 21V5a2 2 0 012-2h8a2 2 0 012 2v16M16 9h2a2 2 0 012 2v10M8 7h4M8 11h4M8 15h4M3 21h18"/>',
		'id'        => '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.6-1.5 1.7-2 3-2s2.4.5 3 2M15 10h3M15 13h3"/>',
		'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/>',
		'book'      => '<path d="M4 5a2 2 0 012-2h13v16H6a2 2 0 00-2 2V5z"/><path d="M4 19a2 2 0 012-2h13"/>',
		'check'     => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'alert'     => '<path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.5"/>',
		'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 00-4 4v2H7v4h3v7h4v-7h3l1-4h-4V8z"/>',
		'youtube'   => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.5v5l4.5-2.5L10 9.5z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5v.01"/>',
		'tiktok'    => '<path d="M14 3v11.5a3.5 3.5 0 11-3.5-3.5M14 3c.5 2.8 2.4 4.5 5 4.6"/>',
		'chat'      => '<path d="M4 5h16v11H9l-5 4V5z"/>',
		'linkedin'  => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 014 0v4M12 10v7"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg class="p2g-icon p2g-icon--' . esc_attr( $name ) . '" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $name ] . '</svg>';
}

/**
 * Allowed SVG tags for wp_kses.
 *
 * @return array
 */
function p2gc_kses_svg() {
	$common = array_fill_keys( array( 'd', 'cx', 'cy', 'r', 'x', 'y', 'width', 'height', 'rx', 'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin' ), true );

	return array(
		'svg'    => array_merge( $common, array_fill_keys( array( 'class', 'aria-hidden', 'focusable', 'viewbox' ), true ) ),
		'path'   => $common,
		'circle' => $common,
		'rect'   => $common,
	);
}

/**
 * Echo an icon.
 *
 * @param string $name Icon.
 */
function p2gc_the_icon( $name ) {
	echo wp_kses( p2gc_icon( $name ), p2gc_kses_svg() );
}

/**
 * URL of the contact page ('' if none is published). Themes can override with the
 * "p2gc_contact_url" filter.
 *
 * @return string
 */
function p2gc_contact_url() {
	$url  = '';
	$page = get_page_by_path( 'lien-he' );
	if ( $page && 'publish' === $page->post_status ) {
		$url = get_permalink( $page );
	} else {
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				's'              => '[p2g_contact_form',
				'fields'         => 'ids',
			)
		);
		$url   = $found ? get_permalink( $found[0] ) : '';
	}

	return (string) apply_filters( 'p2gc_contact_url', $url );
}

/**
 * Print the company information list. Only filled fields are shown; an editor-only
 * reminder is printed when nothing has been entered.
 *
 * @param string $variant "footer" or "card".
 */
function p2gc_company_info( $variant = 'footer' ) {
	$rows = array();

	$legal = p2gc_company( 'legal_name' );
	if ( $legal ) {
		$rows[] = array( 'building', '', '<strong>' . esc_html( $legal ) . '</strong>' );
	}
	$tax = p2gc_company( 'tax_code' );
	if ( $tax ) {
		$reg    = p2gc_company( 'registration' );
		$rows[] = array( 'id', __( 'Mã số doanh nghiệp:', 'play2grow-core' ), esc_html( $tax ) . ( $reg ? '<br><span class="company-info__sub">' . esc_html( $reg ) . '</span>' : '' ) );
	}
	$rep = p2gc_company( 'representative' );
	if ( $rep ) {
		$rows[] = array( 'user', __( 'Người đại diện:', 'play2grow-core' ), esc_html( $rep ) );
	}
	$hq = p2gc_company( 'hq_address' );
	if ( $hq ) {
		$rows[] = array( 'map-pin', __( 'Trụ sở:', 'play2grow-core' ), nl2br( esc_html( $hq ) ) );
	}
	$op = p2gc_company( 'operating_address' );
	if ( $op ) {
		$rows[] = array( 'map-pin', __( 'Cơ sở hoạt động:', 'play2grow-core' ), nl2br( esc_html( $op ) ) );
	}
	$phone = p2gc_company( 'phone' );
	if ( $phone ) {
		$rows[] = array( 'phone', __( 'Hotline:', 'play2grow-core' ), '<a href="' . esc_url( p2gc_tel( $phone ), array( 'tel' ) ) . '">' . esc_html( $phone ) . '</a>' );
	}
	$email = p2gc_company( 'email' );
	if ( $email && is_email( $email ) ) {
		$rows[] = array( 'mail', __( 'Email:', 'play2grow-core' ), '<a href="' . esc_url( 'mailto:' . antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a>' );
	}
	$hours = p2gc_company( 'hours' );
	if ( $hours ) {
		$rows[] = array( 'clock', __( 'Giờ làm việc:', 'play2grow-core' ), esc_html( $hours ) );
	}
	$license = p2gc_company( 'license' );
	if ( $license ) {
		$rows[] = array( 'book', __( 'Giấy phép:', 'play2grow-core' ), nl2br( esc_html( $license ) ) );
	}
	$map = p2gc_company( 'map_url' );
	if ( $map ) {
		$rows[] = array( 'globe', '', '<a href="' . esc_url( $map ) . '" target="_blank" rel="noopener">' . esc_html__( 'Xem chỉ đường trên bản đồ', 'play2grow-core' ) . '<span class="screen-reader-text"> ' . esc_html__( '(mở trong thẻ mới)', 'play2grow-core' ) . '</span></a>' );
	}

	if ( ! $rows ) {
		echo p2gc_editor_note( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
			sprintf(
				/* translators: %s: Customizer URL. */
				__( 'Chưa có thông tin doanh nghiệp nào được nhập. Vào <a href="%s">Giao diện → Tùy biến → Play2Grow – Thông tin doanh nghiệp</a> để điền các thông tin đã xác minh.', 'play2grow-core' ),
				esc_url( admin_url( 'customize.php?autofocus[section]=p2gc_company' ) )
			)
		);
		return;
	}

	echo '<ul class="company-info company-info--' . esc_attr( $variant ) . '">';
	foreach ( $rows as $row ) {
		echo '<li>';
		p2gc_the_icon( $row[0] );
		echo '<span>';
		if ( $row[1] ) {
			echo '<span class="company-info__label">' . esc_html( $row[1] ) . '</span> ';
		}
		echo wp_kses_post( $row[2] );
		echo '</span></li>';
	}
	echo '</ul>';
}
