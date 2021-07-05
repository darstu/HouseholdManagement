@extends('app')
@if($house->removed == 0)
@section('ActionMenu')
    @foreach($allmembers as $m)
        @if($m->household_id == $house->id_Home and $m->owner == 1)
            @if($m->users_id == Auth::user()->id)
                <div class="bg-light border-right" id="sidebar-wrapper">
                    <div class="sidebar-heading">{{ $house->Name }}</div>
                    <div class="list-group list-group-flush" id="mysidebar">
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{ route('manageHousehold', ['Id' => $house->id_Home]) }}">Manage household</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{ route('manageHouseholdMembers', ['Id' => $house->id_Home]) }}">Manage household members</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{ route('inviteMember', ['Id' => $house->id_Home]) }}">Invite members</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">Resource cards</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">Storage locations</a>
                        <a class="list-group-item list-group-item-action dropdown-btn">Suppliers
                            <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <div class="dropdown-container">
                            <a class="dropdown-item" href="{{action('App\Http\Controllers\HouseholdManagement\SuppliersController@index', ['id_house' => $house->id_Home])}}">All Suppliers</a>
                            @foreach($types as $type)
                                @if($type->fk_household_id == $house->id_Home)
                                    <a class="dropdown-item" href="{{ action('App\Http\Controllers\HouseholdManagement\SuppliersController@getType', ['id_house' => $house->id_Home, $type->type_id])}}">{{ $type->Name }}</a>
                                @endif
                            @endforeach
                            <a class="dropdown-item" href="{{ action('App\Http\Controllers\HouseholdManagement\SuppliersController@getType', ['id_house' => $house->id_Home, 'removed'])}}">Deactivated</a>
                        </div>
                        <br>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('resourcesList', ['Id' => $house->id_Home])}}">Resources</a>
{{--                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('openMinMax', ['Id' => $house->id_Home])}}">Min Max quantities</a>--}}
{{--                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('batchesList', ['Id' => $house->id_Home])}}">Batches</a>--}}
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">Purchase offer</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('setCheckTimes', ['Id' => $house->id_Home])}}">Checking times</a>
                    </div>
                </div>
            @else
                <div class="bg-light border-right" id="sidebar-wrapper">
                    <div class="sidebar-heading">{{ $house->Name }}</div>
                    <div class="list-group list-group-flush" id="mysidebar">
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">Resource cards</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">Storage locations</a>
                        <a class="list-group-item list-group-item-action dropdown-btn">Suppliers
                            <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <div class="dropdown-container">
                            <a class="dropdown-item" href="{{action('App\Http\Controllers\HouseholdManagement\SuppliersController@index', ['id_house' => $house->id_Home])}}">All Suppliers</a>
                            @foreach($types as $type)
                                @if($type->fk_household_id == $house->id_Home)
                                    <a class="dropdown-item" href="{{ action('App\Http\Controllers\HouseholdManagement\SuppliersController@getType', ['id_house' => $house->id_Home, $type->type_id])}}">{{ $type->Name }}</a>
                                @endif
                            @endforeach
                        </div>
                        <br>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('resourcesList', ['Id' => $house->id_Home])}}">Resources</a>
{{--                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('openMinMax', ['Id' => $house->id_Home])}}">Min Max quantities</a>--}}
{{--                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('batchesList', ['Id' => $house->id_Home])}}">Batches</a>--}}
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">Purchase offer</a>
                        <a class="list-group-item list-group-item-action dropdown-btn" href="{{route('setCheckTimes', ['Id' => $house->id_Home])}}">Checking times</a>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
@endsection
@else
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">{{ $house->Name }}</div>
        <div class="list-group list-group-flush" id="mysidebar">
            <a class="list-group-item list-group-item-action btn-primary" href="{{ route('activateHousehold', ['Id' => $house->id_Home]) }}">Activate</a>
<!--            <a class="list-group-item list-group-item-action dropdown-btn">Manage household</a>-->
        </div>
    </div>
@endsection
@endif
