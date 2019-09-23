<?php
/*
 *	This is the template which lists all sections for a product
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();
$bpkb_show_icon = basepress_show_section_icon();
$bpkb_header_class = $bpkb_show_icon ? ' class="show-icon"' : '';

//We can iterate through the sections
foreach ( $bpkb_sections as $bpkb_section ) : ?>

		<!-- Section Title -->
	<header class="bpress-post-header">
		<h1<?php echo $bpkb_header_class; ?>>
			<!-- Section icon -->
			<?php if ( basepress_show_section_icon() ) { ?>
				<span aria-hidden="true" class="bpress-section-icon <?php echo $bpkb_section->icon; ?>"></span>
			<?php } ?>

			<!-- Section Title -->
			<?php echo $bpkb_section->name; ?>
		</h1>
	</header>


	<!-- Post list -->
	<?php if ( $bpkb_section->posts_count ) : ?>
		<div class="bpress-card">
			<ul class="bpress-card-body">

		<?php
		foreach ( $bpkb_section->posts as $bpkb_article ) :
			$bpkb_show_post_icon = basepress_show_post_icon();
			$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
		?>
				<li class="bpress-post-link single-section<?php echo $bpkb_post_class; ?>">

					<!-- Post icon -->
					<?php if ( basepress_show_post_icon() ) { ?>
						<span aria-hidden="true" class="bp-icon <?php echo $bpkb_article->icon; ?>"></span>
					<?php } ?>

					<!-- Post permalink -->
					<a href="<?php echo get_the_permalink( $bpkb_article->ID ); ?>">
						<?php echo $bpkb_article->post_title; ?>
					</a>
				</li>
		<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

<?php endforeach; ?>
