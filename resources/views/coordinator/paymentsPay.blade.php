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
          <h1 class="ui header">Payment Periods</h1>
          <div class="ui divider">
          </div>
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              Success.
            </div>
            {{ session('msg') }}
          </div>
          @endif
          @if (isset($unpaid))
          <h3 class="ui yellow header">Pending Payment</h3>
          <div class="ui two stackable cards">
            @foreach ($unpaid as $item)
            <div class="ui raised card">
              <div class="content">
                <div class="ui blue header">
                  {{ $item->dateRange }}
                </div>
                <div class='meta'>
                  <strong>Payment</strong> KSh {{ $item->totalPayment }}
                </div>
                <div class="description">
                  <div class="ui two column grid">
                    <div class="column">
                      <strong>{{ $item->totalTimecards}}</strong>&nbsp;Timecards<br />
                      <strong>{{ $item->unsignedTimecards}}</strong>&nbsp;Unsigned<br />
                      <strong>{{ $item->submittedTimecards}}</strong>&nbsp;Submitted
                    </div>
                    <div class="column middle aligned">
                      <div class="ui yellow statistic">
                        <div class="value">
                          {{ $item->remainingTimecards }}
                        </div>
                        <div class="label">
                          Remaining
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="extra content">
                <a class="ui tiny blue button <?php if ($item->complete == false) { echo "disabled"; }?>"
                  href="/coordinator/payments/pay/selected?id={{ $item->id }}">
                  Pay
                </a>
                <a class="ui tiny basic yellow button" href="/coordinator/payments/pay/selected/details?id={{ $item->id }}">Details</a>
              </div>
            </div>
            @endforeach
          </div>
          @endif
          <br />
          <div class="ui divider"></div>

          <h3 class="ui header">Previous Periods</h3>
          @if ($paid)
            <div class="ui three stackable cards">
            @foreach ($paid as $item)
              <div class="ui card">
                <div class="content">
                  <div class="ui grey header">
                    {{ $item->dateRange }}
                  </div>
                  <div class="meta">
                    <strong>Payment</strong> KSh {{ $item->totalPayment }}
                  </div>
                  <div class="description">
                    <strong>{{ $item->totalTimecards }}</strong>&nbsp;Timecards
                  </div>
                </div>
                <div class="extra content">
                  <a class="ui tiny blue basic button" href="/coordinator/payment-periods/report?id={{ $item->id }}"><i class="print icon"></i>Report</a>
                </div>
              </div>
            @endforeach
          </div>
          <div class="ui divider">

          </div>
          @endif
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
