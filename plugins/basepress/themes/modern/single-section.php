<?php
/*
 *	This is the archive page for a single section.
 */


//Get Knowledge Base object
$bpkb_knowledge_base = basepress_kb();
$bpkb_is_single_kb = basepress_is_single_kb();
$bpkb_sidebar_position = basepress_sidebar_position( true );
$bpkb_show_sidebar = is_active_sidebar( 'basepress-sidebar' ) && $bpkb_sidebar_position != 'none';
$bpkb_content_classes = $bpkb_show_sidebar ? ' show-sidebar' : '';

//Get active theme header
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
		<div class="bpress-content-area bpress-float-<?php echo $bpkb_sidebar_position . $bpkb_content_classes; ?>">

			<!-- Add main content -->
			<main class="bpress-main" role="main">
				<?php basepress_get_template_part( 'single-section-content' ); ?>
			</main>


			<!-- Sub Sections -->
			<?php
			if ( basepress_subsection_style() == 'boxed' ) {
				basepress_get_template_part( 'sections-content-boxed' );
			} else {
				basepress_get_template_part( 'sections-content' );
			}
			?>

		</div><!-- content area -->

		<!-- Sidebar -->
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
