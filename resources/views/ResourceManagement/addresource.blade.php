<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
    <div class="container-fluid"  style="padding-bottom: 50px">
            <div class="row mt-2">
                <div class="col" style="max-width: 10px">
                    <a style="position: center" href="{{URL::previous()}}">
                        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>

                <div class="container-fluid" style="padding-bottom: 20px">
                    <div class="row mt-4">
                        <h3 style="margin-left: 14px;">Add resource</h3>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-8">
                        <form role="form" method="POST" action="{{route('addResource2', ['Id' => $house->id_Home])}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

{{--                                <div class="form-group">--}}
{{--                                    <label for="fk_Stock_card">Stock card</label>--}}
{{--                                    <select id="fk_Stock_card" name="fk_Stock_card" class="form-control">--}}
{{--                                        <option>Choose..</option>--}}
{{--                                        <option>...</option>--}}
{{--                                        @foreach($cards as $c)--}}
{{--                                            <option value="{{$c->id_Stock_card}}">{{$c->Name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                            <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Resource type*</label>
                                <select class=" selectpicker form-control" data-live-search="true" name="type" id="type" >
                                    <option value="0">Select</option>
                                    @foreach($stockTypes as $type)
{{--                                        @if(session()->has('stockCardID'))--}}
{{--                                            <option value="{{$category->id_Stock_card}}" {{ session('stockCardID') == $category->id_Stock_card ? 'selected' : ''}}>{{ $category->Name }}</option>--}}
{{--                                        @else--}}
                                            <option value="{{ $type->id_Stock_type}}">{{ $type->Type_name }}</option>
{{--                                        @endif--}}
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Resource card*</label>
                                <select class="form-control selectpicker" name="category" id="category"  data-live-search="true">
                                    <option value="0">Select</option>
{{--                                    @foreach($cards as $category)--}}
{{--                                        @if(session()->has('stockCardID'))--}}
{{--                                        <option value="{{$category->id_Stock_card}}" {{ session('stockCardID') == $category->id_Stock_card ? 'selected' : ''}}>{{ $category->Name }}</option>--}}
{{--                                        @else--}}
{{--                                            <option value="{{ $category->id_Stock_card }}">{{ $category->Name }}</option>--}}
{{--                                            @endif--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label>Batch</label>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6">--}}
{{--                                      <select name="sub_category" id="subCategory" class="form-control">--}}
{{--                                        <option value="0">Select batch</option>--}}
{{--                                      </select>--}}
{{--                                     </div>--}}
{{--                                <div class="col-6">--}}
{{--                                    <a class="btn btn-secondary " href="{{ route('addBatch', ['Id' => $house->id_Home]) }}">--}}
{{--                                        <i class="fa fa-plus" aria-hidden="true"></i> Add Batch</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                            <div class="form-group">
                                <label for="batch1">Batch no.*</label>
                                <input required type="text" name="batch1" class="form-control" id="batch1"
                                       placeholder="Enter batch number">
                            </div>

                            <div class="form-group">
                                <label for="expiration_date">Expiration date</label>
                                <input type="date" class="form-control" name="expiration_date" id="expiration_date">
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="fk_Warehouse_place">Warehouse place</label>--}}
{{--                                <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control">--}}
{{--                                    <option >Select place</option>--}}
{{--                                    @foreach($places as $p)--}}
{{--                                        <option value="{{$p->id_Warehouse_place}}">{{$p->Warehouse_name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="fk_Warehouse_place">Warehouse place: <span style="font-weight: bold" id="topPlace"></span></label>--}}
{{--                                <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control" required>--}}
{{--                                    <option value=" ">Select place</option>--}}
{{--                                    @foreach($topPlaces as $p)--}}
{{--                                        <option value="{{$p->id_Warehouse_place}}" data-valuea="{{$p->Warehouse_name}}">{{$p->Warehouse_name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label for="fk_Warehouse_place">Warehouse place* <span style="font-weight: bold" id="topPlace"></span></label>
                                <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control selectpicker" data-live-search="true"
                                required>
                                    <option value=" ">Select</option>
                                    @foreach($topPlaces as $p)
                                        <option value="{{$p->id_Warehouse_place}}" data-valuea= "{{$p->Warehouse_name}}">{{$p->Warehouse_name}}</option>
                                    @endforeach
                                </select>
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="fk_Batch">Batch</label>--}}
{{--                                <div class="row">--}}

{{--                                    <div class="col-6">--}}
{{--                                        <select id="fk_Batch" name="fk_Batch" class="form-control">--}}
{{--                                            <option>Choose...</option>--}}
{{--                                            @foreach($batches as $b)--}}
{{--                                                <option value="{{$b->id_Stock_batch}}">{{$b->number}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6">--}}
{{--                                        <a class="btn btn-secondary " href="{{ route('addBatch', ['Id' => $house->id_Home]) }}">--}}
{{--                                            <i class="fa fa-plus" aria-hidden="true"></i> Add Batch</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group">
                                <label for="quantity">Quantity <span id="measurement"></span>*</label>
                                <input type="number" step="0.01" name="quantity" min="0.01" class="form-control" id="quantity" placeholder="0.00">
                            </div>


{{--                            <div class="form-group">--}}
{{--                                <label for="expiration_date">Expiration date</label>--}}
{{--                                <input type="date" class="form-control" name="expiration_date" id="expiration_date"--}}
{{--                                @if(session()->has('recipeName'))--}}
{{--                                    value="{{\Carbon\Carbon::today()->addDays(session('recipeName')->TimeTillExpiry)->toDateString()}}"--}}
{{--                                    @endif>--}}
{{--                            </div>--}}

                            <div class="form-group">
                            <label for="inlineRadio1">Action type</label> <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio1" value="2">
                                <label class="form-check-label" for="inlineRadio1">Received</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio2" value="1">
                                <label class="form-check-label" for="inlineRadio2">Purchase</label>
                            </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio3"
                                           required value="8">
                                    <label class="form-check-label" for="inlineRadio3">Made</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-secondary ">Add resource</button>
                        </form>
                        </div>

                    </div>
                </div>

    </div>

            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{--            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}


            <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>

            <script type='text/javascript'>
                $(document).ready(function()
                {
                    $('#topPlace').on('click', function()
                    {
                        $('#topPlace').html('');
                        var house = "{!! addcslashes($house->id_Home, '"') !!}";
                        // $('#fk_Warehouse_place').find('option').not(':first').remove();
                        $('#fk_Warehouse_place').find('option').remove();
                        var option = "<option value='"+''+"' data-valuea='"+''+"'>"+'Select'+"</option>";
                        $("#fk_Warehouse_place").append(option);

                        $.ajax({
                            url: '../getAllPlaces/'+ house,
                            type: "get",
                            dataType: "json",
                            success: function(response){

                                var len = 0;
                                if(response['data'] != null){
                                    len = response['data'].length;
                                }
// alert(len);
                                if(len > 0){
                                    // Read data and create <option >
                                    for(var i=0; i<len; i++){

                                        var id_Stock_batch = response['data'][i].id_Warehouse_place;
                                        var number = response['data'][i].Warehouse_name;

                                        var option = "<option value='"+id_Stock_batch+"' data-valuea='"+number+"'>"+number+"</option>";
                                        // alert(option);
                                        $("#fk_Warehouse_place").append(option);
                                        // $("#topPlace").append(number);
                                    }
                                }
                                $('#fk_Warehouse_place').selectpicker('refresh');
                            }
                        });
                    });
                });


                $(document).ready(function(){
                    // Department Change
                    $('#type').change(function(){
                        // Department id
                        var id = $(this).val();
                        var house= "{!! addcslashes($house->id_Home, '"') !!}";
                        // Empty the dropdown
                        $('#category').find('option').not(':first').remove();
                        $('#batch1').val('');
                        // AJAX request
                        $.ajax({
                            url: '../getResourceCards/'+ house +'/'+ id,
                            type: "get",
                            dataType: "json",
                            success: function(response){
                                var len = 0;
                                if(response['data'] != null){
                                    len = response['data'].length;
                                }
                                if(len > 0){
                                    // Read data and create <option >
                                    for(var i=0; i<len; i++){

                                        var id_Stock_card = response['data'][i].id_Stock_card;
                                        var Name = response['data'][i].Name;

                                        var option = "<option value='"+id_Stock_card+"'>"+Name+"</option>";

                                        $("#category").append(option);
                                    }
                                    // $('#category').selectpicker('refresh');

                                }
                                $('#category').selectpicker('refresh');
                            }
                        });
                    });
                });

                $(document).ready(function () {
                    // Department Change
                    $('#batch1').change(function () {
                        // Department id
                        var b = $('#batch1').val();
                        var c = $('#category').val();
                        // alert(c);

                        var house = "{!! addcslashes($house->id_Home, '"') !!}";
                        {{--var resource = "{!! addcslashes($card->id_Stock_card, '"') !!}";--}}

                        // alert(house,b);
                        // Empty the dropdown
                        $('#expiration_date').find('input').remove();
                        $('#expiration_date').val('');
                        $("#expiration_date").prop("readonly", false);

                        // AJAX request
                        $.ajax({
                            url: '../../checkBatch/' + house + '/' + c + '/' + b,
                            type: "get",
                            dataType: "json",
                            success: function (response) {

                                var len = 0;
                                if (response['data'] != null) {
                                    len = response['data'].length;
                                }
                                // alert(len);
                                if (len > 0) {
                                    // Read data and create <option >
                                    for (var i = 0; i < len; i++) {

                                        var expiration = response['data'][i].expiration_date;
                                        var b = response['data'][i].fk_Batch;
                                        // alert(expiration);

                                        // var number = response['data'][i].number;

                                        // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                        // $("#subCategory").append(option);
                                        $("#expiration_date").val(expiration);
                                        // alert(expiration);
                                        $("#expiration_date").prop("readonly", true);
                                    }
                                }

                            }
                        });
                    });

                });

                $(document).ready(function(){
                    // Department Change
                    $('#fk_Warehouse_place').change(function(){
                        // Department id
                        var id = $(this).val();
                        var selected_option = $(this).find(":selected").data("valuea");
                        $("#topPlace").append(selected_option);

                        // alert(selected_option);
                        // $("#topPlace").append(name);


                        var house= "{!! addcslashes($house->id_Home, '"') !!}";
                        // Empty the dropdown

                        // AJAX request
                        $.ajax({
                            url: '../../getChildPlaces/'+ house +'/'+ id,
                            type: "get",
                            dataType: "json",
                            success: function(response){

                                var len = 0;
                                if(response['data'] != null){
                                    len = response['data'].length;
                                }
                                $('#fk_Warehouse_place option:not(:selected)').remove();
                                if(len > 0){
                                    // Read data and create <option >
                                    for(var i=0; i<len; i++){

                                        var id_Stock_batch = response['data'][i].id_Warehouse_place;
                                        var number = response['data'][i].Warehouse_name;
                                        if(number!=null) {
                                            // $('#fk_Warehouse_place').find('option').not(':first').remove();
                                            var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>" + number + "</option>";

                                            $("#fk_Warehouse_place").append(option);
                                        }

                                    }
                                }
                                else{
                                    // $('#fk_Warehouse_place').find('option').not(':first').remove();
                                    // var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>NoMore</option>";
                                    // $("#fk_Warehouse_place").append(option);
                                }
                                $('#fk_Warehouse_place').selectpicker('refresh');
                            }
                        });
                    });

                });

                $(document).ready(function(){
                    // Department Change
                    $('#category').change(function(){
                        // Department id
                        var id = $(this).val();
                        var house= "{!! addcslashes($house->id_Home, '"') !!}";
                        // Empty the dropdown
                        $('#subCategory').find('option').not(':first').remove();
                        $('#batch1').val('');
                        // AJAX request
                        $.ajax({
                            url: '../getCategory/'+ house +'/'+ id,
                            type: "get",
                            dataType: "json",
                            success: function(response){

                                var len = 0;
                                if(response['data'] != null){
                                    len = response['data'].length;
                                }
// alert(len);
                                if(len > 0){
                                    // Read data and create <option >
                                    for(var i=0; i<len; i++){

                                        // var id_Stock_batch = response['data'][i].id_Stock_batch;
                                        var number = response['data'][i].measurement_unit;
                                        // $('#measurement').val(number);
                                        $('#measurement').empty();
                                        $("#measurement").append('('+number+')');

                                        // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                        // $("#subCategory").append(option);

                                    }
                                }

                            }
                        });
                    });
                });



            </script>


{{--     --}}
@endsection



