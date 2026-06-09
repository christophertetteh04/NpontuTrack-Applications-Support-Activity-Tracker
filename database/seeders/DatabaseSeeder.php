<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        $admin = User::updateOrCreate([
            'name'        => 'Kwame Asante',
            'employee_id' => 'NPT-001',
            'email'       => 'admin@npontu.com',
            'phone'       => '+233 24 000 0001',
            'department'  => 'Applications Support',
            'designation' => 'Applications Support Manager',
            'role'        => 'admin',
            'password'    => 'password',
            'is_active'   => true,
        ]);

        $lead = User::updateOrCreate([
            'name'        => 'Abena Mensah',
            'employee_id' => 'NPT-002',
            'email'       => 'lead@npontu.com',
            'phone'       => '+233 24 000 0002',
            'department'  => 'Applications Support',
            'designation' => 'Team Lead',
            'role'        => 'team_lead',
            'password'    => 'password',
            'is_active'   => true,
        ]);

        $staff = User::updateOrCreate([
            'name'        => 'Kofi Boateng',
            'employee_id' => 'NPT-003',
            'email'       => 'staff@npontu.com',
            'phone'       => '+233 24 000 0003',
            'department'  => 'Applications Support',
            'designation' => 'Support Analyst',
            'role'        => 'staff',
            'password'    => 'password',
            'is_active'   => true,
        ]);

        $staff2 = User::updateOrCreate([
            'name'        => 'Ama Owusu',
            'employee_id' => 'NPT-004',
            'email'       => 'staff2@npontu.com',
            'phone'       => '+233 24 000 0004',
            'department'  => 'Applications Support',
            'designation' => 'Support Analyst',
            'role'        => 'staff',
            'password'    => 'password',
            'is_active'   => true,
        ]);

        
        $activities = [
            
            ['category' => 'SMS Monitoring', 'sort_order' => 1,
             'title'   => 'Daily SMS count in comparison to SMS count from logs',
             'description' => 'Compare the total SMS dispatched with what is recorded in the system logs. Investigate any variance > 1%.'],
            ['category' => 'SMS Monitoring', 'sort_order' => 2,
             'title'   => 'SMS gateway uptime check',
             'description' => 'Verify all SMS gateways are reachable and processing requests without errors.'],
            ['category' => 'SMS Monitoring', 'sort_order' => 3,
             'title'   => 'Failed SMS delivery report review',
             'description' => 'Pull the failed delivery report and re-queue or escalate as appropriate.'],

            
            ['category' => 'System Health', 'sort_order' => 1,
             'title'   => 'Application server CPU and memory check',
             'description' => 'Ensure no server is above 80% CPU or memory utilisation.'],
            ['category' => 'System Health', 'sort_order' => 2,
             'title'   => 'Database replication lag check',
             'description' => 'Verify replica lag is under 5 seconds across all database clusters.'],
            ['category' => 'System Health', 'sort_order' => 3,
             'title'   => 'Disk space monitoring',
             'description' => 'Alert if any volume exceeds 75% usage.'],

            
            ['category' => 'Incident Management', 'sort_order' => 1,
             'title'   => 'Open incident ticket review',
             'description' => 'Review all open tickets and update statuses. Escalate any ticket older than 4 hours.'],
            ['category' => 'Incident Management', 'sort_order' => 2,
             'title'   => 'Escalation follow-up',
             'description' => 'Follow up on tickets escalated to Tier 2 or vendor.'],

            
            ['category' => 'End-of-Day Tasks', 'sort_order' => 1,
             'title'   => 'Handover notes compilation',
             'description' => 'Compile all pending activities, escalations, and observations for the incoming shift.'],
            ['category' => 'End-of-Day Tasks', 'sort_order' => 2,
             'title'   => 'Shift summary report submission',
             'description' => 'Submit the shift summary to the team lead via the system before close of shift.'],
        ];

        $activityModels = [];
        foreach ($activities as $a) {
            $activityModels[] = Activity::create(array_merge($a, ['created_by' => $lead->id]));
        }

        
        $users = [$admin, $lead, $staff, $staff2];
        $shifts = ['morning', 'afternoon', 'night'];
        $statuses = ['pending', 'in_progress', 'done', 'done', 'done', 'escalated'];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();

            foreach ($activityModels as $activity) {
                
                $numUpdates = rand(1, 3);
                $baseTime   = Carbon::parse($date)->setHour(8);

                for ($u = 0; $u < $numUpdates; $u++) {
                    $updater  = $users[array_rand($users)];
                    $status   = $statuses[array_rand($statuses)];
                    $shift    = $shifts[$u % 3];
                    $logTime  = $baseTime->copy()->addHours($u * 3)->addMinutes(rand(0, 55));

                    $logData = [
                        'activity_id'    => $activity->id,
                        'updated_by'     => $updater->id,
                        'log_date'       => $date,
                        'status'         => $status,
                        'shift'          => $shift,
                        'remark'         => $this->sampleRemark($activity->title, $status),
                        'updated_at_time'=> $logTime,
                    ];

                    
                    if (str_contains($activity->title, 'SMS count')) {
                        $expected = rand(9800, 10200);
                        $actual   = $expected + rand(-150, 150);
                        $variance = $actual - $expected;
                        $logData['expected_value'] = number_format($expected);
                        $logData['actual_value']   = number_format($actual);
                        $logData['variance']        = ($variance >= 0 ? '+' : '') . number_format($variance);
                    }

                    ActivityLog::create($logData);
                }
            }
        }
    }

    private function sampleRemark(string $activityTitle, string $status): string
    {
        $remarks = [
            'done'        => ['Completed without issues.', 'All checks passed. No anomalies found.', 'Resolved. Documented in ticket tracker.', 'Done. Values within acceptable range.'],
            'pending'     => ['Awaiting response from vendor.', 'Blocked — waiting for DB access.', 'Not yet started. Assigned to afternoon shift.', 'Pending escalation approval.'],
            'in_progress' => ['Currently investigating high variance.', 'Running diagnostics.', 'Work in progress. ETA 30 mins.', 'Partially done — 2 of 5 checks cleared.'],
            'escalated'   => ['Escalated to Tier 2 — ticket #4452.', 'Vendor notified. SLA breach imminent.', 'Critical: escalated to on-call engineer.', 'Escalated — root cause unknown.'],
        ];

        $pool = $remarks[$status] ?? $remarks['done'];
        return $pool[array_rand($pool)];
    }
}