<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use stdClass;

class CoordinatorController extends Controller
{
    public function main() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }


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
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get department objects and sort by name
      // split into two chunks
      $collection = DB::table('departments')->orderBy('name')->get();
      $departments = $collection->split(2);

      // return view with data
      return view('coordinator/supervisorAdd')
        ->with('departments', $departments);
    }
    public function supervisorAdd(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate request
      $request->validate([
        'username' => 'email|required',
        'firstname' => 'string|required',
        'lastname' => 'string|required',
        'departments' => 'array'
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
      $id = DB::table('supervisors')->insertGetId([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'token' => $token
      ]);

      // create department entries for supervisor
      foreach ($departments as $i) {
        DB::table('superv_depts')->insert([
          'superv_id' => $id,
          'dept_id' => $i
        ]);
      }


      return redirect('/coordinator/supervisor/add')
        ->with('msg', 'Supervisor successfully added.');

    }
    public function showSupervisorEdit() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // this function retrieves all supervisors
      // and returns it to a view.

      $supervisors = DB::table('supervisors')
        ->select('id','firstname','lastname','email')
        ->orderBy('lastname')->get();
      $departments = DB::table('departments')->get();
      $supervDepts = DB::table('superv_depts')->get();
      $unsignedTimecards = $this->getUnsignedTimecards();

      foreach ($supervisors as $item) {
        $item->fullname = $item->firstname . ' ' . $item->lastname;

        $item->departmentCount = $supervDepts->where('superv_id', $item->id)->count();
        $item->countUnsignedTimecards = 0;
        $departmentsId = $supervDepts->where('superv_id', $item->id)->pluck('dept_id');


        foreach ($departmentsId as $id) {
          $item->countUnsignedTimecards = $unsignedTimecards->where('dept_id', $id)->count();
        }

      }

      // statistics
      $totalSupervisors = $supervisors->count();
      $totalDepartments = $departments->count();

      return view('/coordinator/supervisorEdit')
        ->with('supervisors', $supervisors)
        ->with('totalSupervisors', $totalSupervisors)
        ->with('totalDepartments', $totalDepartments);

    }
    public function showSupervisorEditItem(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // validate
      $request->validate([
          'id' => 'required|integer'
      ]);

      // get supervisor id
      $id = $request['id'];

      // retrieve object with id from database.
      $supervisor = DB::table('supervisors')->where('id', $id)->first();

      $supervisor->fullname = $supervisor->firstname . ' ' . $supervisor->lastname;

      // get all department names supervisor belongs to
      $ids = DB::table('superv_depts')->where('superv_id', $supervisor->id)->pluck('dept_id');
      $names = array();

      foreach ($ids as $item) {
        $name = DB::table('departments')->where('id', $item)->first();
        $names[] = $name->name;
      }

      // count supervisor's unsigned timecards
      $unsigned = $this->getUnsignedTimecards();
      $unsignedTimecards = 0;
      foreach ($ids as $item) {
        $count = $unsigned->where('dept_id', $item)->count();

        $unsignedTimecards = $unsignedTimecards + $count;
      }

      $supervisor->unsignedTimecards = $unsignedTimecards;

      $supervisor->departments = $names;
      $supervisor->departmentIds = $ids->toArray();

      // get all possible departments and split into two
      $depts = DB::table('departments')->orderBy('name')->get();
      $depts = $depts->split(2);

      return view('/coordinator/supervisorEditItem')
        ->with('supervisor', $supervisor)
        ->with('departments', $depts)
        ->with('unsignedTimecards', $unsignedTimecards);
    }
    public function supervisorEditItem(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // validate request
      $request->validate([
        'id' => 'required|integer',
        'departments' => 'array|nullable'
      ]);


      $id = $request['id'];
      $departments = $request['departments'];

      // delete all department entries for this supervisor id
      DB::table('superv_depts')->where('superv_id', $id)->delete();

      // make new department entries for supervisor
      foreach ($departments as $item) {
        DB::table('superv_depts')->insert(
            ['superv_id' => $id, 'dept_id' => $item]
          );
      }

      return redirect('/coordinator/supervisor/edit/item?id='.$id)->with('msg', 'Supervisor updated.');
    }

    public function showDepartments() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      $departments = DB::table('departments')->orderBy('name')->get();
      $supervDepts = DB::table('superv_depts')->get();
      $workerDepts = DB::table('worker_depts')->get();
      // all active timecards
      $activeTimecards = $this->getActiveTimecards();



      // append additional information
      foreach ($departments as $item) {

        // get total number of timecards for this department
          $total = DB::table('timecards')->where('dept_id', $item->id)->count();
          $item->totalTimecards = $total;

        // count active timecards for this department
          $count = 0;
          foreach ($activeTimecards as $card) {
            if ($item->id == $card->dept_id) {
              $count++;
            }
          }

          $item->activeTimecards = $count;

        // count number of supervisors for this department
          $count = 0;
          foreach ($supervDepts as $i) {
            if ($i->dept_id == $item->id) {
              $count++;
            }
          }

          if ($count != 1) {
            $count = $count . ' Supervisors';
          } else {
            $count = $count .' Supervisor';
          }
          $item->supervisorCount = $count;

        // count number of workers for this department
          $count = 0;
          foreach ($workerDepts as $i) {
            if ($i->dept_id == $item->id) {
              $count++;
            }
          }

          if ($count != 1) {
            $count = $count . ' Workers';
          } else {
            $count = $count . ' Worker';
          }
          $item->workerCount = $count;

      }


      return view('/coordinator/departments')->with('departments', $departments);
    }
    public function showDepartmentsAdd() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // get all supervisors
      $supervisors = DB::table('supervisors')->orderBy('lastname')->get();

      // modify supervisors collection
      foreach ($supervisors as $item) {
        $firstname = $item->firstname;
        $lastname = $item->lastname;

        $fullname = $firstname . " " . $lastname;

        $item->fullname = $fullname;
      }


      return view('/coordinator/departmentsAdd')->with('supervisors', $supervisors);


    }
    public function departmentsAdd(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // validation
      $request->validate([
        'name' => 'string|required|between:1,40',
        'supervisors' => 'nullable'
      ]);

      $name = $request['name'];
      $supervisors = $request['supervisors'];

      // check that department does not already exist
      $count = DB::table('departments')->where('name', $name)->count();

      if ($count != 0) {
        return back()->with('error', "That department already exists. Consider editing the department instead.");
      }

      // if department does not exist run the following code

      // insert new department entry and get auto increment ID
      $id = DB::table('departments')->insertGetId(['name' => $name]);

      // if supervisors have been selected, include entries for them.
      if (count($supervisors) > 0) {
        foreach ($supervisors as $item) {
          DB::table('superv_depts')->insert([
            'superv_id' => $item,
            'dept_id' => $id
          ]);
        }


      }

      return redirect('/coordinator/departments')->with('msg', "Department successfully created.");
    }

    public function showWorkerAdd() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // get department objects and sort by name
      // split into two chunks
      $collection = DB::table('departments')->orderBy('name')->get();
      $departments = $collection->split(2);

      // return view with data
      return view('/coordinator/workerAdd')->with('departments', $departments);
    }
    public function workerAdd(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      // append domain name to username entry
      $request['username'] = $request['username'] . '@maxwellsda.org';

      // validate entries
      $request->validate([
        'username' => 'email|required',
        'firstname' => 'string|required',
        'lastname' => 'string|required',
        'departments' => 'array'
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
      $id = DB::table('workers')->insertGetId([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $username,
        'token' => $token
      ]);

      foreach ($departments as $i) {
        DB::table('worker_depts')->insert([
          'worker_id' => $id,
          'dept_id' => $i
        ]);
      }

      return redirect('/coordinator/worker/add')->with('msg', 'Worker successfully added.');

    }
    public function showWorkerEdit() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
      $timecards = DB::table('timecards')->get();
      $activeTimecards = $this->getActiveTimecards();
      $workers = DB::table('workers')->orderBy('lastname')->get();
      $workerDepts = DB::table('worker_depts')->get();
      $departments = DB::table('departments')->get();

      foreach($workers as $worker) {

        $worker->fullname = $worker->firstname . ' ' . $worker->lastname;

        // get total timecards for each worker
        $totalTimecards = $timecards->where('worker_id', $worker->id)->count();
        $worker->totalTimecards = $totalTimecards;

        // get total active timecards for each worker
        $count = 0;
        foreach ($activeTimecards as $card) {
          if ($card->worker_id == $worker->id) {
            $count++;
          }
        }

        $worker->activeTimecards = $count;

        // get all department names for each worker
        $deptIds = $workerDepts->where('worker_id', $worker->id);
        $departmentNames = collect();
        foreach ($deptIds as $id) {
          $name = $departments->where('id', $id->dept_id)->first();
          $departmentNames->push($name->name);
        }

        $worker->departments = $departmentNames;
      }


      // count all workers
      $totalWorkers = $workers->count();

      // count all departments
      $totalDepartments = $departments->count();

      // count all timecards
      $totalTimecards = $timecards->count();
      $totalTimecards = number_format($totalTimecards);

      // return view
      return view('/coordinator/workerEdit')
        ->with('workers', $workers)
        ->with('totalWorkers', $totalWorkers)
        ->with('totalDepartments', $totalDepartments)
        ->with('totalTimecards', $totalTimecards);


    }
    public function showWorkerEditItem(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);


      $id = $request['id'];
      $worker = $this->getWorker($id);

      // get all possible departments and split into two
      $depts = DB::table('departments')->orderBy('name')->get();
      $depts = $depts->split(2);

      return view('/coordinator/workerEditItem')
        ->with('worker', $worker)
        ->with('depts', $depts);




    }
    public function workerEditItem(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer',
        'departments' => 'nullable|array'
      ]);

      $id = $request['id'];
      $departments = $request['departments'];

      // delete all department entries for this worker
      DB::table('worker_depts')->where('worker_id', $id)->delete();

      // make new entries for each department
      foreach ($departments as $item) {
        DB::table('worker_depts')->insert([
          'worker_id' => $id,
          'dept_id' => $item
        ]);
      }

      return redirect('/coordinator/worker/edit')->with('msg', 'Worker details updated.');

    }
    public function showAttendance() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get all workers
      $workers = DB::table('workers')->orderBy('lastname')->get();

      // append worker fullnames
      foreach ($workers as $worker) {
        $firstname = $worker->firstname;
        $lastname = $worker->lastname;

        $worker->fullname = $firstname . ' ' . $lastname;
      }

      // append tardy and absent dates
      foreach ($workers as $worker) {
        $worker->tardyDates = $this->getWorkerTardies($worker);
        $worker->absentDates = $this->getWorkerAbsences($worker);
      }

      // remove workers with no attendance problems
      foreach ($workers as $index => $worker) {
        $tardies = $worker->tardyDates->count();
        $absences = $worker->absentDates->count();

        if ($tardies == 0 && $absences == 0) {
          $workers->forget($index);
        }
      }


      $grouped = $workers->groupBy(function ($item, $key) {
        return substr($item->lastname, 0,1);
      });

      return view('/coordinator/workerAttendance')
        ->with('workers', $grouped);
    }

    public function showPaymentPeriods() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }
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
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      return view('/coordinator/paymentPeriodsAdd');
    }
    public function showPaymentPeriodsReport(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);

      $id = $request['id'];

      // get period from DB
      $period = DB::table('payment_periods')->where('id', $id)->first();
      // all timecards from DB;
      $timecards = DB::table('timecards')->get();


      // store all associated timecards
      $timecards = $this->getPeriodTimecards($period, $timecards);

      // total of all timecards
      $period->totalTimecards = $timecards->count();

      // total payment
      $period->totalPayment = number_format($timecards->sum('pay'));
      // add date range
      $startDate = strtotime($period->startDate);
      $endDate = strtotime($period->endDate);

      $period->dateRange = date('d F', $startDate) . ' - ' . date('d F', $endDate);


      // add all workers and their assoc relevant information
      $workers = DB::table('workers')
        ->orderBy('lastname')
        ->select('id', 'firstname', 'lastname', 'email')
        ->get();

        $period->workers = $workers;

        foreach ($period->workers as $worker) {
          // append fullname
          $worker->fullname = $worker->firstname . ' ' . $worker->lastname;

          // append associated timecards
          $cards = $timecards->where('worker_id', $worker->id);

          // append total hours
          $total = 0;
          foreach ($cards as $timecard) {
            $total += $timecard->hours;
          }
          $worker->totalHours = round($total,2);

          // append total pay
          $total = 0;
          foreach ($cards as $timecard) {
            $total += $timecard->pay;
          }
          $worker->totalPay = $total;

          // append tithe
          $total = 0;
          foreach ($cards as $timecard) {
            $tithe = $timecard->pay * 0.10;
            $total += $tithe;
          }
          $worker->totalTithe = round($total);

          $worker->netPay = ($worker->totalPay) - ($worker->totalTithe);
        }

        // calculate totalTithe
        $totalTithe = 0;
        foreach ($period->workers as $worker) {
          $total = $worker->totalTithe;
          $totalTithe += $total;
        }
        $period->totalTithe = number_format($totalTithe);

      // total net pay
      $totalNetPay = number_format($workers->sum('netPay'));

      return view('/coordinator/paymentPeriodsReport')
        ->with('item', $period)
        ->with('workers', $workers)
        ->with('totalNetPay', $totalNetPay);
    }
    public function paymentPeriodsAdd(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

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
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get all payment periods
      $periods = DB::table('payment_periods')->orderBy('endDate', 'asc')->get();
      // get all timecards
      $timecards = DB::table('timecards')->get();

      foreach ($periods as $period) {
        $cards = $this->getPeriodTimecards($period, $timecards);


        // total timecards
        $period->totalTimecards = $cards->count();
        // unsigned timecards
        $period->unsignedTimecards = $cards->where('signed', 0)->where('paid', 0)->count();
        // submitted timecards
        $period->submittedTimecards = $cards->where('signed', 1)->where('paid', 0)->count();
        // remaining timecards
        $period->remainingTimecards = ($cards->count()) - ($cards->where('signed', 1)->where('paid',0)->count());

        // total payment
        $period->totalPayment = number_format($cards->sum('pay'));

        // date range
        $startDate = strtotime($period->startDate);
        $endDate = strtotime($period->endDate);

        $period->dateRange = date('d M', $startDate) . ' - ' . date('d M', $endDate);

        // if all timecards paid, send variable to enable payment
        if ($period->unsignedTimecards == 0) {
          $period->complete = true;
        } else {
          $period->complete = false;
        }

      }

      $unpaid = $periods->where('paid', 0);
      $paid = $periods->where('paid', 1);
      $paid = $paid->sortByDesc('endDate');

      return view('/coordinator/paymentsPay')
        ->with('unpaid', $unpaid)
        ->with('paid', $paid);


    }
    public function showPaySelected(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // validate request
      $request->validate([
        'id' => 'required|integer'
      ]);


      // retrieve payment period
      $id = $request['id'];
      $period = DB::table('payment_periods')->where('id', $id)->first();

      // set date range string
      $startDate = date('d M', strtotime($period->startDate));
      $endDate = date('d M', strtotime($period->endDate));
      $year = date('Y', strtotime($period->endDate));

      $period->dateRange = $startDate . ' - ' . $endDate;
      $period->year = $year;

      // calculate total payment
      $timecards = DB::table('timecards')->get();

      $cards = $this->getPeriodTimecards($period, $timecards);

      $period->totalPayment = number_format($cards->sum('pay'));

      return view('/coordinator/paymentsPaySelected')
        ->with('period', $period);


    }
    public function paySelected(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);

      // retrieve period
      $id = $request['id'];
      $period = DB::table('payment_periods')->where('id', $id)->first();

      // retrieve associated timecards
      $timecards = DB::table('timecards')->get();
      $cards = $this->getPeriodTimecards($period, $timecards);

      // check to make sure all timecards are signed
      // return to main screen if not
      $unsigned = $cards->where('signed', 0)->count();

      if ($unsigned != 0) {
        return redirect('/coordinator');
      }

      foreach ($cards as $card) {
        // update database entry
        DB::table('timecards')->where('id', $card->id)->update([
          'paid' => 1
        ]);
      }

      // update period as paid
      DB::table('payment_periods')->where('id', $id)->update([
        'paid' => 1
      ]);

      return redirect('/coordinator/payments/pay');



    }
    public function showPaySelectedDetails(Request $request){
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);


      // get payment period
      $id = $request['id'];
      $period = DB::table('payment_periods')->where('id', $id)->first();

      // all workers
      $workers = DB::table('workers')->get();
      // all supervisors
      $supervisors = DB::table('supervisors')->get();
      // supervisor departments
      $supervisorDepts = DB::table('superv_depts')->get();
      // all departments
      $departments = DB::table('departments')->get();
      // all timecards
      $allTimecards = DB::table('timecards')->get();

      // add associated timecards
      $timecards = $this->getPeriodTimecards($period, $allTimecards);

      // count unsigned timecards
      $period->unsignedTimecards = $timecards->where('signed', 0)->count();

      // add worker fullname and date string to each timecard
      foreach ($timecards as $card) {
        $worker = $workers->where('id', $card->worker_id)->first();

        $firstname = $worker->firstname;
        $lastname = $worker->lastname;

        $card->fullname = $firstname . ' ' . $lastname;

        $startDate = date('d M', strtotime($card->startDate));
        $endDate = date('d M', strtotime($card->endDate));

        $card->dateRange = $startDate . ' - ' . $endDate;
      }

      // add date range string
      $startDate = date('d M', strtotime($period->startDate));
      $endDate = date('d M', strtotime($period->endDate));
      $year = date('Y', strtotime($period->endDate));

      $period->dateRange = $startDate . ' - ' . $endDate . ' ' . $year;

      // add all departments
      $departments = DB::table('departments')->orderBy('name', 'asc')->get();
      $period->departments = $departments;

      // add unsigned timecards to each department
      foreach ($period->departments as $item) {

        $item->timecards = $timecards->where('dept_id', $item->id)->where('signed', 0);
      }

      // remove departments with no unsigned timecards
      foreach ($period->departments as $key => $department) {
        if ($department->timecards->count() == 0) {
          $departments->forget($key);
        }
      }

      // count unsigned departments for each timecard
      $count = 0;
      foreach ($period->departments as $item) {
        $total = $timecards->where('dept_id', $item->id)->where('signed', 0)->count();

        if ($total != 0) { $count++; }
      }
      $period->unsignedTimecardsDepartments = $count;

      // count number of workers with unsigned timecards
      $count = 0;
      foreach ($workers as $worker) {
        $total = $timecards->where('worker_id', $worker->id)->where('signed', 0)->count();

        if ($total != 0) { $count++; }
      }
      $period->unsignedTimecardsWorkers = $count;

      // count number of supervisors with unsigned timecards
      $count = 0;
      foreach ($supervisors as $supervisor) {
        $depts = $supervisorDepts->where('superv_id', $supervisor->id);

        $overallTotal = 0;
        foreach ($depts as $item) {
          $total = $timecards->where('dept_id', $item->dept_id)->count();
          $overallTotal += $total;
        }

        if ($overallTotal != 0) { $count++; }
      }
      $period->unsignedTimecardsSupervisors = $count;

      return view('/coordinator/paymentsPaySelectedDetails')
        ->with('period', $period);
    }
    public function showPayscale() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get all payscales
      $payscales = DB::table('payscale')->get();

      $items = collect();

      foreach ($payscales as $i) {
        if ($i->grade == "u") {
          $items->put('Unsatisfactory', $i);
          continue;
        }

        if ($i->grade == "s") {
          $items->put('Satisfactory', $i);
          continue;
        }

        if ($i->grade == "o") {
          $items->put('Outstanding', $i);
          continue;
        }

      }

      // return view
      return view('/coordinator/paymentsPayscale')->with('items', $items);
    }
    public function setPayscale(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // validate form variables
      $request->validate([
        'u' => 'required|integer|min:1',
        's' => 'required|integer|min:1',
        'o' => 'required|integer|min:1'
      ]);

      $u = $request['u'];
      $s = $request['s'];
      $o = $request['o'];

      // update new values in db
      DB::table('payscale')->where('grade', 'u')->update(['pay' => $u]);
      DB::table('payscale')->where('grade', 's')->update(['pay' => $s]);
      DB::table('payscale')->where('grade', 'o')->update(['pay' => $o]);

      return redirect('/coordinator/payments/payscale')->with('msg', 'Payscale successfully updated.');

    }

    public function timecardsImport() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

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
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      return view('/coordinator/timecardsCreate');
    }
    public function timecardsCreate(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // validate input
      $request->validate([
        'startDate' => 'date|required',
      ]);


      // check startDate is a sunday
      $start = date('D', strtotime($request['startDate']));
      if ($start !== 'Sun') {
        return back()->with('error', 'Start date must be a Sunday.');
      }

      // check end date is not same as or before start date
      $start = strtotime($request['startDate']);
      $end = strtotime('+6 days', $start);

      // if all validation passed, continue
      $startDate = date('Y-m-d', $start);
      $endDate = date('Y-m-d', $end);

      // get all workers
      $workers = DB::table('workers')->orderBy('lastname')->get();
      $workerDepts = DB::table('worker_depts')->get();

      // foreach worker, get all dept_ids
      foreach ($workers as $worker) {
        $deptIds = $workerDepts->where('worker_id', $worker->id)->pluck('dept_id');

        // for each id, check if timecard alread exists
        // if not exists, create new timecard
        foreach ($deptIds as $id) {
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
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

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
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->get();

      $workers = DB::table('workers')->get();
      $departments = DB::table('departments')->get();

      // additional data
      foreach ($timecards as $item) {

        $worker = $workers->where('id', $item->worker_id)->first();
        $department = $departments->where('id', $item->dept_id)->first();

        $item->firstname = $worker->firstname;
        $item->lastname = $worker->lastname;
        $item->department = $department->name;

        // total tardies
        $item->tardies = $this->countTimecardTardies($item);

        // total absences
        $item->absences = $this->countTimecardAbsences($item);

      }

      // statistics
      $totalTimecards = count($timecards);
      $totalHours = $timecards->sum('hours');
      $signedTimecards = 0;

      foreach($timecards as $item) {
        if ($item->signed == 1) { $signedTimecards++; }
      }

      $sorted = $timecards->sortBy('lastname');

      return view('/coordinator/timecardsActive')
        ->with('timecards', $sorted)
        ->with('totalTimecards', $totalTimecards)
        ->with('totalHours', $totalHours)
        ->with('signedTimecards', $signedTimecards);


    }
    public function showTimecardsUnsigned() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $timecards = $this->getUnsignedTimecards();

      $workers = DB::table('workers')->get();
      $departments = DB::table('departments')->get();

      $count = $this->countUnsignedTimecards();
      $total = 0;

      foreach ($timecards as $item) {

        // get worker for this timecard
        $worker = $workers->where('id', $item->worker_id)->first();

        $firstname = $worker->firstname;
        $lastname = $worker->lastname;

        // store fullname
        $item->fullname = $firstname . ' ' . $lastname;

        // store department name
        $department = $departments->where('id', $item->dept_id)->first();
        $item->department = $department->name;

        // add to total hours
        $total = $total + $item->hours;

        // count tardies
        $item->tardies = $this->countTimecardTardies($item);

        // count absences
        $item->absences = $this->countTimecardAbsences($item);
      }

      $sorted = $timecards->sortBy('startDate');

      return view('/coordinator/timecardsUnsigned')
        ->with('timecards', $sorted)
        ->with('count', $count)
        ->with('total', $total);
    }
    public function showTimecardsSubmitted() {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      // get all timecards unpaid but signed
      $timecards = DB::table('timecards')
        //->select('id', 'worker_id', 'dept_id', 'hours', 'pay', 'grade')
        ->where('signed', 1)
        ->where('paid', 0)
        ->get();

      $departments = DB::table('departments')->get();
      $workers = DB::table('workers')->get();

      foreach ($timecards as $i) {

        $worker = $workers->where('id', $i->worker_id)->first();

        $department = $departments->where('id', $i->dept_id)->first();

        $i->firstname = $worker->firstname;
        $i->lastname = $worker->lastname;
        $i->department = $department->name;
        $i->grade = strtoupper($i->grade);

        $startDate = date('d M', strtotime($i->startDate));
        $endDate = date('d M', strtotime($i->endDate));

        $i->dateRange = $startDate . ' - ' . $endDate;

        $i->tardies = $this->countTimecardTardies($i);
        $i->absences = $this->countTimecardAbsences($i);

      }

      $sorted = $timecards->sortBy('lastname');

      // statistics
      $totalTimecards = count($timecards);
      $totalHours = number_format($timecards->sum('hours'));
      $totalPay = number_format($timecards->sum('pay'));

      return view('/coordinator/timecardsSubmitted')
        ->with('timecards', $sorted)
        ->with('totalTimecards', $totalTimecards)
        ->with('totalHours', $totalHours)
        ->with('totalPay', $totalPay);
    }
    public function timecardsSubmittedReturn(Request $request) {
      $check = $this->checkLoggedIn();
      if ($check == true) {} else { return redirect('/'); }

      $request->validate([
        'id' => 'required|integer'
      ]);

      $id = $request['id'];

      // unsign timecard with matching id
      DB::table('timecards')->where('id', $id)->update(['signed' => 0]);

      return redirect('/coordinator/timecards/submitted')->with('msg', 'Timecard returned to supervisor');
    }


    private function checkLoggedIn() {
      // this function checks that the current user is a coordinator.
      // if not, redirect to login page.
      $role = session('role');

      if ($role == 'coordinator') { return true;} else { return false; }
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
    private function getActiveTimecards() {
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
      $timecards = DB::table('timecards')
        ->where('startDate', $startDate)
        ->where('endDate', $endDate)
        ->get();

      return $timecards;
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

    private function countTimecardTardies($timecard) {

      // this function counts the total number of tardies in a single timecard
      $count = 0;

      if ($timecard->sunTardy == true) { $count++; }
      if ($timecard->monTardy == true) { $count++; }
      if ($timecard->tueTardy == true) { $count++; }
      if ($timecard->wedTardy == true) { $count++; }
      if ($timecard->thuTardy == true) { $count++; }
      if ($timecard->friTardy == true) { $count++; }
      if ($timecard->satTardy == true) { $count++; }

      return $count;
    }
    private function countTimecardAbsences($timecard) {

      // this function counts the total number of absences in a single timecard
      $count = 0;

      if ($timecard->sunAbsent == true) { $count++; }
      if ($timecard->monAbsent == true) { $count++; }
      if ($timecard->tueAbsent == true) { $count++; }
      if ($timecard->wedAbsent == true) { $count++; }
      if ($timecard->thuAbsent == true) { $count++; }
      if ($timecard->friAbsent == true) { $count++; }
      if ($timecard->satAbsent == true) { $count++; }

      return $count;
    }

    private function getWorker($id) {
      // receives a worker id and returns a worker object with additional information
      $worker = DB::table('workers')->where('id', $id)->first();

      $departments = DB::table('departments')->get();
      $workerDepts = DB::table('worker_depts')->where('worker_id', $id)->get();
      $timecards = DB::table('timecards')->where('worker_id', $id)->get();

      // add collection of department names
      $departmentNames = collect();
      foreach ($workerDepts as $item) {
        $department = $departments->where('id', $item->dept_id)->first();
        $departmentNames->push($department->name);
      }

      $worker->departmentNames = $departmentNames;

      // add array of department id's
      $array = array();
      foreach ($workerDepts as $item) {
        $deptId = $departments->where('id', $item->dept_id)->first();
        $array[] = $deptId->id;
      }

      $worker->deptIds = $array;


      // add departments count
      $worker->totalDepartments = $workerDepts->count();
      // add timecards count
      $worker->totalTimecards = $timecards->count();

      // count total tardies
      $count = 0;
      foreach ($timecards as $card) {
        $tardies = $this->countTimecardTardies($card);
        $count = $count + $tardies;
      }
      $worker->totalTardies = $count;

      // count total absences
      $count = 0;
      foreach ($timecards as $card) {
        $absences = $this->countTimecardAbsences($card);
        $count = $count + $absences;
      }
      $worker->totalAbsences = $count;

      // add fullname
      $worker->fullname = $worker->firstname . ' ' . $worker->lastname;

      return $worker;

    }

    private function getPeriodTimecards($period, $timecards) {
      // this function takes a payment period and timecards and returns all the timecards within that period

      $periodStart = strtotime($period->startDate);
      $periodEnd = strtotime($period->endDate);

      $cards = collect();
      foreach ($timecards as $card) {
        $cardStart = strtotime($card->startDate);
        $cardEnd = strtotime($card->endDate);

        if ($cardStart >= $periodStart && $cardEnd <= $periodEnd) {
          $cards->push($card);
        }
      }

      return $cards;
    }

    private function getWorkerTimecards($worker) {
      // accepts a worker object and returns all the timecards associated with the worker

      $timecards = DB::table('timecards')
        ->where('worker_id', $worker->id)
        ->orderBy('startDate')
        ->get();

      return $timecards;

    }
    private function getWorkerTardies($worker) {
      $timecards = $this->getWorkerTimecards($worker);
      $departments = DB::table('departments')->get();

      $tardies = collect();

      foreach ($timecards as $timecard) {
        $start = strtotime($timecard->startDate);

        $tardies = collect();
        if ($timecard->sunTardy == 1) {

          $date = date('Y-M-d', $start);
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->monTardy == 1) {
          $date = date('Y-M-d', strtotime('+1 day', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->tueTardy == 1) {
          $date = date('Y-M-d', strtotime('+2 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->wedTardy == 1) {
          $date = date('Y-M-d', strtotime('+3 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->thuTardy == 1) {
          $date = date('Y-M-d', strtotime('+4 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->friTardy == 1) {
          $date = date('Y-M-d', strtotime('+5 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

        if ($timecard->satTardy == 1) {
          $date = date('Y-M-d', strtotime('+6 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $tardy = collect();

          $tardy->date = $date;
          $tardy->department = $department->name;
          $tardy->timecardId = $timecardId;

          $tardies->push($tardy);
        }

      }

      return $tardies;
    }
    private function getWorkerAbsences($worker) {
      $timecards = $this->getWorkerTimecards($worker);
      $departments = DB::table('departments')->get();

      $absences = collect();

      foreach ($timecards as $timecard) {
        $start = strtotime($timecard->startDate);

        if ($timecard->sunAbsent == 1) {

          $date = date('Y-M-d', $start);
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);

        }

        if ($timecard->monAbsent == 1) {
          $date = date('Y-M-d', strtotime('+1 day', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

        if ($timecard->tueAbsent == 1) {
          $date = date('Y-M-d', strtotime('+2 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

        if ($timecard->wedAbsent == 1) {
          $date = date('Y-M-d', strtotime('+3 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

        if ($timecard->thuAbsent == 1) {
          $date = date('Y-M-d', strtotime('+4 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

        if ($timecard->friAbsent == 1) {
          $date = date('Y-M-d', strtotime('+5 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

        if ($timecard->satAbsent == 1) {
          $date = date('Y-M-d', strtotime('+6 days', $start));
          $department = $departments->where('id', $timecard->dept_id)->first();
          $timecardId = $timecard->id;

          $absence = collect();

          $absence->date = $date;
          $absence->department = $department->name;
          $absence->timecardId = $timecardId;

          $absences->push($absence);
        }

      }

      return $absences;
    }

}
