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
            <h1 class="header">Modify Worker</h1>
            <div class="text">
              Worker information page. Use the form to edit supervisor information, reset passwords, or change departments.
            </div>
            @if (session('msg'))
            <div class="ui yellow message">
              <i class="close icon"></i>
              <div class="header">
                {{ session('msg') }}
              </div>
            </div>
            @endif
          <div class="ui divider">
          </div>
          <div class="ui two column stackable grid">
            <div class="ui six wide column">
              <div class="ui fluid card">
                <div class="image">
                  <img src="/images/user.png" />
                </div>
                <div class="content">
                  <div class="header">
                    {{ $worker->fullname }}
                  </div>
                  <div class="meta">
                    @foreach ($worker->departmentNames as $item)
                    {{ $item }}<br />
                    @endforeach
                  </div>
                </div>
                <div class="extra content">
                  <i class="clipboard outline icon"></i>
                  {{ $worker->totalTimecards}} Timecards<br />
                  {{ $worker->totalTardies }} Tardies<br />
                  {{ $worker->totalAbsences }} Absences
                </div>
              </div>
            </div>
            <div class="ui column">
              <div class="ui segment">
                <form class="ui fluid form" action="/coordinator/worker/edit/item" method="post">
                {{ csrf_field() }}
                <input type='hidden' name="id" value="{{ $worker->id }}" />
                  <h4 class="ui dividing header">Select Departments</h4>
                  <div class="ui two fields">
                    <div class="ui field">
                      @foreach ($depts[0] as $item)
                      <div class="ui checkbox">
                        <input type="checkbox" name="departments[]" value="{{ $item->id }}"
                          <?php
                            if (in_array($item->id, $worker->deptIds)) {
                              echo "checked";
                            }
                          ?>
                        />
                        <label>{{ $item->name }}</label>
                      </div><br />
                      @endforeach
                    </div>
                    <div class="ui field">
                      @foreach ($depts[1] as $item)
                      <div class="ui checkbox">
                        <input type="checkbox" name="departments[]" value="{{ $item->id }}"
                          <?php
                            if (in_array($item->id, $worker->deptIds)) {
                              echo "checked";
                            }
                          ?>
                        />
                        <label>{{ $item->name }}</label>
                      </div><br />
                      @endforeach
                    </div>
                  </div>
                  <br />
                  <div class="ui buttons">
                    <button class="ui button blue" type="submit">Save</button>
                    <div class="or">

                    </div>
                    <a href="/coordinator/worker/edit" class="ui grey button">Cancel</a>
                  </div>
                </form>
              </div>

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
