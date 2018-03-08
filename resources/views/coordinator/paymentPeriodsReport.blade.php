<!doctype html>
<html lang="en">
  <head>
    <title>Coordinator | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
  </head>
  <body>
    <div class="ui grid container">
      <div class="sixteen wide column">
          <br />
          <div class="ui internally celled grid">
            <div class="row">
              <div class="four wide column center aligned">
                <div class="ui fluid blue statistic">
                  <div class="value">
                    {{ $item->totalTimecards}}
                  </div>
                  <div class="label">
                    timecards
                  </div>
                </div>
              </div>
              <div class="eight wide column">
                <h2 class="ui header center aligned">Payment Report</h2>
                <h3 class="ui blue header center aligned">{{ $item->dateRange }}</h3>
              </div>
              <div class="four wide column">
                <div class="ui fluid blue statistic">
                  <div class="value">
                    {{ $item->totalPayment}}
                  </div>
                  <div class="label">
                    payment
                  </div>
                </div>
              </div>
            </div>
          </div>
          <table class="ui compact celled table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Total Hours</th>
                <th>Earnings</th>
                <th>Tithe</th>
                <th>Net Pay</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($workers as $worker)
              <tr>
                <td>{{ $worker->id }}</td>
                <td>{{ $worker->fullname }}</td>
                <td class="ui left aligned">{{ $worker->totalHours }}&nbsp;h</td>
                <td>KSh&nbsp;{{ $worker->totalPay }}</td>
                <td class="warning">-&nbsp;KSh&nbsp;{{ $worker->totalTithe }}</td>
                <td class="right aligned"><strong>KSh&nbsp;{{ $worker->netPay }}</strong></td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th><strong>Total Tithe</strong></th>
                <th colspan="2"><strong>KSh&nbsp;{{ $item->totalTithe}}</strong></th>
              </tr>
            </tfoot>
          </table>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });
    </script>
  </body>
</html>
