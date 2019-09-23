<?php
/**
 * This template renders the Table of Contents
 *
 */

if ( ! basepress_show_table_of_content() ) {
	return;
}

?>

<div class="bpress-toc">
	<h2>
		<?php basepress_table_of_content_title(); ?>
	</h2>
	<div class="bpress-toc-list">
		<?php basepress_table_of_content(); ?>
	</div>
</div>

