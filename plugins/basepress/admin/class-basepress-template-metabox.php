<?php
/**
 * This is the class that adds the post template metabox on edit screen
 */

// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Template_Metabox' ) ) {

	class Basepress_Template_Metabox {

		/**
		 * basepress_template_metabox constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );
		}



		/**
		 * Adds Template metabox on post edit
		 *
		 * @since 1.0.0
		 *
		 * @param $post_type
		 */
		public function add_meta_box( $post_type ) {

			global $basepress_utils;
			$options = $basepress_utils->get_options();
			if( ! isset( $options['force_sidebar_position'] ) ){

				if( 'knowledgebase' == $post_type ){
					add_meta_box(
						'basepress_template',
						__( 'Template', 'basepress' ),
						array( $this, 'render_meta_box' ),
						$post_type,
						'side',
						'default'
					);
				}
			}
		}



		/**
		 * Renders the metabox on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $post
		 */
		public function render_meta_box( $post ) {

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'basepress_template_meta', 'basepress_template_meta_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$value = get_post_meta( $post->ID, 'basepress_template_name', true );

			//Get list of the active theme templates
			$templates = $this->get_theme_templates();

			// Display the form, using the current value.?>
			<select id="basepress_template_field" name="basepress_template_field">
				
				<option <?php echo ( '' == $value ? 'selected' : ''); ?> disabled>Choose Template</option>
				<option disabled>─────────</option>

				<?php
				foreach ( $templates as $template => $name ) :
					$selected = esc_attr( $value ) == $template ? 'selected' : '';
				?>

				<option value="<?php echo $template; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>

			</select>
			<?php
		}



		/**
		 * Saves the metabox data on post save
		 *
		 * @since 1.0.0
		 *
		 * @param $post_id
		 * @return mixed
		 */
		public function save_meta_box( $post_id ) {

			//We need to verify that this came from our screen and with proper authorization,
			//because save_post can be triggered at other times.

			// Check if our nonce is set.
			if ( ! isset( $_POST['basepress_template_meta_nonce'] ) ) {
				return $post_id;
			}

			$nonce = $_POST['basepress_template_meta_nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'basepress_template_meta' ) ) {
				return $post_id;
			}

			//If this is an autosave, our form has not been submitted,
			//so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			//OK, it's safe for us to save the data now.
			// Update the meta field.
			if ( isset( $_POST['basepress_template_field'] ) ) {
				update_post_meta( $post_id, 'basepress_template_name', $_POST['basepress_template_field'] );
			}
		}



		/**
		 * Gets templates from active theme
		 *
		 * @since 1.0.0
		 *
		 * @return array
		 */
		private function get_theme_templates() {
			global $basepress_utils;
			$options = $basepress_utils->get_options();
			$theme_name = $options['theme_style'];
			$theme_templates = array();
			$theme_directories = array();
			$unique_templates = array();

			$main_theme_dir = get_stylesheet_directory() . '/basepress/' . $theme_name;
			if ( file_exists( $main_theme_dir ) ) {
				$theme_directories[] = $main_theme_dir;
			}
			$theme_directories[] = BASEPRESS_DIR . 'themes/' . $theme_name;

			$files = array();
			foreach ( $theme_directories as $theme_directory ) {
				$files = array_merge( $files, glob( $theme_directory . '/*.php' ) );
			}

			foreach ( $files as $file ) {
				$headers = get_file_data( $file, array( 'Template Name' => 'Template Name' ) );

				if ( empty( $headers['Template Name'] ) ) {
					continue;
				}

				$file_name = basename( $file, '.php' );
				if ( ! in_array( $file_name, $unique_templates ) ) {
					$theme_templates[ $file_name ] = $headers['Template Name'];
					$unique_templates[] = $file_name;
				}
			}

			return $theme_templates;
		}
	}

	new Basepress_Template_Metabox;

}