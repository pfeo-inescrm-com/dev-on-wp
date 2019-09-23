<?php
/*
 *	This is the archive page for search results.
 */


//Get Knowledge Base object
$bpkb_knowledge_base = basepress_kb();
$bpkb_is_single_kb = basepress_is_single_kb();
$bpkb_sidebar_position = basepress_sidebar_position( true );
$bpkb_show_sidebar = is_active_sidebar( 'basepress-sidebar' ) && $bpkb_sidebar_position != 'none';
$bpkb_content_classes = $bpkb_show_sidebar ? ' show-sidebar' : '';


//Get Post meta icons
$bpkb_post_meta_icons = basepress_get_post_meta_icons();
$bpkb_post_views_icon = isset( $bpkb_post_meta_icons[0] ) ? $bpkb_post_meta_icons[0] : '';
$bpkb_post_like_icon = isset( $bpkb_post_meta_icons[1] ) ? $bpkb_post_meta_icons[1] : '';
$bpkb_post_dislike_icon = isset( $bpkb_post_meta_icons[2] ) ? $bpkb_post_meta_icons[2] : '';
$bpkb_post_date_icon = isset( $bpkb_post_meta_icons[3] ) ? $bpkb_post_meta_icons[3] : '';

//Get active theme header
basepress_get_header( 'basepress' );
?>

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
		<div class="bpress-content-area bpress-float-<?php echo $bpkb_sidebar_position . $bpkb_content_classes; ?>">

			<!-- Add main content -->
			<main class="bpress-main" role="main">

				<?php if ( have_posts() ) { ?>

					<h1><?php echo basepress_search_page_title() . ' ' . basepress_search_term(); ?></h1>
					<ul class="bpress-post-list">

					<?php
					while ( have_posts() ) {
						the_post();
						$bpkb_show_post_icon = basepress_show_post_icon();
						$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
					?>

					<li class="bpress-post-link search bpress-clear">
						<div class="bpress-heading<?php echo $bpkb_post_class; ?>">
							<!-- Post icon -->
							<?php if ( basepress_show_post_icon() ) { ?>
								<span aria-hidden="true" class="bpress-heading-icon <?php echo basepress_post_icon( get_the_ID() ); ?> colored"></span>
							<?php } ?>
							<h3>
								<!-- Post permalink -->
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
						</div>

						<p><?php basepress_search_post_snippet(); ?></p>

						<?php	$bpkb_post_section = get_the_terms( get_the_ID(), 'knowledgebase_cat' )[0]; ?>
						<a href="<?php echo get_term_link( $bpkb_post_section ); ?>" class="bpress-search-section"><?php echo $bpkb_post_section->name; ?></a>

						<div class="bpress-post-meta">
							<?php $bpkb_post_metas = basepress_get_post_meta( get_the_ID() ); ?>

							<span class="bpress-post-views"><span class="<?php echo $bpkb_post_views_icon; ?>"></span><?php echo $bpkb_post_metas['views']; ?></span>
							<?php if ( basepress_show_post_votes() ) { ?>
								<span class="bpress-post-likes"><span class="<?php echo $bpkb_post_like_icon; ?>"></span><?php echo $bpkb_post_metas['votes']['like']; ?></span>
								<span class="bpress-post-dislikes"><span class="<?php echo $bpkb_post_dislike_icon; ?>"></span><?php echo $bpkb_post_metas['votes']['dislike']; ?></span>
							<?php } ?>
							<span class="bpress-post-date"><span class="<?php echo $bpkb_post_date_icon; ?>"></span><?php echo get_the_modified_date(); ?></span>
						</div>
					</li>

					<?php	} //End while ?>

					</ul>
				<?php
				} else {
					echo '<h3>' . basepress_search_page_no_results_title() . '</h3>';
				}
				?>

			</main>

			<!-- Pagination -->
			<nav class="bpress-pagination">
			<?php basepress_pagination(); ?>
			</nav>

		</div><!-- content area -->

		<!-- BasePress Sidebar -->
		<?php if ( $bpkb_show_sidebar ) : ?>
			<aside class="bpress-sidebar bpress-float-<?php echo $bpkb_sidebar_position; ?>" role="complementary">
				<div class="hide-scrollbars">
				<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
				</div>
			</aside>
		<?php endif; ?>

	</div>
</div><!-- wrap -->
<?php basepress_get_footer( 'basepress' ); ?>
