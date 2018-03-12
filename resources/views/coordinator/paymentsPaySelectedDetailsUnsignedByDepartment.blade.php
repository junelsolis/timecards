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

          <h1 class="ui dividing header">{{ $period->dateRange }}</h1>
          <h2 class="ui blue header">Unsigned Timecards by Department</h2>
        </div>
      </div>
    </div>
    <div class="ui">
      <div class="ui grid" style="margin: 1%;">
          <div class="twelve wide column">

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
