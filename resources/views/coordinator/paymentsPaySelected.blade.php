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
          <h3 style="text-center"><strong>Confirm Payment</strong></h3><br />
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card" style="background-color: white; margin-top: 3%;">
            <div class="card-body">
              <h4 class="card-title text-center" style="font-family: sans-serif;">{{ $range }}</h4>
              @if ($unsigned !== 0)
              <h4 class="card-title text-center" style='font-family: sans-serif; color: orange;'>There are {{ $unsigned }} unsigned timecards</h4>
              <p class="card-text text-center">Cannot continue payment. Please make sure all timecards for the selected payment period have been signed and submitted by the work supervisors.</p>
              <div class="text-center">
                <div class="btn-group">
                  <a href="/coordinator/payments/pay" class="btn btn-primary">OK</a>
                  <a href="/coordinator/payments/pay/selected/unsigned<?php echo "?startDate=". $startDate . "&endDate=" . $endDate;?>" class="btn btn-outline-warning">Show Timecards</a>
                </div>
              </div>
            </div>
              @endif

            </div>
          </div>
        </div>
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
