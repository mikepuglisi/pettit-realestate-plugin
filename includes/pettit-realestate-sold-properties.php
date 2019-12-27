<?php
add_action( 'init', 'sold_property_post_type' );
function sold_property_post_type() {
    $labels = array(
        'name' => 'Sold Properties',
        'singular_name' => 'Sold Property',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Sold Property',
        'edit_item' => 'Edit Sold Property',
        'new_item' => 'New Sold Property',
        'view_item' => 'View Sold Property',
        'search_items' => 'Search Sold Properties',
        'not_found' =>  'No Sold Properties found',
        'not_found_in_trash' => 'No Sold Properties in the trash',
        'parent_item_colon' => '',
    );

    register_post_type( 'sold_property', array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        // 'rewrite' => [
        //   'slug' => '/',
        //   'with_front' => true
        // ],
        'capability_type' => 'post',
        'has_archive' => false, // 'sold-properties',
        'hierarchical' => false,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-flag',
        'supports' => array(  'thumbnail' ),
        'show_in_rest'       => true,
        'register_meta_box_cb' => 'sold_property_meta_boxes', // Callback function for custom metaboxes
    ) );
}


// add_action( 'template_redirect', 'sold_property_redirect_post' );

// function sold_property_redirect_post() {
//   $queried_post_type = get_query_var('post_type');
//   if (is_singular('sold_property')) { // if ( is_single() && 'sample_post_type' ==  $queried_post_type ) {
//     wp_redirect( '/sold-properties', 301 );
//     exit;
//   }
// }


// metaboxes


function sold_property_meta_boxes() {
    add_meta_box( 'sold_property_form', 'Property Details', 'sold_property_form', 'sold_property', 'normal', 'high' );
}

function sold_property_form($post) {

    //$post_id = get_the_ID();

    $address = get_post_meta( $post->ID, 'address', true );
    $price   = get_post_meta( $post->ID, 'price', true );
    $sqft   = get_post_meta( $post->ID, 'sqft', true );
    $daysOnMarket   = get_post_meta( $post->ID, 'daysOnMarket', true );
    

    // $sold_property_data = get_post_meta( $post_id, '_sold_property', true );
    // $address = ( empty( $sold_property_data['address'] ) ) ? '' : $sold_property_data['address'];
    // $price = ( empty( $sold_property_data['price'] ) ) ? '' : $sold_property_data['price'];
    // $link = ( empty( $sold_property_data['link'] ) ) ? '' : $sold_property_data['link'];

    wp_nonce_field( 'sold_property_nonce', 'sold_property_nonce' );
    ?>


		<table class="form-table">

			<tr>
				<th><label for="address" class="car_name_label">Address <span style="color:red;">*</span></label></th>
				<td>					
                    <input type="text" required value="<?php echo $address; ?>" name="address" id="address" size="40" />
					<p class="description"></p>
                </td>
                
			</tr>
			<tr>
                <th><label for="address" class="car_name_label">Price</label></th>
				<td>					
                    <input type="text" value="<?php echo number_format($price); ?>" name="price" id="price" size="40" />
					<p class="description"></p>
				</td>
			</tr>
			<tr>
				<th><label for="address" class="car_name_label">SQFT</label></th>
				<td>					
                    <input type="text" value="<?php echo number_format($sqft); ?>" name="sqft" id="sqft" size="40" />
					<p class="description"></p>
				</td>
			</tr>
			<tr>
				<th><label for="address" class="car_name_label">Days On Market</label></th>
				<td>					
                    <input type="text" value="<?php echo number_format($daysOnMarket); ?>" name="daysOnMarket" id="daysOnMarket" size="40" />
                    <p class="description">Used for 'Sold in X days' label (when provided)</p> 
				</td>
            </tr>  
                                             
        </table>
        <div><sup style="color:red;">*</sup> Required Field</div>

 
    <?php
  }

