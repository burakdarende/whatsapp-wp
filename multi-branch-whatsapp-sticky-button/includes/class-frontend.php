<?php
/**
 * Frontend assets and markup.
 *
 * @package MBWSB
 */

defined( 'ABSPATH' ) || exit;

/**
 * Public-facing sticky button.
 */
final class MBWSB_Frontend {

	/**
	 * @var MBWSB_Frontend|null
	 */
	private static $instance = null;

	/**
	 * @return MBWSB_Frontend
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );
		add_action( 'wp_footer', array( $this, 'render' ), 20 );
	}

	/**
	 * Build safe branch list for JS (label + wa.me URL).
	 *
	 * @return array<int, array{label: string, url: string}>
	 */
	public static function get_public_branches() {
		$raw = get_option( MBWSB_Plugin::OPTION_BRANCHES, array() );
		if ( ! is_array( $raw ) ) {
			return array();
		}

		$out = array();

		foreach ( $raw as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
			$phone = isset( $row['phone'] ) ? (string) $row['phone'] : '';
			$digits = preg_replace( '/\D+/', '', $phone );

			if ( '' === $label || '' === $digits ) {
				continue;
			}

			$url = 'https://wa.me/' . $digits;
			$url = esc_url_raw( $url );

			$out[] = array(
				'label' => $label,
				'url'   => $url,
			);
		}

		return $out;
	}

	/**
	 * Enqueue CSS/JS only when there is at least one valid branch.
	 */
	public function enqueue() {
		$branches = self::get_public_branches();
		if ( empty( $branches ) ) {
			return;
		}

		$css_rel = 'assets/css/frontend.css';
		$js_rel  = 'assets/js/frontend.js';
		$css     = MBWSB_PLUGIN_URL . $css_rel;
		$js      = MBWSB_PLUGIN_URL . $js_rel;

		$ver_css = MBWSB_VERSION;
		$ver_js  = MBWSB_VERSION;

		if ( file_exists( MBWSB_PLUGIN_DIR . $css_rel ) ) {
			$ver_css = (string) filemtime( MBWSB_PLUGIN_DIR . $css_rel );
		}
		if ( file_exists( MBWSB_PLUGIN_DIR . $js_rel ) ) {
			$ver_js = (string) filemtime( MBWSB_PLUGIN_DIR . $js_rel );
		}

		wp_enqueue_style( 'mbwsb-frontend', $css, array(), $ver_css );
		wp_enqueue_script( 'mbwsb-frontend', $js, array(), $ver_js, true );

		wp_localize_script(
			'mbwsb-frontend',
			'mbwsbData',
			array(
				'branches' => $branches,
				'i18n'     => array(
					'openMenu'  => __( 'WhatsApp hatlarını aç', 'multi-branch-whatsapp-sticky-button' ),
					'closeMenu' => __( 'Menüyü kapat', 'multi-branch-whatsapp-sticky-button' ),
				),
			)
		);
	}

	/**
	 * Print root markup in footer (list filled by JS from localized data).
	 */
	public function render() {
		$branches = self::get_public_branches();
		if ( empty( $branches ) ) {
			return;
		}

		?>
		<div id="mbwsb-root" class="mbwsb" data-mbwsb-root hidden>
			<button
				type="button"
				class="mbwsb__trigger"
				id="mbwsb-trigger"
				aria-expanded="false"
				aria-haspopup="true"
				aria-controls="mbwsb-panel"
				aria-label="<?php echo esc_attr__( 'WhatsApp ile iletişim', 'multi-branch-whatsapp-sticky-button' ); ?>"
			>
				<span class="mbwsb__trigger-icon" aria-hidden="true">
					<?php echo self::whatsapp_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG. ?>
				</span>
			</button>
			<div
				class="mbwsb__panel"
				id="mbwsb-panel"
				role="menu"
				aria-labelledby="mbwsb-trigger"
			>
				<ul class="mbwsb__list" id="mbwsb-list" role="none"></ul>
			</div>
		</div>
		<?php
	}

	/**
	 * WhatsApp-style icon (inline SVG).
	 *
	 * @return string
	 */
	private static function whatsapp_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor" focusable="false" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
	}
}
