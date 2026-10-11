<?php
/**
 * Optional starter content importer (Giao diện → Play2Grow → Nội dung khởi đầu).
 *
 * Safety rules:
 * - Runs only when an administrator presses the button (never on activation).
 * - Never overwrites or deletes anything: an item is skipped if its slug already exists.
 * - Creates DRAFTS by default so the owner can review every page before publishing.
 * - Menus / reading settings are only changed in a separate, explicit step.
 *
 * Content lives in /starter-content as block markup files. Tokens:
 *   {{page:path}} {{post:slug}} {{cat:slug}} {{posts_page}} {{asset:file}}
 *   <!-- p2g:pattern play2grow/slug --> (renders a pattern from /patterns)
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manifest of starter content.
 *
 * @return array
 */
function p2g_starter_manifest() {
	$file = P2G_DIR . '/starter-content/manifest.php';

	return file_exists( $file ) ? (array) include $file : array();
}

/**
 * Link to an item that may still be a draft: the future pretty permalink when
 * permalinks are enabled, otherwise the ?page_id= / ?p= form.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function p2g_starter_link( $post_id ) {
	if ( 'publish' === get_post_status( $post_id ) || '' === (string) get_option( 'permalink_structure' ) ) {
		return (string) get_permalink( $post_id );
	}
	if ( ! function_exists( 'get_sample_permalink' ) ) {
		require_once ABSPATH . 'wp-admin/includes/post.php';
	}
	list( $template, $name ) = get_sample_permalink( $post_id );

	return str_replace( array( '%pagename%', '%postname%' ), $name, $template );
}

/**
 * Render a pattern file by slug (play2grow/xyz → patterns/*.php with that Slug header).
 *
 * @param string $slug Pattern slug.
 * @return string
 */
function p2g_render_pattern_file( $slug ) {
	foreach ( glob( P2G_DIR . '/patterns/*.php' ) as $file ) {
		$headers = get_file_data( $file, array( 'slug' => 'Slug' ) );
		if ( $headers['slug'] === $slug ) {
			ob_start();
			include $file;
			return trim( ob_get_clean() );
		}
	}

	return '';
}

/**
 * Replace tokens in starter content.
 *
 * @param string $content Content.
 * @return string
 */
function p2g_starter_tokens( $content ) {
	$content = preg_replace_callback(
		'/<!-- p2g:pattern ([a-z0-9\/-]+) -->/',
		static function ( $m ) {
			return p2g_render_pattern_file( $m[1] );
		},
		$content
	);

	return preg_replace_callback(
		'/\{\{(page|post|cat|posts_page|asset)(?::([a-z0-9\/._-]+))?\}\}/',
		static function ( $m ) {
			$arg = isset( $m[2] ) ? $m[2] : '';
			switch ( $m[1] ) {
				case 'page':
					$url = p2g_page_link( $arg );
					break;
				case 'post':
					$url = p2g_post_link( $arg );
					break;
				case 'cat':
					$term = get_category_by_slug( $arg );
					$url  = $term ? get_category_link( $term ) : '';
					break;
				case 'posts_page':
					$url = p2g_posts_page_link();
					break;
				default:
					$url = p2g_image_url( $arg );
			}
			return $url ? esc_url( $url ) : esc_url( home_url( '/' ) );
		},
		$content
	);
}

/**
 * Run the import.
 *
 * @param string $status "draft" or "publish".
 * @return array Report: created, skipped.
 */
