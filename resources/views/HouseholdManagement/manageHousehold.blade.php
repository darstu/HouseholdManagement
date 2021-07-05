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
    <a style="position: center" href="{{route('Household', ['id_house' => $house->id_Home])}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3 style="padding-left: 20px"> Household information:</h3>
                <div class="card marginfix col-lg-6 col-sm-9" style="margin-top: 15px; margin-left: 20px; padding-top: 20px; padding-bottom: 20px; padding-left: 20px">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('confirmEditHousehold', $house->id_Home) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <label style="width: 40%; text-align: left; padding-left: 10px" for="inputName" class="lineForHouse">Household Name: *</label>
                                <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputName" name="Name" value="{{$house->Name}}">
                            </div>
                            <div class="form-row">
                                <label style="width: 40%; text-align: left; padding-left: 10px" for="inputAddress" class="lineForHouse">Address: *</label>
                                <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputAddress" name="Address" value="{{$house->Address}}">
                            </div>
                            <div class="form-row">
                                <label style="width: 40%; text-align: left; padding-left: 10px" for="inputPhone" class="lineForHouse">Phone number:</label>
                                <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputPhone" name="Phone" value="{{$house->Phone}}">
                            </div>
                            <div class="form-row">
                                <label style="width: 40%; text-align: left; padding-left: 10px" for="inputAlternativeAddress" class="lineForHouse">Alternative address:</label>
                                <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputAlternativeAddress" name="Alternative_address" value="{{$house->Alternative_address}}">
                            </div>
                            <div class="form-row">
                                <label style="width: 40%; text-align: left; padding-left: 10px" for="inputCity" class="lineForHouse">City: *</label>
                                <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputCity" name="City" value="{{$house->City}}">
                            </div>
                            <br>
                            <p class="requiredfield">* Required fields</p>
                            <div style="padding-left: 15px; margin-right: 5%">
                                <button style="float: right" type="submit" class="btn btn-primary">Save Household</button>
                            </div>
                        </form>
                </div>
    <div class="col-lg-6" style="margin-left: 10px">
        <button class="btn btn-danger" type="delete"><a onclick="return confirm('Do you really want to deactivate this household?')" href="{{route('removeHousehold', $house->id_Home)}}" style="color: white" >Deactivate Household</a></button>
    </div>
@endsection

<style>
    .lineForHouse {
        font-weight: bold;
        text-align: left;
        float: left;
        margin-left: 15px;
    }
    .requiredfield {
        text-align: left;
        float: left;
        margin-left: 15px;
    }

    .marginfix {
        text-align: left;
        margin-bottom: 10px !important;
    }
</style>
