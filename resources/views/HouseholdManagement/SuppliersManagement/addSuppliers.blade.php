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
    @if($title == 'Supplier type')
        <a style="position: center" href="{{URL::previous()}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h4>Supplier type</h4>
        <br>
        <div class="card col-lg-6 col-sm-8" style="padding-top: 20px">
        <form role="form" method="POST" action="{{route('addSuppliers', ['Id' => $house->id_Home, 'title' => $title])}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="inputName" class="lineForHouse col-4">Name *</label>
                <input type="text" class="form-control col-7" id="inputName" name="Name" value="{{old('Name')}}">
            </div>
            <div class="form-group row">
                <label for="inputDescription" class="lineForHouse col-4">Description</label>
                <input type="text" class="form-control col-7" id="inputDescription" name="Description" value="{{old('Description')}}">
            </div>
            <p class="requiredfield">* Required fields</p>
            <div style="padding-left: 15px">
                <button type="submit" class="btn btn-info">Add type</button>
            </div>
        </form>
        </div>
    @else
        <a style="position: center" href="{{action('App\Http\Controllers\HouseholdManagement\SuppliersController@index', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h3>Create supplier</h3>
        <br>
        <div class="card col-lg-6 col-sm-8" style="margin-bottom: 2%; padding-top: 20px">
    <form role="form" method="POST" action="{{route('addSuppliers', ['Id' => $house->id_Home, 'title' => $title])}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="inputName" class="lineForHouse col-4" style="padding-top: 10px">Supplier name: *</label>
            <input type="text" class="form-control col-7" id="inputName" name="Name" value="{{old('Name')}}">
        </div>
        <div class="form-group row">
            <label for="inputType" class="lineForHouse col-4">Type: *</label>
            <select style="margin-right: 10px; margin-bottom: 10px" id="inputType" class="form-control col-4" name="fk_type_id">
                <option value="{{ old('fk_type_id') }}">-------------</option>
                @foreach($types as $type)
                        <option value="{{$type->type_id}} {{ old('fk_type_id') == $type->type_id ? 'selected' : '' }}">{{$type->Name}}</option>
                @endforeach
            </select>
            <?php
            $title = 'Supplier type';
            ?>
            <a class="btn btn-secondary col-3" style="float: right; max-height: 40px" href="{{ route('manageType', ['Id' => $house->id_Home, 'title' => $title]) }}">
                Manage types</a>
        </div>
        <div class="form-group row">
            <label for="inputAddress" class="lineForHouse col-4">Address: *</label>
            <input type="text" class="form-control col-7" id="inputAddress" name="Address" value="{{old('Address')}}">
        </div>
        <div class="form-group row">
            <label for="inputPhone" class="lineForHouse col-4">Phone number:</label>
            <input type="text" class="form-control col-7" id="inputPhone" name="Phone" value="{{old('Phone')}}">
        </div>
        <div class="form-group row" class="lineForHouse col-4">
            <label for="inputCity" class="lineForHouse col-4">City:</label>
            <input type="text" class="form-control col-7" id="inputCity" name="City" value="{{old('City')}}">
        </div>
        <p class="requiredfield">* Required fields</p>
        <div style="padding-left: 15px">
            <button type="submit" class="btn btn-info">Add Supplier</button>
        </div>
    </form>
        </div>
    @endif
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
</style>
