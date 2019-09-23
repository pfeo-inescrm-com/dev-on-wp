<?php
/**
 * This is the template to show the Comments
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="bpress-comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="bpress-comments-title">
			<?php
			printf( _n( '%1$s response to %2$s', '%1$s responses to %2$s', get_comments_number() ),
			number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<?php paginate_comments_links(); ?>
		<?php endif; // Check for comment navigation. ?>

		<ol class="bpress-comment-list">
			<?php
			wp_list_comments( array(
				'walker'      => new BasePress_Walker_Comment(),
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
			));
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

			<?php paginate_comments_links(); ?>
		<?php endif; // Check for comment navigation. ?>

		<?php if ( ! comments_open() ) : ?>
			<p><?php _e( 'Comments are closed.', 'basepress' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		$bpkb_comments_args = array(
			'title_reply_before' => '<h3 class="bpress-comment-reply-title">',
			'title_reply_after'  => '</h3>',
			'comment_field'      => '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>',
			'id_form'            => 'bpress-commentform',
			'class_form'         => 'bpress-comment-form',
		);
		if ( comments_open() ) {
			comment_form( $bpkb_comments_args );
		}
	?>

</div><!-- #comments -->
