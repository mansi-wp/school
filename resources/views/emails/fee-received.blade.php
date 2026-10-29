<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .success-box { background: #ecfdf5; border: 1px solid #34d399; padding: 15px; border-radius: 6px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🎓 Fee Payment Acknowledgment</h2>
    </div>
    <div class="content">
        <p>Dear {{ $parent_name ?? 'Parent' }},</p>
        <p>Thank you! We have successfully received the fee payment for <strong>{{ $student_name }}</strong>.</p>
        
        <div class="success-box">
            <h4 style="margin-top:0; color: #047857;">Receipt Summary</h4>
            <p><strong>Receipt Number:</strong> {{ $receipt_number }}</p>
            <p><strong>Amount Paid:</strong> ₹{{ number_format($amount_paid, 2) }}</p>
            <p><strong>Payment Mode:</strong> {{ strtoupper($payment_method) }}</p>
            <p><strong>Date:</strong> {{ $payment_date }}</p>
            <p><strong>Remaining Balance:</strong> ₹{{ number_format($remaining_balance, 2) }}</p>
        </div>

        <p>An official electronic fee receipt has been archived in the student portal.</p>
        <p>Warm regards,<br><strong>Accounts & Finance Department</strong><br>Apex Coaching Academy</p>
    </div>
    <div class="footer">
        Apex Coaching ERP • System Generated Electronic Receipt
    </div>
</body>
</html>
