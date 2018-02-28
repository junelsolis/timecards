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
      <h3><strong>Add Supervisor</strong></h3>
      Enter supervisor details below
      <hr />
      <form action="/coordinator/supervisor/add" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-sm-8 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="username">E-mail address</label>
              <div class="input-group">
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter email" required />
                <span class="input-group-addon">@maxwellsda.org</span>
              </div>
              <small class="form-text text-muted">Enter a Maxwell email address.</small>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label for="firstname">First Name</label>
              <input id="firstname" type="text" class="form-control" name="firstname" placeholder="User firstname" required />
            </div>
          </div>
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input id="lastname" type="text" class="form-control" name="lastname" placeholder="User lastname" required />
            </div>
          </div>
        </div>
      <hr />
      <h5><strong>Select Departments</strong></h5>
      Check all the departments this supervisor belongs to:<br /><br />
      <div class="row">
        @foreach ($departments as $department)
          <div class="col-sm-6">
            @foreach ($department as $item)
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="departments[]" value="{{ $item->id }}" />{{ $item->name }}
              </label>
            </div>
            @endforeach
          </div>
        @endforeach
      </div>
      <hr />
      @if (session('error'))
      <div class="alert alert-warning" role="alert">
        {{ session('error') }}
      </div>
      <br />
      @endif
      @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-warning" role="alert">
          {{ $error }}
        </div>
        @endforeach
      @endif
      <div class="btn-group">
        <button type="submit" class="btn btn-primary">Add Worker</button>
        <a href="/coordinator" class="btn btn-secondary">Cancel</a>
      </div>
      <br /><br />
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
