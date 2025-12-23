<?php

namespace CompanyOrderMetadata;

if ( ! defined('ABSPATH') ) {
	// Permite cargar en tests sin WP
	define('ABSPATH', __DIR__);
}

final class ReferenceCode {

	public static function build( int $order_id, ?int $year = null ): string {
		$year = $year ?: (int) date('Y');
		return sprintf('CMP-%d-%d', $order_id, $year);
	}

	public static function apply_filter( string $code, $order = null ): string {
		return (string) apply_filters('company_order_reference_code', $code, $order);
	}
}
