<?php
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="mx-auto mt-14 max-w-prose px-4 sm:px-6">
	<?php if ( have_comments() ) : ?>
		<h2 class="font-serif text-2xl font-bold text-slate-900 dark:text-white">
			<?php
			$Argamora_count = get_comments_number();
			printf(
				esc_html( _nx( '%s comment', '%s comments', $Argamora_count, 'comments title', 'Argamora' ) ),
				esc_html( number_format_i18n( $Argamora_count ) )
			);
			?>
		</h2>

		<ol class="comment-list mt-8">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'callback'    => 'Argamora_comment_callback',
					'avatar_size' => 40,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="mt-6 text-slate-500 dark:text-slate-400"><?php esc_html_e( 'Comments are closed.', 'Argamora' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'          => __( 'Leave a comment', 'Argamora' ),
			'title_reply_before'   => '<h2 id="reply-title" class="mt-12 font-serif text-2xl font-bold text-slate-900 dark:text-white">',
			'title_reply_after'    => '</h2>',
			'comment_notes_before' => '<p class="mt-4 text-sm text-slate-500 dark:text-slate-400">' . __( 'Your email address will not be published.', 'Argamora' ) . '</p>',
			'class_submit'         => 'cursor-pointer rounded-full bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700',
			'submit_field'         => '<p class="form-submit mt-4">%1$s %2$s</p>',
		)
	);
	?>
</div>
