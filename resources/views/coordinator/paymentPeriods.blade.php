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
  @include('/coordinator/navbar')
  <body>
    <div class="ui">
      @include('/coordinator/navbar')
      <div class="ui grid" style="margin: 1%;">
          <div class="twelve wide column">
            <div class="ui segment center aligned">
              <h1 class="header">Payment Periods</h1>
              <a class="ui primary button" href="/coordinator/payment-periods/add">Add Period</a>
            </div>
            <div class="ui segment">
              @if (session('msg'))
              <div class="ui yellow message">
                <i class="close icon"></i>
                <div class="header">
                  Success.
                </div>
                {{ session('msg') }}
              </div>
              @endif
              @if (isset($periods))
              <div class="ui stackable cards">
                @foreach ($periods as $item)
                <div class="ui raised card">
                  <div class="content">
                    <div class="header">
                      {{ $item->range }}
                    </div>
                    <div class="description">
                      Timecards <strong>{{ $item->associated }}</strong><br />
                      Payment <strong>KSh {{ $item->payment }}</strong><br /><br />
                    </div>
                    <div class="extra content">
                      <div class="ui two buttons">
                        <div class="ui basic blue button">View</div>
                        <div class="ui basic grey button">Print Report</div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              @endif
            </div>
          </div>
      </div>
    </div>
    <script>$('.ui.sidebar')
      .sidebar('show');
    </script>
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
