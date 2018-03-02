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
          <h1 class="header">Add Payment Period</h1>
          <div class="text">
            Select starting and ending dates for the payment period. The starting date must be a Sunday and the ending date must be a Saturday. The end date cannot be before or the same as the start date.
          </div>
          <div class="ui divider">

          </div>
          <br />
          <form class="ui form left aligned" action="/coordinator/payment-periods/add" method="post">
            {{ csrf_field() }}
            @if (session('error'))
            <div class="ui yellow message">
              <i class="close icon"></i>
              <div class="header">
                Error
              </div>
              {{ session('error') }}
            </div>
            @endif
            <div class="two fields">
              <div class="field">
                <label>Start Date</label>
                <input type="date" placeholder="Select start date" name="startDate" />
              </div>
              <div class="field">
                <label>End Date</label>
                <input type="date" placeholder="Select end date" name="endDate" />
              </div>
            </div>
            <div class="ui buttons">
              <button class="ui primary button blue" type="submit">Create Period</button>
              <div class="or">

              </div>
              <a class="ui grey button" href="/coordinator">Cancel</a>
            </div>
          </form>
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
