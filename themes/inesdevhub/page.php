<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ines CRM Developer Hub
 */

get_header();

// get_template_part('template-parts/breadcrum-area-fullwidth');

?>



<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<!--================================
    START TIMELINE AREA
    =================================-->
		<section class="timeline_area section--padding">
			<div class="container-fluid">

				<div class="row">

					<?php if (is_active_sidebar('inesdevhub-sidebar-main')) : ?>
					<!-- start col-lg-9 -->
					<div class="col-lg-9 col-12">
						<?php else : ?>
						<!-- start col-12 -->
						<div class="col-12">
							<?php endif; ?>

							<?php
							while (have_posts()) :
								the_post();

								get_template_part('template-parts/content', 'page');

								// If comments are open or we have at least one comment, load up the comment template.
								if (comments_open() || get_comments_number()) :
									comments_template();
								endif;

							endwhile; // End of the loop.
							?>

						</div>
						<!-- end /.col if -->


						<?php if (is_active_sidebar('inesdevhub-sidebar-main')) : ?>
						<!-- start .col-lg-3 -->
						<div class="col-lg-3 col-12 mt-5 mt-lg-0">
							<!-- start aside -->
							<aside class="sidebar product--sidebar">
								<?php dynamic_sidebar('inesdevhub-sidebar-main'); ?>
							</aside>
							<!-- end aside -->
						</div>
						<!-- end /.col-lg-3 -->
						<?php endif; ?>





					</div>
				</div>
		</section>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
