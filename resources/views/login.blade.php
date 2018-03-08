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
      <img src="images/maa-logo.png" class="image">
      <div class="content">
        Login to Timecards
      </div>
    </h2><br /><br />
    <!-- <div class="fluid ui basic buttons"> -->
      <a class="ui large button disabled" href="/login/worker">Worker</a>
      <a class="ui large basic blue button" href="/login/supervisor">Supervisor</a>
      <a class="ui large basic blue button" href="/login/coordinator">Coordinator</a>
    <!-- </div> -->
  </div>
</div>
</body>
</html>
