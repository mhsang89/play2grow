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
 * Product / learning-experience detail fields stored on pages that use the
 * "Chi tiết sản phẩm" template. Every field is optional; empty fields are hidden.
 *
 * @return array meta_key => [label, icon, placeholder]
 */
function p2g_program_fields() {
	return array(
		'_p2g_prog_audience' => array( __( 'Độ tuổi phù hợp', 'play2grow' ), 'users', __( 'Ví dụ: Trẻ từ … đến … tuổi', 'play2grow' ) ),
		'_p2g_prog_skills'   => array( __( 'Kỹ năng tiếng Anh', 'play2grow' ), 'book', __( 'Ví dụ: Nghe, nói, từ vựng theo chủ đề', 'play2grow' ) ),
		'_p2g_prog_format'   => array( __( 'Hình thức / nền tảng', 'play2grow' ), 'monitor', __( 'Ví dụ: Ứng dụng di động, hoạt động in ra giấy…', 'play2grow' ) ),
		'_p2g_prog_duration' => array( __( 'Thời lượng', 'play2grow' ), 'clock', __( 'Ví dụ: Mỗi hoạt động khoảng … phút', 'play2grow' ) ),
		'_p2g_prog_require'  => array( __( 'Yêu cầu sử dụng', 'play2grow' ), 'info', __( 'Ví dụ: Cần tài khoản phụ huynh, kết nối Internet…', 'play2grow' ) ),
		'_p2g_prog_fee'      => array( __( 'Chi phí', 'play2grow' ), 'tag', __( 'Chỉ điền mức phí đã được duyệt công bố', 'play2grow' ) ),
	);
}

/**
 * Product status options. The status badge is shown only when one is chosen.
 *
 * @return array
 */
function p2g_program_statuses() {
	return array(
		''         => __( '— Không hiển thị trạng thái —', 'play2grow' ),
		'live'     => __( 'Đang hoạt động', 'play2grow' ),
		'beta'     => __( 'Đang thử nghiệm', 'play2grow' ),
		'upcoming' => __( 'Sắp ra mắt', 'play2grow' ),
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

	foreach ( array_merge( array_keys( p2g_program_fields() ), array( '_p2g_prog_status' ) ) as $key ) {
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
		add_meta_box( 'p2g-seo', __( 'SEO cơ bản (Play2Grow)', 'play2grow' ), 'p2g_seo_box', $post_type, 'normal', 'low' );
	}

	if ( 'page' === $post_type ) {
		add_meta_box( 'p2g-program', __( 'Thông tin sản phẩm / trải nghiệm học tập', 'play2grow' ), 'p2g_program_box', 'page', 'normal', 'default' );
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
		<label for="p2g-meta-description"><strong><?php esc_html_e( 'Mô tả meta (meta description)', 'play2grow' ); ?></strong></label><br>
		<textarea id="p2g-meta-description" name="p2g_meta_description" rows="3" class="widefat" maxlength="320"><?php echo esc_textarea( $desc ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Khoảng 120–160 ký tự, mô tả đúng nội dung trang. Để trống thì theme dùng phần Tóm tắt (Excerpt).', 'play2grow' ); ?></span>
	</p>
	<p>
		<label><input type="checkbox" name="p2g_noindex" value="1" <?php checked( $noindex ); ?>> <?php esc_html_e( 'Không cho công cụ tìm kiếm lập chỉ mục trang này (noindex)', 'play2grow' ); ?></label>
	</p>
	<p class="description"><?php esc_html_e( 'Hộp này tự ẩn khi bạn cài Yoast SEO, Rank Math hoặc plugin SEO tương tự.', 'play2grow' ); ?></p>
	<?php
}

/**
 * Program details box.
 *
 * @param WP_Post $post Post.
 */
function p2g_program_box( $post ) {
	wp_nonce_field( 'p2g_save_meta', 'p2g_meta_nonce' );
	echo '<p class="description">' . esc_html__( 'Chỉ dùng cho trang có mẫu "Chi tiết sản phẩm". Mỗi trường để trống sẽ không hiển thị. Chỉ điền thông tin đã được kiểm chứng trên sản phẩm thực tế.', 'play2grow' ) . '</p>';
	echo '<table class="form-table" role="presentation"><tbody>';
	$current = get_post_meta( $post->ID, '_p2g_prog_status', true );
	echo '<tr><th scope="row"><label for="_p2g_prog_status">' . esc_html__( 'Trạng thái', 'play2grow' ) . '</label></th><td><select id="_p2g_prog_status" name="_p2g_prog_status">';
	foreach ( p2g_program_statuses() as $value => $label ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
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
