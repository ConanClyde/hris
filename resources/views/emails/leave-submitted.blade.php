<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Application Submitted</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #17a2b8, #138496);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin-bottom: 16px;
            color: #444;
        }
        .info {
            background-color: #f0faff;
            border-left: 4px solid #17a2b8;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .info h3 {
            margin-top: 0;
            color: #17a2b8;
            font-size: 18px;
        }
        .info p {
            margin: 8px 0;
        }
        .info strong {
            color: #17a2b8;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .signature {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .submitted-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Application Submitted</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>HR Team</strong>,</p>
            
            <p>A new leave application has been submitted. Please review the details below:</p>
            
            <div class="info">
                <h3>Leave Application Details</h3>
                <p><strong>Employee Name:</strong> {{ $employeeName }}</p>
                <p><strong>Leave Type:</strong> {{ $leaveType }}</p>
                <p><strong>Start Date:</strong> {{ $startDate }}</p>
                <p><strong>End Date:</strong> {{ $endDate }}</p>
                <p><strong>Duration:</strong> {{ $duration }} day(s)</p>
                <p><strong>Reason:</strong> {{ $reason }}</p>
                <p><strong>Submitted On:</strong> {{ $submittedDate }}</p>
            </div>
            
            <p>Please log in to the HRIS system to review and process this leave application.</p>
            
            <div class="signature">
                <p>Thank you,</p>
                <p><strong>HRIS System</strong><br>
                Human Resources Information System</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email from the HRIS System. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} HRIS System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>