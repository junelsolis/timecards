<!doctype html>
<html lang="en">
  <head>
    <title>Coordinator | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link href="/css/pages.css" rel='stylesheet' />
    <link href="https://fonts.googleapis.com/css?family=Raleway:200" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/list.min.js') }}"></script>
  </head>
  @include('/coordinator/navbar')
  <body>
    <div class="container"  id='timecards'>
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
        <div class='col-sm-12'>
          <h3><strong>Active Timecards</strong></h3><br />
        </div>
        <div class="col-sm-12" id="timecards" >
          <table class="table table-sm"style="font-family: sans-serif;">
            <thead>
              <tr>
                <th scope="col" class="sort" data-sort="firstname">First Name</th>
                <th scope="col" class="sort" data-sort="lastname">Last Name</th>
                <th scope="col" class="sort" data-sort="department">Department</th>
                <th scope="col" class="sort" data-sort="hours">Total Hours</th>
              </tr>
            </thead>
            <tbody class="list">
              @foreach ($timecards as $item)
              <tr>
                <td class="firstname">{{ $item->firstname }}</td>
                <td class="lastname">{{ $item->lastname }}</td>
                <td class="department">{{ $item->department }}</td>
                <td class="hours">{{ $item->hours }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <script type="text/javascript">
        var options = {
          valueNames: [ 'firstname', 'lastname', 'department', 'hours' ]
        };

        var userList = new List('timecards', options);

      </script>
    </div>
    @include('/coordinator/footer')




    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
