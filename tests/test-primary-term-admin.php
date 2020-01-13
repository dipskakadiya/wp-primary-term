<?php
/**
 * Class SampleTest
 *
 * @package Wp_Primary_Term
 */

use WPPrimaryTerm\Admin\Primary_Term_Admin;

/**
 * Sample test case.
 */
class Test_Primary_Term_Admin extends WP_UnitTestCase {
	protected static $post_ids = array();
	protected static $cat_ids  = array();
	protected $taxonomy        = 'category';

	public function setUp() {
		parent::setUp();

		require_once WPPT_PATH . 'admin/class-primary-term-admin.php';

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
	}

	public static function wpSetUpBeforeClass( $factory ) {
		self::$post_ids = $factory->post->create_many( 5 );
		self::$cat_ids  = $factory->category->create_many( 3 );
	}

	public function test_enqueue_scripts() {
		global $pagenow;

		/**
		 * verify Script and js not loaded all screen
		 */
		set_current_screen( 'dashboard' );
		$this->assertFalse( wp_script_is( 'wppt-taxonomy-metabox' ) );
		$this->assertFalse( wp_style_is( 'wppt-taxonomy-metabox' ) );

		$pagenow = 'edit.php';
		set_current_screen( 'edit.php' );
		Primary_Term_Admin::get_instance()->enqueue_scripts();
		do_action( 'admin_enqueue_scripts' );
		$this->assertFalse( wp_script_is( 'wppt-taxonomy-metabox' ) );
		$this->assertFalse( wp_style_is( 'wppt-taxonomy-metabox' ) );

		/**
		 * verify Script and js loaded on post edit page
		 */
		$pagenow = 'post.php';
		set_current_screen( 'post.php' );
		Primary_Term_Admin::get_instance()->enqueue_scripts();
		do_action( 'admin_enqueue_scripts' );
		$this->assertTrue( wp_script_is( 'wppt-taxonomy-metabox' ) );
		$this->assertTrue( wp_style_is( 'wppt-taxonomy-metabox' ) );

		wp_deregister_script( 'wppt-taxonomy-metabox' );
		wp_deregister_style( 'wppt-taxonomy-metabox' );

		/**
		 * verify Script and js loaded on new post page
		 */
		$pagenow = 'post-new.php';
		set_current_screen( 'post-new.php' );
		Primary_Term_Admin::get_instance()->enqueue_scripts();
		do_action( 'admin_enqueue_scripts' );
		$this->assertTrue( wp_script_is( 'wppt-taxonomy-metabox' ) );
		$this->assertTrue( wp_style_is( 'wppt-taxonomy-metabox' ) );

	}

	public function test_admin_footer() {
		global $pagenow;

		/**
		 * verify template not added on all screen
		 */
		$pagenow = 'edit.php';
		set_current_screen( 'edit.php' );

		$output = get_echo( array( Primary_Term_Admin::get_instance(), 'admin_footer' ) );
		$this->assertFalse( strpos( $output, 'id="tmpl-wppt-primary-term-input"' ) !== false );
		$this->assertFalse( strpos( $output, 'id="tmpl-wppt-primary-term-element"' ) !== false );
		$this->assertFalse( strpos( $output, 'id="tmpl-wppt-primary-term-render"' ) !== false );

		/**
		 * verify template added on new post screen
		 */
		$pagenow = 'post-new.php';
		set_current_screen( 'post-new.php' );

		$output = get_echo( array( Primary_Term_Admin::get_instance(), 'admin_footer' ) );
		$this->assertTrue( strpos( $output, 'id="tmpl-wppt-primary-term-input"' ) !== false );
		$this->assertTrue( strpos( $output, 'id="tmpl-wppt-primary-term-element"' ) !== false );
		$this->assertTrue( strpos( $output, 'id="tmpl-wppt-primary-term-render"' ) !== false );
	}
}
