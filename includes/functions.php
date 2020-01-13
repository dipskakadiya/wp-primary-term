<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! function_exists( 'wppt_get_the_primary_term' ) ) {
	/**
	 * Retrieve a post's primary term as link with specified format.
	 *
	 * @since 1.0
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int $id int|WP_Post|null $post   Optional. Post ID or post object. Defaults to global $post.
	 * @param string $before Optional. Before link.
	 * @param string $after Optional. After link.
	 * @return string|false|WP_Error A link of term on success, false if there are no terms, WP_Error on failure.
	 */
	function wppt_get_the_primary_term( $taxonomy, $id = null, $before = '', $after = '' ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return false;
		}

		$primary_term_object = new \WPPrimaryTerm\Primary_Term( $post->ID, $taxonomy );
		$primary_term_id     = $primary_term_object->get_primary_term_id();

		if ( empty( $primary_term_id ) ) {
			return false;
		}
		$link = get_term_link( $primary_term_id, $taxonomy );
		$term = get_term( $primary_term_id, $taxonomy );
		$link = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';

		/**
		 * Filters the primary term links for a given taxonomy.
		 *
		 * The dynamic portion of the filter name, `$taxonomy`, refers
		 * to the taxonomy slug.
		 *
		 * @since 1.0
		 *
		 * @param string $link An array of term link.
		 */
		$term_link = apply_filters( "primary_term_links_{$taxonomy}", $link );

		return $before . $term_link . $after;
	}
}

if ( ! function_exists( 'wppt_get_primary_term' ) ) {
	/**
	 * Retrieve a post's primary term.
	 *
	 * @since 1.0
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int $id int|WP_Post|null $post   Optional. Post ID or post object. Defaults to global $post.
	 * @return string|false|WP_Error A object of term on success, false if there are no terms, WP_Error on failure.
	 */
	function wppt_get_primary_term( $taxonomy, $id = null ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return false;
		}

		$primary_term_object = new \WPPrimaryTerm\Primary_Term( $post->ID, $taxonomy );
		$term                = $primary_term_object->get_primary_term();
		if ( empty( $term ) ) {
			return false;
		}

		/**
		 * Filters the primary term for a given taxonomy.
		 *
		 * The dynamic portion of the filter name, `$taxonomy`, refers
		 * to the taxonomy slug.
		 *
		 * @since 1.0
		 *
		 * @param object $term An term object.
		 */
		$term = apply_filters( "primary_term_{$taxonomy}", $term );
		return $term;
	}
}

if ( ! function_exists( 'wppt_get_primary_term_id' ) ) {
	/**
	 * Retrieve a post's primary term id.
	 *
	 * @since 1.0
	 *

	 * @param string $taxonomy Taxonomy name.
	 * @param int $id int|WP_Post|null $post   Optional. Post ID or post object. Defaults to global $post.
	 * @return int|false|WP_Error A id of term on success, false if there are no terms, WP_Error on failure.
	 */
	function wppt_get_primary_term_id( $taxonomy, $id = null ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return false;
		}

		$primary_term_object = new \WPPrimaryTerm\Primary_Term( $post->ID, $taxonomy );
		$primary_term_id     = $primary_term_object->get_primary_term_id();
		if ( empty( $primary_term_id ) ) {
			return false;
		}

		/**
		 * Filters the primary term id for a given taxonomy.
		 *
		 * The dynamic portion of the filter name, `$taxonomy`, refers
		 * to the taxonomy slug.
		 *
		 * @since 1.0
		 *
		 * @param int $primary_term_id term id.
		 */
		$primary_term_id = apply_filters( "primary_term_{$taxonomy}_id", $primary_term_id );
		return (int) $primary_term_id;
	}
}

if ( ! function_exists( 'wppt_get_primary_taxonomies' ) ) {
	/**
	 * Retrieve taxonomy array which are support primary term
	 *
	 * @since 1.0
	 * @return array Taxonomies array
	 */
	function wppt_get_primary_taxonomies() {
		return WPPrimaryTerm\Primary_Term_Public::get_instance()->get_primary_taxonomies();
	}
}
