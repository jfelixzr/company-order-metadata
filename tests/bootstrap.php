<?php

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

// Stubs para filtros WP (solo lo que necesitamos)
$GLOBALS['__test_filters'] = [];

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $fn, $priority = 10, $accepted_args = 1 ) {
		$GLOBALS['__test_filters'][ $tag ][] = $fn;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $tag, $value, ...$args ) {
		if ( empty( $GLOBALS['__test_filters'][ $tag ] ) ) {
			return $value;
		}
		foreach ( $GLOBALS['__test_filters'][ $tag ] as $fn ) {
			$value = $fn( $value, ...$args );
		}
		return $value;
	}
}


require_once dirname(__DIR__) . '/includes/class-reference-code.php';
