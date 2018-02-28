<!DOCTYPE html>
<html>
<div style="background-color: rgb(36,47,64); color: white; padding-right: 20px; padding-top: 5px;">
  <div class="row">
    <div class="col-sm-12">
      <p class="text-right" style="font-size: 0.8em;">
        Change Password | <span class="oi oi-icon-name" title="icon name" aria-hidden="true"></span>Logout
      </p>
    </div>
  </div>
</div>
<nav class="navbar navbar-expand-sm bg-light">
  <a class="navbar-brand" href="/coordinator">Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon">Menu</span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Timecards
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/coordinator/timecards/create">Create</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/coordinator/timecards/active">View Active</a>
          <a class="dropdown-item" href="/coordinator/timecards/unsigned">View Unsigned</a>
          <a class="dropdown-item" href="/coordinator/timecards/submitted">View Submitted</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/coordinator/timecards/import">Import</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Payments
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/coordinator/payments/pay">Pay</a>
          <a class="dropdown-item" href="#">Generate Report</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/coordinator/payment-periods">Payment Periods</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Set Payscale</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Workers
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/coordinator/worker/add">Add</a>
          <a class="dropdown-item" href="/coordinator/worker/edit">Edit</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Attendance</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Supervisors
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/coordinator/supervisor/add">Add</a>
          <a class="dropdown-item" href="/coordinator/supervisor/edit">Edit</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/coordinator/departments">Departments</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search Worker" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
</html>
