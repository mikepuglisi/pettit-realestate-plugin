<?php

if ( ! function_exists( 'create_areas_taxonomy' ) ) {

// Register Custom Taxonomy
function create_areas_taxonomy() {

  $labels = array(
    'name'                       => _x( 'Areas', 'Taxonomy General Name', 'text_domain' ),
    'singular_name'              => _x( 'Area', 'Taxonomy Singular Name', 'text_domain' ),
    'menu_name'                  => __( 'Areas', 'text_domain' ),
    'all_items'                  => __( 'All Items', 'text_domain' ),
    'parent_item'                => __( 'Parent Item', 'text_domain' ),
    'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
    'new_item_name'              => __( 'New Item Name', 'text_domain' ),
    'add_new_item'               => __( 'Add New Item', 'text_domain' ),
    'edit_item'                  => __( 'Edit Item', 'text_domain' ),
    'update_item'                => __( 'Update Item', 'text_domain' ),
    'view_item'                  => __( 'View Item', 'text_domain' ),
    'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
    'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
    'popular_items'              => __( 'Popular Items', 'text_domain' ),
    'search_items'               => __( 'Search Items', 'text_domain' ),
    'not_found'                  => __( 'Not Found', 'text_domain' ),
    'no_terms'                   => __( 'No items', 'text_domain' ),
    'items_list'                 => __( 'Items list', 'text_domain' ),
    'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'show_in_rest'               => true,
    // 'rewrite' => array(
    //     'slug' => 'areas',
    //     'with_front' => false,
    // ),
  );
  register_taxonomy( 'areas', array( 'community', 'popular_area'), $args );

  wp_insert_term(
    'Palm Beach Gardens',
    'areas'
  );
  wp_insert_term(
    'Jupiter Island',
    'areas'
  );
  wp_insert_term(
    'Jupiter',
    'areas'
  );
  wp_insert_term(
    'Juno Beach',
    'areas'
  );
  wp_insert_term(
    'Singer Island',
    'areas'
  );
  wp_insert_term(
    'Stuart',
    'areas'
  );
  wp_insert_term(
    'Palm Beach',
    'areas'
  );
  wp_insert_term(
    'Hutchinson Island',
    'areas'
  );
}
add_action( 'init', 'create_areas_taxonomy', 0 );

}