add_action( 'save_post', 'sold_property_save_post' );
function sold_property_save_post( $post_id ) {

    

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! empty( $_POST['address'] ) && ! wp_verify_nonce( $_POST['sold_property_nonce'], 'sold_property_nonce' ) )
        return;

    if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! empty( $_POST['address'] ) ) {

        if ( ! wp_is_post_revision( $post_id ) && 'sold_property' == get_post_type( $post_id ) ) {
            remove_action( 'save_post', 'sold_property_save_post' );

            wp_update_post( array(
                'ID' => $post_id,
                'post_title' => sanitize_text_field( $_POST['address'])
            ) );

            add_action( 'save_post', 'sold_property_save_post' );
        }

    
        $address = get_post_meta( $post->ID, 'address', true );
        $price   = get_post_meta( $post->ID, 'price', true );
        $sqft   = get_post_meta( $post->ID, 'sqft', true );
        $daysOnMarket   = get_post_meta( $post->ID, 'daysOnMarket', true );

        if ( isset($_POST['address']) ) {        
            update_post_meta($post_id, 'address', sanitize_text_field( $_POST['address']));      
        }  
    
        if ( isset($_POST['price']) ) {  
            $priceStr = str_replace(',', '', $_POST['price']);
            if (substr($priceStr, 0, 1) === '$') {
                $priceStr = ltrim($priceStr, $priceStr[0]);
            }
            if (is_numeric($priceStr)) {
                update_post_meta($post_id, 'price', sanitize_text_field( $priceStr ));  
            } else {
                $error = 'there was an error';
            }
                
        }
    
        if ( isset($_POST['sqft']) ) {    
            $sqftStr = str_replace(',', '', $_POST['sqft']);
            if (is_numeric($sqftStr)) {
                update_post_meta($post_id, 'sqft',  sanitize_text_field($sqftStr));    
            }    
               
        }

        if ( isset($_POST['daysOnMarket']) ) {   
            $daysOnMarketStr = str_replace(',', '', $_POST['daysOnMarket']);
            if (is_numeric($daysOnMarketStr)) {
                update_post_meta($post_id, 'daysOnMarket',  sanitize_text_field($daysOnMarketStr));  
            }      
                
        }          

        // $sold_property_data['client_name'] = ( empty( $_POST['sold_property']['client_name'] ) ) ? '' : sanitize_text_field( $_POST['sold_property']['client_name'] );
        // $sold_property_data['source'] = ( empty( $_POST['sold_property']['source'] ) ) ? '' : sanitize_text_field( $_POST['sold_property']['source'] );
        // $sold_property_data['link'] = ( empty( $_POST['sold_property']['link'] ) ) ? '' : esc_url( $_POST['sold_property']['link'] );

        // update_post_meta( $post_id, '_sold_property', $sold_property_data );
    }
}



//////////////////////////////////
///////////////////////

  
add_filter( 'manage_edit-sold_property_columns', 'sold_property_edit_columns' );
function sold_property_edit_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',                
        'title' => 'Address',        
        'price' => 'Price',       
        'sqft' => 'Sqft',       
        'date' => 'Date'
    );

    return $columns;
}

add_action( 'manage_sold_property_posts_custom_column', 'sold_property_columns', 10, 2 );
function sold_property_columns( $column, $post_id ) {
    $address = get_post_meta( $post_id, 'address', true );
    $price = get_post_meta( $post_id, 'price', true );
    $sqft = get_post_meta( $post_id, 'sqft', true );
    switch ( $column ) {
        case 'price':
            if ( ! empty( $price ) )
                echo number_format($price);            
            break;
        case 'sqft':            
            if ( ! empty( $sqft ) )
                echo number_format($sqft);            
            break;
        // case 'sold_property-source':
        //     if ( ! empty( $sold_property_data['source'] ) )
        //         echo $sold_property_data['source'];
        //     break;
        // case 'sold_property-link':
        //     if ( ! empty( $sold_property_data['link'] ) )
        //         echo $sold_property_data['link'];
        //     break;
    }
}


/**
 * Display a sold property
 *
 * @param  int $post_per_page  The number of sold properties you want to display
 * @param  string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param  array $sold_property_id  The ID or IDs of the sold property(s), comma separated
 *
 * @return  string  Formatted HTML
 */
