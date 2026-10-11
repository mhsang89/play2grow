<?php
/**
 * Contact / sign-up requests ("Đăng ký liên hệ") stored as a private post type.
 *
 * - Only users with the `manage_options` capability (filterable) can view them.
 * - No IP address or user agent is stored.
 * - Old entries are deleted automatically after the retention period (Cài đặt → Play2Grow).
 * - Integrated with WordPress' personal data export / erase tools (Công cụ → Xuất / Xóa dữ liệu cá nhân).
 * - Lives in the Play2Grow Core plugin, so switching themes never hides leads or stops the clean-up.
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Capability needed to read leads.
 *
 * @return string
 */
function p2gc_lead_cap() {
	return (string) apply_filters( 'p2g_lead_capability', 'manage_options' );
}

/**
 * Lead status labels.
 *
 * @return array
 */
function p2gc_lead_statuses() {
	return array(
		'new'       => __( 'Mới', 'play2grow-core' ),
		'contacted' => __( 'Đã liên hệ', 'play2grow-core' ),
		'consulted' => __( 'Đã trao đổi xong', 'play2grow-core' ),
		'closed'    => __( 'Đã đóng', 'play2grow-core' ),
	);
}

/**
 * Register the post type.
 */
function p2gc_register_lead_type() {
	$cap = p2gc_lead_cap();

	register_post_type(
		'p2g_lead',
		array(
			'labels'              => array(
				'name'               => __( 'Đăng ký liên hệ', 'play2grow-core' ),
				'singular_name'      => __( 'Đăng ký liên hệ', 'play2grow-core' ),
				'menu_name'          => __( 'Đăng ký liên hệ', 'play2grow-core' ),
				'all_items'          => __( 'Tất cả đăng ký', 'play2grow-core' ),
				'edit_item'          => __( 'Chi tiết đăng ký', 'play2grow-core' ),
				'search_items'       => __( 'Tìm đăng ký', 'play2grow-core' ),
				'not_found'          => __( 'Chưa có đăng ký nào.', 'play2grow-core' ),
				'not_found_in_trash' => __( 'Thùng rác trống.', 'play2grow-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => array( 'title' ),
			'rewrite'             => false,
			'query_var'           => false,
			'map_meta_cap'        => false,
			'capabilities'        => array(
				'create_posts'           => 'do_not_allow',
				'edit_post'              => $cap,
				'read_post'              => $cap,
				'delete_post'            => $cap,
				'edit_posts'             => $cap,
				'edit_others_posts'      => $cap,
				'edit_private_posts'     => $cap,
				'edit_published_posts'   => $cap,
				'delete_posts'           => $cap,
				'delete_others_posts'    => $cap,
				'delete_private_posts'   => $cap,
				'delete_published_posts' => $cap,
				'publish_posts'          => $cap,
				'read_private_posts'     => $cap,
			),
		)
	);
}
add_action( 'init', 'p2gc_register_lead_type' );

/**
 * Lead data fields (meta key => label).
 *
 * @return array
 */
function p2gc_lead_fields() {
	return array(
		'_p2g_name'     => __( 'Họ và tên', 'play2grow-core' ),
		'_p2g_phone'    => __( 'Số điện thoại', 'play2grow-core' ),
		'_p2g_email'    => __( 'Email', 'play2grow-core' ),
		'_p2g_interest' => __( 'Nội dung quan tâm', 'play2grow-core' ),
		'_p2g_age'      => __( 'Nhóm tuổi của trẻ', 'play2grow-core' ),
		'_p2g_message'  => __( 'Nội dung cần hỗ trợ', 'play2grow-core' ),
		'_p2g_source'   => __( 'Gửi từ trang', 'play2grow-core' ),
		'_p2g_consent'  => __( 'Thời điểm đồng ý xử lý dữ liệu', 'play2grow-core' ),
	);
}

/**
 * Insert a lead.
 *
 * @param array $data Clean data: name, phone, email, interest, age, message, source.
 * @return int Post ID or 0.
 */
function p2gc_insert_lead( $data ) {
	$id = wp_insert_post(
		array(
			'post_type'   => 'p2g_lead',
			'post_status' => 'private',
			/* translators: 1: name, 2: date. */
			'post_title'  => sprintf( __( '%1$s – %2$s', 'play2grow-core' ), $data['name'], wp_date( 'd/m/Y H:i' ) ),
		),
		true
	);

	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	foreach ( array( 'name', 'phone', 'email', 'interest', 'age', 'message', 'source' ) as $key ) {
		if ( ! empty( $data[ $key ] ) ) {
			update_post_meta( $id, '_p2g_' . $key, $data[ $key ] );
		}
	}
	update_post_meta( $id, '_p2g_consent', current_time( 'mysql' ) );
	update_post_meta( $id, '_p2g_status', 'new' );

	return (int) $id;
}

/**
 * Admin list columns.
 *
 * @return array
 */
function p2gc_lead_columns() {
	return array(
		'cb'           => '<input type="checkbox" />',
		'title'        => __( 'Đăng ký', 'play2grow-core' ),
		'p2g_contact'  => __( 'Liên hệ', 'play2grow-core' ),
		'p2g_interest' => __( 'Quan tâm', 'play2grow-core' ),
		'p2g_status'   => __( 'Trạng thái', 'play2grow-core' ),
		'p2g_mail'     => __( 'Email thông báo', 'play2grow-core' ),
		'date'         => __( 'Ngày gửi', 'play2grow-core' ),
	);
}
add_filter( 'manage_p2g_lead_posts_columns', 'p2gc_lead_columns' );

/**
 * Render admin columns.
 *
 * @param string $column  Column.
 * @param int    $post_id Post.
 */
function p2gc_lead_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'p2g_contact':
			$phone = get_post_meta( $post_id, '_p2g_phone', true );
			$email = get_post_meta( $post_id, '_p2g_email', true );
			if ( $phone ) {
				echo '<a href="' . esc_url( p2gc_tel( $phone ), array( 'tel' ) ) . '">' . esc_html( $phone ) . '</a><br>';
			}
			if ( $email ) {
				echo '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>';
			}
			break;
		case 'p2g_interest':
			echo esc_html( get_post_meta( $post_id, '_p2g_interest', true ) );
			break;
		case 'p2g_status':
			$statuses = p2gc_lead_statuses();
			$status   = get_post_meta( $post_id, '_p2g_status', true );
			echo esc_html( isset( $statuses[ $status ] ) ? $statuses[ $status ] : $statuses['new'] );
			break;
		case 'p2g_mail':
			$mailed = get_post_meta( $post_id, '_p2g_mailed', true );
			if ( '1' === (string) $mailed ) {
				esc_html_e( 'Đã gửi', 'play2grow-core' );
			} elseif ( '0' === (string) $mailed ) {
				echo '<span style="color:#b32d2e">' . esc_html__( 'Gửi thất bại', 'play2grow-core' ) . '</span>';
			} else {
				esc_html_e( 'Không gửi (chưa cấu hình)', 'play2grow-core' );
			}
			break;
	}
}
add_action( 'manage_p2g_lead_posts_custom_column', 'p2gc_lead_column_content', 10, 2 );

