<?php
/**
 * This is the class that runs the installation wizard
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Wizard' ) ){

	class Basepress_Wizard{

		private $product_position = 0;
		private $section_position = 0;
		private $article_order = 0;

		/**
		 * Basepress_Wizard constructor.
		 *
		 * @since 2.1.0
		 */
		public function __construct(){
			add_action( 'admin_menu', array( $this, 'add_wizard_page' ), 20 );

			//Enqueue admin scripts and styles
			if ( get_option( 'basepress_run_wizard' ) ){

				add_action( 'load-admin_page_basepress_wizard', array( $this, 'enqueue_admin_scripts' ) );

				add_action( 'admin_notices', array( $this, 'admin_notices' ) );

				add_action( 'admin_init', array( $this, 'add_ajax_callbacks' ) );
			}
			else{
				add_action( 'load-admin_page_basepress_wizard', array( $this, 'redirect_to_settings' ) );
			}

		}


		/**
		 * Redirect to settings page if Wizard page is accessed when settings are already setup
		 *
		 * @since 2.2.0
		 */
		public function redirect_to_settings(){
			wp_redirect( admin_url( '/admin.php?page=basepress' ), 301 );
			exit;
		}

		/**
		 * Adds the Wizard page
		 *
		 * @since 2.1.0
		 */
		public function add_wizard_page() {
			add_submenu_page( null, null, __( 'Setup Wizard', 'basepress' ), 'manage_options', 'basepress_wizard', array( $this, 'display_wizard_screen' ) );
		}


		/**
		 * Enqueues scripts for Wizard page
		 *
		 * @since 2.1.0
		 *
		 * @param $screen
		 */
		public function enqueue_admin_scripts(){
			wp_enqueue_script( 'basepress-wizard-js', plugins_url( 'js/basepress-wizard.js', __FILE__ ), array(), BASEPRESS_VER );
			wp_enqueue_style( 'basepress-wizard-css', plugins_url( 'css/wizard.css', __FILE__ ), array(), BASEPRESS_VER );
		}


		/**
		 * Adds Ajax callbacks
		 *
		 * @since 2.1.0
		 */
		public function add_ajax_callbacks(){
			add_action( 'wp_ajax_basepress_wizard_proceed', array( $this, 'basepress_wizard_proceed' ) );
		}


		/**
		 * Adds admin notices if Wizard needs to be run
		 *
		 * @since 2.1.0
		 */
		public function admin_notices(){
			$current_screen = get_current_screen();
			$excluded_screens = apply_filters( 'basepress_kb_wizard_excluded_screens', array(
				'toplevel_page_basepress',
				'admin_page_basepress_wizard',
				'basepress_page_basepress_manual'
			) );

			if( in_array( $current_screen->id, $excluded_screens ) ){
				return;
			}

			?>
			<div class="notice notice-error">
				<p>
					<?php _e( 'Your Knowledge Base is not set up yet. Use the Setup Wizard or go to the settings page to get started manually.', 'basepress' ); ?>
					<a class="button button-primary" href="<?php menu_page_url( 'basepress_wizard' ); ?>"><?php _e( 'Start Wizard', 'basepress' ); ?></a>
					<a class="button" href="<?php menu_page_url( 'basepress' ); ?>"><?php _e( 'Settings Page', 'basepress' ); ?></a>
				</p>
			</div>
			<?php
		}


		/**
		 * Displays Wizard screen
		 *
		 * @since 2.1.0
		 */
		public function display_wizard_screen(){
			?>
			<div class="basepress-wrap" style="max-width:70em;margin:50px auto">
			<img id="basepress-header" src="<?php echo BASEPRESS_URI . '/assets/img/wizard-header.png'; ?>">

			<div class="basepress-wizard-box">
				<h1><?php _e( 'Knowledge Base Setup Wizard', 'basepress'); ?></h1>

				<ul id="basepress-wizard-steps">
					<li class="active"><?php _e( 'Title & Slug', 'basepress'); ?></li>
					<li><?php _e( 'Menu Item', 'basepress'); ?></li>
					<li><?php _e( 'Theme', 'basepress'); ?></li>
					<li><?php _e( 'Misc', 'basepress'); ?></li>
					<li><?php _e( 'Finish', 'basepress'); ?></li>
				</ul>

				<form id="basepress-wizard-form">
					<div class="basepress-wizard-step active">
						<div class="basepress-wizard-item">
							<label for="title"><?php _e( 'Knowledge Base Title', 'basepress' ); ?></label><br>
							<input type="text" name="title" id="title" value="Knowledge Base"><br>
							<p class="wizard-description"><?php _e( 'This is the title used in the Knowledge base entry page.<br>Examples: Knowledge Base, Help, Support etc.', 'basepress' ); ?></p>
						</div>
						<div class="basepress-wizard-item">
							<label for="slug"><?php _e( 'Knowledge Base Slug', 'basepress' ); ?></label><br>
							<input type="text" name="slug" id="slug" value="knowledge-base">
							<p class="wizard-description"><?php _e( 'This is the slug used in your site URL for your Knowledge base pages.<br>Accepted characters are small letters, numbers and dashes.<br>Example: www.your-domain.com/knowledge-base/', 'basepress' ); ?></p>
						</div>
					</div>

					<div class="basepress-wizard-step">
						<div class="basepress-wizard-item">
							<label for="menu"><?php _e( 'Knowledge Base Menu Item', 'basepress' ); ?></label><br>
							<select name="menu" id="menu">
								<option value="0"><?php _e( 'Select Menu', 'basepress' ); ?></option>
								<?php $this->get_menu_options(); ?>
							</select>
							<p class="wizard-description"><?php _e( 'Choose the menu where to add the Knowledge Base.<br>This will give users access to the knowledge base.<br>You can change it at any time from WordPress->Appearance->Menus.', 'basepress' ); ?></p>
						</div>
					</div>

					<div class="basepress-wizard-step">
						<div class="basepress-wizard-item">
							<label for="theme"><?php _e( 'Knowledge Base Theme', 'basepress' ); ?></label><br>
							<select name="theme" id="theme">
								<?php $this->get_theme_options(); ?>
							</select>
							<p class="wizard-description"><?php _e( 'Choose one of the available themes to style your content.<br>You can change the theme at any time from BasePress aspect settings.', 'basepress' ); ?></p>
						</div>
					</div>

					<div class="basepress-wizard-step">
						<div class="basepress-wizard-item">
							<input type="checkbox" name="single_product" id="single-product" value="1">
							<label for="single-product"><?php _e( 'I need a single Knowledge Base', 'basepress' ); ?></label>
							<p class="wizard-description"><?php _e( 'When enabled the plugin will work as a standard knowledge base.<br>If you decide to build more than one Knowledge Base you can disable this option from BasePress general settings.', 'basepress' ); ?></p>
						</div>
						<div class="basepress-wizard-item">
							<input type="checkbox" name="import_demo" id="import-demo" value="1">
							<label for="import-demo"><?php _e( 'Import demo content', 'basepress' ); ?></label>
							<p class="wizard-description"><?php _e( 'If enabled the wizard will populate your Knowledge Base with some demo content.<br>This will help you to quickly understand how the knowledge base works.', 'basepress' ); ?></p>
						</div>
						<div class="basepress-wizard-item">
							<input type="checkbox" name="build_mode" id="build-mode" value="1">
							<label for="build-mode"><?php _e( 'Enable build mode', 'basepress' ); ?></label>
							<p class="wizard-description"><?php _e( 'When enabled only admin users would be able to access the knowledge base.<br>You can disable this option once the knowledge base is ready for the public from BasePress general settings.', 'basepress' ); ?></p>
						</div>
					</div>

					<div class="basepress-wizard-step">
						<div class="basepress-wizard-item">
							<!-- Ajax Loader -->
							<div id="ajax-loader"></div>
							<div id="wizard-response"></div>
						</div>
					</div>
				</form>

				<button id="wizard-prev" class="button" disabled><?php _e( 'Previous', 'basepress' ); ?></button>
				<button id="wizard-next" class="button button-primary"><?php _e( 'Next', 'basepress' ); ?></button>
				<button id="wizard-proceed" class="button button-primary"><?php _e( 'Proceed', 'basepress' ); ?></button>

			</div>
			<?php
		}


		/**
		 * Gets list of available menus
		 *
		 * @since 2.1.0
		 */
		private function get_menu_options(){
			//The function gets only locations that have a menu assigned
			$locations = get_nav_menu_locations();
			$registered_locations = get_registered_nav_menus();
			foreach ( $locations as $location => $menu_id ) {
				$object = wp_get_nav_menu_object( $menu_id );
				echo '<option value="'. $menu_id . '">' . $object->name . ' (' . $registered_locations[$location] . ')</option>';
			}
		}


		/**
		 * Get list of available BasePress themes
		 *
		 * @since 2.1.0
		 */
		private function get_theme_options(){
			$unique_themes = array();
			$base_theme_dir = get_stylesheet_directory() . '/basepress/';
			$plugin_theme_dir = BASEPRESS_DIR . 'themes/';

			$base_themes = array();

			if ( file_exists( $base_theme_dir ) ) {
				$base_themes = glob( $base_theme_dir . '*' );
			}
			$plugin_themes = glob( $plugin_theme_dir . '*' );
			$themes = array_merge( $plugin_themes, $base_themes );

			echo '<option disabled>' . __( 'Select Theme', 'basepress' ) . '</option>';

			foreach ( $themes as $theme ) {
				$theme_dir = basename( $theme );

				if ( ! in_array( $theme_dir, $unique_themes ) ) {
					$theme_css = file_get_contents( $theme . '/css/style.css', false, null, 0, 200 );
					preg_match( '/Theme Name:\s*(.+)/i', $theme_css, $theme_name );

					echo '<option value="' . $theme_dir . '">' . $theme_name[1] . '</option>';
					$unique_themes[] = $theme_dir;
				}
			}
		}


		/**
		 * Runs the Wizard process
		 *
		 * @since 2.1.0
		 */
		public function basepress_wizard_proceed(){
			global $wpdb;

			$settings = array();
			parse_str( $_POST['settings'] , $settings );
			$shortcode = '[basepress]';
			$block_placeholder = '<!-- wp:basepress-kb/products-block /-->';
			$posts_with_shortcode = array();

			//Get all options
			$title = isset( $settings['title'] ) ? sanitize_text_field( $settings['title'] ) : 'Knowledge Base';
			$slug = isset( $settings['slug'] ) ? strtolower( sanitize_text_field( $settings['slug'] ) ) : 'knowledge-base';
			$menu_id = isset( $settings['menu'] ) ? $settings['menu'] : false;
			$theme = isset( $settings['theme'] ) ? $settings['theme'] : 'default';
			$single_product = isset( $settings['single_product'] ) ? 1 : 0;
			$import_demo = isset( $settings['import_demo'] ) ? 1 : 0;
			$build_mode = isset( $settings['build_mode'] ) ? 1 : 0;
			$post_content = $shortcode;

			//Set content to block if new editor is enabled for pages
			if( function_exists('use_block_editor_for_post_type') && use_block_editor_for_post_type( 'page' ) ){
				$post_content = $block_placeholder;
			}

			//Create the entry page
			$post_id = wp_insert_post(
				array(
					'ID'           => 0,
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $post_content,
					'post_status'  => 'publish',
					'post_type'    => 'page',
				),
				true
			);

			$entry_page = $post_id && ! is_wp_error( $post_id ) ? $post_id : false;

			if( $entry_page ){
				//Create menu item if the user selected a menu
				if( $menu_id ){

					$menu_item = wp_update_nav_menu_item(
						$menu_id, 0, array(
							'menu-item-title'     => $title,
							'menu-item-type'      => 'post_type',
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $entry_page,
							'menu-item-status'    => 'publish',
						)
					);
				}

				//Load default options
				$options = include BASEPRESS_DIR . 'options.php';

				//Prepare Wizard options
				$wizard_options = array(
					'kb_name'     => $title,
					'theme_style' => $theme,
					'entry_page'  => $entry_page,
				);

				//Enable Build Mode
				if( $build_mode ){
					$wizard_options['build_mode'] = 1;
				}

				//Enable Single product mode
				if( $single_product ){
					$wizard_options['single_product_mode'] = 1;
				}

				//Merge default options with wizard ones
				$options = array_merge( $options, $wizard_options );

				//Save the new options
				update_site_option( 'basepress_settings', $options );

				//Delete any old menu items used for the KB
				// Search for any page that contains the shortcode or the block
				$posts_with_shortcode = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT ID, post_title FROM $wpdb->posts WHERE post_type='page' AND ( post_content LIKE %s OR post_content LIKE %s ) AND ID <> {$entry_page};",
						"%{$block_placeholder}%",
						"%{$shortcode}%"
					)
				);

				if( $import_demo ){
					$this->import_demo_content();
				}

				$this->add_widget(
					'basepress-sidebar',
					'basepress_sections_widget',
					array(
						'title' => 'Sections',
						'count' => 0,
						'order-by' => 'custom',
						'post-count' => 0
					)
				);

				$this->add_widget(
					'basepress-sidebar',
					'basepress_related_articles_widget',
					array(
						'title' => 'Related Articles',
						'count' => 0,
						'order-by' => 'custom',
						'include-children' => 0
					)
				);

				//Flush rewrite rules for the new setup
				add_action(	'shutdown', function(){
					flush_rewrite_rules();
				});

				//Disable the Wizard
				delete_option( 'basepress_run_wizard' );
			}

			//Generate the Wizard response
			$response = $this->get_response( $entry_page, $menu_item, $posts_with_shortcode );

			wp_send_json( array(
				'error' => false,
				'response' => $response
			) );
		}


		/**
		 * Add widget to KB sidebar
		 *
		 * @since 2.1.0
		 *
		 * @param $sidebar
		 * @param $name
		 * @param array $args
		 */
		function add_widget( $sidebar, $name, $args = array() ) {
			if ( ! $sidebars = get_option( 'sidebars_widgets' ) ){
				$sidebars = array();
			}

			// Create the sidebar if it doesn't exist.
			if ( ! isset( $sidebars[ $sidebar ] ) ){
				$sidebars[ $sidebar ] = array();
			}

			// Check for existing saved widgets.
			if ( $installed_widgets = get_option( "widget_$name" ) ) {
				//If this widget is already in the sidebar return
				if( count( $installed_widgets ) > 1 ){
					return;
				}
			} else {
				// None existing, start fresh.
				$installed_widgets = array( '_multiwidget' => 1 );
			}

			// Add our settings to the stack.
			$installed_widgets[2] = $args;
			// Add our widget!
			$sidebars[ $sidebar ][] = "$name-2";

			update_option( 'sidebars_widgets', $sidebars );
			update_option( "widget_$name", $installed_widgets );
		}


		/**
		 * Generates the response for the Wizard process
		 *
		 * @since 2.1.0
		 *
		 * @param $post_id
		 * @param $menu_item
		 * @param $posts_with_shortcode
		 * @return string
		 */
		private function get_response( $post_id, $menu_item = null, $posts_with_shortcode ){
			$response = '';

			if( is_wp_error( $post_id ) || is_wp_error( $menu_item ) ){
				$response .= '<h2 class="basepress-wizard-failed">';
				$response .= __( 'We encountered some problems while settings up the Knowledge Base!<br>Please follow the instructions in the manual to set it up manually.', 'basepress' );
				$response .= '</h2>';

				if( is_wp_error( $post_id ) ){
					$response .= '<p class="basepress-wizard-notice">';
					$response .= __( 'Error: Unable to create a new page with the Knowledge Base shortcode/block and relative menu item.', 'basepress' );
					$response .= '</p>';
				}
				elseif( is_wp_error( $menu_item ) ){
					$response .= '<p class="basepress-wizard-notice">';
					$response .= __( 'Error: Unable to create a new menu item for your Knowledge Base.', 'basepress' );
					$response .= '</p>';
				}
			}
			else{
				$kb_link = get_permalink( $post_id );
				$response .= '<h2 class="basepress-wizard-success">';
				$response .= __( 'Your Knowledge Base is ready for action!<br>If you need to change any of the settings please got to BasePress settings page.', 'basepress');
				if( $kb_link ){
					$response .= '<p><a class="basepress-wizard-view-kb" href="' . $kb_link .'" target="_blank">' . __( 'View Knowledge Base', 'basepress' ) . '</a></p>';
				}
				$response .= '</h2>';

				if( null == $menu_item ){
					$response .= '<p class="basepress-wizard-notice">';
					$response .= __( 'You didn\'t select any menu where to add your Knowledge Base. Remember to add one when ready!', 'basepress' );
					$response .= '</p>';
				}
			}

			if( ! empty( $posts_with_shortcode ) ){
				$response .= $this->shortcode_found_notices( $posts_with_shortcode );
			}

			return $response;
		}


		/**
		 * Import Demo Content
		 *
		 * @since 2.1.0
		 *
		 * @return array|mixed
		 */
		private function import_demo_content(){
			if( ! file_exists( BASEPRESS_DIR . '/admin/demo-content.xml' ) ){
				return array( 'error', 0 );
			}

			$demo_xml = simplexml_load_file( BASEPRESS_DIR . '/admin/demo-content.xml' );
			$products = array();

			if( false === $demo_xml ){
				return array( 'error', 1 );
			}

			foreach( $demo_xml->products->product as $product ){
				$product_id = $this->add_new_product( $product );
				if( is_array( $product_id ) ){
					return $product_id;
				}
			}

			foreach( $demo_xml->sections->section as $section ){
				$this->add_new_section( $product_id, $section );
			}

			foreach( $demo_xml->posts->post as $article ){
				$this->add_new_article( $article );
			}
		}


		/**
		 * Add new demo product
		 *
		 * @since 2.1.0
		 *
		 * @param $product
		 * @return array|mixed
		 */
		private function add_new_product( $product ){
			//Insert new term
			$term = wp_insert_term(
				(string)$product->name,
				'knowledgebase_cat',
				array(
					'description' => (string)$product->description,
					'parent'      => 0,
				)
			);

			if ( is_wp_error( $term ) ){
				if( isset( $term->error_data['term_exists'] ) ){
					$term = array( 'term_id' => $term->error_data['term_exists'] );
				}else{
					return array( 'error' => 3 );
				}
			}

			//Add product image
			update_term_meta(
				$term['term_id'],
				'image',
				array(
					'image_url'    => BASEPRESS_URI . '/assets/img/image-placeholder.png',
					'image_width'  => 400,
					'image_height' => 400,
				)
			);

			//Add product visibility
			update_term_meta(
				$term['term_id'],
				'visibility',
				1
			);

			//Add product position
			update_term_meta(
				$term['term_id'],
				'basepress_position',
				$this->product_position++
			);

			//Add product sections style
			update_term_meta(
				$term['term_id'],
				'sections_style',
				array(
					'sections'     => 'list',
					'sub_sections' => 'list',
				)
			);

			return $term['term_id'];
		}


		/**
		 * Add new demo section
		 *
		 * @since 2.1.0
		 *
		 * @param $product_id
		 * @param $section
		 * @return array
		 */
		private function add_new_section( $product_id, $section ){
			//Insert new term
			$term = wp_insert_term(
				(string)$section->name,
				'knowledgebase_cat',
				array(
					'description' => (string)$section->description,
					'parent'      => $product_id,
				)
			);

			if ( is_wp_error( $term ) ){
				if( isset( $term->error_data['term_exists'] ) ){
					$term = array( 'term_id' => $term->error_data['term_exists'] );
				}else{
					return array( 'error' => 4 );
				}
			}

			//Add section icon
			update_term_meta(
				$term['term_id'],
				'icon',
				(string)$section->termmeta->metadata[0]->meta_value
			);

			//Add section image
			update_term_meta(
				$term['term_id'],
				'image',
				array(
					'image_url'    => BASEPRESS_URI . '/assets/img/image-placeholder.png',
					'image_width'  => 400,
					'image_height' => 400,
				)
			);

			//Add section position
			update_term_meta(
				$term['term_id'],
				'basepress_position',
				$this->section_position++
			);
		}


		/**
		 * Add new demo article
		 *
		 * @since 2.1.0
		 *
		 * @param $article
		 */
		private function add_new_article( $article ){
			$section = get_term_by( 'slug', (string)$article->parent_section[0], 'knowledgebase_cat' );

			$post = wp_insert_post(
				array(
					'post_title'    => (string)$article->post_title,
					'post_content'  => (string)$article->post_content,
					'post_status'   => 'publish',
					'post_type'     => 'knowledgebase',
					'menu_order'    => $this->article_order++,
					'tax_input'     => array(
						'knowledgebase_cat' => $section->term_id,
					),
				)
			);

			//Default metadata
			$meta_data = array(
				'basepress_template_name' => 'two-columns-right',
				'basepress_votes'         => array( 'like' => 0, 'dislike' => 0 ),
				'basepress_votes_count'   => 0,
				'basepress_score'         => 0,
				'basepress_views'         => 0
			);

			if( $post && ! is_wp_error( $post ) ){
				foreach( $meta_data as $meta_key => $meta_value ){
					update_post_meta( $post, $meta_key, $meta_value );
				}
			}
		}

		/**
		 * Generates the notice for already existing KB pages
		 *
		 * @since 2.1.0
		 *
		 * @param $posts_with_shortcode
		 * @return string
		 */
		private function shortcode_found_notices( $posts_with_shortcode ){

			$response = '<p class="basepress-wizard-notice">' . __( 'We have found some pages that already contain the shortcode/block for your Knowledge Base.<br>We suggest to delete them as a new page was created by the Wizard:', 'basepress' ) . '</p>';
			$response .= '<ul>';
			foreach( $posts_with_shortcode as $post ){
				$edit_link = get_edit_post_link( $post->ID );
				$response .= '<li>';
				$response .= "<a href='$edit_link' target='_blank'>$post->post_title</a>";
				$response .= '</li>';
			}
			$response .= '</ul>';
			return $response;
		}
	}
	new Basepress_Wizard();
}