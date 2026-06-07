<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shift Seal - <?php echo e($date); ?> <?php echo e($shift); ?></title>
    <style>
        body {
            font-family: 'IBM Plex Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #0066cc;
            font-size: 28px;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .seal-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f0f7ff;
            border-left: 4px solid #0066cc;
            border-radius: 4px;
        }

        .seal-info-item {
            flex: 1;
        }

        .seal-info-label {
            font-size: 11px;
            font-weight: bold;
            color: #0066cc;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .seal-info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .summary {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            flex: 1;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }

        .summary-card.total {
            background-color: #f5f5f5;
        }

        .summary-card.done {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
        }

        .summary-card.in-progress {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .summary-card.pending {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
        }

        .summary-card.escalated {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
        }

        .summary-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #666;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .summary-card.done .summary-value { color: #4caf50; }
        .summary-card.in-progress .summary-value { color: #2196f3; }
        .summary-card.pending .summary-value { color: #ff9800; }
        .summary-card.escalated .summary-value { color: #f44336; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #ddd;
        }

        th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-badge.done { background-color: #e8f5e9; color: #2e7d32; }
        .status-badge.in-progress { background-color: #e3f2fd; color: #1565c0; }
        .status-badge.pending { background-color: #fff3e0; color: #e65100; }
        .status-badge.escalated { background-color: #ffebee; color: #c62828; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
        }

        .footer-item {
            flex: 1;
        }

        .signature-line {
            margin-top: 30px;
            text-align: right;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>⊗ SHIFT HANDOVER SEAL</h1>
        <p>Formal shift closure and audit trail</p>
    </div>

    <!-- Seal Information -->
    <div class="seal-info">
        <div class="seal-info-item">
            <div class="seal-info-label">Sealed Date</div>
            <div class="seal-info-value"><?php echo e(\Carbon\Carbon::parse($date)->format('l, F j, Y')); ?></div>
        </div>
        <div class="seal-info-item">
            <div class="seal-info-label">Shift</div>
            <div class="seal-info-value" style="text-transform: capitalize;"><?php echo e($shift); ?></div>
        </div>
        <div class="seal-info-item">
            <div class="seal-info-label">Sealed By</div>
            <div class="seal-info-value"><?php echo e($sealer->name); ?></div>
        </div>
        <div class="seal-info-item">
            <div class="seal-info-label">Sealed At</div>
            <div class="seal-info-value"><?php echo e(now()->format('H:i:s')); ?></div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-card total">
            <div class="summary-label">Total Activities</div>
            <div class="summary-value"><?php echo e($summary['total']); ?></div>
        </div>
        <div class="summary-card done">
            <div class="summary-label">Done</div>
            <div class="summary-value"><?php echo e($summary['done']); ?></div>
        </div>
        <div class="summary-card in-progress">
            <div class="summary-label">In Progress</div>
            <div class="summary-value"><?php echo e($summary['in_progress']); ?></div>
        </div>
        <div class="summary-card pending">
            <div class="summary-label">Pending</div>
            <div class="summary-value"><?php echo e($summary['pending']); ?></div>
        </div>
        <div class="summary-card escalated">
            <div class="summary-label">Escalated</div>
            <div class="summary-value"><?php echo e($summary['escalated']); ?></div>
        </div>
    </div>

    <!-- Activities Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Activity</th>
                <th style="width: 20%;">Category</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Personnel</th>
                <th style="width: 10%;">Time</th>
                <th style="width: 23%;">Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($log->activity->title); ?></strong></td>
                <td><?php echo e($log->activity->category); ?></td>
                <td>
                    <span class="status-badge <?php echo e($log->status); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $log->status))); ?>

                    </span>
                </td>
                <td><?php echo e($log->updater->name); ?> <br><small>#<?php echo e($log->updater->employee_id); ?></small></td>
                <td style="font-family: monospace;"><?php echo e($log->updated_at_time->format('H:i:s')); ?></td>
                <td><?php echo e($log->remark ? Str::limit($log->remark, 100) : '—'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                    No activities recorded for this shift
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-item">
            <strong>Seal ID:</strong> <?php echo e($date); ?>-<?php echo e($shift); ?><br>
            <strong>Generated:</strong> <?php echo e(now()->format('Y-m-d H:i:s')); ?>

        </div>
        <div class="footer-item" style="text-align: center;">
            This PDF serves as an official audit trail.<br>
            Once sealed, activities cannot be modified.
        </div>
        <div class="footer-item" style="text-align: right;">
            <div class="signature-line">
                <div style="border-top: 1px solid #333; width: 150px; margin-bottom: 5px;"></div>
                <div class="signature-name"><?php echo e($sealer->name); ?></div>
                <div style="font-size: 10px;"><?php echo e($sealer->designation); ?></div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/imac/Projects/NpontuTrack-Applications-Support-Activity-Tracker/resources/views/reports/shift-seal-pdf.blade.php ENDPATH**/ ?>