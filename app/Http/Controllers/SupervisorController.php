<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class SupervisorController extends Controller
{
    public function main() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $activeTimecards = $this->getSupervisorActiveTimecards();
      $sorted = $activeTimecards->sortBy('lastname');

      return view('/supervisor/main')
        ->with('activeTimecards', $sorted);

    }

    public function timecardQuickEdit(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer',
        'day' => 'required|string',
        'in1' => 'nullable|string',
        'out1' => 'nullable|string',
        'in2' => 'nullable|string',
        'out2' => 'nullable|string',
        'tardy' => 'nullable|string|max:2',
        'absent' => 'nullable|string|max:2'
      ]);

      // additional validation

      // do not accept if time out but no time in entered
        if ($request->filled('out1') && !$request->filled('in1')) {
          return back()->with('error', 'No time in entered.');
        }
        if ($request->filled('out2') && !$request->filled('in2')) {
          return back()->with('error', 'No time in entered.');
        }

      // do not accept if both absent and tardy are checked
      if (isset($request['tardy']) && isset($request['absent'])) {
        return back()->with('error', 'Cannot be both tardy and absent. Select only one.');
      }

      // do not accept if time out is before or same as time in
      if (!empty($request['out1'])) {
        if (strtotime($request['in1']) >= strtotime($request['out1'])) {
          return back()->with('error', '1 Time Out cannot be same as or before Time In.');
        }
      }

      if (!empty($request['out2'])) {
        if (strtotime($request['in2']) >= strtotime($request['out2'])) {
          return back()->with('error', '2 Time Out cannot be same as or before Time In.');
        }
      }




      $id = $request['id'];
      $day = $request['day'];
      $in1 = $request['in1'];
      $in2 = $request['in2'];
      $out1 = $request['out1'];
      $out2 = $request['out2'];
      $tardy = $request['tardy'];
      $absent = $request['absent'];

      if ($tardy == 'on') { $tardy = true; } else { $tardy = false; }
      if ($absent == 'on') { $absent = true; } else { $absent = false; }


      DB::table('timecards')->where('id',$id)
        ->update(
          [
            $day . 'TimeIn1' => $in1,
            $day . 'TimeOut1' => $out1,
            $day . 'TimeIn2' => $in2,
            $day . 'TimeOut2' => $out2,
            $day . 'Tardy' => $tardy,
            $day . 'Absent' => $absent,
          ]
        );

    }
    public function showTimecardEdit(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);

      $id = $request['id'];

      // retrieve timecard with matching id
      $timecard = DB::table('timecards')->where('id', $id)->first();
      // retrieve all worker related to timecard
      $worker = DB::table('workers')->where('id', $timecard->worker_id)->first();
      // retrieve department related to timecard
      $department = DB::table('departments')->where('id', $timecard->dept_id)->first();

      // insert additional data into timecard object
      $timecard->fullname = $worker->firstname . ' ' . $worker->lastname;
      $timecard->department = $department->name;
      $timecard->dateRange = date('d M', strtotime($timecard->startDate)) . ' - ' . date('d M', strtotime($timecard->endDate));

        // date abbrev. strings for each day
        $start = strtotime($timecard->startDate);
        $sun = date('d M', $start);
        $mon = date('d M', strtotime('+1 day', $start));
        $tue = date('d M', strtotime('+2 days', $start));
        $wed = date('d M', strtotime('+3 days', $start));
        $thu = date('d M', strtotime('+4 days', $start));
        $fri = date('d M', strtotime('+5 days', $start));
        $sat = date('d M', strtotime('+6 days', $start));

        $dates = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);

      // insert dates array into timecard
      $timecard->dates = $dates;


      return view('/supervisor/timecardEdit')->with('timecard', $timecard);
    }
    public function timecardEdit(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // run request variable validation
      $request->validate([
        'id' => 'required|integer',
        'grade' => 'required|string|max:1',
        'contract' => 'required|numeric',

        'sunTimeIn1' => 'nullable|date_format:H:i',
        'sunTimeOut1' => 'nullable|date_format:H:i',
        'sunTimeIn2' => 'nullable|date_format:H:i',
        'sunTimeOut2' => 'nullable|date_format:H:i',
        'sunTardy' => 'nullable|string|max:2',
        'sunAbsent' => 'nullable|string|max:2',

        'monTimeIn1' => 'nullable|date_format:H:i',
        'monTimeOut1' => 'nullable|date_format:H:i',
        'monTimeIn2' => 'nullable|date_format:H:i',
        'monTimeOut2' => 'nullable|date_format:H:i',
        'monTardy' => 'nullable|string|max:2',
        'monAbsent' => 'nullable|string|max:2',

        'tueTimeIn1' => 'nullable|date_format:H:i',
        'tueTimeOut1' => 'nullable|date_format:H:i',
        'tueTimeIn2' => 'nullable|date_format:H:i',
        'tueTimeOut2' => 'nullable|date_format:H:i',
        'tueTardy' => 'nullable|string|max:2',
        'tueAbsent' => 'nullable|string|max:2',

        'wedTimeIn1' => 'nullable|date_format:H:i',
        'wedTimeOut1' => 'nullable|date_format:H:i',
        'wedTimeIn2' => 'nullable|date_format:H:i',
        'wedTimeOut2' => 'nullable|date_format:H:i',
        'wedTardy' => 'nullable|string|max:2',
        'wedAbsent' => 'nullable|string|max:2',

        'thuTimeIn1' => 'nullable|date_format:H:i',
        'thuTimeOut1' => 'nullable|date_format:H:i',
        'thuTimeIn2' => 'nullable|date_format:H:i',
        'thuTimeOut2' => 'nullable|date_format:H:i',
        'thuTardy' => 'nullable|string|max:2',
        'thuAbsent' => 'nullable|string|max:2',

        'friTimeIn1' => 'nullable|date_format:H:i',
        'friTimeOut1' => 'nullable|date_format:H:i',
        'friTimeIn2' => 'nullable|date_format:H:i',
        'friTimeOut2' => 'nullable|date_format:H:i',
        'friTardy' => 'nullable|string|max:2',
        'friAbsent' => 'nullable|string|max:2',

        'satTimeIn1' => 'nullable|date_format:H:i',
        'satTimeOut1' => 'nullable|date_format:H:i',
        'satTimeIn2' => 'nullable|date_format:H:i',
        'satTimeOut2' => 'nullable|date_format:H:i',
        'satTardy' => 'nullable|string|max:2',
        'satAbsent' => 'nullable|string|max:2',
      ]);

      // assign request variables
      $id = $request['id'];
      $grade = $request['grade'];
      $contract = $request['contract'];

      $sunTimeIn1 = $request['sunTimeIn1'];
      $sunTimeOut1 = $request['sunTimeOut1'];
      $sunTimeIn2 = $request['sunTimeIn2'];
      $sunTimeOut2 = $request['sunTimeOut2'];

      $monTimeIn1 = $request['monTimeIn1'];
      $monTimeOut1 = $request['monTimeOut1'];
      $monTimeIn2 = $request['monTimeIn2'];
      $monTimeOut2 = $request['monTimeOut2'];

      $tueTimeIn1 = $request['tueTimeIn1'];
      $tueTimeOut1 = $request['tueTimeOut1'];
      $tueTimeIn2 = $request['tueTimeIn2'];
      $tueTimeOut2 = $request['tueTimeOut2'];

      $wedTimeIn1 = $request['wedTimeIn1'];
      $wedTimeOut1 = $request['wedTimeOut1'];
      $wedTimeIn2 = $request['wedTimeIn2'];
      $wedTimeOut2 = $request['wedTimeOut2'];

      $thuTimeIn1 = $request['thuTimeIn1'];
      $thuTimeOut1 = $request['thuTimeOut1'];
      $thuTimeIn2 = $request['thuTimeIn2'];
      $thuTimeOut2 = $request['thuTimeOut2'];

      $friTimeIn1 = $request['friTimeIn1'];
      $friTimeOut1 = $request['friTimeOut1'];
      $friTimeIn2 = $request['friTimeIn2'];
      $friTimeOut2 = $request['friTimeOut2'];

      $satTimeIn1 = $request['satTimeIn1'];
      $satTimeOut1 = $request['satTimeOut1'];
      $satTimeIn2 = $request['satTimeIn2'];
      $satTimeOut2 = $request['satTimeOut2'];

      $sunTardy = $request['sunTardy'];
      $sunAbsent = $request['sunAbsent'];

      $monTardy = $request['monTardy'];
      $monAbsent = $request['monAbsent'];

      $tueTardy = $request['tueTardy'];
      $tueAbsent = $request['tueAbsent'];

      $wedTardy = $request['wedTardy'];
      $wedAbsent = $request['wedAbsent'];

      $thuTardy = $request['thuTardy'];
      $thuAbsent = $request['thuAbsent'];

      $friTardy = $request['friTardy'];
      $friAbsent = $request['friAbsent'];

      $satTardy = $request['satTardy'];
      $satAbsent = $request['satAbsent'];

      // modify boolean variables if needed
      if ($sunTardy == 'on') { $sunTardy = true; } else { $sunTardy = false; }
      if ($sunAbsent == 'on') { $sunAbsent = true; } else { $sunAbsent = false; }

      if ($monTardy == 'on') { $monTardy = true; } else { $monTardy = false; }
      if ($monAbsent == 'on') { $monAbsent = true; } else { $monAbsent = false; }

      if ($tueTardy == 'on') { $tueTardy = true; } else { $tueTardy = false; }
      if ($tueAbsent == 'on') { $tueAbsent = true; } else { $tueAbsent = false; }

      if ($wedTardy == 'on') { $wedTardy = true; } else { $wedTardy = false; }
      if ($wedAbsent == 'on') { $wedAbsent = true; } else { $wedAbsent = false; }

      if ($thuTardy == 'on') { $thuTardy = true; } else { $thuTardy = false; }
      if ($thuAbsent == 'on') { $thuAbsent = true; } else { $thuAbsent = false; }

      if ($friTardy == 'on') { $friTardy = true; } else { $friTardy = false; }
      if ($friAbsent == 'on') { $friAbsent = true; } else { $friAbsent = false; }

      if ($satTardy == 'on') { $satTardy = true; } else { $satTardy = false; }
      if ($satAbsent == 'on') { $satAbsent = true; } else { $satAbsent = false; }

      // additional validation

        // disallow entry of time out if corresponding time in not entered
        if (!empty($sunTimeOut1) && empty($sunTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In. Check Sunday.');
        }
        if (!empty($sunTimeOut2) && empty($sunTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In. Check Sunday.');
        }

        if (!empty($monTimeOut1) && empty($monTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($monTimeOut2) && empty($monTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }

        if (!empty($tueTimeOut1) && empty($tueTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($tueTimeOut2) && empty($tueTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }

        if (!empty($wedTimeOut1) && empty($wedTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($wedTimeOut2) && empty($wedTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }

        if (!empty($thuTimeOut1) && empty($thuTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($thuTimeOut2) && empty($thuTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }

        if (!empty($friTimeOut1) && empty($friTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($friTimeOut2) && empty($friTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }

        if (!empty($satTimeOut1) && empty($satTimeIn1)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }
        if (!empty($satTimeOut2) && empty($satTimeIn2)) {
          return back()->with('error', 'Time Out cannot be filled without Time In.');
        }



        // check that time out is not equal to or earlier than time in
        if (!empty($sunTimeIn1) && !empty($sunTimeOut1)) {
          if (strtotime($sunTimeIn1) >= strtotime($sunTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($sunTimeIn2) && !empty($sunTimeOut2)) {
          if (strtotime($sunTimeIn2) >= strtotime($sunTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($monTimeIn1) && !empty($monTimeOut1)) {
          if (strtotime($monTimeIn1) >= strtotime($monTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($monTimeIn2) && !empty($monTimeOut2)) {
          if (strtotime($monTimeIn2) >= strtotime($monTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($tueTimeIn1) && !empty($tueTimeOut1)) {
          if (strtotime($tueTimeIn1) >= strtotime($tueTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($tueTimeIn2) && !empty($tueTimeOut2)) {
          if (strtotime($tueTimeIn2) >= strtotime($tueTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($wedTimeIn1) && !empty($wedTimeOut1)) {
          if (strtotime($wedTimeIn1) >= strtotime($wedTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($wedTimeIn2) && !empty($wedTimeOut2)) {
          if (strtotime($wedTimeIn2) >= strtotime($wedTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($thuTimeIn1) && !empty($thuTimeOut1)) {
          if (strtotime($thuTimeIn1) >= strtotime($thuTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($thuTimeIn2) && !empty($thuTimeOut2)) {
          if (strtotime($thuTimeIn2) >= strtotime($thuTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($friTimeIn1) && !empty($friTimeOut1)) {
          if (strtotime($friTimeIn1) >= strtotime($friTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($friTimeIn2) && !empty($friTimeOut2)) {
          if (strtotime($friTimeIn2) >= strtotime($friTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }


        if (!empty($satTimeIn1) && !empty($satTimeOut1)) {
          if (strtotime($satTimeIn1) >= strtotime($satTimeOut1)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }
        if (!empty($satTimeIn2) && !empty($satTimeOut2)) {
          if (strtotime($satTimeIn2) >= strtotime($satTimeOut2)) {
            return back()->with('error', 'Time In cannot be same as or after Time Out.');
          }
        }



        // check that absent and tardy are not both checked for each day
        if ($sunTardy == true && $sunAbsent == true) {
          $sunTardy = false;
          $sunAbsent = true;
        }

        if ($monTardy == true && $monAbsent == true) {
          $monTardy = false;
          $monAbsent = true;
        }

        if ($tueTardy == true && $tueAbsent == true) {
          $tueTardy = false;
          $tueAbsent = true;
        }

        if ($wedTardy == true && $wedAbsent == true) {
          $wedTardy = false;
          $wedAbsent = true;
        }

        if ($thuTardy == true && $thuAbsent == true) {
          $thuTardy = false;
          $thuAbsent = true;
        }

        if ($friTardy == true && $friAbsent == true) {
          $friTardy = false;
          $friAbsent = true;
        }

        if ($satTardy == true && $satAbsent == true) {
          $satTardy = false;
          $satAbsent = true;
        }

      // calculate total hours
      $total = 0;

      if (!empty($sunTimeIn1) && !empty($sunTimeOut1)) {
        $seconds = strtotime($sunTimeOut1) - strtotime($sunTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($sunTimeIn2) && !empty($sunTimeOut2)) {
        $seconds = strtotime($sunTimeOut2) - strtotime($sunTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($monTimeIn1) && !empty($monTimeOut1)) {
        $seconds = strtotime($monTimeOut1) - strtotime($monTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($monTimeIn2) && !empty($monTimeOut2)) {
        $seconds = strtotime($monTimeOut2) - strtotime($monTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($tueTimeIn1) && !empty($tueTimeOut1)) {
        $seconds = strtotime($tueTimeOut1) - strtotime($tueTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($tueTimeIn2) && !empty($tueTimeOut2)) {
        $seconds = strtotime($tueTimeOut2) - strtotime($tueTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($wedTimeIn1) && !empty($wedTimeOut1)) {
        $seconds = strtotime($wedTimeOut1) - strtotime($wedTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($wedTimeIn2) && !empty($wedTimeOut2)) {
        $seconds = strtotime($wedTimeOut2) - strtotime($wedTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($thuTimeIn1) && !empty($thuTimeOut1)) {
        $seconds = strtotime($thuTimeOut1) - strtotime($thuTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($thuTimeIn2) && !empty($thuTimeOut2)) {
        $seconds = strtotime($thuTimeOut2) - strtotime($thuTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($friTimeIn1) && !empty($friTimeOut1)) {
        $seconds = strtotime($friTimeOut1) - strtotime($friTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($friTimeIn2) && !empty($friTimeOut2)) {
        $seconds = strtotime($friTimeOut2) - strtotime($friTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      if (!empty($satTimeIn1) && !empty($satTimeOut1)) {
        $seconds = strtotime($satTimeOut1) - strtotime($satTimeIn1);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }
      if (!empty($satTimeIn2) && !empty($satTimeOut2)) {
        $seconds = strtotime($satTimeOut2) - strtotime($satTimeIn2);
        $hours = $seconds / 60 / 60;
        $total = $total + $hours;
      }

      // add contract hours to total then round
      $total = $total + $contract;
      $total = round($total,2);

      // calculate pay estimate
      $payscale = DB::table('payscale')->where('grade', $grade)->first();
      $pay = $total * $payscale->pay;

      // update timecard in DB
      DB::table('timecards')->where('id', $id)
        ->update([
          'grade' => $grade,
          'contract' => $contract,
          'hours' => $total,
          'pay' => $pay,

          'sunTimeIn1' => $sunTimeIn1,
          'sunTimeOut1' => $sunTimeOut1,
          'sunTimeIn2' => $sunTimeIn2,
          'sunTimeOut2' => $sunTimeOut2,

          'monTimeIn1' => $monTimeIn1,
          'monTimeOut1' => $monTimeOut1,
          'monTimeIn2' => $monTimeIn2,
          'monTimeOut2' => $monTimeOut2,

          'tueTimeIn1' => $tueTimeIn1,
          'tueTimeOut1' => $tueTimeOut1,
          'tueTimeIn2' => $tueTimeIn2,
          'tueTimeOut2' => $tueTimeOut2,

          'wedTimeIn1' => $wedTimeIn1,
          'wedTimeOut1' => $wedTimeOut1,
          'wedTimeIn2' => $wedTimeIn2,
          'wedTimeOut2' => $wedTimeOut2,

          'thuTimeIn1' => $thuTimeIn1,
          'thuTimeOut1' => $thuTimeOut1,
          'thuTimeIn2' => $thuTimeIn2,
          'thuTimeOut2' => $thuTimeOut2,

          'friTimeIn1' => $friTimeIn1,
          'friTimeOut1' => $friTimeOut1,
          'friTimeIn2' => $friTimeIn2,
          'friTimeOut2' => $friTimeOut2,

          'satTimeIn1' => $satTimeIn1,
          'satTimeOut1' => $satTimeOut1,
          'satTimeIn2' => $satTimeIn2,
          'satTimeOut2' => $satTimeOut2,

          'sunTardy' => $sunTardy,
          'sunAbsent' => $sunAbsent,

          'monTardy' => $monTardy,
          'monAbsent' => $monAbsent,

          'tueTardy' => $tueTardy,
          'tueAbsent' => $tueAbsent,

          'wedTardy' => $wedTardy,
          'wedAbsent' => $wedAbsent,

          'thuTardy' => $thuTardy,
          'thuAbsent' => $thuAbsent,

          'friTardy' => $friTardy,
          'friAbsent' => $friAbsent,

          'satTardy' => $satTardy,
          'satAbsent' => $satAbsent,

        ]);


      return redirect('/supervisor/timecards/edit?id='.$id)->with('msg', 'Timecard successfully updated.');

    }
    public function showTimecardSign() {}
    public function timecardSign(Request $request) {}

    private function checkLoggedIn() {
      $role = session('role');

      if ($role == 'supervisor') { return true;} else { return false; }
    }

    private function countActiveTimecards() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // empty strings to hold start and end dates
      $startDate = '';
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      if ($day === 'Sun') {

        $startDate = date('Y-m-d', strtotime('now'));
        $endDate = date('Y-m-d', strtotime('+6 days'));
      }

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-m-d', strtotime('now'));
          $endDate = date('Y-m-d', strtotime('+6 days'));
          break;
        default:
          $startDate = date('Y-m-d', strtotime('Sunday last week'));
          $sun = strtotime('Sunday last week');
          $end = strtotime('+6 days', $sun);
          $endDate = date('Y-m-d', $end);
          break;
      }

      // count timecards matching date constraints
      $count = DB::table('timecards')
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->count();

      return $count;
    }
    private function countUnsignedTimecards() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // this next section establishes the saturday of last week
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      switch ($day) {
        case 'Sun':
          $endDate = strtotime('Saturday this week');
          break;
        default:
          $start = strtotime('-2 weeks Sunday');
          $endDate = strtotime('+6 days', $start);
          break;
      }


      $timecards = DB::table('timecards')
        ->where('signed', 0)
        ->get();

      foreach ($timecards as $key => $item ) {
        $end = strtotime($item->endDate);

        if ($end <= $endDate) {}
        else {
          $timecards->forget($key);
        }
      }

      return $timecards->count();
    }
    private function countSubmittedTimecards() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $count = DB::table('timecards')->where('signed', 1)->where('paid', 0)->count();

      return $count;
    }
    private function getSupervisorActiveTimecards() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // empty strings to hold start and end dates
      $startDate = '';
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      if ($day === 'Sun') {

        $startDate = date('Y-m-d', strtotime('now'));
        $endDate = date('Y-m-d', strtotime('+6 days'));
      }

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-m-d', strtotime('now'));
          $endDate = date('Y-m-d', strtotime('+6 days'));
          break;
        default:
          $startDate = date('Y-m-d', strtotime('Sunday last week'));
          $sun = strtotime('Sunday last week');
          $end = strtotime('+6 days', $sun);
          $endDate = date('Y-m-d', $end);
          break;
      }


      $id = session('userId');
      $deptIds = DB::table('superv_depts')->where('superv_id', $id)->get();
      $departments = DB::table('departments')->get();
      $workers = DB::table('workers')->get();
      $payscale = DB::table('payscale')->get();

      $timecards = DB::table('timecards')
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->get();

      $items = collect();

      // collect into items collection
      foreach ($deptIds as $item) {
        $cards = $timecards->where('dept_id', $item->dept_id);
        foreach ($cards as $i) {
          $items->push($i);
        }

      }

      // card additional data
      foreach ($items as $card) {

        // department name
        $department = $departments->where('id', $card->dept_id)->first();
        $card->department = $department->name;

        // worker name
        $worker = $workers->where('id', $card->worker_id)->first();

        $card->firstname = $worker->firstname;
        $card->lastname = $worker->lastname;
        $fullname = $worker->firstname . ' ' . $worker->lastname;
        $card->fullname = $fullname;

        // pay estimate
        $factor = $payscale->where('grade', $card->grade)->first();
        $estimate = $card->hours * $factor->pay;
        $card->estimate = $estimate;
      }

      return $items;
    }
    private function getUnsignedTimecards() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // this next section establishes the saturday of last week
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      switch ($day) {
        case 'Sun':
          $endDate = strtotime('Saturday this week');
          break;
        default:
          $start = strtotime('-2 weeks Sunday');
          $endDate = strtotime('+6 days', $start);
          break;
      }


      $timecards = DB::table('timecards')
        ->where('signed', 0)
        ->get();

      foreach ($timecards as $key => $item ) {
        $end = strtotime($item->endDate);

        if ($end <= $endDate) {}
        else {
          $timecards->forget($key);
        }
      }

      return $timecards;
    }
}
