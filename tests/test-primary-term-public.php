<?php
/**
 * Class SampleTest
 *
 * @package Wp_Primary_Term
 */
use WPPrimaryTerm\Primary_Term_Public;

/**
 * Sample test case.
 */
class Test_Primary_Term_Public extends WP_UnitTestCase {
	protected static $post_ids = array();
	protected static $cat_ids  = array();
	protected $taxonomy        = 'category';

	public static function wpSetUpBeforeClass( $factory ) {
		self::$post_ids = $factory->post->create_many( 5 );
		self::$cat_ids  = $factory->category->create_many( 3 );
	}

	public function test_get_primary_taxonomies() {
		$taxonomies = Primary_Term_Public::get_instance()->get_primary_taxonomies( '' );
		$this->assertEquals( 1, count( $taxonomies ) );

		add_filter(
			'primary_term_taxonomies',
			function ( $taxonomies ) {
				$taxonomies[] = array(
					'title' => 'Category',
					'name'  => 'category',
				);
				return $taxonomies;
			}
		);

		$taxonomies = Primary_Term_Public::get_instance()->get_primary_taxonomies();
		$this->assertEquals( 2, count( $taxonomies ) );
	}
}
