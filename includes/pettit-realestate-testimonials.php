<?php

add_action( 'init', 'testimonials_post_type' );
function testimonials_post_type() {
    $labels = array(
        'name' => 'Testimonials',
        'singular_name' => 'Testimonial',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Testimonial',
        'edit_item' => 'Edit Testimonial',
        'new_item' => 'New Testimonial',
        'view_item' => 'View Testimonial',
        'search_items' => 'Search Testimonials',
        'not_found' =>  'No Testimonials found',
        'not_found_in_trash' => 'No Testimonials in the trash',
        'parent_item_colon' => '',
    );

    register_post_type( 'testimonials', array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        // 'rewrite' => [
        //   'slug' => '/',
        //   'with_front' => true
        // ],
        'capability_type' => 'post',
        'has_archive' => 'testimonials',
        'hierarchical' => false,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-format-status',
        'supports' => array( 'editor', 'title', 'thumbnail' ),
        'show_in_rest'       => true,
        'register_meta_box_cb' => 'testimonials_meta_boxes', // Callback function for custom metaboxes
    ) );
}

// metaboxes

function testimonials_meta_boxes() {
  add_meta_box( 'testimonials_form', 'Testimonial Details', 'testimonials_form', 'testimonials', 'normal', 'high' );
}

function testimonials_form() {
  $post_id = get_the_ID();
  $testimonial_data = get_post_meta( $post_id, '_testimonial', true );
  $client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
  $source = ( empty( $testimonial_data['source'] ) ) ? '' : $testimonial_data['source'];
  // $link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];

  wp_nonce_field( 'testimonials', 'testimonials' );
  ?>
  <p>
      <label>Client's Name (Required)</label><br />
      <input type="text" required value="<?php echo $client_name; ?>" name="testimonial[client_name]" size="40" />
  </p>
  <p>
      <label>Business/Site Name (Required)</label><br />
      <input type="text" required value="<?php echo $source; ?>" name="testimonial[source]" size="40" />
  </p>

  <?php
}

