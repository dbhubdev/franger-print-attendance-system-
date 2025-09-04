<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use App\Jobs\SyncAttendanceJob;
class SyncAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // while (true) {
        //     $zk = new ZKTeco('10.0.1.107', 4370);

        //     if ($zk->connect()) {
        //         $attendance = $zk->getAttendance();

        //         foreach ($attendance as $att) {
        //             \App\Models\Attendance::updateOrCreate(
        //                 [
        //                     'uid' => $att['uid'],
        //                     'punch_time' => $att['timestamp']
        //                 ],
        //                 [
        //                     'emp_id' => $att['id']
        //                 ]
        //             );
        //         }

        //         $zk->disconnect();
        //         $this->info("✅ Attendance synced");
        //     } else {
        //         $this->error("❌ Device connection failed");
        //     }

        //     sleep(5); // wait 5 seconds
        // }
        // }

        // while (true) {
        //     // List of devices (IP + Port)
        //     $devices = [
        //         ['ip' => '10.0.1.107', 'port' => 4370],
        //         ['ip' => '10.0.1.108', 'port' => 4370],
        //     ];

        //     foreach ($devices as $device) {
        //         \App\Jobs\SyncAttendanceJob::dispatch($device['ip'], $device['port']);
        //     }


        //     sleep(5); // wait 5 seconds before next round
        // }

        while (true) {
            $devices = [
                ['ip' => '10.0.1.107', 'port' => 4370],
                // ['ip' => '10.0.1.108', 'port' => 4370],
            ];

            foreach ($devices as $device) {
                dispatch(new \App\Jobs\SyncAttendanceJob($device['ip'], $device['port']));
            }

            sleep(5); // wait 5 seconds before next batch
        }
    }
}
