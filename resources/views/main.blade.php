<!doctype html>
<html lang="en">
  <head>
    <title>Timecards v2.0</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <link rel=stylesheet href="https://s3-us-west-2.amazonaws.com/colors-css/2.2.0/colors.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:200" rel="stylesheet">
    <style>
      .vertical-center {
        min-height: 100%;
        min-height: 100vh;

        display: flex;
        align-items: center;
      }
    </style>
  </head>
  <body style="font-family: 'Raleway', sans-serif;">
    <div class="vertical-center">
      <div class="container">
        <div class="text-center">
          <img src="/images/maa-logo.png"style="max-width: 180px;" />
        </div>
        <br />
        <h1 style="font-size: 5vmax;"class="text-center navy">TIMECARDS</h1>
        <form action="/login" method="post">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <br /><br />
                <div class='text-center'>
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="role" value="worker" checked>
                    Worker&nbsp;&nbsp;
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="role" value="supervisor">
                    Supervisor&nbsp;&nbsp;
                  </label>
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="role" value="coordinator">
                    Coordinator&nbsp;&nbsp;
                  </label>
                </div>

              <div class="form-group">
                <br />
                <label for="username">Username</label>
                <div class="input-group">
                  <input type="text" class="form-control" name='username' placeholder="Enter username" id="username" required/>
                  <span class="input-group-addon" id="username">@maxwellsda.org</span>
                </div>
                <br />
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name='password' placeholder="password" required />
                </div>
                @if (session('error'))
                <div class="alert alert-warning" role="alert">
                  {{ session('error') }}
                </div>
                @endif
                <br />
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">&nbsp;&nbsp;&nbsp;Log in&nbsp;&nbsp;&nbsp;</button><br /><br />
                  <a href="#" class="gray">Forgot Password</a>
                </div>
              </div>
              <div class="col-md-3"></div>
          </div>
        </form>
      </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
