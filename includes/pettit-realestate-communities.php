<?

add_action( 'init', 'community_post_type' );
function community_post_type() {
    $labels = array(
        'name' => 'Communities',
        'singular_name' => 'Community',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Community',
        'edit_item' => 'Edit Community',
        'new_item' => 'New Community',
        'view_item' => 'View Community',
        'search_items' => 'Search Communities',
        'not_found' =>  'No Communities found',
        'not_found_in_trash' => 'No Communities in the trash',
        'parent_item_colon' => 'Parent Areas:',
    );

    register_post_type( 'community', array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'exclude_from_search' => true,
      'query_var' => true,
      // 'rewrite' => [
      //   'slug' => '/',
      //   'with_front' => false
      // ],
      'rewrite' => false,
      'show_in_rest'       => true,
      // 'parent_item_colon' => 'Parent Areas:',
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => true,
      'menu_position' => 11,
      'menu_icon' => 'dashicons-admin-multisite',
      'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes'),// , 'page-attributes'

       'register_meta_box_cb' => 'pettit_register_community_meta_boxes' // 'register_community_meta_boxes', // community_meta_boxes Callback function for custom metaboxes
    ) );
}


// function community_rewrites() {
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', 'bottom');
//   add_rewrite_rule( '([^/]+)/embed/?$', 'index.php?community=$matches[1]&embed=true', 'bottom');
//   add_rewrite_rule( '([^/]+)/trackback/?$', 'index.php?community=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '([^/]+)/page/?([0-9]{1,})/?$', 'index.php?community=$matches[1]&paged=$matches[2]', 'bottom');
//   add_rewrite_rule( '([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?community=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '([^/]+)(?:/([0-9]+))?/?$', 'index.php?community=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', 'bottom');
// }
// add_action( 'init', 'community_rewrites' );

// function community_permalinks( $post_link, $post, $leavename ) {
//   if ( isset( $post->post_type ) && 'community' == $post->post_type ) {
//       $post_link = home_url( $post->post_name );
//   }
//   if ( isset( $post->post_type ) && 'popular_area' == $post->post_type ) {
//     $post_link = home_url( $post->post_name );
//   }
//   return $post_link;
// }
// add_filter( 'post_type_link', 'community_permalinks', 10, 3 );




/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */


// META BOXES

function pettit_register_community_meta_boxes() {
  add_meta_box( 'pettit_community_form', 'Additional Info', 'pettit_community_form', 'community', 'normal', 'high' );
}

function pettit_community_form($post) {
  $market_id = get_post_meta( $post->ID, 'market_id', true );
  $short_title  = get_post_meta( $post->ID, 'short_title', true );
  wp_nonce_field( 'community', 'community' );
  ?>

<table class="form-table">
    <tr>
        <th><label for="short_title" class="market_id_label">Short Title</label></th>
        <td>					
            <input type="text" value="<?php echo $short_title; ?>" name="short_title" id="short_title"  size="40" />
            <p class="description">Please provide a short title that will be used for menu links.</p>
        </td>
    </tr>    
    <tr>
        <th><label for="market_id" class="market_id_label">Market Id</label></th>
        <td>					
            <input type="text" value="<?php echo $market_id; ?>" name="market_id" id="market_id" size="40" />
            <p class="description"></p>
        </td>
    </tr>

</table>


  <?php
}

add_action( 'save_post', 'pettit_community_save_post' );
function pettit_community_save_post( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! empty( $_POST['community'] ) && ! wp_verify_nonce( $_POST['community'], 'community' ) )
        return;

    if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! wp_is_post_revision( $post_id ) && 'community' == get_post_type( $post_id ) ) {
        remove_action( 'save_post', 'pettit_community_save_post' );

        wp_update_post( array(
            'ID' => $post_id,
            // 'post_title' => 'Community - ' . $post_id
        ) );

        add_action( 'save_post', 'pettit_community_save_post' );
    }

    if ( isset($_POST['short_title']) ) {        
        update_post_meta($post_id, 'short_title', sanitize_text_field( $_POST['short_title']));      
    }  
    if ( isset($_POST['market_id']) ) {        
        update_post_meta($post_id, 'market_id', sanitize_text_field( $_POST['market_id']));      
    }      

}



add_filter( 'manage_edit-community_columns', 'community_edit_columns' );
function community_edit_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'short-title' => 'Short Title',
        'author' => 'Posted by',
        'date' => 'Date'
    );

    return $columns;
}

add_action( 'manage_community_posts_custom_column', 'community_columns', 10, 2 );
function community_columns( $column, $post_id ) {
    $short_title = get_post_meta( $post_id, 'short_title', true );
    switch ( $column ) {

        case 'short-title':
            if ( ! empty( $short_title ) )
                echo $short_title;
            break;

        // case 'testimonial-link':
        //     if ( ! empty( $testimonial_data['link'] ) )
        //         echo $testimonial_data['link'];
        //     break;
    }
}
