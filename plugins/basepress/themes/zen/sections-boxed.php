<?php
/*
 *	This is BasePress archive page for all the sections in a Knowledge Base.
 */


//Get Knowledge Base object
$bpkb_knowledge_base = basepress_kb();
$bpkb_is_single_kb = basepress_is_single_kb();
$bpkb_sidebar_position = basepress_sidebar_position( true );
$bpkb_show_sidebar = is_active_sidebar( 'basepress-sidebar' ) && $bpkb_sidebar_position != 'none';

//Get active theme header
basepress_get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">

	<!-- Knowledge Base title -->
	<?php if( ! $bpkb_is_single_kb ) :?>
		<header class="bpress-main-header">
			<h2 class="bpress-product-title bpress-kb-title"><?php echo $bpkb_knowledge_base->name; ?></h2>
		</header>
	<?php endif; ?>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<?php basepress_breadcrumbs(); ?>
	</div>

	<div class="bpress-content-area bpress-float-<?php echo $bpkb_sidebar_position; ?>">
		
		<!-- Add search-bar -->
		<div class="bpress-card">
			<?php basepress_searchbar(); ?>
		</div>
		
		<!-- Add main content -->
		<main role="main">
			<?php basepress_get_template_part( 'sections-content-boxed' ); ?>
		</main>
	</div><!-- content area -->
	
	<!-- Sidebar -->
	<?php if ( $bpkb_show_sidebar ) : ?>
	<aside class="bpress-sidebar bpress-float-<?php echo $bpkb_sidebar_position; ?>" role="complementary">
		<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
	</aside>
	<?php endif; ?>

</div><!-- wrap -->
<?php basepress_get_footer( 'basepress' ); ?>