/**
 * Remove "quick edit" / "view" row actions.
 *
 * @param array   $actions Actions.
 * @param WP_Post $post    Post.
 * @return array
 */
function p2gc_lead_row_actions( $actions, $post ) {
	if ( 'p2g_lead' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'], $actions['view'] );
	}

	return $actions;
}
add_filter( 'post_row_actions', 'p2gc_lead_row_actions', 10, 2 );

/**
 * Detail meta box.
 */
function p2gc_lead_meta_boxes() {
	add_meta_box( 'p2g-lead-detail', __( 'Thông tin đăng ký', 'play2grow-core' ), 'p2gc_lead_detail_box', 'p2g_lead', 'normal', 'high' );
	remove_meta_box( 'slugdiv', 'p2g_lead', 'normal' );
}
add_action( 'add_meta_boxes_p2g_lead', 'p2gc_lead_meta_boxes' );

/**
 * Detail box content (read-only data + editable processing status).
 *
 * @param WP_Post $post Post.
 */
function p2gc_lead_detail_box( $post ) {
	wp_nonce_field( 'p2g_lead_status', 'p2g_lead_nonce' );
	echo '<table class="widefat striped"><tbody>';
	foreach ( p2gc_lead_fields() as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( '' === $value ) {
			continue;
		}
		if ( '_p2g_source' === $key && is_numeric( $value ) ) {
			$value = get_the_title( (int) $value ) . ' (' . get_permalink( (int) $value ) . ')';
		}
		printf( '<tr><th style="width:30%%">%1$s</th><td>%2$s</td></tr>', esc_html( $label ), nl2br( esc_html( $value ) ) );
	}
	echo '</tbody></table>';

	$current = get_post_meta( $post->ID, '_p2g_status', true );
	echo '<p><label for="p2g-lead-status"><strong>' . esc_html__( 'Trạng thái xử lý', 'play2grow-core' ) . '</strong></label><br><select id="p2g-lead-status" name="p2g_lead_status">';
	foreach ( p2gc_lead_statuses() as $value => $label ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $current ? $current : 'new', $value, false ), esc_html( $label ) );
	}
	echo '</select></p>';
	echo '<p class="description">' . esc_html__( 'Bấm "Cập nhật" để lưu trạng thái. Đăng ký được tự động xóa sau thời gian lưu trữ đã cấu hình.', 'play2grow-core' ) . '</p>';
}

