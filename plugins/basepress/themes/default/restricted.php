<?php
/*
 * This id the template for the restricted content page
 */

$bpkb_sidebar_position = basepress_sidebar_position( true );
$bpkb_show_sidebar = is_active_sidebar( 'basepress-sidebar' ) && $bpkb_sidebar_position != 'none';
$bpkb_content_classes = $bpkb_show_sidebar ? ' show-sidebar' : '';

basepress_get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">
	
	<div class="bpress-content-area bpress-float-<?php echo $bpkb_sidebar_position . $bpkb_content_classes; ?>">
		
		<!-- Add breadcrumbs -->
		<div class="bpress-crumbs-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>
		
		<!-- Add searchbar -->
		<div class="bpress-searchbar-wrap">	
			<?php basepress_searchbar(); ?>
		</div>
		
		<!-- Add main content -->
		<main class="bpress-main" role="main">
			<header class="bpress-main-header">
				<h1><?php the_title(); ?></h1>
			</header>

			<?php if ( basepress_show_restricted_teaser() ) { ?>
			<div class="article-teaser">
			<?php echo basepress_article_teaser(); ?>
			</div>
			<?php } ?>

			<div class="bpress-restricted-notice"><?php echo basepress_restricted_notice(); ?></div>

			<?php if ( ! is_user_logged_in() && basepress_show_restricted_login() ) { ?>
			<div class="bpress-login">
			<?php
			$bpkb_form_args = array(
				'echo'           => true,
				'remember'       => true,
				'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'form_id'        => 'bpress-loginform',
				'id_username'    => 'user_login',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'bpress-restricted-login-submit',
				'label_username' => __( 'Username', 'basepress' ),
				'label_password' => __( 'Password', 'basepress' ),
				'label_remember' => __( 'Remember Me', 'basepress' ),
				'label_log_in'   => __( 'Log In', 'basepress' ),
				'value_username' => '',
				'value_remember' => false,
			);
			wp_login_form( $bpkb_form_args );
			?>
			</div>
			<?php } ?>
		</main>

	</div><!-- content area -->

	<!-- Sidebar -->
	<?php if ( $bpkb_show_sidebar ) : ?>
	<aside class="bpress-sidebar bpress-float-<?php echo $bpkb_sidebar_position; ?>" role="complementary">
		<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
	</aside>
	<?php endif; ?>
	
</div><!-- .wrap -->

<?php basepress_get_footer( 'basepress' ); ?>
