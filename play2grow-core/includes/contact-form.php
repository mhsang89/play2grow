<?php
/**
 * Contact / "Đăng ký nhận thông tin" form.
 *
 * - Shortcode: [p2g_contact_form title="" intro=""]
 * - Submits to admin-post.php (works without JavaScript); assets/js/form.js upgrades it to fetch().
 * - Client-side + server-side validation, nonce, honeypot, minimum fill time, rate limit.
 * - Collects only parent/contact data. The optional child field is an age range, not a name.
 * - Success is reported only if the request was stored in the database or the
 *   notification email was accepted by wp_mail(). Nothing is faked.
 * - No personal data is put in URLs: the no-JS fallback uses a random token that
 *   points to a short-lived transient.
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Options for "Nội dung quan tâm".
 *
 * @return string[]
 */
function p2gc_interest_options() {
	$custom = trim( (string) p2gc_setting( 'lead_interests' ) );
	if ( '' !== $custom ) {
		$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $custom ) ) );
		return array_values( array_unique( array_map( 'sanitize_text_field', $lines ) ) );
	}

	$options = array();
	foreach ( p2gc_get_programs( p2gc_programs_parent_id(), 20 ) as $program ) {
		$options[] = wp_strip_all_tags( get_the_title( $program ) );
	}

	return array_merge(
		$options,
		array(
			__( 'Nhận thông tin về sản phẩm Play2Grow', 'play2grow-core' ),
			__( 'Hỏi về phương pháp học tiếng Anh qua trải nghiệm', 'play2grow-core' ),
			__( 'Hợp tác / truyền thông', 'play2grow-core' ),
			__( 'Nội dung khác', 'play2grow-core' ),
		)
	);
}

/**
 * Age range options for the optional child age field.
 *
 * @return string[]
 */
function p2gc_age_options() {
	return array(
		__( 'Dưới 4 tuổi', 'play2grow-core' ),
		__( '4 – 6 tuổi', 'play2grow-core' ),
		__( '7 – 9 tuổi', 'play2grow-core' ),
		__( '10 – 12 tuổi', 'play2grow-core' ),
		__( 'Trên 12 tuổi', 'play2grow-core' ),
	);
}

/**
 * Whether the form has at least one working delivery channel.
 *
 * @return bool
 */
function p2gc_form_ready() {
	return (bool) p2gc_setting( 'lead_store' ) || (bool) p2gc_recipient_list();
}

/**
 * Parse recipient setting (comma separated) into valid emails.
 *
 * @return string[]
 */
function p2gc_recipient_list() {
	$raw = (string) p2gc_setting( 'lead_recipient' );
	$out = array();
	foreach ( explode( ',', $raw ) as $email ) {
		$email = sanitize_email( trim( $email ) );
		if ( $email && is_email( $email ) ) {
			$out[] = $email;
		}
	}

	return array_values( array_unique( $out ) );
}

/**
 * Signed timestamp used to reject instant (bot) submissions without cookies.
 *
 * @return string
 */
function p2gc_form_stamp() {
	$time = (string) time();

	return $time . '.' . substr( wp_hash( 'p2g_form|' . $time, 'nonce' ), 0, 16 );
}

/**
 * Validate the signed timestamp.
 *
 * @param string $stamp Stamp.
 * @return string "ok", "fast" or "invalid".
 */
function p2gc_check_stamp( $stamp ) {
	$parts = explode( '.', (string) $stamp );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return 'invalid';
	}
	if ( ! hash_equals( substr( wp_hash( 'p2g_form|' . $parts[0], 'nonce' ), 0, 16 ), $parts[1] ) ) {
		return 'invalid';
	}
	$age = time() - (int) $parts[0];
	if ( $age < 3 ) {
		return 'fast';
	}

	return $age > DAY_IN_SECONDS ? 'invalid' : 'ok';
}

/**
 * Validate and clean submitted data.
 *
 * @param array $raw Unslashed POST data.
 * @return array [clean data, errors (field => message)]
 */
