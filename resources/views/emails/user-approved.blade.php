<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Approved</title>
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
            background: linear-gradient(135deg, #28a745, #218838);
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
        .success {
            background-color: #f0fff4;
            border-left: 4px solid #28a745;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .success h3 {
            margin-top: 0;
            color: #28a745;
            font-size: 18px;
        }
        .success p {
            margin: 8px 0;
        }
        .success strong {
            color: #28a745;
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
        .welcome-badge {
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
            <h1>Account Approved</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <p>Congratulations! Your account registration has been <span class="welcome-badge">APPROVED</span>.</p>
            
            <div class="success">
                <h3>Account Details</h3>
                <p><strong>Full Name:</strong> {{ $fullName }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Role:</strong> {{ $role }}</p>
                <p><strong>Approved By:</strong> {{ $approverName }}</p>
            </div>
            
            <p>You can now access the HRIS system using your credentials. If you have forgotten your password, please use the "Forgot Password" feature to reset it.</p>
            
            <p>If you have any questions or need assistance, please contact the HR department.</p>
            
            <p>Welcome aboard!</p>
            
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