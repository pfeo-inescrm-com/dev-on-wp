<?php
/**
 * This is the class that adds the Gutenberg articles list block
 */

// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Articles_list_block' ) ){

	class Basepress_Articles_list_block{

		/**
		 * Basepress_Articles_list_block constructor.
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
				'basepress-kb-articles-list-editor',
				plugins_url( 'js/basepress-articles-list-block.js', __FILE__ ),
				array(
					'wp-i18n',
					'wp-blocks',
					'wp-element',
					'wp-components',
					'wp-editor'
				),
				filemtime( __DIR__ . "/js/basepress-articles-list-block.js" )
			);

			$languages = plugin_dir_path( __DIR__ ) . 'languages/';
			wp_set_script_translations( 'basepress-kb-articles-list-editor', 'basepress', $languages );

			register_block_type(
				'basepress-kb/articles-list-block',
				array(
					'editor_script'   => 'basepress-kb-articles-list-editor',
					'render_callback' => array( $this, 'block_render' ),
					'attributes'      => array(
						'product' => array(
							'type' => 'string',
						),
						'section' => array(
							'type' => 'string',
						),
						'orderby' => array(
							'type' => 'string',
						),
						'order'   => array(
							'type' => 'string',
						),
						'count'   => array(
							'type' => 'number',
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
		 * @param $attributes
		 * @return string
		 */
		public function block_render( $attributes ){
			$output = '';

			$product = isset( $attributes['product'] ) ? $attributes['product'] : '';
			$section = isset( $attributes['section'] ) ? $attributes['section'] : '';
			$parent = $section ? $section : $product;

			if( ! $parent ){
				return '<p>' . __( 'Please select a Knowledge Base to start!', 'basepress' ) . '</p>';
			}

			$orderby = isset( $attributes['orderby'] ) && $attributes['orderby'] ? $attributes['orderby'] : 'custom';
			$orderby = 'custom' == $orderby ? 'menu_order' : $orderby;
			$order = isset( $attributes['order'] ) && $attributes['order'] ? $attributes['order'] : 'ASC';
			$count = isset( $attributes['count'] ) && $attributes['count'] ? $attributes['count'] : 5;

			$term = get_term( $parent, 'knowledgebase_cat' );
			$include_children = $term->parent == 0 ? true : false;

			// arguments
			$args = array(
				'post_type'      => 'knowledgebase',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'tax_query'      => array(
					array(
						'taxonomy'         => 'knowledgebase_cat',
						'field'            => 'term_id',
						'terms'            => $parent,
						'include_children' => $include_children
					),
				),
				'meta_query'     => array(
					'relation' => 'AND',

					'score' => array(
						'key'   => 'basepress_score',
						'type'  => 'numeric',
						'order' => 'DESC',
					),
					'views' => array(
						'key'   => 'basepress_views',
						'type'  => 'numeric',
						'order' => 'ASC',
					),
				),
				'orderby'        => $orderby,
				'order'          => $order,
			);

			$post_list = new WP_Query( $args );

			// loop over query
			if( $post_list->have_posts() ) :

				$output = '<ul class="bpress-article-list">';
				while( $post_list->have_posts() ) :
					$post_list->the_post();

					$output .= '<li class="bpress-article-list-item">';
					$output .= '<a href="' . get_the_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">';
					$output .= get_the_title() . '</a>';
					$output .= '</li>';

				endwhile;
				$output .= '</ul>';

			endif;

			return $output;
		}
	}
	new Basepress_Articles_list_block();
}