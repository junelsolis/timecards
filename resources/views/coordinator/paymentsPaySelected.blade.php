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
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              Success.
            </div>
            {{ session('msg') }}
          </div>
          @endif


          <h1 class="ui dividing header">Confirm Payment</h1>
          <p class="text">
            Pressing 'Confirm' will mark all timecards included in the period between {{ $period->dateRange }} 2018 as paid. You will then be able to generate and print a payment report for all workers.
          </p>
          <div class="ui internally celled grid">
            <div class="five wide column middle aligned center aligned">
              <div class="ui tiny statistic">
                <div class="value">
                  {{ $period->dateRange }}
                </div>
                <div class="label">
                  Date
                </div>
              </div>
            </div>
            <div class="three wide column middle aligned center aligned">
              <div class="ui small statistic">
                <div class="value">
                  {{ $period->year }}
                </div>
                <div class="label">
                  Year
                </div>
              </div>
            </div>
            <div class="eight wide column middle aligned center aligned">
              <div class="ui yellow small statistic">
                <div class="value">
                  Ksh&nbsp;{{ $period->totalPayment}}
                </div>
                <div class="label">
                  Total Payment
                </div>
              </div>
            </div>
          </div>
          <div class="ui divider">
            <br />
            <div class="ui buttons">
              <a href="/coordinator/payments/pay/selected?id={{ $period->id }}" class="ui blue button">Confirm</a>
              <div class="or">

              </div>
              <a href="/coordinator/payments/pay" class="ui grey button">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ui">
      <div class="ui grid" style="margin: 1%;">
          <div class="twelve wide column">

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
