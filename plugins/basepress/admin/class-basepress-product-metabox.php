<?php
/**
 * This is the class that adds the product metabox on edit screen
 */

// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Product_Metabox' ) ) {

	class Basepress_Product_Metabox {

		/**
		 * basepress_product_metabox constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		}



		/**
		 * Adds Products metabox on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $post_type
		 */
		public function add_meta_box( $post_type ) {

			if ( 'knowledgebase' == $post_type ) {

				//Add BasePress product selector
				add_meta_box(
					'basepress_product',
					__( 'Knowledge Base', 'basepress' ),
					array( $this, 'render_meta_box' ),
					$post_type,
					'side',
					'default'
				);
			}
		}



		/**
		 * Finds the product from the section
		 *
		 * @since 1.0.0
		 *
		 * @param $term
		 * @return int
		 */
		private function get_product( $term ) {
			if ( 0 != $term->parent ) {
				$parent_term = get_term( $term->parent, 'knowledgebase_cat' );

				if ( 0 != $parent_term->parent ) {
					return $this->get_product( $parent_term );
				} else {
					return $parent_term->term_id;
				}
			}
		}



		/**
		 * Renders the metabox on post edit
		 *
		 * @since 1.0.0
		 * @updated 1.7.6
		 *
		 * @param $post
		 */
		public function render_meta_box( $post ) {

			global $basepress_utils;
			
			$options = $basepress_utils->get_options();

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'basepress_product_meta', 'basepress_product_meta_nonce' );

			$value = 0;

			//Get taxonomy terms for the post
			$terms = get_the_terms( $post->ID, 'knowledgebase_cat' );

			if ( $terms ) {
				$value = $this->get_product( $terms[0] );
			}
			else if( isset( $options['single_product_mode'] ) ){
				//Automatically select the prodcut if in Single product mode
				$active_product_id = $basepress_utils->get_active_product_id();
				
				if( $active_product_id ){
					$value = $active_product_id;
				}
			}
			
			//Get list of product categories
			$products = $this->get_products_list();

			// Display the form, using the current value.?>
			<select class="basepress_product_mb">
				<option <?php echo ( '' == $value ? 'selected' : ''); ?> disabled>Choose Knowledge Base</option>
				<option disabled>─────────</option>

				<?php
				foreach ( $products as $product => $name ) :

					$selected = esc_attr( $value ) == $product ? 'selected' : '';

				?>
				<option value="<?php echo $product; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</select>
			<?php
		}




		/**
		 * Generates the list of product
		 *
		 * @since 1.0.0
		 *
		 * @return array
		 */
		private function get_products_list() {

			$products = get_terms(
				'knowledgebase_cat',
				array(
					'orderby'    => 'id',
					'hide_empty' => false,
					'parent'     => 0,
				)
			);
			$products_list = array();

			foreach ( $products as $product ) {
				$products_list[ $product->term_id ] = $product->name;
			}

			return $products_list;
		}

	}

	new Basepress_Product_Metabox;
}