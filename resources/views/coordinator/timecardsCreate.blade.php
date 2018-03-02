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
    @include('/coordinator/navbar')
    <div class="pusher" style="margin: 2%;">
      <div class="ui grid">
        <div class="ten wide column">
          <h1 class="header">Create Timecards</h1>
          <div class="text">
            Use this screen to create new timecards for each week. Starting dates must be Sundays. The end date will automatically be filled and timecards created for each worker based on the departments they are assigned to.
          </div>

          <div class="ui divider">
          </div>
          <br />

          <form class="ui form" action="/coordinator/timecards/create" method="post">
          {{ csrf_field() }}
            <div class="four wide field">
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
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });
    </script>
  </body>
</html>
