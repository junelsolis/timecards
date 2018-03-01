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
  </head>
  <body>
    <div class="ui">
      @include('/coordinator/navbar')
      <div class="ui grid" style="margin: 1%;">
          <div class="ten wide mobile six wide tablet four wide computer column">
            <div class="ui segment">
              <h1 class="header">Create Timecards</h1>
              <form class="ui form" action="/coordinator/timecards/create" method="post">
              {{ csrf_field() }}
                <div class="field">
                  <label>Start Date</label>
                  <input type="date" name="startDate" required />
                </div>
                <div class="ui buttons">
                  <button class="ui button blue" type="submit">Create</button>
                  <div class="or"></div>
                  <a class="ui button grey" href="/coordinator">Cancel</a>
                </div>
              </form>
              @if(session('error'))
              <div class="ui yellow message">
                <i class="close icon"></i>
                <div class="header">
                  Error
                </div>
                <?php echo session('error');?>
              </div>
              @endif
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
