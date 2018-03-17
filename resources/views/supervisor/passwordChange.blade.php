<!doctype html>
<html lang="en">
  <head>
    <title>Supervisor | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
  </head>
  <body>
    @include('/supervisor/navbar')
    <div class="pusher" style="margin: 2%;">
      <div class="ui grid">
        <div class="ten wide column">
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('msg') }}
            </div>
          </div>
          @endif
          @if (session('error'))
          <div class="ui yellow message">
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

        <h1 class="ui dividing header">Change Password</h1>
        <form class="ui form" action="/supervisor/password" method="post">
          {{ csrf_field() }}
          <div class="six wide field">
            <label>Current Password</label>
            <input type="password" name="password" required placeholder="Enter current password"/>
          </div>
          <div class="fields">
            <div class="six wide field">
              <label>New Password</label>
              <input type="password" name="newPassword" required placeholder="Enter new password" />
            </div>
            <div class="six wide field">
              <label>Confirm Password</label>
              <input type="password" name="confirmPassword" required placeholder="Confirm new password" />
            </div>
          </div>
          <div class="ui buttons">
            <button class="ui blue button" type="submit">Submit</button>
            <div class="or"></div>
            <a href="/supervisor" class="ui grey button">Cancel</a>
          </div>
        </form>
        </div>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

      $('.ui.accordion')
  .accordion();

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
