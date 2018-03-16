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
          @if ($periods->count() > 0)
          <h1 class="ui dividing header" id="top">Attendance Reports</h1>
          <div class="ui three stackable cards">
            @foreach ($periods as $period)
            <div class="ui card">
              <div class="content">
                <div class="ui blue header">
                  {{ $period->dateRange }}
                </div>
                <div class='meta'>

                </div>
                <div class="description">
                  Tardies&nbsp;<strong>{{ $period->totalTardies }}</strong><br />
                  Absences&nbsp;<strong>{{ $period->totalAbsences }}</strong>
                </div>
              </div>
              <div class="extra content">
                <a class="ui mini yellow basic disabled button">Details</a>
                <a class="ui mini blue button"><i class="print icon"></i>Print Report</a>
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
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
