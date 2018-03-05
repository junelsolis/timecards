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
            <h1 class="header">Submitted Timecards</h1>
            <div class="text">
              These are all the timecards that have been signed by supervisors that are pending payment. In this page, you may also return a timecard back to the supervisor for revision.
            </div>
            @if (session('msg'))
            <div class="ui yellow message">
              <i class="close icon"></i>
              <div class="header">
                {{ session('msg') }}
              </div>
            </div>
            @endif
          <div class="ui divider"></div>
          <div class="ui segment center aligned">
            <div class="ui three statistics">
              <div class="ui statistic">
                <div class="value">
                  {{ $totalTimecards }}
                </div>
                <div class='label'>
                  Timecards
                </div>
              </div>
              <div class="ui statistic">
                <div class="value">
                  {{ $totalHours }}
                </div>
                <div class="label">
                  Hours
                </div>
              </div>
              <div class="ui statistic">
                <div class="value">
                  {{ $totalPay }}
                </div>
                <div class="label">
                  Payment
                </div>
              </div>
            </div>
          </div>
          <br />
          @if (isset($timecards))
          <div class="ui styled accordion">
            @foreach ($timecards as $item)
            <div class="title">
              <i class="dropdown icon"></i>
              {{ $item->firstname }}&nbsp;{{ $item->lastname }} | {{ $item->department}}
            </div>
            <div class="content">
              <strong>{{ $item->dateRange }}</strong><br /><br />
              <div class="ui three column grid">
                <div class="column">
                  Hours: {{ $item->hours}}<br />
                  Grade: {{ $item->grade }}
                </div>
                <div class="column">
                  Pay: Ksh {{ $item->pay }}
                </div>
                <div class="column">
                  <a href="/coordinator/timecards/submitted/return?id={{ $item->id }}" class="ui mini yellow button">Return to Supervisor</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif
      </div>
    </div>
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
