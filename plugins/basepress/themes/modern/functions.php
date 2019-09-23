<?php
/**
 * Functions to extend the Modern Theme
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'basepress_modern_theme' ) ) {

	class basepress_modern_theme {

		private $settings = '';

		function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'init', array( $this, 'load_theme_settings' ) );

		}


		public function load_theme_settings() {
			$this->settings = get_option( 'basepress_modern_theme' );
		}


		public function enqueue_scripts() {
			global $wp_query,$basepress_utils;

			$options = $basepress_utils->get_options();
			$entry_page = isset( $options['entry_page'] ) ? $options['entry_page'] : '';

			if ( 'knowledgebase' == get_post_type()
				|| ( isset($wp_query->query_vars['post_type']) && 'knowledgebase' == $wp_query->query_vars['post_type'] )
				|| is_tax( 'knowledgebase_cat' ) || is_page( $entry_page )
				|| ( isset( $wp_query->query['taxonomy'] ) && 'knowledgebase_cat' == $wp_query->query['taxonomy'] ) )
			{
				$js_path = $basepress_utils->get_theme_file_path( 'js/modern.js' );
				$js_ver = filemtime( $js_path );
				wp_enqueue_script( 'stickyfixed-js', plugins_url( 'js/fixedsticky.js', __FILE__ ), array( 'jquery' ), $js_ver, true );
				wp_enqueue_script( 'basepress-modern-js', plugins_url( 'js/modern.js', __FILE__ ), array( 'stickyfixed-js' ), $js_ver, true );

				//Settings Styles
				$settings = $this->settings;

				$header_image = $settings['header_image'] ? wp_get_attachment_image_src( $settings['header_image'], 'full' ) : '';

				$styles = '';

				if ( $settings['font_family'] ) {
					$styles .= stripslashes( $settings['font_family'] );
				}

				if ( isset( $settings['full_width_header'] ) ) {
					$styles .= 'body{overflow-x:hidden;}';
				}

				if ( $settings['font_size'] || $settings['font_family'] ) {

					if ( $settings['font_size'] ) {
						$styles .= '.bpress-wrap{font-size:' . $settings['font_size'] . 'px;}';
					}
					if ( $settings['font_family'] ) {
						$styles .= '.bpress-wrap *{';
						preg_match( '/family=([a-zA-Z+]*)/', $settings['font_family'], $font_family );
						$font_family = str_replace( '+', ' ', $font_family[1] );
						$styles .= isset( $font_family ) ? 'font-family:"' . $font_family . '";' : '';
						$styles .= '}';
					}
				}

				//Page header
				$styles .= '.bpress-page-header{';
				if ( $header_image ) {
					$styles .= 'background-image: url(' . $header_image[0] . ');';
				}
				if ( $settings['header_offset'] && '' != $settings['header_offset'] ) {
					$styles .= 'margin-top:' . $settings['header_offset'] . ';';
				}
				if ( isset( $settings['full_width_header'] ) ) {
					$styles .= 'position: relative; width: 100vw; margin-left: -50vw; left: 50%;';
				}

				$styles .= '}';

				//Breadcrumbs
				if ( isset( $settings['full_width_header'] ) ) {
					$styles .= '.bpress-crumbs-wrap{';
					$styles .= 'width: 100vw; position: relative; margin-left: -50vw; left: 50%;';
					$styles .= '}';
				}

				//Sidebar
				if ( isset( $settings['sticky_sidebar'] ) ) {
					$sidebar_threshold = isset( $settings['sidebar_threshold'] ) && '' != $settings['sidebar_threshold'] ? $settings['sidebar_threshold'] : 0;
					$styles .= '.bpress-sidebar{position:sticky;top:' . $sidebar_threshold . ';}';
				}

				//Custom Css
				$styles .= $settings['custom_css'];

				wp_add_inline_style( 'basepress-styles', $styles );
			}
		}

	} //End Class

	new basepress_modern_theme;
}

add_filter( 'basepress_modern_theme_header_title', function( $default = false ){
	$options = get_option( 'basepress_modern_theme' );
	$default = $default ? $default : 'Knowledge Base';
	$has_title = isset( $options['enable_settings'] ) && $options['enable_settings'] && isset( $options['header_title'] ) && $options['header_title'];
	return  $has_title ? stripslashes( $options['header_title'] ) : $default;
});