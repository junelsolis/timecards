<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use stdClass;

class CoordinatorController extends Controller
{
    public function main() {
      $this->checkLoggedIn();

      $countdown = $this->nextPaymentCountDown();
      $countWorkers = $this->countWorkers();
      $countActiveTimecards = $this->countActiveTimecards();
      $countUnsignedTimecards = $this->countUnsignedTimecards();
      $countSubmittedTimecards = $this->countSubmittedTimecards();

      return view('coordinator/main')
        ->with('countdown', $countdown)
        ->with('countWorkers', $countWorkers)
        ->with('countActiveTimecards', $countActiveTimecards)
        ->with('countUnsignedTimecards', $countUnsignedTimecards)
        ->with('countSubmittedTimecards', $countSubmittedTimecards);

    }

    public function showSupervisorAdd() {
      $this->checkLoggedIn();

      // get department objects and sort by name
      // split into two chunks
      $collection = DB::table('departments')->orderBy('name')->get();
      $departments = $collection->split(2);

      // return view with data
      return view('coordinator/supervisorAdd')
        ->with('departments', $departments);
    }
    public function supervisorAdd(Request $request) {
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate request
      $request->validate([
        'username' => 'email|required',
        'firstname' => 'string|required',
        'lastname' => 'string|required',
        'departments' => 'array|required'
      ]);

      // assign and sanitize data
      $firstname = ucwords(strtolower($request['firstname']));
      $lastname = ucwords(strtolower($request['lastname']));
      $email = strtolower($request['username']);
      $departments = $request['departments'];

      // create a token
      $token = sha1(bin2hex(random_bytes(20)));

      // check that user does not already exist
      $exists = DB::table('supervisors')->where('email', $email)->get();
      if ($exists->isEmpty()) {}
      else {
        return back()->with('error', 'User already exists in the database.');
      }

      // create the user in db
      DB::table('supervisors')->insert([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'token' => $token
      ]);

      // create department entries for supervisor
      $id = DB::table('supervisors')->where('email', $email)->first();

      foreach ($departments as $i) {
        DB::table('superv_depts')->insert([
          'superv_id' => $id->id,
          'dept_id' => $i
        ]);
      }


      return redirect('/coordinator')
        ->with('msg', 'Supervisor has been successfully added and an e-mail was sent.');
    }
    public function showSupervisorEdit() {
      $this->checkLoggedIn();
      // this function retrieves all supervisors
      // and returns it to a view.

      // create empty collection
      $items = collect();

      // get all supervisors and add to collection
      $supervisors = DB::table('supervisors')->orderBy('lastname')->get();
      foreach($supervisors as $supervisor) {
        $item = new stdClass();

        $item->id = $supervisor->id;
        $item->firstname = $supervisor->firstname;
        $item->lastname = $supervisor->lastname;
        $item->fullname = $supervisor->firstname . ' ' . $supervisor->lastname;

        $items->push($item);
      }

      // get dept id's for each superv_id
      foreach($items as $item) {

        $dept_ids = DB::table('superv_depts')->where('superv_id', $item->id)->pluck('dept_id');

        $array = array();

        foreach ($dept_ids as $id) {
          $deptNames = DB::table('departments')->where('id', $id)->pluck('name');

          foreach($deptNames as $i) {
            $array[] = $i;
          }

        }

        $item->departments = $array;


      }


      return view('/coordinator/supervisorEdit')->with('supervisors', $items);


    }
    public function showSupervisorEditItem(Request $request) {
      $this->checkLoggedIn();

      // get supervisor id
      $id = $request['id'];

      // retrieve object with id from database.
      $supervisor = DB::table('supervisors')->where('id', $id)->first();

      // short email
      $pieces = explode('@', $supervisor->email);
      $short = $pieces[0];
      $supervisor->short = $short;

      // retrieve and add departments array to supervisor object
      $dept_ids = DB::table('superv_depts')->where('superv_id', $supervisor->id)->pluck('dept_id');
      $deptsArray = array();
      foreach ($dept_ids as $id) {
        $names = DB::table('departments')->where('id', $id)->orderBy('name')->pluck('name');
        foreach($names as $i) {
          $deptsArray[] = $i;
        }
      }

      $supervisor->departments = $deptsArray;

      // get all possible departments and split into two
      $depts = DB::table('departments')->orderBy('name')->get();
      $depts = $depts->split(2);

      return view('/coordinator/supervisorEditItem')
        ->with('supervisor', $supervisor)
        ->with('depts', $depts);
    }
    public function supervisorEditItem(Request $request) {
      $this->checkLoggedIn();

      // append domain to username entry
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate request
      $request->validate([
        'id' => 'required',
        'username' => 'email|required',
        'firstname' => 'alpha|required',
        'lastname' => 'alpha|required',
        'departments' => 'array|required'
      ]);

      // create local vars from request
      $id = $request['id'];
      $username = strtolower($request['username']);
      $firstname = ucwords(strtolower($request['firstname']));
      $lastname = ucwords(strtolower($request['lastname']));
      $departments = $request['departments'];

      // update supervisor entry in db
      DB::table('supervisors')->where('id', $id)->update([
        'email' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname,
      ]);

      // remove entries from supervisor depts table
      // then insert new department entries
      DB::table('superv_depts')->where('superv_id', $id)->delete();
      foreach($departments as $i) {
        DB::table('superv_depts')->insert([
          'superv_id' => $id,
          'dept_id' => $i
        ]);
      }

      return redirect('/coordinator/supervisor/edit')->with('msg', 'Supervisor details updated.');


    }

