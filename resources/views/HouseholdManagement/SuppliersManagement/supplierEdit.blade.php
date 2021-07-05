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
    <a style="position: center" href="{{route('Supplier', ['id_house' => $house->id_Home, 'Id' => $homesupp->supplier_id])}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3>Supplier information</h3>
        <div class="card marginfix col-lg-6 col-sm-8" style="margin-top: 15px; padding-top: 10px">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('confirmEditSupplier', $homesupp->supplier_id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-row">
                        <label style="width: 40%; text-align: left; font-weight: bold" for="inputName" class="col-4">Supplier Name: *</label>
                        <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputName" required name="Name" value="{{$homesupp->Name}}">
                    </div>
                    <div class="form-row">
                        <label style="width: 40%; text-align: left; font-weight: bold" for="inputType" class="col-4">Supplier type: *</label>
                        <select style="width: 50%; margin-left: 20px; margin-bottom: 10px" id="inputType" class="form-control col-7" required name="fk_type_id">
                            <option value="{{$nowtype->type_id}}">{{$nowtype->Name}}</option>
                                @foreach($types as $type)
                                    @if($type->fk_household_id == $house->id_Home)
                                    <option value="{{$type->type_id}}">{{$type->Name}}</option>
                                    @endif
                                @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label style="width: 40%; text-align: left; font-weight: bold" for="inputAddress" class="col-4">Address: *</label>
                        <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputAddress" name="Address" value="{{$homesupp->Address}}">
                    </div>
                    <div class="form-row">
                        <label style="width: 40%; text-align: left; font-weight: bold" for="inputCity" class="col-4">City:</label>
                        <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputCity" name="City" value="{{$homesupp->City}}">
                    </div>
                    <div class="form-row">
                        <label style="width: 40%; text-align: left; font-weight: bold" for="inputPhone" class="col-4">Phone number:</label>
                        <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputPhone" name="Phone" value="{{$homesupp->Phone}}">
                    </div>
                    <p class="requiredfield">* Required fields</p>
                    <div style="padding-left: 15px; margin-right: 5%">
                        <button style="float: right" type="submit" class="btn btn-primary">Save Supplier</button>
                    </div>
                </form>
    </div>
<!--    <?php
    $title = 'Supplier type';
    ?>
    <a class="btn btn-secondary " href="{{ route('manageType', ['Id' => $house->id_Home, 'title' => $title]) }}">
        Manage types</a>-->
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
