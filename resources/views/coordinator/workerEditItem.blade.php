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
              <h1 class="header">Modify Worker</h1>
              <div class="text">
                Worker information page. Use the form to edit worker information, reset passwords, or change departments.
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
            <div class="ui two cards">
              <div class="ui card">
                <div class="content">

                </div>
                <div class="meta">

                </div>
                <div class="description">

                </div>
                <div class="extra content">
                  
                </div>
              </div>
            </div>

            <div class="ui segment">

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
