<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        $templates = NotificationTemplate::all();
        return view('admin.notifications.templates', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:notification_templates,name|max:100',
            'channel' => 'required|in:sms,email,whatsapp,push',
            'trigger_event' => 'required|in:fee_due,fee_received,absent,test_result,admission,general',
            'body' => 'required|string',
            'subject' => 'nullable|string|max:191',
        ]);

        NotificationTemplate::create($validated);

        return back()->with('success', 'Notification template created.');
    }
}
