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

            <h1 class="header">Header</h1>
            <div class="text">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor, tellus eget imperdiet sodales, eros nisi blandit leo, condimentum commodo neque ligula vitae metus. Vivamus et eros sit amet erat faucibus dapibus ut sed nulla. Quisque erat mauris, consequat ac velit nec, aliquam dictum metus. Nulla facilisi. Sed ullamcorper odio a egestas viverra. Maecenas imperdiet dapibus augue, finibus bibendum augue. Vivamus molestie semper imperdiet. Nunc ac nisi sed massa auctor lacinia.
            </div>
            <h2 class="header">subheader</h2>
            <div class="text">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor, tellus eget imperdiet sodales, eros nisi blandit leo, condimentum commodo neque ligula vitae metus. Vivamus et eros sit amet erat faucibus dapibus ut sed nulla. Quisque erat mauris, consequat ac velit nec, aliquam dictum metus. Nulla facilisi. Sed ullamcorper odio a egestas viverra. Maecenas imperdiet dapibus augue, finibus bibendum augue. Vivamus molestie semper imperdiet. Nunc ac nisi sed massa auctor lacinia.
            </div>
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
