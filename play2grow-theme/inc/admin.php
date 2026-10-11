<?php
/**
 * Admin page: Giao diện → Play2Grow (form settings, setup checklist, starter content).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the settings page.
 */
function p2g_admin_menu() {
	add_theme_page(
		__( 'Cài đặt Play2Grow', 'play2grow' ),
		__( 'Play2Grow', 'play2grow' ),
		'manage_options',
		'p2g-settings',
		'p2g_render_settings_page'
	);
}
add_action( 'admin_menu', 'p2g_admin_menu' );

/**
 * Register the option.
 */
function p2g_register_settings() {
	register_setting(
		'p2g_settings_group',
		'p2g_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'p2g_sanitize_settings',
			'default'           => p2g_settings_defaults(),
		)
	);
}
add_action( 'admin_init', 'p2g_register_settings' );

/**
 * Sanitize settings.
 *
 * @param mixed $input Input.
 * @return array
 */
function p2g_sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$out   = p2g_settings_defaults();

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
 * Setup checklist items: [ok, label, help URL].
 *
 * @return array[]
 */
function p2g_setup_checks() {
	$company_filled = 0;
	foreach ( array_keys( p2g_company_fields() ) as $key ) {
		if ( '' !== trim( (string) get_theme_mod( $key, '' ) ) ) {
			++$company_filled;
		}
	}

	return array(
		array(
			'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ),
			__( 'Đã chọn Trang chủ tĩnh (Cài đặt → Đọc)', 'play2grow' ),
			admin_url( 'options-reading.php' ),
		),
		array(
			(bool) get_option( 'page_for_posts' ),
			__( 'Đã chọn trang hiển thị bài viết (Góc học tập)', 'play2grow' ),
			admin_url( 'options-reading.php' ),
		),
		array(
			has_nav_menu( 'primary' ),
			__( 'Đã gán Menu chính', 'play2grow' ),
			admin_url( 'nav-menus.php?action=locations' ),
		),
		array(
			has_nav_menu( 'footer_explore' ) || has_nav_menu( 'footer_support' ),
			__( 'Đã gán menu chân trang', 'play2grow' ),
			admin_url( 'nav-menus.php?action=locations' ),
		),
		array(
			'' !== p2g_contact_url(),
			__( 'Có trang Liên hệ đã xuất bản (nút đăng ký đang hiển thị)', 'play2grow' ),
			admin_url( 'customize.php?autofocus[section]=p2g_header' ),
		),
		array(
			(bool) p2g_recipient_list(),
			__( 'Đã nhập email nhận thông báo đăng ký', 'play2grow' ),
			'#p2g-form-settings',
		),
		array(
			(bool) get_option( 'wp_page_for_privacy_policy' ) && 'publish' === get_post_status( (int) get_option( 'wp_page_for_privacy_policy' ) ),
			__( 'Đã xuất bản và chọn trang Chính sách quyền riêng tư (Cài đặt → Quyền riêng tư)', 'play2grow' ),
			admin_url( 'options-privacy.php' ),
		),
		array(
			$company_filled > 0,
			/* translators: %d: number of filled fields. */
			sprintf( __( 'Thông tin doanh nghiệp đã nhập: %d trường (chỉ nhập thông tin đã xác minh)', 'play2grow' ), $company_filled ),
			admin_url( 'customize.php?autofocus[section]=p2g_company' ),
		),
		array(
			has_custom_logo(),
			__( 'Đã tải logo chính thức', 'play2grow' ),
			admin_url( 'customize.php?autofocus[control]=custom_logo' ),
		),
		array(
			'' !== (string) get_option( 'permalink_structure' ),
			__( 'Đường dẫn tĩnh dạng thân thiện (Cài đặt → Đường dẫn tĩnh, khuyến nghị "Tên bài viết")', 'play2grow' ),
			admin_url( 'options-permalink.php' ),
		),
		array(
			(bool) get_option( 'blog_public' ),
			__( 'Website cho phép công cụ tìm kiếm lập chỉ mục (Cài đặt → Đọc). Chỉ bật khi website đã sẵn sàng.', 'play2grow' ),
			admin_url( 'options-reading.php' ),
		),
	);
}

