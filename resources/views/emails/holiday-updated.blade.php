<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Holiday Updated</title>
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
            background: linear-gradient(135deg, #ffc107, #e0a800);
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
            background-color: #fffde7;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .warning h3 {
            margin-top: 0;
            color: #856404;
            font-size: 18px;
        }
        .warning p {
            margin: 8px 0;
        }
        .warning strong {
            color: #856404;
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
        .update-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Holiday Updated</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>All</strong>,</p>
            
            <p>A holiday has been updated. Please take note of the changes to the calendar.</p>
            
            <div class="warning">
                <h3>Updated Holiday Details</h3>
                <p><strong>Holiday Name:</strong> {{ $holidayName }}</p>
                <p><strong>Updated Date:</strong> {{ $holidayDate }}</p>
                <p><strong>Type:</strong> {{ $holidayType }}</p>
                <p><strong>Updated By:</strong> {{ $updatedBy }}</p>
                @if($description)
                <p><strong>Description:</strong> {{ $description }}</p>
                @endif
            </div>
            
            <p>Please update your work schedules accordingly. The changes take effect immediately.</p>
            
            <p>Thank you for your attention.</p>
            
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