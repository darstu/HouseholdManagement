<style>
    .dropbtn {
          background-color: lightseagreen;
          /*color: white;*/
          /*padding: 16px;*/
          /*font-size: 16px;*/
          border: none;
          cursor: pointer;
      }

    /*.dropbtn:hover, .dropbtn:focus {*/
    /*    background-color: #3e8e41;*/
    /*}*/

    #myInput {
        box-sizing: border-box;
        /*background-image: url('searchicon.png');*/
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 16px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
    }

    #myInput:focus {outline: 3px solid #ddd;}

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f6f6f6;
        min-width: 230px;
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}

    @media (min-width: 992px){
        .d-lg-block {
            display: contents!important;
        }}

    @media (max-width: 620px){
        .searchIn {
            max-width: 120px;
        }}

</style>
@extends('householdActionMenu')
@section('Content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
            <br>
            Check purchase offer here: <a style="text-decoration: underline; font-weight: bold" class="" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">Purchase offer</a>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
{{--    @foreach($messages as $message)--}}
{{--        {{$message}}--}}
{{--        @endforeach--}}
    <div class="container-fluid" style="padding-left: 0; padding-bottom: 50px;">
            <div class=" container-fluid">
                <div class="row mt-5">
                    <div class="col-sm-6" id="pavadinimas" >
                        <h2 class="page-title" >{{$title}}
                        </h2>
                    </div>

                    <div class="col-sm-2" id="mygtukai">
                        <div class="btn-group float-right mt-2" role="group">
                            <a class="btn btn-secondary" href="{{ route('addResource', ['Id' => $house->id_Home]) }}">
                                <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-lg-block">Add new</span></a>
                        </div>
                    </div>
                    <div class="col-sm-2" id="mygtukai">
                        <div class="btn-group float-right mt-2" role="group">
                            <a class="btn btn-secondary " onclick="return confirm('Do you really want to check quantities? This action will overwrite items in existing purchase offer.')"
                               href="{{ route('calculatePurchaseOffer', ['Id' => $house->id_Home]) }}">
                                <i class="fas fa-sync-alt" aria-hidden="true"></i> <span class="d-none d-lg-block">Check Quantities</span></a>
                        </div>
                    </div>
                    <div class="col-sm-2" id="mygtukai">
                        <div class="btn-group float-right mt-2" role="group">
                            <a class="btn btn-secondary " onclick="return confirm('Do you really want to check expirations? This action will overwrite items in existing purchase offer.')"
                               href="{{ route('calculatePurchaseOfferExpiration', ['Id' => $house->id_Home]) }}">
                                <i class="fas fa-sync" aria-hidden="true"></i> <span class="d-none d-lg-block">Check Expirations</span></a>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="dropdown col-sm-5 col-md-3 col-lg-2" >
                        @if($Type!=null)
                            <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen"><span id="filtertext" style="overflow: hidden">Filtered by: {{$Type->Type_name}}</span></button>
                        @else
                            <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter</button>
                        @endif
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a href="{{ route('resourcesList', ['Id' => $house->id_Home])}}">
                                All</a>
                            @foreach($stockTypes as $st)
                                @if($button!=2)
                                <a href="{{ route('filterResourcesList', ['Id' => $house->id_Home,'filter_id' => $st->id_Stock_type,'button'=>1]) }}">
                                    {{$st->Type_name}}</a>
                                    @else
                                    <a href="{{ route('filterResourcesList', ['Id' => $house->id_Home,'filter_id' => $st->id_Stock_type,$button]) }}">
                                        {{$st->Type_name}}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-7 col md-9 " style="">
                        @if($button!=2)
                            <form action="{{ route('searchResource', ['Id' => $house->id_Home, 'filter_id' => $filter_id, 'button'=>2]) }}" method="GET">
                            @else
                        <form action="{{ route('searchResource', ['Id' => $house->id_Home, 'filter_id' => $filter_id, $button]) }}" method="GET">
                            @endif
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey" type="text" value="{{$search}}" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey" type="text" value="{{old('search')}}" name="search" placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" id="find" style=" vertical-align: bottom" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row filters">
                    <div class="col" style="padding-bottom: 20px">
                        @if($button!=2)
                            <a href="{{ route('resourcesList', ['Id' => $house->id_Home]) }}"
                               class="btn btn-secondary active" role="button" aria-pressed="true">Active resources</a>
                            <a href="{{ route('resourcesList2', ['Id' => $house->id_Home]) }}"
                               class="btn btn-secondary" role="button" >All resources</a>
                        @else
                            <a href="{{ route('resourcesList', ['Id' => $house->id_Home]) }}"
                               class="btn btn-secondary" role="button" >Active resources</a>
                            <a href="{{ route('resourcesList2', ['Id' => $house->id_Home]) }}"
                               class="btn btn-secondary active" role="button" aria-pressed="true">All resources</a>
                        @endif
                    </div>
                </div>
            </div>

{{--    @php--}}
{{--use Carbon\Carbon;--}}
{{--    $now = Carbon::now();--}}
{{--@endphp--}}
{{--        {{$now}}--}}
        <div class="container-fluid" style="padding-bottom: 50px;">
        <div class="row">
            @foreach($items as $resource)
            <div class="col col-md-4">
{{--                <a href="#">--}}
                    <a href="{{ route('resourceView', ['Id' => $house->id_Home, 'id_resource'=> $resource->fk_Stock_card])}}" >
                <div class="card">
{{--                    <img class="card-img-top" src="..." alt="Card image cap">--}}
                    <div class="card-body">
{{--                        <h5 class="card-title">ID {{$resource->stock_id}}</h5>--}}
                        <h5 class="card-title">{{$resource->Name}}</h5>
                        <td></td>
                        <p class="info-wrap card-text">{{$resource->Description}}</p>
{{--                        <p>{{$resource->Stock_Card->Stock_Type->Type_name}}</p>--}}
                        <p class="info-wrap card-text">Total quantity in household:
                            <span style="font-weight: bold"> {{$resource->total_quantity}} {{$resource->Stock_Card->measurement_unit}}</span></p>
{{--                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>--}}
                    </div>
                </div>
                </a>
            </div>
                @endforeach
        </div>
            <div>
                <div style="float: left;  text-decoration: underline;">
                    Total items: {{$count}}
                </div>
                <div style="float: right">
                    {{$items->appends($_GET)->links()}}
                </div>

            </div>
        </div>
        </div>
    @endsection
<script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
</script>
