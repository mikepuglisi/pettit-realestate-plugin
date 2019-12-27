<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.larrypettitrealestate.com
 * @since      1.0.0
 *
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 * @author     Mike Puglisi <mikepuglisi@gmail.com>
 */
function pettit_create_default_pages() {
  $site_home_title = 'Pettit Site Home';
  $site_home_page = get_page_by_title($site_home_title, 'OBJECT', 'page');
  $site_home_page_id = empty($site_home_page) ? 0 : $site_home_page->ID;
  if(empty($site_home_page)) {
    $site_home_page_id = wp_insert_post(
        array(
          'comment_status' => 'close',
          'ping_status'    => 'close',
          'post_author'    => 1,
          'post_title'     => ucwords($site_home_title),
          'post_name'      => strtolower(str_replace(' ', '-', trim($site_home_title))),
          'post_status'    => 'publish',
          'post_content'   => '',
          'post_type'      => 'page',
          'post_parent'    => ''
        )
    );
  }
  update_option( 'page_on_front', $site_home_page_id);
  update_option( 'show_on_front', 'page' );
  if(empty(get_page_by_title("Pettit Agent Profile", 'OBJECT', 'page'))) {
    wp_insert_post(
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => ucwords("Pettit Agent Profile"),
        'post_name'      => strtolower(str_replace(' ', '-', trim("Pettit Agent Profile"))),
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:group {"className":"pettit-agent-profile-parent"} -->
        <div class="wp-block-group pettit-agent-profile-parent"><div class="wp-block-group__inner-container"><!-- wp:heading {"className":"agent-profile-title"} -->
        <h2 class="agent-profile-title"> <strong>Palm Beach Gardens</strong> Real Estate Agent </h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"className":"agent-profile-body"} -->
        <p class="agent-profile-body"> If you are looking for Homes For Sale in Palm Beach County or Homes for sale in Martin County Area or are interested in Palm Beach County and Martin County Real Estate Investments Larry Pettit is dedicated to helping you from the first phone call to the last detail during your Real Estate journey. Call today for your complimentary Real Estate evaluation. 561-262-4949. </p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"className":"agent-profile-signature"} -->
        <p class="agent-profile-signature">— Larry Pettit Broker/Associate</p>
        <!-- /wp:paragraph --></div></div>
        <!-- /wp:group -->',
        'post_type'      => 'page',
        'post_parent'    => $site_home_page_id
      )
    );
  }

  if(empty(get_page_by_title("Pettit Popular Areas", 'OBJECT', 'page'))) {
    wp_insert_post(
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => ucwords("Pettit Popular Areas"),
        'post_name'      => strtolower(str_replace(' ', '-', trim("Pettit Popular Areas"))),
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:heading {"className":"h2 fancy-text gold text-primary mb-4"} -->
        <h2 class="fancy-text gold text-primary mb-4"><strong>Popular</strong> Areas</h2>
        <!-- /wp:heading -->
        <!-- wp:shortcode -->
        [pettit_popular_areas]
        <!-- /wp:shortcode -->',
        'post_type'      => 'page',
        'post_parent'    => $site_home_page_id
      )
    );
  }


  if(empty(get_page_by_title("Palm Beach Gardens Communities", 'OBJECT', 'community'))) {
    $soldPropertiesId = wp_insert_post(
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => 'Palm Beach Gardens Communities',
        'post_name'      => 'palm-beach-gardens-communities',
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:paragraph --><p>For home buyers looking to purchase a home in South Florida, the number of communities and neighborhoods available are enormous. Homes in the Palm Beach Gardens, Jupiter, Singer Island, Juno Beach, Stuart, Sewalls Point, Hutchinson Island range from modest to extravagant and are available in town homes, condos, and a whole range of sizes of single family dwellings.</p><!-- /wp:paragraph -->',
        'post_type'      => 'page'
      )
    );
    //$file is the path to your uploaded file (for example as set in the $_FILE posted file array)
    //$filename is the name of the file
    //first we need to upload the file into the wp upload folder. 
    
    
    // update_post_meta($soldPropertiesId, '_wp_page_template', 'page-templates/right-sidebarpage.php');

    $file = __DIR__ . '/../assets/palm-beach-gardens-communities.png';
    $filename = basename($file);
    $upload_file = wp_upload_bits($filename, null, @file_get_contents($file));
    if(!$upload_file['error']) {
      //if succesfull insert the new file into the media library (create a new attachment post type)
      $wp_filetype = wp_check_filetype($filename, null );
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_parent' => $soldPropertiesId,
        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );
      //wp_insert_attachment( $attachment, $filename, $parent_post_id );
      $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $soldPropertiesId );
      if (!is_wp_error($attachment_id)) {
        //if attachment post was successfully created, insert it as a thumbnail to the post $soldPropertiesId
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        //wp_generate_attachment_metadata( $attachment_id, $file ); for images
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
        wp_update_attachment_metadata( $attachment_id,  $attachment_data );
        set_post_thumbnail( $soldPropertiesId, $attachment_id );
      }
    }    
  }


  // if(empty(get_page_by_title("Ballenisles Community", 'OBJECT', 'community'))) {
  //   $parentPage = get_page_by_title("Palm Beach Gardens Communities", 'OBJECT', 'community');
  //   $soldPropertiesId = wp_insert_post(
  //     array(
  //       'comment_status' => 'close',
  //       'ping_status'    => 'close',
  //       'post_author'    => 1,
  //       'post_title'     => 'Ballenisles Community',
  //       'post_name'      => 'ballenisles-community',
  //       'post_status'    => 'publish',
  //       'post_content'   => 'BallenIsles is a gated community located in the heart of Palm Beach Gardens, just minutes from I-95 the Florida turnpike, and the Palm Beach International airport. Close by are a host of fine restaurants, the Gardens Mall and several public beaches.',
  //       'post_type'      => 'page'
  //     )
  //   );
    

  //   wp_update_post(
  //     array(
  //         'ID' => //$image_id, 
  //         'post_parent' => //$new_post_id
  //     )
  //   );
  // }
  
  if(empty(get_page_by_title("Sold Properties", 'OBJECT', 'page'))) {
    $soldPropertiesId = wp_insert_post(
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => 'Sold Properties',
        'post_name'      => 'sold-properties',
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:shortcode -->
        [pettit_sold_properties]
        <!-- /wp:shortcode -->',
        'post_type'      => 'page'
      )
    );
    //$file is the path to your uploaded file (for example as set in the $_FILE posted file array)
    //$filename is the name of the file
    //first we need to upload the file into the wp upload folder. 
    
    
    // update_post_meta($soldPropertiesId, '_wp_page_template', 'page-templates/right-sidebarpage.php');

    $file = __DIR__ . '/../assets/sold-real-estate.png';
    $filename = basename($file);
    $upload_file = wp_upload_bits($filename, null, @file_get_contents($file));
    if(!$upload_file['error']) {
      //if succesfull insert the new file into the media library (create a new attachment post type)
      $wp_filetype = wp_check_filetype($filename, null );
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_parent' => $soldPropertiesId,
        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );
      //wp_insert_attachment( $attachment, $filename, $parent_post_id );
      $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $soldPropertiesId );
      if (!is_wp_error($attachment_id)) {
        //if attachment post was successfully created, insert it as a thumbnail to the post $soldPropertiesId
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        //wp_generate_attachment_metadata( $attachment_id, $file ); for images
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
        wp_update_attachment_metadata( $attachment_id,  $attachment_data );
        set_post_thumbnail( $soldPropertiesId, $attachment_id );
      }
    }    
  }


  if(empty(get_page_by_title("Hutchinson Island Real Estate", 'OBJECT', 'popular_area'))) {
    $hutchinsonIslandId = wp_insert_post(
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => 'Hutchinson Island Real Estate',
        'post_name'      => 'hutchinson-island-real-estate',
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:paragraph --><p>Hutchinson Island is a barrier island located in Martin County about 45 miles north of West Palm Beach. Spanning about 24 miles from north to south and located on the east side of the intercoastal waterway from the mainland.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>Named after James Hutchinson who was given a land grant by the Spanish Governor of Florida in 1803. Hutchinson started a plantation on the island using the island as a barrier from invading Indians. Hutchinson island was also inhabited by pirates during these early years. Pirates used the higher elevations on the island as a lookout to spot passing ships and steal their cargo. The pirates also pillaged Hutchinson’s plantation and crops. He attempted to resolve these issues by visiting the Governor and asking for help in September 1808, however during his return he drowned tragically at sea in a violent storm.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>Throughout the majority of the 1800’s the island remained un-inhabited. The United States acquired Florida from Spain in 1827 and the Hutchinson Island grant was acknowledged by the U S Congress. The first survey of the island was completed in June of 1845.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>The very first structure on the island was built in 1876, and called the House of Refuge located at 301 SE MacArthur Blvd. Originally built for people who were passing through or needed help, the House if Refuge remains today and is listed on the National Register of Historic Places.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>In around 1900 Edward “Ned” Hutchinson a descendant of James Hutchinson built a permanent home on Hutchinson Island where he maintained a bean farm. For several years after this many other people farmed beans, and some farmed beehives for honey production.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>On January 17, 1924, construction began on a 1.2-mile bridge in Jensen located about a half mile from the business district. The wooden bridge spanning the Indian River was completed in May 1926. Now Hutchinson Island was connected to the mainland for the 1st time. The bridge was just wide enough for 2 cars to pass alongside each other. A hand operated draw situated in the deepest part of the river permitted boat travel up and down the waterway. The bridge now provided easy access to the island fostering a growing attraction to beaches, especially with tourists and visitors.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>With limited population the island lacked modern conveniences such as phone and electrical service. Hutchinson Island from Ft. Pierce to the St Lucie inlet remained one of the last undeveloped sections of ocean frontage along the southeast coast of Florida. However finally in mid-1953 Florida Power and Light installed a marine power cable across Indian River to Hutchinson Island which then connected to 18 power poles on Hutchinson Island.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>Immediately benefiting were the Sand Club resort hotel, Sandpiper Inn and one private residence, already built on Hutchinson Island. Doris Collins the operator of the Sandpiper Inn was given the honor of “throwing the switch” after having long endured preparing food without electrical service. However, there were still several other residences to the south on Hutchinson Island who would have to wait for their electrical service.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>Today Hutchinson Island remains one of the most pristine and natural beach areas in all of south Florida, for all to enjoy.                
        ',
        'post_type'      => 'popular_area'
      )
    );
    wp_set_object_terms( $hutchinsonIslandId, array('hutchinson-island'), 'areas' );
  }



  set_post_type(get_page_by_path( 'juno-beach-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');    
  update_post_meta(get_page_by_path( 'juno-beach-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Juno Beach");    
  
  $page_id = get_page_by_path( 'palm-beach-gardens-homes', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID;
  set_post_type(get_page_by_path( 'palm-beach-gardens-homes', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'palm-beach-gardens-homes', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Palm Beach Gardens");    

  set_post_type(get_page_by_path( 'jupiter-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'jupiter-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Jupiter");    

  set_post_type(get_page_by_path( 'jupiter-island-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'jupiter-island-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Jupiter Island");    

  set_post_type(get_page_by_path( 'singer-island-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'singer-island-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Singer Island");    

  set_post_type(get_page_by_path( 'stuart-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'stuart-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Stuart");    

  set_post_type(get_page_by_path( 'palm-beach-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'popular_area');
  update_post_meta(get_page_by_path( 'palm-beach-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area') )->ID, 'short_title', "Palm Beach");    

  
  $jupiterCommunities = get_page_by_path( 'jupiter-communities', OBJECT, array('post', 'community', 'popular_area'));
  set_post_type($jupiterCommunities->ID, 'community');
  update_post_meta($jupiterCommunities->ID, 'short_title', "Jupiter");  

  
  $trumpNational = get_page_by_path( 'trump-national-country-club', OBJECT, array('page', 'community', 'popular_area'));
  set_post_type($trumpNational->ID, 'community');
  wp_update_post(
    array(
        'ID' => $trumpNational->ID, 
        'post_parent' => $jupiterCommunities->ID
    )
  );
  
  $pbgCommunities = get_page_by_path( 'communities-2', OBJECT, array('page', 'post', 'community', 'popular_area'));
  $ballenisles = get_page_by_path( 'ballenisles-real-estate', OBJECT, array('page', 'post', 'community', 'popular_area'));
  update_post_meta($pbgCommunities->ID, 'short_title', "Palm Beach Gardens");  
  update_post_meta($ballenisles->ID, 'short_title', "Ballenisles");  
  set_post_type($pbgCommunities->ID, 'community');    
  set_post_type($ballenisles->ID, 'community');  
  
  wp_update_post(
    array(
        'ID' => $ballenisles->ID, 
        'post_parent' => $pbgCommunities->ID
    )
  );  


  if(empty(get_page_by_title("Eastpointe Community", 'OBJECT', 'community'))) {
     $post_id = (
      array(
        'comment_status' => 'close',
        'ping_status'    => 'close',
        'post_author'    => 1,
        'post_title'     => 'Eastpointe Community',
        'post_name'      => 'eastpointe-community',
        'post_status'    => 'publish',
        'post_content'   => '<!-- wp:paragraph --><p>Located in Palm Beach Gardens is the community of Eastpointe. Here you can find luxurious ranch and single family homes in many pleasant designs and styles. With special features throughout the whole house, residents can enjoy the breathtaking view of the golf course, vaulted ceilings, roman tubs, marble and carpet floors, walk in closets, and large backyards. Along with spectacular homes, Eastpointe also offers a large variety of amenities. Residents and their guests can take advantage of the tennis courts, heated swimming pool, game room, and golf course. There is a mandatory social membership fee of $4,000 for this community. If you’d like to enjoy something outside of this gated community, its convenient location gives you options of nearby shopping centers, entertainment, and restaurants.</p><!-- /wp:paragraph -->',
        'post_parent' => $pbgCommunities->ID,
        'post_type'      => 'community'
      )
    );
    update_post_meta($post_id, 'short_title', "Eastpointe");    
  }  

  pettit_add_sold_property("10330 S Ocean Dr, Jensen Beach, FL 34957", "1819350", "6587", "10330-beach-house.jpg");
  pettit_add_sold_property("103 Beachwalk Lane, Jupiter, FL 33477","730000","5282","103BeachWalkLn.jpg");  
  pettit_add_sold_property("104 Beachwalk Lane , Jupiter, FL 33477","","5423","104BeachWalkLn.jpg");
  pettit_add_sold_property("105 Via Capri, Palm Beach Gardens, FL 33418","","","105ViaCapri.jpg");
  // pettit_add_sold_property("108 Beachwalk Lane , Jupiter, FL 33477","","7197","108BeachWalkLn.jpg");
  // pettit_add_sold_property("110 St Edward, Palm Beach Gardens, FL 33418","","","110StEdward.jpg");
  // pettit_add_sold_property("111 Beachwalk Lane , Jupiter, FL 33477","","6919","111BeachWalkLn.jpg");
  // pettit_add_sold_property("111 Coconut Key Ct, Palm Beach Gardens, FL 33418","","3588","111CoconutKeyCt.jpg");
  // pettit_add_sold_property("112 St. Martin , Palm Beach Gardens, FL 33418","","","112StMartinDr.jpg");
  // pettit_add_sold_property("1122 SE MacArthur, Stuart, FL 34996","989094","1638","1122aquarius.jpg");
  // pettit_add_sold_property("115 Via Capri, Palm Beach Gardens, FL 33418","","","115ViaCapri.jpg");
  // pettit_add_sold_property("120 Vintage Isle Lane, Palm Beach Gardens, FL 33418","","","120VintageIsleDr.jpg");
  // pettit_add_sold_property("12225 Tilinghast Cir., Palm Beach Gardens, FL 33418","","","12225TillinghastCir.jpg", "10");
  // pettit_add_sold_property("123 Olivera, Palm Beach Gardens, FL 33418","","","123OliveraWay.jpg");
  // pettit_add_sold_property("135 Orchid Cay, Palm Beach Gardens, FL 33418","","","135OrchidCay.jpg");
  // pettit_add_sold_property("144 Banyan Isle Dr, Palm Beach Gardens, FL 33418","","","144BanyanIsleDr.jpg");
  // pettit_add_sold_property("152 Sunset Bay, Palm Beach Gardens, FL 33418","","","152SunsetBayDr.jpg");
  // pettit_add_sold_property("2071 Stonington Ter, West Palm Beach, FL 33411","","","2071StoningtonTer.jpg");
  // pettit_add_sold_property("225 Coral Cay Ter, Palm Beach Gardens, FL 33418","","","225CoralCayTer.jpg");
  // pettit_add_sold_property("23 NE MacArthur,Stuart, FL 34996","1927000","3004","23ladolcevita.jpg");
  // pettit_add_sold_property("23 Somerset, Palm Beach Gardens, FL 33418","","","23SomersetDr.jpg");
  // pettit_add_sold_property("2373 Yarmouth Dr., Boca Raton, FL 33434","","","2373YarmouthDr.jpg");
  // pettit_add_sold_property("25 Playa Rienta Way, Palm Beach Gardens, FL 33418","","","25PlayaRientaWay.jpg");
  // pettit_add_sold_property("34 St. James , Palm Beach Gardens, FL 33418","","","34StJamesDrive.jpg");
  // pettit_add_sold_property("359 Chambord, Palm Beach Gardens, FL 33410","","","359Chambord.jpg");
  // pettit_add_sold_property("38 Laguna , Palm Beach Gardens, FL 33418","","","38LagunaTer.jpg");
  // pettit_add_sold_property("4580 S Ocean, Fort Pierce, FL 34949","1950000","5032","4580socean.jpg");
  // pettit_add_sold_property("4643 NE Ocean, Jensen Beach, FL 34957","2865000","3608","4643neocean.jpg");
  // pettit_add_sold_property("4850 NE Spinnaker Pt, Stuart, FL 34996","730000","2641","");
  // pettit_add_sold_property("5117 NE Shore Village, Stuart, FL 34996","1335000","1800","5117neshore.jpg");
  // pettit_add_sold_property("5118 NE Shore Village, Stuart, FL 34996","1089250","3263","5118neshore.jpg");
  // pettit_add_sold_property("589 SW Quck Ct., Port Saint Lucie, FL 34953","","","589SWQuickCT.jpg");
  // pettit_add_sold_property("7 Bay Point Drive, West Palm Beach, FL 33411","","","7BayPointeDrive.jpg");
  // pettit_add_sold_property("713 SE MacArthur, Stuart, FL 34996","911800","1700","713semacarthur.jpg");
  // pettit_add_sold_property("725 SE MacArthur, Stuart, FL 34996","1500000","2232","725semacarthur.jpg");
  // pettit_add_sold_property("755 Greenbriar Dr., West Palm Beach, FL 33403","","","755GreenbriarDr.jpg");
  // pettit_add_sold_property("771 SE MacArthur, Stuart, FL 34996","916200","1773","771semacarthur.jpg");
  // pettit_add_sold_property("7742 Sandhiil Ct., West Palm Beach, FL 33412","","","7742SandhillCt.jpg");
  // pettit_add_sold_property("79 Laguna Terrace, Palm Beach Gardens, FL 33418","","","79LagunaDr.jpg");
  // pettit_add_sold_property("827 SE MacArthur, Stuart, FL 34996","2000000","2034","");
  // pettit_add_sold_property("851 SE MacArthur , Stuart, FL 34996","2000000","2784","851semacarthur.jpg");
  // pettit_add_sold_property("871 SE MacArthur, Stuart, FL 34996","2730900","4238","871semacarthur.jpg");
  // pettit_add_sold_property("894 SE MacArthur, Stuart, FL 34996","2900000","4500","894semacarthur.jpg");
  // pettit_add_sold_property("895 SE MacArthur, Stuart, FL 34996","2600000","2738","895semacarthur.jpg");
  // pettit_add_sold_property("953 SE MacArthur, Stuart, FL 34996","2785000","3331","953semacarthur.jpg");
  // pettit_add_sold_property("101 Chasewood Circle , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("101 Chasewood Circle , Palm Beach Gardens, FL 33418","","4067","");
  // pettit_add_sold_property("10135 Sand Cay, West Palm Beach, FL 33412","","","");
  // pettit_add_sold_property("1022 Grand Isle, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("1027 Grand Isle, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("104 Coconut Key, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("104 Via Quintora, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("105 San Marco, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("105B Palm Point , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("105D Palm Bay Drive, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("106 Island Cove Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("106A Palm Bay Drive, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("107 Orchid Cay Drive, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("109 Windward Cove , Palm Beach Gardens, FL 33418","","4057","");
  // pettit_add_sold_property("109B Palm Point Circle, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("110 D Palm Pointe, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("112 B Palm Bay Dr., Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("113 St. Martin Dr., Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("114 C Palm Bay Dr., Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("115 Vintage Isle Lane , Palm Beach Gardens, FL 33418","","5273","");
  // pettit_add_sold_property("1152 Crystal Drive , Palm Beach Gardens, FL 33418","","4365","");
  // pettit_add_sold_property("11542 Villa Vasari Drive , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("116 St. Edward, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("116 Sunset Cove Lane , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("117 San Marita Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("119 Grand Palm Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("119 San Marita Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("119 Vintage Isle Lane , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("12 Laguna Terrace, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("120 Grand Palm Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("122 Isle Drive, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("122 St. Martin Drive , Palm Beach Gardens, FL 33418","","","");  
  // pettit_add_sold_property("124 Vintage Isle Lane, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("125 Vintage Isle Lane , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("130 San Marco, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("145 Banyan Isle Dr., Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("149 San Marco, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("150 Sunset Bay, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("17125 Gulf Pine Circle , Wellington, FL 33414","","","");
  // pettit_add_sold_property("1825 Flower Drive , Palm Beach Gardens, FL 33410","","3652","");
  // pettit_add_sold_property("2 E. Balfour Road , Palm Beach Gardens, FL 33418","","3737","");
  // pettit_add_sold_property("203 Grand Pointe Dr., Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("205 Coconut Key, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("2120 Milano Court , Palm Beach Gardens, FL 33418","","7189","");
  // pettit_add_sold_property("216 Coconut Key Drive, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("217 Coconut Key, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("224 Grand Pointe, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("26 Laguna, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("262 Porto Vecchio Way , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("27 St. George, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("270 Isle Way, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("29 St. James, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("3 Balfour Court , Palm Beach Gardens, FL 33418","","4421","");
  // pettit_add_sold_property("306 Bravado Lane , Singer Island, FL 33404","","","");
  // pettit_add_sold_property("3620 Gardens Parkway #1601 B , Palm Beach Gardens, FL 33410","","3225","");
  // pettit_add_sold_property("3620 Gardens Parkway #401 B , Palm Beach Gardens, FL 33410","","3225","");
  // pettit_add_sold_property("390 Grand Key, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("41 Dunbar Road , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("41 Dunbar Road , Palm Beach Gardens, FL 33418","","3995","");
  // pettit_add_sold_property("4107 Sandy Spit Lane , Jupiter, FL 33458","","2760","");
  // pettit_add_sold_property("435 Savoie Drive , Palm Beach Gardens, FL 33410","","5371","");
  // pettit_add_sold_property("435 Savoie, Palm Beach Gardens, FL 33410","","","");
  // pettit_add_sold_property("45 St. George, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("4966 Bonsai Circle #200 , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("5150 North Ocean Drive 1201 , Singer Island, FL 33404","","4400","");
  // pettit_add_sold_property("5310 North Ocean Blvd #PH 12 , Singer Island, FL 33404","","9416","");
  // pettit_add_sold_property("57 St. George Place , Palm Beach Gardens, FL 33418","","5749","");
  // pettit_add_sold_property("57 St. George, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("679 Hermatage , Palm Beach Gardens, FL 33410","","","");
  // pettit_add_sold_property("7797 SE Kingsway Street , Hobe Sound, FL 33455","","1753","");
  // pettit_add_sold_property("80 St. James , Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("85 Laguna, Palm Beach Gardens, FL 33418","","","");
  // pettit_add_sold_property("938 Mill Creek Drive , Palm Beach Gardens, FL 33410","","3710","");
  // pettit_add_sold_property("6740 N. Ocean Blvd., Boynton Beach, FL 33435","","","");
  
  

}

function pettit_add_sold_property($address, $price, $sqft, $image_name, $days_on_market = "") {

  if(empty(get_page_by_title($address, 'OBJECT', 'sold_property'))) {
    $post_id = wp_insert_post(
     array(
       'comment_status' => 'close',
       'ping_status'    => 'close',
       'post_author'    => 1,
       'post_title'     => $address,
       'post_name'      => strtolower(str_replace(' ', '-', str_replace(',', '-', trim($address)))),
       'post_status'    => 'publish',
       'post_content'   => ' ',
       'post_parent' => '',
       'post_type'      => 'sold_property'
     )
   );
   update_post_meta($post_id, 'address', $address);    
   update_post_meta($post_id, 'price', $price);   
   update_post_meta($post_id, 'sqft', $sqft);
   update_post_meta($post_id, 'daysOnMarket', $days_on_market);
   if (!empty($image_name)) {
    $file = __DIR__ . '/../assets/' . $image_name;
    $filename = basename($file);
    $upload_file = wp_upload_bits($filename, null, @file_get_contents($file));
    if(!$upload_file['error']) {
      //if succesfull insert the new file into the media library (create a new attachment post type)
      $wp_filetype = wp_check_filetype($filename, null );
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_parent' => $post_id,
        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );
      //wp_insert_attachment( $attachment, $filename, $parent_post_id );
      $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
      if (!is_wp_error($attachment_id)) {
        //if attachment post was successfully created, insert it as a thumbnail to the post $post_id
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        //wp_generate_attachment_metadata( $attachment_id, $file ); for images
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
        wp_update_attachment_metadata( $attachment_id,  $attachment_data );
        set_post_thumbnail( $post_id, $attachment_id );
      }
    }       
   }     
  }    
}

function pettit_create_primary_menu_items() {

	$name = 'Pettit Primary Navigation';
	$menu_exists = wp_get_nav_menu_object($name);
	// if( !$menu_exists){
	// 	$menu_id = wp_create_nav_menu($name);	
  //  }
	if( $menu_exists){
    wp_delete_nav_menu($name);		
   }     
   wp_create_nav_menu($name);	
   $menu = get_term_by( 'name', $name, 'nav_menu' );
   $menu_id = $menu->term_id;
        
        

  $locations = get_nav_menu_locations();

  $locations['primary'] = $menu_id;
  set_theme_mod( 'nav_menu_locations', $locations );

  $new_menu_obj = array();

  // DELETE MENU
	// if( $menu_exists){
  //   wp_delete_nav_menu($name);		
  //  }   

  // DELETE MENU ITEM 
  //wp_delete_post($menu_item_db_id);
      $nav_items_to_add = array(
              'about' => array(
                  'title' => 'About',
                  'path' => 'palm-beach-gardens-realtor',
                  ),
              'about_level2' => array(
                  'title' => 'About Larry Pettit',
                  'path' => 'palm-beach-gardens-realtor',
                  'parent' => 'about',
                  ),
              'testimonials' => array(
                'title' => 'Testimonials',
                'url' => '/testimonials',
                'parent' => 'about',
                ),       
              'press_pages' => array(
                'title' => 'Press Pages',
                'path' => 'palm-beach-county-real-estate-press-pages',
                'parent' => 'about',                
                ),     
              'properties' => array(
                'title' => 'Properties',
                'url' => '#'
                ),     
              'featured' => array(
                'title' => 'Featured',
                'path' => 'homes-for-sale-featured',
                'parent' => 'properties',
              ),     
              'luxury' => array(
                'title' => 'Luxury',
                'path' => 'luxury-homes',
                'parent' => 'properties',
              ),     
              'popular_areas' => array(
                'title' => 'Popular Areas',
                'url' => '#',
                'parent' => 'properties',
                'classes' => 'menu_header'
              ),    
              'popular_areas' => array(
                'title' => 'Popular Areas',
                'url' => '#',
                'parent' => 'properties',
                'classes' => 'menu_header'
              ),  
              'palm-beach-gardens-homes' => array(
                'title' => 'Palm Beach Gardens',
                'url' => '/palm-beach-gardens-homes',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),   
              
              'jupiter-real-estate' => array(
                'title' => 'Jupiter',
                'url' => '/jupiter-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),                
              
              'jupiter-island-real-estate' => array(
                'title' => 'Jupiter Island',
                'url' => '/jupiter-island-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ), 
              
              'juno-beach-real-estate' => array(
                'title' => 'Juno Beach',
                'url' => '/juno-beach-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),    
              
              'singer-island-real-estate' => array(
                'title' => 'Singer Island',
                'url' => '/singer-island-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),     
              'stuart-real-estate' => array(
                'title' => 'Stuart',
                'url' => '/stuart-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),  
              'palm-beach-real-estate' => array(
                'title' => 'Palm Beach',
                'url' => '/palm-beach-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),     
              'hutchinson-island-real-estate' => array(
                'title' => 'Hutchinson Island',
                'url' => '/hutchinson-island-real-estate',
                'parent' => 'properties',
                'classes' => 'menu_indent'
              ),                                                                      
//   set_post_type(get_page_by_path( 'juno-beach-real-estate' )->ID, 'popular_area');  
// //  $term = get_term_by('slug', 'juno-beach', 'areas');
// //  wp_set_post_terms( get_page_by_path( 'juno-beach-real-estate', OBJECT, 'popular_area' )->ID, 'juno-beach', 'areas' );
//   set_post_type(get_page_by_path( 'palm-beach-gardens-homes' )->ID, 'popular_area');
// //  wp_set_post_terms( get_page_by_path( 'palm-beach-gardens-homes', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'palm-beach-gardens', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'jupiter-real-estate' )->ID, 'popular_area');
// //  wp_set_post_terms( get_page_by_path( 'jupiter-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'jupiter', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'jupiter-island-real-estate' )->ID, 'popular_area');
// ///  wp_set_post_terms( get_page_by_path( 'jupiter-island-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'jupiter-island', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'singer-island-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'singer-island-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'singer-island', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'stuart-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'stuart-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'stuart', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'palm-beach-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'palm-beach-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'palm-beach', 'areas')->term_id, 'areas' );
  

              ///// Add Popular Areas
              'sold' => array(
                'title' => 'Sold',
                'url' => '/sold-properties',
                'parent' => 'properties'
                ),     
                'map_search' => array(
                  'title' => 'Map Search',
                  'path' => 'homes-for-sale-map-search',
                  'parent' => 'properties',
                ),     
              'communities' => array(
                'title' => 'Communities',
                'url' => '#'
                ),   
              'jupiter-communities' => array(
                'title' => 'Jupiter',
                'url' => '/jupiter-communities',
                'parent' => 'communities',                
              ),     
              'pbg-communities' => array(
                'title' => 'Palm Beach Gardens',
                'url' => '/communities-2',
                'parent' => 'communities',                
              ),                                   
              ///// Add Communities
              'sellers' => array(
                'title' => 'Sellers',
                'path' => 'listing-agent'
                ),  
              'buyers' => array(
                'title' => 'Buyers',
                'path' => 'buyers-agent-palm-beach-real-estate'
                ),  
              'contact' => array(
                'title' => 'Contact',
                'url' => '/contact'
                ),
          );
    foreach ( $nav_items_to_add as $slug => $nav_item ) {
        $new_menu_obj[$slug] = array();
        if ( array_key_exists( 'parent', $nav_item ) )
            $new_menu_obj[$slug]['parent'] = $nav_item['parent'];
        if (isset($nav_item['url'])) {
          $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => $nav_item['title'],
            'menu-item-url' => $nav_item['url'],
            'menu-item-classes' => !empty($nav_item['classes']) ? $nav_item['classes'] :  '',
            'menu-item-parent-id' => $new_menu_obj[ $nav_item['parent'] ]['id'],
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom', // optional
          ));          
        } else {
          $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0,  array(
            'menu-item-title' => $nav_item['title'],
            'menu-item-object' => 'page',
            'menu-item-classes' => !empty($nav_item['classes']) ? $nav_item['classes'] :  '',
            'menu-item-parent-id' => $new_menu_obj[ $nav_item['parent'] ]['id'],
            'menu-item-object-id' => get_page_by_path( $nav_item['path'] )->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish')
          );
        }

    }
  




    
	$name = 'Pettit Footer Navigation';
	$menu_exists = wp_get_nav_menu_object($name);
	// if( !$menu_exists){
	// 	$menu_id = wp_create_nav_menu($name);	
  //  }
	if( $menu_exists){
    wp_delete_nav_menu($name);		
   }     
   wp_create_nav_menu($name);	
   $menu = get_term_by( 'name', $name, 'nav_menu' );
   $menu_id = $menu->term_id;
        
        

  $locations = get_nav_menu_locations();

  $locations['primary_footer'] = $menu_id;
  set_theme_mod( 'nav_menu_locations', $locations );

  $new_menu_obj = array();

  // DELETE MENU
	// if( $menu_exists){
  //   wp_delete_nav_menu($name);		
  //  }   

  // DELETE MENU ITEM 
  //wp_delete_post($menu_item_db_id);
    $nav_items_to_add = array(
      'news' => array(
        'title' => 'News',
        'url' => '/news'
      ),
      'privacy' => array(
        'title' => 'Privacy',
        'url' => '/privacy-policy-and-terms-of-service'
      ),
      'terms' => array(
        'title' => 'Terms',
        'url' => '/privacy-policy-and-terms-of-service'
      ),
      'contact' => array(
        'title' => 'Contact Me',
        'url' => '/contact'
      ),                  
    );

    foreach ( $nav_items_to_add as $slug => $nav_item ) {
      $new_menu_obj[$slug] = array();
      if ( array_key_exists( 'parent', $nav_item ) )
          $new_menu_obj[$slug]['parent'] = $nav_item['parent'];
      if (isset($nav_item['url'])) {
        $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0, array(
          'menu-item-title' => $nav_item['title'],
          'menu-item-url' => $nav_item['url'],
          'menu-item-classes' => !empty($nav_item['classes']) ? $nav_item['classes'] :  '',
          'menu-item-parent-id' => $new_menu_obj[ $nav_item['parent'] ]['id'],
          'menu-item-status' => 'publish',
          'menu-item-type' => 'custom', // optional
        ));          
      } else {
        $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0,  array(
          'menu-item-title' => $nav_item['title'],
          'menu-item-object' => 'page',
          'menu-item-classes' => !empty($nav_item['classes']) ? $nav_item['classes'] :  '',
          'menu-item-parent-id' => $new_menu_obj[ $nav_item['parent'] ]['id'],
          'menu-item-object-id' => get_page_by_path( $nav_item['path'] )->ID,
          'menu-item-type' => 'post_type',
          'menu-item-status' => 'publish')
        );
      }
    }
        




  // CREATE FOOTER NAV >>>> News Terms Privacy Contact Me
  
  
  // if (isset($locations['primary'])) {
  //     $menu_id = $locations['primary'];

  //     $new_menu_obj = array();

  //     $nav_items_to_add = array(
  //             'about' => array(
  //                 'title' => 'About',
  //                 'path' => 'palm-beach-gardens-realtor',
  //                 ),
  //             'about_level2' => array(
  //                 'title' => 'About Larry Pettit',
  //                 'path' => 'palm-beach-gardens-realtor',
  //                 'parent' => 'about',
  //                 ),
  //             // 'cart' => array(
  //             //     'title' => 'Cart',
  //             //     'path' => 'shop/cart',
  //             //     'parent' => 'shop',
  //             //     ),
  //             // 'checkout' => array(
  //             //     'title' => 'Checkout',
  //             //     'path' => 'shop/checkout',
  //             //     'parent' => 'shop',
  //             //     ),
  //             // 'my-account' => array(
  //             //     'title' => 'My Account',
  //             //     'path' => 'shop/my-account',
  //             //     'parent' => 'shop',
  //             //     ),
  //             // 'lost-password' => array(
  //             //     'title' => 'Lost Password',
  //             //     'path' => 'shop/my-account/lost-password',
  //             //     'parent' => 'my-account',
  //             //     ),
  //             // 'edit-address' => array(
  //             //     'title' => 'Edit My Address',
  //             //     'path' => 'shop/my-account/edit-address',
  //             //     'parent' => 'my-account',
  //             //     ),
  //         );
  //   foreach ( $nav_items_to_add as $slug => $nav_item ) {
  //       $new_menu_obj[$slug] = array();
  //       if ( array_key_exists( 'parent', $nav_item ) )
  //           $new_menu_obj[$slug]['parent'] = $nav_item['parent'];
  //       $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0,  array(
  //               'menu-item-title' => $nav_item['title'],
  //               'menu-item-object' => 'page',
  //               'menu-item-parent-id' => array_key_exists( 'parent', $nav_item ) ? $new_menu_obj[ $nav_item['parent'] ]['id'] : null,
  //               'menu-item-object-id' => get_page_by_path( $nav_item['path'] )->ID,
  //               'menu-item-type' => 'post_type',
  //               'menu-item-status' => 'publish')
  //       );
  //   }
  //}  
}

// function pettit_convert_legacy_post_types () {      
//   set_post_type(get_page_by_path( 'juno-beach-real-estate' )->ID, 'popular_area');  
// //  $term = get_term_by('slug', 'juno-beach', 'areas');
// //  wp_set_post_terms( get_page_by_path( 'juno-beach-real-estate', OBJECT, 'popular_area' )->ID, 'juno-beach', 'areas' );
//   set_post_type(get_page_by_path( 'palm-beach-gardens-homes' )->ID, 'popular_area');
// //  wp_set_post_terms( get_page_by_path( 'palm-beach-gardens-homes', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'palm-beach-gardens', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'jupiter-real-estate' )->ID, 'popular_area');
// //  wp_set_post_terms( get_page_by_path( 'jupiter-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'jupiter', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'jupiter-island-real-estate' )->ID, 'popular_area');
// ///  wp_set_post_terms( get_page_by_path( 'jupiter-island-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'jupiter-island', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'singer-island-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'singer-island-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'singer-island', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'stuart-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'stuart-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'stuart', 'areas')->term_id, 'areas' );
//   set_post_type(get_page_by_path( 'palm-beach-real-estate' )->ID, 'popular_area');
//   //wp_set_post_terms( get_page_by_path( 'palm-beach-real-estate', OBJECT, 'popular_area' )->ID, get_term_by('slug', 'palm-beach', 'areas')->term_id, 'areas' );
  
// }
class Pettit_Realestate_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    pettit_create_default_pages();
    pettit_create_primary_menu_items();
    // pettit_convert_legacy_post_types();
    // add_action('init', 'pettit_convert_legacy_post_types');
	}

}





// function pettit_create_primary_menu_items() {
//   $locations = get_nav_menu_locations();

//   if (isset($locations['primary'])) {
//       $menu_id = $locations['primary'];

//       $new_menu_obj = array();

//       $nav_items_to_add = array(
//               'about_parent' => array(
//                   'title' => 'About',
//                   'path' => 'palm-beach-gardens-realtor',
//                   ),
//               'about' => array(
//                   'title' => 'About Larry Pettit',
//                   'path' => 'palm-beach-gardens-realtor',
//                   'parent' => 'about_parent',
//                   ),
//               // 'cart' => array(
//               //     'title' => 'Cart',
//               //     'path' => 'shop/cart',
//               //     'parent' => 'shop',
//               //     ),
//               // 'checkout' => array(
//               //     'title' => 'Checkout',
//               //     'path' => 'shop/checkout',
//               //     'parent' => 'shop',
//               //     ),
//               // 'my-account' => array(
//               //     'title' => 'My Account',
//               //     'path' => 'shop/my-account',
//               //     'parent' => 'shop',
//               //     ),
//               // 'lost-password' => array(
//               //     'title' => 'Lost Password',
//               //     'path' => 'shop/my-account/lost-password',
//               //     'parent' => 'my-account',
//               //     ),
//               // 'edit-address' => array(
//               //     'title' => 'Edit My Address',
//               //     'path' => 'shop/my-account/edit-address',
//               //     'parent' => 'my-account',
//               //     ),
//           );
//     foreach ( $nav_items_to_add as $slug => $nav_item ) {
//         $new_menu_obj[$slug] = array();
//         $parent = 0;
//         // if ( array_key_exists( 'parent', $nav_item ) )
//         //     $new_menu_obj[$slug]['parent'] = $nav_item['parent'];
//         if ( array_key_exists( 'parent', $nav_item ) )
//           $parent = get_page_by_path( $nav_item['parent'] )->ID;

//         $page_id = get_page_by_path( $nav_item['path'] )->ID;
//         $page_title = $nav_item['title'];
//         pettit_add_page_to_menu($page_id, $page_title, $menu_id, $parent);
//         // $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0,  array(
//         //         'menu-item-title' => $nav_item['title'],
//         //         'menu-item-object' => 'page',
//         //         'menu-item-parent-id' => $new_menu_obj[ $nav_item['parent'] ]['id'],
//         //         'menu-item-object-id' => get_page_by_path( $nav_item['path'] )->ID,
//         //         'menu-item-type' => 'post_type',
//         //         'menu-item-status' => 'publish')
//         // );
//     }
//   }  
// }