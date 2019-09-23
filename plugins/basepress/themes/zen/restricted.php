<?php
/*
 * This id the template for the restricted content page
 */

//Get Knowledge Base object
$bpkb_knowledge_base = basepress_kb();
$bpkb_is_single_kb = basepress_is_single_kb();
$bpkb_sidebar_position = basepress_sidebar_position( true );
$bpkb_show_sidebar = is_active_sidebar( 'basepress-sidebar' ) && $bpkb_sidebar_position != 'none';

$bpkb_show_icon = basepress_show_post_icon();
$bpkb_header_class = $bpkb_show_icon ? ' class="show-icon"' : '';

basepress_get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">
	
	<!-- Product title -->
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
		
		<!-- Add searchbar -->
		<div class="bpress-card">
			<?php basepress_searchbar(); ?>
		</div>

		<!-- Add main content -->
		<main class="bpress-main" role="main">
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

					<?php if ( basepress_show_restricted_teaser() ) { ?>
					<div class="bpress-card-body">
						<div class="article-teaser"><?php echo basepress_article_teaser(); ?></div>
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
				</div>
			</article>
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
