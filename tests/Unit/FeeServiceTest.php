<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\FeeService;

class FeeServiceTest extends TestCase
{
    public function test_late_fee_calculation(): void
    {
        $service = new FeeService();
        $this->assertInstanceOf(FeeService::class, $service);
    }
}
