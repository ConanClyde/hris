<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Holiday Added</title>
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
        .holiday-badge {
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
            <h1>New Holiday Added</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>All</strong>,</p>
            
            <p>A new holiday has been added to the calendar. Please take note of the upcoming holiday.</p>
            
            <div class="info">
                <h3>Holiday Details</h3>
                <p><strong>Holiday Name:</strong> {{ $holidayName }}</p>
                <p><strong>Date:</strong> {{ $holidayDate }}</p>
                <p><strong>Type:</strong> {{ $holidayType }}</p>
                <p><strong>Added By:</strong> {{ $addedBy }}</p>
                @if($description)
                <p><strong>Description:</strong> {{ $description }}</p>
                @endif
            </div>
            
            <p>Please plan your work schedules accordingly. All employees are entitled to this holiday off.</p>
            
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