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

      <div class="ui container grid">
        <div class="sixteen wide column">

          <h1 class="ui dividing header" id="top">Attendance Report</h1>
          <p class="ui text">
            Attendance records for the entire school year. Any workers with attendance any tardies or absences are included in this list.
          </p>
          <a href="/coordinator"><i class="angle double left icon"></i>Back to Dashboard</a>

          <br /><br />
          @if ($workers)
            @foreach($workers as $key => $group)
            <h1 class="ui blue header" id="{{ $key }}"> {{ $key }}</h1>
              <table class="ui striped celled table">
                <thead>
                  <tr>
                    <th></th>
                    <th>Tardies</th>
                    <th>Absences</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($group as $worker)
                  <tr>
                    <td>{{ $worker->fullname }}</td>
                    <td>
                      @if ($worker->tardyDates->count() >= 1)
                        <div class="ui list">
                        @foreach ($worker->tardyDates as $tardy)
                          <div class="item">
                            {{ date('d M Y', strtotime($tardy->date)) }}&nbsp;-&nbsp;<em>{{ $tardy->department }}</em>
                          </div>
                        @endforeach
                        </div>
                      @endif
                    </td>
                    <td>
                      @if ($worker->absentDates->count() >= 1)
                        <div class="ui list">
                        @foreach ($worker->absentDates as $absent)
                          <div class="item">
                            {{ date('d M Y', strtotime($absent->date)) }}&nbsp;-&nbsp;<em>{{ $absent->department }}</em>
                          </div>
                        @endforeach
                        </div>
                      @endif
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              <br /><br />
            @endforeach

          @endif
          <a href="/coordinator"><i class="angle double left icon"></i>Back to Dashboard</a>
        </div>
      </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });
    </script>
  </body>
</html>