/**
 * Save status.
 *
 * @param int $post_id Post.
 */
function p2gc_lead_save_status( $post_id ) {
	if ( ! isset( $_POST['p2g_lead_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['p2g_lead_nonce'] ) ), 'p2g_lead_status' ) ) {
		return;
	}
	if ( ! current_user_can( p2gc_lead_cap() ) || ! isset( $_POST['p2g_lead_status'] ) ) {
		return;
	}
	$status = sanitize_key( wp_unslash( $_POST['p2g_lead_status'] ) );
	if ( isset( p2gc_lead_statuses()[ $status ] ) ) {
		update_post_meta( $post_id, '_p2g_status', $status );
	}
}
add_action( 'save_post_p2g_lead', 'p2gc_lead_save_status' );

/**
 * Keep leads private even when "Publish/Update" is pressed.
 *
 * @param array $data Post data.
 * @return array
 */
function p2gc_lead_force_private( $data ) {
	if ( 'p2g_lead' === $data['post_type'] && ! in_array( $data['post_status'], array( 'trash', 'auto-draft' ), true ) ) {
		$data['post_status'] = 'private';
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'p2gc_lead_force_private' );

/**
 * Daily retention clean-up.
 */
function p2gc_purge_old_leads() {
	$days = (int) p2gc_setting( 'lead_retention_days' );
	if ( $days <= 0 ) {
		return;
	}

	$old = get_posts(
		array(
			'post_type'      => 'p2g_lead',
			'post_status'    => array( 'private', 'publish', 'draft', 'trash' ),
			'posts_per_page' => 200,
			'fields'         => 'ids',
			'date_query'     => array(
				array(
					'before' => gmdate( 'Y-m-d H:i:s', time() - $days * DAY_IN_SECONDS ),
					'column' => 'post_date_gmt',
				),
			),
		)
	);

	foreach ( $old as $id ) {
		wp_delete_post( $id, true );
	}
}
add_action( 'p2g_purge_leads', 'p2gc_purge_old_leads' );

/**
 * Schedule the clean-up.
 */
function p2gc_schedule_purge() {
	if ( ! wp_next_scheduled( 'p2g_purge_leads' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'p2g_purge_leads' );
	}
}
add_action( 'init', 'p2gc_schedule_purge' );

/**
 * Unschedule on plugin deactivation (stored data is kept).
 */
function p2gc_unschedule_purge() {
	wp_clear_scheduled_hook( 'p2g_purge_leads' );
}

/**
 * Find lead IDs for an email address (privacy tools).
 *
 * @param string $email Email.
 * @param int    $page  Page.
 * @return int[]
 */
function p2gc_leads_by_email( $email, $page = 1 ) {
	return get_posts(
		array(
			'post_type'      => 'p2g_lead',
			'post_status'    => 'any',
			'posts_per_page' => 50,
			'paged'          => max( 1, (int) $page ),
			'fields'         => 'ids',
			'meta_key'       => '_p2g_email', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $email, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
}

/**
 * Register exporter.
 *
 * @param array $exporters Exporters.
 * @return array
 */
function p2gc_register_exporter( $exporters ) {
	$exporters['play2grow-leads'] = array(
		'exporter_friendly_name' => __( 'Đăng ký liên hệ Play2Grow', 'play2grow-core' ),
		'callback'               => 'p2gc_privacy_export',
	);

	return $exporters;
}
add_filter( 'wp_privacy_personal_data_exporters', 'p2gc_register_exporter' );

/**
 * Exporter callback.
 *
 * @param string $email Email.
 * @param int    $page  Page.
 * @return array
 */
function p2gc_privacy_export( $email, $page = 1 ) {
	$items = array();
	$ids   = p2gc_leads_by_email( $email, $page );
	foreach ( $ids as $id ) {
		$data = array();
		foreach ( p2gc_lead_fields() as $key => $label ) {
			$value = get_post_meta( $id, $key, true );
			if ( '' !== $value ) {
				$data[] = array(
					'name'  => $label,
					'value' => $value,
				);
			}
		}
		$items[] = array(
			'group_id'    => 'play2grow-leads',
			'group_label' => __( 'Đăng ký liên hệ', 'play2grow-core' ),
			'item_id'     => 'p2g-lead-' . $id,
			'data'        => $data,
		);
	}

	return array(
		'data' => $items,
		'done' => count( $ids ) < 50,
	);
}

/**
 * Register eraser.
 *
 * @param array $erasers Erasers.
 * @return array
 */
function p2gc_register_eraser( $erasers ) {
	$erasers['play2grow-leads'] = array(
		'eraser_friendly_name' => __( 'Đăng ký liên hệ Play2Grow', 'play2grow-core' ),
		'callback'             => 'p2gc_privacy_erase',
	);

	return $erasers;
}
add_filter( 'wp_privacy_personal_data_erasers', 'p2gc_register_eraser' );

/**
 * Eraser callback – deletes the whole lead.
 *
 * @param string $email Email.
 * @return array
 */
function p2gc_privacy_erase( $email ) {
	$ids     = p2gc_leads_by_email( $email, 1 );
	$removed = false;
	foreach ( $ids as $id ) {
		$removed = (bool) wp_delete_post( $id, true ) || $removed;
	}

	return array(
		'items_removed'  => $removed,
		'items_retained' => false,
		'messages'       => array(),
		'done'           => count( $ids ) < 50,
	);
}

/**
 * Suggested text for Settings → Privacy (Cài đặt → Quyền riêng tư → Hướng dẫn chính sách).
 */
function p2gc_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}
	$content = '<p>' . __( 'Form liên hệ / đăng ký nhận thông tin của plugin Play2Grow Core thu thập thông tin của phụ huynh hoặc người liên hệ: họ và tên, số điện thoại và/hoặc email, nội dung quan tâm, nhóm tuổi của trẻ (không bắt buộc, chỉ là khoảng tuổi) và nội dung tin nhắn. Form không yêu cầu họ tên, ảnh hay thông tin định danh của trẻ. Dữ liệu được lưu trong cơ sở dữ liệu của website, chỉ quản trị viên xem được, và được gửi tới email nhận thông báo do quản trị viên cấu hình. Giao diện không lưu địa chỉ IP hay thông tin trình duyệt cùng đăng ký. Dữ liệu tự động bị xóa sau thời gian lưu trữ được cấu hình trong Cài đặt → Play2Grow.', 'play2grow-core' ) . '</p>';
	wp_add_privacy_policy_content( 'Play2Grow', wp_kses_post( wpautop( $content, false ) ) );
}
add_action( 'admin_init', 'p2gc_privacy_policy_content' );