/**
 * Render the page.
 */
function p2g_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = wp_parse_args( (array) get_option( 'p2g_settings', array() ), p2g_settings_defaults() );
	?>
	<div class="wrap p2g-admin">
		<h1><?php esc_html_e( 'Cài đặt giao diện Play2Grow', 'play2grow' ); ?></h1>

		<?php p2g_render_import_notice(); ?>

		<h2><?php esc_html_e( 'Danh sách kiểm tra sau cài đặt', 'play2grow' ); ?></h2>
		<table class="widefat striped" style="max-width:900px">
			<tbody>
			<?php foreach ( p2g_setup_checks() as $check ) : ?>
				<tr>
					<td style="width:40px;text-align:center"><?php echo $check[0] ? '<span class="dashicons dashicons-yes-alt" style="color:#008a20" aria-label="' . esc_attr__( 'Đã xong', 'play2grow' ) . '"></span>' : '<span class="dashicons dashicons-warning" style="color:#b26200" aria-label="' . esc_attr__( 'Chưa xong', 'play2grow' ) . '"></span>'; ?></td>
					<td><?php echo esc_html( $check[1] ); ?></td>
					<td style="width:120px"><a href="<?php echo esc_url( $check[2] ); ?>"><?php esc_html_e( 'Thiết lập', 'play2grow' ); ?></a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p class="description">
			<?php
			echo esc_html(
				p2g_seo_plugin_active()
					? __( 'Đã phát hiện plugin SEO: theme đã tự tắt meta description, Open Graph và JSON-LD của mình để tránh trùng lặp.', 'play2grow' )
					: __( 'Chưa phát hiện plugin SEO: theme đang tự xuất meta description, Open Graph, canonical cho trang lưu trữ và JSON-LD cơ bản.', 'play2grow' )
			);
			?>
		</p>

		<h2 id="p2g-form-settings"><?php esc_html_e( 'Form liên hệ / đăng ký nhận thông tin', 'play2grow' ); ?></h2>
		<p><?php echo wp_kses_post( __( 'Chèn form vào bất kỳ trang nào bằng shortcode <code>[p2g_contact_form]</code>. Trang dùng mẫu "Liên hệ" tự hiển thị form.', 'play2grow' ) ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'p2g_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="p2g-recipient"><?php esc_html_e( 'Email nhận thông báo', 'play2grow' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="p2g-recipient" name="p2g_settings[lead_recipient]" value="<?php echo esc_attr( $s['lead_recipient'] ); ?>" placeholder="ten@tenmien.vn">
						<p class="description"><?php esc_html_e( 'Có thể nhập nhiều email, cách nhau bằng dấu phẩy. Nên cài plugin SMTP để email không vào thư rác; hãy gửi thử một đăng ký sau khi cài đặt.', 'play2grow' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Lưu đăng ký trong trang quản trị', 'play2grow' ); ?></th>
					<td>
						<label><input type="checkbox" name="p2g_settings[lead_store]" value="1" <?php checked( $s['lead_store'] ); ?>> <?php esc_html_e( 'Lưu vào mục "Đăng ký liên hệ" (chỉ quản trị viên xem được). Khuyến nghị bật để không mất đăng ký khi email gặp sự cố.', 'play2grow' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-retention"><?php esc_html_e( 'Thời gian lưu trữ (ngày)', 'play2grow' ); ?></label></th>
					<td>
						<input type="number" min="0" max="3650" id="p2g-retention" name="p2g_settings[lead_retention_days]" value="<?php echo esc_attr( (string) $s['lead_retention_days'] ); ?>" class="small-text">
						<p class="description"><?php esc_html_e( 'Đăng ký cũ hơn số ngày này sẽ tự động bị xóa vĩnh viễn. Nhập 0 để không tự xóa. Hãy thống nhất con số này với nội dung Chính sách quyền riêng tư.', 'play2grow' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Trường nhóm tuổi của trẻ', 'play2grow' ); ?></th>
					<td>
						<label><input type="checkbox" name="p2g_settings[lead_age_field]" value="1" <?php checked( $s['lead_age_field'] ); ?>> <?php esc_html_e( 'Hiển thị trường "Nhóm tuổi của trẻ" (không bắt buộc, chỉ chọn khoảng tuổi)', 'play2grow' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-interests"><?php esc_html_e( 'Lựa chọn "Anh/chị quan tâm đến"', 'play2grow' ); ?></label></th>
					<td>
						<textarea id="p2g-interests" name="p2g_settings[lead_interests]" rows="5" class="large-text"><?php echo esc_textarea( $s['lead_interests'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Mỗi dòng một lựa chọn. Để trống để dùng danh sách mặc định (tên các trang sản phẩm đã xuất bản + các lựa chọn chung).', 'play2grow' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-success"><?php esc_html_e( 'Thông báo gửi thành công', 'play2grow' ); ?></label></th>
					<td><input type="text" class="large-text" id="p2g-success" name="p2g_settings[lead_success_text]" value="<?php echo esc_attr( $s['lead_success_text'] ); ?>" placeholder="<?php echo esc_attr( p2g_success_message() ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="p2g-consent-text"><?php esc_html_e( 'Nội dung ô đồng ý', 'play2grow' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="p2g-consent-text" name="p2g_settings[consent_text]" value="<?php echo esc_attr( $s['consent_text'] ); ?>">
						<p class="description"><?php esc_html_e( 'Để trống để dùng nội dung mặc định có liên kết tới Chính sách quyền riêng tư.', 'play2grow' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Lưu cài đặt', 'play2grow' ) ); ?>
		</form>

		<?php p2g_render_import_section(); ?>
	</div>
	<?php
}

/**
 * Admin notice when the form cannot deliver anywhere or email is missing.
 */
function p2g_admin_notices() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'appearance_page_p2g-settings', 'edit-p2g_lead', 'themes' ), true ) ) {
		return;
	}

	if ( ! p2g_form_ready() ) {
		echo '<div class="notice notice-error"><p>' . wp_kses_post(
			sprintf(
				/* translators: %s: settings URL. */
				__( '<strong>Play2Grow:</strong> Form liên hệ chưa có kênh nhận thông tin nên đang bị ẩn với khách truy cập. <a href="%s">Cấu hình ngay</a>.', 'play2grow' ),
				esc_url( admin_url( 'themes.php?page=p2g-settings#p2g-form-settings' ) )
			)
		) . '</p></div>';
	} elseif ( ! p2g_recipient_list() ) {
		echo '<div class="notice notice-warning"><p>' . wp_kses_post(
			sprintf(
				/* translators: %s: settings URL. */
				__( '<strong>Play2Grow:</strong> Đăng ký mới đang được lưu trong mục "Đăng ký liên hệ" nhưng chưa có email nhận thông báo. <a href="%s">Thêm email</a> để không bỏ lỡ yêu cầu.', 'play2grow' ),
				esc_url( admin_url( 'themes.php?page=p2g-settings#p2g-form-settings' ) )
			)
		) . '</p></div>';
	}
}
add_action( 'admin_notices', 'p2g_admin_notices' );

/**
 * Link to settings after activation.
 */
function p2g_after_switch_theme() {
	set_transient( 'p2g_activated', 1, 60 );
}
add_action( 'after_switch_theme', 'p2g_after_switch_theme' );

/**
 * Activation notice.
 */
function p2g_activation_notice() {
	if ( ! get_transient( 'p2g_activated' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	delete_transient( 'p2g_activated' );
	echo '<div class="notice notice-success is-dismissible"><p>' . wp_kses_post(
		sprintf(
			/* translators: %s: settings URL. */
			__( 'Đã kích hoạt giao diện Play2Grow. Mở <a href="%s">Giao diện → Play2Grow</a> để xem danh sách kiểm tra sau cài đặt và (tùy chọn) tạo nội dung khởi đầu dưới dạng bản nháp.', 'play2grow' ),
			esc_url( admin_url( 'themes.php?page=p2g-settings' ) )
		)
	) . '</p></div>';
}
add_action( 'admin_notices', 'p2g_activation_notice' );