    public function showDepartments() {
      $this->checkLoggedIn();

      $departments = DB::table('departments')->get();

      // append additional information
      foreach ($departments as $item) {

        // get total number of timecards for this department
        $total = DB::table('timecards')->where('dept_id', $item->id)->count();
        $item->totalTimecards = $total;

        // count active timecards for this departments
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

        $item->activeTimecards = $count;
      }
      return view('/coordinator/departments')->with('departments', $departments);
    }
    public function showDepartmentsAdd() {}
    public function departmentsAdd(Request $request) {}

    public function showWorkerAdd() {
      $this->checkLoggedIn();

      // get department objects and sort by name
      // split into two chunks
      $collection = DB::table('departments')->orderBy('name')->get();
      $departments = $collection->split(2);

      // return view with data
      return view('/coordinator/workerAdd')->with('departments', $departments);
    }
    public function workerAdd(Request $request) {
      $this->checkLoggedIn();

      // append domain name to username entry
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate entries
      $request->validate([
        'username' => 'email|required',
        'firstname' => 'string|required',
        'lastname' => 'string|required',
        'departments' => 'array|required'
      ]);

      // assign and sanitize inputs
      $username = strtolower($request['username']);
      $firstname = ucwords(strtolower($request['firstname']));
      $lastname = ucwords(strtolower($request['lastname']));
      $departments = $request['departments'];

      // create token
      $token = sha1(bin2hex(random_bytes(20)));

      // check that worker does not already exist
      // go back with error if exists
      $check = DB::table('workers')->where('email', $username)->get();

      if ($check->isEmpty()) {}
      else {
        return back()->with('error', 'Worker already exists. Try editing the worker instead.');
      }

      // enter worker into db
      DB::table('workers')->insert([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $username,
        'token' => $token
      ]);

      // get new worker id and insert department entries
      $id = DB::table('workers')->where('email', $username)->pluck('id')->first();

      foreach ($departments as $i) {
        DB::table('worker_depts')->insert([
          'worker_id' => $id,
          'dept_id' => $i
        ]);
      }

      return redirect('/coordinator')->with('msg', 'Worker successfully added and e-mail sent.');

    }
    public function showWorkerEdit() {
      $this->checkLoggedIn();

      // create empty collection
      $items = collect();

      // get all workers and add to collection
      $workers = DB::table('workers')->orderBy('lastname')->get();
      foreach($workers as $worker) {
        $item = new stdClass();

        $item->id = $worker->id;
        $item->firstname = $worker->firstname;
        $item->lastname = $worker->lastname;
        $item->fullname = $worker->firstname . ' ' . $worker->lastname;

        $items->push($item);
      }

      // get all dept id's for each worker_id
      foreach ($items as $i) {
        $dept_ids = DB::table('worker_depts')->where('worker_id', $i->id)->pluck('dept_id');

        $array = array();

        foreach ($dept_ids as $id) {
          $deptNames = DB::table('departments')->where('id', $id)->orderBy('name')->pluck('name');

          foreach ($deptNames as $name) {
            $array[] = $name;
          }
        }

        $i->departments = $array;
      }

      return view('/coordinator/workerEdit')->with('workers', $items);


    }
    public function showWorkerEditItem(Request $request) {
      $this->checkLoggedIn();

      // get worker id
      $id = $request['id'];

      // retrieve object with id from database
      $worker = DB::table('workers')->where('id', $id)->first();

      // short email
      $pieces = explode('@', $worker->email);
      $short = $pieces[0];
      $worker->short = $short;

      // retrieve and add departments array to supervisor object
      $dept_ids = DB::table('worker_depts')->where('worker_id', $worker->id)->pluck('dept_id');
      $deptsArray = array();
      foreach($dept_ids as $id) {
        $names = DB::table('departments')->where('id', $id)->orderBy('name')->pluck('name');
        foreach($names as $i) {
          $deptsArray[] = $i;
        }
      }

      $worker->departments = $deptsArray;

      // get all possible departments and split into two chunks
      $depts = DB::table('departments')->orderBy('name')->get();
      $depts = $depts->split(2);

      return view('/coordinator/workerEditItem')
        ->with('worker', $worker)
        ->with('depts', $depts);



    }
    public function workerEditItem(Request $request) {
      $this->checkLoggedIn();

      // attach domain to username entry
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate request
      $request->validate([
        'username' => 'email|required',
        'firstname' => 'alpha|required',
        'lastname' => 'string|required',
        'departments' => 'array|required'
      ]);

      // assign and sanitize data
      $id = $request['id'];
      $username = strtolower($request['username']);
      $firstname = ucwords($request['firstname']);
      $lastname = ucwords($request['lastname']);
      $departments = $request['departments'];

      // update worker with corresponding id in db
      DB::table('workers')->where('id', $id)->update([
        'email' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname
      ]);

      // remove existing entires from worker_depts table
      // then insert new entries
      DB::table('worker_depts')->where('worker_id', $id)->delete();
      foreach ($departments as $i) {
        DB::table('worker_depts')->insert([
          'worker_id' => $id,
          'dept_id' => $i
        ]);
      }

      return redirect('/coordinator/worker/edit')->with('msg', 'Worker details updated.');

    }

