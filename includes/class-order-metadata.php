<?php
/**
 * Order Metadata Handler
 *
 * @package CompanyOrderMetadata
 */

namespace CompanyOrderMetadata;
require_once COMPANY_ORDER_METADATA_PATH . 'includes/class-reference-code.php';

use WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles generation and storage of custom order reference codes
 */
class OrderMetadata {

	public static function init(): void {

		add_action(
			'woocommerce_checkout_order_processed',
			[ __CLASS__, 'maybe_add_reference_code' ],
			10,
			1
		);

		add_action(
			'woocommerce_admin_order_data_after_order_details',
			[ __CLASS__, 'render_admin_reference' ]
		);
	}

	/**
	 * Generate and save reference code once
	 */
	public static function maybe_add_reference_code( $order_id ): void {

		$order = wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		// Prevent duplicates
		if ( $order->get_meta( '_company_reference_code', true ) ) {
			return;
		}

	$reference = ReferenceCode::build( (int) $order_id );
$reference = ReferenceCode::apply_filter( $reference, $order );


		/**
		 * Allow external modification
		 */
		$reference = apply_filters(
			'company_order_reference_code',
			$reference,
			$order
		);

		$order->update_meta_data( '_company_reference_code', $reference );
		$order->save();
	}

	/**
	 * Display reference code in admin order page
	 */
	public static function render_admin_reference( WC_Order $order ): void {

		$reference = $order->get_meta( '_company_reference_code', true );

		if ( ! $reference ) {
			return;
		}
		?>
		<div class="order_data_column">
			<h4><?php esc_html_e( 'Company Reference Code', 'company-order-metadata' ); ?></h4>
			<p><strong><?php echo esc_html( $reference ); ?></strong></p>
		</div>
		<?php
	}
}