add_action( 'save_post', 'testimonials_save_post' );
function testimonials_save_post( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! empty( $_POST['testimonials'] ) && ! wp_verify_nonce( $_POST['testimonials'], 'testimonials' ) )
        return;

    if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! wp_is_post_revision( $post_id ) && 'testimonials' == get_post_type( $post_id ) ) {
        remove_action( 'save_post', 'testimonials_save_post' );

        wp_update_post( array(
            'ID' => $post_id
        ) );

        add_action( 'save_post', 'testimonials_save_post' );
    }

    if ( ! empty( $_POST['testimonial'] ) ) {
        $testimonial_data['client_name'] = ( empty( $_POST['testimonial']['client_name'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_name'] );
        $testimonial_data['source'] = ( empty( $_POST['testimonial']['source'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['source'] );
       // $testimonial_data['link'] = ( empty( $_POST['testimonial']['link'] ) ) ? '' : esc_url( $_POST['testimonial']['link'] );

        update_post_meta( $post_id, '_testimonial', $testimonial_data );
    } else {
        delete_post_meta( $post_id, '_testimonial' );
    }
}


add_filter( 'manage_edit-testimonials_columns', 'testimonials_edit_columns' );
function testimonials_edit_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'testimonial' => 'Testimonial',
        'testimonial-client-name' => 'Client\'s Name',
        'testimonial-source' => 'Business/Site',

        'author' => 'Posted by',
        'date' => 'Date'
    );

    return $columns;
}

add_action( 'manage_posts_custom_column', 'testimonials_columns', 10, 2 );
function testimonials_columns( $column, $post_id ) {
    $testimonial_data = get_post_meta( $post_id, '_testimonial', true );
    switch ( $column ) {
        case 'testimonial':
            the_excerpt();
            break;
        case 'testimonial-client-name':
            if ( ! empty( $testimonial_data['client_name'] ) )
                echo $testimonial_data['client_name'];
            break;
        case 'testimonial-source':
            if ( ! empty( $testimonial_data['source'] ) )
                echo $testimonial_data['source'];
            break;
        // case 'testimonial-link':
        //     if ( ! empty( $testimonial_data['link'] ) )
        //         echo $testimonial_data['link'];
        //     break;
    }
}


/**
 * Display a testimonial
 *
 * @param  int $post_per_page  The number of testimonials you want to display
 * @param  string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param  array $testimonial_id  The ID or IDs of the testimonial(s), comma separated
 *
 * @return  string  Formatted HTML
 */
function get_testimonial( $posts_per_page = 1, $orderby = 'none', $testimonial_id = null ) {
  $args = array(
      'posts_per_page' => (int) $posts_per_page,
      'post_type' => 'testimonials',
      'orderby' => $orderby,
      'no_found_rows' => true,
  );
  if ( $testimonial_id )
      $args['post__in'] = array( $testimonial_id );

  $query = new WP_Query( $args  );

  $testimonials = '';
  if ( $query->have_posts() ) {
      while ( $query->have_posts() ) : $query->the_post();
          $post_id = get_the_ID();
          $testimonial_data = get_post_meta( $post_id, '_testimonial', true );
          $client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
          $source = ( empty( $testimonial_data['source'] ) ) ? '' : '' . $testimonial_data['source'];
         // $link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
          $cite = $client_name . $source;

          $testimonials .= '<aside class="testimonial">';
          $testimonials .= '<span class="quote">&ldquo;</span>';
          $testimonials .= '<div class="entry-content">';
          $testimonials .= '<p class="testimonial-text">' . get_the_content() . '<span></span></p>';
          $testimonials .= '<p class="testimonial-client-name"><cite>' . $cite . '</cite>';
          $testimonials .= '</div>';
          $testimonials .= '</aside>';

      endwhile;
      wp_reset_postdata();
  }

  return $testimonials;
}



/**
 * Display a testimonial
 *
 * @param  int $post_per_page  The number of testimonials you want to display
 * @param  string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param  array $testimonial_id  The ID or IDs of the testimonial(s), comma separated
 *
 * @return  string  Formatted HTML
 */
function get_testimonial_slider( $posts_per_page = 1, $orderby = 'none', $testimonial_id = null ) {
  $args = array(
      'posts_per_page' => (int) $posts_per_page,
      'post_type' => 'testimonials',
      'orderby' => $orderby,
      'no_found_rows' => true,
  );
  if ( $testimonial_id )
      $args['post__in'] = array( $testimonial_id );

  $query = new WP_Query( $args  );

  $testimonials = '';

  $testimonials .= '<div id="testimonialSliderControls" class="carousel slide" data-ride="carousel">';

  $testimonials .= '<div class="carousel-inner" role="listbox">';




  if ( $query->have_posts() ) {
      while ( $query->have_posts() ) : $query->the_post();
          $post_id = get_the_ID();
          $testimonial_data = get_post_meta( $post_id, '_testimonial', true );
          $client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
          $source = ( empty( $testimonial_data['source'] ) ) ? '' : ' ' . $testimonial_data['source'];
         // $link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
          $cite = $client_name . $source;
        $testimonial_text = get_the_content();
        $substring = wp_trim_words($testimonial_text, 20);
          $testimonials .= '<div class="carousel-item"><aside class="testimonial">';
          $testimonials .= '<span class="quote">&ldquo;</span>';
          $testimonials .= '<div class="entry-content">';
          if (strlen($testimonial_text) > 200) {
            $testimonials .= '<p class="testimonial-text">' . $substring . ' <span><strong><a class="text-primary text-nowrap" href="/testimonials">Read more</a></span></p></strong>';
        } else {
            $testimonials .= '<p class="testimonial-text">' . $testimonial_text . '<span></span></p>';
        }
          // $testimonials .= '<p class="testimonial-client-name"><cite>' . $cite . '</cite>';
          $testimonials .= '<div class="testimonial-client-name"><p class="testimonial-client-name-text"><cite><strong>' . $client_name . '</strong></cite></p>';
          $testimonials .= '<p class="testimonial-client-source-text"><cite>' . $source . '</cite></p></div>';
          $testimonials .= '</div>';
          $testimonials .= '</aside></div>';
           
        //   $testimonials .= '<p>Length:' .strlen(get_the_content()). '</p>';

      endwhile;
      $testimonials .= '</div>';

      $testimonials .= '<a class="carousel-control-prev" href="#testimonialSliderControls" role="button" data-slide="prev">';

      $testimonials .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';

      $testimonials .= '<span class="sr-only">Previous</span>';

      $testimonials .= '</a>';

      $testimonials .= '<a class="carousel-control-next" href="#testimonialSliderControls" role="button" data-slide="next">';

      $testimonials .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';

      $testimonials .= '<span class="sr-only">Next</span>';

      $testimonials .= '</a>';

      

      $testimonials .= '</div><!-- .carousel -->';
      $testimonials .= '<script>';
      $testimonials .= 'jQuery( "#testimonialSliderControls .carousel-item" ).first().addClass( "active" );';
      $testimonials .= '</script>';

      wp_reset_postdata();
  }

  return $testimonials;
}


add_shortcode( 'testimonial', 'testimonial_shortcode' );
/**
 * Shortcode to display testimonials
 *
 * [testimonial posts_per_page="1" orderby="none" testimonial_id=""]
 */
function testimonial_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'posts_per_page' => '1',
        'orderby' => 'none',
        'testimonial_id' => '',
    ), $atts ) );

    return get_testimonial( $posts_per_page, $orderby, $testimonial_id );
}



/**
 * Testimonials Widget
 */
class Testimonial_Widget extends WP_Widget {
  public function __construct() {
      $widget_ops = array( 'classname' => 'testimonial_widget', 'description' => 'Display testimonial post type' );
      parent::__construct( 'testimonial_widget', 'Pettit: Testimonials', $widget_ops );
  }