function p2g_run_starter_import( $status ) {
	$manifest = p2g_starter_manifest();
	$report   = array(
		'created' => array(),
		'skipped' => array(),
	);
	$created  = array();

	// Categories.
	foreach ( (array) $manifest['categories'] as $slug => $cat ) {
		if ( get_category_by_slug( $slug ) ) {
			$report['skipped'][] = sprintf( 'Chuyên mục "%s" (đã tồn tại)', $cat['name'] );
			continue;
		}
		$term = wp_insert_term(
			$cat['name'],
			'category',
			array(
				'slug'        => $slug,
				'description' => $cat['description'],
			)
		);
		if ( ! is_wp_error( $term ) ) {
			$report['created'][] = sprintf( 'Chuyên mục "%s"', $cat['name'] );
		}
	}

	// Pages (parents first – manifest order) and posts.
	$items = array();
	foreach ( (array) $manifest['pages'] as $page ) {
		$items[] = array_merge( $page, array( 'type' => 'page' ) );
	}
	foreach ( (array) $manifest['posts'] as $post ) {
		$items[] = array_merge( $post, array( 'type' => 'post' ) );
	}

	foreach ( $items as $item ) {
		$path     = 'page' === $item['type'] && ! empty( $item['parent'] ) ? $item['parent'] . '/' . $item['slug'] : $item['slug'];
		$existing = 'page' === $item['type']
			? get_page_by_path( $path )
			: get_posts(
				array(
					'name'           => $item['slug'],
					'post_type'      => 'post',
					'post_status'    => 'any',
					'posts_per_page' => 1,
				)
			);
		$replacement = false;
		if ( $existing && ( ! is_array( $existing ) || ! empty( $existing ) ) ) {
			if ( 'post' === $item['type'] ) {
				$report['skipped'][] = sprintf( '"%s" (đường dẫn /%s/ đã tồn tại – giữ nguyên nội dung cũ)', $item['title'], $path );
				continue;
			}
			// Never touch the existing page: create a separate draft the owner can review
			// and then copy into the original page (keeping its URL).
			$replacement     = true;
			$item['slug']   .= '-ban-moi';
			$item['title']   = '[Bản mới] ' . $item['title'];
			$report['skipped'][] = sprintf( '/%1$s/ đã tồn tại – giữ nguyên trang cũ, tạo bản nháp "%2$s" để so sánh', $path, $item['title'] );
			if ( get_page_by_path( ( ! empty( $item['parent'] ) ? $item['parent'] . '/' : '' ) . $item['slug'] ) ) {
				continue;
			}
		}

		$file = P2G_DIR . '/starter-content/' . $item['file'];
		if ( ! file_exists( $file ) ) {
			continue;
		}

		$parent_id = 0;
		if ( ! empty( $item['parent'] ) ) {
			$parent    = get_page_by_path( $item['parent'] );
			$parent_id = $parent ? (int) $parent->ID : 0;
		}

		$postarr = array(
			'post_type'    => $item['type'],
			'post_status'  => $replacement ? 'draft' : $status,
			'post_title'   => $item['title'],
			'post_name'    => $item['slug'],
			'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
			'post_content' => '',
			'post_parent'  => $parent_id,
			'menu_order'   => isset( $item['order'] ) ? (int) $item['order'] : 0,
			'post_author'  => get_current_user_id(),
		);
		if ( 'page' === $item['type'] ) {
			$postarr['comment_status'] = 'closed';
			$postarr['ping_status']    = 'closed';
		}

		$id = wp_insert_post( wp_slash( $postarr ), true );
		if ( is_wp_error( $id ) ) {
			continue;
		}

		if ( ! empty( $item['template'] ) ) {
			update_post_meta( $id, '_wp_page_template', $item['template'] );
		}
		if ( ! empty( $item['description'] ) ) {
			update_post_meta( $id, '_p2g_meta_description', $item['description'] );
		}
		if ( ! empty( $item['category'] ) ) {
			$term = get_category_by_slug( $item['category'] );
			if ( $term ) {
				wp_set_post_categories( $id, array( $term->term_id ) );
			}
		}
		if ( ! empty( $item['tags'] ) ) {
			wp_set_post_tags( $id, $item['tags'] );
		}

		$created[ $id ]      = $file;
		$report['created'][] = sprintf( '%1$s "%2$s"', 'page' === $item['type'] ? 'Trang' : 'Bài viết', $item['title'] );
	}

	// Second pass: fill content now that every link target exists.
	$GLOBALS['p2g_importing'] = true;
	foreach ( $created as $id => $file ) {
		$content = p2g_starter_tokens( (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
		wp_update_post(
			wp_slash(
				array(
					'ID'           => $id,
					'post_content' => $content,
				)
			)
		);
	}
	$GLOBALS['p2g_importing'] = false;

	$log = (array) get_option( 'p2g_starter_ids', array() );
	update_option( 'p2g_starter_ids', array_values( array_unique( array_merge( $log, array_keys( $created ) ) ) ), false );

	return $report;
}

/**
 * Finalize: menus + reading settings + privacy page, using PUBLISHED starter pages only.
 *
 * @return array Report lines.
 */
function p2g_run_starter_finalize() {
	$manifest = p2g_starter_manifest();
	$lines    = array();

	$front = p2g_page_id_by_path( 'trang-chu' );
	$blog  = p2g_page_id_by_path( 'goc-hoc-tap' );
	if ( $front ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front );
		$lines[] = 'Đã đặt "Trang chủ" làm trang chủ tĩnh.';
	} else {
		$lines[] = 'Bỏ qua trang chủ: trang /trang-chu/ chưa được xuất bản.';
	}
	if ( $blog ) {
		update_option( 'page_for_posts', $blog );
		$lines[] = 'Đã đặt "Góc học tập" làm trang bài viết.';
	}

	$privacy = p2g_page_id_by_path( 'chinh-sach-quyen-rieng-tu' );
	if ( $privacy ) {
		update_option( 'wp_page_for_privacy_policy', $privacy );
		$lines[] = 'Đã chọn "Chính sách quyền riêng tư" trong Cài đặt → Quyền riêng tư.';
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	foreach ( (array) $manifest['menus'] as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			$lines[] = sprintf( 'Giữ nguyên menu đang gán cho vị trí "%s".', $location );
			continue;
		}
		$menu_obj = wp_get_nav_menu_object( $menu['name'] );
		$menu_id  = $menu_obj ? (int) $menu_obj->term_id : (int) wp_create_nav_menu( $menu['name'] );
		if ( ! $menu_id || is_wp_error( $menu_id ) ) {
			continue;
		}
		if ( ! $menu_obj ) {
			$parents = array();
			foreach ( $menu['items'] as $entry ) {
				list( $path, $label ) = $entry;
				$parent_label         = isset( $entry[2] ) ? $entry[2] : '';
				$args                 = array(
					'menu-item-title'     => $label,
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => $parent_label && isset( $parents[ $parent_label ] ) ? $parents[ $parent_label ] : 0,
				);
				if ( 0 === strpos( $path, 'cat:' ) ) {
					$term = get_category_by_slug( substr( $path, 4 ) );
					if ( ! $term ) {
						continue;
					}
					$args['menu-item-object']    = 'category';
					$args['menu-item-object-id'] = $term->term_id;
					$args['menu-item-type']      = 'taxonomy';
				} else {
					$page_id = p2g_page_id_by_path( $path );
					if ( ! $page_id ) {
						continue;
					}
					$args['menu-item-object']    = 'page';
					$args['menu-item-object-id'] = $page_id;
					$args['menu-item-type']      = 'post_type';
				}
				$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
				if ( ! is_wp_error( $item_id ) && ! isset( $parents[ $label ] ) ) {
					$parents[ $label ] = (int) $item_id;
				}
			}
		}
		$locations[ $location ] = $menu_id;
		$lines[]                = sprintf( 'Đã tạo/gán menu "%1$s" cho vị trí "%2$s".', $menu['name'], $location );
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	return $lines;
}

/**
 * Handle the admin-post actions.
 */
function p2g_handle_starter_actions() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Bạn không có quyền thực hiện thao tác này.', 'play2grow-theme' ), 403 );
	}
	check_admin_referer( 'p2g_starter' );

	$do = isset( $_POST['p2g_do'] ) ? sanitize_key( wp_unslash( $_POST['p2g_do'] ) ) : '';
	if ( 'import' === $do ) {
		$status = ( isset( $_POST['p2g_status'] ) && 'publish' === $_POST['p2g_status'] ) ? 'publish' : 'draft';
		$report = p2g_run_starter_import( $status );
		set_transient( 'p2g_import_report_' . get_current_user_id(), $report, 10 * MINUTE_IN_SECONDS );
	} elseif ( 'finalize' === $do ) {
		$lines = p2g_run_starter_finalize();
		set_transient(
			'p2g_import_report_' . get_current_user_id(),
			array(
				'created' => $lines,
				'skipped' => array(),
			),
			10 * MINUTE_IN_SECONDS
		);
	}

	wp_safe_redirect( admin_url( 'themes.php?page=p2g-settings#p2g-starter' ) );
	exit;
}
add_action( 'admin_post_p2g_starter', 'p2g_handle_starter_actions' );