    public function showPaymentPeriods() {
      $this->checkLoggedIn();

      // get all payment periods
      $items = DB::table('payment_periods')->orderBy('endDate', 'desc')->get();

      // for each item count associated timecards
      foreach ($items as $i) {

        $start = strtotime($i->startDate);
        $end = strtotime($i->endDate);

        $count = 0;
        $payment = 0;

        $all = DB::table('timecards')->select('startDate', 'endDate', 'pay')->get();

        foreach ($all as $timecard) {
          $cardStart = strtotime($timecard->startDate);
          $cardEnd = strtotime($timecard->endDate);

          if ($cardStart >= $start && $cardEnd <= $end) {
            $count++;
            $payment += $timecard->pay;
          }
        }

        $i->associated = $count;
        $i->payment = number_format($payment);
      }

      // for each item, add more readable date strings
      foreach ($items as $i) {
        $readableStartDate = date('d M', strtotime($i->startDate));
        $readableEndDate = date('d M', strtotime($i->endDate));

        $i->readableStartDate = $readableStartDate;
        $i->readableEndDate = $readableEndDate;
        $i->range = $readableStartDate . ' - ' . $readableEndDate;
      }

      return view('/coordinator/paymentPeriods')->with('periods', $items);
    }
    public function showPaymentPeriodsAdd() {
      $this->checkLoggedIn();

      return view('/coordinator/paymentPeriodsAdd');
    }
    public function paymentPeriodsAdd(Request $request) {
      $this->checkLoggedIn();

      // validate request
      $request->validate([
        'startDate' => 'string|required',
        'endDate' => 'string|required'
      ]);

      // assign variables
      $startDate = $request['startDate'];
      $endDate = $request['endDate'];

      // check if start date is a sunday
      $day = date('D', strtotime($startDate));
      if ($day !== 'Sun') {
        return back()->with('error', 'Start date must be on a Sunday.');
      } else {}

      // check if end date is a saturday
      $day = date('D', strtotime($endDate));
      if ($day !== 'Sat') {
        return back()->with('error', 'End date must be on a Saturday.');
      } else {}

      // store in db
      DB::table('payment_periods')->insert([
        'startDate' => $startDate,
        'endDate' => $endDate
      ]);

      // return with redirect
      return redirect('/coordinator/payment-periods')->with('msg', 'Payment period created.');
    }
    public function showPay() {
      $this->checkLoggedIn();

      // get payment periods
      $periods = DB::table('payment_periods')->orderBy('startDate', 'desc')->get();

      // get all timecards
      $all = DB::table('timecards')->select('startDate', 'endDate', 'pay', 'paid')->get();


      foreach ($periods as $i) {
        // form date range string
        $start = date('d M', strtotime($i->startDate));
        $end = date('d M', strtotime($i->endDate));
        $range = $start . ' - ' . $end;

        // count associated timecards
        $associated = 0;
        $payment = 0;
        $paid = true;

        foreach ($all as $timecard) {
          $cardStart = strtotime($timecard->startDate);
          $cardEnd = strtotime($timecard->endDate);
          $periodStart = strtotime($i->startDate);
          $periodEnd = strtotime($i->endDate);

          if ($cardStart >= $periodStart && $cardEnd <= $periodEnd) {
            $associated++;
            $payment += $timecard->pay;

            if ($timecard->paid == false) {
              $paid = false;
            }
          }


        }

        $i->range = $range;
        $i->associated = $associated;
        $i->payment = number_format($payment);
        $i->paid = $paid;
      }

      return view('/coordinator/paymentsPay')->with('periods', $periods);
    }
    public function showPaySelected(Request $request) {
      $this->checkLoggedIn();

      // validate request
      $request->validate([
        'startDate' => 'required',
        'endDate' => 'required'
      ]);

      // copy request to local vars
      $startDate = $request['startDate'];
      $endDate = $request['endDate'];


      // get all timecards
      $timecards = DB::table('timecards')->select('id', 'startDate', 'endDate', 'signed')->get();


      $start = strtotime($startDate);
      $end = strtotime($endDate);

      $unsigned = 0;

      // remove timecards from collection not matching the date range
      foreach ($timecards as $index => $i) {
        $cardStart = strtotime($i->startDate);
        $cardEnd = strtotime($i->endDate);

        if ($cardStart >= $start && $cardEnd <= $end) {}
        else {
          $timecards->forget($index);
        }

      }

      // increment for every unsigned timecard
      foreach($timecards as $i) {
        if ($i->signed == false) {
          $unsigned++;
        }
      }

      // date range string
      $range = date('d M', $start) . ' - ' . date('d M', $end);

      return view('/coordinator/paymentsPaySelected')
        ->with('unsigned', $unsigned)
        ->with('range', $range)
        ->with('startDate', $startDate)
        ->with('endDate', $endDate);


    }
    public function showPaySelectedUnsigned(Request $request) {
      $this->checkLoggedIn();

      // this function retrieves all unsigned timecards for the specified payment period
      $timecards = DB::table('timecards')->where('signed', 0)->where('paid',0)
        ->select('id', 'worker_id', 'dept_id', 'startDate', 'endDate', 'hours', 'grade', 'pay')
        ->get();


      $start = strtotime($request['startDate']);
      $end = strtotime($request['endDate']);

      // check for session data if exists
      if ($request->session()->exists('startDate')) {
        $start = strtotime($request->session()->get('startDate'));
      }

      if ($request->session()->exists('endDate')) {
        $end = strtotime($request->session()->get('endDate'));
      }

      foreach ($timecards as $index => $i) {
        $cardStart = strtotime($i->startDate);
        $cardEnd = strtotime($i->endDate);

        if ($cardStart >= $start && $cardEnd <= $end) {}
        else {
          $timecards->forget($index);
        }
      }

      // append worker name and department name to each timecard
      $workers = DB::table('workers')->get();
      $departments = DB::table('departments')->get();

      foreach ($timecards as $i) {
        $worker_id = $i->worker_id;
        $worker = $workers->where('id', $worker_id)->first();

        $i->firstname = $worker->firstname;
        $i->lastname = $worker->lastname;

        $dept_id = $i->dept_id;
        $department = $departments->where('id', $dept_id)->first();

        $i->department = $department->name;
      }

      // date range string
      $range = date('d M', $start) . ' - ' . date('d M', $end);

      return view('/coordinator/paymentsPaySelectedUnsigned')
        ->with('timecards', $timecards)
        ->with('range', $range)
        ->with('startDate', $request['startDate'])
        ->with('endDate', $request['endDate']);

    }
    public function paySelectedUnsignedSign(Request $request) {
      $this->checkLoggedIn();

      $request->validate([
        'id' => 'required',
        'startDate' => 'date|required',
        'endDate' => 'date|required'
      ]);

      // get id number from request
      $id = $request['id'];

      $startDate = $request['startDate'];
      $endDate = $request['endDate'];

      // mark timecard with matching id number as signed
      DB::table('timecards')->where('id', $id)->update(['signed' => 1 ]);

      // redirect
      return redirect('/coordinator/payments/pay/selected/unsigned?startDate='.$startDate.'&endDate='.$endDate);

    }
    public function paySelectedUnsignedRemind(Request $request) {}

