<?php
/**
 * Meta boxes: SEO description (when no SEO plugin) and program details.
 *
 * Data is stored as normal post meta, so it stays in the database if the theme changes.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class / program detail fields stored on pages that use the "Chi tiết chương trình"
 * template. Every field is optional; empty fields are hidden on the website.
 *
 * @return array meta_key => [label, icon, placeholder]
 */
function p2g_program_fields() {
	return array(
		'_p2g_prog_audience' => array( __( 'Độ tuổi', 'play2grow-theme' ), 'users', __( 'Ví dụ: 4 – 6 tuổi', 'play2grow-theme' ) ),
		'_p2g_prog_skills'   => array( __( 'Nội dung chính', 'play2grow-theme' ), 'book', __( 'Ví dụ: Nghe – nói, từ vựng theo chủ đề, dự án STEAM', 'play2grow-theme' ) ),
		'_p2g_prog_format'   => array( __( 'Hình thức học', 'play2grow-theme' ), 'monitor', __( 'Ví dụ: Lớp trực tiếp tại trung tâm', 'play2grow-theme' ) ),
		'_p2g_prog_duration' => array( __( 'Thời lượng', 'play2grow-theme' ), 'clock', __( 'Ví dụ: 90 phút/buổi, 2 buổi/tuần', 'play2grow-theme' ) ),
		'_p2g_prog_schedule' => array( __( 'Lịch học', 'play2grow-theme' ), 'calendar', __( 'Ví dụ: Thứ Bảy, Chủ Nhật 9:00 – 10:30', 'play2grow-theme' ) ),
		'_p2g_prog_class'    => array( __( 'Sĩ số lớp', 'play2grow-theme' ), 'users', __( 'Ví dụ: Tối đa … học viên', 'play2grow-theme' ) ),
		'_p2g_prog_teacher'  => array( __( 'Giáo viên', 'play2grow-theme' ), 'user', __( 'Chỉ ghi thông tin đã được xác nhận', 'play2grow-theme' ) ),
		'_p2g_prog_fee'      => array( __( 'Học phí', 'play2grow-theme' ), 'tag', __( 'Chỉ điền mức phí đã được duyệt công bố', 'play2grow-theme' ) ),
		'_p2g_prog_require'  => array( __( 'Cần chuẩn bị', 'play2grow-theme' ), 'info', __( 'Ví dụ: Dụng cụ vẽ do trung tâm cung cấp', 'play2grow-theme' ) ),
	);
}

/**
 * Status options. The badge is shown only when one is chosen.
 *
 * @return array
 */
function p2g_program_statuses() {
	return array(
		''         => __( '— Không hiển thị trạng thái —', 'play2grow-theme' ),
		'live'     => __( 'Đang mở lớp', 'play2grow-theme' ),
		'upcoming' => __( 'Sắp khai giảng', 'play2grow-theme' ),
		'beta'     => __( 'Lớp thử nghiệm', 'play2grow-theme' ),
		'paused'   => __( 'Tạm ngưng tuyển sinh', 'play2grow-theme' ),
	);
}

/**
 * Program groups used to organise the "Chương trình học" overview page.
 *
 * @return array
 */
function p2g_program_groups() {
	return (array) apply_filters(
		'p2g_program_groups',
		array(
			'english' => __( 'Tiếng Anh', 'play2grow-theme' ),
			'art'     => __( 'Mỹ thuật', 'play2grow-theme' ),
			'music'   => __( 'Âm nhạc', 'play2grow-theme' ),
			'dance'   => __( 'Nhảy', 'play2grow-theme' ),
			'other'   => __( 'Chương trình khác', 'play2grow-theme' ),
		)
	);
}

/**
 * Register meta so it is protected and typed.
 */
function p2g_register_meta() {
	$auth = static function ( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	};

	foreach ( array( 'post', 'page' ) as $type ) {
		register_post_meta(
			$type,
			'_p2g_meta_description',
			array(
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => 'sanitize_textarea_field',
				'auth_callback'     => $auth,
			)
		);
		register_post_meta(
			$type,
			'_p2g_noindex',
			array(
				'type'          => 'boolean',
				'single'        => true,
				'auth_callback' => $auth,
			)
		);
	}

	foreach ( array_merge( array_keys( p2g_program_fields() ), array( '_p2g_prog_status', '_p2g_prog_group' ) ) as $key ) {
		register_post_meta(
			'page',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => $auth,
			)
		);
	}
}
add_action( 'init', 'p2g_register_meta' );

/**
 * Add meta boxes.
 *
 * @param string  $post_type Post type.
 * @param WP_Post $post      Post.
 */
function p2g_add_meta_boxes( $post_type, $post ) {
	if ( in_array( $post_type, array( 'post', 'page' ), true ) && ! p2g_seo_plugin_active() ) {
		add_meta_box( 'p2g-seo', __( 'SEO cơ bản (Play2Grow)', 'play2grow-theme' ), 'p2g_seo_box', $post_type, 'normal', 'low' );
	}

	if ( 'page' === $post_type ) {
		add_meta_box( 'p2g-program', __( 'Thông tin chương trình / lớp học', 'play2grow-theme' ), 'p2g_program_box', 'page', 'normal', 'default' );
	}
}
add_action( 'add_meta_boxes', 'p2g_add_meta_boxes', 10, 2 );

/**
 * SEO box.
 *
 * @param WP_Post $post Post.
 */
