<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .alert-box { background: #fef2f2; border: 1px solid #f87171; padding: 15px; border-radius: 6px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>⚠️ Daily Attendance Alert</h2>
    </div>
    <div class="content">
        <p>Dear {{ $parent_name ?? 'Parent' }},</p>
        <p>This is to inform you that your ward, <strong>{{ $student_name }}</strong> (Roll No: {{ $roll_number }}), was recorded <strong>ABSENT</strong> today.</p>
        
        <div class="alert-box">
            <p><strong>Batch:</strong> {{ $batch_name }}</p>
            <p><strong>Attendance Date:</strong> {{ $date }}</p>
            <p><strong>Session:</strong> {{ ucfirst($session) }}</p>
        </div>

        <p>If this absence was unexpected or without prior sanctioned leave, please contact our administrative desk immediately at +91 98765 43210.</p>
        <p>Sincerely,<br><strong>Student Welfare & Discipline</strong><br>Apex Coaching Academy</p>
    </div>
    <div class="footer">
        Automated Attendance Communication System
    </div>
</body>
</html>