    public function timecardsImport() {
      $this->checkLoggedIn();

      // get all timecards to import
      $timecards = DB::table('import')->get();

      // remove timecards that do not match a worker in the database
      foreach ($timecards as $index => $i) {
        $worker = DB::table('workers')->where('email', $i->worker)->first();

        if (empty($worker)) {
          $timecards->forget($index);
        }
      }

      // convert data and insert in timecards table
      foreach ($timecards as $index => $i) {
        $worker_id = DB::table('workers')->where('email', $i->worker)->pluck('id')->first();
        $dept_id = DB::table('departments')->where('name', $i->department)->pluck('id')->first();

        DB::table('timecards')->insert([
          'worker_id' => $worker_id,
          'dept_id' => $dept_id,
          'startDate' => $i->startDate,
          'endDate' => $i->endDate,
          'contract' => $i->contract,
          'hours' => $i->hours,
          'grade' => $i->grade,
          'signed' => $i->signed,
          'pay' => $i->pay,
          'paid' => $i->paid,

          'sunTardy' => $i->sunTardy,
          'sunAbsent' => $i->sunAbsent,
          'sunTimeIn1' => $i->sunTimeIn1,
          'sunTimeOut1' => $i->sunTimeOut1,
          'sunTimeIn2' => $i->sunTimeIn2,
          'sunTimeOut2' => $i->sunTimeOut2,

          'monTardy' => $i->monTardy,
          'monAbsent' => $i->monAbsent,
          'monTimeIn1' => $i->monTimeIn1,
          'monTimeOut1' => $i->monTimeOut1,
          'monTimeIn2' => $i->monTimeIn2,
          'monTimeOut2' => $i->monTimeOut2,

          'tueTardy' => $i->tueTardy,
          'tueAbsent' => $i->tueAbsent,
          'tueTimeIn1' => $i->tueTimeIn1,
          'tueTimeOut1' => $i->tueTimeOut1,
          'tueTimeIn2' => $i->tueTimeIn2,
          'tueTimeOut2' => $i->tueTimeOut2,

          'wedTardy' => $i->wedTardy,
          'wedAbsent' => $i->wedAbsent,
          'wedTimeIn1' => $i->wedTimeIn1,
          'wedTimeOut1' => $i->wedTimeOut1,
          'wedTimeIn2' => $i->wedTimeIn2,
          'wedTimeOut2' => $i->wedTimeOut2,

          'thuTardy' => $i->thuTardy,
          'thuAbsent' => $i->thuAbsent,
          'thuTimeIn1' => $i->thuTimeIn1,
          'thuTimeOut1' => $i->thuTimeOut1,
          'thuTimeIn2' => $i->thuTimeIn2,
          'thuTimeOut2' => $i->thuTimeOut2,

          'friTardy' => $i->friTardy,
          'friAbsent' => $i->friAbsent,
          'friTimeIn1' => $i->friTimeIn1,
          'friTimeOut1' => $i->friTimeOut1,
          'friTimeIn2' => $i->friTimeIn2,
          'friTimeOut2' => $i->friTimeOut2,

          'satTardy' => $i->satTardy,
          'satAbsent' => $i->satAbsent,
          'satTimeIn1' => $i->satTimeIn1,
          'satTimeOut1' => $i->satTimeOut1,
          'satTimeIn2' => $i->satTimeIn2,
          'satTimeOut2' => $i->satTimeOut2,

        ]);

        // remove timecard from import table
        DB::table('import')->where('id', $i->id)->delete();
      }

      return redirect('/coordinator')->with('msg', 'Timecards successfully imported.');
    }
    public function showTimecardsCreate() {
      $this->checkLoggedIn();

      return view('/coordinator/timecardsCreate');
    }
    public function timecardsCreate(Request $request) {
      $this->checkLoggedIn();

      // validate input
      $request->validate([
        'startDate' => 'date|required',
      ]);

      // additional validation

      // check startDate is a sunday
      $start = date('D', strtotime($request['startDate']));
      if ($start !== 'Sun') {
        return back()->with('error', 'Start date must be a Sunday.');
      }

      // check end date is not same as or before start date
      $start = strtotime($request['startDate']);
      $end = strtotime($request['endDate'] . '+6 days');

      if ($end <= $start) {
        return back()->with('error', 'End date must not be before start date.');
      }

      // if all validation passed, continue
      $startDate = $request['startDate'];
      $endDate = $request['endDate'];

      // get all workers
      $workers = DB::table('workers')->orderBy('lastname')->get();

      // foreach worker, get all dept_ids
      foreach ($workers as $worker) {
        $dept_ids = DB::table('worker_depts')->where('worker_id', $worker->id)->pluck('dept_id');

        // for each id, check if timecard alread exists
        // if not exists, create new timecard
        foreach ($dept_ids as $id) {
          $check = DB::table('timecards')
            ->where('worker_id', $worker->id)
            ->where('dept_id', $id)
            ->where('startDate', $startDate)
            ->where('endDate', $endDate)->first();

          if (empty($check)) {
            DB::table('timecards')->insert([
              'worker_id' => $worker->id,
              'dept_id' => $id,
              'startDate' => $startDate,
              'endDate' => $endDate,
            ]);
          }
          else { continue; }
        }
      }

      return redirect('/coordinator')->with('msg', 'Timecards successfully created.');

    }
    public function showTimecardsActive() {
      $this->checkLoggedIn();

      // this next section establishes the starting and ending date this week

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


      // get all timecards matching the start and end dates
      $timecards = DB::table('timecards')
        ->select('worker_id', 'dept_id', 'hours')
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->get();

      // additional data
      foreach ($timecards as $item) {
        $firstname = DB::table('workers')->where('id', $item->worker_id)->pluck('firstname')->first();
        $lastname = DB::table('workers')->where('id', $item->worker_id)->pluck('lastname')->first();
        $department = DB::table('departments')->where('id', $item->dept_id)->pluck('name')->first();

        $item->firstname = $firstname;
        $item->lastname = $lastname;
        $item->department = $department;

      }


      return view('/coordinator/timecardsActive')->with('timecards', $timecards);
    }
    public function showTimecardsUnsigned() {
      $this->checkLoggedIn();

      // this next section establishes the saturday of last week
      $startDate = '';
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-m-d', strtotime('Sunday last week'));
          $endDate = date('Y-m-d', strtotime('Saturday this week'));
          break;
        default:
          $startDate = date('Y-m-d', strtotime('-2 weeks Sunday'));
          $start = strtotime('-2 weeks Sunday');
          $end = strtotime('+6 days', $start);
          $endDate = date('Y-m-d', $end);
          break;
      }

