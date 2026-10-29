<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Fee Due Reminder',
                'channel' => 'sms',
                'trigger_event' => 'fee_due',
                'subject' => null,
                'body' => 'Dear Parent, fee installment of {{due_amount}} for {{student_name}} is due on {{due_date}}. Please clear online or visit cashier. - APEX',
                'is_active' => true,
            ],
            [
                'name' => 'Fee Receipt Acknowledgment',
                'channel' => 'whatsapp',
                'trigger_event' => 'fee_received',
                'subject' => null,
                'body' => 'Thank you! Received fee payment of {{amount_paid}} for {{student_name}} via {{payment_method}}. Receipt No: {{receipt_number}}. - APEX',
                'is_active' => true,
            ],
            [
                'name' => 'Student Absent Alert',
                'channel' => 'sms',
                'trigger_event' => 'absent',
                'subject' => null,
                'body' => 'Notice: {{student_name}} was marked ABSENT today ({{attendance_date}}) in {{batch}} session. Please contact reception if unexpected. - APEX',
                'is_active' => true,
            ],
            [
                'name' => 'Test Result Published',
                'channel' => 'whatsapp',
                'trigger_event' => 'test_result',
                'subject' => null,
                'body' => 'Results out! {{student_name}} scored {{marks_obtained}}/{{total_marks}} in {{test_name}} (Subject: {{subject}}). Check student portal for rank. - APEX',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            NotificationTemplate::firstOrCreate(['name' => $tmpl['name']], $tmpl);
        }
    }
}
