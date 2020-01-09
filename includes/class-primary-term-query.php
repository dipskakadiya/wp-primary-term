<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'Primary_Term_Query' ) ) {

    /**
     * Class Primary_Term_Query
     * class for Primary Term Query
     *
     * @since 1.0
     */
    class Primary_Term_Query {

        public function __construct(){
            $this->register_hooks();
        }

        /**
         * setup all public hook and filter
         */
        protected function register_hooks() {
            add_action( 'pre_get_posts', array( $this, 'primary_term_query' ) );
        }

        /**
         * Update wp_query to filter post and custom post types based on their primary categories.
         * @param $query
         *
         * @since 1.0
         * @return WP_Query retun WP_Query object
         */
        public function primary_term_query( $query ){
            $query_vars = $query->query_vars;

            $taxonomies = Primary_Term_Public::get_instance()->get_primary_taxonomies();

            foreach ( $taxonomies as $taxonomy ) {
                if ( isset( $query_vars['primary_term_query'][ 'primary_' . $taxonomy['name'] ] ) ){
                    if ( empty( $query->query_vars['meta_query'] ) ){
                        $query->query_vars['meta_query'] = array(
                            'relation' => 'AND'
                        );
                    }

                    if ( ! empty( $query_vars['primary_term_query']['relation'] ) ){
                        $query->query_vars['meta_query']['relation'] = $query_vars['primary_term_query']['relation'];
                    }

                    $query->query_vars['meta_query'][] = array(
                        'key'     => '_primary_' . $taxonomy['name'],
                        'value'   => $query_vars['primary_term_query'][ 'primary_' . $taxonomy['name'] ]['value'],
                        'compare' => $query_vars['primary_term_query'][ 'primary_' . $taxonomy['name'] ]['compare']
                    );
                }
            }
            return $query;
        }
    }
}
