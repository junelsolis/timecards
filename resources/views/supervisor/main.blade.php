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
          <h1 class="ui dividing header">
            Active Timecards
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
                    <h4>Summary</h4>
                    Grade {{ strtoupper($item->grade) }}<br />
                    Hours {{ $item->hours }}<br />
                    Estimate Ksh{{ $item->estimate }}<br /><br /><br />
                    <div class="ui fluid buttons">
                      <a class="ui mini green button">Edit</a>
                      <a class="ui mini orange button">Sign</a>
                    </div>
                  </div>
                  <div class="twelve wide column">
                    <form class="ui fluid form" action="/supervisor/timecards/quick-edit" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $item->id }}" />
                      <input type="hidden" name="day" value="{{ $day }}" />
                      <h4>Quick Entry</h4>
                      <p class="text">
                        Quick entry allows you to input work times for this day only.
                      </p>
                      <div class="two fields">
                        <div class="field">
                          <label>Time In</label>
                          <input type="time" name="in1" value="<?php echo $item->{$in1}; ?>" />
                        </div>
                        <div class="field">
                          <label>Time Out</label>
                          <input type="time" name="out1" value="<?php echo $item->{$out1}; ?>"/>
                        </div>
                      </div>
                      <br />
                      <div class="two fields">
                        <div class="field">
                          <label>Time In</label>
                          <input type="time" name="in2" value="<?php echo $item->{$in2}; ?>"/>
                        </div>
                        <div class="field">
                          <label>Time Out</label>
                          <input type="time" name="out2" value="<?php echo $item->{$out2}; ?>"/>
                        </div>
                      </div>
                      <button class="ui right floated blue button" type="submit">Save</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif

          <h3 class="ui dividing header">Statistics</h3>
          <h3 class="ui dividing header">Unsigned Timecards</h3>
          <h3 class="ui dividing header">Workers</h3>
        </div>
      </div>
    </div>
    <script>
      $('#toggle').click(function(){
        $('.ui.sidebar').sidebar('toggle');
      });

      $('.ui.accordion')
  .accordion();
    </script>
  </body>
</html>
