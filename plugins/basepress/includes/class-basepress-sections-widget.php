<?php
// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Basepress_Sections_Widget extends WP_Widget {


	/**
	 * Basepress_Sections_Widget constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		parent::__construct(
			'basepress_sections_widget', // Base ID
			esc_html__( 'Knowledge Base - Sections', 'basepress' ), // Name
			array( 'description' => esc_html__( 'Lists all sections for the current product in the Knowledge Base', 'basepress' ) ) // Args
		);
	}




	/**
	 * Front-end display of widget.
	 *
	 * @since 1.0.0
	 * @updated 1.4.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $basepress_utils;
		$options = $basepress_utils->get_options();
		$product = $basepress_utils->get_product();

		if ( ! $product->id ) {
			return;
		}

		//If the widget is called outside of BasePress pages or it is a search result page return
		if ( 'knowledgebase' != get_post_type() || ( is_search() && ! isset( $options['single_product_mode'] ) ) ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
			echo $args['after_title'];
		}

		$this->get_sections_list( $instance );

		echo $args['after_widget'];
	}




	/**
	 * Back-end widget form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$count = ! empty( $instance['count'] ) ? $instance['count'] : '';
		$limit_to_level = ! empty( $instance['limit-to-level'] ) ? $instance['limit-to-level'] : '';
		$max_level = ! empty( $instance['max-level'] ) ? $instance['max-level'] : 1;
		$order_by = ! empty( $instance['order-by'] ) ? $instance['order-by'] : 'custom';
		$post_count = ! empty( $instance['post-count'] ) ? $instance['post-count'] : 0;
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'basepress' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'Number of sections to show:', 'basepress' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo $count; ?>" size="3">
		</p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'order-by' ) ); ?>"><?php esc_attr_e( 'Order by:', 'basepress' ); ?></label>
		<select class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'order-by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order-by' ) ); ?>" type="number" value="<?php echo $order_by; ?>">
			<option value="custom"<?php echo ( 'custom' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Custom', 'basepress' ); ?></option>
			<option value="date-asc"<?php echo ( 'date-asc' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Date Ascending', 'basepress' ); ?></option>
			<option value="date-desc"<?php echo ( 'date-desc' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Date Descending', 'basepress' ); ?></option>
		</select>
		</p>

		<p>
		<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'post-count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post-count' ) ); ?>" type="checkbox" value="1" <?php checked( $post_count, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'post-count' ) ); ?>"><?php esc_attr_e( 'Display articles count', 'basepress' ); ?></label>
		</p>

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'limit-to-level' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit-to-level' ) ); ?>" type="checkbox" value="1" <?php checked( $limit_to_level, 1 ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'max-level' ) ); ?>"><?php esc_attr_e( 'Show up to level:', 'basepress' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'max-level' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max-level' ) ); ?>" type="number" value="<?php echo $max_level; ?>" size="3" min="1">
		</p>

		<?php
	}




	/**
	 * Sanitizes widget form values as they are saved.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['count'] = ! empty( $new_instance['count'] ) ? $new_instance['count'] : '';
		$instance['limit-to-level'] = ! empty( $new_instance['limit-to-level'] ) ? $new_instance['limit-to-level'] : '';
		$instance['max-level'] = ! empty( $new_instance['max-level'] ) ? $new_instance['max-level'] : '';
		$instance['order-by'] = ! empty( $new_instance['order-by'] ) ? $new_instance['order-by'] : 'custom';
		$instance['post-count'] = ! empty( $new_instance['post-count'] ) ? $new_instance['post-count'] : 0;

		return $instance;
	}



	/**
	 * Generates the sections list
	 *
	 * @since 1.0.0
	 *
	 * @param $instance
	 */
	private function get_sections_list( $instance ) {

		global $basepress_utils;
		$product = $basepress_utils->get_product();
		$queried_object = get_queried_object();
		$terms_count = isset( $instance['count'] ) ? $instance['count'] : 0;
		$limit_to_level = isset( $instance['limit-to-level'] ) && $instance['limit-to-level'] ? true : false;
		$max_level = isset( $instance['max-level'] ) && (int)$instance['max-level'] ? $instance['max-level'] - 1 : 999;

		if ( is_a( $queried_object, 'WP_Post' ) ) {
			$terms = get_the_terms( $queried_object->ID, 'knowledgebase_cat' );
			foreach ( $terms as $term ) {
				if ( 0 == $term->parent ) {
					continue;
				}
				$current_term_id = $term->term_id;
			}
		} elseif ( is_a( $queried_object, 'WP_Term' ) ) {
			$current_term_id = $queried_object->term_id;
		} else {
			$current_term_id = '';
		}

		switch ( $instance['order-by'] ) {

			case 'date-asc':
				$meta_key = '';
				$order_by = 'term_id';
				$order    = 'ASC';
				break;

			case 'date-desc':
				$meta_key = '';
				$order_by = 'term_id';
				$order    = 'DESC';
				break;

			case 'custom':
			default:
				$meta_key = 'basepress_position';
				$order_by = 'meta_value_num';
				$order    = 'ASC';
				break;
		}

		$sections = get_terms(
			'knowledgebase_cat',
			array(
				'hide_empty' => true,
				'child_of'   => $product->id,
				'meta_key'   => $meta_key,
				'orderby'    => $order_by,
				'order'      => $order,
				'number'     => $terms_count,
			)
		);

		//Store the section levels
		$levels = array();

		foreach ( $sections as $key => $section ) {

			if ( $section->parent == $product->id ) {
				$levels[ $section->term_id ] = 0;
			} else {
				$level = $levels[ $section->parent ] + 1;
				$levels[ $section->term_id ] = $level;
			}
			//Limit sub-sections to max level
			if( $limit_to_level && $levels[ $section->term_id ] > $max_level ){
				unset( $sections[$key] );
			}
		}

		/**
		* Filter to modify the list of sections
		*/
		$sections = apply_filters( 'basepress_section_widget_items', $sections );

		echo '<ul class="bpress-widget-list">';

		foreach ( $sections as $section ) {

			$level = $levels[ $section->term_id ];
			$level_name = 0 == $level ? ' section' : ' subsection';

			$permalink = get_term_link( $section->term_id, 'knowledgebase_cat' );
			$show_icons = basepress_show_section_icon();
			$classes = $current_term_id == $section->term_id ? ' active' : '';

			if ( $show_icons ) {
				$icon = get_term_meta( $section->term_id, 'icon', true );
				$icon = $icon ? $icon : $basepress_utils->icons->sections->default;
				$classes .= ' show-icon';
			}

			echo '<li class="bpress-widget-item' . $level_name . $classes . ' level-' . $level . '">';
			if ( $show_icons ) {
				echo '<span class="' . $icon . ' bpress-widget-icon"></span>';
			}
			echo '<a href="' . $permalink . '">';

			echo $section->name;
			if ( $instance['post-count'] && $section->count > 0 ) {
				echo ' (' . $section->count . ')';
			}

			echo '</a>';

			echo '</li>';

		}

		echo '</ul>';

	}

	/**
	 * Walker function to create nested lists for the widget
	 * NOT USED YET
	 *
	 * @since 1.3.0
	 *
	 * @param $sections
	 * @param $product_id
	 * @param $current_term_id
	 * @param $post_count
	 */
	private function walk( $sections, $product_id, $current_term_id, $post_count ) {
		global $basepress_utils;
		$show_icons = basepress_show_section_icon();

		//Store the section levels
		$levels = array();

		foreach ( $sections as $section ) {
			if ( $section->parent == $product_id ) {
				$levels[ $section->term_id ] = 0;
			} else {
				$level = $levels[ $section->parent ] + 1;
				$levels[ $section->term_id ] = $level;
			}
		}

		$last_level = -1;

		foreach ( $sections as $section ) {
			$permalink = get_term_link( $section->term_id, 'knowledgebase_cat' );
			$classes = $current_term_id == $section->term_id ? ' active' : '';

			if ( $show_icons ) {
				$icon = get_term_meta( $section->term_id, 'icon', true );
				$icon = $icon ? $icon : $basepress_utils->icons->sections->default;
				$classes .= ' show-icon';
			}

			$level = $levels[ $section->term_id ];
			$section_level = $section->parent == $product_id ? ' section' : ' subsection';

			//Add an item on the table of content
			if ( $level > $last_level ) {
				echo '<ul class="bpress-widget-list">';

			} else {
				echo str_repeat( '</li></ul>', $last_level - $level );
				echo '</li>';
			}
			echo '<li class="bpress-widget-item' . $section_level . $classes . '">';

			if ( $show_icons ) {
				echo '<span class="' . $icon . ' bpress-widget-icon"></span>';
			}

			echo '<a href="' . $permalink . '">';
			echo $section->name;
			if ( $post_count && $section->count > 0 ) {
				echo ' (' . $section->count . ')';
			}
			echo '</a>';

			$last_level = $level;
		}

		if ( count( $sections ) != 0 ) {
			echo '</li></ul>';
		}

	}
}
?>