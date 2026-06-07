<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Activity Report — {{ $dateFrom }} to {{ $dateTo }}</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Activity</th>
                <th>Category</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Expected</th>
                <th>Actual</th>
                <th>Variance</th>
                <th>Shift</th>
                <th>Updated By</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->log_date->format('Y-m-d') }}</td>
                <td>{{ $log->activity->title }}</td>
                <td>{{ $log->activity->category }}</td>
                <td>{{ $log->status_label }}</td>
                <td>{{ $log->remark }}</td>
                <td>{{ $log->expected_value }}</td>
                <td>{{ $log->actual_value }}</td>
                <td>{{ $log->variance }}</td>
                <td>{{ $log->shift }}</td>
                <td>{{ $log->updater->name }}</td>
                <td>{{ $log->updated_at_time->format('H:i:s') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
