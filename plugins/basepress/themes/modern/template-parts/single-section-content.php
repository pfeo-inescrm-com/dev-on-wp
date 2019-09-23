<?php
/*
 *	This template displays a single section content
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();

//We can iterate through the sections
foreach ( $bpkb_sections as $bpkb_section ) : ?>
	
		<div class="bpress-single-section">

			<!-- Section Title -->
			<?php
				$bpkb_show_icon = basepress_show_section_icon();
				$bpkb_section_class = $bpkb_show_icon ? ' show-icon' : '';
			?>
			<div class="bpress-heading<?php echo $bpkb_section_class; ?>">
				<!-- Section icon -->
				<?php if ( $bpkb_show_icon ) { ?>
					<span aria-hidden="true" class="bpress-heading-icon <?php echo $bpkb_section->icon; ?> colored"></span>
				<?php } ?>

				<h1><?php echo $bpkb_section->name; ?></h1>
			</div>

			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $bpkb_section->posts as $bpkb_article ) :
					$bpkb_show_post_icon = basepress_show_post_icon();
					$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
				?>
				<li class="bpress-post-link single-section">

					<div class="bpress-heading<?php echo $bpkb_post_class; ?>">
						<!-- Post icon -->
						<?php if ( $bpkb_show_post_icon ) { ?>
							<span aria-hidden="true" class="bpress-heading-icon <?php echo $bpkb_article->icon; ?>"></span>
						<?php } ?>

						<h3>
							<!-- Post permalink -->
							<a href="<?php echo get_the_permalink( $bpkb_article->ID ); ?>"><?php echo $bpkb_article->post_title; ?></a>
						</h3>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- End section -->
	
<?php endforeach; ?>
