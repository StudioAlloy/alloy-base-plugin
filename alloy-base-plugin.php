<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              studio alloy.nl
 * @since             1.0.0
 * @package           Alloy_Base_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Alloy Base Plugin
 * Plugin URI:        studio alloy.nl
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Studio Alloy
 * Author URI:        studio alloy.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alloy-base-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('ALLOY_BASE_PLUGIN_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-alloy-base-plugin-activator.php
 */
function activate_alloy_base_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-alloy-base-plugin-activator.php';
    Alloy_Base_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-alloy-base-plugin-deactivator.php
 */
function deactivate_alloy_base_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-alloy-base-plugin-deactivator.php';
    Alloy_Base_Plugin_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_alloy_base_plugin');
register_deactivation_hook(__FILE__, 'deactivate_alloy_base_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-alloy-base-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alloy_base_plugin()
{

    $plugin = new Alloy_Base_Plugin();
    $plugin->run();
}
run_alloy_base_plugin();