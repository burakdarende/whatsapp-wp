<?php
/**
 * GitHub üzerinden güncelleme (Plugin Update Checker).
 *
 * Ana dosyada MBWSB_GITHUB_REPO_URL tanımlı ve boş değilse yüklenir.
 *
 * @package MBWSB
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MBWSB_GITHUB_REPO_URL' ) || '' === MBWSB_GITHUB_REPO_URL ) {
	return;
}

$mbwsb_puc_autoload = MBWSB_PLUGIN_DIR . 'vendor/autoload.php';
if ( ! is_readable( $mbwsb_puc_autoload ) ) {
	return;
}

require_once $mbwsb_puc_autoload;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * @var \YahnisElsts\PluginUpdateChecker\v5p0\Vcs\PluginUpdateChecker $mbwsb_update_checker
 */
$mbwsb_update_checker = PucFactory::buildUpdateChecker(
	MBWSB_GITHUB_REPO_URL,
	MBWSB_PLUGIN_FILE,
	'multi-branch-whatsapp-sticky-button',
	12
);

$mbwsb_vcs = $mbwsb_update_checker->getVcsApi();
if ( $mbwsb_vcs && method_exists( $mbwsb_vcs, 'enableReleaseAssets' ) ) {
	/**
	 * Repo kökünde eklenti alt klasöründeyse, GitHub'ın otomatik zipball'ı WP yapısına uymayabilir.
	 * Release'e `multi-branch-whatsapp-sticky-button.zip` ekle (içinde `multi-branch-whatsapp-sticky-button/` klasörü).
	 */
	$mbwsb_vcs->enableReleaseAssets( '/^multi-branch-whatsapp-sticky-button\.zip$/i' );
}

if ( defined( 'MBWSB_GITHUB_TOKEN' ) && MBWSB_GITHUB_TOKEN && method_exists( $mbwsb_update_checker, 'setAuthentication' ) ) {
	$mbwsb_update_checker->setAuthentication( MBWSB_GITHUB_TOKEN );
}