function get_sold_properties( $posts_per_page = 1, $orderby = 'none', $sold_property_id = null ) {
    ?>
<div class='row'>
        <?php 
            $args = array(   
            'post_type' => 'sold_property',
            'posts_per_page' => 50,
            'meta_key'     => '_thumbnail_id'         
            );
            $the_query = new WP_Query( $args );
            /*
              'meta_query' => array(
     array(
       'key' => '_thumbnail_id',
       'value' => '?',
       'compare' => 'NOT EXISTS'
     )
  ),
  */
        ?>
          
        <?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();  ?>
        <?php //if ( has_post_thumbnail() ) : ?>
        
        <?php $META_ATTRIBUTES = get_metadata( 'post', get_the_ID(), '', true ); ?>  
        <div class='col col-12 col-md-6 col-lg-6'>
 
           <div class='sold-property-wrapper position-relative mb-2'>
               
            <div class='tag position-absolute'>
              <?php
                if (number_format((float)$META_ATTRIBUTES['daysOnMarket'][0])) { 
              ?>
                  <span class='text-white font-weight-bold px-1'>SOLD IN <?php echo number_format((float)$META_ATTRIBUTES['daysOnMarket'][0]); ?> DAYS</span>
                <?php } else  {?>
                  <span class='text-white font-weight-bold px-1'>SOLD</span>
                <?php }?>
            </div>
               <canvas style="background-image: url(<?php echo the_post_thumbnail_url( 'medium_large' ); ?>);display: block; width: 100%; height: auto; background-size: cover; background-position: center center;" width="522" height="294"></canvas>               
                <?php if (number_format((float)$META_ATTRIBUTES['price'][0])) { ?>
                  <span class='text-white price position-absolute ml-2'>$<?php echo number_format((float)$META_ATTRIBUTES['price'][0]); ?></span>
                <?php } ?>
                <?php  if (number_format((float)$META_ATTRIBUTES['sqft'][0])) { ?> 
                  <span class='text-white square-feet position-absolute mr-2'><?php echo number_format((float)$META_ATTRIBUTES['sqft'][0]); ?> SqFt</span>
                <?php } ?>               
            </div>

        
          <p class='ml-3 text-primary'><?php echo $META_ATTRIBUTES['address'][0]; ?></p>
        </div>
        <?php endwhile; endif;  ?>
      </div>
      <div>
      <?php 
            $args = array(   
            'post_type' => 'sold_property',
            'posts_per_page' => 50,
            'meta_query' => array(
                array(
                  'key' => '_thumbnail_id',
                  'value' => '?',
                  'compare' => 'NOT EXISTS'
                )
             ),       
            );
            $the_query = new WP_Query( $args );
            /*
              'meta_query' => array(
                array(
                'key' => '_thumbnail_id',
                'value' => '?',
                'compare' => 'NOT EXISTS'
                )
            ),
            */
        ?>          
            <?php if ( $the_query->have_posts() ) : ?>
                <div class="h1 mt-3"><strong>Additional Sold Properties</strong></div>
                <ul class="list-group flat-list">                
                    <li class="list-group-item"><strong>Address</strong></li>         
                    <?php while ( $the_query->have_posts() ) : $the_query->the_post();  ?>                                                                   
                        <?php $META_ATTRIBUTES = get_metadata( 'post', get_the_ID(), '', true ); ?>    
                        <li class="list-group-item"><?php echo $META_ATTRIBUTES['address'][0]; ?></li>     
                    <?php endwhile;  ?>
                </ul>
            <?php endif;   ?>
        </div>
        <?php wp_reset_query(); ?>      
<?php    
}
/*
<div class='row'>
          <?php 
            $args = array(   
            'orderby' => 'menu_order',
            'post_type' => 'sold_properties',
            'posts_per_page' => 6
            );
            $the_query = new WP_Query( $args );
          ?>
          
        <?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <?php $META_ATTRIBUTES = get_metadata( 'post', get_the_ID(), '', true ); ?>  
        <div class='col col-12 col-md-6 col-lg-4'>
           <a href='/sold-properties'>
           <div class='sold-property-wrapper position-relative mb-2'>
               
            <div class='tag position-absolute'>
              <?php
                if (number_format((float)$META_ATTRIBUTES['daysOnMarket'][0])) { 
              ?>
                  <span class='text-white font-weight-bold px-1'>SOLD IN <?php echo number_format((float)$META_ATTRIBUTES['daysOnMarket'][0]); ?> DAYS</span>
                <?php } else  {?>
                  <span class='text-white font-weight-bold px-1'>SOLD</span>
                <?php }?>
            </div>
               <canvas style="background-image: url(<?php echo the_post_thumbnail_url( 'large' ); ?>);display: block; width: 100%; height: auto; background-size: cover; background-position: center center;" width="522" height="294"></canvas>               
                <?php if (number_format((float)$META_ATTRIBUTES['price'][0])) { ?>
                  <span class='text-white price position-absolute ml-2'>$<?php echo number_format((float)$META_ATTRIBUTES['price'][0]); ?></span>
                <?php } ?>
                <?php  if (number_format((float)$META_ATTRIBUTES['sqft'][0])) { ?> 
                  <span class='text-white square-feet position-absolute'><?php echo number_format((float)$META_ATTRIBUTES['sqft'][0]); ?> SqFt</span>
                <?php } ?>               
            </div>

               </a>
          <p class='ml-3 text-primary'><?php echo $META_ATTRIBUTES['address'][0]; ?></p>
        </div>
        <?php endwhile; endif; ?>
        <?php wp_reset_query(); ?>
      </div>
      */
// function get_sold_properties( $posts_per_page = 1, $orderby = 'none', $sold_property_id = null ) {
//   $args = array(
//       'posts_per_page' => (int) $posts_per_page,
//       'post_type' => 'sold_property',
//       'orderby' => $orderby,
//       'no_found_rows' => true,
//   );
//   if ( $sold_property_id )
//       $args['post__in'] = array( $sold_property_id );

//   $query = new WP_Query( $args  );

