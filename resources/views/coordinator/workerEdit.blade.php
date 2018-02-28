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
        @foreach ($workers as $item)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card" style="margin-top: 5%; background-color: whitesmoke;">
            <div class="card-body">
              <h5 class="card-title"><strong>{{ $item->fullname }}</strong></h5>
              <p class="card-text" style="min-height: 50px;">
                @foreach ($item->departments as $department)
                {{ $department }}<br />
                @endforeach
              </p>
              <div class="btn-group" role="group">
                <a href="/coordinator/worker/edit/item?id=<?php echo $item->id; ?>" class="btn btn-primary">Edit Worker</a>
                <a href="#" class="btn btn-outline-warning">Delete</a>
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
