<?php
/**
 * Comments.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$p2g_count = (int) get_comments_number();
			/* translators: %s: number of comments. */
			echo esc_html( sprintf( _n( '%s bình luận', '%s bình luận', $p2g_count, 'play2grow' ), number_format_i18n( $p2g_count ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( '← Bình luận cũ hơn', 'play2grow' ),
				'next_text' => __( 'Bình luận mới hơn →', 'play2grow' ),
			)
		);
	endif;

	if ( ! comments_open() && get_comments_number() ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Bài viết đã đóng bình luận.', 'play2grow' ); ?></p>
		<?php
	endif;

	comment_form(
		array(
			'title_reply'          => __( 'Để lại bình luận', 'play2grow' ),
			'title_reply_to'       => __( 'Trả lời %s', 'play2grow' ),
			'cancel_reply_link'    => __( 'Hủy trả lời', 'play2grow' ),
			'label_submit'         => __( 'Gửi bình luận', 'play2grow' ),
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Email của bạn sẽ không được hiển thị công khai. Vui lòng không chia sẻ thông tin cá nhân của trẻ trong bình luận.', 'play2grow' ) . '</p>',
			'class_submit'         => 'btn btn--primary',
		)
	);
	?>
</section>
