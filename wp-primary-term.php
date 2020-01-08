<?php

/*
Plugin Name: Wp Primary Term
Plugin URI: https://profiles.wordpress.org/dipeshkakadiya/
Description: This plugin allow you to set primary term for posts & custom post type.
Version: 1.0
Author: dipesh<dipesh.kakadiya111@gmail.com>
Author URI: https://profiles.wordpress.org/dipeshkakadiya/
License: GPL2
Text Domain: wordpress-primary-term
Domain Path: /languages
@package WPPrimaryTerm
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! defined( 'WPPT_PATH' ) ) {
    /**
     * Path to the plugin folder.
     *
     * @since 1.0
     */
    define( 'WPPT_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'WPPT_URL' ) ) {
    /**
     * URL to the plugin folder.
     *
     * @since 1.0
     */
    define( 'WPPT_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
}


require_once WPPT_PATH . 'includes/class-primary-term-public.php';

/**
 * Load the admin class if its the admin dashboard
 */
if ( is_admin() ) {
    require_once WPPT_PATH . 'admin/class-primary-term-admin.php';
}