//   $sold_property = '';
//   if ( $query->have_posts() ) {
//       while ( $query->have_posts() ) : $query->the_post();
//           $post_id = get_the_ID();
//           $address = get_post_meta( $post_id, 'address', true );
//           $price   = get_post_meta( $post_id, 'price', true );
//           $sqft   = get_post_meta( $post_id, 'sqft', true );
//           $daysOnMarket   = get_post_meta( $post_id, 'daysOnMarket', true );

//         //   $sold_property_data = get_post_meta( $post_id, '_sold_property', true );
//         //   $client_name = ( empty( $sold_property_data['client_name'] ) ) ? '' : $sold_property_data['client_name'];
//         //   $source = ( empty( $sold_property_data['source'] ) ) ? '' : '' . $sold_property_data['source'];
//         //  // $link = ( empty( $sold_property_data['link'] ) ) ? '' : $sold_property_data['link'];
//         //   $cite = $client_name . $source;

//           $sold_property .= '<aside class="sold property">';
//           $sold_property .= '<span class="quote">&ldquo;</span>';
//           $sold_property .= '<div class="entry-content">';
//           $sold_property .= '<p class="sold property-text">' . $address. '<span></span></p>';          
//           $sold_property .= '</div>';
//           $sold_property .= '</aside>';

//       endwhile;
//       wp_reset_postdata();
//   }

//   return $sold_property;
// }



/**
 * Display a sold property
 *
 * @param  int $post_per_page  The number of sold properties you want to display
 * @param  string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param  array $sold_property_id  The ID or IDs of the sold property(s), comma separated
 *
 * @return  string  Formatted HTML
 */



add_shortcode( 'pettit_sold_properties', 'pettit_sold_property_shortcode' );
/**
 * Shortcode to display sold properties
 *
 * [sold property posts_per_page="1" orderby="none" sold_property_id=""]
 */
function pettit_sold_property_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'posts_per_page' => '50',        
        'sold_property_id' => ''
    ), $atts ) );

    return get_sold_properties( $posts_per_page, $orderby, $sold_property_id );
}


///////////////////
///===========

/**
 * Sold Properties Widget
 */
class sold_property_Widget extends WP_Widget {
    public function __construct() {
        $widget_ops = array( 'classname' => 'sold_property_widget', 'description' => 'Display sold property post type' );
        parent::__construct( 'sold_property_widget', 'Pettit: Sold Properties', $widget_ops );
    }
  
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $posts_per_page = (int) $instance['posts_per_page'];
        $orderby = strip_tags( $instance['orderby'] );
        $sold_property_id = ( null == $instance['sold_property_id'] ) ? '' : strip_tags( $instance['sold_property_id'] );
  
        echo $before_widget;
  
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;
  
        echo get_sold_properties( $posts_per_page, $orderby, $sold_property_id );
  
        echo $after_widget;
    }
  
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = strip_tags( $new_instance['orderby'] );
        $instance['sold_property_id'] = ( null == $new_instance['sold_property_id'] ) ? '' : strip_tags( $new_instance['sold_property_id'] );
  
        return $instance;
    }
  
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'posts_per_page' => '1', 'orderby' => 'none', 'sold_property_id' => null ) );
        $title = strip_tags( $instance['title'] );
        $posts_per_page = (int) $instance['posts_per_page'];
        $orderby = strip_tags( $instance['orderby'] );
        $sold_property_id = ( null == $instance['sold_property_id'] ) ? '' : strip_tags( $instance['sold_property_id'] );
        ?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
  
        <p><label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">Number of Sold Properties: </label>
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
  
        <p><label for="<?php echo $this->get_field_id( 'sold_property_id' ); ?>">Sold Property ID</label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'sold_property_id' ); ?>" name="<?php echo $this->get_field_name( 'sold_property_id' ); ?>" type="text" value="<?php echo $sold_property_id; ?>" /></p>
        <?php
    }
  }
  
  
  
  
  add_action( 'widgets_init', 'register_sold_property_widget' );
  /**
  * Register widget
  *
  * This functions is attached to the 'widgets_init' action hook.
  */
  function register_sold_property_widget() {
    register_widget( 'sold_property_Widget' );
    // register_widget( 'sold_property_Slider_Widget' );
  }
  
  // function html_widget_title( $title ) {
  //   //HTML tag opening/closing brackets
  //   $title = str_replace( '[', '<', $title );
  //   $title = str_replace( '[/', '</', $title );
  
  //   //<strong></strong>
  //   $title = str_replace( 's]', 'strong>', $title );
  //   // <b></b>
  //   $title = str_replace( 'b]', 'b>', $title );
  //   //<em></em>
  //   $title = str_replace( 'e]', 'em>', $title );
  
  //   return $title;
  // }
  // add_filter( 'widget_title', 'html_widget_title' );
    
  
  
  
    