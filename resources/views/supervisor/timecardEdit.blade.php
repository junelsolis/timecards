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
        <div class="ten wide column">
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
            Edit Timecard
          </h1>
          <br />
          <div class="ui internally celled grid">
            <div class="seven wide column">
              <h2 class="ui header">{{ $timecard->fullname }}</h2>
              <h3 class="ui blue header">{{ $timecard->dateRange}} | {{ $timecard->department }}</h3>
            </div>
            <div class="three wide column center aligned">
              <div class="ui fluid blue statistic">
                <div class="value">
                  {{ $timecard->grade }}
                </div>
                <div class="label">
                  Grade
                </div>
              </div>
            </div>
            <div class="three wide column center aligned">
              <div class="ui fluid blue statistic">
                <div class="value">
                  {{ $timecard->hours }}
                </div>
                <div class="label">
                  Hours
                </div>
              </div>
            </div>
            <div class="three wide column center aligned">
              <div class="ui yellow statistic">
                <div class="value">
                  {{ $timecard->pay }}
                </div>
                <div class="label">
                  Pay Estimate
                </div>
              </div>
            </div>
          </div>
          <form class="ui form" action="/supervisor/timecards/edit" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $timecard->id }}" />
            <h3 class="ui dividing header">Information</h3>
            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">SUN</h1>
                    <p class="text">
                      {{ $timecard->dates[0] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="sunTimeIn1" value="{{ $timecard->sunTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="sunTimeOut1" value="{{ $timecard->sunTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="sunTimeIn2" value="{{ $timecard->sunTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="sunTimeOut2" value="{{ $timecard->sunTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="sunTardy"
                          <?php
                            if ($timecard->sunTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="sunAbsent"
                          <?php
                            if ($timecard->sunAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">MON</h1>
                    <p class="text">
                      {{ $timecard->dates[1] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="monTimeIn1" value="{{ $timecard->monTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="monTimeOut1" value="{{ $timecard->monTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="monTimeIn2" value="{{ $timecard->monTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="monTimeOut2" value="{{ $timecard->monTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="monTardy"
                          <?php
                            if ($timecard->monTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="monAbsent"
                          <?php
                            if ($timecard->monAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">TUE</h1>
                    <p class="text">
                      {{ $timecard->dates[2] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="tueTimeIn1" value="{{ $timecard->tueTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="tueTimeOut1" value="{{ $timecard->tueTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="tueTimeIn2" value="{{ $timecard->tueTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="tueTimeOut2" value="{{ $timecard->tueTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="tueTardy"
                          <?php
                            if ($timecard->tueTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="tueAbsent"
                          <?php
                            if ($timecard->tueAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">WED</h1>
                    <p class="text">
                      {{ $timecard->dates[3] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="wedTimeIn1" value="{{ $timecard->wedTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="wedTimeOut1" value="{{ $timecard->wedTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="wedTimeIn2" value="{{ $timecard->wedTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="wedTimeOut2" value="{{ $timecard->wedTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="wedTardy"
                          <?php
                            if ($timecard->wedTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="wedAbsent"
                          <?php
                            if ($timecard->wedAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">THU</h1>
                    <p class="text">
                      {{ $timecard->dates[4] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="thuTimeIn1" value="{{ $timecard->thuTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="thuTimeOut1" value="{{ $timecard->thuTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="thuTimeIn2" value="{{ $timecard->thuTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="thuTimeOut2" value="{{ $timecard->thuTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="thuTardy"
                          <?php
                            if ($timecard->thuTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="thuAbsent"
                          <?php
                            if ($timecard->thuAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">FRI</h1>
                    <p class="text">
                      {{ $timecard->dates[5] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="friTimeIn1" value="{{ $timecard->friTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="friTimeOut1" value="{{ $timecard->friTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="friTimeIn2" value="{{ $timecard->friTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="friTimeOut2" value="{{ $timecard->friTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="friTardy"
                          <?php
                            if ($timecard->friTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="friAbsent"
                          <?php
                            if ($timecard->friAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class='ui fluid card'>
              <div class="content">
                <div class="ui internally celled grid">
                  <div class="three wide column middle aligned center aligned">
                    <h1 class="ui yellow header">SAT</h1>
                    <p class="text">
                      {{ $timecard->dates[6] }}
                    </p>
                  </div>
                  <div class="nine wide column">
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 1</label>
                        <input type="time" name="satTimeIn1" value="{{ $timecard->satTimeIn1}}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 1</label>
                        <input type="time" name="satTimeOut1" value="{{ $timecard->satTimeOut1 }}"/>
                      </div>
                    </div>
                    <div class="two fields">
                      <div class="field">
                        <label class="ui blue header">Time In 2</label>
                        <input type="time" name="satTimeIn2" value="{{ $timecard->satTimeIn2 }}" />
                      </div>
                      <div class="field">
                        <label class="ui blue header">Time Out 2</label>
                        <input type="time" name="satTimeOut2" value="{{ $timecard->satTimeOut2 }}" />
                      </div>
                    </div>
                  </div>
                  <div class="four wide column">
                    <div class="ui fluid field">
                      <div class="ui checkbox">
                        <input type="checkbox" name="satTardy"
                          <?php
                            if ($timecard->satTardy == true) { echo 'checked';}
                          ?>
                        />
                        <label>Tardy</label>
                      </div>
                    </div>
                    <div class="ui fluid field">
                      <div class='ui checkbox'>
                        <input type="checkbox" name="satAbsent"
                          <?php
                            if ($timecard->satAbsent == true) { echo 'checked';}
                          ?>
                        />
                        <label>Absent</label>
                      </div>
                    </div>
                    <br />
                    <div class="ui field">
                      <button class="ui basic blue button">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <h3 class="ui dividing header">Payment</h3>
            <div class="ui two fields">
              <div class="field">
                <label>Contract</label>
                <div class="ui right labeled input">
                  <input type="number" name="contract" value="{{ $timecard->contract }}"/>
                  <div class='ui label'>
                    <div class="text">
                      Hours
                    </div>
                  </div>
                </div>
              </div>
              <div class="field">
                <label>Grade</label>
                <select class="ui fluid dropdown" name="grade">Grade
                  <option value='u' <?php if ($timecard->grade == 'u') { echo 'selected'; }?>>U - Unsatisfactory</option>
                  <option value='s' <?php if ($timecard->grade == 's') { echo 'selected'; }?>>S - Satisfactory</option>
                  <option value='o' <?php if ($timecard->grade == 'o') { echo 'selected'; }?>>O - Outstanding</option>
                </select>

              </div>
            </div>
            <div class="ui dividing header">

            </div>
            <div class="field center aligned">
              <div class="ui large buttons">
                <button class="ui primary blue button" type="submit">Save</button>
                <div class="or">

                </div>
                <a class="ui grey button" href="/supervisor">Cancel</a>
              </div>
            </div>
          </form>
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
