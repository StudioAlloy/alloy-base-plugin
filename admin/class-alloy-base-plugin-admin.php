<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       studio alloy.nl
 * @since      version 2
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
     * @since    version 2
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
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
     * The version of this plugin.
     *
     * @since    version 2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    version 2
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
     * @since    version 2
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
     * @since    version 2
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
    //     wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . '/admin/css/alloy-base-plugin-admin.css', false, 'version 2');
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
                #message.updated.woocommerce-message,
                .notice-info,
                .notice-error {
                    display: none;
                }
                </style>";
        }
    }
    // END Remove the default wordpress notifications for none admins -------------------------------------//

    //------------------------------------------------------//
    // Add admin settings page fields  
    // Added nov 2019 MvE
    //------------------------------------------------------//
    public function add_settings_page()
    {
        $this->plugin_screen_hook_suffix = add_submenu_page(
            'options-general.php',
            __($this->plugin_title . ' Settings', $this->plugin_name), // $menu_title
            __($this->plugin_title . ' Settings', $this->plugin_name), // $menu_title
            'upload_files', // $capability
            $this->plugin_name,  // $menu_slug
            array($this, 'display_settings_page'), // $function
            '', // $icon_url
            '3.0' // $postion number on menu from top
        );

        // Add a General section and fields
        add_settings_section(
            $this->option_name . '_general',
            __('General', $this->plugin_name),
            array($this, $this->option_name . '_general_cb'),
            $this->plugin_name
        );
        add_settings_field(
            $this->option_name . '_upload_size_limit',
            __('Set upload_size_limit', $this->plugin_name),
            array($this, $this->option_name . '_upload_size_limit_cb'),
            $this->plugin_name,
            $this->option_name . '_general',
            array('label_for' => $this->option_name . '_upload_size_limit')
        );
        register_setting($this->plugin_name, $this->option_name . '_upload_size_limit', 'interval');

        add_settings_field(
            $this->option_name . '_enable_comments',
            __('Enable comments', $this->plugin_name),
            array($this, $this->option_name . '_enable_comments_cb'),
            $this->plugin_name,
            $this->option_name . '_general',
            array('label_for' => $this->option_name . '_enable_comments')
        );
        register_setting($this->plugin_name, $this->option_name . '_enable_comments');

        // Add Data Studio section and fields
        add_settings_section(
            $this->option_name . '_data_studio',
            __('Data Studio', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_cb'),
            $this->plugin_name
        );
        add_settings_field(
            $this->option_name . '_data_studio_id',
            __('Data Studio ID', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_id_cb'),
            $this->plugin_name,
            $this->option_name . '_data_studio',
            array('label_for' => $this->option_name . '_data_studio_id')
        );
        register_setting($this->plugin_name, $this->option_name . '_data_studio_id', 'string');

        add_settings_field(
            $this->option_name . '_data_studio_page_one',
            __('Page one', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_page_one_cb'),
            $this->plugin_name,
            $this->option_name . '_data_studio',
            array('label_for' => $this->option_name . '_data_studio_page_one')
        );
        register_setting($this->plugin_name, $this->option_name . '_data_studio_page_one', 'string');

        add_settings_field(
            $this->option_name . '_data_studio_page_two',
            __('Page two', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_page_two_cb'),
            $this->plugin_name,
            $this->option_name . '_data_studio',
            array('label_for' => $this->option_name . '_data_studio_page_two')
        );
        register_setting($this->plugin_name, $this->option_name . '_data_studio_page_two', 'string');

        add_settings_field(
            $this->option_name . '_data_studio_page_three',
            __('Page three', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_page_three_cb'),
            $this->plugin_name,
            $this->option_name . '_data_studio',
            array('label_for' => $this->option_name . '_data_studio_page_three')
        );
        register_setting($this->plugin_name, $this->option_name . '_data_studio_page_three', 'string');

        add_settings_field(
            $this->option_name . '_data_studio_page_four',
            __('Page four', $this->plugin_name),
            array($this, $this->option_name . '_data_studio_page_four_cb'),
            $this->plugin_name,
            $this->option_name . '_data_studio',
            array('label_for' => $this->option_name . '_data_studio_page_four')
        );
        register_setting($this->plugin_name, $this->option_name . '_data_studio_page_four', 'string');
    }
    /**
     * Render the options page for plugin
     *
     * @since  version 2
     */
    public function display_settings_page()
    {
        include_once 'partials/' . $this->plugin_name . '-admin-display.php';
    }
    /**
     * Render the text for the general section
     *
     * @since  version 2
     */
    public function alloy_base_plugin_general_cb()
    {
        echo '<p>' . __('Please change the settings accordingly.', $this->plugin_name) . '</p>';
    }
    public function alloy_base_plugin_data_studio_cb()
    {
        echo '<p>' . __('If you leave this blank a default widget will be shown with our contact info.', $this->plugin_name) . '</p>';
    }
    /**
     * Render the treshold day input for this plugin
     *
     * @since  version 2
     */
    /**/
    public function alloy_base_plugin_upload_size_limit_cb()
    {
        $option = get_option($this->option_name . '_upload_size_limit');
        echo '<input type="number" name="' . $this->option_name . '_upload_size_limit' . '" id="' . $this->option_name . '_upload_size_limit' . '" value="' . $option . '"> kb';
        echo '<p>Set to 0 to turn off. Default is 1mb</p>';
    }
    public function set_quota_upload_size()
    {
        $value = esc_attr(get_option($this->option_name . '_upload_size_limit'));
        if ($value <= 0 || $value == '' || $value == NULL) $value = 999;
        return $value * 1024;
    }

    public function alloy_base_plugin_enable_comments_cb()
    {
        $option = get_option($this->option_name . '_enable_comments');
        // echo '<input type="checkbox" name="' . $this->option_name . '_enable_comments' . '" id="' . $this->option_name . '_enable_comments' . '" value="' . $option . '"' . checked($option, '1') . '>';
        echo '<input type="checkbox" name="' . $this->option_name . '_enable_comments' . '" id="' . $this->option_name . '_enable_comments' . '" value="1"' . checked(1, $option, false) . '>';
    }
    public function alloy_base_plugin_data_studio_id_cb()
    {
        $option = get_option($this->option_name . '_data_studio_id');
        echo '<input type="text" name="' . $this->option_name . '_data_studio_id' . '" id="' . $this->option_name . '_data_studio_id' . '" value="' . $option . '"> ';
        echo '<p>This looks something like this <strong>1ew_0PQZDETWE54784_o_Yt_AMR_0</strong>';
    }
    public function alloy_base_plugin_data_studio_page_one_cb()
    {
        $option = get_option($this->option_name . '_data_studio_page_one');
        echo '<input type="text" name="' . $this->option_name . '_data_studio_page_one' . '" id="' . $this->option_name . '_data_studio_page_one' . '" value="' . $option . '"> ';
    }
    public function alloy_base_plugin_data_studio_page_two_cb()
    {
        $option = get_option($this->option_name . '_data_studio_page_two');
        echo '<input type="text" name="' . $this->option_name . '_data_studio_page_two' . '" id="' . $this->option_name . '_data_studio_page_two' . '" value="' . $option . '"> ';
    }
    public function alloy_base_plugin_data_studio_page_three_cb()
    {
        $option = get_option($this->option_name . '_data_studio_page_three');
        echo '<input type="text" name="' . $this->option_name . '_data_studio_page_three' . '" id="' . $this->option_name . '_data_studio_page_three' . '" value="' . $option . '"> ';
    }
    public function alloy_base_plugin_data_studio_page_four_cb()
    {
        $option = get_option($this->option_name . '_data_studio_page_four');
        echo '<input type="text" name="' . $this->option_name . '_data_studio_page_four' . '" id="' . $this->option_name . '_data_studio_page_four' . '" value="' . $option . '"> ';
    }
    // END Add admin settings page fields  -------------------------------------//

    //------------------------------------------------------//
    // Disable everything to do with 💬 comments 
    // Code found on : https://www.dfactory.eu/turn-off-disable-comments/
    // Added nov 2019 MvE
    //------------------------------------------------------///
    public function disable_comments_post_types_support()
    {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }
    // Close comments on the front-end
    public function disable_comments_status()
    {
        return false;
    }
    // Hide existing comments
    public function disable_comments_hide_existing_comments($comments)
    {
        $comments = array();
        return $comments;
    }
    // Remove comments page in menu
    public function disable_comments_admin_menu()
    {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }
    // Redirect any user trying to access comments page
    public function disable_comments_admin_menu_redirect()
    {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    }
    // Remove comments metabox from dashboard
    public function disable_comments_dashboard()
    {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }
    // Remove comments links from admin bar
    public function disable_comments_admin_bar()
    {
        if (is_admin_bar_showing()) remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }

    // END Disable everything to do with 💬 comments -------------------------------------//
    //------------------------------------------------------//
    // Clean up dashboard ⏹ widgets 
    //------------------------------------------------------//
    public function clean_up_dashboard()
    {
        remove_action('welcome_panel', 'wp_welcome_panel');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'side');
        remove_meta_box('rg_forms_dashboard', 'dashboard', 'side');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    }
    // END Clean up dashboard ⏹ widgets -------------------------------------//
    //------------------------------------------------------//
    // Clean up Admin bar 
    //------------------------------------------------------//
    public function clean_up_admin_bar($wp_admin_bar)
    {
        $wp_admin_bar->add_menu(array(
            'id' => 'alloy-logo',
            'title'  =>  '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASkAAAEsCAMAAACoirUDAAACf1BMVEX///81RUUwQEAyREQzQ0MzREQzREQ0REQwSEgzQ0MzREQzQ0MyRUUzREQ0RERIaGhHaGg8VFQzRERAcHBIampHaWlHaWk3S0tIaGhHaWlFZGQ0RkY0RERHaWlBXV1IampHaWlHaWlFampAW1s7UlJHaWlGZ2dGaWlHaWlDYmI+WVk5UFA2SUlCYGA9V1c4TU1gj49VgYFPd3dLcHBcj49cj49YiIhUgYFQenpMc3Nbj49Vg4NSfHxIa2tdj49ZiopXhoZOdXVKbm5cj49bjY1Tfn5dj49gj49cj49cj49cj49bj49bj49cj49cj49wtLRknZ1wtLRwtLRhmJhwtbVvsrJglpZwtLRwtbVflJRwtLRsra1dkZFwr69wtLRwtLRppqZws7NwtbVwt7dmoqJws7NwtLRqqKhws7NnpKR4wsJ1vr50u7uG2tqF2tqC1dWA0dF7x8dxtraF2tqB09OF2tp8yclzubmA39+F2tp4wsKF2tqE2Nhur6+F2tp9zMyD29trq6uH19d3wMCF2dl+zs6G2dlln5+G2tqF2tqE29uF29tim5s8VVXti3aU4uD3eGDPtaeU9vaG3Nz5emKx3tiX+vqJ4eH3fGTnlH+Z//+O6ur5e2LJvbGS8fH4e2Lzg2yl7+uV+PiI39//gGD5e2LhnImY/f2K4+P5e2L5e2K9zsT6emCf9/X6e2HVrZ36emLtjHaL5ub6e2KN6OiQ7+/5fGP5e2P5fGKP7e3DxbrbpJOr5uJ5xcWT8/O31s5Pd3dYh4daj49moaF2vb2U9fWZ//+a//+f//+Y//+a//+Z//+Z///zhGyZ//+Z///eoY+Z//+Z//+X///apZX5dJ49AAAA1XRSTlMAMBBw3//vgCCfr1Bgz49An/+/EGDP//8g7///QL//gN9wMP//j/9Qr/////////8g3///gP//////3////2D/////v///jxDvz0CfcFCvj//v//9g///PMP+f//8QcN//QK8g/4C//1D/YP//v///////MP+f//8Q7/9g///f/0D/IP+A/1D/j89wr/+vgP8g////r////0D////f//9w/////xDv////n///MP+//2D//4///1DPgP////////+vQDCAr+/PYBCfMN9w769Q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" style="height: 20px; width: 20px; margin-top:5px;">',
            'href'  => get_site_url(),
        ));
        if (is_admin()) {
            $wp_admin_bar->add_menu(array(
                'id' => 'go-to-homepage',
                'title' => '&#10094; &nbsp; Visit site',
                'href'  => get_site_url(),
                'meta'  => array('class' => 'go-to-homepage')
            ));
        } else {
            $wp_admin_bar->add_menu(array(
                'id' => 'go-to-dashboard',
                'title' => '&#10094; &nbsp; Dashboard',
                'href'  => get_admin_url(),
                'meta'  => array('class' => 'go-to-dashboard')
            ));
        }
    }
    public function clean_up_admin_header()
    {
        global $wp_admin_bar;
        if (get_option('sae_clean_admin')) {
            $wp_admin_bar->remove_menu('wp-logo');
            $wp_admin_bar->remove_menu('site-name');
            $wp_admin_bar->remove_menu('updates');
        }
        if (get_option('sae_clean_admin_header_comments') || get_option('sae_hide_all_comment_stuff')) $wp_admin_bar->remove_menu('comments');
        if (get_option('sae_clean_admin_header_new_content')) $wp_admin_bar->remove_menu('new-content');
    }

    // include GA tracking code before the closing head tag

    // END Clean up Admin bar -------------------------------------//
    public function change_admin_footer()
    {
        echo 'Website by <a href="//studioalloy.nl" target="_blank">Studio Alloy</a>';
    }
    public function example_add_dashboard_widgets()
    {
        wp_add_dashboard_widget(
            $this->option_name . '_data_studio_page_one',         // Widget slug.
            'Analytics by Studio Alloy',         // Title.
            $this->option_name . '_analytics_widget_page_one_function' // Display function.
        );

        wp_add_dashboard_widget(
            $this->option_name . '_data_studio_page_two',         // Widget slug.
            'Analytics by Studio Alloy',         // Title.
            $this->option_name . '_analytics_widget_page_one_function', // Display function.
            // 'dashboard',
            // 'side',
            // 'high'
        );
        add_meta_box(
            $this->option_name . '_data_studio_page_three',         // Widget slug.
            'Analytics by Studio Alloy',         // Title.
            $this->option_name . '_analytics_widget_page_one_function', // Display function.
            'dashboard',
            'side',
            'high'
        );
        add_meta_box(
            $this->option_name . '_data_studio_page_four',         // Widget slug.
            'Analytics by Studio Alloy',         // Title.
            $this->option_name . '_analytics_widget_page_one_function', // Display function.
            'dashboard',
            'side',
            'high'
        );
    }

    /* Custom admin bar on fontend ----------------------------------------- */
    // Include the Google Analytics Tracking Code (ga.js)
    // @ https://developers.google.com/analytics/devguides/collection/gajs/
    public function alloy_custom_admin_bar()
    { ?>
        <?php if (current_user_can('edit_posts')) : ?>
            <div class="alloy-custom-admin-bar">
                <a href="<?php echo get_dashboard_url(); ?>"><span>🖥</span> Dashboard</a>
                <a href="<?php echo get_edit_post_link(); ?>"><span>✏️</span> Edit <?php echo is_page() ? 'page' : 'post'; ?></a>
            </div>
            <style media="screen">
                .alloy-custom-admin-bar {
                    position: fixed;
                    bottom: 0;
                    left: 10%;
                    background-color: #0c162d;
                    z-index: 2147483647;
                }

                .alloy-custom-admin-bar a {
                    color: #eee;
                    padding: 10px;
                    font-size: 14px;
                    line-height: 1em;
                    border-right: 2px solid rgba(255, 255, 255, .4);
                    display: inline-block;
                    font-family: sans-serif !important;

                }

                .alloy-custom-admin-bar a:hover {
                    color: #e64;
                }

                .alloy-custom-admin-bar a:last-child {
                    border-right: none;
                }

                .alloy-custom-admin-bar a span {
                    /*font-size: 16px;*/
                    margin-right: 5px;
                }
            </style>
        <?php endif; ?>

<?php }

    // 🛑 END of file add code between here
}
function alloy_base_plugin_analytics_widget_page_one_function($post, $callback_args)
{
    $dataID = get_option("alloy_base_plugin_data_studio_id");
    $dataDefaultPages = ["8viH", "mIga", "sPga", "pTga",];
    if (strpos($callback_args['id'], 'one') !== false) {
        $dataPage = $dataDefaultPages[0];
    } elseif (strpos($callback_args['id'], 'two') !== false) {
        $dataPage = $dataDefaultPages[1];
    } elseif (strpos($callback_args['id'], 'three') !== false) {
        $dataPage = $dataDefaultPages[2];
    } elseif (strpos($callback_args['id'], 'four') !== false) {
        $dataPage = $dataDefaultPages[3];
    }
    // $dataPage = '8viH';
    $dataPage = get_option($callback_args['id']) ? get_option($callback_args['id']) : $dataPage;

    echo '<div class="alloy-analytics-container">
        <iframe src="https://datastudio.google.com/embed/reporting/' . $dataID . '/page/' . $dataPage . '" frameborder="0" style="border:0" allowfullscreen encrypted-media; gyroscope;></iframe>
    </div>';
}
