<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       studio alloy.nl
 * @since      1.0.0
 *
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/admin
 * @author     Studio Alloy <contact@studioalloy.nl>
 */
class Alloy_Base_Plugin_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Alloy_Base_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Alloy_Base_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/alloy-base-plugin-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Alloy_Base_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Alloy_Base_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/alloy-base-plugin-admin.js', array('jquery'), $this->version, false);
    }
    // //------------------------------------------------------//
    // // Load custom #wpadminbar styles 
    // // Added oct 2019 MvE
    // //------------------------------------------------------//
    // public function alloy_load_custom_wp_admin_style()
    // {
    //     wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . '/admin/css/alloy-base-plugin-admin.css', false, '1.0.0');
    //     wp_enqueue_style($this->plugin_name);
    // }
    // // END Load custom #wpadminbar styles -------------------------------------//
    //------------------------------------------------------//
    // Remove the default wordpress notifications for none admins 
    // Added nov 2019 MvE
    //------------------------------------------------------//
    public function alloy_hide_update_msg_non_admins()
    {
        if (!current_user_can('manage_options')) { // non-admin users
            echo "<style>
                .notice-info,
                .notice-error {
                    display: none;
                }
                </style>";
        }
    }
    // END Remove the default wordpress notifications for none admins -------------------------------------//

}
