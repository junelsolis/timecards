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
      <div class="card" style="background-color: whitesmoke;">
        <div class="card-body"  style="text-align: center;">
          <h4><strong>Payment Periods</strong></h4><br />
          <a href="/coordinator/payment-periods/add" class="btn btn-outline-primary">+ Add Period +</a>
        </div>
      </div>
      <div class="row">
        @foreach ($periods as $item)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card" style="background-color: whitesmoke; margin-top: 3%;">
            <div class="card-body">
              <h5 class="card-title" style="font-family: sans-serif;"><strong>{{ $item->range }}</strong></h5>
              <p class="card-text" style="font-family: sans-serif; font-weight: 100;">
                Timecards <strong>{{ $item->associated }}</strong><br />
                Payment <strong>Ksh&nbsp;{{ $item->payment }}</strong>
              </p>
              <div class="btn-group">
                <a href="#" class="btn btn-primary">View</a>
                <a href="#" class="btn btn-outline-warning">Print Report</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>

    </div>
    @include('/coordinator/footer')



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
