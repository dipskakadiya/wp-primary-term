<?php
namespace WPPrimaryTerm\Admin;

use WPPrimaryTerm\Primary_Term;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Primary_Term_Admin' ) ) {
	/**
	 * Class Primary_Term_Admin
	 * Main class for this plugin
	 *
	 * @since 1.0
	 */
	class Primary_Term_Admin {


		/**
		 * The instance of the class Primary_Term_Admin
		 *
		 * @since 1.0
		 * @var Primary_Term_Admin
		 */
		protected static $instance = null;

		/**
		 * WP_Primary_Category constructor.
		 * Class function to register hook for admin setup
		 * @since 1.0
		 */
		public function __construct() {
		}

		/**
		 * Return the current static instant of this class
		 *
		 * @return Primary_Term_Admin
		 * @since 1.0
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->register_hooks();
			}

			return self::$instance;
		}

		/**
		 * Registers the actions and filters for the Admin UI.
		 *
		 * @since 1.0
		 */
		public function register_hooks() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ), 10 );
			add_action( 'save_post', array( $this, 'save_primary_terms' ) );
		}

		/**
		 * Enqueue all the js assets which requited for primary category admin setup.
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			/**
			 * return if screen is not post edit or new post screen
			 */
			if ( ! $this->is_post_edit() && ! $this->is_post_add() ) {
				return;
			}

			wp_register_script( 'wppt-taxonomy-metabox', WPPT_URL . 'admin/js/wppt-taxonomy-metabox.js', array( 'jquery' ), time(), true );
			wp_enqueue_script( 'wppt-taxonomy-metabox' );

			wp_register_style( 'wppt-taxonomy-metabox', WPPT_URL . 'admin/css/wppt-taxonomy-metabox.css', array(), time() );
			wp_enqueue_style( 'wppt-taxonomy-metabox' );

			$taxonomies = wppt_get_primary_taxonomies();
			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $key => $taxonomy ) {
					$taxonomies[ $key ] ['primary'] = wppt_get_primary_term_id( $taxonomy['name'] );
				}
			}
			$data = array(
				'taxonomies' => $taxonomies,
			);
			wp_localize_script( 'wppt-taxonomy-metabox', 'WordPressPrimaryCategory', $data );
		}

		/**
		 * Load all primary terms template
		 *
		 * @since 1.0
		 */
		public function admin_footer() {

			/**
			 * return if screen is not post edit or new post screen
			 */
			if ( ! $this->is_post_edit() && ! $this->is_post_add() ) {
				return;
			}

			/**
			 * Include template for input for primary category
			 */
			include_once WPPT_PATH . 'admin/templates/templates-primary-term-input.php';
			include_once WPPT_PATH . 'admin/templates/templates-primary-term-element.php';
			include_once WPPT_PATH . 'admin/templates/templates-primary-term-render.php';
		}

		/**
		 * Allow to store selected primary term
		 *
		 * @param $post_id  post id for store primary term
		 * @since 1.0
		 */
		public function save_primary_terms( $post_id ) {

			$taxonomies = wppt_get_primary_taxonomies();

			foreach ( $taxonomies as $taxonomy ) {
				$this->save_primary_term( $post_id, $taxonomy );
			}
		}

		/**
		 * Allow to store selected term as primary term of particular taxonomy.
		 *
		 * @param $post_id  post id for store primary term
		 * @param $taxonomy  taxonomy
		 * @since 1.0
		 */
		protected function save_primary_term( $post_id, $taxonomy ) {
			$taxonomy_name = $taxonomy['name'];
			$primary_term  = filter_input( INPUT_POST, 'wppt_primary_' . $taxonomy_name . '_term', FILTER_SANITIZE_NUMBER_INT );

			// We accept an empty string here because we need to save that if no terms are selected.
			if ( null !== $primary_term && check_admin_referer( 'save-primary-term', 'wppt_primary_' . $taxonomy_name . '_nonce' ) ) {
				$primary_term_object = new Primary_Term( $post_id, $taxonomy_name );
				$primary_term_object->save_primary_term( $primary_term );
			}
		}

		/**
		 * Checks if the current screen is post edit
		 *
		 * @return bool
		 * @since 1.0
		 */
		private function is_post_edit() {
			global $pagenow;
			return 'post.php' === $pagenow;
		}

		/**
		 * Checks if the current screen is new post
		 *
		 * @return bool
		 * @since 1.0
		 */
		private function is_post_add() {
			global $pagenow;
			return 'post-new.php' === $pagenow;
		}
	}

	Primary_Term_Admin::get_instance();
}
