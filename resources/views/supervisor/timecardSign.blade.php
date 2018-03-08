<!doctype html>
<html lang="en">
  <head>
    <title>Supervisor | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
  </head>
  <body>
    @include('/supervisor/navbar')
    <div class="pusher" style="margin: 2%;">
      <div class="ui grid">
        <div class="twelve wide column">
          @if (session('msg'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('msg') }}
            </div>
          </div>
          @endif
          @if (session('error'))
          <div class="ui yellow message">
            <i class="close icon"></i>
            <div class="header">
              {{ session('error') }}
            </div>
          </div>
          @endif
          @if (!empty($errors->all()))
          <div class="ui red message">
            <i class="close icon"></i>
            <div class="header">
              @foreach ($errors->all() as $message)
              {{ $message }}
              @endforeach
            </div>
          </div>
          @endif
          <h1 class="ui dividing header">
            Sign Timecard
          </h1>
          <div class="ui segment">
            <div class="ui internally celled grid">
              <div class="four wide column center aligned middle aligned">
                <h3 class="ui blue header">{{ $timecard->department }}</h3>
                <p class="text">
                  <strong>{{ $timecard->dateRange }}</strong>
                </p>
              </div>
              <div class="eight wide column center aligned middle aligned">
                <h2 class="ui header">{{ $timecard->fullname }}</h2>
              </div>
              <div class="four wide column center aligned middle aligned">
                <div class="ui fluid tiny blue statistic">
                  <div class="value">
                    {{ $timecard->hours }}
                  </div>
                  <div class="label">
                    Hours
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="ui segment">
            <div class="ui five tiny statistics">
              <div class="blue statistic">
                <div class="value">
                  {{ strtoupper($timecard->grade) }}
                </div>
                <div class="label">
                  Grade
                </div>
              </div>
              <div class="blue statistic">
                <div class="value">
                  {{ $timecard->contract }}
                </div>
                <div class="label">
                  Contract
                </div>
              </div>
              <div class="yellow statistic">
                <div class='value'>
                  {{ $timecard->tardies }}
                </div>
                <div class='label'>
                  Tardies
                </div>
              </div>
              <div class="orange statistic">
                <div class="value">
                  {{ $timecard->absences }}

                </div>
                <div class='label'>
                  Absences
                </div>
              </div>
              <div class="green statistic">
                <div class='value'>
                  {{ $timecard->pay }}
                </div>
                <div class="label">
                  Pay Estimate
                </div>
              </div>

            </div>
            <div class="ui dividing header">
            </div>
            <form class="ui form" action="/supervisor/timecards/sign" method="post">
              {{ csrf_field() }}
              <input type='hidden' name='id' value="{{ $timecard->id }}" />

              <div class="ui buttons">
                <button class="ui primary blue button" type="submit">Sign</button>
                <div class="or">

                </div>
                <a class="ui yellow button" href="/supervisor/timecards/edit?id={{ $timecard->id }}">Edit</a>
                <a class="ui grey button" href="/supervisor">Cancel</a>
              </div>

            </form>
          </div>
          <div class="ui segment">
            <table class="ui celled padded table">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Time In 1</th>
                  <th>Time Out 1</th>
                  <th>Time In 2</th>
                  <th>Time Out 2</th>
                  <th>Tardy</th>
                  <th>Absent</th>
                </tr>
                <tbody>
                  <tr>
                    <td><h3 class="ui center aligned header">SUN</h3></td>
                    <td class="single line">{{ $timecard->sunTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->sunTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->sunTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->sunTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->sunTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->sunAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">MON</h3></td>
                    <td class="single line">{{ $timecard->monTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->monTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->monTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->monTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->monTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->monAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">TUE</h3></td>
                    <td class="single line">{{ $timecard->tueTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->tueTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->tueTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->tueTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->tueTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->tueAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">WED</h3></td>
                    <td class="single line">{{ $timecard->wedTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->wedTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->wedTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->wedTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->wedTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->wedAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">THU</h3></td>
                    <td class="single line">{{ $timecard->thuTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->thuTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->thuTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->thuTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->thuTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->thuAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">FRI</h3></td>
                    <td class="single line">{{ $timecard->friTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->friTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->friTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->friTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->friTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->friAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <td><h3 class="ui center aligned header">SAT</h3></td>
                    <td class="single line">{{ $timecard->satTimeIn1 }}</td>
                    <td class="single line">{{ $timecard->satTimeOut1 }}</td>
                    <td class="single line">{{ $timecard->satTimeIn2 }}</td>
                    <td class="single line">{{ $timecard->satTimeOut2 }}</td>
                    <td class="ui center aligned">
                      @if ($timecard->satTardy)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                    <td>
                      @if ($timecard->satAbsent)
                      <i class="small yellow checkmark icon"></i>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </thead>
            </table>
          </div>
          <div class="ui segment">
            <form class="ui form" action="/supervisor/timecards/sign" method="post">
              {{ csrf_field() }}
              <input type='hidden' name='id' value="{{ $timecard->id }}" />
              <div class="ui buttons">
                <button class="ui primary blue button" type="submit">Sign</button>
                <div class="or">

                </div>
                <a class="ui yellow button" href="/supervisor/timecards/edit?id={{ $timecard->id }}">Edit</a>
                <a class="ui grey button" href="/supervisor">Cancel</a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

      $('.ui.accordion')
  .accordion();

      $('.message .close')
    .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
    });

    $('.ui.dropdown')
      .dropdown();
    </script>
  </body>
</html>
