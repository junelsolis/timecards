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
    @include('/coordinator/navbar')
    <div class="pusher" style="margin: 2%;">
      <div class="ui grid">
        <div class="ten wide column">
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              Success.
            </div>
            {{ session('msg') }}
          </div>
          @endif

          <h1 class="ui dividing header" id="top">{{ $period->dateRange }}</h1>
          <h2 class='ui blue header'>Unsigned Timecards</h2>
              <div class="ui internally celled grid">
                <div class="four wide column">
                  <div class='ui statistic'>
                    <div class="value">
                      {{ $period->unsignedTimecards }}
                    </div>
                    <div class="label">
                      Unsigned
                    </div>
                  </div>
                </div>
                <div class="four wide column middle aligned">
                  <div class="ui yellow statistic">
                    <div class="value">
                      {{ $period->unsignedTimecardsDepartments }}
                    </div>
                    <div class="label">
                      Departments
                    </div>
                  </div>
                </div>
                <div class="four wide column middle aligned">
                  <div class="ui yellow statistic">
                    <div class="value">
                      {{ $period->unsignedTimecardsWorkers }}
                    </div>
                    <div class="label">
                      Workers
                    </div>
                  </div>
                </div>
                <div class="four wide column middle aligned">
                  <div class="ui yellow statistic">
                    <div class="value">
                      {{ $period->unsignedTimecardsSupervisors }}
                    </div>
                    <div class="label">
                      Supervisors
                    </div>
                  </div>
                </div>
              </div>
              <a href="/coordinator/payments/pay"><i class="angle double left icon"></i>Back</a>
              @if (count($period->departments) > 0)
              <div class="ui divider"></div>
              <?php
                $items = $period->departments->split(2);
              ?>
              <div class="ui two column grid">
                <div class="ui column">
                  <div class="ui list">
                    @foreach ($items[0] as $item)
                    <div class='item'>
                      <div class='content'>
                        <a href="#Unsigned - {{ $item->name }}">{{ $item->name }} <strong>({{ $item->timecards->count() }})</strong></a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                @if ($items->count() > 1)
                <div class="ui column">
                  <div class="ui list">
                    @foreach ($items[1] as $item)
                    <div class='item'>
                      <div class='content'>
                        <a href="#Unsigned - {{ $item->name }}">{{ $item->name }} <strong>({{ $item->timecards->count() }})</strong></a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                @endif
              </div>
              <div class="ui divider"></div>
              @endif

              @foreach ($period->departments as $department)
              <div class="ui dividing header" id="Unsigned - {{ $department->name }}">
                {{ $department->name }}
              </div>
              <table class="ui very compact table">
                <thead>
                  <tr>
                    <th>Timecard #</th>
                    <th>Worker</th>
                    <th>Date</th>
                    <th>Total Hours</th>
                    <th>Grade</th>
                    <th>Pay</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($department->timecards as $timecard)
                  <tr>
                    <td>{{ $timecard->id }}</td>
                    <td>{{ $timecard->fullname }}</td>
                    <td>{{ $timecard->dateRange }}</td>
                    <td>{{ $timecard->hours }}</td>
                    <td>{{ strtoupper($timecard->grade) }}</td>
                    <td>Ksh&nbsp;{{ $timecard->pay }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="ui grid">
                <div class='two column row'>
                  <div class="left floatd column">
                    <a href="#" class="ui tiny basic blue button disabled">Remind Supervisor</a>
                  </div>
                  <div class="right floated right aligned column">
                    <a href="#top" class="right floated"><i class="angle double up icon"></i>Back to Top</a>
                  </div>
                </div>
              </div>
              <br /><br />
              @endforeach
              <a href="/coordinator/payments/pay"><i class="angle double left icon"></i>Back</a>
        </div>
      </div>
    </div>

    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

      $('.ui.accordion')
  .accordion();
    </script>
  </body>
</html>
