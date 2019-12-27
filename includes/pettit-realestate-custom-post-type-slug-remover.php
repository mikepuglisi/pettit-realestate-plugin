<?php

// https://kellenmace.com/remove-custom-post-type-slug-from-permalinks/
/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */

function pettit_remove_custom_post_type_slug( $post_link, $post ) {
  if ( ('popular_area' === $post->post_type || 'community' === $post->post_type)  && 'publish' === $post->post_status ) {
     //  $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
     $post_link = home_url( $post->post_name );
  } 
  return $post_link;
}

add_filter( 'post_type_link', 'pettit_remove_custom_post_type_slug', 10, 2 );

/**
 * Have WordPress match postname to any of our public post types (post, page, race).
 * All of our public post types can have /post-name/ as the slug, so they need to be unique across all posts.
 * By default, WordPress only accounts for posts and pages where the slug is /post-name/.
 *
 * @param $query The current query.
 */
function pettit_add_custom_post_type_post_names_to_main_query( $query ) {
	// Bail if this is not the main query.
	if ( ! $query->is_main_query() ) {
		return;
	}
	// Bail if this query doesn't match our very specific rewrite rule.
	if ( ! isset( $query->query['page'] ) || 2 !== count( $query->query ) ) {
		return;
	}
	// Bail if we're not querying based on the post name.
	if ( empty( $query->query['name'] ) ) {
		return;
	}
	// Add CPT to the list of post types WP will include when it queries based on the post name.
	$query->set( 'post_type', array( 'post', 'page', 'popular_area', 'community' ) );
}
add_action( 'pre_get_posts', 'pettit_add_custom_post_type_post_names_to_main_query' );

// // // https://wordpress.stackexchange.com/questions/203951/remove-slug-from-custom-post-type-post-urls
// // function pettit_realestate_remove_popular_area_slug( $post_link, $post, $leavename ) {
// //   if ( 'popular_area' == $post->post_type && 'publish' == $post->post_status ) {
// //     $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
// //   }
// //   if ( 'community' == $post->post_type && 'publish' == $post->post_status ) {
// //     $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
// //   }
// //   return $post_link;
// // }
// // // add_filter( 'post_type_link', 'pettit_realestate_remove_popular_area_slug', 10, 3 );



// function pettit_realestate_parse_request( $query ) {
//   if ( ! empty( $query->query['name'] ) || ! empty( $query->query['attachment'] ) ) {
//     //  $query->set( 'post_type', array( 'post', 'popular_area', 'community', 'testimonials', 'page' ) );
//   }
// }
// add_action( 'pre_get_posts', 'pettit_realestate_parse_request' );


function prevent_slug_duplicates( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
  $check_post_types = array(
      'post',
      'page',
      'popular_area',
      'community'
  );

  if ( ! in_array( $post_type, $check_post_types ) ) {
      return $slug;
  }

  if ( 'popular_area' == $post_type ) {
      // Saving a popular_area post, check for duplicates in POST or PAGE post types
      $post_match = get_page_by_path( $slug, 'OBJECT', 'post' );
      $page_match = get_page_by_path( $slug, 'OBJECT', 'page' );

      if ( $post_match || $page_match ) {
          $slug .= '-popular_area-duplicate';
      }
  } else if ( 'community' == $post_type ) {
    // Saving a popular_area post, check for duplicates in POST or PAGE post types
    $post_match = get_page_by_path( $slug, 'OBJECT', 'post' );
    $page_match = get_page_by_path( $slug, 'OBJECT', 'page' );

    if ( $post_match || $page_match ) {
        $slug .= '-community-duplicate';
    }
  } else {
      // Saving a POST or PAGE, check for duplicates in popular_area post type
      $popular_area_match = get_page_by_path( $slug, 'OBJECT', 'popular_area' );

      if ( $popular_area_match ) {
          $slug .= '-popular_area-duplicate';
      }

      $community_match = get_page_by_path( $slug, 'OBJECT', 'community' );

      if ( $community_match ) {
          $slug .= '-community-duplicate';
      }
  }

  return $slug;
}
add_filter( 'wp_unique_post_slug', 'prevent_slug_duplicates', 10, 6 );