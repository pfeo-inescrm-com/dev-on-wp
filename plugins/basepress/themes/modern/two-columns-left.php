<?php
/* Template Name: Left Sidebar */

//Get Knowledge Base object
$bpkb_knowledge_base = basepress_kb();
$bpkb_is_single_kb = basepress_is_single_kb();

basepress_get_header( 'basepress' );
?>

	<!-- Main BasePress wrap -->
	<div class="bpress-wrap">

	<div class="bpress-page-header">
		<div class="bpress-content-wrap">
			<!-- Knowledge Base title -->
			<header>
				<h2><?php echo apply_filters( 'basepress_modern_theme_header_title', 'Knowledge Base' ); ?><br><?php echo ( $bpkb_is_single_kb ? '' : $bpkb_knowledge_base->name ); ?></h2>
			</header>

			<!-- Add searchbar -->
			<div class="bpress-searchbar-wrap">
				<?php basepress_searchbar(); ?>
			</div>
		</div>
	</div>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<div class="bpress-content-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>
	</div>

	<div class="bpress-content-wrap">
		<div class="bpress-content-area bpress-float-right">

			<!-- Add main content -->
			<main class="bpress-main" role="main">

				<?php
				//Start the loop.
				while ( have_posts() ) : the_post();

					//Include the page content template using basepress internal function.
					basepress_get_template_part( 'post-content' );

					//Get Polls Items
					basepress_votes();

					//End of the loop.
				endwhile;

				?>

				<!-- Add previous and next articles navigation -->
				<?php basepress_get_template_part( 'adjacent-articles' ); ?>

			</main>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

			</div><!-- content area -->

			<!-- Sidebar -->
			<?php if ( is_active_sidebar( 'basepress-sidebar' ) ) : ?>
				<aside class="bpress-sidebar bpress-float-right" role="complementary">
					<div class="hide-scrollbars">
					<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
					</div>
				</aside>
			<?php endif; ?>
		</div>
	</div><!-- .wrap -->

<?php basepress_get_footer( 'basepress' ); ?>