  public function widget( $args, $instance ) {
      extract( $args );
      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $posts_per_page = (int) $instance['posts_per_page'];
      $orderby = strip_tags( $instance['orderby'] );
      $testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );

      echo $before_widget;

      if ( ! empty( $title ) )
          echo $before_title . $title . $after_title;

      echo get_testimonial( $posts_per_page, $orderby, $testimonial_id );

      echo $after_widget;
  }

  public function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      $instance['title'] = strip_tags( $new_instance['title'] );
      $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
      $instance['orderby'] = strip_tags( $new_instance['orderby'] );
      $instance['testimonial_id'] = ( null == $new_instance['testimonial_id'] ) ? '' : strip_tags( $new_instance['testimonial_id'] );

      return $instance;
  }

  public function form( $instance ) {
      $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'posts_per_page' => '1', 'orderby' => 'none', 'testimonial_id' => null ) );
      $title = strip_tags( $instance['title'] );
      $posts_per_page = (int) $instance['posts_per_page'];
      $orderby = strip_tags( $instance['orderby'] );
      $testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );
      ?>
      <p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">Number of Testimonials: </label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>" />
      </p>

      <p><label for="<?php echo $this->get_field_id( 'orderby' ); ?>">Order By</label>
      <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
          <option value="none" <?php selected( $orderby, 'none' ); ?>>None</option>
          <option value="ID" <?php selected( $orderby, 'ID' ); ?>>ID</option>
          <option value="date" <?php selected( $orderby, 'date' ); ?>>Date</option>
          <option value="modified" <?php selected( $orderby, 'modified' ); ?>>Modified</option>
          <option value="rand" <?php selected( $orderby, 'rand' ); ?>>Random</option>
      </select></p>

      <p><label for="<?php echo $this->get_field_id( 'testimonial_id' ); ?>">Testimonial ID</label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'testimonial_id' ); ?>" name="<?php echo $this->get_field_name( 'testimonial_id' ); ?>" type="text" value="<?php echo $testimonial_id; ?>" /></p>
      <?php
  }
}



/**
 * Testimonials Widget
 */
class Testimonial_Slider_Widget extends WP_Widget {
  public function __construct() {
      $widget_ops = array( 'classname' => 'testimonial_slider_widget', 'description' => 'Display testimonial posts in slider' );
      parent::__construct( 'testimonial_slider_widget', 'Pettit: Testimonial Slider', $widget_ops );
  }

  public function widget( $args, $instance ) {
      extract( $args );
      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $posts_per_page = (int) $instance['posts_per_page'];
      $orderby = strip_tags( $instance['orderby'] );
     // $testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );

      echo $before_widget;

      if ( ! empty( $title ) )
          echo $before_title . $title . $after_title;

      echo get_testimonial_slider( $posts_per_page, $orderby, $testimonial_id );

      echo $after_widget;
  }

  public function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      $instance['title'] = strip_tags( $new_instance['title'] );
      $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
      $instance['orderby'] = strip_tags( $new_instance['orderby'] );
     // $instance['testimonial_id'] = ( null == $new_instance['testimonial_id'] ) ? '' : strip_tags( $new_instance['testimonial_id'] );

      return $instance;
  }

  public function form( $instance ) {
      $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'posts_per_page' => '1', 'orderby' => 'none', 'testimonial_id' => null ) );
      $title = strip_tags( $instance['title'] );
      $posts_per_page = (int) $instance['posts_per_page'];
      $orderby = strip_tags( $instance['orderby'] );
      $testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );
      ?>
      <p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">Number of Testimonials: </label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>" />
      </p>

      <p><label for="<?php echo $this->get_field_id( 'orderby' ); ?>">Order By</label>
      <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
          <option value="none" <?php selected( $orderby, 'none' ); ?>>None</option>
          <option value="ID" <?php selected( $orderby, 'ID' ); ?>>ID</option>
          <option value="date" <?php selected( $orderby, 'date' ); ?>>Date</option>
          <option value="modified" <?php selected( $orderby, 'modified' ); ?>>Modified</option>
          <option value="rand" <?php selected( $orderby, 'rand' ); ?>>Random</option>
      </select></p>

      <?php
  }
}


add_action( 'widgets_init', 'register_testimonials_widget' );
/**
* Register widget
*
* This functions is attached to the 'widgets_init' action hook.
*/
function register_testimonials_widget() {
  register_widget( 'Testimonial_Widget' );
  register_widget( 'Testimonial_Slider_Widget' );
}

function html_widget_title( $title ) {
  //HTML tag opening/closing brackets
  $title = str_replace( '[', '<', $title );
  $title = str_replace( '[/', '</', $title );

  //<strong></strong>
  $title = str_replace( 's]', 'strong>', $title );
  // <b></b>
  $title = str_replace( 'b]', 'b>', $title );
  //<em></em>
  $title = str_replace( 'e]', 'em>', $title );

  return $title;
}
add_filter( 'widget_title', 'html_widget_title' );