function p2gc_validate_lead( $raw ) {
	$errors = array();
	$get    = static function ( $key, $max = 200 ) use ( $raw ) {
		$value = isset( $raw[ $key ] ) && is_string( $raw[ $key ] ) ? $raw[ $key ] : '';
		return mb_substr( trim( $value ), 0, $max );
	};

	$data = array(
		'name'     => sanitize_text_field( $get( 'p2g_name', 120 ) ),
		'phone'    => sanitize_text_field( $get( 'p2g_phone', 30 ) ),
		'email'    => sanitize_email( $get( 'p2g_email', 120 ) ),
		'interest' => sanitize_text_field( $get( 'p2g_interest', 160 ) ),
		'age'      => sanitize_text_field( $get( 'p2g_age', 40 ) ),
		'message'  => sanitize_textarea_field( $get( 'p2g_message', 1500 ) ),
		'source'   => absint( $get( 'p2g_source', 20 ) ),
	);

	$name_len = mb_strlen( $data['name'] );
	if ( 0 === $name_len ) {
		$errors['p2g_name'] = __( 'Vui lòng nhập họ và tên.', 'play2grow-core' );
	} elseif ( $name_len < 2 || $name_len > 80 || ! preg_match( "/^[\\p{L}\\p{M}\\s.'-]+$/u", $data['name'] ) ) {
		$errors['p2g_name'] = __( 'Họ và tên cần từ 2 đến 80 ký tự và chỉ gồm chữ cái.', 'play2grow-core' );
	}

	$raw_email = $get( 'p2g_email', 120 );
	if ( '' === $data['phone'] && '' === $raw_email ) {
		$errors['p2g_phone'] = __( 'Vui lòng nhập số điện thoại hoặc email để Play2Grow có thể liên hệ lại.', 'play2grow-core' );
	}
	if ( '' !== $data['phone'] ) {
		$digits = preg_replace( '/[\s.\-()]/', '', $data['phone'] );
		if ( ! preg_match( '/^(?:\+?84|0)(?:[35789]\d{8}|2\d{9})$/', $digits ) ) {
			$errors['p2g_phone'] = __( 'Số điện thoại chưa đúng định dạng Việt Nam (ví dụ: 0912 345 678).', 'play2grow-core' );
		} else {
			$data['phone'] = $digits;
		}
	}
	if ( '' !== $raw_email && ! is_email( $data['email'] ) ) {
		$errors['p2g_email'] = __( 'Email chưa đúng định dạng (ví dụ: ten@email.com).', 'play2grow-core' );
	}

	if ( '' !== $data['interest'] && ! in_array( $data['interest'], p2gc_interest_options(), true ) ) {
		$data['interest'] = '';
	}
	if ( '' !== $data['age'] && ! in_array( $data['age'], p2gc_age_options(), true ) ) {
		$data['age'] = '';
	}

	if ( empty( $raw['p2g_consent'] ) ) {
		$errors['p2g_consent'] = __( 'Vui lòng đồng ý để Play2Grow xử lý thông tin để liên hệ lại.', 'play2grow-core' );
	}

	if ( $data['source'] && ! get_post( $data['source'] ) ) {
		$data['source'] = 0;
	}

	return array( $data, $errors );
}

/**
 * Hashed client key for rate limiting (raw IP is never stored).
 *
 * @return string
 */
function p2gc_client_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	return 'p2g_rl_' . substr( wp_hash( $ip . '|p2g' ), 0, 20 );
}

/**
 * Send the notification email.
 *
 * @param array $data    Clean data.
 * @param int   $lead_id Stored lead ID (0 if not stored).
 * @return bool|null Null when no recipient is configured.
 */
