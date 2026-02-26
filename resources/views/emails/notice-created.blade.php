<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Notice Published</title>
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
            background: linear-gradient(135deg, #6c757d, #5a6268);
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
        .notice {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .notice h3 {
            margin-top: 0;
            color: #6c757d;
            font-size: 18px;
        }
        .notice p {
            margin: 8px 0;
        }
        .notice strong {
            color: #495057;
        }
        .notice-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
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
        .notice-badge {
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
            <h1>New Notice Published</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>All</strong>,</p>
            
            <p>A new notice has been published. Please review the information below:</p>
            
            <div class="notice">
                <h3>Notice Details</h3>
                <p><strong>Title:</strong> {{ $noticeTitle }}</p>
                <p><strong>Category:</strong> {{ $noticeCategory }}</p>
                <p><strong>Publish Date:</strong> {{ $publishDate }}</p>
                <p><strong>Published By:</strong> {{ $publishedBy }}</p>
                <p><strong>Priority:</strong> {{ $priority }}</p>
            </div>
            
            <h4>Notice Content:</h4>
            <div class="notice-content">
                <p>{!! nl2br(e($noticeContent)) !!}</p>
            </div>
            
            <p>Please read the notice carefully and take appropriate action if required.</p>
            
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