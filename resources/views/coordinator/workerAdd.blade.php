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
              <h1 class="header">Add Worker</h1>
              <div class="text">
                Add a worker by entering the relevant information below.
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
            <div class="ui segment">

              <form class="ui form" action="/coordinator/worker/add" method="post">
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
                <br />
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
