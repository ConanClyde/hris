<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRIS Daily Summary Digest</title>
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
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .header p {
            margin: 8px 0 0;
            font-size: 13px;
            opacity: 0.85;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }
        .stat-grid {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .stat-card {
            flex: 1;
            min-width: 120px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }
        .stat-card .number {
            font-size: 28px;
            font-weight: 700;
            color: #4f46e5;
        }
        .stat-card .label {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .item-list li {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #475569;
        }
        .item-list li:last-child {
            border-bottom: none;
        }
        .item-list li strong {
            color: #1e293b;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-amber {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }
        .empty-state {
            text-align: center;
            padding: 16px;
            color: #94a3b8;
            font-size: 14px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daily Summary Digest</h1>
            <p>{{ $generatedAt }}</p>
        </div>

        <div class="content">
            <!-- Quick Stats -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="number">{{ count($pendingLeaves) }}</div>
                    <div class="label">Pending Leaves</div>
                </div>
                <div class="stat-card">
                    <div class="number">{{ count($pendingTrainings) }}</div>
                    <div class="label">Pending Trainings</div>
                </div>
                <div class="stat-card">
                    <div class="number">{{ $pendingPdsCount }}</div>
                    <div class="label">PDS Reviews</div>
                </div>
                <div class="stat-card">
                    <div class="number">{{ count($outToday) }}</div>
                    <div class="label">Out Today</div>
                </div>
            </div>

            <!-- Pending Leave Applications -->
            <div class="section">
                <div class="section-title">🗓️ Pending Leave Applications</div>
                @if(count($pendingLeaves) > 0)
                    <ul class="item-list">
                        @foreach($pendingLeaves as $leave)
                            <li>
                                <strong>{{ $leave['employee_name'] }}</strong> —
                                {{ $leave['leave_type'] }}
                                ({{ $leave['from_date'] }} to {{ $leave['to_date'] }})
                                <span class="badge badge-amber">Pending</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">No pending leave applications.</div>
                @endif
            </div>

            <!-- Pending Training Requests -->
            <div class="section">
                <div class="section-title">📚 Pending Training Requests</div>
                @if(count($pendingTrainings) > 0)
                    <ul class="item-list">
                        @foreach($pendingTrainings as $training)
                            <li>
                                <strong>{{ $training['employee_name'] }}</strong> —
                                {{ $training['title'] }}
                                <span class="badge badge-amber">Pending</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">No pending training requests.</div>
                @endif
            </div>

            <!-- Who's Out Today -->
            <div class="section">
                <div class="section-title">🏖️ Who's Out Today</div>
                @if(count($outToday) > 0)
                    <ul class="item-list">
                        @foreach($outToday as $out)
                            <li>
                                <strong>{{ $out['employee_name'] }}</strong> —
                                {{ $out['leave_type'] }}
                                <span class="badge badge-green">On Leave</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">Everyone is in the office today! 🎉</div>
                @endif
            </div>

            <div class="signature">
                <p>Thank you,</p>
                <p><strong>HRIS System</strong><br>
                Human Resources Information System</p>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated daily digest from the HRIS System. Please do not reply.</p>
            <p>&copy; {{ date('Y') }} HRIS System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
