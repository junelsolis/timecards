<!DOCTYPE html>
<html>
<div class="ui visible sidebar vertical thin left inverted menu">
  <div class="item">
    <div class="header">
      {{ session('fullname') }}
    </div>
    <p class="grey" style="font-size: 0.9em;">
      <em>Supervisor</em>
    </p>
  </div>
  <div class="item">
    <div class="header">
      <a href="/supervisor">Dashboard</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Timecards</div>
    <div class="menu">
      <a class="item" href="">View Active</a>
      <a class="item" href="">View Unsigned</a>
      <a class="item" href="">View Submitted</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Payments</div>
    <div class="menu">
      
    </div>
  </div>
  <div class="item">
    <div class="header">Workers</div>
    <div class="menu">
      <a class="item" href="/coordinator/worker/edit">View</a>
      <a class="item">Attendance</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Departments</div>
    <div class="menu">
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
    <p class="grey" style="font-size: 0.8em;">
      <em>Created by Junel R.S. Solis&nbsp;|&nbsp;MAA</em>
    </p>
  </div>
</div>
</html>
