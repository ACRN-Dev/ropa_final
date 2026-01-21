<!-- resources/views/risk/export-pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Risk Register Export</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            font-size: 11px;
        }
        
        .header {
            background: linear-gradient(to right, #f97316, #fb923c);
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: table-cell;
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }
        
        .summary-item h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-item .number {
            font-size: 24px;
            font-weight: bold;
            color: #f97316;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background: #f97316;
            color: white;
        }
        
        th {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .risk-item {
            page-break-inside: avoid;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            background: white;
        }
        
        .risk-title {
            font-size: 14px;
            font-weight: bold;
            color: #f97316;
            margin-bottom: 8px;
        }
        
        .risk-details {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .risk-detail-row {
            display: table-row;
        }
        
        .risk-detail-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 5px;
            background: #f5f5f5;
            border: 1px solid #eee;
        }
        
        .risk-detail-value {
            display: table-cell;
            width: 70%;
            padding: 5px;
            border: 1px solid #eee;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-critical {
            background: #fee;
            color: #c00;
            border: 1px solid #f00;
        }
        
        .badge-high {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .badge-medium {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #eab308;
        }
        
        .badge-low {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Risk Register Report</h1>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <div class="summary-item">
            <h3>Total Risks</h3>
            <div class="number">{{ $risks->count() }}</div>
        </div>
        <div class="summary-item">
            <h3>Critical</h3>
            <div class="number">{{ $risks->where('risk_level', 'critical')->count() }}</div>
        </div>
        <div class="summary-item">
            <h3>High</h3>
            <div class="number">{{ $risks->where('risk_level', 'high')->count() }}</div>
        </div>
        <div class="summary-item">
            <h3>Medium/Low</h3>
            <div class="number">{{ $risks->where('risk_level', '!=', 'critical')->where('risk_level', '!=', 'high')->count() }}</div>
        </div>
    </div>

    <!-- Summary Table -->
    <table>
        <thead>
            <tr>
                <th>Risk ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Risk Level</th>
                <th>Status</th>
                <th>Owner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($risks as $risk)
            <tr>
                <td>{{ $risk->risk_id }}</td>
                <td>{{ $risk->title }}</td>
                <td>{{ $risk->department }}</td>
                <td>
                    <span class="badge badge-{{ $risk->risk_level }}">
                        {{ ucfirst($risk->risk_level) }}
                    </span>
                </td>
                <td>{{ ucfirst(str_replace('_', ' ', $risk->status)) }}</td>
                <td>{{ $risk->response_owner ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detailed Risk Items -->
    @foreach($risks as $risk)
    <div class="risk-item">
        <div class="risk-title">{{ $risk->risk_id }} - {{ $risk->title }}</div>
        
        <div class="risk-details">
            <div class="risk-detail-row">
                <div class="risk-detail-label">Description</div>
                <div class="risk-detail-value">{{ $risk->description }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Category</div>
                <div class="risk-detail-value">{{ $risk->risk_category }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Department</div>
                <div class="risk-detail-value">{{ $risk->department }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Likelihood / Impact</div>
                <div class="risk-detail-value">{{ $risk->likelihood }} / {{ $risk->impact }} (Score: {{ $risk->likelihood * $risk->impact }})</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Risk Level</div>
                <div class="risk-detail-value">
                    <span class="badge badge-{{ $risk->risk_level }}">
                        {{ ucfirst($risk->risk_level) }}
                    </span>
                </div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Status</div>
                <div class="risk-detail-value">{{ ucfirst(str_replace('_', ' ', $risk->status)) }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Owner</div>
                <div class="risk-detail-value">{{ $risk->response_owner ?? 'N/A' }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Current Controls</div>
                <div class="risk-detail-value">{{ $risk->current_controls ?? 'N/A' }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Residual Risk Score</div>
                <div class="risk-detail-value">{{ $risk->residual_risk_score ?? 'N/A' }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Mitigation Plan</div>
                <div class="risk-detail-value">{{ $risk->mitigation_plan ?? 'N/A' }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Target Date</div>
                <div class="risk-detail-value">{{ $risk->target_date?->format('M d, Y') ?? 'N/A' }}</div>
            </div>
            <div class="risk-detail-row">
                <div class="risk-detail-label">Review Date</div>
                <div class="risk-detail-value">{{ $risk->review_date?->format('M d, Y') ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>This is an automated report generated from the Risk Register system</p>
        <p>Page <span>1</span></p>
    </div>
</body>
</html>