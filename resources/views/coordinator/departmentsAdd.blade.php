<!doctype html>
<html lang="en">
  <head>
    <title>Coordinator | Timecards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="{{ asset('semantic/dist/semantic.min.js')}}"></script>
  </head>
  </head>
  <body>
    <div class="ui">
      @include('/coordinator/navbar')
      <div class="ui grid" style="margin: 1%;">
          <div class="eight wide column">
            <div class="ui segment">
              <h1 class="header">Create Department</h1>
              <form class="ui form" action="/coordinator/departments/add" method="post">
              {{ csrf_field() }}
                <h4 class="ui dividing header">Information</h4>
                <div class="field">
                  <label>Name</label>
                  <input type="text" name="name" placeholder="Name of department (max 40 characters)" required />
                </div>
                <h4 class="ui dividing header">Additional Information</h4>
                <div class="grouped fields">
                  <label>Select supervisors for this department (optional)</label>
                  @foreach ($supervisors as $item)
                  <div class="field">
                    <div class="ui checkbox">
                      <input type="checkbox" name="supervisors[]" value="{{ $item->id }}" />
                      <label>{{ $item->fullname }}</label>
                    </div>
                  </div>
                  @endforeach
                </div>
                <br />
                <div class="ui buttons">
                  <button class="ui primary blue button" type="submit">Create</button>
                  <a class="ui basic grey button" href="/coordinator">Cancel</a>
                </div>
              </form>
              @if(session('error'))
              <div class="ui yellow message">
                <i class="close icon"></i>
                <div class="header">
                  Error
                </div>
                <?php echo session('error');?>
              </div>
              @endif
            </div>
          </div>
      </div>
    </div>
    <script>$('.ui.sidebar')
  .sidebar('show');
    </script>
    <script>$('.message .close')
      .on('click', function() {
        $(this)
          .closest('.message')
          .transition('fade')
        ;
      });
    </script>
  </body>
</html>
