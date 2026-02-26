<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Application Rejected</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
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
        .warning {
            background-color: #fdf2f2;
            border-left: 4px solid #dc3545;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .warning h3 {
            margin-top: 0;
            color: #dc3545;
            font-size: 18px;
        }
        .warning p {
            margin: 8px 0;
        }
        .warning strong {
            color: #dc3545;
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
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Application Rejected</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>We regret to inform you that your leave application has been <span class="status-badge">REJECTED</span>.</p>
            
            <div class="warning">
                <h3>Leave Details</h3>
                <p><strong>Leave Type:</strong> {{ $leaveType }}</p>
                <p><strong>Start Date:</strong> {{ $startDate }}</p>
                <p><strong>End Date:</strong> {{ $endDate }}</p>
                <p><strong>Duration:</strong> {{ $duration }} day(s)</p>
                <p><strong>Rejected By:</strong> {{ $approverName }}</p>
                @if($reason)
                <p><strong>Reason:</strong> {{ $reason }}</p>
                @endif
            </div>
            
            <p>If you wish to appeal this decision or submit a new application, please contact the HR department for further guidance.</p>
            
            <p>Thank you for your understanding.</p>
            
            <div class="signature">
                <p>Best regards,</p>
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