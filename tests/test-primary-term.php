<?php
/**
 * Class SampleTest
 *
 * @package Wp_Primary_Term
 */

use WPPrimaryTerm\Primary_Term;

/**
 * Sample test case.
 */
class Test_Primary_Term extends WP_UnitTestCase {
    protected static $post_ids = array();
    protected static $cat_ids = array();
    protected $taxonomy        = 'category';
    protected $class_instance        = '';

    public function setUp(){
        parent::setUp();
    }

    public static function wpSetUpBeforeClass( $factory ) {
        self::$post_ids = $factory->post->create_many( 5 );
        self::$cat_ids = $factory->category->create_many( 3 );
    }

	/**
	 * Test case for save_primary_term
	 */
	public function test_save_primary_term() {
        wp_set_post_categories( self::$post_ids[0], array( self::$cat_ids[0], self::$cat_ids[1] ) );

        $this->class_instance = new Primary_Term( self::$post_ids[0], $this->taxonomy );

        /**
         * Verify database doesn't have primary term set
         */
        $primary_term_id = get_post_meta( self::$post_ids[0], '_primary_' . $this->taxonomy, true );
        $this->assertNotEquals( self::$cat_ids[0], $primary_term_id );
        $this->assertNotEquals( self::$cat_ids[1], $primary_term_id );

        /**
         * Verify save_primary_term store primary term meta correctly
         */
        $this->class_instance->save_primary_term( self::$cat_ids[0] );
        $primary_term_id = get_post_meta( self::$post_ids[0], '_primary_' . $this->taxonomy, true );
        $this->assertEquals( self::$cat_ids[0], $primary_term_id );
        $this->assertNotEquals( self::$cat_ids[1], $primary_term_id );
	}

    /**
     * Test case for save_primary_term
     */
    public function test_get_primary_term_id() {

        $cache_key = 'primary_term_' . $this->taxonomy . '_' . self::$post_ids[0];

        wp_set_post_categories( self::$post_ids[0], array( self::$cat_ids[0], self::$cat_ids[1] ) );

        $this->class_instance = new Primary_Term( self::$post_ids[0], $this->taxonomy );
        $this->class_instance->save_primary_term( self::$cat_ids[0] );

        /**
         * Verify get_primary_term_id return correct primary term id
         */
        $primary_term_id = $this->class_instance->get_primary_term_id();
        $this->assertEquals( self::$cat_ids[0], $primary_term_id );

        /**
         * Verify primary term should be selected as post term otherwise it's return false
         */
        wp_set_post_categories( self::$post_ids[0], self::$cat_ids[1] );
        wp_cache_delete( $cache_key, 'wp-primary-term' );
        $primary_term_id = $this->class_instance->get_primary_term_id();
        $this->assertNotEquals( self::$cat_ids[0], $primary_term_id );
        $this->assertFalse( $primary_term_id );

        /**
         * Verify Cache functionality working
         */
        $this->class_instance->save_primary_term( self::$cat_ids[1] );
        $primary_term_id = $this->class_instance->get_primary_term_id();
        $this->assertEquals( self::$cat_ids[1], $primary_term_id );

        wp_set_post_categories( self::$post_ids[0], self::$cat_ids[0] );
        $this->assertEquals( self::$cat_ids[1], $primary_term_id );

        wp_cache_delete( $cache_key, 'wp-primary-term' );
        $primary_term_id = $this->class_instance->get_primary_term_id();
        $this->assertNotEquals( self::$cat_ids[1], $primary_term_id );
    }

    public function test_get_primary_term(){
        wp_set_post_categories( self::$post_ids[0], array( self::$cat_ids[0], self::$cat_ids[1] ) );

        $this->class_instance = new Primary_Term( self::$post_ids[0], $this->taxonomy );
        $this->class_instance->save_primary_term( self::$cat_ids[0] );

        /**
         * Verify get_primary_term return correct primary term object
         */
        $primary_term = $this->class_instance->get_primary_term();
        $this->assertEquals( get_term( self::$cat_ids[0] ), $primary_term );
        $this->assertNotEquals( get_term( self::$cat_ids[1] ), $primary_term );

        /**
         * Verify primary term should be selected as post term otherwise it's return false
         */
        wp_set_post_categories( self::$post_ids[0], self::$cat_ids[1] );
        $cache_key = 'primary_term_' . $this->taxonomy . '_' . self::$post_ids[0];
        wp_cache_delete( $cache_key, 'wp-primary-term' );
        $primary_term = $this->class_instance->get_primary_term();
        $this->assertFalse( $primary_term );

    }
}
