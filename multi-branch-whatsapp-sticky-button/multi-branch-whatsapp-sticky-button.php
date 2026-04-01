<?php
/**
 * Plugin Name:       Multi-Branch WhatsApp Sticky Button
 * Description:       Sol altta sabit WhatsApp butonu; birden fazla şube hattı ve açılır menü.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            burakdarende
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       multi-branch-whatsapp-sticky-button
 *
 * @package MBWSB
 */

defined( 'ABSPATH' ) || exit;

define( 'MBWSB_VERSION', '1.0.0' );
define( 'MBWSB_PLUGIN_FILE', __FILE__ );
define( 'MBWSB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MBWSB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once MBWSB_PLUGIN_DIR . 'includes/class-plugin.php';

MBWSB_Plugin::instance();
