<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'audience' => 'required|in:all,by_course,by_batch,fee_defaulters',
            'channels' => 'required|array',
            'message' => 'required|string|max:500',
        ]);

        $validated['sent_by'] = auth()->id();
        $validated['sent_at'] = now();

        Announcement::create($validated);

        return back()->with('success', 'Bulk broadcast queued for dispatch.');
    }
}
