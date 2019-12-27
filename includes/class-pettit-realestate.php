<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.larrypettitrealestate.com
 * @since      1.0.0
 *
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 * @author     Mike Puglisi <mikepuglisi@gmail.com>
 */
class Pettit_Realestate {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pettit_Realestate_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PETTIT_REALESTATE_VERSION' ) ) {
			$this->version = PETTIT_REALESTATE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pettit-realestate';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
    $this->check_dependencies();
  }


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pettit_Realestate_Loader. Orchestrates the hooks of the plugin.
	 * - Pettit_Realestate_i18n. Defines internationalization functionality.
	 * - Pettit_Realestate_Admin. Defines all hooks for the admin area.
	 * - Pettit_Realestate_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pettit-realestate-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pettit-realestate-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pettit-realestate-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pettit-realestate-public.php';

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-testimonials.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-sold-properties.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-popular-areas.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-communities.php';
    // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-business-profile-widget.php';
    // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-areas-taxonomy.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pettit-realestate-custom-post-type-slug-remover.php';


		$this->loader = new Pettit_Realestate_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pettit_Realestate_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pettit_Realestate_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pettit_Realestate_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pettit_Realestate_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pettit_Realestate_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
  }

  private function check_dependencies() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/class-tgm-plugin-activation.php';

    add_action( 'tgmpa_register', 'pettit__register_required_plugins' );

    /**
     * Register the required plugins for this theme.
     *
     * In this example, we register five plugins:
     * - one included with the TGMPA library
     * - two from an external source, one from an arbitrary source, one from a GitHub repository
     * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
     *
     * The variables passed to the `tgmpa()` function should be:
     * - an array of plugin arrays;
     * - optionally a configuration array.
     * If you are not changing anything in the configuration array, you can remove the array and remove the
     * variable from the function call: `tgmpa( $plugins );`.
     * In that case, the TGMPA default settings will be used.
     *
     * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
     */
    function pettit__register_required_plugins() {
      /*
       * Array of plugin arrays. Required keys are name and slug.
       * If the source is NOT from the .org repo, then source is also required.
       */
      $plugins = array(

        // // This is an example of how to include a plugin bundled with a theme.
        // array(
        // 	'name'               => 'TGM Example Plugin', // The plugin name.
        // 	'slug'               => 'tgm-example-plugin', // The plugin slug (typically the folder name).
        // 	'source'             => dirname( __FILE__ ) . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
        // 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        // 	'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        // 	'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        // 	'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
        // 	'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        // 	'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        // ),

        // // This is an example of how to include a plugin from an arbitrary external source in your theme.
        // array(
        // 	'name'         => 'TGM New Media Plugin', // The plugin name.
        // 	'slug'         => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
        // 	'source'       => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
        // 	'required'     => true, // If false, the plugin is only 'recommended' instead of required.
        // 	'external_url' => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
        // ),

        // // This is an example of how to include a plugin from a GitHub repository in your theme.
        // // This presumes that the plugin code is based in the root of the GitHub repository
        // // and not in a subdirectory ('/src') of the repository.
        // array(
        // 	'name'      => 'Adminbar Link Comments to Pending',
        // 	'slug'      => 'adminbar-link-comments-to-pending',
        // 	'source'    => 'https://github.com/jrfnl/WP-adminbar-comments-to-pending/archive/master.zip',
        // ),

        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
          'name'               => 'Multiple Post Thumbnails', // The plugin name.
          'slug'               => 'multiple-post-thumbnails',
          'required'  => true,
        ),
        array(
          'name'               => 'Customizer Social Icons', // The plugin name.
          'slug'               => 'customizer-social-icons',
          'required'  => true,
        ),
        array(
          'name'               => 'Remove Category from URL', // The plugin name.
          'slug'               => 'remove-category-url',
          'required'  => true,
        ),
        //
        // array(
        //   'name'               => 'Shortcode for Current Date', // The plugin name.
        //   'slug'               => 'shortcode-for-current-date',
        //   'required'  => true,
        // ),
        // array(
        //   'name'               => 'Widget CSS Classes', // The plugin name.
        //   'slug'               => 'widget-css-classes',
        //   'required'  => true,
        // ),


        // This is an example of the use of 'is_callable' functionality. A user could - for instance -
        // have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
        // 'wordpress-seo-premium'.
        // By setting 'is_callable' to either a function from that plugin or a class method
        // `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
        // recognize the plugin as being installed.
        // array(
        // 	'name'        => 'WordPress SEO by Yoast',
        // 	'slug'        => 'wordpress-seo',
        // 	'is_callable' => 'wpseo_init',
        // ),

      );

      /*
       * Array of configuration settings. Amend each line as needed.
       *
       * TGMPA will start providing localized text strings soon. If you already have translations of our standard
       * strings available, please help us make TGMPA even better by giving us access to these translations or by
       * sending in a pull-request with .po file(s) with the translations.
       *
       * Only uncomment the strings in the config array if you want to customize the strings.
       */
      $config = array(
        'id'           => 'text-domain',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'plugins.php',            // Parent menu slug.
        'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        /*
        'strings'      => array(
          'page_title'                      => __( 'Install Required Plugins', 'text-domain' ),
          'menu_title'                      => __( 'Install Plugins', 'text-domain' ),
          /* translators: %s: plugin name. * /
          'installing'                      => __( 'Installing Plugin: %s', 'text-domain' ),
          /* translators: %s: plugin name. * /
          'updating'                        => __( 'Updating Plugin: %s', 'text-domain' ),
          'oops'                            => __( 'Something went wrong with the plugin API.', 'text-domain' ),
          'notice_can_install_required'     => _n_noop(
            /* translators: 1: plugin name(s). * /
            'This theme requires the following plugin: %1$s.',
            'This theme requires the following plugins: %1$s.',
            'text-domain'
          ),
          'notice_can_install_recommended'  => _n_noop(
            /* translators: 1: plugin name(s). * /
            'This theme recommends the following plugin: %1$s.',
            'This theme recommends the following plugins: %1$s.',
            'text-domain'
          ),
          'notice_ask_to_update'            => _n_noop(
            /* translators: 1: plugin name(s). * /
            'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
            'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
            'text-domain'
          ),
          'notice_ask_to_update_maybe'      => _n_noop(
            /* translators: 1: plugin name(s). * /
            'There is an update available for: %1$s.',
            'There are updates available for the following plugins: %1$s.',
            'text-domain'
          ),
          'notice_can_activate_required'    => _n_noop(
            /* translators: 1: plugin name(s). * /
            'The following required plugin is currently inactive: %1$s.',
            'The following required plugins are currently inactive: %1$s.',
            'text-domain'
          ),
          'notice_can_activate_recommended' => _n_noop(
            /* translators: 1: plugin name(s). * /
            'The following recommended plugin is currently inactive: %1$s.',
            'The following recommended plugins are currently inactive: %1$s.',
            'text-domain'
          ),
          'install_link'                    => _n_noop(
            'Begin installing plugin',
            'Begin installing plugins',
            'text-domain'
          ),
          'update_link' 					  => _n_noop(
            'Begin updating plugin',
            'Begin updating plugins',
            'text-domain'
          ),
          'activate_link'                   => _n_noop(
            'Begin activating plugin',
            'Begin activating plugins',
            'text-domain'
          ),
          'return'                          => __( 'Return to Required Plugins Installer', 'text-domain' ),
          'plugin_activated'                => __( 'Plugin activated successfully.', 'text-domain' ),
          'activated_successfully'          => __( 'The following plugin was activated successfully:', 'text-domain' ),
          /* translators: 1: plugin name. * /
          'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'text-domain' ),
          /* translators: 1: plugin name. * /
          'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'text-domain' ),
          /* translators: 1: dashboard link. * /
          'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'text-domain' ),
          'dismiss'                         => __( 'Dismiss this notice', 'text-domain' ),
          'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'text-domain' ),
          'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'text-domain' ),

          'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
        */
      );

      tgmpa( $plugins, $config );
    }
  }

}
