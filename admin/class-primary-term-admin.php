<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'Primary_Category_Admin' ) ) {

    /**
     * Class WP_Primary_Category
     * Main class for this plugin
     *
     * @since 1.0
     */
    class Primary_Category_Admin {

        /**
         * The instance of the class Primary_Category_Admin
         *
         * @since 1.0
         * @var Primary_Category_Admin
         */
        protected static $instance = null;

        /**
         * WP_Primary_Category constructor.
         * Class function to register hook for admin setup
         * @since 1.0
         */
        public function __construct(){
            $this->register_hooks();
        }

        /**
         * Return the current static instant of this class
         *
         * @since 1.0
         * @return Primary_Category_Admin
         */
        public static function get_instance(){
            // If the single instance hasn't been set, set it now.
            if ( null === self::$instance ) {
                self::$instance = new self();
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
        }

        /**
         * Enqueue all the js assets which requited for primary category admin setup.
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

            $data       = array(
                'taxonomies' => array(
                    array(
                        'title'=> 'Category',
                        'name'=> 'category',
                        'primary'=> '1',
                    )
                )
            );
            wp_localize_script( 'wppt-taxonomy-metabox', 'WordPressPrimaryCategory', $data );
        }

        /**
         * Load all primary terms template
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
         * Checks if the current screen is post edit
         * @return bool
         */
        private function is_post_edit(){
            global $pagenow;
            return 'post.php' === $pagenow;
        }

        /**
         * Checks if the current screen is new post
         * @return bool
         */
        private function is_post_add(){
            global $pagenow;
            return 'post-new.php' === $pagenow;
        }
    }

    Primary_Category_Admin::get_instance();
}
