<?php
/**
 * Settings page: Cài đặt → Play2Grow (contact form, lead storage and retention).
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu entry.
 */
function p2gc_admin_menu() {
	add_options_page(
		__( 'Play2Grow – Form liên hệ', 'play2grow-core' ),
		__( 'Play2Grow', 'play2grow-core' ),
		'manage_options',
		'p2gc-settings',
		'p2gc_render_settings_page'
	);
}
add_action( 'admin_menu', 'p2gc_admin_menu' );

/**
 * Register the option.
 */
function p2gc_register_settings() {
	register_setting(
		'p2gc_settings_group',
		'p2gc_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'p2gc_sanitize_settings',
			'default'           => p2gc_settings_defaults(),
		)
	);
}
add_action( 'admin_init', 'p2gc_register_settings' );

/**
 * Sanitize settings.
 *
 * @param mixed $input Input.
 * @return array
 */
function p2gc_sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$out   = p2gc_settings_defaults();

	$emails = array();
	foreach ( explode( ',', isset( $input['lead_recipient'] ) ? (string) $input['lead_recipient'] : '' ) as $email ) {
		$email = sanitize_email( trim( $email ) );
		if ( $email && is_email( $email ) ) {
			$emails[] = $email;
		}
	}
	$out['lead_recipient']      = implode( ', ', array_unique( $emails ) );
	$out['lead_store']          = empty( $input['lead_store'] ) ? 0 : 1;
	$out['lead_age_field']      = empty( $input['lead_age_field'] ) ? 0 : 1;
	$out['lead_retention_days'] = isset( $input['lead_retention_days'] ) ? max( 0, min( 3650, absint( $input['lead_retention_days'] ) ) ) : 180;
	$out['lead_interests']      = isset( $input['lead_interests'] ) ? sanitize_textarea_field( $input['lead_interests'] ) : '';
	$out['lead_success_text']   = isset( $input['lead_success_text'] ) ? sanitize_text_field( $input['lead_success_text'] ) : '';
	$out['consent_text']        = isset( $input['consent_text'] ) ? sanitize_text_field( $input['consent_text'] ) : '';

	return $out;
}

/**
 * Render the page.
 */
