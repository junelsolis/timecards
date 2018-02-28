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
      <h3><strong>Edit Worker</strong></h3>
      Modify worker details below
      <hr />
      <form action="/coordinator/worker/edit/item" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $worker->id }}" />
        <div class="row">
          <div class="col-sm-8 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="username">E-mail address</label>
              <div class="input-group">
                <input type="text" class="form-control" id="username" name="username" value="{{ $worker->short }}"
                  required />
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
              <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $worker->firstname }}" required />
            </div>
          </div>
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $worker->lastname }}" required />
            </div>
          </div>
        </div>
      <hr />
      <h5><strong>Select Departments</strong></h5>
      Check all the departments this worker belongs to:<br /><br />
      <div class="row">
        @foreach ($depts as $chunk)
          <div class="col-sm-6">
            @foreach ($chunk as $item)
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="departments[]" value="{{ $item->id }}"
                  <?php
                    foreach ($worker->departments as $dept) {
                      if ($dept === $item->name) {
                        echo "checked";
                      }
                    }
                  ?>
                />{{ $item->name }}
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
        <button type="submit" class="btn btn-primary">Save Settings</button>
        <a href="/coordinator/worker/edit" class="btn btn-secondary">Back</a>
        <a href="/coordinator" class="btn btn-outline-secondary">Cancel</a>
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
