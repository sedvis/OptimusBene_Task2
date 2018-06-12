<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.10/css/weather-icons.min.css">
    <title>{{trans('weather.title')}}</title>
</head>
<body>

<header>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand d-flex align-items-center">
                <strong>{{trans('weather.title')}}</strong>
            </a>
        </div>
    </div>
</header>
<main role="main">
    <div class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4" id="alert_holder">

                </div>
            </div>
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="card mb-4 box-shadow">
                        <form>
                            <div class="card-body">
                                <div class="h-25"><i class="card-img-top wi wi-day-sunny"></i></div>
                                <select class="form-control" name="city" id="city">
                                    @foreach(config('owm.cities') as $city)
                                        <option value="{{strtolower($city)}}"
                                                @if($city == $current_city) selected @endif>{{$city}}</option>
                                    @endforeach
                                </select>
                                <h5 class="card-title">Weather in
                                    <span id="title">{{title_case($current_city)}}</span>
                                </h5>
                                <p class="card-text">Temperature:
                                    <span id="temperature"></span>
                                                     °C
                                </p>
                                <p class="card-text">Wind speed:
                                    <span id="wind_speed"></span>
                                </p>
                                <p class="card-text">Wind direction:
                                    <span id="wind_direction"></span>
                                </p>
                                <label class="col-form-label" for="email">Subscribe to wind alerts</label>
                                <input type="email" name="email" id="email" class="form-control mb-2"
                                       placeholder="Your email" required>
                                <button type="button" class="btn-primary btn" id="subscribe">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(function () {
        updateWeather();
    });

    $('select[name="city"]').on('change', function () {
        updateWeather();
    });
    $('#subscribe').on('click', function () {
        subscribe();
    });

    function updateWeather() {
        var current_city = $('select[name="city"]').val();
        $.get({
            url: '{{route('current')}}',
            method: 'get',
            dataType: 'json',
            data: {city: current_city},
            success: function (json) {
                if (json.success) {
                    $('#title').text(json.data.name);
                    $('#temperature').text(json.data.main.temp);
                    $('#wind_direction').text(json.data.wind.deg);
                    $('#wind_speed').text(json.data.wind.speed);
                }
            }
        });
    }

    function subscribe() {
        var email = $('input[name="email"]').val();
        $.get({
            url: '{{route('subscribe')}}',
            method: 'post',
            dataType: 'json',
            data: {email: email},
            success: function (json) {
                var html;
                if (json.success) {
                    html = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        json.message +
                        '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '    <span aria-hidden="true">&times;</span>' +
                        '  </button>' +
                        '</div>';
                } else {
                    html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        json.message +
                        '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '    <span aria-hidden="true">&times;</span>' +
                        '  </button>' +
                        '</div>';
                }
                $('#alert_holder').html(html);
            }
        });
    }
</script>

</body>
</html>