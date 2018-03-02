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
          <h1 class="header">Departments</h1>
          <div class="text">
            Each supervisor and worker belongs to at least one department.<br />
            Use the cards below to view and edit information for each department.
          </div><br />
          <a class="ui primary button" href="/coordinator/departments/add">Add Department</a>
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('msg') }}
            </div>
          </div>
          @endif
          <br /><br />
          <div class="ui divider"></div>


          @if (isset($departments))
          <div class="ui four stackable cards">
            @foreach ($departments as $item)
            <div class="ui card">
              <div class="center aligned image">
                <i class="sitemap icon"></i>
              </div>
              <div class="content">
                <div class="header">
                  {{ $item->name }}
                </div>
                <div class="meta">
                  {{ $item->totalTimecards }} Timecards<br />
                  {{ $item->activeTimecards }} Active
                </div>
              </div>
              <div class="extra content">
                <i class="user outline icon"></i>{{ $item->supervisorCount }}<br />
                <i class="user icon"></i>{{ $item->workerCount }}
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
    </script>
  </body>
</html>