      // get all timecards matching date range
      $timecards = DB::table('timecards')
        ->select('worker_id', 'dept_id', 'hours')
        ->where('startDate' , $startDate)
        ->where('endDate', $endDate)
        ->where('signed', 0)
        ->get();

      foreach ($timecards as $i) {
        $firstname = DB::table('workers')->where('id', $i->worker_id)->pluck('firstname')->first();
        $lastname = DB::table('workers')->where('id', $i->worker_id)->pluck('lastname')->first();
        $department = DB::table('departments')->where('id', $i->dept_id)->pluck('name')->first();

        $i->firstname = $firstname;
        $i->lastname = $lastname;
        $i->department = $department;
      }


      return view('/coordinator/timecardsUnsigned')->with('timecards', $timecards);
    }
    public function showTimecardsSubmitted() {
      $this->checkLoggedIn();

      // get all timecards unpaid but signed
      $timecards = DB::table('timecards')
        ->select('worker_id', 'dept_id', 'hours', 'pay', 'grade')
        ->where('signed', 1)
        ->where('paid', 0)
        ->orderBy('startDate', 'desc')
        ->get();

      foreach ($timecards as $i) {
        $firstname = DB::table('workers')->where('id', $i->worker_id)->pluck('firstname')->first();
        $lastname = DB::table('workers')->where('id', $i->worker_id)->pluck('lastname')->first();
        $department = DB::table('departments')->where('id', $i->dept_id)->pluck('name')->first();

        $i->firstname = $firstname;
        $i->lastname = $lastname;
        $i->department = $department;
        $i->grade = strtoupper($i->grade);
      }

      return view('/coordinator/timecardsSubmitted')->with('timecards', $timecards);
    }


    private function checkLoggedIn() {
      // this function checks that the current user is a coordinator.
      // if not, redirect to login page.
      $role = session()->get('role');

      if ($role !== 'coordinator' || empty($role)) {
        return redirect('/');
      }
    }
    private function nextPaymentCountDown() {
      // get all payment periods
      $periods = DB::table('payment_periods')->get();

      // get current timestamp
      $now = strtotime('now');

      foreach ($periods as $index => $i) {
        $startDate = strtotime($i->startDate);
        $endDate = strtotime($i->endDate);

        if ($now >= $startDate && $now <= $endDate) {}
        else {
          $periods->forget($index);
        }
      }

      // return zero if no periods left.
      if ($periods->isEmpty()) {
        return 0;
      }

      // select remaining period and assign
      $period = $periods->first();

      // calculate seconds until end of period
      $unix = strtotime($period->endDate) - $now;

      // $days = 0;
      // $hours = 0;
      // $minutes = 0;
      // $seconds = 0;
      //
      // while ($unix >= 86400) {
      //   $days++;
      //   $unix =- 86400;
      // }
      //
      // while ($unix >= 3600) {
      //   $hours++;
      //   $unix =- 3600;
      // }
      //
      // while ($unix >= 60) {
      //   $minutes++;
      //   $unix =- 60;
      // }
      //
      // $seconds = $unix;

      return $unix;

    }

    private function countWorkers() {
      // this function counts and returns total number of active workers
      $count = DB::table('workers')->count();

      return $count;
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

      // this next section establishes the saturday of last week
      $startDate = '';
      $endDate = '';

      // get three-letter day of the week
      $day = date('D', strtotime('now'));

      switch ($day) {
        case 'Sun':
          $startDate = date('Y-m-d', strtotime('Sunday last week'));
          $endDate = date('Y-m-d', strtotime('Saturday this week'));
          break;
        default:
          $startDate = date('Y-m-d', strtotime('-2 weeks Sunday'));
          $start = strtotime('-2 weeks Sunday');
          $end = strtotime('+6 days', $start);
          $endDate = date('Y-m-d', $end);
          break;
      }

      // count timecards matching time constraints
      $count = DB::table('timecards')
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->count();

      return $count;
    }
    private function countSubmittedTimecards() {
      $this->checkLoggedIn();

      $count = DB::table('timecards')->where('signed', 1)->where('paid', 0)->count();

      return $count;
    }
}
