<?php
/**
 * This is the class that adds the sections page on admin
 */

 // Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Sections_Page' ) ) {

	class Basepress_Sections_Page {

		/**
		 * basepress_sections_page constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'add_sections_page' ) );

			add_action( 'init', array( $this, 'add_ajax_callbacks' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		}



		/**
		 * Adds the Sections page on admin
		 *
		 * @since 1.0.0
		 */
		public function add_sections_page() {

			//Add the sub menu 'Sections' on knowledgebase post type
			add_submenu_page( 'edit.php?post_type=knowledgebase', 'BasePress ' . __( 'Sections', 'basepress' ), __( 'Sections', 'basepress' ), 'manage_categories', 'basepress_sections', array( $this, 'display_screen' ) );

		}



		/**
		 * Defines the ajax calls for this screen
		 *
		 * @since 1.0.0
		 */
		public function add_ajax_callbacks() {
			add_action( 'wp_ajax_basepress_get_section_data', array( $this, 'basepress_get_section_data' ) );
			add_action( 'wp_ajax_basepress_new_section', array( $this, 'basepress_new_section' ) );
			add_action( 'wp_ajax_basepress_delete_section', array( $this, 'basepress_delete_section' ) );
			add_action( 'wp_ajax_basepress_update_section', array( $this, 'basepress_update_section' ) );
			add_action( 'wp_ajax_basepress_update_section_order', array( $this, 'basepress_update_section_order' ) );
			add_action( 'wp_ajax_basepress_get_section_list', array( $this, 'basepress_get_section_list' ) );
		}



		/**
		 * Enqueues admin scripts for this screen
		 *
		 * @since 1.0.0
		 *
		 * @param $hook
		 */
		public function enqueue_admin_scripts( $hook ) {
			//Enqueue admin script for Sections screen only
			if ( 'knowledgebase_page_basepress_sections' == $hook ) {
				wp_enqueue_media();
				wp_register_script( 'basepress-sections-js', plugins_url( 'js/basepress-sections.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'basepress-sections-js' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}



		/**
			* Generates the page content
			*
			* @since 1.0.0
			*/
		public function display_screen() {
			?>
			<div class="wrap">
				<h1><?php echo __( 'Knowledge Base Sections', 'basepress' ); ?></h1>

				<div id="col-container" class="wp-clearfix">

					<!-- Add New Section -->
					
					<div id="col-left">
						<div class="col-wrap">
							<div class="form-wrap">
								<h2><?php echo __( 'Add New Section', 'basepress' ); ?></h2>

								<form id="new-basepress-section">

									<div class="form-field form-required term-name-wrap">
										<label for="section-name"><?php echo __( 'Name', 'basepress' ); ?></label>
										<input name="section-name" id="section-name" type="text" value="" size="40" aria-required="true">
										<p class="description"><?php echo __( 'The name is how it appears on your site.', 'basepress' ); ?></p>
									</div>

									<div class="form-field term-slug-wrap">
										<label for="section-slug">Slug</label>
										<input name="slug" id="section-slug" type="text" value="" size="40">
										<p class="description"><?php echo __( 'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'basepress' ); ?></p>
									</div>

									<div class="form-field term-description-wrap">
										<label for="section-description"><?php echo __( 'Description', 'basepress' ); ?></label>
										<textarea name="description" id="section-description" rows="5" cols="40"></textarea>
										<p class="description"><?php echo __( 'The description is not prominent by default; however, some themes may show it.', 'basepress' ); ?></p>
									</div>

									<div class="form-field term-image-wrap">
										<label for="section-icon-class"><?php echo __( 'Icon', 'basepress' ); ?></label>
										<div id="section-icon"><span aria-hidden="true"></span></div>
										<input id='section-icon-class' value='' type='text' name='section-icon-class' hidden="hidden">
										<p class="description"><?php echo __( 'Choose an icon for this section.', 'basepress' ); ?></p>
									</div>

									<p id="section-buttons">
										<a id="select-icon" class="button button-primary"><?php echo __( 'Select icon', 'basepress' ); ?></a>
										<a id="remove-icon" class="button button-primary"><?php echo __( 'Remove icon', 'basepress' ); ?></a>
									</p>

									<div class="form-field term-image-wrap">
										<label for="section-image-url"><?php echo __( 'Section image', 'basepress' ); ?></label>
										<div id="section-image"><img src=""></div>
										<input id='section-image-url' value='' type='text' name='section-image-url' hidden="hidden">
										<input id='section-image-width' value='' type='text' name='section-image-width' hidden="hidden">
										<input id='section-image-height' value='' type='text' name='section-image-height' hidden="hidden">
										<p class="description"><?php echo __( 'Choose an image for this section.', 'basepress' ); ?></p>

										<p id="section-buttons">
											<a id="select-image" class="button button-primary"><?php echo __( 'Select image', 'basepress' ); ?></a>
											<a id="remove-image" class="button button-primary"><?php echo __( 'Remove image', 'basepress' ); ?></a>
										</p>
									</div>

									<p class="submit">
										<a id="add-section" class="button button-primary">
										<?php echo __( 'Add New Section', 'basepress' ); ?>
										</a>
									</p>
								</form>
							</div>
						</div>
					</div>


					<!-- Sections Table -->

					<div id="col-right">
						<div class="col-wrap">
							<div id="basepress-sections">

								<div class="table-nav top">
									<div class="alignleft">

										<select id="product-select" class="alignleft">
											<option value="0" selected="selected" disabled="disabled"><?php echo __( 'Select Knowledge Base', 'basepress' ); ?></option>
											<?php $this->get_products_list(); ?>
										</select>
										<div id="section-breadcrumb" class="alignleft">
											<?php echo __( 'Section:', 'basepress' ); ?>
											<ul>
												<li data-section="0"><a href="#" ><?php echo __( 'Root', 'basepress' ); ?></a></li>
											</ul>
										</div>
									</div>

									<div class="alignright">
										<a id="save-section-order" class="button button-primary"><?php echo __( 'Save Order', 'basepress' ); ?></a>
									</div>

								</div>

								<br class="clear">

								<div class="sections-header">
									<div class="section-icon-th"><?php echo __( 'Icon', 'basepress' ); ?></div>
									<div class="section-image-th"><?php echo __( 'Image', 'basepress' ); ?></div>
									<div class="section-name"><?php echo __( 'Name', 'basepress' ); ?></div>
									<div class="section-description"><?php echo __( 'Description', 'basepress' ); ?></div>
									<div class="section-slug"><?php echo __( 'Slug', 'basepress' ); ?></div>
									<div class="section-count"><?php echo __( 'Count', 'basepress' ); ?></div>
									<div class="section-actions"></div>
								</div>

								<div id="sections-table">
									<ul>

									</ul>
								</div>

							</div>
						</div>
					</div>
				</div>


				<!-- Edit Section -->

				<?php
				//If the page url contains the section id get parent and product ids
				$section_id = '';
				$product_id = '';
				$parent_id = '';
				$parent_name = '';

				if ( isset( $_GET['section'] ) && '' != $_GET['section'] ) {
					$section_id = $_GET['section'];
					$section = get_term_by( 'id', $section_id, 'knowledgebase_cat' );
					$parent = get_term_by( 'id', $section->parent, 'knowledgebase_cat' );
					$term = $section;

					while ( 0 != $term->parent ) {
						$term_id = $term->parent;
						$term = get_term_by( 'id', $term_id, 'knowledgebase_cat' );
					}
					$product_id = $term->term_id;
					$parent_id = $parent->term_id;
					$parent_name = $parent->name;
				}
				?>
				<div id="edit-section-wrap">
					<form id="edit-basepress-section">
						<input id="section-id" value="<?php echo $section_id; ?>" name="section-id" hidden="hidden">
						<input id="product-id" value="<?php echo $product_id; ?>" name="product-id" hidden="hidden">
						<input id="parent-id" value="<?php echo $parent_id; ?>" name="parent-id" hidden="hidden">
						<input id="parent-name" value="<?php echo $parent_name; ?>" name="parent-name" hidden="hidden">
						<h2><?php echo __( 'Edit Section', 'basepress' ); ?><span id="edit-section-name"></span></h2>
						
						<div class="form-field form-required term-name-wrap">
							<label for="section-name"><?php echo __( 'Name', 'basepress' ); ?></label>
							<input name="section-name" id="section-name-edit" type="text" value="" size="40" aria-required="true">
						</div>
						<br>

						<div class="form-field term-slug-wrap">
							<label for="section-slug">Slug</label>
							<input name="slug" id="section-slug-edit" type="text" value="" size="40">
						</div>
						<br>

						<div class="form-field term-description-wrap">
							<label for="section-description"><?php echo __( 'Description', 'basepress' ); ?></label>
							<textarea name="description" id="section-description-edit" rows="5" cols="40"></textarea>
						</div>
						<br>

						<div class="form-field term-image-wrap">
							<label for="section-icon-class-edit"><?php echo __( 'Icon', 'basepress' ); ?></label>
							<div id="section-icon-edit"><span aria-hidden="true"></span></div>
							<input id='section-icon-class-edit' value='' type='text' name='section-icon-class' hidden="hidden">
						</div>

						<p id="section-buttons">
							<a id="select-icon-edit" class="button button-primary"><?php echo __( 'Select icon', 'basepress' ); ?></a>
							<a id="remove-icon-edit" class="button button-primary"><?php echo __( 'Remove icon', 'basepress' ); ?></a>
						</p>

						<div class="form-field term-image-wrap">
							<label for="section-image-url-edit"><?php echo __( 'Section image', 'basepress' ); ?></label>
							<div id="section-image-edit"><img src=""></div>
							<input id='section-image-url-edit' value='' type='text' name='section-image-url' hidden="hidden">
							<input id='section-image-width-edit' value='' type='text' name='section-image-width' hidden="hidden">
							<input id='section-image-height-edit' value='' type='text' name='section-image-height' hidden="hidden">
							<p class="description"><?php echo __( 'Choose an image for this section.', 'basepress' ); ?></p>
							
							<p id="section-buttons">
								<a id="select-image-edit" class="button button-primary"><?php echo __( 'Select image', 'basepress' ); ?></a>
								<a id="remove-image-edit" class="button button-primary"><?php echo __( 'Remove image', 'basepress' ); ?></a>
							</p>
						</div>

						<div>
							<label for="section-parent-edit"><?php echo __( 'Parent', 'basepress' ); ?></label>
							<div id="section-parent-edit"></div>
						</div>

						<div id="default-category-edit">
							<a href="#" target="_blank">View More</a>
						</div>

						<p class="submit">
							<a id="save-change" class="button button-primary">
							<?php echo __( 'Save changes', 'basepress' ); ?>
							</a>
							<a id="cancel-change" class="button button-primary">
							<?php echo __( 'Cancel', 'basepress' ); ?>
							</a>
						</p>
					</form>
				</div>

				<!-- Icon Selector -->
				<div id="icon-selector-wrap">	
					<div id="icon-selector" class="wp-clearfix">
						<h1 id="icon-selector-header"><?php echo __( 'Select icon', 'basepress' ); ?></h1>
						<div id="icon-selector-body">
							<?php $this->get_icons();; ?>
						</div>
						<p>
							<a id="cancel-icon-select" class="button button-primary"><?php echo __( 'Cancel', 'basepress' ); ?></a>
						</p>
					</div>
				</div>


				<!-- Ajax Loader -->
				<div id="ajax-loader"></div>

			</div><!-- .wrap -->
		<?php
		}




		/**
		 * Generates the products list to show on product select menu
		 *
		 * @since 1.0.0
		 * @ipdated 1.7.6
		 */
		public function get_products_list() {
			global $basepress_utils;
			
			$active_product_id = $basepress_utils->get_active_product_id();

			//Get all product terms in knowledgebase_cat
			$args = array(
				'taxonomy'   => 'knowledgebase_cat',
				'hide_empty' => false,
				'parent'     => 0,
				'orderby'    => 'name',
				'order'      => 'ASC'
			);
			
			$product_terms = get_terms( $args );
			
			//Iterate over each product
			foreach( $product_terms as $product ){
				$selected = ( $active_product_id && $active_product_id == $product->term_id ) ? ' selected' : '';
				
				echo '<option value="' . $product->term_id . '"' . $selected . '>' . $product->name . '</option>';
			}
		}



		/**
		 * Generates the HTML row for the section
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8
		 *
		 * @param $section
		 */
		public function get_section_row( $section ) {
			global $basepress_utils;

			//Get the section icon
			$icon = get_term_meta( $section->term_id, 'icon', true );
			$form = $basepress_utils->icons_form;
			$extra_classes = $icon ? $form['extra-classes'] . ' ' : '';


			//Get the section image
			$image = get_term_meta( $section->term_id, 'image', true );
			$image_url = isset( $image['image_url'] ) ? $image['image_url'] : '';

			//Get the section position
			$pos = get_term_meta( $section->term_id, 'basepress_position', true );

			//If descriptions are long we add the dots after 180 characters
			$continue = strlen( $section->description ) > 180 ? '...' : '';

			$count = $section->count;

			?>
			<li class="section-row" data-section="<?php echo $section->term_id; ?>" data-name="<?php echo $section->name; ?>" data-pos="<?php echo $pos; ?>">
				<div class="section-icon"><div><span aria-hidden="true" class="<?php echo $extra_classes . $icon; ?>"></span></div></div>
				<div class="section-image"><div style="background-image:url(<?php echo $image_url; ?>)"></div></div>
				<div class="section-name"><div><?php echo $section->name; ?></div></div>
				<div class="section-description"><div><?php echo substr( $section->description, 0, 180 ) . $continue; ?></div></div>
				<div class="section-slug"><div><?php echo $section->slug; ?></div></div>
				<div class="section-count"><?php echo $count; ?></div>
				<div class="section-actions">
					<span class="dashicons dashicons-edit"></span>
					<?php if ( 0 == $count ) { ?>
					<span class="dashicons dashicons-trash"></span>
					<?php } ?>
				</div>
			</li>
		<?php
		}


		/*
		*	Ajax call functions for Sections page
		*
		*/


		/**
		 * Generates the section list to show on sections table
		 *
		 * @since 1.0.0
		 */
		public function basepress_get_section_list() {
			global $wp_version;
			$product = $_POST['product'];
			$section = $_POST['section'];
			$section_parent = 0 != $section ? $section : $product;

			//Get all sections terms in knowledgebase_cat
			$args = array(
				'taxonomy'   => 'knowledgebase_cat',
				'hide_empty' => false,
				'meta_key'   => 'basepress_position',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
				'pad_counts' => true,
			);

			if ( $wp_version <= 4.5 ) {
				$section_terms = get_terms( 'knowledgebase_cat', $args );
			} else {
				$section_terms = get_terms( $args );
			}

			//Filter out only the sections for current product
			$section_terms = wp_list_filter( $section_terms, array( 'parent' => $section_parent ) );

			ob_start();
			//Iterate over each section
			foreach ( $section_terms as $section ) {

				//Get the HTML row for this section
				$this->get_section_row( $section );

			}
			$section_rows = ob_get_clean();

			//Return HTML row
			header( 'Content-type: application/json' );
			echo json_encode( array(
				'error' => false,
				'data' => $section_rows,
			));

			wp_die();
		}




		/**
		 * Adds a New Section
		 *
		 * @since 1.0.0
		 */
		public function basepress_new_section() {
			$form = array();
			$product = $_POST['product'];
			$section = $_POST['section'];
			$parent = 0 != $section ? $section : $product;

			//Get the section count. This value represents the position of the new section
			$term_position = wp_count_terms( 'knowledgebase_cat', array(
				'hide_empty' => false,
				'parent'     => $parent,
			) );

			//WP returns an empty array if there are no terms. In that case we set it to 0
			if ( ! is_int( $term_position ) ) {
				$term_position = 0;
			}

			//Extract the form data we received via ajax
			parse_str( $_POST['form'], $form );

			//Insert new term
			$term = wp_insert_term(
				$form['section-name'],
				'knowledgebase_cat',
				array(
					'description' => $form['description'],
					'slug'        => $form['slug'],
					'parent'      => $parent,
				)
			);

			//If there was a problem we return a wp_error
			if ( is_wp_error( $term ) ) {
				header( 'Content-type: application/json' );
				echo json_encode( array(
					'error' => true,
					'data' => $term->get_error_message(),
				));
				wp_die();
			}

			//Add section icon
			update_term_meta(
				$term['term_id'],
				'icon',
				$form['section-icon-class']
			);

			//Add section image
			update_term_meta(
				$term['term_id'],
				'image',
				array(
					'image_url'    => $form['section-image-url'],
					'image_width'  => $form['section-image-width'],
					'image_height' => $form['section-image-height'],
				)
			);

			//Add section position
			update_term_meta(
				$term['term_id'],
				'basepress_position',
				$term_position
			);

			//Get new term data and generate HTML row to add to section list table
			$section = get_term( $term['term_id'] );
			ob_start();
			$this->get_section_row( $section );
			$section_row = ob_get_clean();

			//Return HTML row
			header( 'Content-type: application/json' );
			echo json_encode( array(
				'error' => false,
				'data' => $section_row,
				'pos' => $term_position,
			));

			wp_die();
		}




		/**
		 * Deletes a section
		 *
		 * @since 1.0.0
		 */
		public function basepress_delete_section() {
			//Get section id from ajax POST
			$section_id = $_POST['section'];

			//Get the term for this section
			$term = get_term( $section_id, 'knowledgebase_cat' );

			//If there was a problem exit
			if ( is_wp_error( $term ) || null == $term ) {
				echo 'The section cannot be deleted';
				wp_die();
			}

			//If the term has some articles exit
			if ( $term->count > 0 ) {
				echo 'This section has some articles. You must delete the articles first.';
				wp_die();
			} else {
				//If we had no problems we can delete the section term
				$delete = wp_delete_term(
					$section_id,
					'knowledgebase_cat'
				);

				echo $delete;
				wp_die();
			}
		}



		/**
		 * Updates a section's data
		 *
		 * @since 1.0.0
		 */
		public function basepress_update_section() {
			$form = array();

			//Extract the form data we received via ajax
			parse_str( $_POST['form'], $form );

			//Update section data
			$term = wp_update_term(
				$form['section-id'],
				'knowledgebase_cat',
				array(
					'name'        => $form['section-name'],
					'description' => $form['description'],
					'slug'        => $form['slug'],
					'parent'      => $form['section-parent'],
				)
			);

			//If there was a problem exit
			if ( is_wp_error( $term ) ) {
				header( 'Content-type: application/json' );
				echo json_encode( array(
					'error' => true,
					'data' => $term->get_error_message(),
				));
				wp_die();
			}

			//update section icon
			update_term_meta(
				$term['term_id'],
				'icon',
				$form['section-icon-class']
			);

			//update section image @since 1.2.0
			update_term_meta(
				$term['term_id'],
				'image',
				array(
					'image_url'    => $form['section-image-url'],
					'image_width'  => $form['section-image-width'],
					'image_height' => $form['section-image-height'],
				)
			);

			//if the term has not position add it
			$term_position = get_term_meta( $term['term_id'], 'basepress_position', true );
			if ( ! $term_position ) {
				update_term_meta(
					$term['term_id'],
					'basepress_position',
					0
				);
			}

			//Get the new data from DB
			$term = get_term( $term['term_id'], 'knowledgebase_cat' );

			//Get icon
			$term_icon = get_term_meta( $term->term_id, 'icon', true );

			//Get image @since 1.2.0
			$term_image = get_term_meta( $term->term_id, 'image', true );

			//Get position
			$term_position = get_term_meta( $term->term_id, 'basepress_position', true );

			/**
			 * Fires after the Section and its metadata has been saved
			 */
			do_action( 'basepress_section_updated', $term, $term_icon, $term_image, $term_position );

			$continue = strlen( $term->description ) > 180 ? '...' : '';

			//Return the new section
			//We return the data from DB to make sure everything worked
			header( 'Content-type: application/json' );
			echo json_encode( array(
				'id'          => $term->term_id,
				'name'        => $term->name,
				'slug'        => $term->slug,
				'description' => substr( $term->description, 0, 180 ) . $continue,
				'icon'        => $term_icon,
				'image'       => $term_image, //@since 1.2.0
				'parent'      => $term->parent,
			) );

			wp_die();
		}




		/**
		 * Gets a Section's data
		 *
		 * @since 1.0.0
		 */
		public function basepress_get_section_data() {
			//Get section id from ajax POST
			$section_id = $_POST['section'];

			//Get the term for this section
			$term = get_term( $section_id, 'knowledgebase_cat' );

			//Get section parents menu
			$parents = wp_dropdown_categories(array(
				'taxonomy'     => 'knowledgebase_cat',
				'name'         => 'section-parent',
				'orderby'      => 'name',
				'selected'     => $term->parent,
				'exclude'      => $term->term_id,
				'show_count'   => false,
				'hide_empty'   => false,
				'hierarchical' => true,
				'echo'         => 0,
			) );

			/**
			 * This is filter allows to change the parents dropdown list on section edit
			 */
			$parents = apply_filters( 'basepress_section_parent_dropdown', $parents, $section_id );

			//Get  the icon for this section
			$term_icon = get_term_meta( $term->term_id, 'icon', true );

			//Get the image for this section @since 1.2.0
			$term_image = get_term_meta( $term->term_id, 'image', true );

			//If there is no image construct an empty image object
			if ( empty( $term_image ) ) {
				$term_image = array(
					(object) array(
						'image_url'    => '',
						'image_width'  => '',
						'image_height' => '',
					),
				);
			}

			//Return section data
			header( 'Content-type: application/json' );
			echo json_encode(
				array(
					'section'     => array(
						'id'          => $term->term_id,
						'name'        => $term->name,
						'slug'        => $term->slug,
						'description' => $term->description,
						'icon'        => $term_icon,
						'image'       => $term_image,
						'parent'      => $term->parent,
						'default_edit_link' => get_edit_term_link( $term->term_id, 'knowledgebase_cat' ),
					),
					'parents' => $parents,
				)
			);

			wp_die();
		}



		/**
		* Updates the sections order
		*
		* @since 1.0.0
		*/
		public function basepress_update_section_order() {
			//Get the array of sections order
			$order = $_POST['order'];

			//Update the order of every section
			foreach ( $order as $position => $term_id ) {
				update_term_meta( $term_id, 'basepress_position', $position );
			}

			/**
			 * This filter can be used to do further processing after section order has been saved
			 */
			do_action( 'basepress_section_order_updated', $order );

			wp_die();
		}


		/**
		 * Retrieves the icons from the theme
		 *
		 * @since 1.0.0
		 */
		public function get_icons() {
			global $basepress_utils;

			$icons = $basepress_utils->icons;

			if ( ! empty( $icons ) ) {
				foreach ( $icons->sections->icon as $icon ) {
					echo '<div class="basepress-icon" data-icon="' . $icon . '">';
					echo '<span aria-hidden="true" class="' . $icon . '"></span>';
					echo '</div>';
				}
			}
		}

	} //End Class

	new Basepress_Sections_Page;

}