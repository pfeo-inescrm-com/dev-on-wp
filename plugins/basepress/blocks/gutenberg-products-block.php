<?php
/**
* This is the class that adds the Gutenberg products block
*/

// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Products_block' ) ){

	class Basepress_Products_block{

		/**
		 * Basepress_Products_block constructor.
		 *
		 * @since 2.1.0
		 */
		public function __construct(){
			add_action( 'init', array( $this, 'register_block' ) );
		}



		/**
		 * Register the block
		 *
		 * @since 2.1.0
		 */
		public function register_block(){

			wp_register_script(
				'basepress-kb-products-block-editor',
				plugins_url( 'js/basepress-products-block.js', __FILE__ ),
				array(
					'wp-i18n',
					'wp-blocks',
					'wp-element',
					'wp-components'
				),
				filemtime( __DIR__ . "/js/basepress-products-block.js" )
			);

			$languages = plugin_dir_path( __DIR__ ) . 'languages/';
			wp_set_script_translations( 'basepress-kb-products-block-editor', 'basepress', $languages );

			register_block_type(
				'basepress-kb/products-block',
				array(
					'editor_script' => 'basepress-kb-products-block-editor',
					'render_callback' => array( $this, 'block_render' )
				)
			);
		}

		/**
		 * Render the block
		 *
		 * @since 2.1.0
		 *
		 * @return false|string
		 */
		public function block_render(){
			global $basepress_utils;

			$products = $basepress_utils->get_products();

			if( empty( $products ) ){
				return $basepress_utils->no_products_message();
			}

			$products_template = $basepress_utils->get_theme_file_path( 'products.php' );

			ob_start();
			if ( $products_template ) {
				include $products_template;
			}
			return ob_get_clean();
		}


		/**
		 * Ajax callback for products css for block
		 *
		 * @since 2.1.0
		 */
		public function basepress_products_block_call(){
			global $basepress_utils;

			$stylesheet = apply_filters( 'basepress_theme_style', 'style.css' );

			$theme_css = $basepress_utils->get_theme_file_uri( 'css/' . $stylesheet );

			wp_send_json( $theme_css );
		}
	}

	new Basepress_Products_block();
}
