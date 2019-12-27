<?php

/**
 * BusinessProfiles Widget
 */
class BusinessProfile_Widget extends WP_Widget {
  public function __construct() {
      $widget_ops = array( 'classname' => 'businessprofile_widget', 'description' => 'Display businessprofile post type' );
      parent::__construct( 'businessprofile_widget', 'Pettit: Business Profile Display', $widget_ops );
  }

  public function widget( $args, $instance ) {
      extract( $args );

      echo $before_widget;

      if ( ! empty( $title ) )
          echo $before_title . $title . $after_title;
    ?>
    <address class="pettit-contact-card" itemscope="" itemtype="http://schema.org/RealEstateAgent">
			<!-- <meta itemprop="description" content="Just another WordPress site"> -->
      <!-- <meta itemprop="url" content="//localhost:3000"> -->


		  <meta itemprop="address" content="<?php echo $GLOBALS['cgv']['address'] ?>">




      <div class="pettit-phone" itemprop="telephone">
        <?php echo $GLOBALS['cgv']['phone'] ?>
      </div>

      <div class="pettit-email" itemprop="email" content="<?php echo $GLOBALS['cgv']['email'] ?>">
        <a href="mailto:<?php echo $GLOBALS['cgv']['email'] ?>"><?php echo $GLOBALS['cgv']['email'] ?></a>
      </div>

      <div class="pettit-directions">
        <a href="//maps.google.com/maps?saddr=current+location&amp;daddr=<?php echo $GLOBALS['cgv']['address'] ?>" target="_blank" rel="noopener noreferrer"><?php echo $GLOBALS['cgv']['address'] ?></a>
      </div>


		</address>
    <?php


      echo $after_widget;
  }

}


function register_businessprofiles_widget() {
  register_widget( 'BusinessProfile_Widget' );
}

add_action( 'widgets_init', 'register_businessprofiles_widget' );