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
          <h1 class="header">Payscale</h1>
          <div class="text">
            Payscales determine the amount per hour paid for each letter grade given.
          </div>
          <div class="ui divider">

          </div>

          <div class="ui segment">
            <div class="ui three statistics">
              <div class="statistic">
                <div class="value">
                  {{ $items['Unsatisfactory']->pay }}
                </div>
                <div class="label">
                  Unsatisfactory
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  {{ $items['Satisfactory']->pay }}
                </div>
                <div class="label">
                  Satisfactory
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  {{ $items['Outstanding']->pay }}
                </div>
                <div class="label">
                  Outstanding
                </div>
              </div>
            </div>
          </div>
          <div class="ui segment">
            @if (session('msg'))
            <div class="ui yellow message">
              <i class="close icon"></i>
              <div class="header">
                {{ session('msg') }}
              </div>
            </div>
            @endif
            <form class="ui form" action="/coordinator/payments/payscale" method="post">
              {{ csrf_field() }}
              <h4 class="ui dividing header">Set Payscale</h4>
              <div class="three wide field">
                <div class="label">
                  Unsatisfactory
                </div>
                <div class="ui labeled input">
                  <div class="ui label">
                    Ksh
                  </div>
                  <input type="number" name="u" value="{{ $items['Unsatisfactory']->pay }}" />
                </div>
              </div>
              <div class="three wide field">
                <div class="label">
                  Satisfactory
                </div>
                <div class="ui labeled input">
                  <div class="ui label">
                    Ksh
                  </div>
                  <input type="number" name="s" value="{{ $items['Satisfactory']->pay }}" />
                </div>
              </div>
              <div class="three wide field">
                <div class="label">
                  Outstanding
                </div>
                <div class="ui labeled input">
                  <div class="ui label">
                    Ksh
                  </div>
                  <input type="number" name="o" value="{{ $items['Outstanding']->pay }}" />
                </div>
              </div>
              <br /><br />
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
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });
    </script>
  </body>
</html>
