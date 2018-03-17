<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class SupervisorController extends Controller
{
    public function main() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get active timecards
      $activeTimecards = $this->getSupervisorActiveTimecards();
      $sorted = $activeTimecards->sortBy('lastname');

      // get unsigned timecardSign
      $unsignedTimecards = $this->getSupervisorUnsignedTimecards();
      $unsignedSorted = $unsignedTimecards->sortBy('lastname');

      // get workers
      $workers = $this->getWorkers();
      $sortedWorkers = $workers->sortBy('lastname');
      $splitWorkers = $sortedWorkers->split(2);

      // additional info for workers
      return view('/supervisor/main')
        ->with('activeTimecards', $sorted)
        ->with('unsignedTimecards', $unsignedSorted)
        ->with('workers', $splitWorkers);

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

      return redirect('/supervisor')->with('msg', 'Timecard successfully edited.');

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

        'sunTimeIn1' => 'nullable|string',
        'sunTimeOut1' => 'nullable|string',
        'sunTimeIn2' => 'nullable|string',
        'sunTimeOut2' => 'nullable|string',
        'sunTardy' => 'nullable|string|max:2',
        'sunAbsent' => 'nullable|string|max:2',

        'monTimeIn1' => 'nullable|string',
        'monTimeOut1' => 'nullable|string',
        'monTimeIn2' => 'nullable|string',
        'monTimeOut2' => 'nullable|string',
        'monTardy' => 'nullable|string|max:2',
        'monAbsent' => 'nullable|string|max:2',

        'tueTimeIn1' => 'nullable|string',
        'tueTimeOut1' => 'nullable|string',
        'tueTimeIn2' => 'nullable|string',
        'tueTimeOut2' => 'nullable|string',
        'tueTardy' => 'nullable|string|max:2',
        'tueAbsent' => 'nullable|string|max:2',

        'wedTimeIn1' => 'nullable|string',
        'wedTimeOut1' => 'nullable|string',
        'wedTimeIn2' => 'nullable|string',
        'wedTimeOut2' => 'nullable|string',
        'wedTardy' => 'nullable|string|max:2',
        'wedAbsent' => 'nullable|string|max:2',

        'thuTimeIn1' => 'nullable|string',
        'thuTimeOut1' => 'nullable|string',
        'thuTimeIn2' => 'nullable|string',
        'thuTimeOut2' => 'nullable|string',
        'thuTardy' => 'nullable|string|max:2',
        'thuAbsent' => 'nullable|string|max:2',

        'friTimeIn1' => 'nullable|string',
        'friTimeOut1' => 'nullable|string',
        'friTimeIn2' => 'nullable|string',
        'friTimeOut2' => 'nullable|string',
        'friTardy' => 'nullable|string|max:2',
        'friAbsent' => 'nullable|string|max:2',

        'satTimeIn1' => 'nullable|string',
        'satTimeOut1' => 'nullable|string',
        'satTimeIn2' => 'nullable|string',
        'satTimeOut2' => 'nullable|string',
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


      return redirect('/supervisor')->with('msg', 'Timecard successfully updated.');

    }
    public function showTimecardSign(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);

      // retrieve timecard
      $id = $request['id'];
      $timecard = DB::table('timecards')->where('id', $id)->first();

      // add department name
      $department = DB::table('departments')->where('id', $timecard->dept_id)->first();
      $timecard->department = $department->name;

      // add worker fullname
      $worker = DB::table('workers')->where('id', $timecard->worker_id)->first();
      $timecard->fullname = $worker->firstname . ' ' . $worker->lastname;

      // add date range
      $start = date('d M', strtotime($timecard->startDate));
      $end = date('d M', strtotime($timecard->endDate));

      $timecard->dateRange = $start . ' - ' . $end;

      // add number of tardies
      $count = 0;

      if ($timecard->sunTardy == true) { $count++; }
      if ($timecard->monTardy == true) { $count++; }
      if ($timecard->tueTardy == true) { $count++; }
      if ($timecard->wedTardy == true) { $count++; }
      if ($timecard->thuTardy == true) { $count++; }
      if ($timecard->friTardy == true) { $count++; }
      if ($timecard->satTardy == true) { $count++; }

      $timecard->tardies = $count;

      // add number of absences
      $count = 0;

      if ($timecard->sunAbsent == true) { $count++; }
      if ($timecard->monAbsent == true) { $count++; }
      if ($timecard->tueAbsent == true) { $count++; }
      if ($timecard->wedAbsent == true) { $count++; }
      if ($timecard->thuAbsent == true) { $count++; }
      if ($timecard->friAbsent == true) { $count++; }
      if ($timecard->satAbsent == true) { $count++; }

      $timecard->absences = $count;

      return view('/supervisor/timecardSign')->with('timecard', $timecard);

    }
    public function timecardSign(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate(['id' => 'required|integer']);


      // sign timecard
      $id = $request['id'];
      $this->signTimecard($id);

      return redirect('/supervisor')->with('msg', 'Timecard successfully signed.');
    }
    public function showTimecardsActive() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get date range string
      $startDate = '';
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      if ($day === 'Sun') {

        $startDate = date('Y-M-d', strtotime('now'));
        $endDate = date('Y-M-d', strtotime('+6 days'));
      }

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-M-d', strtotime('now'));
          $endDate = date('Y-M-d', strtotime('+6 days'));
          break;
        default:
          $startDate = date('Y-M-d', strtotime('Sunday last week'));
          $sun = strtotime('Sunday last week');
          $end = strtotime('+6 days', $sun);
          $endDate = date('Y-M-d', $end);
          break;
      }

      $dateRange = date('d F', strtotime($startDate)) . ' - ' . date('d F', strtotime($endDate));

      // get active timecards
      $timecards = $this->getSupervisorActiveTimecards();
      $sorted = $timecards->sortBy('lastname');

      return view('/supervisor/timecardsActive')
        ->with('dateRange', $dateRange)
        ->with('timecards', $sorted);
    }

    public function showPeriodCurrent() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // current period
      $period = $this->getCurrentPeriod();
      // timecards associated with current period
      $timecards = $this->getPeriodTimecards($period);
      // departments associated with supervisor
      $departments = $this->getDepartments();

      // payment graph data
      $paymentsGraphData = $this->getTotalPaymentsForPeriod($period);
      // hours graph data
      $hoursGraphData = $this->getTotalHoursForPeriod($period);

      // total tardies and absences
        $tardies = 0;
        $absences = 0;
        foreach ($timecards as $card) {
          $tardies += $this->countTimecardTardies($card);
          $absences += $this->countTimecardAbsences($card);
        }

        $period->totalTardies = $tardies;
        $period->totalAbsences = $absences;

      // tardies and absences by worker
      $workers = $this->getWorkers();

      foreach ($workers as $index => $worker) {
        $workerTimecards = $this->getWorkerTimecards($worker);

        $tardies = 0;
        $absences = 0;
        foreach ($workerTimecards as $i => $card) {
          $tardies += $this->countTimecardTardies($card);
          $absences += $this->countTimecardAbsences($card);

        }

        foreach ($workerTimecards as $card) {
          $worker->tardyDates = $this->getTimecardTardyDates($card);
          $worker->absentDates = $this->getTimecardAbsentDates($card);
        }

      }

      // total payment
      $period->totalPayment = number_format($timecards->sum('pay'));

      // total hours
      $totalHours = $timecards->sum('hours');
      $period->totalHours = round($totalHours);

      // total timecards
      $period->totalTimecards = $timecards->count();

      // date range
      $start = strtotime($period->startDate);
      $end = strtotime($period->endDate);

      $period->dateRange = date('d M', $start) . ' - ' . date('d M', $end) . ' ' . date('Y', $end);

      // worker summaries
      foreach ($workers as $worker) {
        $fullname = $worker->firstname . ' ' . $worker->lastname;
        $worker->fullname = $fullname;

        $worker->totalHours = $timecards->where('worker_id', $worker->id)->sum('hours');
        $worker->totalPay = $timecards->where('worker_id', $worker->id)->sum('pay');
      }

      return view('/supervisor/periodCurrent')
        ->with('paymentsGraphData', $paymentsGraphData)
        ->with('hoursGraphData', $hoursGraphData)
        ->with('workers', $workers)
        ->with('period', $period);


    }
    public function showPeriodHistory() {}

    public function showAttendance() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get all periods
      $periods = DB::table('payment_periods')->orderBy('endDate', 'desc')->get();

      // get all workers
      $workers = $this->getWorkers();

      // append related timecards for each worker
      foreach ($workers as $worker) {
        $timecards = $this->getWorkerTimecards($worker);
        $worker->timecards = $timecards;

        // append tardy dates
        foreach ($worker->timecards as $timecard) {
          $worker->tardyDates = $this->getTimecardTardyDates($timecard);
        }

        // append absent dates
        foreach ($worker->timecards as $timecard) {
          $worker->absentDates = $this->getTimecardAbsentDates($timecard);
        }
      }

      // remove workers with no attendance issues
      foreach ($workers as $index => $worker) {
        if ($worker->tardyDates->count() == 0 && $worker->absentDates->count() == 0) {
          $workers->forget($index);
        }
      }

      // append timecards associated with the current period
      // date range string
      foreach ($periods as $item) {
        $startDate = date('d M', strtotime($item->startDate));
        $endDate = date('d M', strtotime($item->endDate));
        $year = date('Y', strtotime($item->endDate));

        $item->dateRange = $startDate . ' - ' . $endDate . ' ' . $year;

        $item->timecards = $this->getPeriodTimecards($item);

        $totalTardies = 0;
        $totalAbsences = 0;
        foreach ($item->timecards as $timecard) {
          $total = $this->countTimecardTardies($timecard);
          $totalTardies += $total;

          $total = $this->countTimecardAbsences($timecard);
          $totalAbsences += $total;

        }

        $item->totalTardies = $totalTardies;
        $item->totalAbsences = $totalAbsences;
      }

      return view('/supervisor/attendance')
        ->with('periods', $periods)
        ->with('workers', $workers);
    }
    public function showAttendancePeriod(Request $request) {}
    public function showChangePassword() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      return view('/supervisor/passwordChange');
    }
    public function changePassword(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'password' => 'required|string',
        'newPassword' => 'required|string|min:8',
        'confirmPassword' => 'required|string|min:8'
      ]);

      $password = $request['password'];
      $newPassword = $request['newPassword'];
      $confirmPassword = $request['confirmPassword'];

      if ($newPassword != $confirmPassword) {
        return back()->with('error', 'New passwords do not match. Please try again.');
      }

      $password = sha1($password);
      $newPassword = sha1($newPassword);

      // retrieve supervisor object
      $id = session('userId');
      $supervisor = DB::table('supervisors')->where('id', $id)->first();

      // check if user entered the correct password
      if ($password != $supervisor->password) {
        return back()->with('error', 'You entered the wrong password. Please try again.');
      }

      // update the supervisors password then logout.
      DB::table('supervisors')->where('id', $id)
        ->update([
          'password' => $newPassword
        ]);

      return redirect('/logout');
    }


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

        $startDate = date('Y-M-d', strtotime('now'));
        $endDate = date('Y-M-d', strtotime('+6 days'));
      }

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-M-d', strtotime('now'));
          $endDate = date('Y-M-d', strtotime('+6 days'));
          break;
        default:
          $startDate = date('Y-M-d', strtotime('Sunday last week'));
          $sun = strtotime('Sunday last week');
          $end = strtotime('+6 days', $sun);
          $endDate = date('Y-M-d', $end);
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

        $startDate = date('Y-M-d', strtotime('now'));
        $endDate = date('Y-M-d', strtotime('+6 days'));
      }

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-M-d', strtotime('now'));
          $endDate = date('Y-M-d', strtotime('+6 days'));
          break;
        default:
          $startDate = date('Y-M-d', strtotime('Sunday last week'));
          $sun = strtotime('Sunday last week');
          $end = strtotime('+6 days', $sun);
          $endDate = date('Y-M-d', $end);
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
        ->where('signed', 0)
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
        $card->estimate = round($estimate);
      }

      return $items;
    }
    private function getSupervisorUnsignedTimecards() {
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

      // retrieve timecards and remove those that do not match the end date
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


      // get supervisor department id's
      $id = session('userId');
      $deptIds = DB::table('superv_depts')->where('superv_id', $id)->get();
      // get workers
      $workers = DB::table('workers')->get();
      // get departments
      $departments = DB::table('departments')->get();

      // collect timecards that match the dept id's
      $items = collect();

      foreach ($deptIds as $id) {
        $cards = $timecards->where('dept_id', $id->dept_id);

        foreach ($cards as $card) {

          // retrieve worker
          $worker = $workers->where('id', $card->worker_id)->first();

          // retrieve department name
          $department = $departments->where('id', $card->dept_id)->first();
          $card->department = $department->name;

          // insert worker fullname
          $card->lastname = $worker->lastname;
          $card->fullname = $worker->firstname . ' ' . $worker->lastname;

          // insert date range string
          $card->dateRange = date('d M', strtotime($card->startDate)) . ' - ' . date('d M', strtotime($card->endDate));

          // count tardies
          $card->tardies = $this->countTimecardTardies($card);

          // count absences
          $card->absences = $this->countTimecardAbsences($card);


          $items->push($card);
        }
      }



      return $items;
    }

    private function signTimecard($id) {
      // this function finalizes the pay calculation and signs the timecard
      $payscale = DB::table('payscale')->get();
      $timecard = DB::table('timecards')->where('id', $id)->first();

      // finalize pay calculation
      $factor = $payscale->where('grade', $timecard->grade)->first();
      $total = $timecard->hours + $timecard->contract;
      $total = round($total, 2);
      $pay = $total * $factor->pay;

      DB::table('timecards')->where('id', $id)
        ->update([
          'hours' => $total,
          'pay' => $pay,
          'signed' => true
        ]);

    }

    private function getDepartments() {
      // this function returns all the departments the supervisor is assigned to
      $id = session('userId');

      $deptIds = DB::table('superv_depts')->where('superv_id', $id)->get();
      $departments = DB::table('departments')->get();

      $items = collect();

      foreach ($deptIds as $id) {
        $department = $departments->where('id', $id->dept_id)->first();
        $items->push($department);
      }

      return $items;

    }
    private function getWorkers() {
      // this function returns all the workers assigned to the supervisor
      // plus additional info
      $departments = $this->getDepartments();
      $workerDepts = DB::table('worker_depts')->get();
      $workersTable = DB::table('workers')->get();
      $timecardsTable = DB::table('timecards')->get();

      $items = collect();

      foreach ($departments as $department) {
        // collect worker ids that match the department id
        $workerIds = $workerDepts->where('dept_id', $department->id);


        // collect workers that match the worker id
        foreach ($workerIds as $item) {
          $worker = $workersTable->where('id', $item->worker_id)->first();

          // additional info

            // fullname
            $worker->fullname = $worker->firstname . ' ' . $worker->lastname;

          // push to items collection
          $items->push($worker);
        }
      }

      // count total timecards for each worker for the departments of this supervisor
      foreach ($items as $worker) {

        $totalTimecards = 0;
        $workerDepartments = collect();

        foreach($departments as $dept) {
          $count = $timecardsTable->where('worker_id', $worker->id)
            ->where('dept_id', $dept->id)->count();

          $totalTimecards = $totalTimecards + $count;

          if ($count != 0) {
            $workerDepartments->push($dept->name);
          }
        }



        $worker->totalTimecards = $totalTimecards;
        $worker->departmentNames = $workerDepartments;
      }


      $sorted = $items->sortBy('lastname');

      return $sorted;


    }
    private function getWorkerTimecards($worker) {
      // this function retrieves all the timecards associated
      // with a worker for this supervisor's departments.

      $departments = $this->getDepartments();
      $timecards = DB::table('timecards')->get();

      $items = collect();
      foreach ($departments as $dept) {
        foreach ($timecards as $card) {
          if ($card->dept_id == $dept->id && $card->worker_id == $worker->id) {
            $items->push($card);
          }
        }
      }

      return $items;
    }

    // private function countTimecardTardies($id, $timecards) {
    //   // receives a timecard id and a collection of timecards and counts the number of tardies in that timecard
    //   $timecard = $timecards->where('id', $id)->first();
    //
    //   $count = 0;
    //
    //   if ($timecard->sunTardy == 1) { $count++; }
    //   if ($timecard->monTardy == 1) { $count++; }
    //   if ($timecard->tueTardy == 1) { $count++; }
    //   if ($timecard->wedTardy == 1) { $count++; }
    //   if ($timecard->thuTardy == 1) { $count++; }
    //   if ($timecard->friTardy == 1) { $count++; }
    //
    //
    //   return $count;
    // }
    // private function countTimecardAbsences($id, $timecards) {
    //   // receives a timecard id and a collection of timecards and counts the number of absences in that timecard
    //   $timecard = $timecards->where('id', $id)->first();
    //
    //   $count = 0;
    //
    //   if ($timecard->sunAbsent == 1) { $count++; }
    //   if ($timecard->monAbsent == 1) { $count++; }
    //   if ($timecard->tueAbsent == 1) { $count++; }
    //   if ($timecard->wedAbsent == 1) { $count++; }
    //   if ($timecard->thuAbsent == 1) { $count++; }
    //   if ($timecard->friAbsent == 1) { $count++; }
    //   if ($timecard->satAbsent == 1) { $count++; }
    //
    //   return $count;
    // }

    private function getCurrentPeriod(){
      // this function returns the current payment period

      // get current timestamp
      $now = strtotime('now');
      $now = date('Y-m-d', $now);
      $now = $now . ' 23:59:59';
      $now = strtotime($now);


      // all payment periods
      $paymentPeriods = DB::table('payment_periods')->get();

      foreach ($paymentPeriods as $index => $i) {
        $startDate = strtotime($i->startDate . ' 00:00:00');
        $endDate = strtotime($i->endDate . ' 23:59:59');

        if ($now >= $startDate && $now <= $endDate) {}
        else {
          $paymentPeriods->forget($index);
        }

      }

      $period = $paymentPeriods->first();

      return $period;

    }
    private function getPeriodTimecards($period) {
      // returns all timecards associated with supervisor's departments for the given payment period

      $departments = $this->getDepartments();
      $timecards = DB::table('timecards')->get();

      $periodStart = strtotime($period->startDate);
      $periodEnd = strtotime($period->endDate);

      $items = collect();

      foreach ($departments as $department) {
        $cards = $timecards->where('dept_id', $department->id);

        foreach ($cards as $card) {
          $cardStart = strtotime($card->startDate);
          $cardEnd = strtotime($card->endDate);

          if ($cardStart >= $periodStart && $cardEnd <= $periodEnd) {
            $items->push($card);
          }

        }
      }

      return $items;
    }
    private function countTimecardTardies($timecard) {
      // takes a timecard and counts tardies;

      $count = 0;

      if ($timecard->sunTardy == 1) { $count++; }
      if ($timecard->monTardy == 1) { $count++; }
      if ($timecard->tueTardy == 1) { $count++; }
      if ($timecard->wedTardy == 1) { $count++; }
      if ($timecard->thuTardy == 1) { $count++; }
      if ($timecard->friTardy == 1) { $count++; }
      if ($timecard->satTardy == 1) { $count++; }

      return $count;
    }
    private function countTimecardAbsences($timecard) {
      // takes a timecard and counts tardies;

      $count = 0;

      if ($timecard->sunAbsent == 1) { $count++; }
      if ($timecard->monAbsent == 1) { $count++; }
      if ($timecard->tueAbsent == 1) { $count++; }
      if ($timecard->wedAbsent == 1) { $count++; }
      if ($timecard->thuAbsent == 1) { $count++; }
      if ($timecard->friAbsent == 1) { $count++; }
      if ($timecard->satAbsent == 1) { $count++; }


      return $count;
    }
    private function getTimecardTardyDates($timecard) {
      $start = strtotime($timecard->startDate);

      $tardies = collect();
      if ($timecard->sunTardy == 1) {
        $date = date('Y-M-d', $start);
        $tardies->push($date);
      }

      if ($timecard->monTardy == 1) {
        $date = date('Y-M-d', strtotime('+1 day', $start));
        $tardies->push($date);
      }

      if ($timecard->tueTardy == 1) {
        $date = date('Y-M-d', strtotime('+2 days', $start));
        $tardies->push($date);
      }

      if ($timecard->wedTardy == 1) {
        $date = date('Y-M-d', strtotime('+3 days', $start));
        $tardies->push($date);
      }

      if ($timecard->thuTardy == 1) {
        $date = date('Y-M-d', strtotime('+4 days', $start));
        $tardies->push($date);
      }

      if ($timecard->friTardy == 1) {
        $date = date('Y-M-d', strtotime('+5 days', $start));
        $tardies->push($date);
      }

      if ($timecard->satTardy == 1) {
        $date = date('Y-M-d', strtotime('+6 days', $start));
        $tardies->push($date);
      }

      return $tardies;
    }
    private function getTimecardAbsentDates($timecard) {
      $start = strtotime($timecard->startDate);

      $absences = collect();
      if ($timecard->sunAbsent == 1) {
        $date = date('Y-M-d', $start);
        $absences->push($date);
      }

      if ($timecard->monAbsent == 1) {
        $date = date('Y-M-d', strtotime('+1 day', $start));
        $absences->push($date);
      }

      if ($timecard->tueAbsent == 1) {
        $date = date('Y-M-d', strtotime('+2 days', $start));
        $absences->push($date);
      }

      if ($timecard->wedAbsent == 1) {
        $date = date('Y-M-d', strtotime('+3 days', $start));
        $absences->push($date);
      }

      if ($timecard->thuAbsent == 1) {
        $date = date('Y-M-d', strtotime('+4 days', $start));
        $absences->push($date);
      }

      if ($timecard->friAbsent == 1) {
        $date = date('Y-M-d', strtotime('+5 days', $start));
        $absences->push($date);
      }

      if ($timecard->satAbsent == 1) {
        $date = date('Y-M-d', strtotime('+6 days', $start));
        $absences->push($date);
      }

      return $absences;
    }


    private function getTotalPaymentsForPeriod($period) {
      // receives a period object then returns two collections:
      // a collection of date ranges and a collection of amounts

      $timecards = $this->getPeriodTimecards($period);

      $periodStart = strtotime($period->startDate . ' 00:00:00');
      $periodEnd = strtotime($period->endDate . ' 23:59:59');

      $start = strtotime($period->startDate . ' 00:00:00');
      $end = strtotime('+6 days', $start);
      $end = date('Y-m-d', $end) . ' 23:59:59';
      $end = strtotime($end);

      $weeks = collect();
      $payments = collect();

      while ($end <= $periodEnd) {

        $cards = $timecards;

        $startDate = date('d M', $start);
        $endDate = date('d M', $end);

        $dateString = $startDate . ' - ' . $endDate;
        $weeks->push($dateString);

        $startDate = date('Y-m-d', $start);
        $endDate = date('Y-m-d', $end);

        $total = 0;
        foreach ($cards as $index => $card) {
          $cardStart = $card->startDate;
          $cardEnd = $card->endDate;

          if ($cardStart == $startDate && $cardEnd == $endDate) {
            $total += $card->pay;
            round($total);

          }
        }

        $payments->push($total);

        $start = strtotime('+7 days', $start);
        $end = strtotime('+7 days', $end);


      }

      $items = collect();
      $items->weeks = $weeks;
      $items->payments = $payments;

      return $items;
    }
    private function getTotalHoursForPeriod($period) {
      // this function receives a period object and returns two collections:
      // week ranges and total hours

      $timecards = $this->getPeriodTimecards($period);

      $periodStart = strtotime($period->startDate . ' 00:00:00');
      $periodEnd = strtotime($period->endDate . ' 23:59:59');

      $start = strtotime($period->startDate . ' 00:00:00');
      $end = strtotime('+6 days', $start);
      $end = date('Y-m-d', $end) . ' 23:59:59';
      $end = strtotime($end);

      $weeks = collect();
      $hours = collect();

      while ($end <= $periodEnd) {

        $startDate = date('d M', $start);
        $endDate = date('d M', $end);
        $string = $startDate . ' - ' . $endDate;

        $weeks->push($string);


        $startDate = date('Y-m-d', $start);
        $endDate = date('Y-m-d', $end);

        $cards = $timecards
          ->where('startDate', $startDate)
          ->where('endDate', $endDate);

        $total = $cards->sum('hours');

        $hours->push(round($total,1));

        $start = strtotime('+7 days', $start);
        $end = strtotime('+7 days', $end);
      }

      $items = collect();
      $items->weeks = $weeks;
      $items->hours = $hours;

      return $items;


    }

}