function p2gc_send_lead_mail( $data, $lead_id ) {
	$to = p2gc_recipient_list();
	if ( ! $to ) {
		return null;
	}

	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	/* translators: 1: site name, 2: person name. */
	$subject = sprintf( __( '[%1$s] Đăng ký liên hệ mới từ %2$s', 'play2grow-core' ), $site, $data['name'] );

	$lines   = array();
	$labels  = array(
		'name'     => __( 'Họ và tên', 'play2grow-core' ),
		'phone'    => __( 'Số điện thoại', 'play2grow-core' ),
		'email'    => __( 'Email', 'play2grow-core' ),
		'interest' => __( 'Nội dung quan tâm', 'play2grow-core' ),
		'age'      => __( 'Nhóm tuổi của trẻ', 'play2grow-core' ),
		'message'  => __( 'Nội dung', 'play2grow-core' ),
	);
	foreach ( $labels as $key => $label ) {
		if ( '' !== (string) $data[ $key ] ) {
			$lines[] = $label . ': ' . $data[ $key ];
		}
	}
	if ( $data['source'] ) {
		$lines[] = __( 'Gửi từ trang', 'play2grow-core' ) . ': ' . get_permalink( $data['source'] );
	}
	if ( $lead_id ) {
		$lines[] = '';
		$lines[] = __( 'Xem trong trang quản trị', 'play2grow-core' ) . ': ' . admin_url( 'post.php?post=' . $lead_id . '&action=edit' );
	}
	$lines[] = '';
	$lines[] = __( 'Email này chứa dữ liệu cá nhân. Vui lòng không chuyển tiếp cho người không có trách nhiệm xử lý.', 'play2grow-core' );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $data['email'] && is_email( $data['email'] ) ) {
		$reply_name = preg_replace( '/[\r\n"<>,;]/', '', $data['name'] );
		$headers[]  = 'Reply-To: ' . $reply_name . ' <' . $data['email'] . '>';
	}

	return (bool) wp_mail( $to, $subject, implode( "\n", $lines ), $headers );
}

/**
 * Handle a submission (admin-post.php?action=p2g_lead).
 */
