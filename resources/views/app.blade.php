<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Household resource management</title>


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

@section('ActionMenu')
    <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">Household name</div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light">Dashboard</a>
            </div>
        </div>
@show

{{--    </div>--}}
<!-- /#sidebar-wrapper -->

    <!-- Page Content -->


    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-primary" id="menu-toggle">
                <i id="arrow" class="fas fa-chevron-circle-left"></i>
            </button>
            <img id="logo" src="{{asset('images/favicon.png')}}" style="max-width: 40px;max-height: 40px; margin-left: 20px;cursor: pointer">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <!--redirecting to main page-->
                        <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                    </li>
                    @guest
                        <li class="nav-item inactive">
                            <a class="nav-link">Recipes <span class="sr-only">(current)</span></a>
                    @else
                        <li class="nav-item active">
                            <a class="nav-link" id="openOverlay" style="cursor: pointer;">Recipes <span class="sr-only">(current)</span></a>
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
            @yield('Content')
            {{--            <p>The starting state of the menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will change.</p>--}}
            {{--            <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>. The top navbar is optional, and just for demonstration. Just create an element with the <code>#menu-toggle</code> ID which will toggle the menu when clicked.</p>--}}
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
{{--<div class="fluid-container">--}}
{{--    <div id="pickHousehold" class="overlay" style="transition: opacity 1s linear">--}}
{{--        <span class="closebtn" title="Close Overlay" onclick="closeOverlay()">x</span>--}}
{{--        <div id="overlayContent" class="overlay-content">--}}
{{--                    <p class="label label-default" style="font-size: 4.2vw">Pick household for checking </p>--}}
{{--                    <p class="label label-default" style="font-size: 4.2vw">ability to make the dish</p>--}}
{{--                    <form action="{{route('RecipeList')}}" >--}}
{{--                        --}}{{--                <label for="houseID" style="color: white;font-size: 40px">Pick household for checking ability to make the dish</label>--}}
{{--                        <select id="houseID" class="form-control" name="houseID">--}}
{{--                            @php--}}
{{--                                $Households=session('Households');--}}
{{--                                $old=(int)session('houseID');--}}
{{--                            @endphp--}}
{{--                            @isset($Households)--}}
{{--                                @foreach($Households as $household)--}}
{{--                                    @isset($old)--}}
{{--                                        <option value="{{$household->id_Home}}"--}}
{{--                                            {{ $household->id_Home == $old ? 'selected' : '' }}>{{$household->Name}}</option>--}}
{{--                                    @else--}}
{{--                                        <option--}}
{{--                                            value="{{$household->id_Home}}" {{ $loop->first ? 'selected' : '' }}>{{$household->Name}}</option>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            @endisset--}}
{{--                        </select>--}}
{{--                        <button class="btn-primary" onclick="handler(event)" type="submit" ><i class="fas fa-check"></i>--}}
{{--                        </button>--}}
{{--                    </form>--}}
{{--                    --}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- /#wrapper -->

{{--<!-- Bootstrap core JavaScript -->--}}
{{--<script src="vendor/jquery/jquery.min.js"></script>--}}
{{--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
<input type="hidden" id="multiple" value="{{session('MultipleHouseholds')}}">
<div id="pickHousehold" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pick household for checking ability to make the dish</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('RecipeList')}}" class="d-flex">
                    <select id="houseID" class="form-control" name="houseID">
                        @php
                            $Households=session('Households');
                            $old=(int)session('houseID');
                        @endphp
                        @isset($Households)
                            @foreach($Households as $household)
                                @isset($old)
                                    <option value="{{$household->id_Home}}"
                                        {{ $household->id_Home == $old ? 'selected' : '' }}>{{$household->Name}}</option>
                                @else
                                    <option
                                        value="{{$household->id_Home}}" {{ $loop->first ? 'selected' : '' }}>{{$household->Name}}</option>
                                @endif
                            @endforeach
                        @endisset
                    </select>
                    <button class="btn btn-primary float-right" type="submit" style="width: 50px"><i class="fas fa-check"></i>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
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
                $("#pickHousehold").modal('show');
            } else {
                window.location.href = "{{ route('RecipeList')}}"
            }
        } else {
            window.location.href = "{{ route('RecipeList')}}"
        }
    }

    function closeOverlay() {
        $("#pickHousehold").fadeOut();
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
        $('#logo').click(function(){
           if(window.location.href.indexOf("recipes") < 0){
               window.location.href = "{{ route('home')}}"
           }else{
               window.location.href = "{{ route('RecipeList')}}"
           }
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>

</body>

</html>
