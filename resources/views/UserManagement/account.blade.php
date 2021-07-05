@extends('app')

@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="list-group list-group-flush">
            <div style="padding: 28px 16px 28px 16px" class="list-group-item list-group-item-action bg-light"> </div>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('invites') }}" class="list-group-item list-group-item-action bg-light">Invites</a>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('CreateHousehold') }}" class="list-group-item list-group-item-action bg-light">Create household</a>
        </div>
    </div>
@endsection

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
    <a style="position: center" href="{{route('home')}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3>ID: {{$user->id}}</h3>
    <div class="card marginfix col-lg-8">
        <form class="form" method="POST" action="{{ Route('confirmEditAccount',  Auth::user()->id)}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div  class="container-fluid">
                <h4 style="padding-top: 10px; text-align: left">User details</h4>
                <hr>
                <div class="form-group row">
                    <label for="email" class="col-lg-3 control-label lineForHouse">Your email:</label>
                    <div class="col-lg-6">
                        <input  id="email" class="form-control" type="email"  name="email" value="{{$user->email}}" required>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="name" class="col-lg-3 control-label lineForHouse">Name:</label>
                    <div class="col-lg-6">
                        <input id="name" class="form-control" type="text" name="name" value="{{$user->name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="surname" class="col-lg-3 control-label lineForHouse">Surname:</label>
                    <div class="col-lg-6">
                        <input id="surname" class="form-control" type="text" name="surname" value="{{$user->surname}}">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-lg-8 ">
                        <button class="btn btn-info" type="submit" style="float: right"><a style="color: white" >Save</a></button>
                    </div>
                    <div class="nav-item dropdown passwordBox col-lg-4" style="float: right">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Change password
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="padding: 10px" aria-labelledby="navbarDropdown">
                            <div class="form-group row">
                                <label for="old_password" class="col-lg-8 control-label">Old password:</label>
                                <div class="col-lg-12">
                                    <input id="old_password" type="password" class="form-control" name="old_password"  min="8">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-lg-8 control-label">New password:</label>
                                <div class="col-lg-12">
                                    <input id="password" type="password" class="form-control" name="password"  min="8">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-lg-8 control-label">Confirm Password:</label>
                                <div class="col-lg-12">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" min="8" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-6">
        <button class="btn btn-danger" type="delete"><a onclick="return confirm('Do you really want to delete account?')" href="{{route('removeAccount', $user->id)}}" style="color: white" >Delete Account</a></button>
    </div>
    <br>
    <table id="myTable" class="col-sm-6 col-lg-4 table table-bordered" style="width: 80% ;border: none">
        <h5>Removed households</h5>
        <tbody>
        @foreach($member as $mem)
            @foreach($houses as $house)
                @if($mem->household_id == $house->id_Home and $house->removed == 1)
                    <tr>
                        <td>{{ $house->Name }}</td>
                        <td><a class="btn btn-dark" href="{{route('Household', ['id_house' => $house->id_Home])}}">Information</a></td>
                        <td><a class="btn btn-primary" href="{{ route('activateHousehold', ['Id' => $house->id_Home]) }}">Activate</a></td>
                    </tr>
        @endif
        @endforeach
        @endforeach
    </table>
@endsection

<style>
    .marginfix {
        text-align: left;
        margin-bottom: 10px !important;
    }
    .lineForHouse {
        font-weight: bold;
        text-align: left;
        float: left;
        margin-left: 15px;
    }
</style>

