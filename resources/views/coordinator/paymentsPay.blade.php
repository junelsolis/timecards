<!doctype html>
<html lang="en">
  <head>
    <title>Coordinator | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link href="/css/pages.css" rel='stylesheet' />
    <link href="https://fonts.googleapis.com/css?family=Raleway:200" rel="stylesheet">
  </head>
  @include('/coordinator/navbar')
  <body>
    <div class="container">
      <br />
      @if (session('error'))
      <div class="alert alert-warning" role="alert">
        {{ session('error') }}
      </div>
      @endif
      @if (session('msg'))
      <div class="alert alert-warning" role="alert">
        {{ session('msg') }}
      </div>
      @endif
      <div class="row">
        <div class="col-sm-12 text-center">
          <h3 style="text-center"><strong>Select Payment Period</strong></h3><br />
        </div>
      </div>
      <div class="row">
        @foreach ($periods as $item)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card" style="background-color: white; margin-top: 3%;">
            <div class="card-body">
              <h5 class="card-title" style="font-family: sans-serif;"><strong>{{ $item->range }}</strong></h5>
              <p class="card-text" style="font-family: sans-serif; font-weight: 100;">
                Timecards <strong>{{ $item->associated }}</strong><br />
                Payment <strong>Ksh&nbsp;{{ $item->payment }}</strong>
              </p>
              @if ($item->paid === true)
              <div class="btn-group">
                <a href="#" class="btn btn-primary">View</a>
                <a href="#" class="btn btn-outline-warning">Print Report</a>
              </div>
              @endif
              @if ($item->paid === false)
              <a href="/coordinator/payments/pay/selected?<?php echo 'startDate=' . $item->startDate . '&endDate=' . $item->endDate; ?>" class="btn btn-outline-warning">Pay Timecards</a>
              @endif

            </div>
          </div>
        </div>
        @endforeach

      </div>

    </div>
    @include('/coordinator/footer')




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
  </body>
</html>
