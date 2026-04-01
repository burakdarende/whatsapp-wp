<?php
/**
 * Plugin Name:       Multi-Branch WhatsApp Sticky Button
 * Description:       Sol altta sabit WhatsApp butonu; birden fazla şube hattı ve açılır menü.
 * Version:           1.0.3
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

define( 'MBWSB_VERSION', '1.0.3' );
define( 'MBWSB_PLUGIN_FILE', __FILE__ );
define( 'MBWSB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MBWSB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * GitHub güncellemeleri: `https://github.com/kullanici/repo/` (sonunda / olsun).
 * Boş bırakılırsa uzaktan güncelleme kontrolü yapılmaz.
 */
define( 'MBWSB_GITHUB_REPO_URL', 'https://github.com/burakdarende/whatsapp-wp' );

/**
 * Özel (private) repo için: GitHub Personal Access Token (classic), sadece repo okuma.
 * Public repoda boş bırakın.
 */
define( 'MBWSB_GITHUB_TOKEN', '' );

require_once MBWSB_PLUGIN_DIR . 'includes/class-plugin.php';

MBWSB_Plugin::instance();

require_once MBWSB_PLUGIN_DIR . 'includes/class-updater.php';
