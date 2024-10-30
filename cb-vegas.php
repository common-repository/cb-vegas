<?php

/**
 * The file that fires up this plugin.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @wordpress-plugin
 *                    Plugin Name:       cbVegas
 *                    Plugin URI:        https://wordpress.org/plugins/cb-vegas/
 *                    Description:       This plugin enables you to use the "Vegas Background Slideshow" for images on regular posts and pages.
 *                    Version:           0.3.6
 *                    Author:            demispatti
 *                    Contributors:      demispatti
 *                    Author URI:        https://demispatti.ch
 *                    License:           GPL-2.0+
 *                    License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *                    Text Domain:       cb-vegas
 *                    Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include the autoloader.
 *
 * @since 1.0.0
 */
include plugin_dir_path( __FILE__ ) . 'includes/class-cb-vegas-autoloader.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cb-vegas-activator.php
 */
function activate_cb_vegas() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cb-vegas-activator.php';
	CB_Vegas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cb-vegas-deactivator.php
 */
function deactivate_cb_vegas() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cb-vegas-deactivator.php';
	CB_Vegas_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cb_vegas' );
register_deactivation_hook( __FILE__, 'deactivate_cb_vegas' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cb-vegas.php';

/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
function run_cb_vegas() {

	$plugin = new CB_Vegas();
	$plugin->run();
}

run_cb_vegas();
