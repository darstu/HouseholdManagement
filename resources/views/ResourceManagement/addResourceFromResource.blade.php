<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<style>
    select {
        background-image:
            linear-gradient(45deg, transparent 50%, #495057 60%),
            linear-gradient(135deg, #495057 40%, transparent 50%) !important;
        background-position: calc(100% - 15px) 15px, calc(100% - 10px) 15px, 100% 0;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        -webkit-appearance: none;
        -moz-appearance: none;

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
    <div class="container-fluid" style="padding-bottom: 50px">
        <div class="row mt-2">
            <div class="col" style="max-width: 10px">
                <a style="position: center" href="{{URL::previous()}}">
                    <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16"
                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z"
                              clip-rule="evenodd"/>
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
                    <form role="form" method="POST"
                          action="{{route('addResourceFromResource', ['Id' => $house->id_Home, 'id_resource'=>$card->id_Stock_card])}}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label for="Stock_card">Resource*</label>
                            <input type="text" class="form-control" name="category" id="category"
                                   value="{{$card->Name}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="batch1">Batch*</label>
                            <input required type="text" name="batch1" class="form-control" id="batch1"
                                   placeholder="Enter batch number">
                        </div>

                        <div class="form-group">
                            <label for="expiration_date">Expiration date</label>
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date">
                        </div>

                        <div class="form-group">
                            <label for="fk_Warehouse_place">Warehouse place* <span style="font-weight: bold" id="topPlace"></span></label>
                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control selectpicker" data-live-search="true" required>
                                <option value=" ">Select place</option>
                                @foreach($topPlaces as $p)
                                    <option value="{{$p->id_Warehouse_place}}" data-valuea="{{$p->Warehouse_name}}">{{$p->Warehouse_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity ({{$card->measurement_unit}})*</label>
                            <input required type="number" step="0.01" name="quantity" min="0.01" class="form-control"
                                   id="quantity" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="inlineRadio1">Action type</label> <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio1"
                                       value="2">
                                <label class="form-check-label" for="inlineRadio1">Received</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio2"
                                       value="1">
                                <label class="form-check-label" for="inlineRadio2">Purchase</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio3"
                                       required value="8"
                                       @if(session()->has('actionType'))
                                       checked
                                    @endif
                                >
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
{{--                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>
{{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
{{--    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
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
                        url: '../../getAllPlaces/'+ house,
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


        $(document).ready(function () {
            // Department Change
            $('#batch1').change(function () {
                // Department id
                var b = $('#batch1').val();
                // alert(b);

                var house = "{!! addcslashes($house->id_Home, '"') !!}";
                var resource = "{!! addcslashes($card->id_Stock_card, '"') !!}";

                // alert(house,b);
                // Empty the dropdown
                $('#expiration_date').find('input').remove();
                $('#expiration_date').val('');
                $("#expiration_date").prop("readonly", false);

                // AJAX request
                $.ajax({
                    url: '../../checkBatch/' + house + '/' + resource + '/' + b,
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
                var name = $("select option:selected").data('valuea');

                $("#topPlace").append(name);
                // $("#topPlace").append(name);

                // alert(name);
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
// alert(len);
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
                                    // $("#topPlace").append(number);
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

    </script>



@endsection

<script>

</script>