function p2g_seo_box( $post ) {
	wp_nonce_field( 'p2g_save_meta', 'p2g_meta_nonce' );
	$desc    = get_post_meta( $post->ID, '_p2g_meta_description', true );
	$noindex = (bool) get_post_meta( $post->ID, '_p2g_noindex', true );
	?>
	<p>
		<label for="p2g-meta-description"><strong><?php esc_html_e( 'Mô tả meta (meta description)', 'play2grow-theme' ); ?></strong></label><br>
		<textarea id="p2g-meta-description" name="p2g_meta_description" rows="3" class="widefat" maxlength="320"><?php echo esc_textarea( $desc ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Khoảng 120–160 ký tự, mô tả đúng nội dung trang. Để trống thì theme dùng phần Tóm tắt (Excerpt).', 'play2grow-theme' ); ?></span>
	</p>
	<p>
		<label><input type="checkbox" name="p2g_noindex" value="1" <?php checked( $noindex ); ?>> <?php esc_html_e( 'Không cho công cụ tìm kiếm lập chỉ mục trang này (noindex)', 'play2grow-theme' ); ?></label>
	</p>
	<p class="description"><?php esc_html_e( 'Hộp này tự ẩn khi bạn cài Yoast SEO, Rank Math hoặc plugin SEO tương tự.', 'play2grow-theme' ); ?></p>
	<?php
}

/**
 * Program details box.
 *
 * @param WP_Post $post Post.
 */
function p2g_program_box( $post ) {
	wp_nonce_field( 'p2g_save_meta', 'p2g_meta_nonce' );
	echo '<p class="description">' . esc_html__( 'Dùng cho trang có mẫu "Chi tiết chương trình". Trường để trống sẽ hiển thị "Đang cập nhật" hoặc được ẩn. Chỉ điền thông tin đã được xác nhận.', 'play2grow-theme' ) . '</p>';
	echo '<table class="form-table" role="presentation"><tbody>';
	$current = get_post_meta( $post->ID, '_p2g_prog_status', true );
	echo '<tr><th scope="row"><label for="_p2g_prog_status">' . esc_html__( 'Trạng thái', 'play2grow-theme' ) . '</label></th><td><select id="_p2g_prog_status" name="_p2g_prog_status">';
	foreach ( p2g_program_statuses() as $value => $label ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
	}
	echo '</select></td></tr>';
	$group = get_post_meta( $post->ID, '_p2g_prog_group', true );
	echo '<tr><th scope="row"><label for="_p2g_prog_group">' . esc_html__( 'Nhóm chương trình', 'play2grow-theme' ) . '</label></th><td><select id="_p2g_prog_group" name="_p2g_prog_group"><option value="">' . esc_html__( '— Chưa phân nhóm —', 'play2grow-theme' ) . '</option>';
	foreach ( p2g_program_groups() as $value => $label ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $group, $value, false ), esc_html( $label ) );
	}
	echo '</select></td></tr>';
	foreach ( p2g_program_fields() as $key => $field ) {
		printf(
			'<tr><th scope="row"><label for="%1$s">%2$s</label></th><td><input type="text" class="widefat" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s"></td></tr>',
			esc_attr( $key ),
			esc_html( $field[0] ),
			esc_attr( get_post_meta( $post->ID, $key, true ) ),
			esc_attr( $field[2] )
		);
	}
	echo '</tbody></table>';
}

/**
 * Save meta boxes.
 *
 * @param int $post_id Post ID.
 */
function p2g_save_meta( $post_id ) {
	if ( ! isset( $_POST['p2g_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['p2g_meta_nonce'] ) ), 'p2g_save_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['p2g_meta_description'] ) ) {
		$desc = sanitize_textarea_field( wp_unslash( $_POST['p2g_meta_description'] ) );
		if ( '' === $desc ) {
			delete_post_meta( $post_id, '_p2g_meta_description' );
		} else {
			update_post_meta( $post_id, '_p2g_meta_description', $desc );
		}
		if ( ! empty( $_POST['p2g_noindex'] ) ) {
			update_post_meta( $post_id, '_p2g_noindex', 1 );
		} else {
			delete_post_meta( $post_id, '_p2g_noindex' );
		}
	}

	if ( 'page' === get_post_type( $post_id ) && isset( $_POST['_p2g_prog_group'] ) ) {
		$group = sanitize_key( wp_unslash( $_POST['_p2g_prog_group'] ) );
		if ( '' !== $group && array_key_exists( $group, p2g_program_groups() ) ) {
			update_post_meta( $post_id, '_p2g_prog_group', $group );
		} else {
			delete_post_meta( $post_id, '_p2g_prog_group' );
		}
	}

	if ( 'page' === get_post_type( $post_id ) && isset( $_POST['_p2g_prog_status'] ) ) {
		$status = sanitize_key( wp_unslash( $_POST['_p2g_prog_status'] ) );
		if ( '' !== $status && array_key_exists( $status, p2g_program_statuses() ) ) {
			update_post_meta( $post_id, '_p2g_prog_status', $status );
		} else {
			delete_post_meta( $post_id, '_p2g_prog_status' );
		}
	}

	if ( 'page' === get_post_type( $post_id ) ) {
		foreach ( array_keys( p2g_program_fields() ) as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}
			$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			if ( '' === $value ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}
}
add_action( 'save_post', 'p2g_save_meta' );
