<?php
/**
 * Admin settings page and option registration.
 *
 * @package MBWSB
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin UI.
 */
final class MBWSB_Admin {

	const PAGE_SLUG = 'mbwsb-settings';
	const OPTION_GROUP = 'mbwsb';

	/**
	 * @var MBWSB_Admin|null
	 */
	private static $instance = null;

	/**
	 * @return MBWSB_Admin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register option and sanitize callback.
	 */
	public function register_settings() {
		register_setting(
			self::OPTION_GROUP,
			MBWSB_Plugin::OPTION_BRANCHES,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_branches' ),
				'default'           => array(),
			)
		);
	}

	/**
	 * Sanitize repeater rows.
	 *
	 * @param mixed $value Raw value from POST.
	 * @return array<int, array{label: string, phone: string}>
	 */
	public function sanitize_branches( $value ) {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$clean = array();

		foreach ( $value as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$label = isset( $row['label'] ) ? sanitize_text_field( wp_unslash( $row['label'] ) ) : '';
			$phone = isset( $row['phone'] ) ? wp_unslash( $row['phone'] ) : '';
			$phone = preg_replace( '/\D+/', '', $phone );

			if ( '' === $label && '' === $phone ) {
				continue;
			}

			$clean[] = array(
				'label' => $label,
				'phone' => $phone,
			);
		}

		return $clean;
	}

	/**
	 * Add submenu under Settings.
	 */
	public function add_menu() {
		add_options_page(
			__( 'Multi-Branch WhatsApp', 'multi-branch-whatsapp-sticky-button' ),
			__( 'WhatsApp Hatları', 'multi-branch-whatsapp-sticky-button' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin CSS/JS on our page only.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}

		$ver_css = MBWSB_VERSION;
		$ver_js  = MBWSB_VERSION;
		$css     = MBWSB_PLUGIN_URL . 'assets/css/admin.css';
		$js      = MBWSB_PLUGIN_URL . 'assets/js/admin.js';

		$css_path = MBWSB_PLUGIN_DIR . 'assets/css/admin.css';
		$js_path  = MBWSB_PLUGIN_DIR . 'assets/js/admin.js';
		if ( file_exists( $css_path ) ) {
			$ver_css = (string) filemtime( $css_path );
		}
		if ( file_exists( $js_path ) ) {
			$ver_js = (string) filemtime( $js_path );
		}

		wp_enqueue_style( 'mbwsb-admin', $css, array(), $ver_css );
		wp_enqueue_script( 'mbwsb-admin', $js, array(), $ver_js, true );
	}

	/**
	 * Render settings form.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$branches = get_option( MBWSB_Plugin::OPTION_BRANCHES, array() );
		if ( ! is_array( $branches ) ) {
			$branches = array();
		}
		if ( empty( $branches ) ) {
			$branches = array(
				array(
					'label' => '',
					'phone' => '',
				),
			);
		}

		?>
		<div class="wrap mbwsb-admin-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Şube adı ve ülke kodu dahil telefon numarası girin. Örnek: 905551234567', 'multi-branch-whatsapp-sticky-button' ); ?>
			</p>

			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_GROUP );
				?>

				<table class="widefat striped mbwsb-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Şube / İsim', 'multi-branch-whatsapp-sticky-button' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Telefon (ülke kodu ile)', 'multi-branch-whatsapp-sticky-button' ); ?></th>
							<th scope="col" class="mbwsb-col-actions"><?php esc_html_e( 'İşlem', 'multi-branch-whatsapp-sticky-button' ); ?></th>
						</tr>
					</thead>
					<tbody id="mbwsb-rows">
						<?php
						foreach ( $branches as $index => $row ) {
							$label = isset( $row['label'] ) ? (string) $row['label'] : '';
							$phone = isset( $row['phone'] ) ? (string) $row['phone'] : '';
							$this->render_row( (int) $index, $label, $phone );
						}
						?>
					</tbody>
				</table>

				<p>
					<button type="button" class="button button-secondary" id="mbwsb-add-row">
						<?php esc_html_e( 'Yeni Hat Ekle', 'multi-branch-whatsapp-sticky-button' ); ?>
					</button>
				</p>

				<?php submit_button( __( 'Kaydet', 'multi-branch-whatsapp-sticky-button' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Output one table row.
	 *
	 * @param int|string $index Row index or placeholder.
	 * @param string     $label Label value.
	 * @param string     $phone Phone value.
	 */
	private function render_row( $index, $label, $phone ) {
		$name_base = 'mbwsb_branches[' . $index . ']';
		?>
		<tr class="mbwsb-row">
			<td>
				<label class="screen-reader-text" for="<?php echo esc_attr( 'mbwsb-label-' . $index ); ?>">
					<?php esc_html_e( 'Şube / İsim', 'multi-branch-whatsapp-sticky-button' ); ?>
				</label>
				<input
					type="text"
					class="regular-text"
					id="<?php echo esc_attr( 'mbwsb-label-' . $index ); ?>"
					name="<?php echo esc_attr( $name_base . '[label]' ); ?>"
					value="<?php echo esc_attr( $label ); ?>"
					autocomplete="organization"
				/>
			</td>
			<td>
				<label class="screen-reader-text" for="<?php echo esc_attr( 'mbwsb-phone-' . $index ); ?>">
					<?php esc_html_e( 'Telefon', 'multi-branch-whatsapp-sticky-button' ); ?>
				</label>
				<input
					type="text"
					class="regular-text"
					id="<?php echo esc_attr( 'mbwsb-phone-' . $index ); ?>"
					name="<?php echo esc_attr( $name_base . '[phone]' ); ?>"
					value="<?php echo esc_attr( $phone ); ?>"
					inputmode="tel"
					autocomplete="tel"
				/>
			</td>
			<td class="mbwsb-col-actions">
				<button type="button" class="button-link-delete mbwsb-remove-row" aria-label="<?php esc_attr_e( 'Satırı sil', 'multi-branch-whatsapp-sticky-button' ); ?>">
					<?php esc_html_e( 'Sil', 'multi-branch-whatsapp-sticky-button' ); ?>
				</button>
			</td>
		</tr>
		<?php
	}
}