function p2gc_handle_lead() {
	$is_ajax = ! empty( $_POST['p2g_ajax'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- only selects response format.
	$post    = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified below.

	$respond = static function ( $status, $message, $errors = array(), $values = array() ) use ( $is_ajax, $post ) {
		if ( $is_ajax ) {
			wp_send_json(
				array(
					'status'  => $status,
					'message' => $message,
					'errors'  => $errors,
				),
				'success' === $status ? 200 : 400
			);
		}

		$token = wp_generate_password( 20, false );
		set_transient(
			'p2g_form_' . $token,
			array(
				'status'  => $status,
				'message' => $message,
				'errors'  => $errors,
				'values'  => 'success' === $status ? array() : $values,
			),
			10 * MINUTE_IN_SECONDS
		);

		$back = isset( $post['p2g_source'] ) ? get_permalink( absint( $post['p2g_source'] ) ) : '';
		if ( ! $back ) {
			$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		}
		wp_safe_redirect( add_query_arg( 'p2g_t', $token, remove_query_arg( 'p2g_t', $back ) ) . '#p2g-form' );
		exit;
	};

	// 1. Honeypot: pretend success to bots without storing anything.
	if ( ! empty( $post['p2g_website'] ) ) {
		$respond( 'success', p2gc_success_message() );
	}

	// 2. Nonce.
	if ( empty( $post['p2g_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $post['p2g_nonce'] ), 'p2g_lead' ) ) {
		$respond( 'error', __( 'Phiên làm việc của trang đã hết hạn. Vui lòng tải lại trang và gửi lại thông tin.', 'play2grow-core' ) );
	}

	// 3. Fill-time check.
	$stamp = p2gc_check_stamp( isset( $post['p2g_stamp'] ) ? (string) $post['p2g_stamp'] : '' );
	if ( 'ok' !== $stamp ) {
		$respond( 'error', __( 'Biểu mẫu được gửi quá nhanh hoặc đã quá hạn. Vui lòng tải lại trang và thử lại.', 'play2grow-core' ) );
	}

	// 4. Rate limit per client IP (hashed). Behind a reverse proxy/CDN, REMOTE_ADDR must be
	// the real visitor IP, otherwise raise or disable the limit: add_filter( 'p2g_rate_limit', … ).
	$limit = (int) apply_filters( 'p2g_rate_limit', 5 );
	$key   = p2gc_client_key();
	$count = (int) get_transient( $key );
	if ( $limit > 0 && $count >= $limit ) {
		$respond( 'error', __( 'Bạn đã gửi khá nhiều lần trong thời gian ngắn. Vui lòng thử lại sau ít phút.', 'play2grow-core' ) );
	}

	// 5. Validation.
	list( $data, $errors ) = p2gc_validate_lead( $post );
	$values                = array_intersect_key( $data, array_flip( array( 'name', 'phone', 'email', 'interest', 'age', 'message' ) ) );
	if ( $errors ) {
		$respond( 'error', __( 'Vui lòng kiểm tra lại các trường được đánh dấu.', 'play2grow-core' ), $errors, $values );
	}

	if ( ! p2gc_form_ready() ) {
		$respond( 'error', __( 'Biểu mẫu chưa được cấu hình để nhận thông tin. Vui lòng liên hệ Play2Grow qua các kênh khác trên website.', 'play2grow-core' ), array(), $values );
	}

	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	// 6. Store + notify.
	$lead_id = p2gc_setting( 'lead_store' ) ? p2gc_insert_lead( $data ) : 0;
	$mailed  = p2gc_send_lead_mail( $data, $lead_id );
	if ( $lead_id && null !== $mailed ) {
		update_post_meta( $lead_id, '_p2g_mailed', $mailed ? '1' : '0' );
	}

	/**
	 * Fires after a lead was processed (e.g. to push it to a CRM from a plugin).
	 *
	 * @param array    $data    Clean data.
	 * @param int      $lead_id Stored lead ID or 0.
	 * @param bool|null $mailed Email result (null = no recipient configured).
	 */
	do_action( 'p2g_lead_received', $data, $lead_id, $mailed );

	if ( ! $lead_id && true !== $mailed ) {
		$respond( 'error', __( 'Rất tiếc, hệ thống chưa ghi nhận được thông tin do lỗi kỹ thuật. Vui lòng thử lại sau hoặc liên hệ Play2Grow qua các kênh khác trên website.', 'play2grow-core' ), array(), $values );
	}

	$respond( 'success', p2gc_success_message() );
}
add_action( 'admin_post_p2g_lead', 'p2gc_handle_lead' );
add_action( 'admin_post_nopriv_p2g_lead', 'p2gc_handle_lead' );

/**
 * Success message.
 *
 * @return string
 */
function p2gc_success_message() {
	$custom = trim( (string) p2gc_setting( 'lead_success_text' ) );

	return '' !== $custom ? $custom : __( 'Cảm ơn anh/chị! Play2Grow đã nhận được thông tin và sẽ liên hệ lại qua số điện thoại hoặc email anh/chị đã cung cấp.', 'play2grow-core' );
}

/**
 * Consent label HTML.
 *
 * @return string
 */
function p2gc_consent_label() {
	$custom = trim( (string) p2gc_setting( 'consent_text' ) );
	if ( '' !== $custom ) {
		return esc_html( $custom );
	}

	$policy = get_privacy_policy_url();
	$text   = __( 'Tôi là phụ huynh/người giám hộ hoặc người liên hệ từ đủ 18 tuổi, và đồng ý để Play2Grow sử dụng thông tin trên nhằm liên hệ phản hồi yêu cầu này.', 'play2grow-core' );

	if ( $policy ) {
		$text .= ' ' . sprintf(
			/* translators: %s: privacy policy link. */
			__( 'Xem %s.', 'play2grow-core' ),
			'<a href="' . esc_url( $policy ) . '" target="_blank" rel="noopener">' . esc_html__( 'Chính sách quyền riêng tư', 'play2grow-core' ) . '</a>'
		);
		return wp_kses( $text, array( 'a' => array( 'href' => true, 'target' => true, 'rel' => true ) ) );
	}

	return esc_html( $text );
}

/**
 * Render the form.
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2gc_contact_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'title' => '',
			'intro' => '',
		),
		$atts,
		'p2g_contact_form'
	);

	// No-JS result from a previous submission.
	$state = array();
	if ( isset( $_GET['p2g_t'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only token lookup.
		$token = preg_replace( '/[^A-Za-z0-9]/', '', sanitize_text_field( wp_unslash( $_GET['p2g_t'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $token ) {
			$state = (array) get_transient( 'p2g_form_' . $token );
			delete_transient( 'p2g_form_' . $token );
		}
	}
	$values = isset( $state['values'] ) ? (array) $state['values'] : array();
	$errors = isset( $state['errors'] ) ? (array) $state['errors'] : array();
	$val    = static function ( $key ) use ( $values ) {
		return isset( $values[ $key ] ) ? (string) $values[ $key ] : '';
	};

	ob_start();

	if ( ! p2gc_form_ready() ) {
		if ( p2gc_can_see_notes() ) {
			echo p2gc_editor_note( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
				sprintf(
					/* translators: %s: settings URL. */
					__( 'Form đang bị ẩn với khách truy cập vì chưa có kênh nhận thông tin: hãy bật "Lưu đăng ký" hoặc nhập email nhận thông báo tại <a href="%s">Cài đặt → Play2Grow</a>.', 'play2grow-core' ),
					esc_url( admin_url( 'options-general.php?page=p2gc-settings' ) )
				)
			);
		}
		return ob_get_clean();
	}

	p2gc_enqueue_form_script();

	$form_id = 'p2g-form';
	$field   = static function ( $name ) use ( $errors ) {
		return isset( $errors[ $name ] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $name ) . '-error"' : '';
	};
	$error   = static function ( $name ) use ( $errors ) {
		$msg = isset( $errors[ $name ] ) ? $errors[ $name ] : '';
		return '<p class="field__error" id="' . esc_attr( $name ) . '-error"' . ( $msg ? '' : ' hidden' ) . '>' . esc_html( $msg ) . '</p>';
	};
	?>
	<div class="lead-form-wrap" id="<?php echo esc_attr( $form_id ); ?>">
		<?php if ( $atts['title'] ) : ?>
			<h2 class="lead-form__title"><?php echo esc_html( $atts['title'] ); ?></h2>
		<?php endif; ?>
		<?php if ( $atts['intro'] ) : ?>
			<p class="lead-form__intro"><?php echo esc_html( $atts['intro'] ); ?></p>
		<?php endif; ?>

		<div class="form-status<?php echo ! empty( $state['status'] ) ? ' form-status--' . esc_attr( $state['status'] ) : ''; ?>" role="status" aria-live="polite" tabindex="-1"<?php echo empty( $state['message'] ) ? ' hidden' : ''; ?>>
			<?php
			if ( ! empty( $state['message'] ) ) {
				p2gc_the_icon( 'success' === $state['status'] ? 'check' : 'alert' );
				echo '<span>' . esc_html( $state['message'] ) . '</span>';
			}
			?>
		</div>

		<form class="lead-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate>
			<input type="hidden" name="action" value="p2g_lead">
			<input type="hidden" name="p2g_nonce" value="<?php echo esc_attr( wp_create_nonce( 'p2g_lead' ) ); ?>">
			<input type="hidden" name="p2g_stamp" value="<?php echo esc_attr( p2gc_form_stamp() ); ?>">
			<input type="hidden" name="p2g_source" value="<?php echo esc_attr( (string) get_queried_object_id() ); ?>">
			<div class="lead-form__hp" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden">
				<label for="p2g-website"><?php esc_html_e( 'Để trống trường này', 'play2grow-core' ); ?></label>
				<input type="text" id="p2g-website" name="p2g_website" value="" tabindex="-1" autocomplete="off">
			</div>

			<p class="lead-form__required-note"><?php echo wp_kses( __( 'Các trường có dấu <span class="req" aria-hidden="true">*</span> là bắt buộc.', 'play2grow-core' ), array( 'span' => array( 'class' => true, 'aria-hidden' => true ) ) ); ?></p>

			<div class="field">
				<label for="p2g-name"><?php esc_html_e( 'Họ và tên phụ huynh / người liên hệ', 'play2grow-core' ); ?> <span class="req" aria-hidden="true">*</span></label>
				<input type="text" id="p2g-name" name="p2g_name" autocomplete="name" required maxlength="80" value="<?php echo esc_attr( $val( 'name' ) ); ?>"<?php echo $field( 'p2g_name' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in closure. ?>>
				<?php echo $error( 'p2g_name' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in closure. ?>
			</div>

			<div class="field-row">
				<div class="field">
					<label for="p2g-phone"><?php esc_html_e( 'Số điện thoại', 'play2grow-core' ); ?></label>
					<input type="tel" id="p2g-phone" name="p2g_phone" autocomplete="tel" inputmode="tel" maxlength="20" value="<?php echo esc_attr( $val( 'phone' ) ); ?>"<?php echo $field( 'p2g_phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo $error( 'p2g_phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="field">
					<label for="p2g-email"><?php esc_html_e( 'Email', 'play2grow-core' ); ?></label>
					<input type="email" id="p2g-email" name="p2g_email" autocomplete="email" maxlength="120" value="<?php echo esc_attr( $val( 'email' ) ); ?>"<?php echo $field( 'p2g_email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo $error( 'p2g_email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			<p class="field__hint" id="p2g-contact-hint"><?php esc_html_e( 'Chỉ cần điền một trong hai: số điện thoại hoặc email.', 'play2grow-core' ); ?></p>

			<div class="field-row">
				<div class="field">
					<label for="p2g-interest"><?php esc_html_e( 'Anh/chị quan tâm đến', 'play2grow-core' ); ?></label>
					<select id="p2g-interest" name="p2g_interest">
						<option value=""><?php esc_html_e( '— Chọn nội dung —', 'play2grow-core' ); ?></option>
						<?php foreach ( p2gc_interest_options() as $option ) : ?>
							<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $val( 'interest' ), $option ); ?>><?php echo esc_html( $option ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php if ( p2gc_setting( 'lead_age_field' ) ) : ?>
					<div class="field">
						<label for="p2g-age"><?php esc_html_e( 'Nhóm tuổi của trẻ (không bắt buộc)', 'play2grow-core' ); ?></label>
						<select id="p2g-age" name="p2g_age">
							<option value=""><?php esc_html_e( '— Không chọn —', 'play2grow-core' ); ?></option>
							<?php foreach ( p2gc_age_options() as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $val( 'age' ), $option ); ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
			</div>

			<div class="field">
				<label for="p2g-message"><?php esc_html_e( 'Anh/chị cần Play2Grow hỗ trợ điều gì? (không bắt buộc)', 'play2grow-core' ); ?></label>
				<textarea id="p2g-message" name="p2g_message" rows="4" maxlength="1500"><?php echo esc_textarea( $val( 'message' ) ); ?></textarea>
				<p class="field__hint"><?php esc_html_e( 'Vui lòng không ghi họ tên đầy đủ, ảnh hay thông tin nhận dạng của trẻ.', 'play2grow-core' ); ?></p>
			</div>

			<div class="field field--checkbox">
				<input type="checkbox" id="p2g-consent" name="p2g_consent" value="1" required<?php echo $field( 'p2g_consent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<label for="p2g-consent"><?php echo p2gc_consent_label(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in function. ?> <span class="req" aria-hidden="true">*</span></label>
				<?php echo $error( 'p2g_consent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<button type="submit" class="btn btn--primary btn--block lead-form__submit">
				<span class="btn__label"><?php esc_html_e( 'Gửi thông tin', 'play2grow-core' ); ?></span>
			</button>
		</form>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'p2g_contact_form', 'p2gc_contact_form_shortcode' );

/**
 * Pages showing the form (?p2g_t=…) should not be cached/indexed with the token.
 *
 * @param array $robots Robots.
 * @return array
 */
function p2gc_form_token_robots( $robots ) {
	if ( isset( $_GET['p2g_t'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$robots['noindex'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'p2gc_form_token_robots' );

/**
 * Enqueue the form enhancement script (only on pages that show the form).
 */
function p2gc_enqueue_form_script() {
	if ( wp_script_is( 'p2gc-form', 'enqueued' ) ) {
		return;
	}
	wp_enqueue_script(
		'p2gc-form',
		P2GC_URL . 'assets/js/form.js',
		array(),
		P2GC_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_add_inline_script(
		'p2gc-form',
		'window.p2gcForm = ' . wp_json_encode(
			array(
				'sending'     => __( 'Đang gửi…', 'play2grow-core' ),
				'networkErr'  => __( 'Không gửi được do lỗi kết nối. Vui lòng kiểm tra mạng và thử lại.', 'play2grow-core' ),
				'required'    => __( 'Vui lòng điền thông tin này.', 'play2grow-core' ),
				'nameLen'     => __( 'Họ và tên cần từ 2 đến 80 ký tự.', 'play2grow-core' ),
				'contactReq'  => __( 'Vui lòng nhập số điện thoại hoặc email để Play2Grow có thể liên hệ lại.', 'play2grow-core' ),
				'phoneFormat' => __( 'Số điện thoại chưa đúng định dạng (ví dụ: 0912 345 678).', 'play2grow-core' ),
				'emailFormat' => __( 'Email chưa đúng định dạng (ví dụ: ten@email.com).', 'play2grow-core' ),
				'consentReq'  => __( 'Vui lòng đồng ý để Play2Grow xử lý thông tin để liên hệ lại.', 'play2grow-core' ),
			)
		) . ';',
		'before'
	);
}
