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
            <h1 class="header">Active Timecards</h1>
            <div class="text">
              List of all active timecards for this week, including their details.
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
                  {{ $signedTimecards }} / {{ $totalTimecards }}
                </div>
                <div class="label">
                  Signed
                </div>
              </div>
              <div class="ui statistic">
                <div class="value">
                  {{ $totalHours }}
                </div>
                <div class="labeL">
                  Total Hours
                </div>
              </div>
            </div>

          </div>
          <div class="ui divider">

          </div>
          <br />
          @if (isset($timecards))
          <div class="ui styled accordion">
            @foreach ($timecards as $item)
            <div class="title">
              <i class="dropdown icon"></i>
              {{ $item->firstname }} {{ $item->lastname }} | {{ $item->department }} | {{ $item->hours }}
            </div>
            <div class='content'>
              <div class="ui two column grid">
                <div class='column'>
                  Hours: {{ $item->hours }}<br />
                  Grade: <?php echo strtoupper($item->grade); ?>
                </div>
                <div class='column'>
                  Tardies: {{ $item->tardies }}<br />
                  Absences: {{ $item->absences }}
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
    </script>
  </body>
</html>
