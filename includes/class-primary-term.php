<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'Primary_Term' ) ) {

    /**
     * Class Primary_Term
     * class for Primary Term
     *
     * @since 1.0
     */
    class Primary_Term {

        /**
         * The taxonomy to which this term belongs.
         *
         * @since 1.0
         */
        protected $taxonomy_name;

        /**
         * The post ID to which this term belongs.
         *
         * @since 1-0
         */
        protected $post_id;


        /**
         * Primary_Term constructor.
         * @param $post_id post id
         * @param $taxonomy_name taxonomy slug
         * @since 1.0
         */
        public function __construct( $post_id, $taxonomy_name ){
            $this->post_id = $post_id;
            $this->taxonomy_name = $taxonomy_name;
        }

        /**
         * Save primary term into post meta
         *
         * @param $termId
         * @since 1.0
         */
        public function save_primary_term( $termId ){
            update_post_meta( $this->post_id, '_primary_' . $this->taxonomy_name, $termId );
        }

        /**
         * Get primary term id for post
         *
         * @since 1.0
         * @return bool|int
         */
        public function get_primary_term_id(){
            $primary_term_id = (int) get_post_meta( $this->post_id, '_primary_' . $this->taxonomy_name, true );

            $post_terms_ids = $this->ger_post_terms_ids();
            if ( ! in_array( $primary_term_id, $post_terms_ids ) ){
                $primary_term_id = false;
            }
            return $primary_term_id;
        }

        /**
         * Get primary term object for post
         * @since 1.0
         * @return array|WP_Error|WP_Term|null
         */
        public function get_primary_term(){
            $primary_term_id = $this->get_primary_term_id();
            return get_term( $primary_term_id, $this->taxonomy_name );
        }

        /**
         * Get term ids for post
         * @since 1.0
         * @return array
         */
        private function ger_post_terms_ids(){
            $terms = get_the_terms( $this->post_id, $this->taxonomy_name );
            if ( ! is_array( $terms ) ) {
                $terms = array();
            }
            return wp_list_pluck( $terms, 'term_id' );
        }
    }
}
