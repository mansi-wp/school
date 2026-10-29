<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    public function getMonthlyStats(int $batchId, string $yearMonth): array
    {
        $records = Attendance::where('batch_id', $batchId)
            ->where('attendance_date', 'LIKE', "{$yearMonth}%")
            ->get();

        $totalMarked = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();

        return [
            'total' => $totalMarked,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'percentage' => $totalMarked > 0 ? round(($present / $totalMarked) * 100, 1) : 0,
        ];
    }
}
