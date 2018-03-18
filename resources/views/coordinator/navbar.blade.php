<!DOCTYPE html>
<html>
<div class="ui visible sidebar vertical thin left inverted menu">
  <div class="item">
    <div class="header">
      {{ session('fullname') }}
    </div>
    <p class="grey" style="font-size: 0.9em;">
      <em>Coordinator</em>
    </p>
  </div>
  <div class="item">
    <div class="header">
      <a href="/coordinator">Dashboard</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Timecards</div>
    <div class="menu">
      <a class="item" href="/coordinator/timecards/create">Create</a>
      <a class="item" href="/coordinator/timecards/active">View Active</a>
      <a class="item" href="/coordinator/timecards/unsigned">View Unsigned</a>
      <a class="item" href="/coordinator/timecards/submitted">View Submitted</a>
      <a class="item" href="/coordinator/timecards/import">Import</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Payments</div>
    <div class="menu">
      <a class="item" href="/coordinator/payments/pay">Pay</a>
      <a class="item" href="/coordinator/payment-periods">Payment Periods</a>
      <a class="item" href="/coordinator/payments/payscale">Set Payscale</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Workers</div>
    <div class="menu">
      <a class="item" href="/coordinator/worker/add">Add</a>
      <a class="item" href="/coordinator/worker/edit">View</a>
      <a class="item" href="/coordinator/worker/attendance">Attendance</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Supervisors</div>
    <div class="menu">
      <a class="item" href="/coordinator/supervisor/add">Add</a>
      <a class="item" href="/coordinator/supervisor/edit">View</a>
      <a class="item" href="/coordinator/departments">Departments</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Support</div>
    <div class="menu">
      <a class="item">Send Email</a>
    </div>
  </div>
  <div class="item">
    <div class="header">
      <a href="/logout">Logout</a>
    </div>
  </div>
  <div class="item">
    <p class="grey" style="font-size: 0.8em;">
      <em>Created by Junel R.S. Solis&nbsp;|&nbsp;MAA</em>
    </p>
  </div>
</div>
</html>
