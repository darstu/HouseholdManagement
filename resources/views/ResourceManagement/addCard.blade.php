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
    @if($title == 'Stock card')
        <a style="position: center" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h2>Create resource card</h2>
        <div class="card col-lg-6 col-sm-8" style="margin-bottom: 10px; padding-top: 20px">
            <form role="form" method="POST" action="{{route('addCard', ['Id' => $house->id_Home, 'title' => $title])}}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="lineForHouse col-4">Name: *</label>
                    @if(session()->has('recipeName'))
                        <input type="text" class="form-control col-7" id="inputName" name="Name"
                               value="{{old('Name',session('recipeName')->Name)}}">
                    @else
                        <input type="text" class="form-control col-7" id="inputName" name="Name"
                               value="{{old('Name')}}">
                    @endif
                </div>
                <div class="form-group row">
                    <label for="inputDescription" class="lineForHouse col-4">Description:</label>
                    <input type="text" class="form-control col-7" id="inputDescription" name="Description"
                           value="{{old('Description')}}">
                </div>
                <div class="form-group row">
                    <label for="inputType" class="lineForHouse col-4">Type: *</label>
                    <select style="margin-bottom: 10px" id="inputType" class="form-control col-4" name="fk_Stock_type">
                        <option value="{{ old('fk_type_id') }}">------------</option>
                        @foreach($stock_types as $tp)
                            <option value="{{$tp->id_Stock_type}}">{{$tp->Type_name}}</option>
                        @endforeach
                    </select>
                    <?php
                    $title = 'Stock type';
                    ?>
                    <a class="btn btn-secondary col-3 " style="float: right; margin-left: 10px; max-height: 40px"
                       href="{{ route('manageType', ['Id' => $house->id_Home, 'title' => $title]) }}">
                        Manage types</a>
                </div>
                <div class="form-group row">
                    <label for="inputAddress" class="lineForHouse col-4">Measurement: *</label>
                    <input type="text" class="form-control col-7" id="inputAddress" name="measurement_unit"
                           value="{{old('measurement_unit')}}">
                </div>

                <div class="form-group row">
                    <label for="image" class="lineForHouse col-4">Upload a photo</label>
                    <input type="file" class="col-7" id="image" accept="image/jpg" name="image" value="{{old('image')}}">
                </div>

                <p class="requiredfield">* Required fields</p>
                <div style="padding-left: 15px">
                    <button type="submit" class="btn btn-info">Add resource</button>
                </div>
        </div>
        </form>
    @elseif($title == 'Stock type')
        <a style="position: center" href="{{URL::previous()}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h3>Add resource type</h3>
        <br>
        <div class="card col-lg-6 col-sm-8" style="padding-top: 15px">
            <form role="form" method="POST" action="{{route('addCard', ['Id' => $house->id_Home, 'title' => $title])}}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="lineForHouse col-4">Name: *</label>
                    <input type="text" class="form-control col-7" id="inputName" name="Type_name"
                           value="{{old('Type_name')}}">
                </div>
                <div class="form-group row">
                    <label for="inputDescription" class="lineForHouse col-4">Description</label>
                    <input type="text" class="form-control col-7" id="inputDescription" name="Type_description"
                           value="{{old('Type_description')}}">
                </div>
                <p class="requiredfield">* Required fields</p>
                <div style="padding-left: 15px">
                    <button type="submit" class="btn btn-info">Add type</button>
                </div>
            </form>
        </div>
    @else
        <a style="position: center" href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h2>Create storage</h2>
        <br>
        <div class="card col-lg-6 col-sm-8" style="margin-bottom: 10px; padding-top: 20px">
            <form role="form" method="POST" action="{{route('addCard', ['Id' => $house->id_Home, 'title' => $title])}}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="lineForHouse col-4">Name: *</label>
                    <input type="text" class="form-control col-7" id="inputName" name="Warehouse_name"
                           value="{{old('Warehouse_name')}}">
                </div>
                <div class="form-group row">
                    <label for="inputAddress" class="lineForHouse col-4">Address:</label>
                    <input type="text" class="form-control col-7" id="inputAddress" name="Address"
                           value="{{old('Address')}}">
                </div>
                <div class="form-group row">
                    <label for="inputDescription" class="lineForHouse col-4">Description:</label>
                    <input type="text" class="form-control col-7" id="inputDescription" name="Description"
                           value="{{old('Description')}}">
                </div>
                <div class="form-group row">
                    <label for="inputType" class="lineForHouse col-4">Place:</label>
                    <select style="width: 100%; margin-bottom: 10px" id="inputType" class="form-control col-7"
                            name="fk_Warehouse_place">
                        <option value="">------------</option>
                        @foreach($allwares as $wares)
                            <?php
                            $l = 0;
                            ?>
                            @foreach($lockedwarehouse as $locked)
                                @if($locked->warehouse_id == $wares->id_Warehouse_place or $locked->warehouse_id == $wares->fk_Warehouse_place)
                                    <?php
                                    $l = $l + 1;
                                    ?>
                                @endif
                            @endforeach
                            @if($wares->removed != 1 and $l == 0)
                                <option value="{{$wares->id_Warehouse_place}}">{{$wares->Warehouse_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <p class="requiredfield">* Required fields</p>
                <div style="padding-left: 15px">
                    <button type="submit" class="btn btn-info">Add storage</button>
                </div>
        </div>
        </form>

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
