<?php
/*
 * The template part for displaying content
 */
?>

<article id="post-<?php the_ID(); ?>">
	<header class="bpress-main-header">
		<h1><?php the_title(); ?></h1>
	</header>

	<?php
		//Add the table of content
		basepress_get_template_part( 'table-of-content' );
	?>

	<?php the_content(); ?>


	<!-- Pagination -->
	<nav class="bpress-pagination">
		<?php	basepress_post_pagination(); ?>
	</nav>

</article>
