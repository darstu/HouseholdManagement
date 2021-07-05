<style>
    /*.card {*/
    /*    display: inline-block;*/
    /*    */
    .col {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .card-title {
        text-align: center;
    }

    .card-text {
        text-align: right;
    }

    /*.card{
        max-width: 250px;
    }*/
    .hovering:hover {
        color: #0000fa;
        text-decoration: underline;
        text-underline-color: #0000fa;
        cursor: pointer;
    }
</style>
@extends('householdActionMenu')
@section('Content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
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
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if($title == 'Stock card')



        <div class="container-fluid">

            <div class="container-fluid">
                <a style="position: center" href="{{route('Household', ['id_house' => $house->id_Home])}}">
                    <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16"
                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
                <div class="row mt-4">
                    <div class="col-8">
                        <h1>Resource cards</h1>
                    </div>
                    <div class="col-4">
                        <div class="btn-group float-right mt-2" role="group">
                            @if($permission_create->restricted == 1)
                            @else
                                <a class="btn btn-secondary "
                                   href="{{ route('CreateStockCard', ['Id' => $house->id_Home]) }}">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($as == 0)
            @if($typ == 'removed')
                <p style="margin-top: 40px; padding-left: 15px">Household has no deactivated resource cards.</p>
            @elseif($typ != 'null')
                <p style="margin-top: 40px; padding-left: 15px">Household has no resource cards of this
                    type.</p>
            @else
                <p style="margin-top: 40px; padding-left: 15px">Household does not have any resource cards
                    yet.</p>
            @endif
        @else

            @if($typ == 'removed')
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortFilteredStock', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Resource Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a class="dropdown-item" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">All resource cards</a>
                            @foreach($stypes as $stype)
                                <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => $stype->id_Stock_type])}}">{{ $stype->Type_name }}</a>
                            @endforeach
                            <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">Removed</a>
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchStock', ['Id' => $house->id_Home, 'filter_id' => 0]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $tp)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="width: 300px; padding-left: 10px">
                                    <?php
                                    $title = "Stock card";
                                    ?>
                                    <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $tp->id_Stock_card, 'title' => $title])}}'>
                                        <div class="card-body">
                                            <h5 class="card-title">{{$tp->Name}} <a class="btn btn-primary" href="{{ route('activateStock', ['id_house' => $house->id_Home, 'Id' => $tp->id_Stock_card]) }}">Activate</a></h5>
                                            <img class="card-img-top" style="width: 250px; height: 200px;"
                                                 src="{{ asset('/images') . '/' . $tp->image . '.jpg'}}" alt="{{$tp->image}}">
                                            <p class="card-text">{{$tp->Address}}</p>
                                            <p class="card-text" style="text-align: left">Removed: {{$tp->removed_date}}</p>
                                            <a class="btn btn-danger" href="{{ route('deleteStock', ['type_id' => $tp->id_Stock_card]) }}">Delete</a>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @elseif($typ != 'null')
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortFilteredStock', ['id_house' => $house->id_Home, 'type' => $typ])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Resource Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a class="dropdown-item" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">All resource cards</a>
                            @foreach($stypes as $stype)
                                <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => $stype->id_Stock_type])}}">{{ $stype->Type_name }}</a>
                            @endforeach
                            <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">Removed</a>
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchStock', ['Id' => $house->id_Home, 'filter_id' => $typ]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $tp)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="width: 300px;">
                                    <?php
                                    $title = "Stock card";
                                    ?>
                                    <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $tp->id_Stock_card, 'title' => $title])}}'>
                                        <div class="card-body">
                                            <h5 class="card-title">{{$tp->Name}}</h5>
                                            <img class="card-img-top" style="width: 250px; height: 200px;"
                                                 src="{{ asset('/images') . '/' . $tp->image . '.jpg'}}" alt="{{$tp->image}}">
                                            <p class="card-text">{{$tp->Address}}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortStock', ['id_house' => $house->id_Home])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Resource Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a class="dropdown-item" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">All resource cards</a>
                            @foreach($stypes as $stype)
                                <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => $stype->id_Stock_type])}}">{{ $stype->Type_name }}</a>
                            @endforeach
                            <a class="dropdown-item" href="{{ action('App\Http\Controllers\ResourceManagement\ResourceListController@getTypeStock', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">Removed</a>
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchStock', ['Id' => $house->id_Home, 'filter_id' => -1]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $tp)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="width: 300px;">
                                    <?php
                                    $title = "Stock card";
                                    ?>
                                    <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $tp->id_Stock_card, 'title' => $title])}}'>
                                        <div class="card-body">
                                            <h5 class="card-title">{{$tp->Name}}</h5>
                                            <img class="card-img-top" style="width: 250px; height: 200px;"
                                                 src="{{ asset('/images') . '/' . $tp->image . '.jpg'}}" alt="{{$tp->image}}">
                                            <p class="card-text">{{$tp->Address}}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif
            <!--        <div style="float: right">{{--{{$allstocks->appends($_GET)->links()}}--}}</div>-->
        @endif
    @elseif($title == 'Warehouse place')
        <div class="container-fluid">
            <div class="container-fluid">
                <a style="position: center" href="{{route('Household', ['id_house' => $house->id_Home])}}">
                    <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16"
                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
                <div class="row mt-4">
                    <div class="col-8">
                        <h1>Storage locations</h1>
                    </div>
                    <div class="col-4">
                        <div class="btn-group float-right mt-2" role="group">
                            @if($permission_create->restricted == 1)
                            @else
                                <a class="btn btn-secondary "
                                   href="{{ route('CreateWarehouse', ['Id' => $house->id_Home]) }}">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @if($as == 0)
            @if($typ == 'removed')
                <p style="margin-top: 40px; padding-left: 15px">Household has no removed storage locations.</p>
            @elseif($typ != null)
                <p style="margin-top: 40px; padding-left: 15px">Household has no storage locations of this
                    type.</p>
            @else
                <p style="margin-top: 40px; padding-left: 15px">Household does not have any storage locations
                    yet.</p>
            @endif
        @else

            @if($typ == 'removed')
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortFilteredWarehouse', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Storage Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
                                All storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'highest'])}}">
                                Highest level storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'lowest'])}}">
                                Lowest level storage places</a>
                            @foreach($allmembers as $m)
                                @if($m->household_id == $house->id_Home and $m->owner == 1)
                                    @if($m->users_id == Auth::user()->id)
                                        <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'removed'])}}">
                                            Removed storage places</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchWarehouse', ['Id' => $house->id_Home, 'filter_id' => -3]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $ware)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="width: 300px;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$ware->Warehouse_name}} <a class="btn btn-primary" href="{{ route('activateWarehouse', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place]) }}">Activate</a></h5>
                                        @if($ware->Description == null)
                                            <p class="card-text" style="text-align: left">Aprašymas: -------</p>
                                        @else
                                            <p class="card-text" style="text-align: left">
                                                Aprašymas: {{$ware->Description}}</p>
                                        @endif
                                        @if($ware->fk_Warehouse_place == null)
                                            <p class="card-text" style="text-align: left">Adresas: {{$house->Name}}</p>
                                        @else
                                            <p class="card-text" style="text-align: left">
                                                @foreach($warehousesfor as $warfor)
                                                    @if($ware->fk_Warehouse_place == $warfor->id_Warehouse_place)
                                                        Where it's placed: {{$warfor->Warehouse_name}}</p>
                                        @endif
                                        @endforeach
                                        @endif
                                        <p class="card-text" style="text-align: left">Deactivated: {{$ware->removed_date}}</p>
                                        <a class="btn btn-danger" href="{{ route('deleteWare', ['type_id' => $ware->id_Warehouse_place]) }}">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <!--        <div style="float: right">{{--{{$allstocks->appends($_GET)->links()}}--}}</div>-->
            @elseif($typ == 'highest')
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortFilteredWarehouse', ['id_house' => $house->id_Home, 'type' => 'highest'])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Storage Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
                                All storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'highest'])}}">
                                Highest level storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'lowest'])}}">
                                Lowest level storage places</a>
                            @foreach($allmembers as $m)
                                @if($m->household_id == $house->id_Home and $m->owner == 1)
                                    @if($m->users_id == Auth::user()->id)
                                        <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'removed'])}}">
                                            Deactivated storage places</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchWarehouse', ['Id' => $house->id_Home, 'filter_id' => -2]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $ware)
                            <?php
                            $l = 0;
                            ?>
                            @foreach($lockedwarehouse as $locked)
                                @if($locked->warehouse_id == $ware->id_Warehouse_place or $locked->warehouse_id == $ware->fk_Warehouse_place)
                                    <?php
                                    $l = $l + 1;
                                    ?>
                                @endif
                            @endforeach
                            @if($l == 0)
                                {{--@if($l == 0 and $ware->fk_Warehouse_place == null)--}}
                                <div class="col-lg-4 col-sm-6">
                                    <div class="card" style="width: 300px;">
                                        <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place, 'title' => $title])}}'>
                                            <div class="card-body">
                                                <h5 class="card-title">{{$ware->Warehouse_name}}</h5>
                                                @if($ware->Description == null)
                                                    <p class="card-text" style="text-align: left">Aprašymas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Aprašymas: {{$ware->Description}}</p>
                                                @endif
                                                @if($ware->Address == null)
                                                    <p class="card-text" style="text-align: left">Adresas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Adresas: {{$ware->Address}}</p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                <!--        <div style="float: right">{{--{{$allstocks->appends($_GET)->links()}}--}}</div>-->
            @elseif($typ =='lowest')
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortFilteredWarehouse', ['id_house' => $house->id_Home, 'type' => 'lowest'])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Storage Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
                                All storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'highest'])}}">
                                Highest level storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'lowest'])}}">
                                Lowest level storage places</a>
                            @foreach($allmembers as $m)
                                @if($m->household_id == $house->id_Home and $m->owner == 1)
                                    @if($m->users_id == Auth::user()->id)
                                        <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'removed'])}}">
                                            Deactivated storage places</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchWarehouse', ['Id' => $house->id_Home, 'filter_id' => -1]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $ware)
                            <?php
                            $l = 0;
                            ?>
                            @foreach($lockedwarehouse as $locked)
                                @if($locked->warehouse_id == $ware->id_Warehouse_place or $locked->warehouse_id == $ware->fk_Warehouse_place)
                                    <?php
                                    $l = $l + 1;
                                    ?>
                                @endif
                            @endforeach
                            @if($l == 0)
                                {{--@if($l == 0 and $ware->fk_Warehouse_place == null)--}}
                                <div class="col-lg-4 col-sm-6">
                                    <div class="card" style="width: 300px;">
                                        <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place, 'title' => $title])}}'>
                                            <div class="card-body">
                                                <h5 class="card-title">{{$ware->Warehouse_name}}</h5>
                                                @if($ware->Description == null)
                                                    <p class="card-text" style="text-align: left">Aprašymas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Aprašymas: {{$ware->Description}}</p>
                                                @endif
                                                @if($ware->Address == null)
                                                    <p class="card-text" style="text-align: left">Adresas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Adresas: {{$ware->Address}}</p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!--        <div style="float: right">{{--{{$allstocks->appends($_GET)->links()}}--}}</div>-->
            @else
                <br>
                <div class="row mt-8 col-gl-10">
                    <form method="POST" action="{{Route('SortWarehouse', ['id_house' => $house->id_Home])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Storage Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
                        </span>
                        <br>
                    </form>
                    <div class="dropdown col-3 col-md-2">
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary"
                                style="background-color: lightseagreen; border-color: lightseagreen">Filter
                        </button>
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            <a href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
                                All storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'highest'])}}">
                                Highest level storage places</a>
                            <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'lowest'])}}">
                                Lowest level storage places</a>
                            @foreach($allmembers as $m)
                                @if($m->household_id == $house->id_Home and $m->owner == 1)
                                    @if($m->users_id == Auth::user()->id)
                                        <a href="{{ action('App\Http\Controllers\ResourceManagement\WarehousePlaceController@getTypeWare', ['id_house' => $house->id_Home, 'removed'])}}">
                                            Deactivated storage places</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md">
                        <form style="float: right; margin-right: 20px"
                              action="{{ route('searchWarehouse', ['Id' => $house->id_Home, 'filter_id' => 0]) }}"
                              method="GET">
                            @if($search != null)
                                <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                            @else
                                <input class="searchIn"
                                       style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                       type="text" value="{{old('search')}}" name="search"
                                       placeholder="Search.." required/>
                            @endif
                            <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom"
                                    type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <div class="row">
                        @foreach($allstocks as $ware)
                            <?php
                            $l = 0;
                            ?>
                            @foreach($lockedwarehouse as $locked)
                                @if($locked->warehouse_id == $ware->id_Warehouse_place or $locked->warehouse_id == $ware->fk_Warehouse_place)
                                    <?php
                                    $l = $l + 1;
                                    ?>
                                @endif
                            @endforeach
                            @if($l == 0)
                                {{--@if($l == 0 and $ware->fk_Warehouse_place == null)--}}
                                <div class="col-lg-4 col-sm-6">
                                    <div class="card" style="width: 300px;">
                                        <a href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place, 'title' => $title])}}'>
                                            <div class="card-body">
                                                <h5 class="card-title">{{$ware->Warehouse_name}}</h5>
                                                @if($ware->Description == null)
                                                    <p class="card-text" style="text-align: left">Aprašymas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Aprašymas: {{$ware->Description}}</p>
                                                @endif
                                                @if($ware->Address == null)
                                                    <p class="card-text" style="text-align: left">Adresas: -------</p>
                                                @else
                                                    <p class="card-text" style="text-align: left">
                                                        Adresas: {{$ware->Address}}</p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                <!--        <div style="float: right">{{--{{$allstocks->appends($_GET)->links()}}--}}</div>-->
            @endif

        @endif


    @endif
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
