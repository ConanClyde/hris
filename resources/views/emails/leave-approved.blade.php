<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Application Approved</title>
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
            background: linear-gradient(135deg, #1a73e8, #0d6efd);
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
        .highlight {
            background-color: #f0f7ff;
            border-left: 4px solid #1a73e8;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .highlight h3 {
            margin-top: 0;
            color: #1a73e8;
            font-size: 18px;
        }
        .highlight p {
            margin: 8px 0;
        }
        .highlight strong {
            color: #1a73e8;
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
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Application Approved</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>We are pleased to inform you that your leave application has been <span class="status-badge">APPROVED</span>.</p>
            
            <div class="highlight">
                <h3>Leave Details</h3>
                <p><strong>Leave Type:</strong> {{ $leaveType }}</p>
                <p><strong>Start Date:</strong> {{ $startDate }}</p>
                <p><strong>End Date:</strong> {{ $endDate }}</p>
                <p><strong>Duration:</strong> {{ $duration }} day(s)</p>
                <p><strong>Approved By:</strong> {{ $approverName }}</p>
            </div>
            
            <p>Your leave has been recorded in the system. Please ensure that your duties are properly covered during your absence.</p>
            
            <p>If you have any questions or concerns, please contact the HR department.</p>
            
            <div class="signature">
                <p>Thank you,</p>
                <p><strong>HR Department</strong><br>
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