<?php
namespace WPPrimaryTerm;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( ! class_exists( 'Primary_Term_Public' ) ) {
	/**
	 * Class Primary_Term_Public
	 * Main class for this plugin
	 *
	 * @since 1.0
	 */
	class Primary_Term_Public {


		/**
		 * The instance of the class Primary_Term_Public
		 *
		 * @since 1.0
		 * @var Primary_Term_Public
		 */
		protected static $instance = null;

		/**
		 * All plugin classes
		 *
		 * @var string
		 */
		public $c = array();

		/**
		 * Primary_Term_Public constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {
		}

		/**
		 * Return the current statis instant of this class
		 *
		 * @return Primary_Term_Public
		 * @since 1.0
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->register_autoload();
				self::$instance->register_hooks();
			}

			return self::$instance;
		}

		/**
		 * Auto load all class
		 *
		 * @since 1.0
		 */
		private function register_autoload() {

			include WPPT_PATH . 'includes/functions.php';

			spl_autoload_register(
				function ( $class_name ) {

					$class_name = explode( '\\', $class_name );
					$class_name = $class_name[ array_key_last( $class_name ) ];

					/**
					 * Class name should start with Primary_Term for autoload
					 */
					$cid = 'Primary_Term';
					if ( substr( $class_name, 0, strlen( $cid ) ) !== $cid ) {
						return false;
					}

					/**
					 * Class file name should be same as class name with prefix `class-` and need to `-` in place of `_` with lowercase.
					 */
					$class_file_name = 'class-' . strtolower( str_replace( '_', '-', $class_name ) );
					$folders         = array(
						'includes/',
					);
					foreach ( $folders as $folder ) {
						$load = WPPT_PATH . $folder . $class_file_name . '.php';
						if ( file_exists( $load ) ) {
							require_once $load;
							return true;
						}
					}
					return false;
				}
			);
		}

		/**
		 * setup all public hook and filter
		 */
		protected function register_hooks() {
		}

		/**
		 * Return taxonomies array for which primary term functionality enables
		 *
		 * @return array
		 * @since 1.0
		 */
		public function get_primary_taxonomies() {
			$taxonomies = array(
				array(
					'title' => 'Category',
					'name'  => 'category',
				),
			);
			return apply_filters( 'primary_term_taxonomies', $taxonomies );
		}
	}

	Primary_Term_Public::get_instance();
}
