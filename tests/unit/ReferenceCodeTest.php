<?php

use PHPUnit\Framework\TestCase;
use CompanyOrderMetadata\ReferenceCode;

final class Test_ReferenceCode extends TestCase
{

	protected function setUp(): void
	{
		parent::setUp();
		$GLOBALS['__test_filters'] = [];
	}
	public function test_build_generates_expected_format()
	{
		$code = ReferenceCode::build(123, 2026);

		$this->assertSame('CMP-123-2026', $code);

		$parts = explode('-', $code);
		$this->assertCount(3, $parts);
		$this->assertSame('CMP', $parts[0]);
		$this->assertSame('123', $parts[1]);
		$this->assertSame('2026', $parts[2]);
	}

	public function test_build_uses_explicit_year()
	{
		$this->assertSame('CMP-3324-2025', ReferenceCode::build(3324, 2025));
	}

	public function test_build_uses_current_year_when_not_provided()
	{
		$year = (int) date('Y');
		$this->assertSame("CMP-10-$year", ReferenceCode::build(10));
	}

	public function test_apply_filter_overrides_code()
	{
		add_filter('company_order_reference_code', function ($code, $order) {
			return 'OVERRIDDEN';
		}, 10, 2);

		$this->assertSame(
			'OVERRIDDEN',
			ReferenceCode::apply_filter('CMP-3324-2025', (object) ['id' => 1])
		);
	}

	public function test_apply_filter_keeps_code_when_no_filter()
	{
		$this->assertSame(
			'CMP-2-2025',
			ReferenceCode::apply_filter('CMP-2-2025', null)
		);
	}
}
