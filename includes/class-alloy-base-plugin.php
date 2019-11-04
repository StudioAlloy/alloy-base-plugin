<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       studio alloy.nl
 * @since      1.0.0
 *
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/includes
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
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/includes
 * @author     Studio Alloy <contact@studioalloy.nl>
 */
class Alloy_Base_Plugin
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Alloy_Base_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
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
     * The options name to be used in this plugin
     *
     * @since  	version 2
     * @access 	private
     * @var  	string 		$option_name 	Option name of this plugin
     */
    private $option_name = 'alloy_base_plugin';
    private $plugin_title = 'Alloy Base';

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
    public function __construct()
    {
        if (defined('ALLOY_BASE_PLUGIN_VERSION')) {
            $this->version = ALLOY_BASE_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'alloy-base-plugin';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Alloy_Base_Plugin_Loader. Orchestrates the hooks of the plugin.
     * - Alloy_Base_Plugin_i18n. Defines internationalization functionality.
     * - Alloy_Base_Plugin_Admin. Defines all hooks for the admin area.
     * - Alloy_Base_Plugin_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-alloy-base-plugin-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-alloy-base-plugin-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-alloy-base-plugin-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-alloy-base-plugin-public.php';

        $this->loader = new Alloy_Base_Plugin_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Alloy_Base_Plugin_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Alloy_Base_Plugin_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Alloy_Base_Plugin_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_filter('login_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_filter('wp_footer', $plugin_admin, 'alloy_custom_admin_bar');
        $this->loader->add_filter('wp_footer', $plugin_admin, 'enqueue_styles');
        $this->loader->add_filter('wp_before_admin_bar_render', $plugin_admin, 'clean_up_admin_header');
        $this->loader->add_filter('admin_bar_menu', $plugin_admin, 'clean_up_admin_bar');
        $this->loader->add_filter('wp_footer', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'alloy_hide_update_msg_non_admins');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'alloy_show_acf_field_slug');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_settings_page');
        $this->loader->add_action('wp_dashboard_setup', $plugin_admin, 'clean_up_dashboard');
        $this->loader->add_filter('upload_size_limit', $plugin_admin, 'set_quota_upload_size');
        $this->loader->add_filter('admin_footer_text', $plugin_admin, 'change_admin_footer');

        show_admin_bar(false); // Diable admin bar fro all users
        //disable all comments
        if (!get_option('alloy_base_plugin_enable_comments')) {
            $this->loader->add_action('admin_init', $plugin_admin, 'disable_comments_post_types_support');
            $this->loader->add_filter('comments_open', $plugin_admin, 'disable_comments_status', 20, 2);
            $this->loader->add_filter('pings_open', $plugin_admin, 'disable_comments_status', 20, 2);
            $this->loader->add_filter('comments_array', $plugin_admin, 'disable_comments_hide_existing_comments', 10, 2);
            $this->loader->add_action('admin_menu', $plugin_admin, 'disable_comments_admin_menu');
            $this->loader->add_action('admin_init', $plugin_admin, 'disable_comments_admin_menu_redirect');
            $this->loader->add_action('admin_init', $plugin_admin, 'disable_comments_dashboard');
            $this->loader->add_action('init', $plugin_admin, 'disable_comments_admin_bar');
        }
        if (get_option('alloy_base_plugin_data_studio_id')) {
            $this->loader->add_action('wp_dashboard_setup', $plugin_admin, 'example_add_dashboard_widgets');
        }
        $this->loader->add_filter('admin_footer_text', $plugin_admin, 'alloy_toggle_admin_role');
        $this->loader->add_action('wp_ajax_my_action', $plugin_admin, 'alloy_switch_role_callback');
    }
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Alloy_Base_Plugin_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Alloy_Base_Plugin_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
