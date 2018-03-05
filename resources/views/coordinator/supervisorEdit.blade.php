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
    <div class="pusher" style="margin: 2%">
      <div class="ui grid">
        <div class="twelve wide column">
          <h1 class="header">Supervisors</h1>
          <div class="text">
            This page shows all supervisors. Click on the card links to modify or delete a worker.
          </div>
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('msg') }}
            </div>
          </div>
          @endif
          <div class="ui segment center aligned">
            <div class="ui two statistics">
              <div class="ui statistic">
                <div class="value">
                  {{ $totalSupervisors}}
                </div>
                <div class="label">
                  Supervisors
                </div>
              </div>
              <div class="ui statistic">
                <div class="value">
                  {{ $totalDepartments }}
                </div>
                <div class="label">
                  Departments
                </div>
              </div>
            </div>
          </div>
          <div class="ui three stackable cards">
            @foreach ($supervisors as $item)
            <div class="card">
              <div class="content">
                <div class="header">
                  {{ $item->fullname }}
                </div>
                <div class="meta">
                  <div class="text">
                    {{ $item->departmentCount }}&nbsp;Departments
                  </div>
                </div>
                <div class="description">
                  <i class="address card icon"></i>
                  {{ $item->countUnsignedTimecards }}&nbsp;Unsigned Timecards<br />
                </div>
              </div>
              <div class="extra content">
                <div class="ui buttons">
                  <a class="compact mini ui basic button green" href="/coordinator/supervisor/edit/item?id={{ $item->id }}">View</a>
                  <a class="compact mini ui basic button yellow disabled">Delete</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
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
