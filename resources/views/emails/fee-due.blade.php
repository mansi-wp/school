<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .due-box { background: #fef2f2; border: 1px solid #f87171; padding: 15px; border-radius: 6px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🎓 Apex Coaching Academy</h2>
    </div>
    <div class="content">
        <p>Dear {{ $parent_name ?? 'Parent' }},</p>
        <p>This is a formal reminder regarding the upcoming course fee installment for your ward, <strong>{{ $student_name }}</strong> (Roll No: {{ $roll_number }}).</p>
        
        <div class="due-box">
            <h4 style="margin-top:0; color: #b91c1c;">Installment Due Notice</h4>
            <p><strong>Due Amount:</strong> ₹{{ number_format($due_amount, 2) }}</p>
            <p><strong>Due Date:</strong> {{ $due_date }}</p>
            <p><strong>Grace Days:</strong> 5 Days (Late fee of ₹50/day applicable thereafter)</p>
        </div>

        <p>You may clear this installment by visiting our cashier terminal or using the online parent portal.</p>
        <p>Warm regards,<br><strong>Accounts & Finance Department</strong><br>Apex Coaching Academy</p>
    </div>
    <div class="footer">
        Plot 42, Education Hub, Knowledge Park III, Greater Noida, UP - 201310 • +91 98765 43210
    </div>
</body>
</html>
