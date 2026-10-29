<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .score-box { background: #eef2ff; border: 1px solid #c7d2fe; padding: 15px; border-radius: 6px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📊 Examination Results Published</h2>
    </div>
    <div class="content">
        <p>Dear {{ $parent_name ?? 'Parent' }},</p>
        <p>The evaluation results for <strong>{{ $test_name }}</strong> have been compiled and published for <strong>{{ $student_name }}</strong>.</p>
        
        <div class="score-box">
            <h4 style="margin-top:0; color: #4338ca;">Scorecard Summary</h4>
            <p><strong>Subject:</strong> {{ $subject }}</p>
            <p><strong>Marks Obtained:</strong> {{ $marks_obtained }} / {{ $total_marks }}</p>
            <p><strong>Institute Rank:</strong> #{{ $rank ?? 'N/A' }}</p>
            <p><strong>Percentile Score:</strong> {{ $percentile }}%</p>
            @if(!empty($remarks))
                <p><strong>Faculty Remarks:</strong> <em>{{ $remarks }}</em></p>
            @endif
        </div>

        <p>Log in to the student portal to review the detailed question-level error analysis and solution key.</p>
        <p>Best regards,<br><strong>Academic Examination Cell</strong><br>Apex Coaching Academy</p>
    </div>
    <div class="footer">
        Apex Coaching ERP Examination Division
    </div>
</body>
</html>
