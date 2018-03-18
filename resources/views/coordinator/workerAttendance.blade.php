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

          <h1 class="ui dividing header">Attendance</h1>
          <br /><br />
          @if ($workers)
            @foreach($workers as $key => $group)
            <h1 class="ui blue header" id="{{ $key }}"> {{ $key }}</h1>
              <table class="ui very basic collapsing celled table">
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
                    <td><strong>{{ $worker->fullname }}</strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });
    </script>
  </body>
</html>
