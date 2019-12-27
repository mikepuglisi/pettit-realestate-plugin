<?

add_action( 'init', 'popular_area_post_type' );

function popular_area_post_type() {
    $labels = array(
        'name' => 'Popular Areas',
        'singular_name' => 'Popular Area',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Popular Area',
        'edit_item' => 'Edit Popular Area',
        'new_item' => 'New Popular Area',
        'view_item' => 'View Popular Area',
        'search_items' => 'Search Popular Areas',
        'not_found' =>  'No Popular Areas found',
        'not_found_in_trash' => 'No Popular Areas in the trash',
        'parent_item_colon' => '',
    );

    register_post_type( 'popular_area', array(
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
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-awards',
        'supports' => array( 'title', 'editor', 'thumbnail'),// , 'page-attributes'
        'register_meta_box_cb' => 'pettit_register_popular_area_meta_boxes', // areas_meta_boxes Callback function for custom metaboxes
    ) );
}

// function popular_area_rewrites() {
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/attachment/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', 'bottom');
//   add_rewrite_rule( '([^/]+)/embed/?$', 'index.php?popular_area=$matches[1]&embed=true', 'bottom');
//   add_rewrite_rule( '([^/]+)/trackback/?$', 'index.php?popular_area=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '([^/]+)/page/?([0-9]{1,})/?$', 'index.php?popular_area=$matches[1]&paged=$matches[2]', 'bottom');
//   add_rewrite_rule( '([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?popular_area=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '([^/]+)(?:/([0-9]+))?/?$', 'index.php?popular_area=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', 'bottom');
//   add_rewrite_rule( '[^/]+/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', 'bottom');
// }
// add_action( 'init', 'popular_area_rewrites' );

// function popular_area_permalinks( $post_link, $post, $leavename ) {
//   if ( isset( $post->post_type ) && 'popular_area' == $post->post_type ) {
//       $post_link = home_url( $post->post_name );
//   }
//   if ( isset( $post->post_type ) && 'popular_area' == $post->post_type ) {
//     $post_link = home_url( $post->post_name );
//   }

//   return $post_link;
// }
// add_filter( 'post_type_link', 'popular_area_permalinks', 10, 3 );


function pettit_register_popular_area_meta_boxes() {
  add_meta_box( 'pettit_popular_area_form', 'Additional Info', 'pettit_popular_area_form', 'popular_area', 'normal', 'high' );
}

function pettit_popular_area_form($post) {
  
  $short_title   = get_post_meta( $post->ID, 'short_title', true );
  wp_nonce_field( 'popular_area', 'popular_area' );
  ?>

<table class="form-table">
    <tr>
        <th><label for="short_title" class="market_id_label">Short Title</label></th>
        <td>					
            <input type="text" value="<?php echo $short_title; ?>" name="short_title" id="short_title"  size="40" />
            <p class="description">Please provide a short title that will be used for menu links.</p>
        </td>
    </tr>    
</table>


  <?php
}

add_action( 'save_post', 'pettit_popular_area_save_post' );
function pettit_popular_area_save_post( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! empty( $_POST['popular_area'] ) && ! wp_verify_nonce( $_POST['popular_area'], 'popular_area' ) )
        return;

    if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! wp_is_post_revision( $post_id ) && 'popular_area' == get_post_type( $post_id ) ) {
        remove_action( 'save_post', 'pettit_popular_area_save_post' );

        wp_update_post( array(
            'ID' => $post_id,
            // 'post_title' => 'Community - ' . $post_id
        ) );

        add_action( 'save_post', 'pettit_popular_area_save_post' );
    }

    if ( isset($_POST['short_title']) ) {        
        update_post_meta($post_id, 'short_title', sanitize_text_field( $_POST['short_title']));      
    }  
  

    // if ( ! empty( $_POST['popular_area'] ) ) {
    //     $popular_area_data['market_id'] = ( empty( $_POST['popular_area']['market_id'] ) ) ? '' : sanitize_text_field( $_POST['popular_area']['market_id'] );
    //    // $popular_area_data['source'] = ( empty( $_POST['popular_area']['source'] ) ) ? '' : sanitize_text_field( $_POST['popular_area']['source'] );
    //    // $popular_area_data['link'] = ( empty( $_POST['popular_area']['link'] ) ) ? '' : esc_url( $_POST['popular_area']['link'] );

    //     update_post_meta( $post_id, '_popular_area', $popular_area_data );
    // } else {
    //     delete_post_meta( $post_id, '_popular_area' );
    // }
}



add_filter( 'manage_edit-popular_area_columns', 'popular_area_edit_columns' );
function popular_area_edit_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'short-title' => 'Short Title!',
        'author' => 'Posted by',
        'date' => 'Date'
    );

    return $columns;
}

add_action( 'manage_popular_area_posts_custom_column', 'popular_area_columns', 10, 2 );
function popular_area_columns( $column, $post_id ) {
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




if ( ! function_exists('popular_area_shortcode') ) {

  function popular_area_shortcode() {
    $args   =   array(
                'post_type'         =>  'popular_area',
                'post_status'       =>  'publish',
                'order' => 'ASC',
                'posts_per_page' => 8,
                );

      $postslist = new WP_Query( $args );
      global $post;
      $events = "";

      if ( $postslist->have_posts() ) :
      $events   .= '<div class="container px-lg-0"><div class="popular-areas-parent row">'; // <div class="h2 fancy-text gold text-primary mb-4"><strong>POPULAR</strong> AREAS</div>
          while ( $postslist->have_posts() ) : $postslist->the_post();
              if(class_exists('MultiPostThumbnails')) {
                $thumb = MultiPostThumbnails::has_post_thumbnail('popular_area', 'thumbnail-image');
                $thumbnailImage = MultiPostThumbnails::get_the_post_thumbnail('popular_area', 'thumbnail-image', $postslist->post->ID, 'popular_area-thumbnail-image-thumbnail');
              }

              $events    .= '<div class="col-12 col-lg-3 px-1 my-1">';
              $events    .= '<div><a class="popular-areas-anchor aspect ratio-16x9" href="'. get_permalink() .'">' .  $thumbnailImage . '<span class="text-white popular-areas-image-text position-absolute"><strong>' . get_the_title() . '</strong></span>' .'</a></div>';
              $events    .= '</div>';
          endwhile;
          wp_reset_postdata();
          $events  .= '</div></div>';
      endif;
      return $events;
  }
  add_shortcode( 'pettit_popular_areas', 'popular_area_shortcode' );
}
add_action('init', 'thumbnail_image_meta_box');
function thumbnail_image_meta_box() {
  if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(array(
      'label' => 'Thumbnail Image',
      'id' => 'thumbnail-image',
      'post_type' => 'popular_area'
    ));
    add_image_size('popular_area-thumbnail-image-thumbnail', 576, 384);
  }
}

