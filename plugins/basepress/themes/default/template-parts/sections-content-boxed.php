<?php
/*
 *	This template lists all top sections with a boxed style
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();

?>
<div class="bpress-grid">

<?php
//We can iterate through the sections
foreach ( $bpkb_sections as $bpkb_section ) :
?>

	<div class="bpress-col bpress-col-<?php basepress_section_cols(); ?>">
		<div class="bpress-section-boxed">
			<a href="<?php echo $bpkb_section->permalink; ?>">

				<!-- Section Title icon -->
				<?php if ( $bpkb_section->image['image_url'] ) { ?>
					<div class="bpress-section-image">
						<img src="<?php echo $bpkb_section->image['image_url']; ?>" alt="<?php echo $bpkb_section->name; ?>">
					</div>
				<?php } else { ?>
					<span aria-hidden="true" class="bpress-section-icon <?php echo $bpkb_section->icon; ?>"></span>
				<?php } ?>

				<!-- Section Title -->
				<h2 class="bpress-section-title"><?php echo $bpkb_section->name; ?></h2>

				<!-- Section Description -->
				<?php if ( $bpkb_section->description ) { ?>
				<p><?php echo $bpkb_section->description; ?></p>
				<?php } ?>

				<!-- Section View All -->
				<span class="bpress-viewall">
					<?php printf( _n( 'View %d article', 'View all %d articles', $bpkb_section->posts_count, 'basepress' ), $bpkb_section->posts_count ); ?>
				</span>

			</a>

		</div><!-- End Section -->
	</div><!-- End col -->

<?php endforeach; ?>

</div><!-- End grid -->
