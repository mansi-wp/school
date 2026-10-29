<?php

namespace App\Services;

use App\Models\Test;
use App\Models\TestResult;

class ResultService
{
    public function calculateRanks(Test $test): void
    {
        $validResults = TestResult::where('test_id', $test->id)
            ->where('is_absent', 0)
            ->orderByDesc('marks_obtained')
            ->get();

        $count = $validResults->count();

        foreach ($validResults as $idx => $res) {
            $rank = $idx + 1;
            $percentile = $count > 0 ? round((($count - $rank + 1) / $count) * 100, 2) : 100;
            $res->update([
                'rank' => $rank,
                'percentile' => $percentile,
            ]);
        }
    }
}
