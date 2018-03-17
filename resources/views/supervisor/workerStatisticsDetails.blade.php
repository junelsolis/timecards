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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
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

          <h1 class="ui dividing header">Worker Details</h1>
          <div class="ui grid">
            <div class="six wide column middle aligned">
              <h2 class="ui blue header">{{ $worker->fullname }}</h2>
            </div>
            <div class="three wide column middle aligned">
              @foreach ($worker->departmentNames as $item)
              <h5 class="ui grey header">{{ $item }}</h5>
              @endforeach
            </div>
            <div class="seven wide column middle aligned">
              <div class="two statistics">
                <div class="ui mini yellow statistic">
                  <div class="value">
                    {{ $worker->timecards->count() }}
                  </div>
                  <div class="label">
                    timecards
                  </div>
                </div>
                <div class="ui mini yellow statistic">
                  <div class="value">
                    216
                  </div>
                  <div class="label">
                    hours
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="ui divider">
          </div>

          <div class="ui fluid column">
            <canvas id="paymentHistory"></canvas>
          </div>
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
    <script>
      var payments = document.getElementById("paymentHistory");
      var paymentChart = new Chart(payments, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($paymentGraphData->weeks) ?>,
          datasets: [{
              label: 'Payments (KSh)',
              data: <?php echo json_encode($paymentGraphData->payments) ?>,
              borderWidth: 2
          }]
        },
        options: {
          legend: {
            position: 'bottom'
          }
        }

      });

    </script>
  </body>
</html>
