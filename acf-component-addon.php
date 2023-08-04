<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.zealousweb.com/
 * @since             1.0.0
 * @package           Acf_Component_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Component Addon
 * Plugin URI:        https://www.zealousweb.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            ZealousWeb
 * Author URI:        https://www.zealousweb.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-component-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ACF_COMPONENT_ADDON_VERSION', '1.0.0' );

if ( !defined( 'THEME_DIRECTORY_COMPONENTS_PATH' ) ) {
	define( 'THEME_DIRECTORY_COMPONENTS_PATH', get_template_directory().'/template-part/components/' );
}

if ( !defined( 'THEME_DIRECTORY_PATH' ) ) {
	define( 'THEME_DIRECTORY_PATH', get_template_directory() );
}

if ( !defined( 'THEME_DIRECTORY_URL' ) ) {
	define( 'THEME_DIRECTORY_URL', get_template_directory_uri() );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acf-component-addon-activator.php
 */
function activate_acf_component_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf-component-addon-activator.php';
	Acf_Component_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acf-component-addon-deactivator.php
 */
function deactivate_acf_component_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf-component-addon-deactivator.php';
	Acf_Component_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_acf_component_addon' );
register_deactivation_hook( __FILE__, 'deactivate_acf_component_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-acf-component-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acf_component_addon() {
	$plugin = new Acf_Component_Addon();
	$plugin->run();
}
run_acf_component_addon();

add_action('plugins_loaded', function () {
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    if (!is_plugin_active('advanced-custom-fields-pro/acf.php')) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// add_action('admin_notices', 'acf_admin_notice');
		$error_message = 'Your custom plugin requires the <a href="https://www.advancedcustomfields.com/">ACF Pro plugin</a> to be active. Please install and activate ACF Pro before activating this plugin.';
        add_action('admin_notices', function() use ($error_message) {
            echo '<div class="custom-notice notice notice-warning is-dismissible"><p>' . $error_message . '</p></div>';
			echo '<style>.custom-notice + #message{display:none;}</style>';
        });
    }
});