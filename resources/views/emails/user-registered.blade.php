<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New User Registration</title>
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
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
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
            background-color: #f5f3ff;
            border-left: 4px solid #6f42c1;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .info h3 {
            margin-top: 0;
            color: #6f42c1;
            font-size: 18px;
        }
        .info p {
            margin: 8px 0;
        }
        .info strong {
            color: #6f42c1;
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
        .alert-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New User Registration</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>HR Team</strong>,</p>
            
            <p>A new user has registered an account in the HRIS system. Please review the registration details below and approve or reject the account as appropriate.</p>
            
            <div class="info">
                <h3>User Registration Details</h3>
                <p><strong>Full Name:</strong> {{ $fullName }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Role Requested:</strong> {{ $role }}</p>
                <p><strong>Registration Date:</strong> {{ $registrationDate }}</p>
                <p><strong>Registration IP:</strong> {{ $ipAddress }}</p>
            </div>
            
            <p>Please log in to the HRIS system to review and process this registration request.</p>
            
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