function p2gc_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = wp_parse_args( (array) get_option( 'p2gc_settings', array() ), p2gc_settings_defaults() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Play2Grow – Form liên hệ và dữ liệu đăng ký', 'play2grow-core' ); ?></h1>
		<h2 id="p2g-form-settings"><?php esc_html_e( 'Form liên hệ / đăng ký nhận thông tin', 'play2grow-core' ); ?></h2>
		<p><?php echo wp_kses_post( __( 'Chèn form vào bất kỳ trang nào bằng shortcode <code>[p2g_contact_form]</code>. Trang dùng mẫu "Liên hệ" tự hiển thị form.', 'play2grow-core' ) ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'p2gc_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="p2g-recipient"><?php esc_html_e( 'Email nhận thông báo', 'play2grow-core' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="p2g-recipient" name="p2gc_settings[lead_recipient]" value="<?php echo esc_attr( $s['lead_recipient'] ); ?>" placeholder="ten@tenmien.vn">
						<p class="description"><?php esc_html_e( 'Có thể nhập nhiều email, cách nhau bằng dấu phẩy. Nên cài plugin SMTP để email không vào thư rác; hãy gửi thử một đăng ký sau khi cài đặt.', 'play2grow-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Lưu đăng ký trong trang quản trị', 'play2grow-core' ); ?></th>
					<td>
						<label><input type="checkbox" name="p2gc_settings[lead_store]" value="1" <?php checked( $s['lead_store'] ); ?>> <?php esc_html_e( 'Lưu vào mục "Đăng ký liên hệ" (chỉ quản trị viên xem được). Khuyến nghị bật để không mất đăng ký khi email gặp sự cố.', 'play2grow-core' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-retention"><?php esc_html_e( 'Thời gian lưu trữ (ngày)', 'play2grow-core' ); ?></label></th>
					<td>
						<input type="number" min="0" max="3650" id="p2g-retention" name="p2gc_settings[lead_retention_days]" value="<?php echo esc_attr( (string) $s['lead_retention_days'] ); ?>" class="small-text">
						<p class="description"><?php esc_html_e( 'Đăng ký cũ hơn số ngày này sẽ tự động bị xóa vĩnh viễn. Nhập 0 để không tự xóa. Hãy thống nhất con số này với nội dung Chính sách quyền riêng tư.', 'play2grow-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Trường nhóm tuổi của trẻ', 'play2grow-core' ); ?></th>
					<td>
						<label><input type="checkbox" name="p2gc_settings[lead_age_field]" value="1" <?php checked( $s['lead_age_field'] ); ?>> <?php esc_html_e( 'Hiển thị trường "Nhóm tuổi của trẻ" (không bắt buộc, chỉ chọn khoảng tuổi)', 'play2grow-core' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-interests"><?php esc_html_e( 'Lựa chọn "Anh/chị quan tâm đến"', 'play2grow-core' ); ?></label></th>
					<td>
						<textarea id="p2g-interests" name="p2gc_settings[lead_interests]" rows="5" class="large-text"><?php echo esc_textarea( $s['lead_interests'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Mỗi dòng một lựa chọn. Để trống để dùng danh sách mặc định (tên các trang chương trình đã xuất bản + các lựa chọn chung).', 'play2grow-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-success"><?php esc_html_e( 'Thông báo gửi thành công', 'play2grow-core' ); ?></label></th>
					<td><input type="text" class="large-text" id="p2g-success" name="p2gc_settings[lead_success_text]" value="<?php echo esc_attr( $s['lead_success_text'] ); ?>" placeholder="<?php echo esc_attr( p2gc_success_message() ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-consent-text"><?php esc_html_e( 'Nội dung ô đồng ý', 'play2grow-core' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="p2g-consent-text" name="p2gc_settings[consent_text]" value="<?php echo esc_attr( $s['consent_text'] ); ?>">
						<p class="description"><?php esc_html_e( 'Để trống để dùng nội dung mặc định có liên kết tới Chính sách quyền riêng tư.', 'play2grow-core' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Lưu cài đặt', 'play2grow-core' ) ); ?>
		</form>

		<h2><?php esc_html_e( 'Thông tin doanh nghiệp', 'play2grow-core' ); ?></h2>
		<p><a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=p2gc_company' ) ); ?>"><?php esc_html_e( 'Mở Tùy biến → Play2Grow – Thông tin doanh nghiệp', 'play2grow-core' ); ?></a></p>
	</div>
	<?php
}

/**
 * Admin notice when the form cannot deliver anywhere or email is missing.
 */
function p2gc_admin_notices() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'settings_page_p2gc-settings', 'edit-p2g_lead', 'themes' ), true ) ) {
		return;
	}

	if ( ! p2gc_form_ready() ) {
		echo '<div class="notice notice-error"><p>' . wp_kses_post(
			sprintf(
				/* translators: %s: settings URL. */
				__( '<strong>Play2Grow:</strong> Form liên hệ chưa có kênh nhận thông tin nên đang bị ẩn với khách truy cập. <a href="%s">Cấu hình ngay</a>.', 'play2grow-core' ),
				esc_url( admin_url( 'options-general.php?page=p2gc-settings#p2g-form-settings' ) )
			)
		) . '</p></div>';
	} elseif ( ! p2gc_recipient_list() ) {
		echo '<div class="notice notice-warning"><p>' . wp_kses_post(
			sprintf(
				/* translators: %s: settings URL. */
				__( '<strong>Play2Grow:</strong> Đăng ký mới đang được lưu trong mục "Đăng ký liên hệ" nhưng chưa có email nhận thông báo. <a href="%s">Thêm email</a> để không bỏ lỡ yêu cầu.', 'play2grow-core' ),
				esc_url( admin_url( 'options-general.php?page=p2gc-settings#p2g-form-settings' ) )
			)
		) . '</p></div>';
	}
}
add_action( 'admin_notices', 'p2gc_admin_notices' );

