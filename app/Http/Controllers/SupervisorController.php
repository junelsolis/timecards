<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class SupervisorController extends Controller
{
    public function main() {
      $this->checkLoggedIn();

      $activeTimecards = $this->getSupervisorActiveTimecards();
      $sorted = $activeTimecards->sortBy('lastname');

      return view('/supervisor/main')
        ->with('activeTimecards', $sorted);

    }

    public function timecardQuickEdit(Request $request) {
      $this->checkLoggedIn();

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

      // retrieve timecard with matching id
      // $timecard = DB::table('timecards')->where('id', $id)->first();
      //
      // $timecard->{$day . 'TimeIn1'} = $in1;
      // $timecard->{$day . 'TimeOut1'} = $out1;
      // $timecard->{$day . 'TimeIn2'} = $in2;
      // $timecard->{$day . 'TimeOut2'} = $out2;
      // $timecard->{$day . 'Tardy'} = $tardy;
      // $timecard->{$day . 'Absent'} = $absent;

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

    private function checkLoggedIn() {
      // this function checks that the current user is a coordinator.
      // if not, redirect to login page.
      $role = session()->get('role');

      if ($role !== 'coordinator' || empty($role)) {
        return redirect('/');
      }
    }

    private function countActiveTimecards() {
      $this->checkLoggedIn();

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
      $this->checkLoggedIn();

      $this->checkLoggedIn();

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
      $this->checkLoggedIn();

      $count = DB::table('timecards')->where('signed', 1)->where('paid', 0)->count();

      return $count;
    }
    private function getSupervisorActiveTimecards() {
      $this->checkLoggedIn();

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
      $this->checkLoggedIn();

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
