<?php
/**
 * This is the class that adds the post icon metabox on edit screen
 */

// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Post_Icon_Metabox' ) ) {

	class Basepress_Post_Icon_Metabox {

		/**
		 * basepress_post_icon_metabox constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );
		}


		/**
		 * Adds Sections metabox on post edit screen
		 *
		 * @since 1.0.0
		 * @param $post_type
		 */
		public function add_meta_box( $post_type ) {

			if ( 'knowledgebase' == $post_type ) {

				//Add BasePress section( sub category ) selector
				add_meta_box(
					'basepress_post_icon',
					__( 'Article Icon', 'basepress' ),
					array( $this, 'render_meta_box' ),
					$post_type,
					'side',
					'default'
				);
			}
		}



		/**
		 * Renders the metabox on edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $post
		 */
		public function render_meta_box( $post ) {
			global $basepress_utils;

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'basepress_post_icon_meta', 'basepress_post_icon_meta_nonce' );

			$icons = $basepress_utils->icons;

			// Use get_post_meta to retrieve an existing value from the database.
			$value = get_post_meta( $post->ID, 'basepress_post_icon', true );

			echo '<div id="basepress-post-icons">';

			echo '<label>';
			echo '<input type="radio" class="basepress-post-icon" name="basepress_post_icon" value=""' . checked( $value, '', false ) . '>';
			echo '<span class="basepress-empty-post-icon"></span>';
			echo '</label>';
			foreach ( $icons->post->icon as $icon ) {
				echo '<label>';
				echo '<input type="radio" class="basepress-post-icon" name="basepress_post_icon" value="' . $icon . '"' . checked( $value, $icon, false ) . '>';
				echo '<span class="' . $icon . '"></span>';
				echo '</label>';
			}
			echo '</div>';
		}



		/**
		 * Saves metabox data on post save
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
			if ( ! isset( $_POST['basepress_post_icon_meta_nonce'] ) ) {
				return $post_id;
			}

			$nonce = $_POST['basepress_post_icon_meta_nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'basepress_post_icon_meta' ) ) {
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
			if ( isset( $_POST['basepress_post_icon'] ) ) {
				update_post_meta( $post_id, 'basepress_post_icon', $_POST['basepress_post_icon'] );
			}
		}
	}

	new Basepress_Post_Icon_Metabox;
}