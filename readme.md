# WordPress Primary Term
Contributors: dipesh.kakadiya
Tags: term, primary
Requires at least: 4.5
Requires PHP: 5.6
Tested up to: 5.3
Stable tag: 1.0
License: GPLv2

Allows you to choose primary term for posts and custom post types.

## Description

Allows you to choose primary taxonomy for posts and custom post types.
This plugin only work with WordPress Classic Editor.

## Installation
1. Add the plugin's folder in the WordPress' plugin directory.
2. Activate the plugin.
3. Now you will have the ability to make any term a primary term for the post

## Developers' Notes

if you want to filter post and custom post types based on their primary categories.

For primary term query, Meta key will be `_primary_<taxonomy>`
Ex For Category filter key will be `_primary_category`
```
/**
 * This snippet fetch 10 published posts which have category ID 5
 * marked as primary category
 */
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => '10',
    'meta_query' => array(
        'relation' => 'AND',
        'array(
            'key' => '_primary_category',
            'value' => '5',
            'compare' => '='
        ),
    ),
);
$main_query = new WP_Query( $args );

/**
 * This snippet fetch 10 published posts which have category ID 5 or 2
 * marked as primary category
 */
 $args = array(
     'post_type' => 'post',
     'post_status' => 'publish',
     'posts_per_page' => '10',
     'meta_query' => array(
         'relation' => 'AND',
         array(
             'key' => '_primary_category',
             'value' => '5,2',
             'compare' => 'IN'
         ),
     ),
 );
 $main_query = new WP_Query( $args );

```

## Template functions

### Get a post Primary term link

`wppt_get_the_primary_term( $taxonomy, $id, $before, $after );`

$taxonomy : Taxonomy name.  
$id       : Post ID or post object. Defaults to global $post [ Optional ] .   
$before   : Before link [ Optional ].   
$after    : After link [ Optional ].   

### Get a post Primary term object

`wppt_get_primary_term( $taxonomy, $id );`

$taxonomy : Taxonomy name.  
$id       : Post ID or post object. Defaults to global $post [ Optional ].  

### Get a post Primary term Id

`wppt_get_primary_term_id( $taxonomy, $id );`

$taxonomy : Taxonomy name.  
$id       : Post ID or post object. Defaults to global $post [ Optional ].   

### Get taxonomy array which are support primary term
`wppt_get_primary_taxonomies();`


## Changelog

### 1.0
* Initial Development.
