<!doctype html>
<html lang="en">
  <head>
    <title>Timecards 2.0</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
    <style type="text/css">
      /* body {
        background-color: #DADADA;
      } */
      body > .grid {
        height: 100%;
      }
      .image {
        margin-top: -100px;
      }
      .column {
        max-width: 450px;
      }
  </style>
  </head>
<body>
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui blue image header">
      <img src="{{ asset('/images/maa-logo.png') }}" class="image">
      <div class="content">
        Coordinator Login
      </div>
    </h2><br /><br />
    <form class="ui large form" action="/login/coordinator" method="post">
      {{ csrf_field() }}
        <div class="field">
          <div class="ui right labeled input">
            <input type="text" name="email" placeholder="Email address" required>
            <div class="ui label">
              <div class="text">@maxwellsda.org</div>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password" autocomplete="off" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;" required>
          </div>
        </div>
        <button class="fluid ui primary button" type="submit">
          Login
        </button>
      @if(session('error'))
      <div class="ui yellow message">
        <i class="close icon"></i>
        <div class="header">
          Invalid credentials
        </div>
        The email address or password you entered is incorrect.
      </div>
      @endif

    </form>

    <div class="ui message">
      Forgot your password? <a href="#">Reset</a>
    </div>
    <a href="/"><i class="angle double left icon"></i>Back</a>
  </div>
</div>
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
