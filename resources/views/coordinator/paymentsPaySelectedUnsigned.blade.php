<!doctype html>
<html lang="en">
  <head>
    <title>Coordinator | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{ asset('js/list.min.js') }}"></script>
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
          <h3><strong>Unsigned Timecards</strong></h3>
          <h3 class="text-warning" style="font-family: sans-serif;">{{ $range }}</h3><br />
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12" id="timecards" >
          <table class="table table-sm"style="font-family: sans-serif;">
            <thead>
              <tr>
                <th scope="col" class="sort" data-sort="firstname">First Name</th>
                <th scope="col" class="sort" data-sort="lastname">Last Name</th>
                <th scope="col" class="sort" data-sort="department">Department</th>
                <th scope="col" class="sort" data-sort="hours">Total Hours</th>
                <th scope="col" class="sort" data-sort="grade">Grade</th>
                <th scope="col" class="sort" data-sort="pay">Pay</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody class="list">
              @foreach ($timecards as $item)
              <tr>
                <td class="firstname">{{ $item->firstname }}</td>
                <td class="lastname">{{ $item->lastname }}</td>
                <td class="department">{{ $item->department }}</td>
                <td class="hours">{{ $item->hours }}</td>
                <td class="grade">{{ strtoupper($item->grade) }}</td>
                <td class="pay">{{ $item->pay }}</td>
                <td>
                  <a href="/coordinator/payments/pay/selected/unsigned/sign<?php echo '?id=' . $item->id .
                    '&startDate=' . $startDate . '&endDate=' . $endDate; 
                    ?>"
                    class="btn btn-outline-warning">Mark Signed</a>
                </td>
                <td><a href="#" class="btn btn-outline-primary">Remind Supervisor</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      var options = {
        valueNames: [ 'firstname', 'lastname', 'department', 'hours' ]
      };

      var userList = new List('timecards', options);

    </script>
    @include('/coordinator/footer')




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
  </body>
</html>
