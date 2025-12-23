<?php
/**
 * Plugin Name: Company Order Metadata
 * Plugin URI: https://jfelixdev.com/
 * Description: Extends WooCommerce to add custom internal reference codes to orders.
 * Version: 1.0.0
 * Author: Jose Zapata
 * Author URI: https://jfelixdev.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: company-order-metadata
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 9.8
 */
namespace CompanyOrderMetadata;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'COMPANY_ORDER_METADATA_VERSION', '1.0.0' );
define( 'COMPANY_ORDER_METADATA_PATH', plugin_dir_path( __FILE__ ) );
define( 'COMPANY_ORDER_METADATA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Declare WooCommerce HPOS compatibility
 */
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			__FILE__,
			true
		);
	}
});

final class CompanyOrderMetadataPlugin {

	private static ?CompanyOrderMetadataPlugin $instance = null;

	private function __construct() {}

	public static function get_instance(): CompanyOrderMetadataPlugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function boot(): void {

		if ( ! $this->is_woocommerce_active() ) {
			add_action( 'admin_notices', [ $this, 'woocommerce_missing_notice' ] );
			return;
		}

		$this->load_dependencies();

		// ✅ Correcto: inicializar aquí
		OrderMetadata::init();

		load_plugin_textdomain(
			'company-order-metadata',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	private function load_dependencies(): void {
		require_once COMPANY_ORDER_METADATA_PATH . 'includes/class-order-metadata.php';
	}

	private function is_woocommerce_active(): bool {
		return class_exists( 'WooCommerce' ) || did_action( 'woocommerce_loaded' );
	}

	public function woocommerce_missing_notice(): void {
		echo '<div class="notice notice-error"><p>'
			. esc_html__( 'Company Order Metadata requires WooCommerce to be installed and active.', 'company-order-metadata' )
			. '</p></div>';
	}

	public static function activate(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die(
				esc_html__( 'This plugin requires WooCommerce to be installed and active.', 'company-order-metadata' )
			);
		}
	}

	public static function deactivate(): void {}
}

/**
 * Start plugin
 */
add_action( 'plugins_loaded', function () {
	CompanyOrderMetadataPlugin::get_instance()->boot();
});

register_activation_hook(
	__FILE__,
	[ '\CompanyOrderMetadata\CompanyOrderMetadataPlugin', 'activate' ]
);

register_deactivation_hook(
	__FILE__,
	[ '\CompanyOrderMetadata\CompanyOrderMetadataPlugin', 'deactivate' ]
);