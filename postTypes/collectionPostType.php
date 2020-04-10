<?php
////////////////////////////////////////////////////////////////////////////////////
// Register Custom Taxonomy  COLLECTION
////////////////////////////////////////////////////////////////////////////////////

function collection() {
	$labels = array(
		'name'                  => _x( 'Collection', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Collection', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Collection', 'text_domain' ),
		'name_admin_bar'        => __( 'Collection', 'text_domain' ),
		'archives'              => __( 'Collection Archive', 'text_domain' ),
		'attributes'            => __( 'Collection Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Collection Item:', 'text_domain' ),
		'all_items'             => __( 'All collections', 'text_domain' ),
		'add_new_item'          => __( 'Add a collection', 'text_domain' ),
		'add_new'               => __( 'Add a new collection', 'text_domain' ),
		'new_item'              => __( 'New collection', 'text_domain' ),
		'edit_item'             => __( 'Edit collection', 'text_domain' ),
		'update_item'           => __( 'Update collection', 'text_domain' ),
		'view_item'             => __( 'See collection', 'text_domain' ),
		'view_items'            => __( 'See collections', 'text_domain' ),
		'search_items'          => __( 'Search a collection', 'text_domain' ),
		'not_found'             => __( 'No match', 'text_domain' ),
		'not_found_in_trash'    => __( 'No match in trash', 'text_domain' ),
		'featured_image'        => __( 'Featured image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Add into collection', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this  collection', 'text_domain' ),
		'items_list'            => __( 'Collection list', 'text_domain' ),
		'items_list_navigation' => __( 'Navigation list of  collection ', 'text_domain' ),
		'filter_items_list'     => __( 'Filter  collection list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( ' collection', 'text_domain' ),
		'description'           => __( 'Create all collection for your products', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
		'taxonomies'            => array( '' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-businessman',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
        'show_in_rest'          => true,
	);
	register_post_type( 'collection', $args );
}
add_action( 'init', 'collection', 0 );
?>