<?php
/**
 * Bootstrap: loads admin and frontend.
 *
 * @package MBWSB
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin singleton.
 */
final class MBWSB_Plugin {

	const OPTION_BRANCHES = 'mbwsb_branches';

	/** @var string 'left'|'right' */
	const OPTION_POSITION = 'mbwsb_position';

	/**
	 * @var MBWSB_Plugin|null
	 */
	private static $instance = null;

	/**
	 * @return MBWSB_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		require_once MBWSB_PLUGIN_DIR . 'includes/class-admin.php';
		require_once MBWSB_PLUGIN_DIR . 'includes/class-frontend.php';

		if ( is_admin() ) {
			MBWSB_Admin::instance();
		}
		MBWSB_Frontend::instance();
	}
}
