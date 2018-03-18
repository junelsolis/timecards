  <!doctype html>
<html lang="en">
  <head>
    <title>Supervisor | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
  </head>
  <body>
    @include('/supervisor/navbar')
    <div class="pusher" style="margin: 2%;">
      <div class="ui grid">
        <div class="ten wide column">
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('msg') }}
            </div>
          </div>
          @endif
          @if (session('error'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('error') }}
            </div>
          </div>
          @endif
          @if (!empty($errors->all()))
          <div class="ui red message">
            <i class="close icon"></i>
            <div class="header">
              @foreach ($errors->all() as $message)
              {{ $message }}
              @endforeach
            </div>
          </div>
          @endif
          <h1 class="ui dividing header" id="top">Current Period</h1>
          <p class="text">
            A summary of information related to your departments and workers for the current payment period.
          </p>
          <h2 class="ui yellow header">{{ $period->dateRange }}</h2>
          <div class="ui internally celled grid">
            <div class="eight wide column center aligned">
              <div class="ui statistic">
                <div class="value">
                  {{ $period->totalPayment }}
                </div>
                <div class="label">
                  Payment
                </div>
              </div>
            </div>
            <div class="four wide column center aligned">
              <div class="ui statistic">
                <div class="value">
                  {{ $period->totalHours }}
                </div>
                <div class="label">
                  total hours
                </div>
              </div>
            </div>
            <div class="four wide column center aligned">
              <div class="ui statistic">
                <div class="value">
                  {{ $period->totalTimecards }}
                </div>
                <div class="label">
                  timecards
                </div>
              </div>
            </div>
          </div><br /><br />
          <h2 class="ui blue header" id="payments">Payments</h2>
          <div class="ui divider"></div>
          <div class="ui fluid column">
            <canvas id="paymentHistory"></canvas>
          </div><br /><br />
          <h2 class="ui blue header" id="hours">Total Hours</h2>
          <div class="ui divider"></div>
          <div class="ui fluid column">
            <canvas id="totalHours"></canvas>
            <a href="#top"><i class="angle double up icon"></i>Back to Top</a>
          </div><br /><br />



          <h2 class="ui blue header" id="attendance">Attendance</h2>
          <div class="ui divider"></div>
          <div class="ui internally celled grid">
            <div class="two wide column center aligned">
              <div class="ui tiny yellow statistic">
                <div class="value">
                  {{ $period->totalTardies }}
                </div>
                <div class="label">
                  Tardies
                </div>
              </div>
            </div>
            <div class="two wide column center aligned">
              <div class="ui tiny yellow statistic">
                <div class="value">
                  {{ $period->totalAbsences }}
                </div>
                <div class="label">
                  Absences
                </div>
              </div>
            </div>
          </div>
          @if ($period->totalTardies > 0 || $period->totalAbsences >0)
            <div class="ui styled accordion">
            @foreach ($workers as $worker)
              @if ($worker->tardyDates->count() > 0 || $worker->absentDates->count() > 0)
                <div class="title">
                  <i class="dropdown icon"></i>
                  {{ $worker->fullname }}
                </div>
                <div class="content">
                  <div class="ui two column grid">
                    <div class="column">
                      <h5 class="ui yellow header">Tardies</h5>
                      <div class="ui list">
                        @foreach ($worker->tardyDates as $date)
                        <div class="item">
                          {{ $date }}
                        </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="column">
                      <h5 class="ui yellow header">Absences</h5>
                      <div class="ui list">
                        @foreach ($worker->absentDates as $date)
                        <div class="item">
                          {{ $date }}
                        </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
            </div>
          @endif
          <br />
          <a href="#top"><i class="angle double up icon"></i>Back to Top</a>
          <br /><br />


          @if ($workers->count() > 0)
          <h2 class="ui blue header" id="workers">Workers</h2>
          <div class="ui divider"></div>
          <table class="ui compact table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Hours</th>
                <th>Pay</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($workers as $worker)
              <tr>
                <td>{{ $worker->fullname }}</td>
                <td>{{ $worker->totalHours }}</td>
                <td>Ksh&nbsp;{{ $worker->totalPay }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <a href="#top"><i class="angle double up icon"></i>Back to Top</a>
          @endif
        </div>
      </div>
    </div>
    <script>
      var payments = document.getElementById("paymentHistory");
      var paymentChart = new Chart(payments, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($paymentsGraphData->weeks) ?>,
          datasets: [{
              label: 'Total Payment',
              data: <?php echo json_encode($paymentsGraphData->payments) ?>,
              borderWidth: 2
          }]
        },
        options: {
          legend: {
            position: 'bottom'
          }
        }

      });


      var hours = document.getElementById("totalHours");
      var hoursChart = new Chart(hours, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($hoursGraphData->weeks) ?>,
          datasets: [{
              label: 'Total Hours',
              data: <?php echo json_encode($hoursGraphData->hours) ?>,
              borderWidth: 2
          }]
        },
        options: {
          legend: {
            position: 'bottom'
          }
        }

      });

    </script>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

      $('.ui.accordion')
      .accordion();

      $('.message .close')
    .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
    });
    </script>
  </body>
</html>
