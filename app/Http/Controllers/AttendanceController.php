<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Rats\Zkteco\Lib\ZKTeco;

class AttendanceController extends Controller
{
    public function fetch()
    {
        $zk = new ZKTeco('10.0.1.107', 4370);

        if ($zk->connect()) {
            $attendance = $zk->getAttendance();

            foreach ($attendance as $att) {
                Attendance::updateOrCreate(
                    [
                        'uid' => $att['uid'],
                        'punch_time' => $att['timestamp']
                    ],
                    [
                        'emp_id' => $att['id']
                    ]
                );
            }

            $zk->disconnect();
            return response()->json(['message' => '✅ Attendance synced']);
        }

        return response()->json(['message' => '❌ Device connection failed']);
    }

    public function index()
    {
        return Attendance::latest()->paginate(20);
    }
    public function view()
    {
        $attendances = Attendance::latest()->paginate(20);
        return view('attendance.index', compact('attendances'));
    }

}