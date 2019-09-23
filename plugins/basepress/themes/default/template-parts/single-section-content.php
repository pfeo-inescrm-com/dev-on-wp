<?php
/*
 *	This template displays a single section content
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();

//We can iterate through the sections
foreach ( $bpkb_sections as $bpkb_section ) :
?>
	
		<div class="bpress-section">

			<?php if ( $bpkb_section->posts_count ) : ?>
			<!-- Section Title -->
			<h1 class="bpress-section-title">
				<!-- Section icon -->
				<?php if ( basepress_show_section_icon() ) { ?>
					<span aria-hidden="true" class="bpress-section-icon <?php echo $bpkb_section->icon; ?>"></span>
				<?php } ?>
				
				<!-- Section permalink -->
				<a href="<?php echo $bpkb_section->permalink; ?>"><?php echo $bpkb_section->name; ?></a>
			</h1>
			<?php endif; ?>

			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $bpkb_section->posts as $bpkb_article ) :
					$bpkb_show_post_icon = basepress_show_post_icon();
					$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
					?>
					<li class="bpress-post-link single-section<?php echo $bpkb_post_class; ?>">

						<!-- Post permalink -->
						<a href="<?php echo get_the_permalink( $bpkb_article->ID ); ?>">

							<!-- Post icon -->
							<?php if ( basepress_show_post_icon() ) { ?>
							<span aria-hidden="true" class="bpress-section-icon <?php echo $bpkb_article->icon; ?>"></span>
							<?php } ?>

							<?php echo $bpkb_article->post_title; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- End section -->
	
<?php endforeach; ?>
