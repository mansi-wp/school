<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #d97706; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .message-box { background: #fffbeb; border: 1px solid #fde68a; padding: 20px; border-radius: 6px; margin: 20px 0; font-size: 15px; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📢 {{ $title ?? 'Institute Announcement' }}</h2>
    </div>
    <div class="content">
        <p>Dear Student / Parent,</p>
        
        <div class="message-box">
            {!! nl2br(e($announcement_message ?? '')) !!}
        </div>

        <p>Warm regards,<br><strong>Apex Coaching Academy Administration</strong></p>
    </div>
    <div class="footer">
        Apex Coaching Official Broadcast
    </div>
</body>
</html>
