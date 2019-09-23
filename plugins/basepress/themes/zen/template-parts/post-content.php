<?php
/*
 * The template part for displaying content
 */

$bpkb_show_icon = basepress_show_post_icon();
$bpkb_header_class = $bpkb_show_icon ? ' class="show-icon"' : '';
?>

<article id="post-<?php the_ID(); ?>">
	<header class="bpress-post-header">
		<h1<?php echo $bpkb_header_class; ?>>
			<?php if ( basepress_show_post_icon() ) { ?>
				<span aria-hidden="true" class="<?php echo basepress_post_icon( get_the_ID() ); ?>"></span>
			<?php } ?>
			<?php the_title(); ?>
		</h1>
	</header>

	<div class="bpress-card">
		<?php
		//Add the table of content
		basepress_get_template_part( 'table-of-content' );
		?>

		<div class="bpress-card-body">
		<?php the_content(); ?>
		</div>

		<!-- Pagination -->
		<nav class="bpress-pagination">
			<?php basepress_post_pagination(); ?>
		</nav>

		<!-- Get Polls Items -->
		<?php basepress_votes(); ?>

		<!-- Add previous and next articles navigation -->
		<?php basepress_get_template_part( 'adjacent-articles' ); ?>
	</div>
</article>