/**
 * Show the last import report.
 */
function p2g_render_import_notice() {
	$key    = 'p2g_import_report_' . get_current_user_id();
	$report = get_transient( $key );
	if ( ! $report ) {
		return;
	}
	delete_transient( $key );
	echo '<div class="notice notice-success"><p><strong>' . esc_html__( 'Kết quả:', 'play2grow-theme' ) . '</strong></p><ul style="list-style:disc;padding-left:20px">';
	foreach ( (array) $report['created'] as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}
	foreach ( (array) $report['skipped'] as $line ) {
		echo '<li>' . esc_html__( 'Bỏ qua:', 'play2grow-theme' ) . ' ' . esc_html( $line ) . '</li>';
	}
	echo '</ul></div>';
}

/**
 * Starter content section on the settings page.
 */
function p2g_render_import_section() {
	$manifest = p2g_starter_manifest();
	if ( ! $manifest ) {
		return;
	}
	?>
	<h2 id="p2g-starter"><?php esc_html_e( 'Nội dung khởi đầu (tùy chọn)', 'play2grow-theme' ); ?></h2>
	<p style="max-width:900px"><?php esc_html_e( 'Tạo các trang và bài viết tiếng Việt đã biên soạn sẵn cho Play2Grow. Thao tác này KHÔNG ghi đè hay xóa nội dung hiện có: trang/bài nào trùng đường dẫn sẽ được bỏ qua. Các đoạn cần chủ sở hữu xác nhận được đánh dấu bằng ghi chú nội bộ (chỉ người đăng nhập mới thấy).', 'play2grow-theme' ); ?></p>
	<details style="max-width:900px;margin-bottom:12px"><summary><?php esc_html_e( 'Danh sách sẽ được tạo', 'play2grow-theme' ); ?></summary>
		<ul style="list-style:disc;padding-left:20px">
			<?php foreach ( (array) $manifest['pages'] as $page ) : ?>
				<li><?php echo esc_html( sprintf( 'Trang: %1$s (/%2$s/)', $page['title'], ( ! empty( $page['parent'] ) ? $page['parent'] . '/' : '' ) . $page['slug'] ) ); ?></li>
			<?php endforeach; ?>
			<?php foreach ( (array) $manifest['posts'] as $post ) : ?>
				<li><?php echo esc_html( sprintf( 'Bài viết: %s', $post['title'] ) ); ?></li>
			<?php endforeach; ?>
		</ul>
	</details>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-bottom:16px">
		<?php wp_nonce_field( 'p2g_starter' ); ?>
		<input type="hidden" name="action" value="p2g_starter">
		<input type="hidden" name="p2g_do" value="import">
		<fieldset>
			<label><input type="radio" name="p2g_status" value="draft" checked> <?php esc_html_e( 'Tạo dưới dạng bản nháp (khuyến nghị – rà soát rồi xuất bản từng trang)', 'play2grow-theme' ); ?></label><br>
			<label><input type="radio" name="p2g_status" value="publish"> <?php esc_html_e( 'Xuất bản ngay (chỉ dùng trên website thử nghiệm/staging)', 'play2grow-theme' ); ?></label>
		</fieldset>
		<?php submit_button( __( 'Bước 1: Tạo nội dung khởi đầu', 'play2grow-theme' ), 'secondary', 'submit', false ); ?>
	</form>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'p2g_starter' ); ?>
		<input type="hidden" name="action" value="p2g_starter">
		<input type="hidden" name="p2g_do" value="finalize">
		<p style="max-width:900px"><?php esc_html_e( 'Sau khi đã xuất bản các trang: tạo menu (chỉ cho vị trí chưa có menu), đặt Trang chủ / Góc học tập trong Cài đặt → Đọc và chọn trang Chính sách quyền riêng tư. Chỉ dùng các trang đã xuất bản.', 'play2grow-theme' ); ?></p>
		<?php submit_button( __( 'Bước 2: Tạo menu & cài đặt trang chủ', 'play2grow-theme' ), 'secondary', 'submit', false ); ?>
	</form>
	<?php
}
