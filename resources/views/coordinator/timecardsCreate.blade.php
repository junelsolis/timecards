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
      @if (session('error'))
      <div class="alert alert-warning" role="alert">
        {{ session('error') }}
      </div>
      @endif
      @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-warning" role="alert">
          {{ $error }}
        </div>
        @endforeach
      @endif
      <h3><strong>Create Timecards</strong></h3>
      Enter details below
      <hr />
      <form action="/coordinator/timecards/create" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label for="startDate">Start Date</label>
              <input type="date" class="form-control" id="startDate" name="startDate" required style="font-family: 'Helvetica', sans-serif;">
              <small id="startDate" class="form-text text-muted">Select a Sunday</small>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary">Create</button>
              <a href="/coordinator/payment-periods" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </div>
        </div>

      </form>

    </div>
    @include('/coordinator/footer')



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
