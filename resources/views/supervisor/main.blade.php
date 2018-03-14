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
          <h1 class="ui dividing header" id="top">
            Timecards this week
          </h1>
          @if (isset($activeTimecards))
          <?php
            $day = strtolower(date('D'));

            $in1 = "{$day}TimeIn1";
            $out1 = "{$day}TimeOut1";
            $in2 = "{$day}TimeIn2";
            $out2 = "{$day}TimeOut2";


          ?>
          <div class="ui styled fluid accordion">
            @foreach ($activeTimecards as $item)
            <div class="title">
              <i class="dropdown icon"></i>
              {{ $item->fullname }} | {{ $item->department }}
            </div>
            <div class="content">
              <div class="ui internally celled grid">
                <div class="row">
                  <div class="four wide column">
                    <h4 class="ui blue header">Summary</h4>
                    <strong>Grade</strong> {{ strtoupper($item->grade) }}<br />
                    <strong>Hours</strong> {{ $item->hours }}h<br />
                    <strong>Estimate</strong> Ksh{{ $item->estimate }}<br /><br /><br />
                    <a class="mini ui blue basic button" href="/supervisor/timecards/edit?id={{ $item->id }}">Edit</a>
                    <a class="mini ui orange button" href="/supervisor/timecards/sign?id={{ $item->id }}">Sign</a>
                  </div>
                  <div class="twelve wide column middle aligned">
                    <form class="ui fluid form" action="/supervisor/timecards/quick-edit" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $item->id }}" />
                      <input type="hidden" name="day" value="{{ $day }}" />
                      <h3 class="ui yellow header">Quick Entry</h3>
                      <p class="text">
                        Quick entry allows you to input work times for <strong><em>today</em></strong>.
                      </p>
                      <div class="two fields">
                        <div class="field">
                          <label class="ui blue header">Time In</label>
                          <input type="time" name="in1" value="<?php echo $item->{$in1}; ?>" />
                        </div>
                        <div class="field">
                          <label class="ui blue header">Time Out</label>
                          <input type="time" name="out1" value="<?php echo $item->{$out1}; ?>"/>
                        </div>
                      </div>
                      <br />
                      <div class="two fields">
                        <div class="field">
                          <label class="ui blue header">Time In</label>
                          <input type="time" name="in2" value="<?php echo $item->{$in2}; ?>"/>
                        </div>
                        <div class="field">
                          <label class="ui blue header">Time Out</label>
                          <input type="time" name="out2" value="<?php echo $item->{$out2}; ?>"/>
                        </div>
                      </div>
                      <div class="two fluid fields">
                        <div class="field">
                          <div class="ui checkbox">
                            <input type="checkbox" name="tardy"
                              <?php
                                if ($item->{$day .'Tardy'}) {
                                  echo "checked";
                                }
                              ?>
                             />
                            <label>Tardy</label>
                          </div><br />
                          <div class="ui checkbox">
                            <input type="checkbox" name="absent"
                              <?php
                                if ($item->{$day . 'Absent'}) {
                                  echo "checked";
                                }
                              ?>
                            />
                            <label>Absent</label>
                          </div>
                        </div>
                        <div class="field">
                          <button class="ui right floated blue button" type="submit">Save</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <br />
          <a href="#top"><i class="angle double up icon"></i>Top</a>
          @endif


          <!-- <div class="ui segment">
            stats go in here
          </div> -->
          @if (count($unsignedTimecards) > 0)
          <h2 class="ui dividing header">Unsigned Timecards</h2>
          <div class="ui styled fluid accordion">
            @foreach ($unsignedTimecards as $timecard)
            <div class="title">
              <i class="dropdown icon"></i>
              {{ $timecard->fullname }} | {{ $timecard->department }}
            </div>
            <div class="content">
              <div class="ui internally celled grid">
                <div class="four wide column center aligned middle aligned">
                  <div class="ui blue large statistic">
                    <div class="value">
                      {{ $timecard->hours }}
                    </div>
                    <div class="label">
                      Hours
                    </div>
                  </div>
                </div>
                <div class="eight wide column center aligned">
                  <h3 class="ui blue header">{{ $timecard->dateRange }}</h3>
                  <div class="ui tiny statistic">
                    <div class="value">
                      {{$timecard->tardies}}
                    </div>
                    <div class="label">
                      Tardies
                    </div>
                  </div>
                  <div class="ui tiny statistic">
                    <div class="value">
                      {{ $timecard->absences}}
                    </div>
                    <div class='label'>
                      Absences
                    </div>
                  </div>
                  <div class="ui tiny statistic">
                    <div class="value">
                      {{ strtoupper($timecard->grade) }}
                    </div>
                    <div class="label">
                      Grade
                    </div>
                  </div>
                  <div class="ui tiny statistic">
                    <div class="value">
                      {{ $timecard->pay}}
                    </div>
                    <div class="label">
                      Pay
                    </div>
                  </div>

                </div>
                <div class="four wide column center aligned">
                  <a class="ui tiny basic blue button" href="/supervisor/timecards/edit?id={{ $timecard->id }}">Edit</a>
                  <a class="ui tiny orange button" href="/supervisor/timecards/sign?id={{$timecard->id}}">Sign</a>

                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif



          @if ($workers)
          <h2 class="ui dividing header">Workers</h2>
          <div class="ui two column stackable grid">
            <div class="ui column">
              @foreach ($workers[0] as $worker)
              <div class="ui list">
                <div class="item">
                  <i class="user icon"></i>
                  <div class="content">
                    <h5 class="ui blue header">{{ $worker->fullname }}</h5>
                  </div>
                </div>
                <div class="item">
                  <i class="building outline icon"></i>
                  <div class="content">
                    <span style="color: orange;">
                      @foreach ($worker->departmentNames as $item)
                      {{ $item }}
                      @endforeach
                    </span>
                  </div>
                </div>
                <div class="item">
                  <i class="clipboard outline icon"></i>
                  <div class="content">
                    {{ $worker->totalTimecards }} Timecards
                  </div>
                </div>
              </div>
              <div class="ui divider"></div>
              @endforeach
            </div>
            <div class="ui column">
              @foreach ($workers[1] as $worker)
              <div class="ui list">
                <div class="item">
                  <i class="user icon"></i>
                  <div class="content">
                    <h5 class="ui blue header">{{ $worker->fullname }}</h5>
                  </div>
                </div>
                <div class="item">
                  <i class="building outline icon"></i>
                  <div class="content">
                    <span style="color: orange;">
                      @foreach ($worker->departmentNames as $item)
                      {{ $item }}
                      @endforeach
                    </span>
                  </div>
                </div>
                <div class="item">
                  <i class="clipboard outline icon"></i>
                  <div class="content">
                    {{ $worker->totalTimecards }} Timecards
                  </div>
                </div>
              </div>
              <div class="ui divider"></div>
              @endforeach
            </div>
          </div>
          <a href="#top"><i class="angle double up icon"></i>Top</a>
          @endif

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
    </script>
  </body>
</html>
