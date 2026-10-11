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
 * Setup checklist items: [ok, label, help URL].
 *
 * @return array[]
 */
function p2g_setup_checks() {
	$company_filled = 0;
	if ( p2g_core_active() ) {
		foreach ( array_keys( p2gc_company_fields() ) as $key ) {
			if ( '' !== p2gc_company( $key ) ) {
				++$company_filled;
			}
		}
	}

	return array(
		array(
			p2g_core_active(),
			__( 'Đã kích hoạt plugin Play2Grow Core (form liên hệ, thông tin doanh nghiệp)', 'play2grow' ),
			admin_url( 'plugins.php' ),
		),
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
			p2g_core_active() && p2gc_recipient_list(),
			__( 'Đã nhập email nhận thông báo đăng ký (Cài đặt → Play2Grow)', 'play2grow' ),
			admin_url( 'options-general.php?page=p2gc-settings' ),
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
			admin_url( 'customize.php?autofocus[section]=p2gc_company' ),
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

		<?php p2g_render_import_section(); ?>
	</div>
	<?php
}

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
