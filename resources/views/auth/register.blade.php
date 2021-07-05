<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HouseHold resource</title>


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-sidebar.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-sidebar.css') }}" rel="stylesheet">
    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Custom styles for this template -->
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">--}}
    <link href="{{ asset('css/resource.css') }}" rel="stylesheet">
    <link href="{{ asset('css/overlay.css') }}" rel="stylesheet">
    <style>
        .dropdown-container {
            display: none;
            /*background-color: #262626;*/
            padding-left: 8px;
        }

        .list-group-item.active {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        a:hover {
            color: lightseagreen;
        }

        .active {
            /*background-color:#6c757d;*/
        }

        .btn-primary {
            background-color: lightseagreen;
            border-color: lightseagreen;
        }

        .arrowFlip {
            transform: scaleX(-1);
        }
    </style>
</head>


<body>

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->

{{--    </div>--}}
<!-- /#sidebar-wrapper -->

    <!-- Page Content -->


    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <!--            <button class="btn btn-primary" id="menu-toggle">
                            <i id="arrow" class="fas fa-chevron-circle-left"></i>
                        </button>-->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    @guest
                        <li class="nav-item inactive">
                            <a class="nav-link">Recipes <span class="sr-only">(current)</span></a>
                    @else
                        <li class="nav-item active">
                            <a class="nav-link" id="openOverlay">Recipes <span class="sr-only">(current)</span></a>
                        </li>
                    @endguest
                    {{--<li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>--}}

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        {{--@if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif--}}
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('account') }}">Account information</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest

                        </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            {{--            <h1 class="mt-4">Simple Sidebar</h1>--}}
            <div class="container pad">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('Register') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Surname</label>

                                        <div class="col-md-6">
                                            <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" autocomplete="surname" autofocus>

                                            @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--            <p>The starting state of the menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will change.</p>--}}
            {{--            <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>. The top navbar is optional, and just for demonstration. Just create an element with the <code>#menu-toggle</code> ID which will toggle the menu when clicked.</p>--}}
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>


<!-- /#wrapper -->

{{--<!-- Bootstrap core JavaScript -->--}}
{{--<script src="vendor/jquery/jquery.min.js"></script>--}}
{{--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}

<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/17731d65b3.js" crossorigin="anonymous"></script>


<!-- Menu Toggle Script -->
<script>
    function openOverlay($check) {
        if (window.location.href.indexOf("recipes") < 0 || $check === 0) {
            if ($('#multiple').val()) {
                document.getElementById("pickHousehold").style.display = "block";
            } else {
                window.location.href = "{{ route('RecipeList')}}"
            }
        } else {
            window.location.href = "{{ route('RecipeList')}}"
        }
    }

    function closeOverlay() {
        document.getElementById("pickHousehold").style.display = "none";
    }

    $(document).ready(function () {
        const wrapper = $("#wrapper"),
            sidewrapper = $("#sidebar-wrapper");

        if (parseFloat(sidewrapper.css("marginLeft")) !== 0) {
            $('#arrow').toggleClass('arrowFlip')
        }
        $("#openOverlay").on('click', function (e) {
            openOverlay(1);
        });
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            wrapper.toggleClass("toggled");
            $('#arrow').toggleClass('arrowFlip')
        });
    });

    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;
    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
    //Paryskinamas aktuvus meniu punktas
    $(function () {
        var url = window.location.href;
        $("#navbarSupportedContent a").each(function () {
            if (url == (this.href)) {
                $(this).closest("a").addClass("active");
            }
        });
        $("#mysidebar a").each(function () {
            if (url == (this.href)) {
                $(this).closest("a").addClass("active");
            }
        });
    });
    $('.dropdown-btn').click(function () {
        $('.dropdown-btn.active').removeClass("active");
        $(this).addClass("active");
    });
</script>
</body>

</html>

