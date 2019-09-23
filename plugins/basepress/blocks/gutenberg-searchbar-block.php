<?php
/**
 * This is the class that adds the Gutenberg search bar block
 */

// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_SearchBar_block' ) ){

	class Basepress_SearchBar_block{

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
				'basepress-kb-searchbar-block-editor',
				plugins_url( 'js/basepress-searchbar-block.js', __FILE__ ),
				array(
					'wp-i18n',
					'wp-blocks',
					'wp-element',
					'wp-components',
					'wp-editor'
				),
				filemtime( __DIR__ . "/js/basepress-searchbar-block.js" )
			);

			$languages = plugin_dir_path( __DIR__ ) . 'languages/';
			wp_set_script_translations( 'basepress-kb-searchbar-block-editor', 'basepress', $languages );

			register_block_type(
				'basepress-kb/searchbar-block',
				array(
					'editor_script' => 'basepress-kb-searchbar-block-editor',
					'render_callback' => array( $this, 'block_render' ),
					'attributes'      => array(
						'product' => array(
							'type' => 'string',
							'default' => 0
						),
						'width' => array(
							'type' => 'string',
							'default' => ''
						)
					)
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
		public function block_render( $attributes ){
			global $basepress_search;

			return $basepress_search->do_shortcode( $attributes );

		}
	}

	new Basepress_SearchBar_block();
}
