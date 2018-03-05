<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://hraza.de
 * @since             1.0.0
 * @package           League_Stats
 *
 * @wordpress-plugin
 * Plugin Name:       League Statistics
 * Plugin URI:        http://hraza.de
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hussnain Raza
 * Author URI:        http://hraza.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       league-stats
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-league-stats-activator.php
 */
function activate_league_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-league-stats-activator.php';
	League_Stats_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-league-stats-deactivator.php
 */
function deactivate_league_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-league-stats-deactivator.php';
	League_Stats_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_league_stats' );
register_deactivation_hook( __FILE__, 'deactivate_league_stats' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-league-stats.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_league_stats() {

	$plugin = new League_Stats();
	$plugin->run();

}
run_league_stats();
