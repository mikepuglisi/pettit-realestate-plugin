<?php

// https://kellenmace.com/remove-custom-post-type-slug-from-permalinks/
/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */

function pettit_remove_custom_post_type_slug( $post_link, $post ) {
  if ( 'popular_area' === $post->post_type && 'publish' === $post->post_status ) {
      $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
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
	$query->set( 'post_type', array( 'post', 'page', 'popular_area' ) );
}
add_action( 'pre_get_posts', 'pettit_add_custom_post_type_post_names_to_main_query' );