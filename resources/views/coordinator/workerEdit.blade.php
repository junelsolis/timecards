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
  @include('/coordinator/navbar')
  <body>
    <div class="ui">
      @include('/coordinator/navbar')
      <div class="ui grid" style="margin: 1%;">
          <div class="thirteen wide column">
            <div class="ui segment center aligned">
              <h1 class="header">Workers</h1>
              <div class="text">
                This page shows all workers. Click on the card links to modify or delete a worker.
              </div>
              @if (session('msg'))
              <div class="ui yellow message">
                <i class="close icon"></i>
                <div class="header">
                  {{ session('msg') }}
                </div>
              </div>
              @endif
            </div>
            <div class="ui segment center aligned">
              <div class="ui three statistics">
                <div class="ui statistic">
                  <div class="value">
                    {{ $totalWorkers}}
                  </div>
                  <div class="label">
                    Workers
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
                <div class="ui statistic">
                  <div class="value">
                    {{ $totalTimecards }}
                  </div>
                  <div class="label">
                    Timecards
                  </div>
                </div>
              </div>
            </div>
            <div class="ui segment">
              <div class="ui stackable cards">
                @foreach ($workers as $item)
                <div class="card">
                  <div class="content">
                    <div class="header">
                      {{ $item->fullname }}
                    </div>
                    <div class="meta">
                      <div class="text">
                        @foreach ($item->departments as $i)
                        {{ $i }}<br />
                        @endforeach
                      </div>
                    </div>
                    <div class="description">
                      <i class="address card icon"></i>
                      {{ $item->totalTimecards }}&nbsp;Timecards<br />
                      <i class="address card outline icon"></i>
                      {{ $item->activeTimecards }}&nbsp;Active
                    </div>
                  </div>
                  <div class="extra content">
                    <div class="ui buttons">
                      <a class="compact ui button blue">View</a>
                      <a class="compact ui basic button green" href="/coordinator/worker/edit/item?id={{ $item->id }}">Modify</a>
                      <a class="compact ui basic button yellow">Delete</a>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
      </div>
    </div>
    <script>$('.ui.sidebar')
      .sidebar('show');
    </script>
    <script>$('.message .close')
      .on('click', function() {
        $(this)
          .closest('.message')
          .transition('fade')
        ;
      });
    </script>
  </body>
</html>
