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
    <div class="container-fluid">
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
        <div class="col-sm-12">
          <div class="card" style="background-color: lemonchiffon;">
            <div class="card-body text-center">
              <h4><strong>Next Payment</strong></h4><br />
              <h4 style="line-height: 0px; font-family: sans-serif;">{{ $countdown }} s</h4>

            </div>
          </div>
        </div>
        <div class="col-sm-4 col-md-2" style="padding-top: 1%;">
          <div class="card" style="background-color: whitesmoke;">
            <div class='card-body text-center'>
              <p>Active</p>
              <h2 style="font-family: sans-serif; color: royalblue;"><strong>{{ $countActiveTimecards  }}</strong></h2>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-md-2" style="padding-top: 1%;">
          <div class="card" style="background-color: whitesmoke;">
            <div class='card-body text-center'>
              <p>Unsigned</p>
              <h2 style="font-family: sans-serif; color: orange;"><strong>{{ $countUnsignedTimecards }}</strong></h2>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-md-2" style="padding-top: 1%;">
          <div class="card" style="background-color: whitesmoke;">
            <div class='card-body text-center'>
              <p>Signed</p>
              <h2 style="font-family: sans-serif; color: green;"><strong>{{ $countSubmittedTimecards }}</strong></h2>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6" style="padding-top: 1%;">
          <div class="card" style="background-color: whitesmoke;">
            <div class='card-body text-center'>
              <p>Active Workers</p>
              <h2 style="font-family: sans-serif;"><strong>{{ $countWorkers }}</strong></h2>
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
