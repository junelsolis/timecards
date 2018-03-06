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
            <h1 class="header">Add Supervisor</h1>
            <div class="text">
              Add a supervisor by entering the relevant information below.
            </div>
            @if (session('msg'))
            <div class="ui yellow message">
              <i class="close icon"></i>
              <div class="header">
                {{ session('msg') }}
              </div>
            </div>
            @endif
            @if (session('error'))
            <div class="ui red message">
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
          <div class="ui segment">
            <form class="ui form" action="/coordinator/supervisor/add" method="post">
              {{ csrf_field() }}
              <h4 class="ui dividing header">Information</h4>
              <div class="ui six wide field">
                <div class="label">
                  Email address
                </div>
                <div class="ui right labeled input">
                  <input type="text" name="username" placeholder="Email address" required />
                  <div class="ui label">
                    <div class="text">
                      @maxwellsda.org
                    </div>
                  </div>
                </div>
              </div>
              <div class="ui two fields">
                <div class="ui field">
                  <div class="label">
                    Firstname
                  </div>
                  <input type="text" name="firstname" placeholder="Firstname" required />
                </div>
                <div class='ui field'>
                  <div class="label">
                    Lastname
                  </div>
                  <input type="text" name="lastname" placeholder="Lastname" required />
                </div>
              </div>
              <h4 class="ui dividing header">Departments</h4>

              <div class="row">
                <div class="ui two fields">
                  <div class="ui field">
                  @foreach($departments[0] as $item)
                    <div class="ui checkbox">
                      <input type="checkbox" name="departments[]" value="{{ $item->id }}" />
                      <label>{{ $item->name }}</label>
                    </div><br />
                  @endforeach
                  </div>
                  <div class="ui field">
                  @foreach($departments[1] as $item)
                    <div class="ui checkbox">
                      <input type="checkbox" name="departments[]" value="{{ $item->id }}" />
                      <label>{{ $item->name }}</label>
                    </div><br />
                  @endforeach
                  </div>
                </div>
              </div>
              <h4 class="ui dividing header"></h4>
              <div class="ui buttons">
                <button class="ui button blue">Save</button>
                <div class="or"></div>
                <a class="ui button grey" href="/coordinator">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

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
