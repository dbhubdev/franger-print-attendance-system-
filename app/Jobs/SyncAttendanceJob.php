<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Rats\Zkteco\Lib\ZKTeco; // Add this line if ZKTeco is in App\Libraries, adjust as needed
use App\Models\Attendance; // Add this line to import the Attendance model
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ip;
    protected $port;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function handle()
    {
        try {
            $zk = new ZKTeco($this->ip, $this->port);

            \App\Models\Device::firstOrCreate(
                ['ip' => $this->ip]
            );

            // --- 1. Sync Attendance ---
            if ($zk->connect()) {
                $attendance = $zk->getAttendance();

                foreach ($attendance as $att) {
                    Attendance::updateOrCreate(
                        [
                            'uid' => $att['uid'],
                            'device_ip' => $this->ip,
                            'punch_time' => $att['timestamp'],
                        ],
                        [
                            'emp_id' => $att['id']
                        ]
                    );
                }

                $employees = $zk->getUser(); // Assuming your ZKTeco library has this method
                foreach ($employees as $emp) {
                    // Save department first
                    $department = null;
                    if (!empty($emp['department'])) {
                        $department = \App\Models\Department::firstOrCreate([
                            'name' => $emp['department']
                        ]);
                    }

                    // Save employee
                    \App\Models\Employee::updateOrCreate(
                        ['id' => $emp['uid']],
                        [
                            'emp_id' => $emp['name'],
                            'name' => $emp['name'],
                            'department_id' => $department ? $department->id : null
                        ]
                    );
                }


                $zk->disconnect();
                Log::info("✅ Synced from {$this->ip}");
            } else {
                Log::warning("⚠️ Could not connect to device {$this->ip}:{$this->port}");
            }
        } catch (\Throwable $e) {
            Log::error("❌ Error syncing device {$this->ip}: " . $e->getMessage());
        }
    }
}
