<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AttendanceService;

class AttendanceServiceTest extends TestCase
{
    public function test_attendance_service_initialization(): void
    {
        $service = new AttendanceService();
        $this->assertInstanceOf(AttendanceService::class, $service);
    }
}
