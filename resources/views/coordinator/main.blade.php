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
    <div class="ui">
        @include('/coordinator/navbar')
      <div class="grid" style="margin: 1%;">
        <div class="sixteen wide column fluid">
          <h1>header</h1>
          <p>
            content goes here1
          </p>
        </div>
      </div>

    <script>$('.ui.sidebar')
  .sidebar('show');
    </script>
  </body>
</html>
